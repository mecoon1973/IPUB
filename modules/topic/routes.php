<?php

use Illuminate\Support\Facades\Route;
use Modules\Topic\Controller\DetaiCongDoanController;
use Modules\Topic\Controller\HDXBNXBGDVNController;
use Modules\Topic\Controller\PhieuDkDetaiController;
use Modules\Topic\Controller\QDInController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {

    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'topic'], function () {
            Route::group(['prefix' => 'phieu-dk-detai'], function () {
                Route::get('/paginate/{page?}', [PhieuDkDetaiController::class, 'getPaginatePhieuDkDetai'])->name('phieu-dk-detai.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [PhieuDkDetaiController::class, 'getListPhieuDkDetai'])->name('phieu-dk-detai.list');
                Route::post('/store', [PhieuDkDetaiController::class, 'store'])->name('phieu-dk-detai.store');
                Route::delete('/delete/{id}', [PhieuDkDetaiController::class, 'delete'])->name('phieu-dk-detai.delete');
            });
            Route::group(['prefix' => 'detai-congdoan'], function () {
                Route::get('/paginate/{page?}', [DetaiCongDoanController::class, 'getPaginate'])->name('detai-congdoan.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [DetaiCongDoanController::class, 'getList'])->name('detai-congdoan.list');
                Route::post('/store', [DetaiCongDoanController::class, 'store'])->name('detai-congdoan.store');
                Route::delete('/delete/{id}', [DetaiCongDoanController::class, 'delete'])->name('detai-congdoan.delete');
            });
            Route::group(['prefix' => 'qd-in'], function () {
                Route::get('/paginate/{page?}', [QDInController::class, 'getPaginate'])->name('qd-in.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [QDInController::class, 'getList'])->name('qd-in.list');
                Route::post('/store', [QDInController::class, 'store'])->name('qd-in.store');
                Route::delete('/delete/{id}', [QDInController::class, 'delete'])->name('qd-in.delete');
            });
            Route::group(['prefix' => 'hdxb-nxbgdvn'], function () {
                Route::get('/paginate/{page?}', [HDXBNXBGDVNController::class, 'getPaginate'])->name('hdxb-nxbgdvn.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [HDXBNXBGDVNController::class, 'getList'])->name('hdxb-nxbgdvn.list');
            });
        });
    });

});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'phieu-dk-detai'], function () {
        Route::get('/quan-ly', [PhieuDkDetaiController::class, 'viewManagePhieuDkDetai'])->name('phieu-dk-detai.manage');
        Route::get('/tai-ban', [PhieuDkDetaiController::class, 'viewTaiBanPhieuDkDetai'])->name('phieu-dk-detai.tai-ban');
        Route::get('/chuyen-ke-hoach', [PhieuDkDetaiController::class, 'viewChuyenKeHoachPhieuDkDetai'])->name('phieu-dk-detai.chuyen-ke-hoach');
        Route::get('/cap-nhat/{id?}', [PhieuDkDetaiController::class, 'viewStorePhieuDkDetai'])->name('phieu-dk-detai.store');
    });
    Route::group(['prefix' => 'qd-in'], function () {
        Route::get('/quan-ly', [QDInController::class, 'viewManageQDIn'])->name('qd-in.manage');
        Route::get('/cap-nhat/{id?}', [QDInController::class, 'viewStoreQDIn'])->name('qd-in.store');
    });
    Route::group(['prefix' => 'hdxb-nxbgdvn'], function () {
        Route::get('/quan-ly', [HDXBNXBGDVNController::class, 'viewManageHDXBNXBGDVN'])->name('hdxb-nxbgdvn.manage');
    });
});
