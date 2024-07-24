<?php

namespace App\Repositories;

use App\DTO\ItemDto;
use App\DTO\PurchaseInvoiceDto;
use App\Interfaces\iItemRepository;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PurchaseRepository implements IPurchaseRepository {

    private iItemRepository $itemRepository;
    private SupplierRepository $supplierRepository;

    public function __construct(iItemRepository $itemRepository, SupplierRepository $supplierRepository) {
        $this->itemRepository = $itemRepository;
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * @throws \Exception
     */
    public function create(int $supplierId, array $purchaseDetails): PurchaseInvoiceDto {

        try {
            //Create a new purchase
            $newPurchase = new Purchase();

            $supplier = $this->supplierRepository->getSupplierById($supplierId);

            $newPurchase->supplier_id = $supplier->id;
            $newPurchase->save();

            $purchaseInvoiceDetails = [];
            foreach ($purchaseDetails as $purchaseDetail) {

                $purchaseDetailModel = new PurchaseDetail();

                $item = $this->getPurchaseItem($purchaseDetail->item);

                $purchaseDetailModel->price = $item->price;
                $purchaseDetailModel->quantity = $purchaseDetail->item->quantity;
                $purchaseDetailModel->subtotal = $purchaseDetailModel->price * $purchaseDetailModel->quantity;

                //Purchase detail tax set in order to return it on the purchase invoice.
                $purchaseDetail->tax = $purchaseDetailModel->subtotal * $item->tax->rate / 100;

                $purchaseDetailModel->tax = $purchaseDetail->tax;

                $purchaseDetailModel->item()->associate($item);
                $purchaseDetailModel->purchase()->associate($newPurchase);

                $purchaseDetailModel->save();

                $purchaseInvoiceDetails[] = $purchaseDetail;
            }


            return new PurchaseInvoiceDto($supplier, $purchaseInvoiceDetails);

        } catch (ModelNotFoundException $exception) {
            throw new ModelNotFoundException($exception->getMessage());
        }

    }

    private function createItem($itemDto) : Item{
        try {
            return $this->itemRepository->create($itemDto);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    private function getPurchaseItem(ItemDto $itemDto): Item {

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

    public function getPurchases(): Builder {

        return DB::table('purchases')
            ->join('purchase_details', 'purchase_details.purchase_id', '=', 'purchases.id')
            ->rightJoin('items', 'purchase_details.item_id', '=', 'items.id')
            ->select(
                'items.id as item_id',
                'items.description as item',
                'items.price as item_price',
                DB::raw('IFNULL(SUM(purchase_details.quantity), 0) as quantity'),
                DB::raw("IFNULL(DATE_FORMAT(purchases.created_at, '%d/%m/%Y'), DATE_FORMAT(CURRENT_DATE, '%d/%m/%Y')) as date")
            )
            ->groupBy('items.id', 'item', 'items.price', 'date');
    }

}
