<?php

namespace App\Http\Controllers;

use App\Models\Category;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('documents')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('home.index', compact('categories'));
    }
}
