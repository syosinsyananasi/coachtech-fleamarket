<?php

namespace App\Http\Controllers;

use App\Models\Item;

class FavoriteController extends Controller
{
    public function store($item_id)
    {
        auth()->user()->favorites()->attach($item_id);
        return back();
    }

    public function destroy($item_id)
    {
        auth()->user()->favorites()->detach($item_id);
        return back();
    }
}
