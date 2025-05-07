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

    def check_plagiarism_content(self, content: str) -> Dict[str, Any]:
        """Process text content directly and check for plagiarism"""
        print("👉 Starting plagiarism check for content")
        
        # Use text chunking instead of paragraphs
        chunks = self.text_service.split_into_chunks(content)
        
        # Process chunks in batches
        batch_results = []
        for i in range(0, len(chunks), 5):  # Batch size of 5
            batch = chunks[i:i+5]
            batch_results.extend(self.process_paragraph_batch(batch))

        # Generate final report
        report = self.generate_report(batch_results)
        print("✅ Plagiarism check completed successfully")
        return report

    def split_into_paragraphs(self, text: str) -> List[str]:
        """Split text into meaningful paragraphs"""
        paragraphs = [p.strip() for p in text.split('\n\n') if p.strip()]
        meaningful_paras = [p for p in paragraphs if len(p) >= self.min_paragraph_length]
        return meaningful_paras

    def process_paragraph_batch(self, paragraphs: List[str]) -> List[Dict[str, Any]]:
        """Process a batch of paragraphs and search for matches"""
        # Preprocess and embed
        processed_paragraphs = [self.text_service.preprocess_text(p) for p in paragraphs]
        embeddings = self.embedding_service.convert_text_to_embedding(processed_paragraphs)
        
        # Search Milvus in batch
        search_params = {
            "metric_type": "COSINE",
            "offset": 0,
            "limit": 5,  # Top 5 matches per paragraph
            "params": {"nprobe": 16}
        }

        results = self.client.search(
            collection_name="documents",
            data=embeddings,
            anns_field="embedding",
            output_fields=["document_id", "subject_code", "original_name"],
            **search_params
        )

        # Process results
        paragraph_results = []
        for idx, (para, hits) in enumerate(zip(paragraphs, results)):
            para_id = f"para-{idx+1}"
            sources = []
            
            for hit in hits:
                # Fixed: Access distance as dictionary key instead of attribute
                similarity = 1 - hit['distance']  # Convert to similarity percentage
                if similarity >= self.similarity_threshold:
                    sources.append({
                        "url": hit['entity'].get("source_url", ""),
                        "title": hit['entity'].get("original_name", "Unknown Source"),
                        "similarity_percentage": round(similarity * 100, 1),
                        "published_date": hit['entity'].get("published_date", "")
                    })
            
            # Calculate paragraph similarity (max of all matches)
            para_similarity = max([s['similarity_percentage'] for s in sources], default=0)
            
            paragraph_results.append({
                "id": para_id,
                "text": para,
                "similarity_percentage": para_similarity,
                "sources": sources
            })
        
        return paragraph_results

    def generate_report(self, paragraph_results: List[Dict[str, Any]]) -> Dict[str, Any]:
        """Generate the final report in the specified format"""
        # Calculate overall similarity
        total_similarity = sum(p['similarity_percentage'] for p in paragraph_results)
        avg_similarity = total_similarity / len(paragraph_results) if paragraph_results else 0
        
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
                "total_similarity_percentage": round(avg_similarity, 1),
                "overall_verdict": verdict,
                "source_count": len(sources_summary),
                "processed_at": datetime.now(timezone.utc).isoformat(),
                "paragraphs": paragraph_results,
                "sources_summary": sources_summary
            }
        }
