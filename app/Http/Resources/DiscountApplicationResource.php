<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountApplicationResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guest_monitoring_id' => $this->guest_monitoring_id,
            'discount_id' => $this->discount_id,
            'custom_name' => $this->custom_name,
            'type' => $this->type,
            'discount_rate' => number_format($this->discount_rate, 2),
            'applied_value' => number_format($this->applied_value, 2),
            'display_name' => $this->custom_name ?? $this->discount?->name ?? 'Custom Discount',
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'discount' => new DiscountResource($this->whenLoaded('discount')),
            'guest_monitoring' => new GuestMonitoringResource($this->whenLoaded('guestMonitoring'))
        ];
    }
}
