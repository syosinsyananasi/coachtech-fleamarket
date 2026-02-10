<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    private function mockStripeSession()
    {
        $mock = Mockery::mock('alias:' . \Stripe\Checkout\Session::class);
        $mock->shouldReceive('create')
            ->andReturn((object) ['url' => route('purchase.paymentSuccess')]);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_purchase_completes_successfully()
    {
        $this->mockStripeSession();

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

        $this->actingAs($buyer);

        // 商品購入画面を開く
        $this->get(route('purchase.create', $item->id))
            ->assertStatus(200);

        // 購入ボタンを押す
        $response = $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        // Stripe決済ページにリダイレクト
        $response->assertRedirect(route('purchase.paymentSuccess'));

        // 決済成功後
        $response = $this->get(route('purchase.paymentSuccess'));

        $this->assertDatabaseHas('items', [
            'id' => $item->id,
            'status' => 'sold',
        ]);

        $response->assertRedirect('/');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_sold_item_shows_sold_on_index()
    {
        $this->mockStripeSession();

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

        // 購入ボタンを押す
        $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        // 決済成功後
        $this->get(route('purchase.paymentSuccess'));

        // 商品一覧でSold表示を確認
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<span class="product-card__sold">Sold</span>', false);
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_purchased_item_appears_in_profile()
    {
        $this->mockStripeSession();

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
            'status' => 'available',
        ]);

        $this->actingAs($buyer);

        // 商品購入画面を開く
        $this->get(route('purchase.create', $item->id))
            ->assertStatus(200);

        // 購入ボタンを押す
        $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        // 決済成功後
        $this->get(route('purchase.paymentSuccess'));

        // マイページで購入した商品が表示される
        $response = $this->get(route('mypage.index', ['page' => 'buy']));

        $response->assertStatus(200);
        $response->assertSee('購入した商品');
    }
}
