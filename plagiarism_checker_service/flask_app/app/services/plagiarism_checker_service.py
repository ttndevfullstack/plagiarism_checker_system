from typing import List, Dict, Any
from datetime import datetime, timezone

from flask_app.app.services.file_handler import FileHandler
from flask_app.app.databases.milvus_connection import MilvusConnection
from flask_app.app.services.process_text_service import ProcessTextService
from flask_app.app.factories.embedding_model_factory import EmbeddingModelFactory

class PlagiarismCheckerService:
    def __init__(self, embedding_model: str):
        self.file_handler = FileHandler()
        self.text_service = ProcessTextService()
        self.client = MilvusConnection.get_client()
        self.embedding_service = EmbeddingModelFactory.get_model(embedding_model)

        self.min_paragraph_length = 100
        self.similarity_threshold = 0.8

    def check_plagiarism_content(self, content: Dict[str, str], total_words) -> Dict[str, Any]:
        """Process pre-chunked paragraphs and return a structured plagiarism report"""
        paragraph_results = []

        for chunk_id, paragraph in content.items():
            if len(paragraph.strip()) < self.min_paragraph_length:
                continue  # Skip short paragraphs

            result = self.check_plagiarism_paragraph(paragraph)
            result["id"] = chunk_id
            paragraph_results.append(result)

        report = self.generate_report(paragraph_results, total_words)
        print("✅ Plagiarism check completed successfully")
        return report

    def check_plagiarism_paragraph(self, paragraph: str) -> Dict[str, Any]:
        """Check plagiarism for a single paragraph"""
        clean_text, processed_text = self.text_service.preprocess_text(paragraph)
        embedding = self.embedding_service.convert_text_to_embedding(processed_text)

        search_params = {
            "metric_type": "COSINE",
            "offset": 0,
            "limit": 10,
            "params": {
                "ef": 128
            }
        }

        results = self.client.search(
            collection_name="documents",
            data=[embedding],
            anns_field="embedding",
            output_fields=[
                "sentence_id",
                "document_id",
                "subject_code",
                "original_name",
                "raw_text",
                "document_word_count",
            ],
            **search_params
        )
        # print("Search Results:", results)

        sources = []
        for hit in results[0]:
            similarity = round(max(0.0, min(1.0, hit['distance'])) * 100, 1)
            if similarity >= self.similarity_threshold * 100:
                sources.append({
                    "similarity_percentage": similarity,
                    "sentence_id": hit['entity'].get("sentence_id", ""),
                    "document_id": hit['entity'].get("document_id", ""),
                    "subject_code": hit['entity'].get("subject_code", ""),
                    "title": hit['entity'].get("original_name", ""),
                    "raw_text": hit['entity'].get("raw_text", ""),
                    "matched_word_count": len(clean_text.split()),
                    "document_word_count": hit['entity'].get("document_word_count", 0),
                })

        return {
            "text": paragraph,
            "similarity_percentage": max((s['similarity_percentage'] for s in sources), default=0.0),
            "sources": sources,
            "matched_word_count": len(clean_text.split())
        }

    def generate_report(self, paragraph_results: List[Dict[str, Any]], total_words) -> Dict[str, Any]:
        """Generate the final report in the specified format"""
        # Calculate maximum similarity across paragraphs
        # Calculate weighted similarity across paragraphs
        words_analyzed = 0
        
        # Calculate total words analyzed from paragraphs that have matches
        for para in paragraph_results:
            if para['sources']:
                words_analyzed += para.get('matched_word_count', 0)
        
        # Calculate overall similarity
        overall_similarity = self.calculate_similar_percent(words_analyzed, total_words)

        # Generate sources summary - Modified to handle multiple sources properly
        source_map = {}
        for para in paragraph_results:
            for source in para['sources']:
                key = f"{source.get('document_id', '')}::{source['title']}"
                if key not in source_map:
                    source_map[key] = {
                        "document_id": source.get('document_id', ''),
                        "url": source.get('url', ''),
                        "title": source['title'],
                        "total_matched": 0,
                        "source_similarity": 0,
                        "document_word_count": source.get('document_word_count', 0),
                        "matches": []
                    }

                # Add match details
                source_map[key]['matches'].append({
                    'sentence_id': para['id'],
                    'similarity': source['similarity_percentage']
                })

                # Update statistics
                source_map[key]['total_matched'] += 1
                source_map[key]['source_similarity'] = self.calculate_similar_percent(words_analyzed, source.get('document_word_count', 0))

        # Convert source_map to list and sort by highest similarity
        sources_summary = list(source_map.values())
        sources_summary.sort(key=lambda x: (-x['source_similarity'], -x['total_matched']))
        originality_score = round(100.0 - overall_similarity, 1)  # Use max similarity for originality score

        # Clean up matches from final output (optional)
        for source in sources_summary:
            del source['matches']
        
        return {
            "status": "success",
            "data": {
                "originality_score": originality_score,
                "similarity_score": overall_similarity,
                "source_matched": len(sources_summary),
                "words_analyzed": words_analyzed,
                "overall_verdict": self.get_verdict(overall_similarity),
                "processed_at": datetime.now(timezone.utc).isoformat(),
                "paragraphs": paragraph_results,
                "sources_summary": sources_summary
            }
        }
    
    def get_verdict(self, similarity_score: float) -> str:
        if similarity_score > 75:
            return ("🔴 Critical Match Level (75-100%)\n"
                      "Your content shows significant matching text with existing sources. "
                      "We strongly recommend immediate revision to ensure academic integrity.")
        elif similarity_score > 50:
            return ("🟠 High Match Level (50-74%)\n"
                      "Notable similarities detected with other sources. "
                      "Consider revising sections with high similarity to improve originality.")
        elif similarity_score > 25:
            return ("🟡 Moderate Match Level (25-49%)\n"
                      "Some matching content detected. Review highlighted sections "
                      "and ensure proper citations where needed.")
        elif similarity_score > 0:
            return ("🟢 Low Match Level (1-24%)\n"
                      "Minor matches found. Content appears largely original "
                      "with few common phrases or properly cited materials.")
        else:
            return ("✅ No Matches Found (0%)\n"
                      "No significant matching content detected. "
                      "Your work appears to be highly original.")

    def calculate_similar_percent(self, matched_words, uploaded_words):
        if uploaded_words <= 0:
            return 0.0
        percent = (matched_words / uploaded_words) * 100
        percent = max(0.0, min(percent, 100.0))

        print(f"Matched Words: {matched_words}, Uploaded Words: {uploaded_words}", "Percent:", percent)
        return round(percent, 1)