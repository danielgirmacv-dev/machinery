<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class TurnstileService
{
    public function verify(?string $token, ?string $remoteIp = null): void
    {
        if (empty(config('services.turnstile.secret_key'))) {
            return;
        }

        if (empty($token)) {
            if (app()->environment('local')) {
                return;
            }
            throw ValidationException::withMessages([
                'email' => ['Please complete the security check.'],
            ]);
        }

        $payload = [
            'secret' => config('services.turnstile.secret_key'),
            'response' => $token,
        ];

        if ($remoteIp) {
            $payload['remoteip'] = $remoteIp;
        }

        $response = Http::asForm()->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            $payload
        );

        if (! $response->json('success')) {
            $errorCodes = $response->json('error-codes') ?? ['invalid-input-response'];
            $errorReason = implode(', ', $errorCodes);

            throw ValidationException::withMessages([
                'email' => ["Security check failed ({$errorReason}). Please try again."],
            ]);
        }
    }
}
