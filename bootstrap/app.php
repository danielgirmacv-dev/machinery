<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard'));

        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ── 401 Unauthenticated ──────────────────────────────────────────────
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthenticated. Please log in to continue.',
                    'errors'  => [],
                ], 401);
            }
        });

        // ── 403 Forbidden / Unauthorized ────────────────────────────────────
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'You do not have permission to perform this action.',
                    'errors'  => [],
                ], 403);
            }
        });

        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to perform this action.',
                    'errors'  => [],
                ], 403);
            }
        });

        // ── 404 Not Found ───────────────────────────────────────────────────
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $model = class_basename($e->getModel());
                return response()->json([
                    'message' => "{$model} not found.",
                    'errors'  => [],
                ], 404);
            }
        });

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'The requested endpoint was not found.',
                    'errors'  => [],
                ], 404);
            }
        });

        // ── 422 Validation ───────────────────────────────────────────────────
        // Laravel handles this automatically, but we ensure the shape is consistent.
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage(),
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // ── 429 Too Many Requests ────────────────────────────────────────────
        $exceptions->render(function (TooManyRequestsHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;
                return response()->json([
                    'message' => "Too many requests. Please try again in {$retryAfter} seconds.",
                    'errors'  => [],
                ], 429);
            }
        });

        // ── Generic HTTP Exceptions (e.g. abort(403)) ────────────────────────
        // NOTE: This must NOT cover TooManyRequestsHttpException (429) or
        // AccessDeniedHttpException (403) as those are handled specifically above.
        // It MUST come after all specific HttpException subclasses.
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $status  = $e->getStatusCode();
                // Skip 429 and 403 — already handled by specific renderers above
                if (in_array($status, [403, 429])) {
                    return null; // Let the specific handler above take over
                }
                $message = $e->getMessage() ?: match ($status) {
                    400     => 'Bad request.',
                    405     => 'Method not allowed.',
                    408     => 'Request timeout.',
                    503     => 'Service temporarily unavailable.',
                    default => 'An error occurred.',
                };
                return response()->json([
                    'message' => $message,
                    'errors'  => [],
                ], $status);
            }
        });

        // ── 500 Unhandled Server Exceptions ──────────────────────────────────
        // IMPORTANT: We only handle actual unexpected server errors here.
        // We explicitly exclude known HTTP and validation exceptions so their
        // specific handlers above are not bypassed.
        $exceptions->render(function (\Throwable $e, Request $request) {
            // Only handle truly unhandled exceptions (not HTTP-typed or validation)
            if ($e instanceof HttpException
                || $e instanceof ValidationException
                || $e instanceof AuthenticationException
                || $e instanceof AuthorizationException
                || $e instanceof ModelNotFoundException
            ) {
                return null; // Let the specific handler above deal with it
            }

            if ($request->is('api/*') || $request->expectsJson()) {
                $message = app()->isProduction()
                    ? 'A server error occurred. Please try again later.'
                    : $e->getMessage();

                return response()->json([
                    'message' => $message,
                    'errors'  => [],
                ], 500);
            }
        });

    })->create();
