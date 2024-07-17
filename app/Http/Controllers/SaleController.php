<?php

namespace App\Http\Controllers;

use App\DTO\ItemDto;
use App\DTO\SaleDetailDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\SalePostRequest;
use App\Repositories\CustomerRepository;
use App\Repositories\ItemRepository;
use App\Repositories\SaleInvoiceRepository;
use App\Repositories\SaleRepository;
use Illuminate\Http\Request;

class SaleController extends Controller {

    public function __construct(SaleRepository $saleRepository) {
        $this->saleRepository = $saleRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalePostRequest $request) {

        $customerId = $request->input('customer_id');
        $saleDetailsRequest = $request->input('sale_details');

        $saleDetails = [];
        foreach ($saleDetailsRequest as $detail) {
            $item = new ItemDto();
            $item->id = $detail['item']['id'];

            $saleDetail = new SaleDetailDto();
            $saleDetail->item = $item;
            $saleDetail->quantity = $detail['quantity'];

            $saleDetails[] = $saleDetail;
        }

        $itemRepository = new ItemRepository();
        $saleInvoiceRepository = new SaleInvoiceRepository($itemRepository);
        $customerRepository = new CustomerRepository();

        $saleRepository = new SaleRepository($itemRepository, $saleInvoiceRepository, $customerRepository);

        $saleInvoice = $saleRepository->create($customerId, $saleDetails);

        return response()->json($saleInvoice);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        //
    }
}
