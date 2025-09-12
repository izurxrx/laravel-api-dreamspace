<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rateName' => $this->rate_name,
            'rateCategory' => $this->rate_category,
            'facilityId' => $this->facility_id,
            'rateType' => $this->rate_type,
            'timePeriod' => $this->time_period,
            'baseRate' => $this->base_rate,
            'durationHours' => $this->duration_hours,
            'durationType' => $this->duration_type,
            'applicableHours' => $this->applicable_hours,
            'maxBookingTime' => $this->max_booking_time,
            'description' => $this->description,
            'status' => $this->status,
            'extensionFee' => $this->extension_fee,
            'isActive' => $this->is_active,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
