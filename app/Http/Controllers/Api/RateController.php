<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Models\EntranceFee;
use App\Http\Resources\EntranceFeeResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function getAllRates()
    {
        $rates = Rate::where('is_active', 1)
            ->orderBy('rate_name')
            ->get();
        return RateResource::collection($rates);
    }

    public function getEntranceFees()
    {
        $fees = EntranceFee::where('is_active', 1)
            ->orderBy('guest_type')
            ->get();

        return EntranceFeeResource::collection($fees);
    }

    public function getExclusiveRates()
    {
        $rates = Rate::where('is_active', 1)
            ->where('rate_category', 'exclusive')
            ->orderBy('rate_name')
            ->get();
        return RateResource::collection($rates);
    }

    public function addRate(Request $request)
    {
        // Validate input
        $request->validate([
            'rateName' => 'required|string|max:100',
            'rateCategory' => 'required|string|in:entrance_fee,exclusive',
            'facilityId' => 'required|integer|exists:facilities,id',
            'rateType' => 'required|string|in:per_hour,per_day,flat_rate',
            'timePeriod' => 'nullable|string|max:50',
            'baseRate' => 'required|numeric|min:0',
            'durationHours' => 'nullable|integer|min:0',
            'durationType' => 'nullable|string|max:50',
            'applicableHours' => 'nullable|string|max:100',
            'maxBookingTime' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'extensionFee' => 'nullable|numeric|min:0',
        ]);

        // Create rate
        $rate = Rate::create([
            'rate_name' => $request->rateName,
            'rate_category' => $request->rateCategory,
            'facility_id' => $request->facilityId,
            'rate_type' => $request->rateType,
            'time_period' => $request->timePeriod ?? '',
            'base_rate' => $request->baseRate,
            'duration_hours' => $request->durationHours ?? 0,
            'duration_type' => $request->durationType ?? '',
            'applicable_hours' => $request->applicableHours ?? '',
            'max_booking_time' => $request->maxBookingTime ?? '',
            'description' => $request->description ?? '',
            'extension_fee' => $request->extensionFee ?? 0,
            'is_active' => 1,
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Rate added successfully",
            "rate" => new RateResource($rate)
        ], 201);
    }

    public function editRate(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'rateName' => 'required|string|max:100',
            'rateCategory' => 'required|string|in:entrance_fee,exclusive',
            'facilityId' => 'required|integer|exists:facilities,id',
            'rateType' => 'required|string|in:per_hour,per_day,flat_rate',
            'timePeriod' => 'nullable|string|max:50',
            'baseRate' => 'required|numeric|min:0',
            'durationHours' => 'nullable|integer|min:0',
            'durationType' => 'nullable|string|max:50',
            'applicableHours' => 'nullable|string|max:100',
            'maxBookingTime' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'extensionFee' => 'nullable|numeric|min:0',
        ]);

        $rate = Rate::findOrFail($id);
        $rate->rate_name = $request->rateName;
        $rate->rate_category = $request->rateCategory;
        $rate->facility_id = $request->facilityId;
        $rate->rate_type = $request->rateType;
        $rate->time_period = $request->timePeriod ?? '';
        $rate->base_rate = $request->baseRate;
        $rate->duration_hours = $request->durationHours ?? 0;
        $rate->duration_type = $request->durationType ?? '';
        $rate->applicable_hours = $request->applicableHours ?? '';
        $rate->max_booking_time = $request->maxBookingTime ?? '';
        $rate->description = $request->description ?? '';
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
        $rate->is_active = 0;
        $rate->save();

        return response()->json([
            "status" => "success",
            "message" => "Rate archived successfully",
            "rate" => new RateResource($rate)
        ]);
    }

    public function restoreRate($id)
    {
        $rate = Rate::findOrFail($id);
        $rate->is_active = 1;
        $rate->save();

        return response()->json([
            "status" => "success",
            "message" => "Rate restored successfully",
            "rate" => new RateResource($rate)
        ]);
    }

    public function viewArchivedRates()
    {
        $rates = Rate::where('is_active', 0)
            ->orderBy('rate_name')
            ->get();
        return RateResource::collection($rates);
    }
}
