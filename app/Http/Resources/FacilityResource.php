<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FacilityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->facilityType ? $this->facilityType->name : null,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'expectedCapacity' => $this->expected_capacity,
            'maxCapacity' => $this->max_capacity,
            'bookingTypes' => $this->booking_types,
            'cutoffTime' => $this->cutoff_time,
            'requiresAccommodation' => $this->requires_accommodation,
            'timeBasedBooking' => $this->time_based_booking,
            'dayBasedBooking' => $this->day_based_booking,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
