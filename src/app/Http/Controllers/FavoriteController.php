<?php

namespace App\Http\Controllers;

use App\Models\Item;

class FavoriteController extends Controller
{
    public function store(Item $item)
    {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $user->favorites()->attach($item->id);
    return back();
    }

    public function destroy(Item $item)
    {
    /** @var \App\Models\User $user */
    $user = auth()->user();
    $user->favorites()->detach($item->id);
    return back();
    }
}
