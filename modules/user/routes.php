<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Controller\UsersController;

Route::group(['middleware' => ['web', 'auth.custom']], function () {

    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('/paginate/{page?}', [UsersController::class, 'getPaginateUser'])->where('page', regexRoute("page"));
            Route::get('/list', [UsersController::class, 'getListUser']);
            Route::post('/store', [UsersController::class, 'storeUser'])->name('user.store');
            Route::delete('/delete/{id}', [UsersController::class, 'deleteUser'])->name('user.delete');
            Route::post('/create-account/{id}', [UsersController::class, 'createAccount'])->name('user.createAccount');
            Route::post('/reset-password/{id}', [UsersController::class, 'resetPassword'])->name('user.resetPassword');

        });
    });
});

Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'tai-khoan'], function () {
        Route::get('/quan-ly', [UsersController::class, 'viewManageUser'])->name('user.manage');
        Route::get('/cap-nhat/{id?}', [UsersController::class, 'viewStoreUser'])->name('user.store');
        Route::group(['prefix' => '{id}'], function () {
            Route::get('/cap-tai-khoan', [UsersController::class, 'viewCreateAccount'])->name('user.create-account');
            Route::get('/phan-quyen', [UsersController::class, 'viewAssignPermissions'])->name('user.assign-permissions');
            // Route::get('/dong-bo-ky-so', [UsersController::class, 'viewSyncKySo'])->name('user.sync-ky-so');
        });
    });
});
