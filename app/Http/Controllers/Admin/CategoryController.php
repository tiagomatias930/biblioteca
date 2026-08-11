<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_protected'] = $request->boolean('is_protected');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if (! $data['is_protected']) {
            $data['access_code'] = null;
        }

        Category::create($data);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Categoria criada com sucesso.');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(StoreCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['is_protected'] = $request->boolean('is_protected');

        if ($request->hasFile('cover_image')) {
            if ($category->cover_image) {
                Storage::disk('public')->delete($category->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        if (! $data['is_protected']) {
            $data['access_code'] = null;
        } elseif (empty($data['access_code'])) {
            // Keep the existing access code when the field is left blank on update.
            unset($data['access_code']);
        }

        $category->update($data);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Categoria atualizada com sucesso.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        foreach ($category->documents as $document) {
            Storage::disk('documents')->delete($document->disk_path);
        }

        if ($category->cover_image) {
            Storage::disk('public')->delete($category->cover_image);
        }

        $category->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Categoria removida.');
    }
}
