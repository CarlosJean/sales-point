<?php

namespace App\Http\Controllers;

use App\DTO\ItemDto;
use App\Http\Requests\ItemPostRequest;
use App\Repositories\ItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{


    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository) {
        $this->itemRepository = $itemRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = $this->itemRepository->getitems();
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @throws ValidationException
     */
    public function store(ItemPostRequest $request) : JsonResponse {

        $item = new ItemDto();
        $item->description = $request->input('description');
        $item->price = $request->input('price');
        $item->taxId = $request->input('tax_id');

        $response = $this->itemRepository->create($item);

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
