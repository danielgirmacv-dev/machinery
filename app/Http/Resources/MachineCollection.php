<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MachineCollection extends ResourceCollection
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = MachineResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }

    /**
     * Get additional data that should be returned with the resource array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        return [
            'meta' => [
                'statuses' => [
                    ['value' => 'working', 'label' => 'Working'],
                    ['value' => 'faulty', 'label' => 'Faulty'],
                    ['value' => 'disposed', 'label' => 'Disposed'],
                    ['value' => 'under_maintenance', 'label' => 'Under Maintenance'],
                ],
            ],
        ];
    }
}
