@extends('layouts.auth')

@section('title', 'ログイン')

@section('content')
<div class="auth-container">
    <form class="auth-form" action="{{ route('login') }}" method="POST" novalidate>
        @csrf

        <h1 class="auth-form__title">ログイン</h1>

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

        <button type="submit" class="auth-form__button">ログインする</button>

        <a href="{{ route('register') }}" class="auth-form__link">会員登録はこちら</a>
    </form>
</div>
@endsection
