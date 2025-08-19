<?php

use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;


Route::get('/{any}', function () {
    return Response::file(public_path('index.html'));
})->where('any', '^(?!api).*$'); // Exclude /api/* routes
