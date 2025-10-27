<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // ログインなしのユーザー作成ルートでCSRF保護を無効化
        $middleware->validateCsrfTokens(except: [
            'admin/users/create-without-auth'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();