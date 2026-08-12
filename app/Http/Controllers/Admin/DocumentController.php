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
        $category = Category::findOrFail($validated['category_id']);
        $files = $request->file('files');
        $count = 0;

        foreach ($files as $file) {
            $storedPath = $file->store('documents/' . $category->slug, 'documents');

            // If a custom title is set AND there is only one file, use the custom title.
            // Otherwise, use the file name without extension.
            if (!empty($validated['title']) && count($files) === 1) {
                $title = $validated['title'];
            } else {
                $title = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            }

            Document::create([
                'category_id' => $category->id,
                'title' => $title,
                'disk_path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ]);

            $count++;
        }

        $message = $count === 1 
            ? 'Documento enviado com sucesso.' 
            : "{$count} documentos enviados com sucesso.";

        return redirect()
            ->route('admin.dashboard')
            ->with('status', $message);
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
