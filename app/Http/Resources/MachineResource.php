<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'machine_code'      => $this->machine_code,
            'machine_name'      => $this->machine_name,
            'category_id'       => $this->category_id,
            'category'          => CategoryResource::make($this->whenLoaded('category')),
            'machine_type_id'   => $this->machine_type_id,
            'machine_type'      => MachineTypeResource::make($this->whenLoaded('machineType')),
            'department_id'     => $this->department_id,
            'department'        => DepartmentResource::make($this->whenLoaded('department')),
            'location_id'       => $this->location_id,
            'location'          => LocationResource::make($this->whenLoaded('location')),
            'serial_number'     => $this->serial_number,
            'status'            => $this->status,
            'status_label'      => $this->status_label,
            'purchase_date'     => $this->purchase_date?->format('Y-m-d'),
            'remarks'           => $this->remarks,
            // Extended fields
            'description'           => $this->description,
            'machine_type_text'     => $this->machine_type,
            'model'                 => $this->model,
            'machine_group'         => $this->machine_group,
            'engine_type'           => $this->engine_type,
            'engine_serial_number'  => $this->engine_serial_number,
            'plate_number'          => $this->plate_number,
            'power'                 => $this->power,
            'weight'                => $this->weight,
            'purchase_order_number' => $this->purchase_order_number,
            'received_date'         => $this->received_date?->format('Y-m-d'),
            'manufacturer'          => $this->manufacturer,
            'supplier'              => $this->supplier,
            'price'                 => $this->price,
            'manufacturing_year'    => $this->manufacturing_year,
            // Computed
            'next_maintenance_date' => $this->next_maintenance_date?->format('Y-m-d'),
            'created_by'        => UserResource::make($this->whenLoaded('createdBy')),
            'updated_by'        => UserResource::make($this->whenLoaded('updatedBy')),
            'maintenance_records'   => MaintenanceRecordResource::collection($this->whenLoaded('maintenanceRecords')),
            'movement_histories'    => MovementHistoryResource::collection($this->whenLoaded('movementHistories')),
            'created_at'        => $this->created_at?->toISOString(),
            'updated_at'        => $this->updated_at?->toISOString(),
        ];
    }
}
