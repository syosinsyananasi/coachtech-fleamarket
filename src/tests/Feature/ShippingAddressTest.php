<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShippingAddressTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_purchase_has_correct_shipping_address()
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

        $this->post(route('address.update', $item), [
            'postal_code' => '999-8888',
            'address' => '新しい住所',
            'building' => '新しいビル101',
        ]);

        $profile = $user->profile->fresh();

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'カード支払い',
            'postal_code' => $profile->postal_code,
            'address' => $profile->address,
            'building' => $profile->building,
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
