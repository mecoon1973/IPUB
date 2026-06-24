<?php

use Illuminate\Support\Facades\Route;
use Modules\legalDeposit\Controller\PhieuNhapLCController;
use Modules\legalDeposit\Controller\ToKhaiLuuChuyenController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'legal-deposit'], function () {
            Route::group(['prefix' => 'phieu-nhap-lc'], function () {
                Route::get('/paginate/{page?}', [PhieuNhapLCController::class, 'getPaginate'])->name('phieu-nhap-lc.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [PhieuNhapLCController::class, 'getList'])->name('phieu-nhap-lc.list');
                Route::post('/store', [PhieuNhapLCController::class, 'store'])->name('phieu-nhap-lc.store');
                Route::delete('/delete/{id}', [PhieuNhapLCController::class, 'delete'])->name('phieu-nhap-lc.delete');
            });
            Route::group(['prefix' => 'to-khai-luu-chuyen'], function () {
                Route::get('/paginate/{page?}', [ToKhaiLuuChuyenController::class, 'getPaginate'])->name('to-khai-luu-chuyen.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [ToKhaiLuuChuyenController::class, 'getList'])->name('to-khai-luu-chuyen.list');
                Route::post('/store', [ToKhaiLuuChuyenController::class, 'store'])->name('to-khai-luu-chuyen.store');
                Route::delete('/delete/{id}', [ToKhaiLuuChuyenController::class, 'delete'])->name('to-khai-luu-chuyen.delete');
            });
        });
    });

});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'quan-ly-luu-chieu'], function () {
        Route::group(['prefix' => 'phieu-nhap-lc'], function () {
            Route::get('/quan-ly', [PhieuNhapLCController::class, 'viewManagePhieuNhapLC'])->name('phieu-nhap-lc.manage');
            Route::get('/cap-nhat/{id?}', [PhieuNhapLCController::class, 'viewStorePhieuNhapLC'])->name('phieu-nhap-lc.store');
        });
        Route::group(['prefix' => 'to-khai-luu-chuyen'], function () {
            Route::get('/quan-ly', [ToKhaiLuuChuyenController::class, 'viewManageToKhaiLuuChuyen'])->name('to-khai-luu-chuyen.manage');
            Route::get('/cap-nhat/{id?}', [ToKhaiLuuChuyenController::class, 'viewStoreToKhaiLuuChuyen'])->name('to-khai-luu-chuyen.store');
        });
    });
});
