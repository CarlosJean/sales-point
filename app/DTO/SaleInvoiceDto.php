<?php

namespace App\DTO;

class SaleInvoiceDto {
    public array $details;
    public float $subtotal;
    public float $tax;
    public float $total;
}
