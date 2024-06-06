<?php

namespace App\Interfaces;

use App\DTO\ItemDto;

interface iItemRepository{

    function getItem($id);

    function create(ItemDto $item);
}
