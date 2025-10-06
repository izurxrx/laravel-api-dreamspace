<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RateResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'rate_category' => $this->rate_category,
            'facility_id' => $this->facility_id,
            'guest_type_id' => $this->guest_type_id,
            'rate_type' => $this->rate_type,
            'duration_hours' => $this->duration_hours,
            'time_period' => $this->time_period,
            'base_rate' => number_format($this->base_rate, 2),
            'description' => $this->description,
            'status' => $this->status,
            'extension_fee' => number_format($this->extension_fee, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'facility' => new FacilityResource($this->whenLoaded('facility')),
            'guest_type' => new GuestTypeResource($this->whenLoaded('guestType')),
            'discounts' => DiscountResource::collection($this->whenLoaded('discounts')),
            'rate_discounts' => RateDiscountResource::collection($this->whenLoaded('rateDiscounts'))
        ];
    }
}
