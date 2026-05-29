<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use App\Models\Sport;
use App\Models\Field;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageUploadTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;
    private Sport $sport;
    private string $testImageContent;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);

        // Setup owner
        $this->owner = User::create([
            'role_id' => $ownerRole->id,
            'name' => 'Test Owner',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
        ]);

        // Setup sport
        $this->sport = Sport::create([
            'name' => 'Bóng đá',
            'slug' => 'bong-da',
            'is_active' => true,
        ]);

        // Đọc dữ liệu ảnh test được tạo sẵn
        $testImagePath = public_path('test_image.png');
        if (!file_exists($testImagePath)) {
            // Fallback nếu chưa có file
            @copy('https://raw.githubusercontent.com/laravel/framework/9.x/tests/Foundation/fixtures/placeholder.png', $testImagePath);
        }
        $this->testImageContent = file_exists($testImagePath) ? file_get_contents($testImagePath) : 'fake-image-bytes';
    }

    public function test_owner_can_upload_avatar()
    {
        $this->actingAs($this->owner);

        Storage::fake('public');

        // Tạo UploadedFile từ file test có sẵn
        $avatar = UploadedFile::fake()->createWithContent('avatar.png', $this->testImageContent)->mimeType('image/png');

        // Đảm bảo thư mục public/uploads/avatars tồn tại
        $avatarDir = public_path('uploads/avatars');
        if (!file_exists($avatarDir)) {
            mkdir($avatarDir, 0755, true);
        }

        $response = $this->post(route('owner.profile.avatar'), [
            'avatar' => $avatar,
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->owner->refresh();
        $this->assertNotNull($this->owner->avatar);

        $filePath = public_path('uploads/avatars/' . $this->owner->avatar);
        $this->assertFileExists($filePath);

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function test_owner_can_create_field_with_image()
    {
        $this->actingAs($this->owner);

        Storage::fake('public');

        $image = UploadedFile::fake()->createWithContent('field.png', $this->testImageContent)->mimeType('image/png');

        $response = $this->post(route('owner.fields.store'), [
            'name' => 'Sân Bóng Mini A',
            'sport_id' => $this->sport->id,
            'code' => 'SBMA',
            'description' => 'Sân cỏ nhân tạo chất lượng cao',
            'address' => '123 Đường ABC, Quận 1',
            'price_per_hour' => 150000,
            'open_time' => '06:00',
            'close_time' => '22:00',
            'status' => 'active',
            'image' => $image,
        ]);

        $response->assertStatus(302);
        $response->assertRedirect(route('owner.fields.index'));
        $response->assertSessionHasNoErrors();

        $field = Field::where('code', 'SBMA')->first();
        $this->assertNotNull($field);
        $this->assertNotNull($field->image);
        $this->assertNotNull($field->image_url);

        Storage::disk('public')->assertExists($field->image);
    }
}
