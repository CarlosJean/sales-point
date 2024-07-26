<?php

namespace App\Repositories;

use App\DTO\SaleDetailDto;
use App\DTO\SaleInvoiceDetailDto;
use App\DTO\SaleInvoiceDto;
use App\Interfaces\iCustomerRepository;
use App\Interfaces\iItemRepository;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleDetail;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class SaleRepository {

    private iItemRepository $itemRepository;
    private $saleInvoiceRepository;
    private iCustomerRepository $customerRepository;

    public function __construct(iItemRepository $itemRepository, SaleInvoiceRepository $SaleInvoiceRepository, iCustomerRepository $customerRepository) {
        $this->itemRepository = $itemRepository;
        $this->saleInvoiceRepository = $SaleInvoiceRepository;
        $this->customerRepository = $customerRepository;
    }

    public function create(int $customerId, array $saleDetails): SaleInvoiceDto {

        $sale = new Sale();
        $customer = $this->customerRepository->getCustomerById($customerId);
        $sale->customer_id = $customer->id;
        $sale->save();

        $saleInvoice = new SaleInvoiceDto();
        $saleInvoice->details = [];

        foreach ($saleDetails as $detail) {
            $saleDetail = new SaleDetail();

            $item = $this->itemRepository->getItem($detail->item->id);

            $saleDetail->quantity = $detail->quantity;
            $saleDetail->subtotal = $item->price * $detail->quantity;
            $saleDetail->tax = $saleDetail->subtotal * $item->tax->rate / 100;
            $saleDetail->total = $saleDetail->subtotal + $saleDetail->tax;
            $saleDetail->item_id = $item->id;

            $saleDetail->sale()->associate($sale);

            $saleDetail->save();

            $saleInvoice->details[] = $this->saleInvoiceRepository->getSaleInvoiceDetails($saleDetail);
        }

        $saleInvoice->subtotal = $this->saleInvoiceRepository->getSaleSubtotal($saleInvoice->details);
        $saleInvoice->tax = $this->saleInvoiceRepository->getSaleTax($saleInvoice->details);
        $saleInvoice->total = $this->saleInvoiceRepository->getSaleTotal($saleInvoice->details);

        return $saleInvoice;
    }

    public function getSales(): Builder {
        return DB::table('sales')
            ->join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->rightJoin('items', 'sale_details.item_id', '=', 'items.id')
            ->select(
                'items.id as item_id',
                'items.description as item',
                'items.price as item_price',
                'items.image as item_image',
                DB::raw('IFNULL(SUM(sale_details.quantity), 0) as quantity'),
                DB::raw("IFNULL(DATE_FORMAT(sales.created_at, '%d/%m/%Y'), DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y')) as date")
            )
            ->groupBy('items.id', 'item', 'items.price', 'date', 'items.image');
    }

}
