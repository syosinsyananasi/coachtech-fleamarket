<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchases.address', compact('item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $profile = auth()->user()->profile;

        if ($profile) {
            $profile->update([
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]);
        }

        return redirect()->route('purchase.create', ['item_id' => $item_id]);
    }
}
