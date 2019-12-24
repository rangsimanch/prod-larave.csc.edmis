<?php

namespace App\Http;

use App\Http\Middleware\ConstructionContractSelect;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\TrimStrings::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];
/**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'api' => [
            'throttle:60,1',
            'bindings',
            \App\Http\Middleware\AuthGates::class,
        ],
        'web' => [
            \App\Http\Middleware\ConstructionContractSelect::class,
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\AuthGates::class,
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ApprovalMiddleware::class,
        ],
    ];
 /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'can'              => \Illuminate\Auth\Middleware\Authorize::class,
        'auth'             => \Illuminate\Auth\Middleware\Authenticate::class,
        'guest'            => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed'           => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle'         => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'cache.headers'    => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'bindings'         => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'auth.basic'       => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
<<<<<<< HEAD
        'select' => \App\Http\Middleware\ConstructionContractSelect::class,
=======
       // 'ConstructionContract.Select' => \App\Http\Middleware\ConstructionContractSelect::class,
>>>>>>> parent of 2c893c1... Constraction Contract Select
        
    ];
}
