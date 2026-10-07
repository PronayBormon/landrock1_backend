<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__ . '/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(append: [
            'throttle:api',
        ]);
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        ]);
    })
    ->withBroadcasting(
        __DIR__ . '/../routes/channels.php',
<<<<<<< HEAD
        ['prefix' => 'api', 'middleware' => ['auth:sanctum', 'throttle:api']],
=======
        ['prefix' => 'api', 'middleware' => ['auth:sanctum']],
>>>>>>> 00886d0de841d6b12064315085a82431be136494
    )
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
