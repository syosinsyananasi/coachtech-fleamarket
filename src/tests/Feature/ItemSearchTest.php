<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_partial_match_search_by_name()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト腕時計',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テストバッグ',
            'description' => '説明',
            'price' => 2000,
            'image' => 'test.jpg',
        ]);

        $response = $this->get('/?keyword=腕時計');

        $response->assertStatus(200);
        $response->assertSee('テスト腕時計');
        $response->assertDontSee('テストバッグ');
    }

    // 検索状態がマイリストでも保持されている
    public function test_search_state_is_preserved_on_mylist()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        $item = Item::create([
            'user_id' => $otherUser->id,
            'condition_id' => $condition->id,
            'name' => 'テスト腕時計',
            'description' => '説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        $user->favorites()->attach($item->id);

        $this->actingAs($user);

        $response = $this->get('/?keyword=腕時計');
        $response->assertStatus(200);
        $response->assertSee('テスト腕時計');
        $response->assertSee('value="腕時計"', false);

        $response = $this->get('/?tab=mylist&keyword=腕時計');
        $response->assertStatus(200);
        $response->assertSee('テスト腕時計');
        $response->assertSee('value="腕時計"', false);
    }
}
