<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Document;

#[Signature('app:sync-certificates')]
#[Description('Sync certificate PDF files from the storage folder into the database under the Certificações category')]
class SyncCertificates extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $category = Category::where('slug', 'documentos-certificacao')->first();
        if (!$category) {
            $this->error('Category "Certificações" (slug: documentos-certificacao) not found in the database. Please run migrations/seeders first.');
            return Command::FAILURE;
        }

        $files = Storage::disk('documents')->files('documents/documentos-certificacao');

        // 1. Delete documents from database if their files no longer exist on disk
        $existingDocs = Document::where('category_id', $category->id)->get();
        foreach ($existingDocs as $doc) {
            if (!Storage::disk('documents')->exists($doc->disk_path)) {
                $doc->delete();
                $this->warn("Removed from database (file deleted from disk): {$doc->original_name}");
            }
        }

        if (empty($files)) {
            $this->info('No files found in storage/app/documents/documents/documentos-certificacao');
            return Command::SUCCESS;
        }

        $imported = 0;
        $updated = 0;

        foreach ($files as $filePath) {
            $filename = basename($filePath);

            // Skip hidden files
            if (str_starts_with($filename, '.')) {
                continue;
            }

            // Determine mime type and size
            $mimeType = Storage::disk('documents')->mimeType($filePath) ?: 'application/pdf';
            $size = Storage::disk('documents')->size($filePath) ?: 0;

            // Generate a readable title from the filename
            $titleWithoutExtension = pathinfo($filename, PATHINFO_FILENAME);
            
            // Split camelCase/PascalCase words nicely (e.g. CertificadoDG -> Certificado DG)
            $splitCamel = preg_replace('/(?<!^)(?=[A-Z][a-z])|(?<=[a-z])(?=[A-Z])/', ' ', $titleWithoutExtension);

            // Replace hyphens, underscores and multiple spaces with a single space, and trim
            $cleanTitle = trim(preg_replace('/[_\s-]+/', ' ', $splitCamel));
            
            // Capitalize words nicely
            $cleanTitle = ucwords(mb_strtolower($cleanTitle));

            // Check if document already exists
            $doc = Document::where('disk_path', $filePath)->first();

            if ($doc) {
                $doc->update([
                    'title' => $cleanTitle,
                    'mime_type' => $mimeType,
                    'size' => $size,
                ]);
                $updated++;
            } else {
                Document::create([
                    'category_id' => $category->id,
                    'title' => $cleanTitle,
                    'disk_path' => $filePath,
                    'original_name' => $filename,
                    'mime_type' => $mimeType,
                    'size' => $size,
                ]);
                $this->info("Imported: {$filename} as '{$cleanTitle}'");
                $imported++;
            }
        }

        $this->info("Sync completed! Imported (new): {$imported}, Updated (existing): {$updated}");
        return Command::SUCCESS;
    }
}
