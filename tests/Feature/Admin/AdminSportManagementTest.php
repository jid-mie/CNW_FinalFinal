<?php

namespace Tests\Feature\Admin;

use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSportManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Role $adminRole;
    private string $testImageContent;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::create(['name' => 'admin', 'display_name' => 'Admin']);
        $this->admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
        ]);

        $testImagePath = public_path('test_image.png');
        if (!file_exists($testImagePath)) {
            @copy('https://raw.githubusercontent.com/laravel/framework/9.x/tests/Foundation/fixtures/placeholder.png', $testImagePath);
        }
        $this->testImageContent = file_exists($testImagePath) ? file_get_contents($testImagePath) : 'fake-image-bytes';
    }

    public function test_admin_can_create_sport_with_image_upload()
    {
        $this->actingAs($this->admin);

        Storage::fake('public');

        $image = UploadedFile::fake()->createWithContent('tennis.png', $this->testImageContent)->mimeType('image/png');

        $response = $this->post(route('admin.sports.store'), [
            'name' => 'Tennis',
            'description' => 'Tennis sport description',
            'image_file' => $image,
        ]);

        if ($response->status() !== 302) {
            dd([
                'status' => $response->status(),
                'errors' => session('errors') ? session('errors')->getMessages() : null,
                'content' => $response->getContent(),
            ]);
        }

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.sports.index'));
        $response->assertSessionHas('success');

        $sport = Sport::where('name', 'Tennis')->first();
        $this->assertNotNull($sport);
        $this->assertNotNull($sport->image_url);

        // Verify that the file was stored on the disk
        $parsedUrl = parse_url($sport->image_url, PHP_URL_PATH);
        $path = ltrim(str_replace('/storage/', '', $parsedUrl), '/');
        Storage::disk('public')->assertExists($path);
    }

    public function test_admin_can_update_sport_with_new_image_upload()
    {
        $this->actingAs($this->admin);

        Storage::fake('public');

        // Create sport first with an initial image
        $image1 = UploadedFile::fake()->createWithContent('badminton.png', $this->testImageContent)->mimeType('image/png');
        
        $response = $this->post(route('admin.sports.store'), [
            'name' => 'Badminton',
            'description' => 'Initial desc',
            'image_file' => $image1,
        ]);

        if ($response->status() !== 302) {
            dd([
                'status' => $response->status(),
                'errors' => session('errors') ? session('errors')->getMessages() : null,
                'content' => $response->getContent(),
            ]);
        }

        $sport = Sport::where('name', 'Badminton')->first();
        $this->assertNotNull($sport);
        $initialImageUrl = $sport->image_url;
        
        $parsedOldUrl = parse_url($initialImageUrl, PHP_URL_PATH);
        $oldPath = ltrim(str_replace('/storage/', '', $parsedOldUrl), '/');
        Storage::disk('public')->assertExists($oldPath);

        // Update the sport with a new image
        $image2 = UploadedFile::fake()->createWithContent('badminton_new.png', $this->testImageContent)->mimeType('image/png');

        $response = $this->post(route('admin.sports.update', $sport->id), [
            'name' => 'Badminton Updated',
            'description' => 'Updated desc',
            'image_file' => $image2,
        ]);

        if ($response->status() !== 302) {
            dd([
                'status' => $response->status(),
                'errors' => session('errors') ? session('errors')->getMessages() : null,
                'content' => $response->getContent(),
            ]);
        }

        $response->assertStatus(302);
        $response->assertRedirect(route('admin.sports.index'));

        $sport->refresh();
        $this->assertEquals('Badminton Updated', $sport->name);
        $this->assertNotEquals($initialImageUrl, $sport->image_url);

        // Verify that the new file was stored on the disk
        $parsedNewUrl = parse_url($sport->image_url, PHP_URL_PATH);
        $newPath = ltrim(str_replace('/storage/', '', $parsedNewUrl), '/');
        Storage::disk('public')->assertExists($newPath);

        // Verify that the old file was deleted from the disk
        Storage::disk('public')->assertMissing($oldPath);
    }
}
