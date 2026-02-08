<?php

namespace Tests\Feature;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_edit_page_shows_initial_values()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'profiles/test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区テスト町',
            'building' => 'テストビル101',
        ]);

        $this->actingAs($user);
        $response = $this->get(route('mypage.edit'));

        $response->assertStatus(200);
        $response->assertSee('profiles/test.jpg');
        $response->assertSee('テストユーザー');
        $response->assertSee('123-4567');
        $response->assertSee('東京都渋谷区テスト町');
        $response->assertSee('テストビル101');
    }
}
