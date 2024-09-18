<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $status
 * @property mixed $bank
 * @property mixed $id
 * @property mixed $created_at
 * @property mixed $reference_number
 * @property mixed $user
 */
class WithDrawHistoryResource extends JsonResource
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
            'player_name' => $this->user->name,
            'paymentType' => $this->paymentType->name,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
