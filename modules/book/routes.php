<?php

use Illuminate\Support\Facades\Route;
use Modules\Book\Controller\SachController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'book'], function () {
            Route::get('/paginate/{page?}', [SachController::class, 'getPaginate'])->name('sach.paginate')->where('page', regexRoute("page"));
            Route::get('/list', [SachController::class, 'getList'])->name('sach.list');
            Route::post('/store', [SachController::class, 'store'])->name('sach.store');
            Route::delete('/delete/{id}', [SachController::class, 'delete'])->name('sach.delete');
        });
    });

});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'sach'], function () {
        Route::get('/quan-ly', [SachController::class, 'viewManageSach'])->name('sach.manage');
        Route::get('/in-ma-isbn/{id}', [SachController::class, 'viewPrintISBN'])->name('sach.print-isbn');
    });
});
