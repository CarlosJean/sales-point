<?php

namespace App\DTO;

final class ItemDto
{
    private $id;
    private $name;
    private $quantity;

    public function __construct($id, $name, $quantity)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function quantity()
    {
        return $this->quantity;
    }
}
