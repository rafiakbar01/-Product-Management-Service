<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:64|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'product_type' => 'required|in:physical,digital,service',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'min_stock_alert' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,draft',
            'description' => 'nullable|string',
        ];
    }

    /**
     * Custom Indonesian messages for validation
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama produk wajib diisi.',
            'sku.required' => 'SKU produk wajib diisi.',
            'sku.unique' => 'SKU tersebut sudah terdaftar dalam sistem.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'price.required' => 'Harga jual produk wajib diisi.',
            'price.numeric' => 'Harga jual harus berupa angka valid.',
            'price.min' => 'Harga jual tidak boleh kurang dari 0.',
            'stock.required' => 'Jumlah stok awal produk wajib diisi.',
            'stock.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh negatif.',
            'min_stock_alert.required' => 'Batas minimum notifikasi stok wajib ditentukan.',
        ];
    }

    /**
     * Handle failed validation for API calls
     */
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson() || $this->is('api/*')) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Validasi input produk gagal.',
                'errors' => $validator->errors(),
            ], 422));
        }

        parent::failedValidation($validator);
    }
}
