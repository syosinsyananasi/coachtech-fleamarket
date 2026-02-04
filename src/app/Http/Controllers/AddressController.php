<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit(Item $item)
    {
        return view('purchases.address', compact('item'));
    }

    public function update(AddressRequest $request, Item $item)
    {
        $profile = auth()->user()->profile;

        if ($profile) {
            $profile->update([
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]);
        }

        return redirect()->route('purchase.create', $item);
    }
}
