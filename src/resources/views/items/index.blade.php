@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<div class="items-tabs">
    <a href="{{ route('item.index') }}" class="items-tabs__tab {{ !request('tab') || request('tab') === 'recommend' ? 'items-tabs__tab--active' : '' }}">おすすめ</a>
    <a href="{{ route('item.index', ['tab' => 'mylist']) }}" class="items-tabs__tab {{ request('tab') === 'mylist' ? 'items-tabs__tab--active' : '' }}">マイリスト</a>
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
        <p>商品がありません。</p>
    @endforelse
</div>
@endsection
