<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Condition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ExhibitionTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_can_be_created_with_valid_data()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $condition = Condition::create(['name' => '良好']);
        $category1 = Category::create(['name' => 'ファッション']);
        $category2 = Category::create(['name' => '家電']);

        $this->actingAs($user);

        $response = $this->post(route('sell.store'), [
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
            'condition_id' => $condition->id,
            'categories' => [$category1->id, $category2->id],
            'image' => UploadedFile::fake()->image('item.jpg'),
        ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト商品の説明',
            'price' => 5000,
            'condition_id' => $condition->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category1->id,
        ]);

        $this->assertDatabaseHas('category_item', [
            'category_id' => $category2->id,
        ]);
    }
}
