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
    <header class="auth-header">
        <a href="/">
            <img src="{{ asset('images/logo-white.png') }}" alt="COACHTECH" class="auth-header__logo">
        </a>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/novalidate.js') }}"></script>
    @yield('scripts')
</body>
</html>
