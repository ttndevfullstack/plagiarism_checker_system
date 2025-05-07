@if(isset($text['link']))
    <a href="{{ $text['link'] }}" style="
        @if($text['font']['bold'] ?? false) font-weight: bold; @endif
        @if($text['font']['italic'] ?? false) font-style: italic; @endif
        @if($text['font']['underline'] ?? false) text-decoration: underline; @endif
        @if($text['font']['color'] ?? false) color: #{{ $text['font']['color'] }}; @endif
        @if($text['font']['size'] ?? false) font-size: {{ $text['font']['size'] }}pt; @endif
        @if($text['font']['name'] ?? false) font-family: '{{ $text['font']['name'] }}'; @endif
    ">
        {!! isset($text['raw']) ? ($text['text'] ?? '') : html_entity_decode(e($text['text'] ?? '')) !!}
    </a>
@else
    <span style="
        @if($text['font']['bold'] ?? false) font-weight: bold; @endif
        @if($text['font']['italic'] ?? false) font-style: italic; @endif
        @if($text['font']['underline'] ?? false) text-decoration: underline; @endif
        @if($text['font']['color'] ?? false) color: #{{ $text['font']['color'] }}; @endif
        @if($text['font']['size'] ?? false) font-size: {{ $text['font']['size'] }}pt; @endif
        @if($text['font']['name'] ?? false) font-family: '{{ $text['font']['name'] }}'; @endif
    ">
        {!! isset($text['raw']) ? ($text['text'] ?? '') : html_entity_decode(e($text['text'] ?? '')) !!}
    </span>
@endif