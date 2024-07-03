<?php

namespace App\Repositories;

interface IPurchaseRepository {
    public function create(array $itemDto);
}
