<?php

namespace App\Repositories;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class InventoryRepository {

    public function __construct(PurchaseRepository $purchaseRepository, SaleRepository $saleRepository) {
        $this->purchaseRepository = $purchaseRepository;
        $this->saleRepository = $saleRepository;
    }

    public function getInventory(string $from, string $to): Collection {
        $unionQuery = $this->purchaseAndSale();

        $from = date('d/m/Y', strtotime($from . '00:00:00'));
        $to = date('d/m/Y', strtotime($to . '23:59:59'));

        return DB::table(DB::raw("({$unionQuery->toSql()}) as subquery"))
            ->whereBetween('date', [$from, $to])
            ->where('SUM(quantity)', '>', 0)
            ->select(
                'item_id',
                'item',
                DB::raw('SUM(quantity) as quantity'),
                'date'
            )
            ->groupBy('item_id', 'item', 'date')
            ->get();
    }

    private function purchaseAndSale(): Builder {
        $purchasesQuery = $this->purchaseRepository->getPurchases();
        $salesQuery = $this->saleRepository->getSales();

        return $purchasesQuery->unionAll($salesQuery);
    }

    public function getItemStock(): Collection {
        $unionQuery = $this->purchaseAndSale();

        return DB::table(DB::raw("({$unionQuery->toSql()}) as subquery"))
            ->select(
                'item_id',
                'item',
                'item_price',
                DB::raw('SUM(quantity) as quantity'),
                DB::raw( "CONCAT('". url('/') ."', item_image) as item_image"),
            )
            ->groupBy('item_id', 'item', 'item_price', 'item_image')
            ->get();
    }

}
