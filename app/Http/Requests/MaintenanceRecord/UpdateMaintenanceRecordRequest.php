<?php

namespace App\Http\Requests\MaintenanceRecord;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMaintenanceRecordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->canEdit();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'maintenance_type' => [
                'sometimes',
                Rule::in(['preventive', 'corrective', 'inspection']),
            ],
            'description' => ['sometimes', 'required', 'string', 'max:2000'],
            'performed_by' => ['nullable', 'string', 'max:255'],
            'performed_at' => ['sometimes', 'date', 'before_or_equal:today'],
            'next_maintenance_date' => ['nullable', 'date', 'after:performed_at'],
            'cost' => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'remarks' => ['nullable', 'string', 'max:1000'],
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
            'performed_at.before_or_equal' => 'Maintenance date cannot be in the future.',
            'next_maintenance_date.after' => 'Next maintenance date must be after the performed date.',
            'cost.max' => 'Cost cannot exceed 9,999,999.99.',
        ];
    }
}
