<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'discount_rate' => number_format($this->discount_rate, 2),
            'rate_type' => $this->rate_type,
            'discount_scope' => $this->discount_scope,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'description' => $this->description,
            'is_active' => $this->isActive(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'applications' => DiscountApplicationResource::collection($this->whenLoaded('applications')),
            'rates' => RateResource::collection($this->whenLoaded('rates')),
            'rate_discounts' => RateDiscountResource::collection($this->whenLoaded('rateDiscounts')),
            
            'applications_count' => $this->whenCounted('applications'),
            'rates_count' => $this->whenCounted('rates')
        ];
    }

    private function isActive(): bool
    {
        $now = now();
        $startValid = is_null($this->start_date) || $this->start_date <= $now;
        $endValid = is_null($this->end_date) || $this->end_date >= $now;
        
        return $startValid && $endValid;
    }
}
