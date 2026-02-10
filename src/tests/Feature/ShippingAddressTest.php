<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

    private function mockStripeSession()
    {
        $mock = Mockery::mock('alias:' . \Stripe\Checkout\Session::class);
        $mock->shouldReceive('create')
            ->andReturn((object) ['url' => route('purchase.paymentSuccess')]);
    }

    public function test_changed_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '旧住所',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        $this->actingAs($user);

        $this->get(route('address.edit', $item))->assertStatus(200);

        $this->post(route('address.update', $item), [
            'postal_code' => '999-8888',
            'address' => '新しい住所',
            'building' => '新しいビル101',
        ]);

        $response = $this->get(route('purchase.create', $item));

        $response->assertStatus(200);
        $response->assertSee('999-8888');
        $response->assertSee('新しい住所');
        $response->assertSee('新しいビル101');
    }

    /**
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
    public function test_purchase_has_correct_shipping_address()
    {
        $this->mockStripeSession();

        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '111-1111',
            'address' => '旧住所',
        ]);

        $item = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        $this->actingAs($user);

        // 住所変更画面を開く
        $this->get(route('address.edit', $item))->assertStatus(200);

        // 住所を変更する
        $this->post(route('address.update', $item), [
            'postal_code' => '999-8888',
            'address' => '新しい住所',
            'building' => '新しいビル101',
        ]);

        // 商品購入画面を開く
        $this->get(route('purchase.create', $item->id))->assertStatus(200);

        // 商品を購入する
        $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'カード支払い',
            'postal_code' => '999-8888',
            'address' => '新しい住所',
            'building' => '新しいビル101',
        ]);

        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'postal_code' => '999-8888',
            'address' => '新しい住所',
            'building' => '新しいビル101',
        ]);
    }
}
