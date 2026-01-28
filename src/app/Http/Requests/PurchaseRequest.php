<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment_method' => ['required', 'in:コンビニ支払い,カード支払い'],
            'postal_code' => ['required', 'max:8'],
            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'payment_method.required' => '支払い方法を選択してください',
            'postal_code.required' => '配送先を選択してください',
            'address.required' => '配送先を選択してください',
        ];
    }
}
