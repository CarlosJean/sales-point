<?php

namespace App\Repositories;

use App\Interfaces\iCustomerRepository;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CustomerRepository implements iCustomerRepository{

    public function getCustomerById(int $id) {
        try {
            return Customer::find($id);
        } catch (ModelNotFoundException $e) {
            throw new ModelNotFoundException('Customer not found. Please provide a valid Customer id.');
        }
    }
}
