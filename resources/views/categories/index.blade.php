<html>
    <body>
        <select>
            @include('categories.options_cache')
            {{-- @include('categories.options', ['categories' => $categories, 'level' => 0]) --}}
        </select>
    </body>
</html>