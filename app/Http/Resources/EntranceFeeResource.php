<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EntranceFeeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'guestType' => $this->guest_type,
            'feeAmount' => $this->fee_amount,
            'description' => $this->description,
            'isActive' => $this->is_active,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
