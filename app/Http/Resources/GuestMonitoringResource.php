<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestMonitoringResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'guest_name' => $this->guest_name,
            'contact_number' => $this->contact_number,
            'entry_time' => $this->entry_time,
            'exit_time' => $this->exit_time,
            'status' => $this->status,
            'total_fee' => number_format($this->total_fee, 2),
            'is_checked_out' => !is_null($this->exit_time),
            'duration_minutes' => $this->exit_time ? 
                $this->entry_time->diffInMinutes($this->exit_time) : 
                $this->entry_time->diffInMinutes(now()),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'details' => GuestMonitoringDetailResource::collection($this->whenLoaded('details')),
            'discount_applications' => DiscountApplicationResource::collection($this->whenLoaded('discountApplications')),
            
            'details_count' => $this->whenCounted('details'),
            'discount_applications_count' => $this->whenCounted('discountApplications')
        ];
    }
}
