<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovementHistoryResource extends JsonResource
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
            'from_department_id' => $this->from_department_id,
            'from_department' => DepartmentResource::make($this->whenLoaded('fromDepartment')),
            'to_department_id' => $this->to_department_id,
            'to_department' => DepartmentResource::make($this->whenLoaded('toDepartment')),
            'from_location_id' => $this->from_location_id,
            'from_location' => LocationResource::make($this->whenLoaded('fromLocation')),
            'to_location_id' => $this->to_location_id,
            'to_location' => LocationResource::make($this->whenLoaded('toLocation')),
            'moved_at' => $this->moved_at?->toISOString(),
            'reason' => $this->reason,
            'summary' => $this->summary,
            'created_by' => UserResource::make($this->whenLoaded('createdBy')),
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
