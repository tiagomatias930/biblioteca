<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_admin_can_create_a_category(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Certificações',
            'description' => 'Diplomas e cursos',
            'sort_order' => 1,
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('categories', [
            'name' => 'Certificações',
            'slug' => 'certificacoes',
            'is_protected' => false,
        ]);
    }

    public function test_category_creation_requires_a_name(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'description' => 'sem nome',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_protected_category_requires_an_access_code(): void
    {
        $admin = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Confidencial',
            'is_protected' => '1',
        ]);

        $response->assertSessionHasErrors('access_code');
    }

    public function test_admin_can_upload_a_cover_image_when_creating_a_category(): void
    {
        $admin = User::factory()->create();
        $image = UploadedFile::fake()->image('cover.jpg');

        $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Documentos Pessoais',
            'cover_image' => $image,
        ]);

        $category = Category::firstWhere('name', 'Documentos Pessoais');
        $this->assertNotNull($category->cover_image);
        Storage::disk('public')->assertExists($category->cover_image);
    }

    public function test_admin_can_update_a_category_without_resetting_its_access_code(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->protected('original-code')->create();

        $response = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Nome atualizado',
            'is_protected' => '1',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $category->refresh();
        $this->assertTrue($category->checkAccessCode('original-code'));
        $this->assertEquals('Nome atualizado', $category->name);
    }

    public function test_guest_cannot_create_categories(): void
    {
        $response = $this->post(route('admin.categories.store'), ['name' => 'Teste']);

        $response->assertRedirect(route('admin.login'));
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_admin_can_delete_a_category(): void
    {
        $admin = User::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
