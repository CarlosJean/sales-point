<?php

namespace App\DTO;

use App\Models\Item;
use App\Models\Tax;

class PurchaseDetailDto {
    public Item $item;
    public float $quantity;
    public Tax $tax;
    public float $subtotal;
    public float $price;
}
