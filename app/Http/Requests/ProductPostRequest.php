<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
//        if (session('account_role') == 'admin' ) {
//            return true;
//        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'item_title' => 'required|string|unique:products',
            'product_category' => 'required',
            'price' => 'required',
            'unit' => 'required',
            'points' => 'required'
        ];
    }
}
