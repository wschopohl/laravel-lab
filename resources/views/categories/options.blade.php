@foreach ($categories as $category)
    <option value="{{ $category->id }}">{{ str_repeat("--", $level) }} {{ $level }} {{ $category->name }}</option>
    @include('categories.options', ['categories' => $category->categories, 'level' => $level+1])
@endforeach