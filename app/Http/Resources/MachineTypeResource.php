<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'category_id'   => $this->category_id,
            'category'      => CategoryResource::make($this->whenLoaded('category')),
            'category_code' => $this->category_code,
            'description'   => $this->description,
            'eec_number'    => $this->eec_number,
            'machine_count' => $this->whenCounted('machines', $this->machines_count),
            'created_at'    => $this->created_at?->toISOString(),
            'updated_at'    => $this->updated_at?->toISOString(),
        ];
    }
}
