<?php

use App\Http\Controllers\Api\ProfileController as ApiProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user()
            ? response()->json($request->user())
            : response()->json(null, 401);
    });

    Route::get('/social-providers', function () {
        return response()->json(array_values(array_filter(
            ['github', 'facebook', 'x', 'google', 'apple'],
            fn (string $provider) => ! empty(env(strtoupper($provider === 'x' ? 'X' : $provider).'_CLIENT_ID')),
        )));
    });

    Route::middleware('auth')->group(function () {
        Route::put('/settings/profile', [ApiProfileController::class, 'update']);
        Route::delete('/settings/profile', [ApiProfileController::class, 'destroy']);
    });
});
