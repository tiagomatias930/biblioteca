<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicCategoryBrowsingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('documents');
        Storage::fake('public');
    }

    public function test_home_page_lists_categories(): void
    {
        Category::factory()->create(['name' => 'Certificações']);
        Category::factory()->create(['name' => 'Documentos Pessoais']);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Certificações');
        $response->assertSee('Documentos Pessoais');
    }

    public function test_open_category_lists_its_documents(): void
    {
        $category = Category::factory()->create();
        $document = Document::factory()->for($category)->create(['title' => 'Diploma.pdf']);

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertSee('Diploma.pdf');
        $response->assertSee(route('documents.download', $document));
    }

    public function test_protected_category_shows_password_form_instead_of_documents(): void
    {
        $category = Category::factory()->protected('segredo123')->create();
        Document::factory()->for($category)->create(['title' => 'Contrato.pdf']);

        $response = $this->get(route('categories.show', $category));

        $response->assertOk();
        $response->assertDontSee('Contrato.pdf');
        $response->assertSee('código de acesso');
    }

    public function test_wrong_access_code_is_rejected(): void
    {
        $category = Category::factory()->protected('segredo123')->create();

        $response = $this->post(route('categories.unlock', $category), [
            'access_code' => 'errado',
        ]);

        $response->assertSessionHasErrors('access_code');
        $this->assertNull(session("category_unlocked_{$category->id}"));
    }

    public function test_correct_access_code_unlocks_the_category(): void
    {
        $category = Category::factory()->protected('segredo123')->create();
        Document::factory()->for($category)->create(['title' => 'Contrato.pdf']);

        $unlock = $this->post(route('categories.unlock', $category), [
            'access_code' => 'segredo123',
        ]);
        $unlock->assertRedirect(route('categories.show', $category));

        $response = $this->get(route('categories.show', $category));
        $response->assertOk();
        $response->assertSee('Contrato.pdf');
    }

    public function test_downloading_a_document_returns_the_original_file(): void
    {
        $category = Category::factory()->create();
        $file = UploadedFile::fake()->create('curriculo.pdf', 100, 'application/pdf');
        $path = $file->store('documents/' . $category->slug, 'documents');

        $document = Document::factory()->for($category)->create([
            'disk_path' => $path,
            'original_name' => 'curriculo.pdf',
        ]);

        $response = $this->get(route('documents.download', $document));

        $response->assertOk();
        $response->assertHeader('content-disposition');
        $this->assertStringContainsString('curriculo.pdf', $response->headers->get('content-disposition'));
    }

    public function test_downloading_from_a_protected_category_without_unlocking_is_forbidden(): void
    {
        $category = Category::factory()->protected('segredo123')->create();
        $file = UploadedFile::fake()->create('confidencial.pdf', 50, 'application/pdf');
        $path = $file->store('documents/' . $category->slug, 'documents');

        $document = Document::factory()->for($category)->create([
            'disk_path' => $path,
            'original_name' => 'confidencial.pdf',
        ]);

        $response = $this->get(route('documents.download', $document));

        $response->assertForbidden();
    }

    public function test_downloading_a_missing_file_returns_404(): void
    {
        $category = Category::factory()->create();
        $document = Document::factory()->for($category)->create([
            'disk_path' => 'documents/does-not-exist.pdf',
        ]);

        $response = $this->get(route('documents.download', $document));

        $response->assertNotFound();
    }
}
