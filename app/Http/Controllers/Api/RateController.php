<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use Illuminate\Http\Request;



class RateController extends Controller
{
    public function getAllRates()
    {
        $rates = Rate::all();
        return RateResource::collection($rates);
    }

    public function addRate(Request $request)
    {
        $request->validate([
            'rateCategory' => 'required|string|in:facility,exclusive,entrance',
            'facilityId' => 'nullable|integer|exists:facilities,id',
            'guestTypeId' => 'nullable|integer|exists:guest_types,id',
            'rateType' => 'required|string|in:Day-Based,Time-Based,Per-Head',
            'durationHours' => 'nullable|integer|min:0',
            'timePeriod' => 'required|string|in:AM,PM',
            'baseRate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'extensionFee' => 'nullable|numeric|min:0',
        ]);

        $rate = Rate::create([
            'rate_category' => $request->rateCategory,
            'facility_id' => $request->facilityId ?? null,
            'guest_type_id' => $request->guestTypeId ?? null,
            'rate_type' => $request->rateType,
            'duration_hours' => $request->durationHours ?? null,
            'time_period' => $request->timePeriod,
            'base_rate' => $request->baseRate,
            'description' => $request->description ?? '',
            'status' => $request->status,
            'extension_fee' => $request->extensionFee ?? 0,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Rate added successfully",
            "rate" => new RateResource($rate)
        ], 201);
    }

    public function editRate(Request $request, $id)
    {
        $request->validate([
            'rateCategory' => 'required|string|in:facility,exclusive,entrance',
            'facilityId' => 'nullable|integer|exists:facilities,id',
            'guestTypeId' => 'nullable|integer|exists:guest_types,id',
            'rateType' => 'required|string|in:Day-Based,Time-Based,Per-Head',
            'durationHours' => 'nullable|integer|min:0',
            'timePeriod' => 'required|string|in:AM,PM',
            'baseRate' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'extensionFee' => 'nullable|numeric|min:0',
        ]);

        $rate = Rate::findOrFail($id);

        $rate->rate_category = $request->rateCategory;
        $rate->facility_id = $request->facilityId ?? null;
        $rate->guest_type_id = $request->guestTypeId ?? null;
        $rate->rate_type = $request->rateType;
        $rate->duration_hours = $request->durationHours ?? null;
        $rate->time_period = $request->timePeriod;
        $rate->base_rate = $request->baseRate;
        $rate->description = $request->description ?? '';
        $rate->status = $request->status;
        $rate->extension_fee = $request->extensionFee ?? 0;

        $rate->save();

        return response()->json([
            "status" => "success",
            "message" => "Rate updated successfully",
            "rate" => new RateResource($rate)
        ]);
    }


    public function archiveRate($id)
    {
        $rate = Rate::findOrFail($id);
        $rate->delete();

        return response()->json([
            "status" => "success",
            "message" => "Rate archived successfully",
            "rate" => new RateResource($rate)
        ]);
    }

    public function restoreRate($id)
    {
        $rate = Rate::onlyTrashed()->findOrFail($id);
        $rate->restore();

        return response()->json([
            "status" => "success",
            "message" => "Rate restored successfully",
            "rate" => new RateResource($rate)
        ]);
    }

    public function viewArchivedRates()
    {
        $rates = Rate::onlyTrashed()->get();
        return RateResource::collection($rates);
    }
}
