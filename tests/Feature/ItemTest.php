<?php

namespace Tests\Feature;

use App\DTO\ItemDto;
use App\Models\Item;
use App\Models\Tax;
use App\Repositories\ItemRepository;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class ItemTest extends TestCase
{
    use RefreshDatabase;

    public function test_that_can_not_get_an_item_by_id()
    {
        $itemId = 1;

        $itemRepository = new ItemRepository();

        $item = $itemRepository->getitem($itemId);

        $this->assertNull($item);
    }

    public function test_that_can_get_an_item_by_id()
    {
        $itemId = 1;

        //Creating taxes
        Tax::factory()
            ->count(2)
            ->state(new Sequence(
                ['rate' => 0],
                ['rate' => 18],
            ))->create();

        //Creating items
        Item::factory()
            ->state(new Sequence(
                fn () => ['tax_id' => rand(1, 2)]
            ))->create();

        $itemRepository = new ItemRepository();

        $item = $itemRepository->getitem($itemId);

        $this->assertNotNull($item);
    }

    public function test_that_can_create_item(){
        $itemRepository = new ItemRepository();

        $item = new ItemDto();
        $item->description = 'Apple';
        $item->price = 10.5;
        $item->taxId = 1;

        $response = $itemRepository->create($item);


        $this->assertequals(200, $response['code']);
    }

    public function test_that_can_get_items(){
        $itemRepository = new ItemRepository();
        $items = $itemRepository->getitems();

        $this->assertNotNull($items);
    }
}
