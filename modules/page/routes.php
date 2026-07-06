<?php

use Illuminate\Support\Facades\Route;
use Modules\Page\Controller\ExportController;
use Modules\Page\Controller\TemplateExcelController;
use Modules\Page\Controller\TestController;
use Modules\User\Controller\UsersController;

Route::middleware('web')->group(function () {
    Route::get('/dang-nhap', [UsersController::class, 'viewLogin'])->name('dang-nhap');

    Route::get('/quen-mat-khau', [UsersController::class, 'viewForgetPassword'])->name('quen-mat-khau');

    Route::post('/login', [UsersController::class, 'login']);
    Route::post('/forget-password', [UsersController::class, 'forgetPassword']);
});

Route::middleware(['web', 'auth.custom'])->group(function () {
    Route::get('/', function () {
        return view('page::home');
    });
    Route::get('/test', [ExportController::class, 'test']);
});
