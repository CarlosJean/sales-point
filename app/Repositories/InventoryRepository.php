<?php

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class InventoryRepository {

    public function __construct(PurchaseRepository $purchaseRepository, SaleRepository $saleRepository) {
        $this->purchaseRepository = $purchaseRepository;
        $this->saleRepository = $saleRepository;
    }

    public function getInventory(): Collection {
        $purchasesQuery = $this->purchaseRepository->getPurchases();
        $salesQuery = $this->saleRepository->getSales();

        $unionQuery = $purchasesQuery->unionAll($salesQuery);

        return DB::table(DB::raw("({$unionQuery->toSql()}) as subquery"))
            ->select('item_id', 'item', DB::raw('SUM(quantity) as quantity'), 'date')
            ->groupBy('item_id', 'item', 'date')
            ->get();
    }

    public function getItemStock(int $itemId) {
        return $this->getInventory()->where('item_id','=', $itemId)->first();
    }
}
