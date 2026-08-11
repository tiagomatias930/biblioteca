<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use PHPUnit\Framework\Attributes\DataProvider;

class ModelHelpersTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_slug_is_generated_automatically_from_the_name(): void
    {
        $category = Category::factory()->create(['name' => 'Documentos para Vaga de Emprego']);

        $this->assertEquals('documentos-para-vaga-de-emprego', $category->slug);
    }

    public function test_duplicate_category_names_get_unique_slugs(): void
    {
        $first = Category::factory()->create(['name' => 'Certificações']);
        $second = Category::factory()->create(['name' => 'Certificações']);

        $this->assertNotEquals($first->slug, $second->slug);
    }

    public function test_access_code_is_hashed_and_verifiable(): void
    {
        $category = Category::factory()->protected('minha-senha')->create();

        $this->assertNotEquals('minha-senha', $category->getRawOriginal('access_code'));
        $this->assertTrue($category->checkAccessCode('minha-senha'));
        $this->assertFalse($category->checkAccessCode('senha-errada'));
    }

    #[DataProvider('humanSizeProvider')]
    public function test_document_human_size_formats_bytes_correctly(int $bytes, string $expected): void
    {
        $document = Document::factory()->make(['size' => $bytes]);

        $this->assertEquals($expected, $document->humanSize());
    }

    public static function humanSizeProvider(): array
    {
        return [
            'bytes' => [500, '500 B'],
            'kilobytes' => [2048, '2 KB'],
            'megabytes' => [3_145_728, '3 MB'],
            'fractional_kilobytes' => [1536, '1.5 KB'],
            'zero_bytes' => [0, '0 B'],
        ];
    }

    public function test_document_extension_is_uppercased(): void
    {
        $document = Document::factory()->make(['original_name' => 'contrato.pdf']);

        $this->assertEquals('PDF', $document->extension());
    }
}
