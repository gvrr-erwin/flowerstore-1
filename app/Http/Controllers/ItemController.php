<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'max_price' => 'numeric|min:0',
            'item_types.*' => 'in:meat,vegetable,fruit,drink,canned_good,others',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Apply filters
        $query = Item::query();

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('item_types')) {
            if ($request->item_types !== null && count($request->item_types) > 0) {
                $query->whereIn('itemType', $request->item_types);
            }
        }

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Paginate the results
        $items = $query->with('shop')->paginate(5);

        return response()->json($items);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'itemType' => 'required|in:meat,vegetable,fruit,drink,canned_good,others',
            'quantity' => 'required|integer|min:0',
            'shop_id' => 'required|exists:shops,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        // Create new item
        $item = Item::create($request->all());

        return response()->json($item, 201);

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
        $item = Item::find($id);
        return response()->json($item);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $item = Item::find($id);
        $item->name = $request->get('name');
        $item->price = $request->get('price');
        $item->itemType = $request->get('itemType');
        $item->quantity = $request->get('quantity');
        $item->shop_id = $request->get('shop_id'); // Update shop_id instead of shop
        $item->save();

        return response()->json('Successfully Updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::find($id);
        $item->delete();

        return response()->json('Successfully Deleted');
    }
}
