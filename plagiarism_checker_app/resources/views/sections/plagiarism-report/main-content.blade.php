<x-filament::card>
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full space-y-4">
            <div class="space-y-2">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">Content Analysis</h4>
                    @if ($filename)
                        <span class="text-sm text-gray-500 dark:text-gray-400">File: {{ $filename }}</span>
                    @endif
                </div>

                <div class="rounded border p-4 bg-gray-50 dark:bg-gray-800">
                    @if (!is_null($preview_content))
                        <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300">
                            <div class="document-preview">
                                @foreach($preview_content as $section)
                                    <div class="page-section mb-8">
                                        @if(is_array($section) && isset($section[0]['type']))
                                            {{-- DOCX structure --}}
                                            @foreach($section as $element)
                                                @include('partials/document-element', ['element' => $element])
                                            @endforeach
                                        @else
                                            {{-- PDF structure --}}
                                            @foreach($section as $element)
                                                @if(is_array($element) && isset($element['text']))
                                                    <p>
                                                        @include('partials/document-text', ['text' => $element])
                                                    </p>
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                        <div class="page-number-indicator text-sm text-gray-500 border-t pt-2">
                                            Page {{ $section['page'] ?? 1 }} of {{ $section['total_pages'] ?? 1 }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-filament::card>
