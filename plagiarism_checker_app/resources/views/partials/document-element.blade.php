@if($element['type'] === 'paragraph')
    <div 
        x-data="{ showTooltip: false, tooltipX: 0, tooltipY: 0 }"
        @mouseenter="showTooltip = true; tooltipX = $event.clientX; tooltipY = $event.clientY;"
        @mousemove="tooltipX = $event.clientX; tooltipY = $event.clientY;"
        @mouseleave="showTooltip = false"
        class="relative"
    >
        <p
            class="{{ $element['highlight'] ?? '' }} {{ $class ?? '' }}"
            data-text-id="{{ $index ?? 'none' }}"
            style="text-align: {{ $element['alignment'] ?? 'left' }};"
        >
            @foreach($element['content'] as $text)
                @include('partials/document-text', ['text' => $text])
            @endforeach
        </p>

        @if(isset($element['content'][0]['paragraph_result']))
            @include('partials/report-paragraph-tooltip', [
                'paragraph' => $element['content'][0]['paragraph_result'] ?? []
            ])
        @endif
    </div>
    
@elseif($element['type'] === 'heading')
    <h{{ $element['level'] }}
        class="{{ $element['highlight'] ?? '' }} {{ $class ?? '' }}"
        data-text-id="{{ $index ?? 'none' }}"
        style="text-align: {{ $element['alignment'] ?? 'left' }};"
    >
        @foreach($element['content'] as $text)
            @if(isset($text['link']))
                <a data-text-id="{{ $index ?? 'none' }}" href="{{ $text['link'] }}" style="
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
                <span 
                    class="{{ $element['highlight'] ?? '' }} {{ $class ?? '' }}"
                    data-text-id="{{ $index ?? 'none' }}"
                    style="
                      @if($text['font']['bold'] ?? false) font-weight: bold; @endif
                      @if($text['font']['italic'] ?? false) font-style: italic; @endif
                      @if($text['font']['underline'] ?? false) text-decoration: underline; @endif
                      @if($text['font']['color'] ?? false) color: #{{ $text['font']['color'] }}; @endif
                      font-size: {{ (24 - ($element['level'] * 2)) }}pt;
                      @if($text['font']['name'] ?? false) font-family: '{{ $text['font']['name'] }}'; @endif"
                >
                    {{ is_array($text['text']) ? implode('', $text['text']) : $text['text'] }}
                </span>
            @endif
        @endforeach
    </h{{ $element['level'] }}>

@elseif($element['type'] === 'table')
    <table class="styled-table">
        @foreach($element['rows'] as $row)
            <tr>
                @foreach($row['cells'] as $cell)
                    <td>
                        @foreach($cell['content'] as $cellElement)
                            @include('partials/document-element', ['element' => $cellElement, 'index' => $index, 'class' => 'm-0'])
                        @endforeach
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>

@elseif($element['type'] === 'text-break')
    <br/>
@endif

@push('styles')
<style>
    .m-0 {
      margin: 0;
    }

    .styled-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 25px 0;
        font-size: 0.9em;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        border-radius: 10px;
        overflow: hidden;
    }

    .styled-table thead tr {
        background-color: #009879;
        color: #ffffff;
        text-align: left;
        font-weight: bold;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
        border: 1px solid #dddddd;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
        transition: all 0.3s ease;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #009879;
    }

    .styled-table tbody tr:hover {
        background-color: #f1f1f1;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .styled-table th:first-child {
        border-top-left-radius: 10px;
    }

    .styled-table th:last-child {
        border-top-right-radius: 10px;
    }

    .styled-table tr:last-child td:first-child {
        border-bottom-left-radius: 10px;
    }

    .styled-table tr:last-child td:last-child {
        border-bottom-right-radius: 10px;
    }
</style>
@endpush
