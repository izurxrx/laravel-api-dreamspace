<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\GuestMonitoringResource;
use App\Models\GuestMonitoring;
use Illuminate\Support\Facades\DB;

class GuestMonitoringController extends Controller
{
    // Get all guest monitoring records
    public function getAllGuestMonitoring()
    {
        $guests = GuestMonitoring::with([
            'details.rate',
            'discounts.discount'
        ])->get();

        return GuestMonitoringResource::collection($guests);
    }

    // Show a single guest monitoring record
    public function show($id)
    {
        $guest = GuestMonitoring::with([
            'details.rate',
            'discounts.discount'
        ])->findOrFail($id);

        return new GuestMonitoringResource($guest);
    }

    // Store a new guest monitoring record
    public function addGuestMonitoring(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'entry_time' => 'required|date',
            'exit_time' => 'nullable|date',
            'details' => 'required|array|min:1',
            'details.*.rate_id' => 'required|exists:rates,id',
            'details.*.guest_count' => 'required|integer|min:1',
            'details.*.applied_rate' => 'required|numeric|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.discount_id' => 'nullable|exists:discounts,id',
            'discounts.*.custom_name' => 'nullable|string|max:100',
            'discounts.*.type' => 'sometimes|required|string|in:percentage,fixed',
            'discounts.*.discount_rate' => 'sometimes|required|numeric|min:0',
            'discounts.*.applied_value' => 'sometimes|required|numeric|min:0',
        ]);

        // 🚫 Prevent overlapping check-in
        $existing = GuestMonitoring::where('guest_name', $validated['guest_name'])
            ->whereNull('exit_time')   // still checked in
            ->where('status', 'unpaid') // not settled
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Guest already checked in and has not checked out yet.'
            ], 422);
        }

        return DB::transaction(function () use ($validated) {
            $guest = GuestMonitoring::create([
                'guest_name' => $validated['guest_name'],
                'contact_number' => $validated['contact_number'] ?? null,
                'entry_time' => $validated['entry_time'],
                'exit_time' => $validated['exit_time'] ?? null,
                'total_fee' => 0,
                'status' => 'unpaid',
            ]);

            $total = 0;

            // Add details (line items)
            foreach ($validated['details'] as $detail) {
                $item = $guest->details()->create($detail);
                $total += $item->guest_count * $item->applied_rate;
            }

            // Apply discounts
            if (!empty($validated['discounts'])) {
                foreach ($validated['discounts'] as $disc) {
                    $guest->discounts()->create($disc);
                    $total -= $disc['applied_value'];
                }
            }

            $guest->update(['total_fee' => $total]);

            return new GuestMonitoringResource(
                $guest->load('details.rate', 'discounts.discount')
            );
        });
    }


    public function editGuestMonitoring(Request $request, $id)
    {
        $guest = GuestMonitoring::findOrFail($id);

        // 🚫 Block if already paid
        if ($guest->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot modify a paid record.'
            ], 403);
        }

        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:50',
            'entry_time' => 'required|date',
            'exit_time' => 'nullable|date',
            'details' => 'required|array|min:1',
            'details.*.id' => 'nullable|exists:guest_monitoring_details,id',
            'details.*.rate_id' => 'required|exists:rates,id',
            'details.*.guest_count' => 'required|integer|min:1',
            'details.*.applied_rate' => 'required|numeric|min:0',
            'discounts' => 'nullable|array',
            'discounts.*.discount_id' => 'nullable|exists:discounts,id',
            'discounts.*.custom_name' => 'nullable|string|max:100',
            'discounts.*.type' => 'sometimes|required|string|in:percentage,fixed',
            'discounts.*.discount_rate' => 'sometimes|required|numeric|min:0',
            'discounts.*.applied_value' => 'sometimes|required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated, $guest) {
            $guest->update([
                'guest_name' => $validated['guest_name'],
                'contact_number' => $validated['contact_number'] ?? null,
                'entry_time' => $validated['entry_time'],
                'exit_time' => $validated['exit_time'] ?? null,
            ]);

            $total = 0;

            // --- Details sync ---
            $existingDetailIds = $guest->details()->pluck('id')->toArray();
            $submittedDetailIds = array_filter(array_column($validated['details'], 'id'));

            // Delete removed details
            $toDeleteDetails = array_diff($existingDetailIds, $submittedDetailIds);
            if (!empty($toDeleteDetails)) {
                $guest->details()->whereIn('id', $toDeleteDetails)->delete();
            }

            // Add or update details + recalc total
            foreach ($validated['details'] as $detail) {
                if (isset($detail['id'])) {
                    $item = $guest->details()->findOrFail($detail['id']);
                    $item->update($detail);
                } else {
                    $item = $guest->details()->create($detail);
                }
                $total += $detail['guest_count'] * $detail['applied_rate'];
            }

            // --- Discounts sync ---
            $existingDiscountIds = $guest->discounts()->pluck('id')->toArray();
            $submittedDiscountIds = array_filter(array_column($validated['discounts'] ?? [], 'id'));

            // Delete removed discounts
            $toDeleteDiscounts = array_diff($existingDiscountIds, $submittedDiscountIds);
            if (!empty($toDeleteDiscounts)) {
                $guest->discounts()->whereIn('id', $toDeleteDiscounts)->delete();
            }

            // Add or update discounts + apply total deduction
            if (!empty($validated['discounts'])) {
                foreach ($validated['discounts'] as $disc) {
                    if (isset($disc['id'])) {
                        $d = $guest->discounts()->findOrFail($disc['id']);
                        $d->update($disc);
                    } else {
                        $guest->discounts()->create($disc);
                    }
                    $total -= $disc['applied_value'];
                }
            }

            $guest->update(['total_fee' => $total]);

            return new GuestMonitoringResource(
                $guest->load('details.rate', 'discounts.discount')
            );
        });
    }

    public function deleteGuestMonitoring($id)
    {
        $guest = GuestMonitoring::findOrFail($id);
        
        if ($guest->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete a paid record.'
            ], 403);
        }

        $guest->delete();

        return response()->json([
            'success' => true,
            'message' => 'Guest monitoring record deleted successfully'
        ]);
    }

    public function restoreGuestMonitoring($id)
    {
        $guest = GuestMonitoring::withTrashed()->findOrFail($id);

        if ($guest->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot restore a paid record.'
            ], 403);
        }
        
        $guest->restore();

        return response()->json([
            'success' => true,
            'message' => 'Guest monitoring record restored successfully',
            'guest' => new GuestMonitoringResource($guest)
        ]);
    }

    public function viewArchivedGuestMonitoring()
    {
        $guests = GuestMonitoring::onlyTrashed()->with([
            'details.rate',
            'discounts.discount'
        ])->get();

        return GuestMonitoringResource::collection($guests);
    }

    public function statusChangeGuestMonitoring(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:unpaid,paid'
        ]);

        $guest = GuestMonitoring::findOrFail($id);
        $guest->status = $request->status;
        $guest->save();

        return response()->json([
            'success' => true,
            'message' => 'Guest monitoring status updated successfully',
            'guest' => new GuestMonitoringResource($guest)
        ]);
    }
}
