<x-filament::card>
    <div class="flex flex-col lg:flex-row gap-6">
        <div class="w-full space-y-4">
            <div class="space-y-2">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="font-medium text-gray-900 dark:text-white">Content Analysis</h4>
                    @if ($preview_content['filename'] ?? false)
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            File: {{ $preview_content['filename'] }}
                        </span>
                    @endif
                </div>

                <div class="rounded border p-4 bg-gray-50 dark:bg-gray-800">
                    @if (!is_null($preview_content))
                        <div class="prose prose-lg max-w-none dark:prose-invert prose-headings:font-bold prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300">
                            <div class="document-preview">
                                @foreach($preview_content as $section)
                                    <div class="page-section mb-8">
                                        @foreach($section as $element)
                                            @if($element['type'] === 'paragraph')
                                                <p style="text-align: {{ $element['style']['alignment'] ?? 'left' }};">
                                                    @foreach($element['content'] as $text)
                                                        @include('partials/document-text', ['text' => $text])
                                                    @endforeach
                                                </p>

                                            @elseif($element['type'] === 'heading')
                                                <h{{ $element['level'] }} style="text-align: {{ $element['alignment'] ?? 'left' }};">
                                                    @foreach($element['content'] as $text)
                                                        @if(isset($text['link']))
                                                            <a href="{{ $text['link'] }}" style="
                                                                @if($text['font']['bold'] ?? false) font-weight: bold; @endif
                                                                @if($text['font']['italic'] ?? false) font-style: italic; @endif
                                                                @if($text['font']['underline'] ?? false) text-decoration: underline; @endif
                                                                @if($text['font']['color'] ?? false) color: #{{ $text['font']['color'] }}; @endif
                                                                font-size: {{ (24 - ($element['level'] * 2)) }}pt;
                                                                @if($text['font']['name'] ?? false) font-family: '{{ $text['font']['name'] }}'; @endif
                                                            ">
                                                                {{ $text['text'] }}
                                                            </a>
                                                        @else
                                                          <span style="
                                                              @if($text['font']['bold'] ?? false) font-weight: bold; @endif
                                                              @if($text['font']['italic'] ?? false) font-style: italic; @endif
                                                              @if($text['font']['underline'] ?? false) text-decoration: underline; @endif
                                                              @if($text['font']['color'] ?? false) color: #{{ $text['font']['color'] }}; @endif
                                                              font-size: {{ (24 - ($element['level'] * 2)) }}pt;
                                                              @if($text['font']['name'] ?? false) font-family: '{{ $text['font']['name'] }}'; @endif
                                                          ">
                                                              {{ is_array($text['text']) ? implode('', $text['text']) : $text['text'] }}
                                                          </span>
                                                        @endif
                                                    @endforeach
                                                </h{{ $element['level'] }}>
                                                
                                            @elseif($element['type'] === 'table')
                                                <table class="table table-bordered">
                                                    @foreach($element['rows'] as $row)
                                                        <tr>
                                                            @foreach($row['cells'] as $cell)
                                                                <td>
                                                                    @foreach($cell['content'] as $cellElement)
                                                                        @if($cellElement['type'] === 'paragraph')
                                                                            <p>
                                                                                @foreach($cellElement['content'] as $text)
                                                                                    @include('partials/document-text', ['text' => $text])
                                                                                @endforeach
                                                                            </p>
                                                                        @elseif($cellElement['type'] === 'heading')
                                                                            <h{{ $cellElement['level'] }}>
                                                                                @foreach($cellElement['content'] as $text)
                                                                                    @include('partials/document-text', ['text' => $text])
                                                                                @endforeach
                                                                            </h{{ $cellElement['level'] }}>
                                                                        @endif
                                                                    @endforeach
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    @endforeach
                                                </table>
                                            
                                            @elseif($element['type'] === 'text-break')
                                                <br/>
                                            @endif
                                        @endforeach
                                        
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
