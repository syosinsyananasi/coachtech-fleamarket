<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemListTest extends TestCase
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

    // 全商品を取得できる
    public function test_all_items_are_displayed()
    {
        $user = User::factory()->create();
        $this->createItem($user, ['name' => '商品A']);
        $this->createItem($user, ['name' => '商品B']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('商品A');
        $response->assertSee('商品B');
    }

    // 購入済み商品は「Sold」と表示される
    public function test_sold_items_show_sold_label()
    {
        $user = User::factory()->create();
        $this->createItem($user, ['name' => '売り切れ商品', 'status' => 'sold']);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<span class="product-card__sold">Sold</span>', false);
    }

    // 自分が出品した商品は表示されない
    public function test_own_items_are_not_displayed()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $otherUser = User::factory()->create();

        $this->createItem($user, ['name' => '自分の商品']);
        $this->createItem($otherUser, ['name' => '他人の商品']);

        $this->actingAs($user);
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('自分の商品');
        $response->assertSee('他人の商品');
    }
}
