<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceRecordResource extends JsonResource
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
            'machine_id' => $this->machine_id,
            'machine' => MachineResource::make($this->whenLoaded('machine')),
            'maintenance_type' => $this->maintenance_type,
            'type_label' => $this->type_label,
            'description' => $this->description,
            'performed_by' => $this->performed_by,
            'performed_at' => $this->performed_at?->format('Y-m-d'),
            'next_maintenance_date' => $this->next_maintenance_date?->format('Y-m-d'),
            'cost' => $this->cost,
            'remarks' => $this->remarks,
            'is_overdue' => $this->isOverdue(),
            'created_by' => UserResource::make($this->whenLoaded('createdBy')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
