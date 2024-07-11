<?php

namespace App\Http\Controllers;

use App\DTO\ItemDto;
use App\DTO\PurchaseDetailDto;
use App\Models\Purchase;
use App\Http\Requests\StorePurchaseRequest;
use App\Repositories\PurchaseRepository;
use Exception;
use Illuminate\Http\JsonResponse;

class PurchaseController extends Controller {
    private PurchaseRepository $purchaseRepository;

    function __construct(PurchaseRepository $purchaseRepository) {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request): JsonResponse {
        try {

            $supplierId = $request->input('supplier_id');
            $purchaseDetailsRequest = $request->input('purchase_details');
            $purchaseDetails = [];

            foreach ($purchaseDetailsRequest as $purchaseDetailRequest) {
                $item = new ItemDto();
                if (isset($purchaseDetailRequest['item']['id'])) {
                    $item->id = $purchaseDetailRequest['item']['id'];
                }
                if (isset($purchaseDetailRequest['item']['description'])) {
                    $item->description = $purchaseDetailRequest['item']['description'];
                }
                $item->price = $purchaseDetailRequest['item']['price'];
                $item->taxId = $purchaseDetailRequest['item']['tax_id'];
                $item->quantity = $purchaseDetailRequest['item']['quantity'];

                $purchaseDetail = new PurchaseDetailDto();
                $purchaseDetail->item = $item;

                $purchaseDetails[] = $purchaseDetail;
            }


            $purchaseInvoice = $this->purchaseRepository->create($supplierId, $purchaseDetails);

            return response()->json([
                'status' => true,
                'message' => "Purchase registered successfully.",
                'data' => [
                    'date' => $purchaseInvoice->generationDate(),
                    'supplier_name' => $purchaseInvoice->supplierName(),
                    'details' => $purchaseInvoice->details(),
                    'subtotal' => $purchaseInvoice->subtotal(),
                    'total' => $purchaseInvoice->total(),
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Purchase $purchase) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    /*public function update(UpdatePurchaseRequest $request, Purchase $purchase) {
        //
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase) {
        //
    }
}
