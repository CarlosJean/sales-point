<?php

namespace App\Repositories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SupplierRepository {

    public function getSupplierById(int $id) : Supplier {
        $supplier = Supplier::find($id);
        if ($supplier == null){
            throw new ModelNotFoundException("Supplier not found. Please provide a valid supplier id.");
        }

        return $supplier;
    }
}
