<?php

namespace App\Http\Requests\Machine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMachineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->canEdit();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('machine_name') && ! $this->filled('machine_name')) {
            $code = $this->input('machine_code')
                ?? $this->route('machine')?->machine_code;

            if ($code) {
                $this->merge(['machine_name' => $code]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $machineId = $this->route('machine')->id ?? $this->route('machine');

        return [
            'machine_code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('machines', 'machine_code')->ignore($machineId),
            ],
            // nullable — incomplete CSV-imported machines may have no name yet
            'machine_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'department_id' => ['nullable', 'integer', 'exists:departments,id'],
            'location_id' => ['nullable', 'integer', 'exists:locations,id'],
            'serial_number' => ['nullable', 'string', 'max:100'],
            'status' => [
                'sometimes',
                Rule::in(['working', 'faulty', 'disposed', 'under_maintenance']),
            ],
            'purchase_date' => ['nullable', 'date', 'before_or_equal:today'],
            'remarks' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string', 'max:2000'],
            'machine_type_id' => ['nullable', 'integer', 'exists:machine_types,id'],
            'machine_type' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'machine_group' => ['nullable', 'string', 'max:255'],
            'engine_type' => ['nullable', 'string', 'max:255'],
            'engine_serial_number' => ['nullable', 'string', 'max:255'],
            'plate_number' => ['nullable', 'string', 'max:255'],
            'power' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'numeric'],
            'purchase_order_number' => ['nullable', 'string', 'max:255'],
            'received_date' => ['nullable', 'date', 'before_or_equal:today'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'numeric'],
            'manufacturing_year' => ['nullable', 'integer'],
            'movement_reason' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'machine_code.unique' => 'This machine code already exists.',
            'purchase_date.before_or_equal' => 'Purchase date cannot be in the future.',
            'category_id.exists' => 'Selected category does not exist.',
            'department_id.exists' => 'Selected department does not exist.',
            'location_id.exists' => 'Selected location does not exist.',
        ];
    }
}
