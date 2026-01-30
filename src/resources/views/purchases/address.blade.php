@extends('layouts.app')

@section('title', '住所の変更')

@section('content')
<div class="address-container">
    <h1 class="address-form__title">住所の変更</h1>

    <form class="address-form" action="{{ route('address.update', $item->id) }}" method="POST" novalidate>
        @csrf

        <div class="address-form__group">
            <label class="address-form__label" for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="address-form__input" value="{{ old('postal_code', $shippingAddress['postal_code'] ?? '') }}">
            @error('postal_code')
                <p class="address-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address-form__group">
            <label class="address-form__label" for="address">住所</label>
            <input type="text" name="address" id="address" class="address-form__input" value="{{ old('address', $shippingAddress['address'] ?? '') }}">
            @error('address')
                <p class="address-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="address-form__group">
            <label class="address-form__label" for="building">建物名</label>
            <input type="text" name="building" id="building" class="address-form__input" value="{{ old('building', $shippingAddress['building'] ?? '') }}">
            @error('building')
                <p class="address-form__error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="address-form__button">更新する</button>
    </form>
</div>
@endsection
