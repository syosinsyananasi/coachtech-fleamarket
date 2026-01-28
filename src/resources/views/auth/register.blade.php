@extends('layouts.auth')

@section('title', '会員登録')

@section('content')
<div class="auth-container">
    <form class="auth-form" action="{{ route('register') }}" method="POST" novalidate>
        @csrf

        <h1 class="auth-form__title">会員登録</h1>

        <div class="auth-form__group">
            <label class="auth-form__label" for="name">ユーザー名</label>
            <input type="text" name="name" id="name" class="auth-form__input" value="{{ old('name') }}">
            @error('name')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-form__group">
            <label class="auth-form__label" for="email">メールアドレス</label>
            <input type="email" name="email" id="email" class="auth-form__input" value="{{ old('email') }}">
            @error('email')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-form__group">
            <label class="auth-form__label" for="password">パスワード</label>
            <input type="password" name="password" id="password" class="auth-form__input">
            @error('password')
                <p class="auth-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-form__group">
            <label class="auth-form__label" for="password_confirmation">確認用パスワード</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="auth-form__input">
        </div>

        <button type="submit" class="auth-form__button">登録する</button>

        <a href="{{ route('login') }}" class="auth-form__link">ログインはこちら</a>
    </form>
</div>
@endsection
