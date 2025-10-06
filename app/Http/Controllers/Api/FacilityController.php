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
        $facilities = Facility::with('facilityType')->get();
        return FacilityResource::collection($facilities);
    }

    public function getAllFacilityTypes()
    {
        $facilities = FacilityType::all();
        return FacilityTypeResource::collection($facilities);
    }

    public function addFacility(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name',
            'facility_type_id' => 'required|integer|exists:facility_types,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:1',
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
        ]);
        
        return response()->json([
            "success" => true,
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
        ]);
        
        return response()->json([
            "success" => true,
            "message" => "Facility Type added successfully",
            "facilityType" => new FacilityTypeResource($facilityType)
        ], 201);
    }
    
    public function editFacility(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:facilities,name,',
            'facility_type_id' => 'required|integer|exists:facility_types,id',
            'quantity' => 'required|integer|min:1',
            'description' => 'nullable|string',
            'expectedCapacity' => 'nullable|integer|min:0',
            'maxCapacity' => 'nullable|integer|min:0',
        ]);

         $facility = Facility::findOrFail($id);

        $facility->update([
            'name' => $request->name,
            'facility_type_id' => $request->facility_type_id,
            'description' => $request->description ?? '',
            'quantity' => $request->quantity,
            'expected_capacity' => $request->expectedCapacity ?? 0,
            'max_capacity' => $request->maxCapacity ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility updated successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }

    public function editFacilityType(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:facility_types,name,'.$id,
        ]);

         $facilityType = FacilityType::findOrFail($id);

        $facilityType->update([
            'name' => trim($request->name),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility Type updated successfully',
            'facilityType' => new FacilityTypeResource($facilityType)
        ]);
    }


    public function archiveFacility(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return response()->json([
            'success' => true,
            'message' => 'Facility archived successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }

    public function archiveFacilityType(Request $request, $id)
    {
        $facilityType = FacilityType::findOrFail($id);
        $facilityType->delete();

        return response()->json([
            'success' => true,
            'message' => 'Facility Type archived successfully',
            'facilityType' => new FacilityTypeResource($facilityType)
        ]);
    }

    public function viewArchivedFacilities()
    {
        $facilities = Facility::onlyTrashed()->with('facilityType')->orderBy('name')->get();
        return FacilityResource::collection($facilities);
    }

    public function viewArchivedFacilityTypes()
    {
        $facilityTypes = FacilityType::onlyTrashed()->orderBy('name')->get();
        return FacilityTypeResource::collection($facilityTypes);
    }

    public function restoreFacility(Request $request, $id)
    {
        $facility = Facility::onlyTrashed()->findOrFail($id);
        $facility->restore();

        return response()->json([
            'success' => true,
            'message' => 'Facility restored successfully',
            'facility' => new FacilityResource($facility)
        ]);
    }

    public function restoreFacilityType(Request $request, $id)
    {
        $facilityType = FacilityType::onlyTrashed()->findOrFail($id);
        $facilityType->restore();

        return response()->json([
            'success' => true,
            'message' => 'Facility Type restored successfully',
            'facilityType' => new FacilityTypeResource($facilityType)
        ]);
    }
}
