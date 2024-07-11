<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public function details(){
        return $this->hasMany(PurchaseDetail::class);
    }

    public function supplier(){
        return $this->hasOne(Supplier::class);
    }

}
