<?php

namespace Tests\Feature;

use App\DTO\ItemDto;
use App\DTO\SaleDetailDto;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Tax;
use App\Repositories\CustomerRepository;
use App\Repositories\ItemRepository;
use App\Repositories\SaleInvoiceRepository;
use App\Repositories\SaleRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaleTest extends TestCase {

    //use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_create_sale(): void {

        $customer = Customer::factory()->create();

        Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        $items = Item::factory()
            ->count(2)
            ->create();

        $itemRepository = new ItemRepository();
        $saleInvoiceRepository = new SaleInvoiceRepository($itemRepository);
        $customerRepository = new CustomerRepository();

        $saleRepository = new SaleRepository($itemRepository, $saleInvoiceRepository, $customerRepository);

        $item1 = new ItemDto();
        $item1->id = $items[0]->id;

        $saleDetail1 = new SaleDetailDto();
        $saleDetail1->item = $item1;
        $saleDetail1->quantity = 3;

        $item2 = new ItemDto();
        $item2->id = $items[1]->id;

        $saleDetail2 = new SaleDetailDto();
        $saleDetail2->item = $item2;
        $saleDetail2->quantity = 5;

        $saleDetails = [$saleDetail1, $saleDetail2];

        $invoice = $saleRepository->create($customer->id, $saleDetails);

        //Assert
        $subtotal1 = $saleDetail1->quantity * $items[0]->price;
        $subtotal2 = $saleDetail2->quantity * $items[1]->price;

        $itemTax1 = $subtotal1 * $items[0]->tax->rate / 100;
        $itemTax2 = $subtotal2 * $items[1]->tax->rate / 100;

        $expectedSubtotal = $subtotal1 + $subtotal2;
        $expectedTax = $itemTax1 + $itemTax2;
        $expectedTotal = $expectedSubtotal + $expectedTax;

        $this->assertEquals($expectedSubtotal, $invoice->subtotal);
        $this->assertEquals($expectedTax, $invoice->tax);
        $this->assertEquals($expectedTotal, $invoice->total);
    }
}
