<?php

use Illuminate\Support\Facades\Route;
// use Modules\Book\Controller\PhieuChuyenBanThaoController;
use Modules\Book\Controller\SachController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'book'], function () {
            Route::get('/paginate/{page?}', [SachController::class, 'getPaginate'])->name('sach.paginate')->where('page', regexRoute("page"));
            Route::get('/list', [SachController::class, 'getList'])->name('sach.list');
            Route::post('/store', [SachController::class, 'store'])->name('sach.store');
            Route::delete('/delete/{id}', [SachController::class, 'delete'])->name('sach.delete');
        });
        // Route::group(['prefix' => 'phieu-chuyen-ban-thao'], function () {
        //     Route::get('/paginate/{page?}', [PhieuChuyenBanThaoController::class, 'getPaginate'])->name('phieu-chuyen-ban-thao.paginate')->where('page', regexRoute("page"));
        //     Route::get('/list', [PhieuChuyenBanThaoController::class, 'getList'])->name('phieu-chuyen-ban-thao.list');
        //     Route::post('/store', [PhieuChuyenBanThaoController::class, 'store'])->name('phieu-chuyen-ban-thao.store');
        //     Route::delete('/delete/{id}', [PhieuChuyenBanThaoController::class, 'delete'])->name('phieu-chuyen-ban-thao.delete');
        // });
    });

});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'sach'], function () {
        Route::get('/quan-ly', [SachController::class, 'viewManageSach'])->name('sach.manage');
        Route::get('/in-ma-isbn/{id}', [SachController::class, 'viewPrintISBN'])->name('sach.print-isbn');
    });
    Route::group(['prefix' => 'phieu-chuyen-ban-thao'], function () {
        // Route::get('/quan-ly', [PhieuChuyenBanThaoController::class, 'viewManagePhieuChuyenBanThao'])->name('phieu-chuyen-ban-thao.manage');
        // Route::get('/cap-nhat/{id?}', [PhieuChuyenBanThaoController::class, 'viewStorePhieuChuyenBanThao'])->name('phieu-chuyen-ban-thao.store');
    });
});
