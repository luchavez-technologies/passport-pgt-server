<?php

use Illuminate\Support\Facades\Route;

collect(config('passport-pgt-server.routes'))->each(function (array $arr, string $key) {
    ['uri' => $uri, 'action' => $action, 'middleware' => $middleware, 'http_method' => $method] = $arr;
    Route::$method($uri, $action)->name("passport-pgt-server.$key")->middleware($middleware);
});
