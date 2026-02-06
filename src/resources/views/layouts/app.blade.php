<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'coachtech fleamarket')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @yield('styles')
</head>
<body>
    <header class="main-header">
        <a href="/">
            <img src="{{ asset('images/logo-white.png') }}" alt="COACHTECH" class="main-header__logo">
        </a>

        <div class="main-header__search">
            <form action="{{ route('item.index') }}" method="GET">
                <label for="keyword" class="sr-only">商品を検索</label>
                <input type="text" name="keyword" id="keyword" class="main-header__search-input" placeholder="なにをお探しですか？" value="{{ request('keyword') }}">
            </form>
        </div>

        <nav class="main-header__nav">
            @auth
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="main-header__nav-link" style="background: none; border: none; cursor: pointer;">ログアウト</button>
                </form>
                <a href="{{ route('mypage.index') }}" class="main-header__nav-link">マイページ</a>
            @else
                <a href="{{ route('login') }}" class="main-header__nav-link">ログイン</a>
                <a href="{{ route('mypage.index') }}" class="main-header__nav-link">マイページ</a>
            @endauth
            <a href="{{ route('sell.create') }}" class="main-header__nav-button">出品</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/novalidate.js') }}"></script>
    @yield('scripts')
</body>
</html>
