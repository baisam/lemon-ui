<select id="{{ $id }}" name="{{ $name }}" class="btn {{ $class }}" {!! $attributes !!} >
    <option value="">－{{ $label }}－</option>
    @foreach($options as $option => $label)
        <option value="{{ $option }}" {{ $option == $value ?'selected':'' }}>{{ $label }}</option>
    @endforeach
</select>
@php
    $config = array_merge([
        'allowClear'         => false,
        'minimumInputLength' => 0
    ], $config);

    if ($placeholder) {
        $config['placeholder'] = $placeholder;
    }
    if (count($options) > 20) {
        $config['select2'] = true;
    }

    if (isset($config['select2'])) {
        app('resources')->getInstance()->requireResource('select2');
    }
@endphp
@if(isset($config['select2']) && $config['select2'])
@script
var __{{ $id }}_config = @json($config);
@isset($keywords)
var __{{ $id }}_keywords = @json($keywords);
__{{ $id }}_config.matcher = function(params, data) {
    // Always return the object if there is nothing to compare
    if ($.trim(params.term) === '') {
        return data;
    }

    // Do a recursive check for options with children
    if (data.children && data.children.length > 0) {
        // Clone the data object if there are children
        // This is required as we modify the object to remove any non-matches
        var match = $.extend(true, {}, data);

        // Check each child of the option
        for (var c = data.children.length - 1; c >= 0; c--) {
            var child = data.children[c];

            var matches = __{{ $id }}_config.matcher(params, child);

            // If there wasn't a match, remove the object in the array
            if (matches == null) {
                match.children.splice(c, 1);
            }
        }

        // If any children matched, return the new object
        if (match.children.length > 0) {
            return match;
        }

        // If there were no matching children, check just the plain object
        return __{{ $id }}_config.matcher(params, match);
    }

    var text = data.text.toUpperCase();
    var term = params.term.toUpperCase();

    // Check if the text contains the term
    if (text.indexOf(term) > -1) {
        return data;
    }

    // Check if the keywords contains the term
    if (typeof __{{ $id }}_keywords[data.id] !== 'undefined' &&
        (__{{ $id }}_keywords[data.id]+'').toUpperCase().indexOf(term) > -1) {
        return data;
    }

    // If it doesn't contain the term, don't return anything
    return null;
}
@endisset
$('#{{ $id }}').select2(__{{ $id }}_config);
@endscript
@endif