<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_completes_successfully()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'status' => 'available',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $item->update(['status' => 'pending']);

        $this->actingAs($buyer);

        $response = $this->withSession(['purchase_item_id' => $item->id])
            ->get(route('purchase.paymentSuccess'));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $response->assertRedirect('/');
    }

    public function test_sold_item_shows_sold_on_index()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入済み商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'status' => 'available',
        ]);

        $this->actingAs($buyer);

        // 商品購入画面を開く
        $this->get(route('purchase.create', $item->id))
            ->assertStatus(200);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $item->update(['status' => 'pending']);

        $this->withSession(['purchase_item_id' => $item->id])
            ->get(route('purchase.paymentSuccess'));

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<span class="product-card__sold">Sold</span>', false);
    }

    public function test_purchased_item_appears_in_profile()
    {
        $buyer = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入した商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
            'status' => 'sold',
        ]);

        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $this->actingAs($buyer);
        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}
