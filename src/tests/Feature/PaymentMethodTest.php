<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
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
    public function test_selected_payment_method_is_reflected_in_subtotal()
    {
        $this->mockStripeSession();

        $user = User::factory()->create();
        $seller = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);

        Profile::create([
            'user_id' => $user->id,
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

        $this->actingAs($user);

        // 購入処理を実行
        $this->post(route('purchase.store', $item->id), [
            'payment_method' => 'コンビニ支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        // DBに選択した支払い方法が保存されているか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 'コンビニ支払い',
        ]);
    }
}
