<?php

namespace App\Rules;

use App\Interfaces\iItemRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Stock implements ValidationRule
{

    public function __construct(int $itemId, PurchaseRepository $purchaseRepository, SaleRepository $saleRepository, InventoryRepository $inventoryRepository) {
        $this->itemId = $itemId;
        $this->purchaseRepository = $purchaseRepository;
        $this->saleRepository = $saleRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //$inventoryRepository = new InventoryRepository($this->purchaseRepository, $this->saleRepository);
        $itemStock = $this->inventoryRepository
                ->getItemStock($this->itemId)
                ->quantity;

        if ($value > $itemStock) {
            $fail('The requested quantity is greater than the available on stock.');
        }
    }
}
