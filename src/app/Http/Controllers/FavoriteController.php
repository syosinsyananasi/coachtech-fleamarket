<?php

namespace App\Http\Controllers;

use App\Models\Item;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        $item = Item::findOrFail($item_id);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->favorites()->attach($item->id);
        return back();
    }

    public function destroy($item_id)
    {
        $item = Item::findOrFail($item_id);
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->favorites()->detach($item->id);
        return back();
    }
}
