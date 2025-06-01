<x-filament::card style="background: linear-gradient(135deg, #f5f7fa 0%, #e4f0fb 100%); border: none; border-radius: 8px; overflow: hidden;">
    <div style="text-align: center; padding: 1.5rem 1rem; position: relative;">
        <!-- Decorative elements -->
        <div style="position: absolute; top: 0; right: 0; width: 75px; height: 75px; background: radial-gradient(circle, rgba(46,204,113,0.1) 0%, rgba(46,204,113,0) 70%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 60px; height: 60px; background: radial-gradient(circle, rgba(52,152,219,0.1) 0%, rgba(52,152,219,0) 70%);"></div>
        
        <!-- Dynamic Icon with animation -->
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 60px; height: 60px; border-radius: 50%; 
            background-color: {{ ($results['originality_score'] ?? 100) >= 80 ? '#e6f7ee' : (($results['originality_score'] ?? 100) >= 50 ? '#fff7e6' : '#fce8e8') }}; 
            margin-bottom: 1rem; animation: pulse 2s infinite;">
            @if(($results['originality_score'] ?? 100) >= 80)
                <svg style="width: 30px; height: 30px; color: #2ecc71;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif(($results['originality_score'] ?? 100) >= 50)
                <svg style="width: 30px; height: 30px; color: #f1c40f;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            @else
                <svg style="width: 30px; height: 30px; color: #e74c3c;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            @endif
        </div>

        <!-- Result Message -->
        <div style="margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.25rem; font-weight: 700; color: {{ ($results['similarity_score'] ?? 0) > 85 ? '#e74c3c' : (($results['similarity_score'] ?? 0) > 65 ? '#f39c12' : (($results['similarity_score'] ?? 0) > 30 ? '#f1c40f' : '#2ecc71')) }}; margin-bottom: 0.5rem;">
                @if(($results['similarity_score'] ?? 0) > 85)
                    ⚠️ High Risk - Critical Attention Required ⚠️
                @elseif(($results['similarity_score'] ?? 0) > 65)
                    ⚡ Moderate Risk - Review Suggested ⚡
                @elseif(($results['similarity_score'] ?? 0) > 30)
                    📝 Low Risk - Minor Review Advised 📝
                @else
                    🌟 Excellent - Highly Original Content 🌟
                @endif
            </h2>
            <p style="font-size: 0.9rem; color: #7f8c8d; max-width: 450px; margin: 0 auto; line-height: 1.4;">
                @if(($results['similarity_score'] ?? 0) > 85)
                    This content requires immediate attention as it contains significant matching text with existing sources. We recommend thoroughly revising the highlighted sections to ensure academic integrity and originality.
                @elseif(($results['similarity_score'] ?? 0) > 65)
                    We've identified notable similarities with other sources. While some matches may be appropriate citations, we recommend reviewing the highlighted sections to enhance originality and ensure proper attribution.
                @elseif(($results['similarity_score'] ?? 0) > 30)
                    Your content shows some common phrases or potentially cited material. While this level is generally acceptable, you may want to review the highlighted sections for potential improvements.
                @else
                    Congratulations! Your content demonstrates excellent originality. Any minor matches are likely common phrases or properly cited materials. Keep up the great work!
                @endif
            </p>
        </div>

        <!-- Statistics Grid -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; max-width: 600px; margin: 0 auto; background: white; padding: 1rem; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
            <!-- Originality Score -->
            <div style="padding: 0.75rem;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #2ecc71; margin-bottom: 0.25rem;">
                    {{ $results['originality_score'] ?? '100' }}%
                </div>
                <div style="font-size: 0.75rem; color: #7f8c8d; font-weight: 500;">
                    Originality Score
                </div>
                <div style="height: 3px; background: #ecf0f1; margin-top: 0.5rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['originality_score'] ?? 100) }}%; background: #2ecc71; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Similarity Score -->
            <div style="padding: 0.75rem;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #e74c3c; margin-bottom: 0.25rem;">
                    {{ $results['similarity_score'] ?? '0' }}%
                </div>
                <div style="font-size: 0.75rem; color: #7f8c8d; font-weight: 500;">
                    Similarity Found
                </div>
                <div style="height: 3px; background: #ecf0f1; margin-top: 0.5rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['similarity_score'] ?? 0) }}%; background: #e74c3c; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Sources Checked -->
            <div style="padding: 0.75rem;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #3498db; margin-bottom: 0.25rem;">
                    {{ $results['source_matched'] ?? '0' }}
                </div>
                <div style="font-size: 0.75rem; color: #7f8c8d; font-weight: 500;">
                    Sources Matched
                </div>
                <div style="height: 3px; background: #ecf0f1; margin-top: 0.5rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #3498db; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Words Analyzed -->
            <div style="padding: 0.75rem;">
                <div style="font-size: 1.5rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.25rem;">
                    {{ $results['words_analyzed'] ?? '0' }}
                </div>
                <div style="font-size: 0.75rem; color: #7f8c8d; font-weight: 500;">
                    Words Analyzed
                </div>
                <div style="height: 3px; background: #ecf0f1; margin-top: 0.5rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #9b59b6; border-radius: 2px;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }
    </style>
</x-filament::card>