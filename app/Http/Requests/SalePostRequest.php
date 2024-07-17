<?php

namespace App\Http\Requests;

use App\Repositories\InventoryRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use App\Rules\Stock;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class SalePostRequest extends FormRequest {


    public function __construct(PurchaseRepository $purchaseRepository, SaleRepository $saleRepository, InventoryRepository $inventoryRepository) {
        $this->purchaseRepository = $purchaseRepository;
        $this->saleRepository = $saleRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

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
            'sale_details.*.quantity' => [function ($attribute, $value, $fail) {
                $this->validateStock($attribute, $value, $fail);
            }]
        ];
    }

    private function validateStock($attribute, $value, $fail): void {
        $index = explode('.', $attribute)[1];
        $itemId = $this->input("sale_details.$index.item.id");

        $stockRule = new Stock($itemId, $this->purchaseRepository, $this->saleRepository, $this->inventoryRepository);
        $stockRule->validate($attribute, $value, $fail);
    }

    protected function failedValidation(Validator $validator) {
        $response = response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors()
        ], 422);

        throw new ValidationException($validator, $response);
    }
}
