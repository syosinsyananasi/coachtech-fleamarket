@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
<div class="profile-container">
    <h1 class="profile-form__title">プロフィール設定</h1>

    <form class="profile-form" action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="profile-form__avatar">
            <div class="profile-form__avatar-image">
                @if(isset($profile) && $profile->image)
                    <img src="{{ asset('storage/' . $profile->image) }}" alt="プロフィール画像">
                @endif
            </div>
            <label class="profile-form__avatar-button">
                画像を選択する
                <input type="file" name="image" class="profile-form__avatar-input" accept="image/*">
            </label>
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="name">ユーザー名</label>
            <input type="text" name="name" id="name" class="profile-form__input" value="{{ old('name', $user->name ?? '') }}">
            @error('name')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="postcode">郵便番号</label>
            <input type="text" name="postcode" id="postcode" class="profile-form__input" value="{{ old('postcode', $profile->postcode ?? '') }}">
            @error('postcode')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="address">住所</label>
            <input type="text" name="address" id="address" class="profile-form__input" value="{{ old('address', $profile->address ?? '') }}">
            @error('address')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="profile-form__group">
            <label class="profile-form__label" for="building">建物名</label>
            <input type="text" name="building" id="building" class="profile-form__input" value="{{ old('building', $profile->building ?? '') }}">
            @error('building')
                <p class="profile-form__error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="profile-form__button">更新する</button>
    </form>
</div>
@endsection
