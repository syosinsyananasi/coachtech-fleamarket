<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    private function createItem($user, $overrides = [])
    {
        $condition = Condition::firstOrCreate(['name' => '良好']);

        return Item::create(array_merge([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'status' => 'available',
        ], $overrides));
    }

    // いいねした商品だけが表示される
    public function test_only_favorited_items_are_displayed()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();

        $favItem = $this->createItem($otherUser, ['name' => 'いいね商品']);
        $nonFavItem = $this->createItem($otherUser, ['name' => '未いいね商品']);

        $user->favorites()->attach($favItem->id);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('いいね商品');
        $response->assertDontSee('未いいね商品');
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_favorited_items_show_sold_label()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();

        $soldItem = $this->createItem($otherUser, ['name' => '売り切れ商品', 'status' => 'sold']);
        $user->favorites()->attach($soldItem->id);

        $this->actingAs($user);
        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertSee('Sold');
    }

    // 未認証の場合は何も表示されない
    public function test_unauthenticated_user_sees_no_items()
    {
        $user = User::factory()->create();
        $this->createItem($user, ['name' => 'テスト商品']);

        $response = $this->get('/?tab=mylist');

        $response->assertStatus(200);
        $response->assertDontSee('テスト商品');
    }
}
