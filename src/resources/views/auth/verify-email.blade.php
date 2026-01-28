@extends('layouts.auth')

@section('title', 'メール認証')

@section('content')
<div class="auth-container">
    <div class="verify-email">
        <p class="verify-email__message">
            登録していただいたメールアドレスに認証メールを送付しました。<br>
            メール認証を完了してください。
        </p>

        <a href="{{ route('verification.verify.redirect') }}" class="verify-email__button">
            認証はこちらから
        </a>

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="verify-email__resend">認証メールを再送する</button>
        </form>
    </div>
</div>
@endsection
