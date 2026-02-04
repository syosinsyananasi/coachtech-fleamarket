@extends('layouts.app')

@section('title', '商品一覧')

@section('content')
<nav class="items-tabs" aria-label="商品フィルター">
    <a href="{{ route('item.index') }}" class="items-tabs__tab {{ !request('tab') || request('tab') === 'recommend' ? 'items-tabs__tab--active' : '' }}">おすすめ</a>
    <a href="{{ route('item.index', ['tab' => 'mylist']) }}" class="items-tabs__tab {{ request('tab') === 'mylist' ? 'items-tabs__tab--active' : '' }}">マイリスト</a>
</nav>

<ul class="items-grid">
    @forelse($items as $item)
        <li class="items-grid__item">
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
        </li>
    @empty
        <li><p>商品がありません。</p></li>
    @endforelse
</ul>
@endsection
