@extends('layouts.app')

@section('title', $item->name)

@section('content')
<div class="item-detail">
    <div class="item-detail__image">
        @if($item->image)
            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
        @else
            <span class="item-detail__image-placeholder">商品画像</span>
        @endif
    </div>

    <div class="item-detail__info">
        <h1 class="item-detail__name">{{ $item->name }}</h1>
        <p class="item-detail__brand">{{ $item->brand ?? 'ブランド名' }}</p>
        <p class="item-detail__price">
            <span class="item-detail__price-yen">¥</span>{{ number_format($item->price) }}<span class="item-detail__price-tax">(税込)</span>
        </p>

        <div class="item-detail__actions">
            @auth
                <form action="{{ $item->isFavoritedBy(auth()->user()) ? route('favorite.destroy', $item->id) : route('favorite.store', $item->id) }}" method="POST" class="item-detail__favorite-form">
                    @csrf
                    @if($item->isFavoritedBy(auth()->user()))
                        @method('DELETE')
                        <button type="submit" class="item-detail__favorite item-detail__favorite--active">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="#ff5555" stroke="#ff5555" stroke-width="1">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    @else
                        <button type="submit" class="item-detail__favorite">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1">
                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                            </svg>
                        </button>
                    @endif
                </form>
            @else
                <span class="item-detail__favorite">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1">
                        <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                    </svg>
                </span>
            @endauth
            <span class="item-detail__count">{{ $item->favorites->count() }}</span>

            <span class="item-detail__comment-icon">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#000000" stroke-width="1">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </span>
            <span class="item-detail__count">{{ $item->comments->count() }}</span>
        </div>

        @if(!$item->is_sold)
            <a href="{{ route('purchase.create', $item->id) }}" class="item-detail__purchase-button">購入手続きへ</a>
        @else
            <span class="item-detail__sold-button">売り切れ</span>
        @endif

        <div class="item-detail__description">
            <h2 class="item-detail__section-title">商品説明</h2>
            <p class="item-detail__description-text">{!! nl2br(e($item->description)) !!}</p>
        </div>

        <div class="item-detail__info-section">
            <h2 class="item-detail__section-title">商品の情報</h2>

            <div class="item-detail__info-row">
                <span class="item-detail__info-label">カテゴリー</span>
                <div class="item-detail__categories">
                    @foreach($item->categories as $category)
                        <span class="item-detail__category">{{ $category->name }}</span>
                    @endforeach
                </div>
            </div>

            <div class="item-detail__info-row">
                <span class="item-detail__info-label">商品の状態</span>
                <span class="item-detail__info-value">{{ $item->condition->name }}</span>
            </div>
        </div>

        <div class="item-detail__comments">
            <h2 class="item-detail__comments-title">コメント({{ $item->comments->count() }})</h2>

            <div class="item-detail__comments-list">
                @foreach($item->comments as $comment)
                    <div class="item-detail__comment">
                        <div class="item-detail__comment-user">
                            <div class="item-detail__comment-avatar">
                                @if($comment->user->profile && $comment->user->profile->profile_image)
                                    <img src="{{ asset('storage/' . $comment->user->profile->profile_image) }}" alt="{{ $comment->user->name }}">
                                @endif
                            </div>
                            <span class="item-detail__comment-name">{{ $comment->user->name }}</span>
                        </div>
                        <p class="item-detail__comment-text">{{ $comment->content }}</p>
                    </div>
                @endforeach
            </div>

            @auth
                <div class="item-detail__comment-form">
                    <h3 class="item-detail__comment-form-title">商品へのコメント</h3>
                    <form action="{{ route('comment.store', $item->id) }}" method="POST">
                        @csrf
                        <textarea name="content" class="item-detail__comment-input" rows="5">{{ old('content') }}</textarea>
                        @error('content')
                            <p class="item-detail__error">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="item-detail__comment-button">コメントを送信する</button>
                    </form>
                </div>
            @endauth
        </div>
    </div>
</div>
@endsection
