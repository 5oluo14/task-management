<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            $this->mergeWhen($this->token !== null, [
                'access_token' => $this->token,
                'token_type' => 'Bearer',
            ]),
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
