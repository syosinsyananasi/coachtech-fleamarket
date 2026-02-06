@extends('layouts.app')

@section('title', 'プロフィール設定')

@section('content')
<div class="profile-container">
    <h1 class="profile-form__title">プロフィール設定</h1>

    <form class="profile-form" action="{{ route('mypage.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="profile-form__avatar">
            <div class="profile-form__avatar-image">
                @if(isset($profile) && $profile->profile_image)
                    <img src="{{ asset('storage/' . $profile->profile_image) }}" alt="プロフィール画像">
                @endif
            </div>
            <label class="profile-form__avatar-button">
                画像を選択する
                <input type="file" name="profile_image" class="profile-form__avatar-input" accept="image/*" data-image-preview=".profile-form__avatar-image" data-preview-alt="プロフィール画像">
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
            <label class="profile-form__label" for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="profile-form__input" value="{{ old('postal_code', $profile->postal_code ?? '') }}">
            @error('postal_code')
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

@section('scripts')
<script src="{{ asset('js/image-preview.js') }}"></script>
@endsection
