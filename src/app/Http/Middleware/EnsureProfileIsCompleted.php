<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileIsCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user) {
            // メール未認証 → 認証画面へ
            if (!$user->hasVerifiedEmail()) {
                if (!$request->is('email/*') && !$request->is('logout')) {
                    return redirect()->route('verification.notice');
                }
            }
            // メール認証済み + プロフィール未設定 → プロフィール設定へ
            elseif (!$user->profile) {
                if (!$request->is('mypage/profile') && !$request->is('logout')) {
                    return redirect()->route('mypage.edit');
                }
            }
        }

        return $next($request);
    }
}
