import fitz  # PyMuPDF
import os
import tempfile
from flask import request, jsonify, send_file
from io import BytesIO
import pandas as pd

class PDFHighlighter:
    def __init__(self, input_pdf_path):
        self.input_pdf_path = input_pdf_path
        self.highlights = []
        self.current_number = 1
    
    def find_text_and_highlight(self, search_text, highlight_color=(1, 1, 0)):  # Yellow by default (RGB)
        """Find text in PDF and add it to highlights list"""
        doc = fitz.open(self.input_pdf_path)

        if search_text == "":
            # Add a default badge at a fixed position on the first page
            page_num = 0
            badge_rect = fitz.Rect(50, 50, 70, 70)  # Default position and size
            self.highlights.append({
                'page': page_num,
                'coordinates': badge_rect,
                'text': "",
                'color': highlight_color,
                'number': 1
            })
            doc.close()
            return self

        for page_num in range(len(doc)):
            page = doc.load_page(page_num)
            text_instances = page.search_for(search_text)
            
            for inst in text_instances:
                self.highlights.append({
                    'page': page_num,
                    'coordinates': inst,
                    'text': search_text,
                    'color': highlight_color,
                    'number': self.current_number
                })
                self.current_number += 1
        
        doc.close()
        return self
    
    def generate_highlighted_pdf_bytes(self):
        """Create a PDF with Turnitin-style highlights and numbers"""
        doc = fitz.open(self.input_pdf_path)
        
        for hl in self.highlights:
            page = doc.load_page(hl['page'])
            
            # Convert color to PyMuPDF format
            if isinstance(hl['color'], str):
                # Handle hex colors
                color = fitz.utils.getColor(hl['color'])
            else:
                # Assume RGB tuple
                color = hl['color']
            
            # Ensure exactly 3 components
            if len(color) == 4:  # RGBA
                color = color[:3]
            elif len(color) not in (3, 1):
                color = (1, 1, 0)  # Default yellow
            
            # Add semi-transparent highlight
            highlight = page.add_highlight_annot(hl['coordinates'])
            highlight.set_colors(stroke=color)
            highlight.set_opacity(0.3)
            highlight.update()
            
            # Calculate number badge position (top-left of highlight)
            badge_size = 12  # Slightly larger for better visibility
            badge_x = hl['coordinates'].x0
            badge_y = hl['coordinates'].y0 - badge_size - 1

            # Draw number badge background
            badge_rect = fitz.Rect(
                badge_x, badge_y,
                badge_x + badge_size, badge_y + badge_size
            )
            badge = page.add_rect_annot(badge_rect)
            badge.set_colors(fill=color, stroke=color)
            badge.set_opacity(1)
            badge.update()
            
            # Draw number text centered in badge using insert_textbox
            font_size = 10
            text = str(hl['number'])
            page.insert_textbox(
                badge_rect,
                text,
                fontsize=font_size,
                color=(1, 1, 1),  # White
                fontname="helv",
                align=1,  # Center alignment
                render_mode=3  # Fill text for best visibility
            )
        
        # Save to bytes
        pdf_bytes = doc.write()
        doc.close()
        
        return pdf_bytes
    
    def generate_report(self):
        """Generate a report of all highlights"""
        return pd.DataFrame(self.highlights)
