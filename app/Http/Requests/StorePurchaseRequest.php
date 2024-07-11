<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePurchaseRequest extends FormRequest {
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        return [
            'supplier_id' => 'required',
            'purchase_details' => 'required',
            'purchase_details.*.item.price' => 'required|min:1|numeric',
            'purchase_details.*.item.tax_id' => 'required',
            'purchase_details.*.item.quantity' => 'required | min: 1 | numeric',
        ];
    }

    public function messages(): array {
        return [
            'supplier_id' => 'Please provide a valid supplier id.',
            'purchase_details' => 'Please provide a valid purchase details.',
            'purchase_details.*.item.price.required' => 'Please provide a valid item price.',
            'purchase_details.*.item.price.min' => 'Please provide a price of at least :min.',
            'purchase_details.*.item.tax_id.required' => 'Please provide a valid tax id.',
            'purchase_details.*.item.quantity.required' => 'Please provide a valid item quantity.',
            'purchase_details.*.item.quantity.min' => 'Please provide a quantity of at least :min.',
        ];
    }

    public function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'data' => $validator->errors(),
        ]));
    }
}
