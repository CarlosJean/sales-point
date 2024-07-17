<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Supplier;
use App\Models\Tax;
use App\Repositories\CustomerRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\ItemRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleInvoiceRepository;
use App\Repositories\SaleRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryTest extends TestCase {
    use RefreshDatabase;

    public function test_inventory(): void {
        //Arrange
        $supplier = Supplier::factory()->create();
        $purchase = Purchase::factory()->create();
        $purchase->supplier_id = $supplier->id;

        Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        $items = Item::factory()->count(3)->create();
        $purchaseDetails = PurchaseDetail::factory()->count(2)->create();

        $customer = Customer::factory()->create();
        $sale = Sale::factory()->create();

        $sale->customer_id = $customer->id;

        $saleDetails = SaleDetail::factory()->count(2)->create();

        $itemRepository = new ItemRepository();
        $supplierRepository = new SupplierRepository();

        $purchaseRepository = new PurchaseRepository($itemRepository, $supplierRepository);

        $customerRepository = new CustomerRepository();
        $saleInvoiceRepository = new SaleInvoiceRepository($itemRepository);

        $saleRepository = new SaleRepository($itemRepository, $saleInvoiceRepository, $customerRepository);

        $inventoryRepository = new InventoryRepository($purchaseRepository, $saleRepository);

        $inventory = $inventoryRepository->getInventory();

        //Assert

        $saleItem = $saleDetails->map(function($saleDetail){
            return $saleDetail->item_id;
        });

        $purchaseItem = $purchaseDetails->map(function($purchaseDetail){
            return $purchaseDetail->item_id;
        });

        $itemToTest = $purchaseItem
                ->intersect($saleItem)
                ->first();

        $purchaseQuantity = $purchaseDetails->where('item_id', $itemToTest)->first()->quantity;
        $saleQuantity = $saleDetails->where('item_id', $itemToTest)->first()->quantity * -1;

        $expectedQuantity = $purchaseQuantity + $saleQuantity;
        $itemName = $items->where('id', $itemToTest)->first()->description;

        $this->assertEquals($expectedQuantity, $inventory->where('item',$itemName)->first()->quantity);
    }
}
