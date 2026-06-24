<?php

namespace Tests\Feature\Admin;

use App\Models\Field;
use App\Models\Role;
use App\Models\Sport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FieldCodeGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_field_code_is_automatically_generated_when_creating_without_code(): void
    {
        $ownerRole = Role::create(['name' => 'owner', 'display_name' => 'Owner']);
        $owner = User::create([
            'role_id' => $ownerRole->id,
            'name' => 'Owner Account',
            'email' => 'owner@test.com',
            'password' => bcrypt('Password123!'),
        ]);

        $sport = Sport::create([
            'name' => 'Bóng đá',
            'slug' => 'bong-da',
            'is_active' => true,
        ]);

        // Create without code
        $field = Field::create([
            'owner_id' => $owner->id,
            'sport_id' => $sport->id,
            'name' => 'Sân A',
            'address' => 'Hanoi',
            'price_per_hour' => 100000,
            'status' => 'active',
        ]);

        $this->assertNotEmpty($field->code);
        $this->assertEquals('SBD-001', $field->code);

        // Create second field without code
        $field2 = Field::create([
            'owner_id' => $owner->id,
            'sport_id' => $sport->id,
            'name' => 'Sân B',
            'address' => 'Hanoi',
            'price_per_hour' => 100000,
            'status' => 'active',
        ]);

        $this->assertEquals('SBD-002', $field2->code);
    }
}
