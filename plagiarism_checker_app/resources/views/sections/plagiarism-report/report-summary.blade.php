<x-filament::card style="background: linear-gradient(135deg, #f5f7fa 0%, #e4f0fb 100%); border: none; border-radius: 12px; overflow: hidden;">
    <div style="text-align: center; padding: 2.5rem 1.5rem; position: relative;">
        <!-- Decorative elements -->
        <div style="position: absolute; top: 0; right: 0; width: 100px; height: 100px; background: radial-gradient(circle, rgba(46,204,113,0.1) 0%, rgba(46,204,113,0) 70%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 80px; height: 80px; background: radial-gradient(circle, rgba(52,152,219,0.1) 0%, rgba(52,152,219,0) 70%);"></div>
        
        <!-- Dynamic Icon with animation -->
        <div style="display: inline-flex; align-items: center; justify-content: center; width: 80px; height: 80px; border-radius: 50%; 
            background-color: {{ ($results['originality_score'] ?? 100) >= 80 ? '#e6f7ee' : (($results['originality_score'] ?? 100) >= 50 ? '#fff7e6' : '#fce8e8') }}; 
            margin-bottom: 1.5rem; animation: pulse 2s infinite;">
            @if(($results['originality_score'] ?? 100) >= 80)
                <svg style="width: 40px; height: 40px; color: #2ecc71;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            @elseif(($results['originality_score'] ?? 100) >= 50)
                <svg style="width: 40px; height: 40px; color: #f1c40f;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
            @else
                <svg style="width: 40px; height: 40px; color: #e74c3c;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
            @endif
        </div>

        <!-- Result Message -->
        <div style="margin-bottom: 2rem;">
            <h2 style="font-size: 1.75rem; font-weight: 700; color: {{ ($results['similarity_score'] ?? 0) > 70 ? '#e74c3c' : (($results['similarity_score'] ?? 0) > 40 ? '#f1c40f' : '#2ecc71') }}; margin-bottom: 0.75rem;">
                @if(($results['similarity_score'] ?? 0) > 70)
                    High Plagiarism Detected! ⚠️
                @elseif(($results['similarity_score'] ?? 0) > 40)
                    Moderate Plagiarism Found! ⚠️
                @elseif(($results['similarity_score'] ?? 0) > 20)
                    Low Plagiarism Detected 📝
                @else
                    Excellent Originality! 🎉
                @endif
            </h2>
            <p style="font-size: 1.1rem; color: #7f8c8d; max-width: 600px; margin: 0 auto; line-height: 1.6;">
                @if(($results['similarity_score'] ?? 0) > 70)
                    This content has substantial similarities with other sources.
                @elseif(($results['similarity_score'] ?? 0) > 40)
                    This content has significant similarities with other sources.
                @elseif(($results['similarity_score'] ?? 0) > 20)
                    Some similarities found with other sources.
                @else
                    Content appears mostly original.
                @endif
            </p>
        </div>

        <!-- Statistics Grid -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; max-width: 800px; margin: 0 auto; background: white; padding: 1.5rem; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.05);">
            <!-- Originality Score -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #2ecc71; margin-bottom: 0.5rem;">
                    {{ $results['originality_score'] ?? '100' }}%
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Originality Score
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['originality_score'] ?? 100) }}%; background: #2ecc71; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Similarity Score -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #e74c3c; margin-bottom: 0.5rem;">
                    {{ $results['similarity_score'] ?? '0' }}%
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Similarity Found
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: {{ min(100, $results['similarity_score'] ?? 0) }}%; background: #e74c3c; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Sources Checked -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #3498db; margin-bottom: 0.5rem;">
                    {{ $results['source_matched'] ?? '0' }}
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Sources Matched
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #3498db; border-radius: 2px;"></div>
                </div>
            </div>
            
            <!-- Words Analyzed -->
            <div style="padding: 1rem;">
                <div style="font-size: 2rem; font-weight: 700; color: #9b59b6; margin-bottom: 0.5rem;">
                    {{ $results['words_analyzed'] ?? '0' }}
                </div>
                <div style="font-size: 0.875rem; color: #7f8c8d; font-weight: 500;">
                    Words Analyzed
                </div>
                <div style="height: 4px; background: #ecf0f1; margin-top: 0.75rem; border-radius: 2px;">
                    <div style="height: 100%; width: 100%; background: #9b59b6; border-radius: 2px;"></div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</x-filament::card>