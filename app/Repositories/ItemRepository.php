<?php

namespace App\Repositories;

use App\Interfaces\iItemRepository;
use App\Models\Item;

class ItemRepository implements iItemRepository{


    public function getitem($id){
        return Item::where('id', $id)->first();
    }

}