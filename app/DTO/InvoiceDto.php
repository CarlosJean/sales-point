<?php

namespace App\DTO;

final class InvoiceDto
{
    private $items;
    public function __construct($items){
        $this->items = $items;
    }   

    public function items(){
        return $this->items;
    }
}
