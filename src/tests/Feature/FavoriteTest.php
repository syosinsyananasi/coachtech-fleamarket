<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private function createItem($user)
    {
        $condition = Condition::firstOrCreate(['name' => '良好']);

        return Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);
    }

    public function test_user_can_favorite_an_item()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();
        $item = $this->createItem($otherUser);

        $this->actingAs($user);

        $response = $this->get(route('item.show', $item));
        $response->assertSee('<span class="item-detail__count">0</span>', false);

        $this->post(route('favorite.store', $item));

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', $item));
        $response->assertSee('<span class="item-detail__count">1</span>', false);
    }

    // 追加済みのアイコンは色が変化する
    public function test_favorited_icon_changes_color()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();
        $item = $this->createItem($otherUser);

        $user->favorites()->attach($item->id);

        $this->actingAs($user);
        $response = $this->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('heart-active.png');
    }

    // 再度いいねアイコンを押下することによって、いいねを解除することができる
    public function test_user_can_unfavorite_an_item()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();
        $item = $this->createItem($otherUser);

        $user->favorites()->attach($item->id);

        $this->actingAs($user);

        $response = $this->get(route('item.show', $item));
        $response->assertSee('heart-active.png');
        $response->assertSee('<span class="item-detail__count">1</span>', false);

        $this->delete(route('favorite.destroy', $item));

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        $response = $this->get(route('item.show', $item));
        $response->assertSee('heart-default.png');
        $response->assertSee('<span class="item-detail__count">0</span>', false);
    }
}
