<?php

namespace Tests\Feature;

use App\DTO\ItemDto;
use App\Models\Tax;
use App\Repositories\ItemRepository;
use App\Repositories\PurchaseRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Tests\TestCase;

class PurchaseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_that_purchase_can_be_created() {
        $itemRepository = new ItemRepository();
        $purchaseRepository = new PurchaseRepository($itemRepository);

        $firsItem = new ItemDto();
        $firsItem->description = "Peras";
        $firsItem->quantity = 3;

        $secondItem = new ItemDto();
        $secondItem->id = 2;
        $secondItem->quantity = 3;

        $items = array($firsItem, $secondItem);

        //Creating taxes

        Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        $purchase = $purchaseRepository->create($items);
    }

}
