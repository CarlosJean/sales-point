<?php

namespace App\Repositories;

use App\DTO\SaleDetailDto;
use App\DTO\SaleInvoiceDetailDto;
use App\DTO\SaleInvoiceDto;
use App\Interfaces\iItemRepository;
use App\Models\Item;
use App\Models\Sale;
use App\Models\SaleDetail;

class SaleRepository {

    private iItemRepository $itemRepository;
    private $SaleInvoiceRepository;

    public function __construct(iItemRepository $itemRepository, SaleInvoiceRepository $SaleInvoiceRepository) {
        $this->itemRepository = $itemRepository;
        $this->SaleInvoiceRepository = $SaleInvoiceRepository;
    }

    public function create(array $saleDetails) : SaleInvoiceDto{

        $sale = new Sale();
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

            $saleInvoice->details[] = $this->SaleInvoiceRepository->getSaleInvoiceDetails($saleDetail);
        }

        $saleInvoice->subtotal = $this->SaleInvoiceRepository->getSaleSubtotal($saleInvoice->details);
        $saleInvoice->tax = $this->SaleInvoiceRepository->getSaleTax($saleInvoice->details);
        $saleInvoice->total = $this->SaleInvoiceRepository->getSaleTotal($saleInvoice->details);

        return $saleInvoice;
    }

}
