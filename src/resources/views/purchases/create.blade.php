@extends('layouts.app')

@section('title', '商品購入')

@section('content')
<div class="purchase">
    <div class="purchase__main">
        <div class="purchase__product">
            <div class="purchase__product-image">
                @if($item->image)
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                @else
                    <span>商品画像</span>
                @endif
            </div>
            <div class="purchase__product-info">
                <h1 class="purchase__product-name">{{ $item->name }}</h1>
                <p class="purchase__product-price">¥ {{ number_format($item->price) }}</p>
            </div>
        </div>

        <div class="purchase__divider"></div>

        <div class="purchase__section">
            <h2 class="purchase__section-title">支払い方法</h2>
            <select name="payment_method" id="payment_method" class="purchase__select" form="purchase-form">
                <option value="">選択してください</option>
                <option value="コンビニ支払い" {{ old('payment_method', session('payment_method')) === 'コンビニ支払い' ? 'selected' : '' }}>コンビニ支払い</option>
                <option value="カード支払い" {{ old('payment_method', session('payment_method')) === 'カード支払い' ? 'selected' : '' }}>カード支払い</option>
            </select>
            @error('payment_method')
                <p class="purchase__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="purchase__divider"></div>

        <div class="purchase__section">
            <div class="purchase__section-header">
                <h2 class="purchase__section-title">配送先</h2>
                <a href="{{ route('address.edit', $item->id) }}" class="purchase__change-link">変更する</a>
            </div>
            <div class="purchase__address">
                <p>〒 {{ $profile->postal_code ?? '' }}</p>
                <p>{{ $profile->address ?? '' }}{{ $profile->building ?? '' }}</p>
            </div>
            @error('postal_code')
                <p class="purchase__error">{{ $message }}</p>
            @enderror
            @error('address')
                <p class="purchase__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="purchase__divider"></div>
    </div>

    <div class="purchase__sidebar">
        <div class="purchase__summary">
            <div class="purchase__summary-row">
                <span class="purchase__summary-label">商品代金</span>
                <span class="purchase__summary-value">¥ {{ number_format($item->price) }}</span>
            </div>
            <div class="purchase__summary-row">
                <span class="purchase__summary-label">支払い方法</span>
                <span class="purchase__summary-value" id="selected-payment">{{ old('payment_method', session('payment_method')) ?: '未選択' }}</span>
            </div>
        </div>

        <form id="purchase-form" action="{{ route('purchase.store', $item->id) }}" method="POST" novalidate>
            @csrf
            <input type="hidden" name="postal_code" value="{{ $profile->postal_code ?? '' }}">
            <input type="hidden" name="address" value="{{ $profile->address ?? '' }}">
            <input type="hidden" name="building" value="{{ $profile->building ?? '' }}">
            <button type="submit" class="purchase__button">購入する</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        const selectedPayment = document.getElementById('selected-payment');
        selectedPayment.textContent = this.value || '未選択';
    });

    document.getElementById('purchase-form').addEventListener('submit', function() {
        const paymentMethod = document.getElementById('payment_method').value;
        if (paymentMethod === 'コンビニ支払い') {
            this.target = '_blank';
        } else {
            this.target = '_self';
        }
    });
</script>
@endsection
