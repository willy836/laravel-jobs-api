<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'company' => $this->company,
            'position' => $this->position,
            'status' => $this->status,
            'user_id' => (string)$this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
