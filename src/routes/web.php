<?php

use Illuminate\Support\Facades\Route;
use admin\banners\Controllers\BannerManagerController;

Route::name('admin.')->middleware(['web','admin.auth'])->group(function () {  
    Route::resource('banners', BannerManagerController::class);
    Route::post('banners/updateStatus', [BannerManagerController::class, 'updateStatus'])->name('banners.updateStatus');

});
