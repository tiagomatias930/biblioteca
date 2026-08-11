<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function download(Request $request, Document $document): StreamedResponse
    {
        $category = $document->category;

        if ($category->is_protected) {
            $unlocked = $request->session()->get("category_unlocked_{$category->id}", false);

            abort_unless($unlocked, 403, 'Este documento está numa categoria protegida.');
        }

        abort_unless(Storage::disk('documents')->exists($document->disk_path), 404);

        return Storage::disk('documents')->download(
            $document->disk_path,
            $document->original_name
        );
    }
}
