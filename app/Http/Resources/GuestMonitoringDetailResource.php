<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestMonitoringDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guest_monitoring_id' => $this->guest_monitoring_id,
            'rate_id' => $this->rate_id,
            'guest_count' => $this->guest_count,
            'applied_rate' => number_format($this->applied_rate, 2),
            'total_amount' => number_format($this->applied_rate * $this->guest_count, 2),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'rate' => new RateResource($this->whenLoaded('rate')),
            'guest_monitoring' => new GuestMonitoringResource($this->whenLoaded('guestMonitoring')),
            
            'facility' => new FacilityResource($this->whenLoaded('rate.facility')),
            'guest_type' => new GuestTypeResource($this->whenLoaded('rate.guestType'))
        ];
    }
}
