<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Sentry\Laravel\Integration;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Sentry integration
        Integration::handles($exceptions);

        $response = function (string $error, int $status) {
            return response()->json(['error' => $error, 'status' => $status], $status);        };

        $exceptions->shouldRenderJsonWhen(function (Request $request) {
            return $request->is('*') || $request->expectsJson();
        });

        $exceptions->render(function (Throwable $e) use ($response) {
            $code = $e->getCode();
            $code = is_int($code) && ($code >= 400 && $code < 500) ? $code : 500;;

            return $response($e->getMessage(), $code);
        });
    })->create();
