<?php

namespace App\DTO;

use App\Models\Supplier;

class PurchaseInvoiceDto {

    private Supplier $supplier;
    private array $details;


    public function __construct(Supplier $supplier, array $details) {
        $this->supplier = $supplier;
        $this->details = $details;
    }

    public function generationDate(): string {
        return date("Y-m-d");
    }

    public function supplierName() : string{
        return (isset($this->supplier->name)) ? $this->supplier->name : "";
    }

    public function subtotal(): float {
        $subtotal = 0;
        foreach ($this->details as $detail) {
            if (isset($detail->subtotal) > 0) {
                $subtotal += $detail->subtotal;
            }
        }
        return $subtotal;
    }

    public function total(): float {
        $subtotal = 0;
        foreach ($this->details as $detail) {
            if (isset($detail->tax)) {
                $subtotal += $detail->tax;
            }
        }

        return $this->subtotal() + $subtotal;
    }

    public function details(){
        return $this->details;
    }
}
