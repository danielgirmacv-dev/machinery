<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $turnstileRequired = config('services.turnstile.secret_key') && !app()->environment('local');

        return [
            'email'                 => ['required', 'string', 'email'],
            'password'              => ['required', 'string', 'min:6'],
            'turnstile_token'       => ['nullable', 'string'],
            'cf-turnstile-response' => [$turnstileRequired ? 'required_without:turnstile_token' : 'nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'                         => 'An email address is required.',
            'email.email'                            => 'Please enter a valid email address.',
            'password.required'                      => 'A password is required.',
            'password.min'                           => 'Password must be at least 6 characters.',
            'cf-turnstile-response.required_without' => 'Please complete the security check.',
        ];
    }
}
