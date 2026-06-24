<?php

use Illuminate\Support\Facades\Route;
use Modules\QualityAssessment\Controller\DSDocRaSoatController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {

    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'quality-assessment'], function () {
            Route::group(['prefix' => 'ds-doc-ra-soat'], function () {
                Route::get('/paginate/{page?}', [DSDocRaSoatController::class, 'getPaginate'])->name('ds-doc-ra-soat.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [DSDocRaSoatController::class, 'getList'])->name('ds-doc-ra-soat.list');
                Route::post('/store', [DSDocRaSoatController::class, 'store'])->name('ds-doc-ra-soat.store');
                Route::delete('/delete/{id}', [DSDocRaSoatController::class, 'delete'])->name('ds-doc-ra-soat.delete');
            });

        });

    });

});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'quan-ly-kiem-dinh'], function () {
        Route::group(['prefix' => 'ds-doc-ra-soat'], function () {
            Route::get('/quan-ly', [DSDocRaSoatController::class, 'viewManageDSDocRaSoat'])->name('ds-doc-ra-soat.manage');
            Route::get('/cap-nhat/{id?}', [DSDocRaSoatController::class, 'viewStoreDSDocRaSoat'])->name('ds-doc-ra-soat.store');
        });

    });
});
