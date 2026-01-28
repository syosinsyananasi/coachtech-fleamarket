<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $keyword = $request->query('keyword');

        if ($tab === 'mylist') {
            if (!auth()->check()) {
                $items = collect();
            } else {
                $items = auth()->user()->favorites()
                    ->when($keyword, function ($query) use ($keyword) {
                        return $query->where('name', 'like', '%' . $keyword . '%');
                    })
                    ->get();
            }
        } else {
            $items = Item::when(auth()->check(), function ($query) {
                    return $query->where('user_id', '!=', auth()->id());
                })
                ->when($keyword, function ($query) use ($keyword) {
                    return $query->where('name', 'like', '%' . $keyword . '%');
                })
                ->get();
        }

        return view('items.index', compact('items', 'tab', 'keyword'));
    }

    public function show($item_id)
    {
        $item = Item::with(['user', 'condition', 'categories', 'comments.user', 'favorites'])->findOrFail($item_id);
        return view('items.show', compact('item'));
    }
}
