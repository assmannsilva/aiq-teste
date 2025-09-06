<?php

use App\Exceptions\ExternalApiException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ExternalApiException $e) {
            $status = $e->getCode() == 400 ? 400 : 502;
            $message = $e->getCode() == 400 ? $e->getMessage() : "External Services Unavailable";

            return response()->json(['error' => $message], $status);
        });
    })->create();
