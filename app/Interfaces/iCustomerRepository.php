<?php

namespace App\Interfaces;

interface iCustomerRepository {
    public function getCustomerById(int $id);
}
