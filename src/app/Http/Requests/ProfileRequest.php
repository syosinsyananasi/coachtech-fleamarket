<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png'],
            'name' => ['required', 'max:20'],
            'postal_code' => ['required', 'max:8'],
            'address' => ['required'],
            'building' => ['nullable'],
        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください',
            'name.required' => 'ユーザー名を入力してください',
            'name.max' => 'ユーザー名は20文字以内で入力してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.max' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
