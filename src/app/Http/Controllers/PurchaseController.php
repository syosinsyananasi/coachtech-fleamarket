<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function create($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();
        $profile = $user->profile;

        return view('purchases.create', compact('item', 'profile'));
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item_id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        $item->update(['is_sold' => true]);

        return redirect('/');
    }
}
