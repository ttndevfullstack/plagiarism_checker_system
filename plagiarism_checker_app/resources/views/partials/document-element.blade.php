@if($element['type'] === 'paragraph')
    <p style="text-align: {{ $element['alignment'] ?? 'left' }};">
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
                            @include('partials/document-element', ['element' => $cellElement])
                        @endforeach
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>

@elseif($element['type'] === 'text-break')
    <br/>
@endif