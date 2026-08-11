<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('documents')
            ->with('documents')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.dashboard', [
            'categories' => $categories,
            'totalDocuments' => $categories->sum('documents_count'),
        ]);
    }
}
