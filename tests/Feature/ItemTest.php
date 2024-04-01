<?php

namespace Tests\Feature;

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
}
