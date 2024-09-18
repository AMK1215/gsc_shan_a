<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'player_id' => $this->user_name,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'balance' => $this->wallet->balance,
            'status' => $this->status,
        ];

    }
}
