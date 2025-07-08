from typing import List, Dict, Any
from datetime import datetime, timezone
from flask_app.config import Config

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

    def check_plagiarism(self, chunked_text_list: Dict[str, str], document_word_count) -> Dict[str, Any]:
        """Process pre-chunked paragraphs and return a structured plagiarism report"""
        print("   👉 Searching to database")
        chunked_text_results = []

        for chunk_id, chunked_text in chunked_text_list.items():
            if len(chunked_text.split()) < getattr(Config, "MIN_CHUNKED_TEXT_WORD", 3):
                continue  # Skip short paragraphs

            result = self.check_plagiarism_by_chunk(chunked_text)
            result["id"] = chunk_id
            chunked_text_results.append(result)

        report = self.generate_report(chunked_text_results, document_word_count)
        return report

    def check_plagiarism_by_chunk(self, chunked_text: str) -> Dict[str, Any]:
        """Check plagiarism for a single paragraph"""
        clean_text, processed_text = self.text_service.preprocess_text(chunked_text)
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
            if similarity >= getattr(Config, "SIMILARITY_THRESHOLD", 0.8) * 100:
                sources.append({
                    "similarity_percentage": similarity,
                    "sentence_id": hit['entity'].get("sentence_id", ""),
                    "document_id": hit['entity'].get("document_id", ""),
                    "subject_code": hit['entity'].get("subject_code", ""),
                    "title": hit['entity'].get("original_name", ""),
                    "raw_text": hit['entity'].get("raw_text", ""),
                    "chunk_word_count": len(clean_text.split()),
                    "document_word_count": hit['entity'].get("document_word_count", 0),
                })

        return {
            "text": chunked_text,
            "similarity_percentage": max((s['similarity_percentage'] for s in sources), default=0.0),
            "sources": sources,
            "chunk_word_count": len(clean_text.split())
        }

    def generate_report(self, chunked_text_results: List[Dict[str, Any]], document_word_count) -> Dict[str, Any]:
        """Generate the final report in the specified format"""
        print("   👉 Generate report data")
        # ✅ 1. Calculate total words analyzed
        words_analyzed = 0
        for chunk_result in chunked_text_results:
            if chunk_result['sources']:
                words_analyzed += chunk_result.get('chunk_word_count', 0)

        # ✅ 2. Calculate overall similarity and originality score
        overall_similarity = self.calculate_similar_percent(words_analyzed, document_word_count)
        originality_score = round(100.0 - overall_similarity, 1)

        # ✅ 3. Calculate sources summary
        source_map = {}
        for chunk_result in chunked_text_results:
            for source in chunk_result['sources']:
                key = f"{source.get('document_id', '')}::{source['title']}"
                if key not in source_map:
                    source_map[key] = {
                        "document_id": source.get('document_id', ''),
                        "url": source.get('url', ''),
                        "title": source['title'],
                        "total_matched": 0,
                        "matched_word_count": 0,  # FIX: add per-source matched word count
                        "source_similarity": 0,
                        "document_word_count": source.get('document_word_count', 0),
                        "matches": []
                    }

                # Add match details
                source_map[key]['matches'].append({
                    'sentence_id': chunk_result['id'],
                    'similarity': source['similarity_percentage'],
                    'chunk_word_count': chunk_result.get('chunk_word_count', 0)
                })

                # Update statistics
                source_map[key]['total_matched'] += 1
                source_map[key]['matched_word_count'] += chunk_result.get('chunk_word_count', 0)  # accumulate matched words

        # ✅ 4. Calculate similarity percent for sources
        for key, data in source_map.items():
            data['source_similarity'] = self.calculate_similar_percent(
                data['matched_word_count'], data.get('document_word_count', 0)
            )

        # ✅ 5. Sort and take top sources
        sources_summary = list(source_map.values())
        sources_summary.sort(key=lambda x: (-x['source_similarity'], -x['total_matched']))
        top_sources = sources_summary[:getattr(Config, "MAX_MATCHED_SOURCE", 10)]
        top_source_keys = [
            f"{src['document_id']}::{src['title']}" for src in top_sources
        ]

        # ✅ 6. Assign color indexes
        color_indices = list(getattr(Config, "HIGHLIGHT_COLORS", {}).keys())
        if getattr(Config, "IS_RANDOM_COLOR", False):
            import random
            random.shuffle(color_indices)
        source_color_index_map = {
            key: color_indices[i]
            for i, key in enumerate(top_source_keys)
            if i < len(color_indices)
        }

        # ✅ 7. Add color index to sources_summary
        for src in sources_summary:
            key = f"{src.get('document_id', '')}::{src['title']}"
            src['color_index'] = source_color_index_map.get(key, -1)

        # ✅ 8. Add color index to every source in paragraph
        for para in chunked_text_results:
            for src in para.get('sources', []):
                key = f"{src.get('document_id', '')}::{src['title']}"
                src['color_index'] = source_color_index_map.get(key, -1)

        # ✅ 9. Clean up matches
        for source in sources_summary:
            del source['matches']
            del source['matched_word_count']

        return {
            "status": "success",
            "data": {
                "originality_score": originality_score,
                "similarity_score": overall_similarity,
                "source_matched": len(sources_summary),
                "words_analyzed": words_analyzed,
                "overall_verdict": self.get_verdict(overall_similarity),
                "processed_at": datetime.now(timezone.utc).isoformat(),
                "paragraphs": chunked_text_results,
                "sources_summary": sources_summary[:getattr(Config, 'MAX_MATCHED_SOURCE', 10)]
            }
        }, source_color_index_map
    
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
        percent = max(0.1, min(percent, 100.0))

        # print(f"Matched Words: {matched_words}, Uploaded Words: {uploaded_words}", "Percent:", percent)
        return round(percent, 1)