<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function create(Item $item)
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('purchases.create', compact('item', 'profile'));
    }

    public function store(PurchaseRequest $request, Item $item)
    {
        if ($item->status !== Item::STATUS_AVAILABLE) {
            return redirect()->route('item.show', $item)
                ->with('error', 'この商品は現在購入できません。');
        }

        $item->update(['status' => Item::STATUS_PENDING]);

        session([
            'purchase_data' => [
                'item_id' => $item->id,
                'payment_method' => $request->payment_method,
                'postal_code' => $request->postal_code,
                'address' => $request->address,
                'building' => $request->building,
            ]
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethodTypes = $request->payment_method === 'カード支払い' ? ['card'] : ['konbini'];

        $sessionParams = [
            'payment_method_types' => $paymentMethodTypes,
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success'),
            'cancel_url' => route('purchase.create', $item),
        ];

        if ($request->payment_method === 'コンビニ支払い') {
            $sessionParams['payment_method_options'] = [
                'konbini' => [
                    'expires_after_days' => 3,
                ],
            ];
        }

        $session = Session::create($sessionParams);

        return redirect($session->url);
    }

    public function success()
    {
        return $this->completePurchase();
    }

    private function completePurchase()
    {
        $purchaseData = session('purchase_data');

        if (!$purchaseData) {
            return redirect('/');
        }

        $item = Item::findOrFail($purchaseData['item_id']);

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $purchaseData['item_id'],
            'payment_method' => $purchaseData['payment_method'],
            'postal_code' => $purchaseData['postal_code'],
            'address' => $purchaseData['address'],
            'building' => $purchaseData['building'],
        ]);

        $item->update(['status' => Item::STATUS_SOLD]);

        session()->forget('purchase_data');

        return redirect('/');
    }
}
