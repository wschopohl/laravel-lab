<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index() {
        // $categories = \App\Category::doesntHave('parent')->get();
        // return view('categories.index', compact('categories'));
        return view('categories.index');
    }
}
