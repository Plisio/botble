<?php

use Plisio\PlisioPayment\Http\Controllers\PlisioController;
use Illuminate\Support\Facades\Route;

Route::middleware('core')
    ->prefix('payment/plisio')
    ->name('payments.plisio.')
    ->group(function () {
        Route::get('success', [PlisioController::class, 'success'])
            ->middleware('web')
            ->name('success');

        Route::get('error', [PlisioController::class, 'error'])
            ->middleware('web')
            ->name('error');

        Route::post('webhook', [PlisioController::class, 'webhook'])
            ->name('webhook');
    });
