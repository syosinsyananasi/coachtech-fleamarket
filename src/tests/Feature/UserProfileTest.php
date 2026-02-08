<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_displays_required_info()
    {
        $user = User::factory()->create(['name' => 'テストユーザー']);

        Profile::create([
            'user_id' => $user->id,
            'profile_image' => 'profiles/test.jpg',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $condition = Condition::create(['name' => '良好']);

        $listedItem = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => '出品商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        $seller = User::factory()->create();
        $purchasedItem = Item::create([
            'user_id' => $seller->id,
            'condition_id' => $condition->id,
            'name' => '購入商品',
            'description' => 'テスト説明',
            'price' => 2000,
            'image' => 'test.jpg',
            'status' => 'sold',
        ]);

        Purchase::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'payment_method' => 'カード支払い',
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('mypage.index', ['page' => 'sell']));
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('profiles/test.jpg');
        $response->assertSee('出品商品');

        $response = $this->get(route('mypage.index', ['page' => 'buy']));
        $response->assertStatus(200);
        $response->assertSee('購入商品');
    }
}
