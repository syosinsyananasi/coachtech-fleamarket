<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ItemSeeder extends Seeder
{
    private const ITEMS = [
        [
            'name' => '腕時計',
            'price' => 15000,
            'brand' => 'Rolax',
            'description' => 'スタイリッシュなデザインのメンズ腕時計',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
            'condition' => '良好',
            'categories' => ['ファッション', 'メンズ', 'アクセサリー'],
        ],
        [
            'name' => 'HDD',
            'price' => 5000,
            'brand' => '西芝',
            'description' => '高速で信頼性の高いハードディスク',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
            'condition' => '目立った傷や汚れなし',
            'categories' => ['家電'],
        ],
        [
            'name' => '玉ねぎ3束',
            'price' => 300,
            'brand' => 'なし',
            'description' => '新鮮な玉ねぎ3束のセット',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
            'condition' => 'やや傷や汚れあり',
            'categories' => ['キッチン'],
        ],
        [
            'name' => '革靴',
            'price' => 4000,
            'brand' => null,
            'description' => 'クラシックなデザインの革靴',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
            'condition' => '状態が悪い',
            'categories' => ['ファッション', 'メンズ'],
        ],
        [
            'name' => 'ノートPC',
            'price' => 45000,
            'brand' => null,
            'description' => '高性能なノートパソコン',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
            'condition' => '良好',
            'categories' => ['家電'],
        ],
        [
            'name' => 'マイク',
            'price' => 8000,
            'brand' => 'なし',
            'description' => '高音質のレコーディング用マイク',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
            'condition' => '目立った傷や汚れなし',
            'categories' => ['家電'],
        ],
        [
            'name' => 'ショルダーバッグ',
            'price' => 3500,
            'brand' => null,
            'description' => 'おしゃれなショルダーバッグ',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
            'condition' => 'やや傷や汚れあり',
            'categories' => ['ファッション', 'レディース'],
        ],
        [
            'name' => 'タンブラー',
            'price' => 500,
            'brand' => 'なし',
            'description' => '使いやすいタンブラー',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
            'condition' => '状態が悪い',
            'categories' => ['キッチン'],
        ],
        [
            'name' => 'コーヒーミル',
            'price' => 4000,
            'brand' => 'Starbacks',
            'description' => '手動のコーヒーミル',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
            'condition' => '良好',
            'categories' => ['キッチン'],
        ],
        [
            'name' => 'メイクセット',
            'price' => 2500,
            'brand' => null,
            'description' => '便利なメイクアップセット',
            'image' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
            'condition' => '目立った傷や汚れなし',
            'categories' => ['コスメ', 'レディース'],
        ],
    ];

    public function run()
    {
        $user = User::first();
        $conditions = Condition::pluck('id', 'name');
        $categories = Category::pluck('id', 'name');

        Storage::disk('public')->makeDirectory('items');

        foreach (self::ITEMS as $itemData) {
            $imageContents = file_get_contents($itemData['image']);
            $imageName = 'items/' . uniqid() . '.jpg';
            Storage::disk('public')->put($imageName, $imageContents);

            $item = Item::create([
                'user_id' => $user->id,
                'condition_id' => $conditions[$itemData['condition']],
                'name' => $itemData['name'],
                'brand' => $itemData['brand'],
                'description' => $itemData['description'],
                'price' => $itemData['price'],
                'image' => $imageName,
            ]);

            $categoryIds = collect($itemData['categories'])->map(fn ($name) => $categories[$name]);
            $item->categories()->attach($categoryIds);
        }
    }
}
