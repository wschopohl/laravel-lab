This question and it's underlying problems awoke my interest and so I wanted to find out more about the whole matter. I created a test scenario myself.

## Optimization

First some optimizations to the code of the blade templates:

    // index.blade.php
    <select>
        @include('categories.options', ['categories' => $categories, 'level' => 0])
    </select>

    // options.blade.php
    @foreach ($categories as $category)
        <option value="{{ $category->id }}">{{ str_repeat("--", $level) }} {{ $category->name }}</option>
        @include('categories.options', ['categories' => $category->categories, 'level' => $level+1])
    @endforeach

I then generated a database with about 5000 nested categories, 7 levels deep, to test the load times. My assumption was that if you add eager loading to the Category Model you can optimize the load time:

    // Category.php
    protected $with = ['categories'];
    // and also try nested eager loading 
    protected $with = ['categories.categories'];

Here are the results:

                           Time  Queries  Memory
    --------------------------------------------------
     No eager loading   12,81 s     5101   112MB
    1 x eager loading    1,49 s        9    31MB
    2 x eager loading    1,54 s        9    31MB
    3 x eager loading    1,48 s        9    31MB
    Cached               0,08 s        0     4MB
    
    (stats recorded mainly with debugbar)

So as you can see eager loading definitely makes sense, but nested eager loading, at least not my approach seemed to make any difference. The real deal is caching.

-------
## Caching

So the only real solution, to get the site loading as fast as possible (that I could think of) is caching.

    // create new job in console
    php artisan make:job RenderCategoryView

    // RenderCategoryView.php
    public function handle()
    {
        $categories = \App\Category::where('category_id', null)->get();
        $html = \View::make('categories.index', compact('categories'))->render();
        file_put_contents(resource_path('views/categories/options_cache.blade.php'), $html);
        return true;
    }

Now you can replace the `@include` of your blade template like this:

    @include('categories.options_cache')

To test the generation of the options_cache file you can do:

    laravel tinker
    \App\Jobs\RenderCategoryView::dispatchNow();

I also removed the now unecessary loading of the `$categories` variable in the Controller and the new load time is **83 ms**. Not Very suprising, because now everything is cached.

To automatically generate a new view cache once a category is created, edited or deleted you should include this in the respective Controller(s):

    \App\Jobs\RenderCategoryView::dispatch();

Read more about how to dispatch jobs on queues and the like in the Laravel Docs.