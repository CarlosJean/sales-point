<?php

namespace App\Repositories;

use App\DTO\ItemDto;
use App\Interfaces\iItemRepository;
use App\Models\Item;
use App\Models\Tax;
use Exception;
use Illuminate\Support\Facades\DB;

class ItemRepository implements iItemRepository{

    public function getitems() : array{

        $items = Item::all();

        $itemDtos = array();

        foreach($items as $item){
            $itemDto = new ItemDto();

            $itemDto->id = $item->id;
            $itemDto->description = $item->description;
            $itemDto->price = $item->price;

            $itemDtos[] = $itemDto;
        }

        return $itemDtos;
    }

    public function getitem($id){
        try {
         return Item::where('id', $id)->first();
        }catch (\throwable $exception){
            throw $exception;
        }
    }

    /**
     * @throws \Throwable
     */
    public function create(ItemDto $item): Item {

        try {
            if ( !isset($item->taxId) || empty($item->taxId)) $item->taxId = 1;
            if ( !isset($item->price) || empty($item->price)) $item->price = 0;

            return Item::create([
                'description' => $item->description,
                'price' => $item->price,
                'tax_id' => $item->taxId,
            ]);
        }catch (\throwable $exception){
            throw new Exception($exception->getMessage());
        }
    }
}
