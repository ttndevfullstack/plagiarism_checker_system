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
        self.similarity_threshold = 0.3

    def check_plagiarism_content(self, content: Dict[str, str]) -> Dict[str, Any]:
        """Process pre-chunked paragraphs and return a structured plagiarism report"""
        paragraph_results = []

        for chunk_id, paragraph in content.items():
            if len(paragraph.strip()) < self.min_paragraph_length:
                continue  # Skip short paragraphs

            result = self.check_plagiarism_paragraph(paragraph)
            result["id"] = chunk_id
            paragraph_results.append(result)

        report = self.generate_report(paragraph_results)
        print("✅ Plagiarism check completed successfully")
        return report

    def check_plagiarism_paragraph(self, paragraph: str) -> Dict[str, Any]:
        """Check plagiarism for a single paragraph"""
        processed_paragraph = self.text_service.preprocess_text(paragraph)
        embedding = self.embedding_service.convert_text_to_embedding(processed_paragraph)

        search_params = {
            "metric_type": "COSINE",
            "offset": 0,
            "limit": 5,
            "params": {
                "ef": 64
            }
        }

        results = self.client.search(
            collection_name="documents",
            data=[embedding],
            anns_field="embedding",
            output_fields=["document_id", "paragraph_id", "subject_code", "original_name", "source_url", "published_date"],
            **search_params
        )
        print("Search Results:", results)

        sources = []
        for hit in results[0]:
            similarity = round(max(0.0, min(1.0, 1 - hit['distance'])) * 100, 1)
            if similarity >= self.similarity_threshold * 100:
                sources.append({
                    "document_id": hit['entity'].get("document_id", ""),
                    "url": hit['entity'].get("source_url", ""),
                    "title": hit['entity'].get("original_name", "Unknown Source"),
                    "similarity_percentage": similarity,
                    "published_date": hit['entity'].get("published_date", "")
                })

        return {
            "text": paragraph,
            "similarity_percentage": max((s['similarity_percentage'] for s in sources), default=0.0),
            "sources": sources
        }

    def generate_report(self, paragraph_results: List[Dict[str, Any]]) -> Dict[str, Any]:
        """Generate the final report in the specified format"""
        # Calculate maximum similarity across paragraphs
        # Calculate weighted similarity across paragraphs
        words_analyzed = 0
        weighted_similarity = 0.0
        
        for para in paragraph_results:
            words = len(para['text'].split())
            words_analyzed += words
            weighted_similarity += para['similarity_percentage'] * words
        
        # Calculate overall similarity (weighted average)
        overall_similarity = round(weighted_similarity / words_analyzed, 1) if words_analyzed > 0 else 0.0

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
                        "highest_similarity": 0,
                        "matches": []
                    }

                # Add match details
                source_map[key]['matches'].append({
                    'paragraph_id': para['id'],
                    'similarity': source['similarity_percentage']
                })

                # Update statistics
                source_map[key]['total_matched'] += 1
                source_map[key]['highest_similarity'] = max(
                    source_map[key]['highest_similarity'],
                    source['similarity_percentage']
                )

        # Convert source_map to list and sort by highest similarity
        sources_summary = list(source_map.values())
        sources_summary.sort(key=lambda x: (-x['highest_similarity'], -x['total_matched']))
        # words_analyzed = sum(len(p['text'].split()) for p in paragraph_results)
        originality_score = round(100.0 - overall_similarity, 1)  # Use max similarity for originality score

        # Clean up matches from final output (optional)
        for source in sources_summary:
            del source['matches']
        
        # Updated professional verdict messages
        if overall_similarity > 75:
            verdict = ("🔴 Critical Match Level (75-100%)\n"
                      "Your content shows significant matching text with existing sources. "
                      "We strongly recommend immediate revision to ensure academic integrity.")
        elif overall_similarity > 50:
            verdict = ("🟠 High Match Level (50-74%)\n"
                      "Notable similarities detected with other sources. "
                      "Consider revising sections with high similarity to improve originality.")
        elif overall_similarity > 25:
            verdict = ("🟡 Moderate Match Level (25-49%)\n"
                      "Some matching content detected. Review highlighted sections "
                      "and ensure proper citations where needed.")
        elif overall_similarity > 0:
            verdict = ("🟢 Low Match Level (1-24%)\n"
                      "Minor matches found. Content appears largely original "
                      "with few common phrases or properly cited materials.")
        else:
            verdict = ("✅ No Matches Found (0%)\n"
                      "No significant matching content detected. "
                      "Your work appears to be highly original.")

        return {
            "status": "success",
            "data": {
                "originality_score": originality_score,
                "similarity_score": overall_similarity,
                "source_matched": len(sources_summary),
                "words_analyzed": words_analyzed,
                "overall_verdict": verdict,
                "processed_at": datetime.now(timezone.utc).isoformat(),
                "paragraphs": paragraph_results,
                "sources_summary": sources_summary
            }
        }
