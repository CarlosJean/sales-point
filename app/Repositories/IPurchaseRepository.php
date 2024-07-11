<?php

namespace App\Repositories;

interface IPurchaseRepository {
    public function create(int $supplierId, array $purchaseDetails);
}
