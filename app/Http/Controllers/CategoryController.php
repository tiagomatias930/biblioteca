<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Request $request, Category $category): View
    {
        if ($category->is_protected && ! $this->isUnlocked($request, $category)) {
            return view('categories.protected', compact('category'));
        }

        $documents = $category->documents;

        return view('categories.show', compact('category', 'documents'));
    }

    public function unlock(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'access_code' => ['required', 'string'],
        ]);

        if (! $category->is_protected) {
            return redirect()->route('categories.show', $category);
        }

        if (! $category->checkAccessCode($validated['access_code'])) {
            return back()
                ->withErrors(['access_code' => 'Código de acesso incorreto.'])
                ->withInput();
        }

        $request->session()->put($this->sessionKey($category), true);

        return redirect()->route('categories.show', $category);
    }

    public function isUnlocked(Request $request, Category $category): bool
    {
        return (bool) $request->session()->get($this->sessionKey($category), false);
    }

    protected function sessionKey(Category $category): string
    {
        return "category_unlocked_{$category->id}";
    }
}
