<?php

namespace App\Http\Requests;

use Dotenv\Exception\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ItemPostRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'price' =>  'required | min:1 | numeric',
            'description' =>  'required',
            'tax_id' =>  'required | numeric',
        ];
    }

    public function messages() : array {
        return [
            'price.required' =>  'El precio es requerido.',
            'price.min' =>  'El precio mínimo debe ser 1.',
            'description.required' =>  'La descripción es requerida.',
            'tax_id.required' =>  'El impuesto es requerido.',
            'tax_id.numeric' =>  'El impuesto debe ser un número.',
        ];
    }     

    // protected function failedValidation(Validator $validator) {
    //     throw new HttpResponseException(response()->json([
    //         'success'   => false,
    //         'message'   => 'Validation errors',
    //         'data'      => $validator->errors()
    //     ]));
    // }
}
