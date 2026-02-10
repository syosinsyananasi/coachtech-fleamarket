<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodTest extends TestCase
{
    use RefreshDatabase;

    // 小計画面で変更が即時反映される
    public function test_selected_payment_method_is_reflected_in_subtotal()
    {
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
        ]);

        $this->actingAs($user);

        $this->get(route('purchase.create', $item))->assertStatus(200);

        $response = $this->withSession(['payment_method' => 'コンビニ支払い'])
            ->get(route('purchase.create', $item));

        $response->assertStatus(200);
        $response->assertSee('<dd class="purchase__summary-value" id="selected-payment">コンビニ支払い</dd>', false);
    }
}
