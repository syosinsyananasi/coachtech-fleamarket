<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;

class MypageController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $page = request()->query('page', 'sell');
        /** @var \App\Models\User $user */
        if ($page === 'buy') {
            $items = $user->purchases()->with('item')->get()->pluck('item');
        } else {
            $items = $user->items;
        }

        return view('mypage.index', compact('user', 'items', 'page'));
    }

    public function edit()
    {
        $user = auth()->user();
        /** @var \App\Models\User $user */
        $profile = $user->profile;
        return view('mypage.edit', compact('user', 'profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = auth()->user();
        /** @var \App\Models\User $user */
        $user->update(['name' => $request->name]);

        $profileData = [
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ];

        if ($request->hasFile('profile_image')) {
            $profileData['profile_image'] = $request->file('profile_image')->store('profiles', 'public');
        }

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            $profileData
        );

        return redirect()->route('item.index');
    }
}
