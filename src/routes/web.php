<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SellController;
use Illuminate\Support\Facades\Route;

// 商品一覧（トップ画面）
Route::get('/', [ItemController::class, 'index'])->name('item.index');

// 商品詳細
Route::get('/item/{item_id}', [ItemController::class, 'show'])->name('item.show');

// 認証必須のルート
Route::middleware(['auth', 'verified'])->group(function () {
    // 商品出品
    Route::get('/sell', [SellController::class, 'create'])->name('sell.create');
    Route::post('/sell', [SellController::class, 'store'])->name('sell.store');

    // いいね
    Route::post('/item/{item_id}/favorite', [FavoriteController::class, 'store'])->name('favorite.store');
    Route::delete('/item/{item_id}/favorite', [FavoriteController::class, 'destroy'])->name('favorite.destroy');

    // コメント
    Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');

    // 購入
    Route::get('/purchase/{item_id}', [PurchaseController::class, 'create'])->name('purchase.create');
    Route::post('/purchase/{item_id}', [PurchaseController::class, 'store'])->name('purchase.store');

    // 住所変更
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/purchase/address/{item_id}', [AddressController::class, 'update'])->name('address.update');

    // マイページ
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypage.index');
    Route::get('/mypage/profile', [MypageController::class, 'edit'])->name('mypage.edit');
    Route::post('/mypage/profile', [MypageController::class, 'update'])->name('mypage.update');
});
