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

        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_code' => $request->postal_code,
            'address' => $request->address,
            'building' => $request->building,
        ]);

        session(['purchase_item_id' => $item->id]);

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
            'success_url' => route('purchase.paymentSuccess'),
            'cancel_url' => route('purchase.create', $item),
        ];

        // コンビニ支払いの有効期限（3日間）
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

    public function handlePaymentSuccess()
    {
        return $this->completePurchase();
    }

    private function completePurchase()
    {
        $itemId = session('purchase_item_id');

        if (!$itemId) {
            return redirect('/');
        }

        $item = Item::findOrFail($itemId);
        $item->update(['status' => Item::STATUS_SOLD]);

        session()->forget('purchase_item_id');

        return redirect('/');
    }
}
