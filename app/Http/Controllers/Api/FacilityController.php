<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FacilityResource;
use App\Http\Resources\FacilityTypeResource;
use App\Models\Facility;
use App\Models\FacilityType;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function getAllFacilities()
    {
        $facilities = Facility::where('is_active', 1)
            ->with('facilityType')
            ->orderBy('name')
            ->get();
        return FacilityResource::collection($facilities);
    }

    public function getAllFacilityTypes()
    {
        $facilities = FacilityType::where('is_active', 1)->get(['id', 'name']);
        return FacilityTypeResource::collection($facilities);
    }

    public function addFacility(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:100',
            'facility_type_id' => 'required|integer|exists:facility_types,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'expectedCapacity' => 'nullable|integer|min:0',
            'maxCapacity' => 'nullable|integer|min:0',
        ]);

        // Create facility
        $facility = Facility::create([
            'name' => $request->name,
            'facility_type_id' => $request->facility_type_id,
            'description' => $request->description ?? '',
            'quantity' => $request->quantity,
            'expected_capacity' => $request->expectedCapacity ?? 0,
            'max_capacity' => $request->maxCapacity ?? 0,
            'is_active' => 1,
        ]);
        
        return response()->json([
            "status" => "success",
            "message" => "Facility added successfully",
            "facility" => new FacilityResource($facility)
        ], 201);
    }

    public function addFacilityType(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:50|unique:facility_types,name',
        ]);

        // Create facility type
        $facilityType = FacilityType::create([
            'name' => trim($request->name),
            'is_active' => 1,
        ]);
        
        return response()->json([
            "status" => true,
            "message" => "Facility type added successfully",
            "facilityType" => new FacilityTypeResource($facilityType)
        ], 201);
    }
    
    public function editFacility(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'facility_type_id' => 'required|integer|exists:facility_types,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'expectedCapacity' => 'nullable|integer|min:0',
            'maxCapacity' => 'nullable|integer|min:0',
        ]);

        $facility = Facility::where('id', $id)->where('is_active', 1)->firstOrFail();

        $facility->name = $request->name;
        $facility->facility_type_id = $request->facility_type_id;
        $facility->description = $request->description ?? '';
        $facility->quantity = $request->quantity;
        $facility->expected_capacity = $request->expectedCapacity ?? 0;
        $facility->max_capacity = $request->maxCapacity ?? 0;
        $facility->save();

        return response()->json([
            'success' => true,
            'message' => 'Facility updated successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }

    public function archiveFacility(Request $request, $id)
    {
        // Find the facility and check if it's active
        $facility = Facility::where('id', $id)->where('is_active', 1)->firstOrFail();

        // Archive the facility
        $facility->is_active = 0;
        $facility->save();

        return response()->json([
            'success' => true,
            'message' => 'Facility archived successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }

    public function viewArchivedFacilities()
    {
        $facilities = Facility::where('is_active', 0)
            ->with('facilityType')
            ->orderBy('name')
            ->get();

        return FacilityResource::collection($facilities);
    }

    public function restoreFacility(Request $request, $id)
    {
        // Find the archived facility
        $facility = Facility::where('id', $id)->where('is_active', 0)->firstOrFail();

        // Restore the facility
        $facility->is_active = 1;
        $facility->save();

        return response()->json([
            'success' => true,
            'message' => 'Facility restored successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }
}
