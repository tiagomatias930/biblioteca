<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
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

    public function edit(Document $document): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.documents.edit', compact('document', 'categories'));
    }

    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        $validated = $request->validated();
        $newCategory = Category::findOrFail($validated['category_id']);

        if ($request->hasFile('file')) {
            // Delete old file
            Storage::disk('documents')->delete($document->disk_path);

            // Store new file
            $file = $request->file('file');
            $storedPath = $file->store('documents/' . $newCategory->slug, 'documents');

            $document->fill([
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
                'disk_path' => $storedPath,
            ]);
        } elseif ($document->category_id !== $newCategory->id) {
            // Move file on disk to the new category directory
            $oldPath = $document->disk_path;
            $filename = basename($oldPath);
            $newPath = 'documents/' . $newCategory->slug . '/' . $filename;

            // Handle filename collision in destination
            $i = 1;
            $pathInfo = pathinfo($filename);
            while (Storage::disk('documents')->exists($newPath)) {
                $newFilename = $pathInfo['filename'] . '_' . $i++ . '.' . ($pathInfo['extension'] ?? '');
                $newPath = 'documents/' . $newCategory->slug . '/' . $newFilename;
            }

            if (Storage::disk('documents')->exists($oldPath)) {
                Storage::disk('documents')->move($oldPath, $newPath);
            }
            $document->disk_path = $newPath;
        }

        $document->update([
            'category_id' => $newCategory->id,
            'title' => $validated['title'],
            'disk_path' => $document->disk_path,
        ]);

        return redirect()
            ->route('admin.dashboard')
            ->with('status', 'Documento atualizado com sucesso.');
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
