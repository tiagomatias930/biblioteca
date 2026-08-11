<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDocumentUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('documents');
    }

    public function test_admin_can_upload_a_document_to_a_category(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('diploma.pdf', 500, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('admin.documents.store'), [
            'category_id' => $category->id,
            'title' => 'Diploma de Engenharia',
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('documents', [
            'category_id' => $category->id,
            'title' => 'Diploma de Engenharia',
            'original_name' => 'diploma.pdf',
        ]);

        $document = Document::firstWhere('title', 'Diploma de Engenharia');
        Storage::disk('documents')->assertExists($document->disk_path);
    }

    public function test_upload_rejects_disallowed_file_types(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('script.exe', 10, 'application/x-msdownload');

        $response = $this->actingAs($admin)->post(route('admin.documents.store'), [
            'category_id' => $category->id,
            'title' => 'Suspeito',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
        $this->assertDatabaseCount('documents', 0);
    }

    public function test_upload_rejects_files_over_the_size_limit(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('grande.pdf', 25000, 'application/pdf'); // 25 MB > 20 MB limit

        $response = $this->actingAs($admin)->post(route('admin.documents.store'), [
            'category_id' => $category->id,
            'title' => 'Ficheiro grande',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('file');
    }

    public function test_upload_requires_an_existing_category(): void
    {
        $admin = User::factory()->create();
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->actingAs($admin)->post(route('admin.documents.store'), [
            'category_id' => 9999,
            'title' => 'Doc',
            'file' => $file,
        ]);

        $response->assertSessionHasErrors('category_id');
    }

    public function test_guest_cannot_upload_documents(): void
    {
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $response = $this->post(route('admin.documents.store'), [
            'category_id' => $category->id,
            'title' => 'Doc',
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseCount('documents', 0);
    }

    public function test_admin_can_delete_a_document(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $path = $file->store('documents/' . $category->slug, 'documents');
        $document = Document::factory()->for($category)->create(['disk_path' => $path]);

        $response = $this->actingAs($admin)->delete(route('admin.documents.destroy', $document));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('documents')->assertMissing($path);
    }

    public function test_admin_can_update_document_metadata_without_replacing_file(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create(['name' => 'Categoria Inicial']);
        $newCategory = Category::factory()->create(['name' => 'Categoria Destino']);
        
        $file = UploadedFile::fake()->create('relatorio.pdf', 100, 'application/pdf');
        $path = $file->store('documents/' . $category->slug, 'documents');
        
        $document = Document::factory()->for($category)->create([
            'title' => 'Titulo Antigo',
            'disk_path' => $path,
            'original_name' => 'relatorio.pdf',
        ]);

        $response = $this->actingAs($admin)->put(route('admin.documents.update', $document), [
            'category_id' => $newCategory->id,
            'title' => 'Titulo Novo',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'category_id' => $newCategory->id,
            'title' => 'Titulo Novo',
        ]);

        // Assert file was moved to the new category slug directory
        $newExpectedPath = 'documents/' . $newCategory->slug . '/' . basename($path);
        Storage::disk('documents')->assertExists($newExpectedPath);
        Storage::disk('documents')->assertMissing($path);
    }

    public function test_admin_can_replace_document_file(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();
        
        $oldFile = UploadedFile::fake()->create('antigo.pdf', 50, 'application/pdf');
        $oldPath = $oldFile->store('documents/' . $category->slug, 'documents');
        
        $document = Document::factory()->for($category)->create([
            'title' => 'Contrato',
            'disk_path' => $oldPath,
            'original_name' => 'antigo.pdf',
        ]);

        $newFile = UploadedFile::fake()->create('novo.docx', 120, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($admin)->put(route('admin.documents.update', $document), [
            'category_id' => $category->id,
            'title' => 'Contrato Atualizado',
            'file' => $newFile,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('documents', [
            'id' => $document->id,
            'title' => 'Contrato Atualizado',
            'original_name' => 'novo.docx',
        ]);

        $document->refresh();
        Storage::disk('documents')->assertExists($document->disk_path);
        Storage::disk('documents')->assertMissing($oldPath);
    }
}
