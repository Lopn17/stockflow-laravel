<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'name'           => ['required', 'string', 'max:255'],
            'sku'            => ['required', 'string', 'max:50', 'unique:products,sku'],
            'barcode'        => ['nullable', 'string', 'max:100'],
            'category_id'    => ['required', 'exists:categories,id'],
            'supplier_id'    => ['required', 'exists:suppliers,id'],
            'description'    => ['nullable', 'string'],
            'purchase_price' => ['required', 'numeric', 'min:0'],
            'selling_price'  => ['required', 'numeric', 'min:0'],
            'minimum_stock'  => ['required', 'integer', 'min:0'],
            'current_stock'  => ['required', 'integer', 'min:0'],
            'image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}