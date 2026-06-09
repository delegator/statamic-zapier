<?php

use Illuminate\Support\Facades\Route;
use Delegator\ZapierForms\Http\Controllers\WebhooksController;

Route::prefix('zapier-forms')->name('zapier-forms.')->group(function () {
    Route::get('/', [WebhooksController::class, 'edit'])->name('index');
    Route::post('/', [WebhooksController::class, 'update'])->name('update');
});
