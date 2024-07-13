<?php

namespace Tests\Feature;

use App\DTO\ItemDto;
use App\DTO\PurchaseDetailDto;
use App\Models\Item;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use App\Models\Tax;
use App\Repositories\ItemRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SupplierRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_can_purchase_an_existing_item() {

        //arrange
        Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        Item::factory()
            ->count(2)
            ->create();

        Supplier::factory()
                ->create();

        $supplierRepository = new SupplierRepository();
        $itemRepository = new ItemRepository();
        $purchaseRepository = new PurchaseRepository($itemRepository, $supplierRepository);

        $firstPurchaseDetail = new PurchaseDetailDto();
        $firstPurchaseDetail->item = new ItemDto();
        $firstPurchaseDetail->item->id = 1;
        $firstPurchaseDetail->item->quantity = 2;

        $secondPurchaseDetail = new PurchaseDetailDto();
        $secondPurchaseDetail->item = new ItemDto();
        $secondPurchaseDetail->item->id = 2;
        $secondPurchaseDetail->item->quantity = 6;

        $supplierId = 1;

        $items = array($firstPurchaseDetail, $secondPurchaseDetail);

        //Act
        $purchaseRepository->create($supplierId, $items);

        //Assert
        $this->assertDatabaseCount('purchase_details', 2);
    }
    public function test_that_can_purchase_non_existing_items() {

        //arrange
        $tax = Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        $supplier = Supplier::factory()
                ->create();

        $supplierRepository = new SupplierRepository();
        $itemRepository = new ItemRepository();
        $purchaseRepository = new PurchaseRepository($itemRepository, $supplierRepository);

        $firstPurchaseDetail = new PurchaseDetailDto();
        $firstPurchaseDetail->item = new ItemDto();
        $firstItem = Item::factory()->make();

        $firstPurchaseDetail->item->description = $firstItem->description;
        $firstPurchaseDetail->item->quantity = 2;
        $firstPurchaseDetail->item->price = $firstItem->price;
        $firstPurchaseDetail->item->taxId = $tax[1]->id;

        $secondPurchaseDetail = new PurchaseDetailDto();
        $secondPurchaseDetail->item = new ItemDto();
        $secondItem = Item::factory()->make();

        $secondPurchaseDetail->item->description = $secondItem->description;
        $secondPurchaseDetail->item->quantity = 2;
        $secondPurchaseDetail->item->price = $secondItem->price;
        $secondPurchaseDetail->item->taxId = $tax[1]->id;

        $supplierId = $supplier->id;

        $items = array($firstPurchaseDetail, $secondPurchaseDetail);

        //Act
        $purchaseInventory = $purchaseRepository->create($supplierId, $items);

        //Assert
        $this->assertDatabaseCount('purchase_details', 2);
    }

}
