<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function getAllDiscounts()
    {
        $discounts = Discount::all();
        return DiscountResource::collection($discounts);
    }

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return new DiscountResource($discount);
    }

    public function addDiscount(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:discounts,name',
            'type' => 'required|in:Seasonal,Standard',
            'discount_rate' => 'required|numeric|min:0|max:100',
            'rate_type' => 'required|in:percentage,fixed',
            'discount_scope' => 'required|in:system,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $discount = Discount::create([
            'name' => $request->name,
            'type' => $request->type,
            'discount_rate' => $request->discount_rate,
            'rate_type' => $request->rate_type,
            'discount_scope' => $request->discount_scope,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Discount added successfully',
            'discount' => new DiscountResource($discount)
        ]);
    }

    public function editDiscount(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:discounts,name' . (isset($id) ? ',' . $id : ''),
            'type' => 'required|in:Seasonal,Standard',
            'discount_rate' => 'required|numeric|min:0|max:100',
            'rate_type' => 'required|in:percentage,fixed',
            'discount_scope' => 'required|in:system,custom',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
        ]);

        $discount = Discount::findOrFail($id);
        $discount->update([
            'name' => $request->name,
            'type' => $request->type,
            'discount_rate' => $request->discount_rate,
            'rate_type' => $request->rate_type,
            'discount_scope' => $request->discount_scope,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Discount updated successfully',
            'discount' => new DiscountResource($discount)
        ]);
    }

    public function archiveDiscount($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();

       return response()->json([
            'status' => 'success',
            'message' => 'Discount archived successfully',
            'discount' => new DiscountResource($discount)
        ]);
    }

    public function restoreDiscount($id)
    {
        $discount = Discount::onlyTrashed()->findOrFail($id);
        $discount->restore();

        return response()->json([
            'status' => 'success',
            'message' => 'Discount restored successfully',
            'discount' => new DiscountResource($discount)
        ]);
    }

    public function viewArchivedDiscounts()
    {
        $discounts = Discount::onlyTrashed()->get();
        return DiscountResource::collection($discounts);
    }
}
