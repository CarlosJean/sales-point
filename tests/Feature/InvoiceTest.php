<?php

namespace Tests\Feature;

use App\DTO\InvoiceDto;
use App\DTO\ItemDto;
use App\Models\Item;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public function test_that_detail_is_complete()
    {
        $invoice = new InvoiceDto(
            [
                new ItemDto(1, "apple", 3),
                new ItemDto(2, "pear", 3),
            ]
        );

        //Creating items
        Item::factory()->create();
        Item::factory()->create();        
    }
}
