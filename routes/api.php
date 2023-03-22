<?php

use Illuminate\Support\Facades\Route;

Route::prefix('oauth')->group(function () {
    collect([
        'register' => [
            'method' => 'post',
            'auth' => false,
        ],
        'logout' => [
            'method' => 'post',
            'auth' => true,
        ],
        'me' => [
            'method' => 'get',
            'auth' => true,
        ],
    ])->each(function ($item, $method) {
        $http_method = $item['method'];
        $auth = $item['auth'];
        if ($controller = passportPgtServer()->getControllers($method)) {
            if ($auth) {
                Route::$http_method($method, $controller)
                    ->middleware('auth:api')
                    ->name('server_'.$method);
            } else {
                Route::$http_method($method, $controller)->name('server_'.$method);
            }
        }
    });
});
