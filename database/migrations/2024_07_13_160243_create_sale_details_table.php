<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('item_id');
            $table->float('quantity');
            $table->float('subtotal');
            $table->float('tax');
            $table->float('total');

            $table->foreign('sale_id')->references('id')->on('sales');
            $table->foreign('item_id')->references('id')->on('items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
