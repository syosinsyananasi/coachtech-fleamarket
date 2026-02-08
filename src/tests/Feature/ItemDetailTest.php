<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_required_info_is_displayed()
    {
        $user = User::factory()->create(['name' => 'コメントユーザー']);
        $condition = Condition::create(['name' => '良好']);
        $category = Category::create(['name' => 'ファッション']);

        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明文',
            'price' => 5000,
            'image' => 'test.jpg',
        ]);

        $item->categories()->attach($category->id);

        Comment::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント内容',
        ]);

        $user->favorites()->attach($item->id);

        $response = $this->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('storage/test.jpg');
        $response->assertSee('テスト商品');
        $response->assertSee('テストブランド');
        $response->assertSee('5,000');
        $response->assertSee('テスト商品の説明文');
        $response->assertSee('ファッション');
        $response->assertSee('良好');
        $response->assertSee('コメントユーザー');
        $response->assertSee('テストコメント内容');
        $response->assertSeeInOrder([
            'heart-default.png',
            '1',
            'comment-icon.png',
            '1',
        ]);
    }

    public function test_multiple_categories_are_displayed()
    {
        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);
        $category1 = Category::create(['name' => 'ファッション']);
        $category2 = Category::create(['name' => '家電']);
        $category3 = Category::create(['name' => 'インテリア']);

        $item = Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);

        $item->categories()->attach([$category1->id, $category2->id, $category3->id]);

        $response = $this->get(route('item.show', $item));

        $response->assertStatus(200);
        $response->assertSee('ファッション');
        $response->assertSee('家電');
        $response->assertSee('インテリア');
    }
}
