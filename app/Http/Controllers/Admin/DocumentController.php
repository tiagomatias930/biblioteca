<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.documents.create', compact('categories'));
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $file = $request->file('file');
        $category = Category::findOrFail($validated['category_id']);

        $storedPath = $file->store('documents/' . $category->slug, 'documents');

        Document::create([
            'category_id' => $category->id,
            'title' => $validated['title'],
            'disk_path' => $storedPath,
            'original_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Documento enviado com sucesso.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        Storage::disk('documents')->delete($document->disk_path);
        $document->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Documento removido.');
    }
}
