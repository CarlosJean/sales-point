<?php

namespace App\Repositories;

class InvoicesRepository{

    public function create($items){

    }

    public function total($invoice){
        
        $total = 0;
        foreach ($invoice->items() as $key => $item) {
            $total += $item->quantity();
        }
        
        return $total;
    }
}