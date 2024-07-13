<?php

namespace App\Repositories;

use App\DTO\SaleInvoiceDetailDto;
use App\Interfaces\iItemRepository;
use App\Models\SaleDetail;

class SaleInvoiceRepository {

    private iItemRepository $itemRepository;

    public function __construct(iItemRepository $itemRepository) {
        $this->itemRepository = $itemRepository;
    }

    public function getSaleInvoiceDetails(SaleDetail $saleDetail) : SaleInvoiceDetailDto {

        $item = $this->itemRepository->getItem($saleDetail->item_id);

        $saleInvoiceDetail = new SaleInvoiceDetailDto();
        $saleInvoiceDetail->item = $item->description;
        $saleInvoiceDetail->quantity = $saleDetail->quantity;
        $saleInvoiceDetail->price = $item->price;
        $saleInvoiceDetail->subtotal = $saleDetail->subtotal;
        $saleInvoiceDetail->tax = $saleDetail->tax;

        return $saleInvoiceDetail;
    }

    public function getSaleSubtotal(array $saleInvoiceDetail) : float{
        $details = collect($saleInvoiceDetail);

        return $details->sum('subtotal');
    }
    public function getSaleTax(array $saleInvoiceDetail) : float{
        $details = collect($saleInvoiceDetail);

        return $details->sum('tax');
    }

    public function getSaleTotal(array $saleInvoiceDetail) : float{
        return $this->getSaleSubtotal($saleInvoiceDetail) +  $this->getSaleTax($saleInvoiceDetail);
    }
}
