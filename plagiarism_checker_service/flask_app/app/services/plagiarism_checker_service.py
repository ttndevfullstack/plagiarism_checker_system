from datetime import datetime, timezone
from typing import List, Dict, Any

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

        self.min_paragraph_length = 50
        self.similarity_threshold = 0.4

    def check_plagiarism(self, file) -> Dict[str, Any]:
        """Process file and check for plagiarism"""
        print("👉 Starting plagiarism check")
        
        # Extract and preprocess text
        file_path = self.file_handler.save_file(file)
        text = self.file_handler.extract_text_from_file(file_path)
        
        # Use text chunking instead of paragraphs
        chunks = self.text_service.split_into_chunks(text)
        
        # Process chunks in batches for efficiency
        batch_results = []
        for i in range(0, len(chunks), 5):  # Batch size of 5
            batch = chunks[i:i+5]
            batch_results.extend(self.process_paragraph_batch(batch))

        # Generate final report
        report = self.generate_report(batch_results)
        print("✅ Plagiarism check completed successfully")
        return report

    def check_plagiarism_content(self, content: Dict[str, str]) -> Dict[str, Any]:
      """Process pre-chunked paragraphs and return a structured plagiarism report"""
      print("👉 Starting plagiarism check for pre-parsed paragraphs")

      paragraph_results = []

      for chunk_id, paragraph in content.items():
          if len(paragraph.strip()) < self.min_paragraph_length:
              continue  # Skip short paragraphs

          result = self.check_plagiarism_paragraph(paragraph)
          result["id"] = chunk_id  # Assign original ID
          paragraph_results.append(result)

      # Generate report
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
          "params": {"nprobe": 16}
      }

      results = self.client.search(
          collection_name="documents",
          data=[embedding],
          anns_field="embedding",
          output_fields=["document_id", "subject_code", "original_name", "source_url", "published_date"],
          **search_params
      )

      sources = []
      for hit in results[0]:
          similarity = min(round((1 - hit['distance']) * 100, 1), 100.0)
          if similarity >= self.similarity_threshold * 100:
              sources.append({
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
        # Calculate overall similarity
        total_similarity = sum(p['similarity_percentage'] for p in paragraph_results)
        avg_similarity = min(total_similarity / len(paragraph_results), 100.0) if paragraph_results else 0.0
        
        # Generate sources summary - Modified to handle multiple sources properly
        source_map = {}
        for para in paragraph_results:
            for source in para['sources']:
                key = f"{source['url']}::{source['title']}"  # Create unique key for each source
                if key not in source_map:
                    source_map[key] = {
                        "url": source['url'],
                        "title": source['title'],
                        "total_matched": 0,
                        "highest_similarity": 0,
                        "matches": []  # Track all matches
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
        words_analyzed = sum(len(p['text'].split()) for p in paragraph_results)
        originality_score = round(100.0 - avg_similarity, 1)

        # Clean up matches from final output (optional)
        for source in sources_summary:
            del source['matches']
        
        # Rest of verdict logic remains the same
        if avg_similarity > 70:
            verdict = "High plagiarism detected. This content has substantial similarities with other sources."
        elif avg_similarity > 40:
            verdict = "Moderate plagiarism detected. This content has significant similarities with other sources."
        elif avg_similarity > 20:
            verdict = "Low plagiarism detected. Some similarities found with other sources."
        else:
            verdict = "Minimal plagiarism detected. Content appears mostly original."
        
        return {
            "status": "success",
            "data": {
                "originality_score": originality_score,
                "total_similarity_percentage": round(avg_similarity, 1),
                "overall_verdict": verdict,
                "source_count": len(sources_summary),
                "words_analyzed": words_analyzed,
                "processed_at": datetime.now(timezone.utc).isoformat(),
                "paragraphs": paragraph_results,
                "sources_summary": sources_summary
            }
        }
