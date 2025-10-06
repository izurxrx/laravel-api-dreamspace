<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateDiscountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate_id' => $this->rate_id,
            'discount_id' => $this->discount_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            
            'rate' => new RateResource($this->whenLoaded('rate')),
            'discount' => new DiscountResource($this->whenLoaded('discount'))
        ];
    }
}
