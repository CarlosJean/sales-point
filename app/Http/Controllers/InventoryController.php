<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\InventoryCollection;
use App\Repositories\InventoryRepository;
use App\Repositories\PurchaseRepository;
use App\Repositories\SaleRepository;
use Illuminate\Http\Request;

class InventoryController extends Controller
{

    public function __construct(PurchaseRepository $purchaseRepository, SaleRepository $saleRepository, InventoryRepository $inventoryRepository) {
        $this->purchaseRepository = $purchaseRepository;
        $this->saleRepository = $saleRepository;
        $this->inventoryRepository = $inventoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $inventoryRequest)
    {
        $from = $inventoryRequest->input('from');
        $to = $inventoryRequest->input('to');

        $inventory = $this->inventoryRepository->getInventory($from, $to);

        return new InventoryCollection($inventory);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
