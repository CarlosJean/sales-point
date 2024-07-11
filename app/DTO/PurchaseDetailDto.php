<?php

namespace App\DTO;

use App\Models\Item;
use App\Models\Tax;

class PurchaseDetailDto {
    public ItemDto $item;
    public float $tax;
}
