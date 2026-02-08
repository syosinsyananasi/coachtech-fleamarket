<?php

namespace Tests\Feature;

use App\Models\Condition;
use App\Models\Item;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    private function createItem($user)
    {
        $condition = Condition::firstOrCreate(['name' => '良好']);

        return Item::create([
            'user_id' => $user->id,
            'condition_id' => $condition->id,
            'name' => 'テスト商品',
            'description' => 'テスト説明',
            'price' => 1000,
            'image' => 'test.jpg',
        ]);
    }

    // ログイン済みのユーザーはコメントを送信できる
    public function test_authenticated_user_can_post_comment()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $item = $this->createItem($user);

        $this->actingAs($user);
        $response = $this->post(route('comment.store', $item), [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('comments', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'content' => 'テストコメント',
        ]);
    }

    // ログイン前のユーザーはコメントを送信できない
    public function test_unauthenticated_user_cannot_post_comment()
    {
        $user = User::factory()->create();
        $item = $this->createItem($user);

        $response = $this->post(route('comment.store', $item), [
            'content' => 'テストコメント',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_comment_content_is_required()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $item = $this->createItem($user);

        $this->actingAs($user);
        $response = $this->post(route('comment.store', $item), [
            'content' => '',
        ]);

        $response->assertSessionHasErrors('content');
    }

    public function test_comment_must_not_exceed_255_characters()
    {
        $user = User::factory()->create();
        Profile::create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都渋谷区',
        ]);
        $item = $this->createItem($user);

        $this->actingAs($user);
        $response = $this->post(route('comment.store', $item), [
            'content' => str_repeat('a', 256),
        ]);

        $response->assertSessionHasErrors('content');
    }
}
