<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->whenLoaded('user')),
            'action' => $this->action,
            'action_label' => $this->action_label,
            'auditable_type' => $this->auditable_type,
            'auditable_id' => $this->auditable_id,
            'model_name' => $this->model_name,
            'old_values' => $this->old_values,
            'new_values' => $this->new_values,
            'changes' => $this->changes,
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
