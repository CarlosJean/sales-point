<?php

namespace App\Repositories;

use App\DTO\ItemDto;
use App\Interfaces\iItemRepository;
use App\Models\Item;

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
        return Item::where('id', $id)->first();
    }

    /**
     * @throws \Throwable
     */
    public function create(ItemDto $item): array {

        try {
            Item::create([
                'description' => $item->description,
                'price' => $item->price,
                'tax_id' => $item->taxId,
            ]);

            return [
                'code' => 200,
                'message' => 'Artículo creado satisfactoriamente.'
            ];
        }catch (\throwable $exception){
            throw new $exception;
        }

    }
}
