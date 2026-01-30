@extends('layouts.app')

@section('title', 'マイページ')

@section('content')
<div class="mypage">
    <div class="mypage__profile">
        <div class="mypage__avatar">
            @if($user->profile && $user->profile->profile_image)
                <img src="{{ asset('storage/' . $user->profile->profile_image) }}" alt="{{ $user->name }}">
            @endif
        </div>
        <div class="mypage__user-info">
            <h1 class="mypage__username">{{ $user->name }}</h1>
            <a href="{{ route('mypage.edit') }}" class="mypage__edit-button">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage__tabs">
        <a href="{{ route('mypage.index', ['page' => 'sell']) }}" class="mypage__tab {{ $page === 'sell' ? 'mypage__tab--active' : '' }}">出品した商品</a>
        <a href="{{ route('mypage.index', ['page' => 'buy']) }}" class="mypage__tab {{ $page === 'buy' ? 'mypage__tab--active' : '' }}">購入した商品</a>
    </div>

    <div class="items-grid">
        @forelse($items as $item)
            <a href="{{ route('item.show', $item) }}" class="product-card">
                <div class="product-card__image">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    @else
                        商品画像
                    @endif
                    @if($item->status !== 'available')
                        <span class="product-card__sold">Sold</span>
                    @endif
                </div>
                <p class="product-card__name">{{ $item->name }}</p>
            </a>
        @empty
            <p class="mypage__empty">商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
