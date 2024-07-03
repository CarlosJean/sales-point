<?php

namespace App\Repositories;

use App\DTO\ItemDto;
use App\DTO\PurchaseDetailDto;
use App\Interfaces\iItemRepository;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseRepository implements IPurchaseRepository {

    public function __construct(iItemRepository $itemRepository) {
        $this->itemRepository = $itemRepository;
    }

    /**
     * @throws \Exception
     */
    public function create(array $itemDtos) {

        //Create a new purchase
        $newPurchase = new Purchase();
        $newPurchase->save();

        foreach ($itemDtos as $itemDto) {

            $purchaseDetailDto = new PurchaseDetailDto();
            $purchaseDetailDto->item = $this->getPurchaseItem($itemDto);
            $purchaseDetailDto->quantity = $itemDto->quantity;
            $purchaseDetailDto->price = 0;//$itemDto->price;
            //$purchaseDetailDto->tax = $itemDto->tax;
            $purchaseDetailDto->subtotal = $purchaseDetailDto->quantity * $purchaseDetailDto->price;


            $purchaseDetail = new PurchaseDetail();

            $purchaseDetail->item()
                ->associate($purchaseDetailDto->item);

            $purchaseDetail->purchase()
                ->associate($newPurchase);

            $purchaseDetail->quantity = $purchaseDetailDto->quantity;

            $purchaseDetail->save();
        }
    }

    private function createItem($itemDto) {
        try {
            return $this->itemRepository->create($itemDto);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    private function getPurchaseItem(ItemDto $itemDto) : Item{

        $isNewItem = (!isset($itemDto->id) || $itemDto->id == null);

        try {
            if (!$isNewItem) {
                $item = Item::find($itemDto->id);
                if ($item == null) throw new ModelNotFoundException();
            } else {
                $item = $this->createItem($itemDto);
            }

            return $item;
        } catch (ModelNotFoundException) {
            throw new ModelNotFoundException("Item not found. Please provide a valid item id. ");
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
