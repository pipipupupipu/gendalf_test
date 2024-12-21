<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminApiController;
use App\Http\Controllers\PartnerApiController;

Route::post('/login', [AdminController::class, 'login']);
Route::post('/logout', [AdminController::class, 'logout']);

Route::whereIn('tableName', ['items', 'categories'])
    ->group(function () {
        Route::get('/{tableName}/{id}', [PartnerApiController::class, 'get'])
            ->where('id', '.*');

        Route::get('/{tableName}', [PartnerApiController::class, 'get']);
    });


Route::prefix('admin')->group(function () {
    Route::whereIn('tableName', ['items', 'categories', 'users', 'partner_rights'])
        ->group(function () {
            Route::get('/{tableName}/{id}', [AdminApiController::class, 'get'])
                ->where('id', '.*');

            Route::get('/{tableName}', [AdminApiController::class, 'get']);

            Route::post('/{tableName}', [AdminApiController::class, 'create']);

            Route::put('/{tableName}/{id}', [AdminApiController::class, 'update']);

            Route::delete('/{tableName}/{id}', [AdminApiController::class, 'delete'])
                ->where('id', '.*');
        });
});

