<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $this['user'];
        $marquee_text = $this['bannerText'];

        return [
            'user' => [
                'id' => $user->id,
                'player_id' => $user->user_name,
                'name' => $user->name,
                'phone' => $user->phone,
                'balance' => $user->wallet->balance,
            ],
            'marquee_text' => $marquee_text->text,
        ];

    }
}
