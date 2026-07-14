<?php

use Illuminate\Support\Facades\Route;
use Modules\System\Controller\BienMoiTruongController;
use Modules\System\Controller\BosachController;
use Modules\System\Controller\ChucnangController;
use Modules\System\Controller\ChuyenmonController;
use Modules\System\Controller\DoituongController;
use Modules\System\Controller\DonviController;
use Modules\System\Controller\HDXBController;
use Modules\System\Controller\LopController;
use Modules\System\Controller\MangsachController;
use Modules\System\Controller\NhomController;
use Modules\System\Controller\PhanheController;
use Modules\System\Controller\QuyenController;
use Modules\System\Controller\MonhocController;
use Modules\System\Controller\TusachController;
use Modules\System\Controller\ChucvuController;
use Modules\System\Controller\CongviecchebaninController;
use Modules\System\Controller\CongviecthietkeController;
use Modules\System\Controller\DoituongSNVController;
use Modules\System\Controller\DonviLCController;
use Modules\System\Controller\LoaiXBPController;
use Modules\System\Controller\LoaiXBPLCController;
use Modules\System\Controller\MangsachCXBController;
use Modules\System\Controller\NgoainguController;
use Modules\System\Controller\SystemLogController;
use Modules\System\Controller\TemplateExportController;
use ExportFile\phpWord\ExportWord;

Route::group(['middleware' => ['web', 'auth.custom']], function () {

    Route::group(['prefix' => 'api'], function () {
        Route::group(['prefix' => 'system'], function () {
            Route::group(['prefix' => 'donvi'], function () {
                Route::get('/get-all', [DonviController::class, 'getAllDonvi'])->name('donvi.get-all');
                Route::post('/store', [DonviController::class, 'store'])->name('donvi.store');
                Route::delete('/delete/{id}', [DonviController::class, 'delete'])->name('donvi.delete');
            });
            Route::group(['prefix' => 'hdxb'], function () {
                Route::get('/get-all', [HDXBController::class, 'getAllHDXB'])->name('hdxb.get-all');
                Route::post('/store', [HDXBController::class, 'store'])->name('hdxb.store');

            });
            Route::group(['prefix' => 'quyen'], function () {
                Route::get('/get-all', [QuyenController::class, 'getAllQuyen'])->name('quyen.get-all');
                Route::post('/store', [QuyenController::class, 'store'])->name('quyen.store');
                Route::delete('/delete/{id}', [QuyenController::class, 'delete'])->name('quyen.delete');
            });
            Route::group(['prefix' => 'nhom'], function () {
                Route::get('/list/{page?}', [NhomController::class, 'getListNhom'])->name('nhom.lists')->where('page', regexRoute("page"));
                Route::get('/get-all', [NhomController::class, 'getAllNhom'])->name('nhom.get-all');
                Route::post('/store', [NhomController::class, 'store'])->name('nhom.store');
                Route::post('/add-canbo-to-nhom/{id}', [NhomController::class, 'addCanboToNhom'])->name('nhom.add-canbo-to-nhom');
                Route::delete('/delete/{id}', [NhomController::class, 'delete'])->name('nhom.delete');
            });
            Route::group(['prefix' => 'chuc-nang'], function () {
                Route::get('/get-all', [ChucnangController::class, 'getAllChucnang'])->name('chucnang.get-all');
                Route::post('/store', [ChucnangController::class, 'store'])->name('chucnang.store');
                Route::delete('/delete/{id}', [ChucnangController::class, 'delete'])->name('chucnang.delete');
            });
            Route::group(['prefix' => 'phan-he'], function () {
                Route::get('/get-all', [PhanheController::class, 'getAllPhanhe'])->name('phanhe.get-all');
            });
            Route::group(['prefix' => 'lop'], function () {
                Route::get('/paginate/{page?}', [LopController::class, 'getPaginateLop'])->name('lop.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [LopController::class, 'getListLop'])->name('lop.list');
                Route::post('/store', [LopController::class, 'store'])->name('lop.store');
                Route::delete('/delete/{id}', [LopController::class, 'delete'])->name('lop.delete');
            });
            Route::group(['prefix' => 'mon-hoc'], function () {
                Route::get('/paginate/{page?}', [MonhocController::class, 'getPaginateMonhoc'])->name('monhoc.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [MonhocController::class, 'getListMonhoc'])->name('monhoc.list');
                Route::post('/store', [MonhocController::class, 'store'])->name('monhoc.store');
                Route::delete('/delete/{id}', [MonhocController::class, 'delete'])->name('monhoc.delete');
            });
            Route::group(['prefix' => 'mang-sach'], function () {
                Route::get('/paginate/{page?}', [MangsachController::class, 'getPaginateMangsach'])->name('mangsach.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [MangsachController::class, 'getListMangsach'])->name('mangsach.list');
                Route::post('/store', [MangsachController::class, 'store'])->name('mangsach.store');
                Route::delete('/delete/{id}', [MangsachController::class, 'delete'])->name('mangsach.delete');
            });
            Route::group(['prefix' => 'mang-sach-cxb'], function () {
                Route::get('/paginate/{page?}', [MangsachCXBController::class, 'getPaginate'])->name('mangsachCXB.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [MangsachCXBController::class, 'getList'])->name('mangsachCXB.list');
                Route::post('/store', [MangsachCXBController::class, 'store'])->name('mangsachCXB.store');
                Route::delete('/delete/{id}', [MangsachCXBController::class, 'delete'])->name('mangsachCXB.delete');
            });
            Route::group(['prefix' => 'bo-sach'], function () {
                Route::get('/paginate/{page?}', [BosachController::class, 'getPaginateBosach'])->name('bosach.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [BosachController::class, 'getListBosach'])->name('bosach.list');
                Route::post('/store', [BosachController::class, 'store'])->name('bosach.store');
                Route::delete('/delete/{id}', [BosachController::class, 'delete'])->name('bosach.delete');
            });
            Route::group(['prefix' => 'doi-tuong'], function () {
                Route::get('/paginate/{page?}', [DoituongController::class, 'getPaginateDoituong'])->name('doituong.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [DoituongController::class, 'getListDoituong'])->name('doituong.list');
                Route::post('/store', [DoituongController::class, 'store'])->name('doituong.store');
                Route::delete('/delete/{id}', [DoituongController::class, 'delete'])->name('doituong.delete');
            });
            Route::group(['prefix' => 'doi-tuong-snv'], function () {
                Route::get('/paginate/{page?}', [DoituongSNVController::class, 'getPaginate'])->name('doituongSNV.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [DoituongSNVController::class, 'getList'])->name('doituongSNV.list');
                Route::post('/store', [DoituongSNVController::class, 'store'])->name('doituongSNV.store');
                Route::delete('/delete/{id}', [DoituongSNVController::class, 'delete'])->name('doituongSNV.delete');
            });
            Route::group(['prefix' => 'donvi-lc'], function () {
                Route::get('/paginate/{page?}', [DonviLCController::class, 'getPaginate'])->name('donviLC.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [DonviLCController::class, 'getList'])->name('donviLC.list');
                Route::post('/store', [DonviLCController::class, 'store'])->name('donviLC.store');
                Route::delete('/delete/{id}', [DonviLCController::class, 'delete'])->name('donviLC.delete');
            });
            Route::group(['prefix' => 'tu-sach'], function () {
                Route::get('/paginate/{page?}', [TusachController::class, 'getPaginateTusach'])->name('tusach.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [TusachController::class, 'getListTusach'])->name('tusach.list');
                Route::post('/store', [TusachController::class, 'store'])->name('tusach.store');
                Route::delete('/delete/{id}', [TusachController::class, 'delete'])->name('tusach.delete');
            });
            Route::group(['prefix' => 'chuyen-mon'], function () {
                Route::get('/paginate/{page?}', [ChuyenmonController::class, 'getPaginateChuyenmon'])->name('chuyenmon.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [ChuyenmonController::class, 'getList'])->name('chuyenmon.list');
                Route::post('/store', [ChuyenmonController::class, 'store'])->name('chuyenmon.store');
                Route::delete('/delete/{id}', [ChuyenmonController::class, 'delete'])->name('chuyenmon.delete');
            });
            Route::group(['prefix' => 'chuc-vu'], function () {
                Route::get('/paginate/{page?}', [ChucvuController::class, 'getPaginateChucvu'])->name('chucvu.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [ChucvuController::class, 'getListChucvu'])->name('chucvu.list');
                Route::post('/store', [ChucvuController::class, 'store'])->name('chucvu.store');
                Route::delete('/delete/{id}', [ChucvuController::class, 'delete'])->name('chucvu.delete');
            });
            Route::group(['prefix' => 'loai-xbp'], function () {
                Route::get('/paginate/{page?}', [LoaiXBPController::class, 'getPaginateLoaiXBP'])->name('loaibp.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [LoaiXBPController::class, 'getListLoaiXBP'])->name('loaibp.list');
                Route::post('/store', [LoaiXBPController::class, 'store'])->name('loaibp.store');
                Route::delete('/delete/{id}', [LoaiXBPController::class, 'delete'])->name('loaibp.delete');
            });
            Route::group(['prefix' => 'ngoai-ngu'], function () {
                Route::get('/paginate/{page?}', [NgoainguController::class, 'getPaginate'])->name('ngoaingu.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [NgoainguController::class, 'getList'])->name('ngoaingu.list');
                Route::post('/store', [NgoainguController::class, 'store'])->name('ngoaingu.store');
                Route::delete('/delete/{id}', [NgoainguController::class, 'delete'])->name('ngoaingu.delete');
            });
            Route::group(['prefix' => 'cong-viec-thiet-ke'], function () {
                Route::get('/paginate/{page?}', [CongviecthietkeController::class, 'getPaginate'])->name('congviecthietke.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [CongviecthietkeController::class, 'getList'])->name('congviecthietke.list');
                Route::post('/store', [CongviecthietkeController::class, 'store'])->name('congviecthietke.store');
                Route::delete('/delete/{id}', [CongviecthietkeController::class, 'delete'])->name('congviecthietke.delete');
            });
            Route::group(['prefix' => 'cong-viec-che-ban-in'], function () {
                Route::get('/paginate/{page?}', [CongviecchebaninController::class, 'getPaginate'])->name('congviecchebanin.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [CongviecchebaninController::class, 'getList'])->name('congviecchebanin.list');
                Route::post('/store', [CongviecchebaninController::class, 'store'])->name('congviecchebanin.store');
                Route::delete('/delete/{id}', [CongviecchebaninController::class, 'delete'])->name('congviecchebanin.delete');
            });
            Route::group(['prefix' => 'system-log'], function () {
                Route::get('/paginate/{page?}', [SystemLogController::class, 'getPaginate'])->name('systemLog.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [SystemLogController::class, 'getList'])->name('systemLog.list');
            });
            Route::group(['prefix' => 'bien-moi-truong'], function () {
                Route::get('/paginate/{page?}', [BienMoiTruongController::class, 'getPaginate'])->name('bienMoiTruong.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [BienMoiTruongController::class, 'getList'])->name('bienMoiTruong.list');
                Route::post('/store', [BienMoiTruongController::class, 'store'])->name('bienMoiTruong.store');
                Route::delete('/delete/{id}', [BienMoiTruongController::class, 'delete'])->name('bienMoiTruong.delete');
            });
            Route::group(['prefix' => 'loai-xbp-luu-chieu'], function () {
                Route::get('/paginate/{page?}', [LoaiXBPLCController::class, 'getPaginate'])->name('loaiXbpLc.paginate')->where('page', regexRoute("page"));
                Route::get('/list', [LoaiXBPLCController::class, 'getList'])->name('loaiXbpLc.list');
                Route::post('/store', [LoaiXBPLCController::class, 'store'])->name('loaiXbpLc.store');
                Route::delete('/delete/{id}', [LoaiXBPLCController::class, 'delete'])->name('loaiXbpLc.delete');
            });
            Route::group(['prefix' => 'template-export'], function () {
                Route::get('/get-list', [TemplateExportController::class, 'getList'])->name('templateExport.get-list');
                Route::get('/paginate/{page?}', [TemplateExportController::class, 'getPaginate'])->name('templateExport.get-paginate');
                Route::post('/upload', [TemplateExportController::class, 'uploadTemplate'])->name('templateExport.upload');
                Route::post('/store', [TemplateExportController::class, 'store'])->name('templateExport.store');
                Route::delete('/delete/{id}', [TemplateExportController::class, 'delete'])->name('templateExport.delete');
            });
        });
    });

});


Route::group(['middleware' => ['web', 'auth.custom']], function () {
    Route::group(['prefix' => 'he-thong'], function () {
        Route::group(['prefix' => 'template-export'], function () {
            Route::get('/', [TemplateExportController::class, 'viewManageTemplateExport'])->name('templateExport.manage');
            Route::get('/cap-nhat/{id?}', [TemplateExportController::class, 'viewStoreTemplateExport'])->name('templateExport.store');

        });
        Route::group(['prefix' => 'don-vi'], function () {
            Route::get('/quan-ly', [DonviController::class, 'viewManageDonvi'])->name('donvi.manage');
            Route::get('/cap-nhat/{id?}', [DonviController::class, 'viewStoreDonvi'])->name('donvi.store');

        });
        Route::group(['prefix' => 'hdxb'], function () {
            Route::get('/quan-ly', [HDXBController::class, 'viewManageHDXB'])->name('hdxb.manage');
            Route::get('/cap-nhat/{id?}', [HDXBController::class, 'viewStoreHDXB'])->name('hdxb.store');

        });
        Route::group(['prefix' => 'quyen'], function () {
            Route::get('/quan-ly', [QuyenController::class, 'viewManageQuyen'])->name('quyen.manage');
            Route::get('/gan-chuc-nang-vao-quyen/{id}', [QuyenController::class, 'viewPermissionSettings'])->name('quyen.permission-settings');
            Route::get('/cap-nhat/{id?}', [QuyenController::class, 'viewStoreQuyen'])->name('quyen.store');

        });
        Route::group(['prefix' => 'nhom'], function () {
            Route::get('/quan-ly', [NhomController::class, 'viewManageNhom'])->name('nhom.manage');
            Route::get('/quan-ly-can-bo/{id}', [NhomController::class, 'viewManageCanboInNhom'])->name('nhom.manage-can-bo');
            Route::get('/phan-quyen-nhom/{id}', [NhomController::class, 'viewPermissionSettingsNhom'])->name('nhom.permission-settings');
            Route::get('/cap-nhat/{id?}', [NhomController::class, 'viewStoreNhom'])->name('nhom.store');
        });
        Route::group(['prefix' => 'chuc-nang'], function () {
            Route::get('/quan-ly', [ChucnangController::class, 'viewManageChucnang'])->name('chucnang.manage');
            Route::get('/cap-nhat/{id?}', [ChucnangController::class, 'viewStoreChucnang'])->name('chucnang.store');
        });
        Route::group(['prefix' => 'lop'], function () {
            Route::get('/quan-ly', [LopController::class, 'viewManageLop'])->name('lop.manage');
            Route::get('/cap-nhat/{id?}', [LopController::class, 'viewStoreLop'])->name('lop.store');
        });
        Route::group(['prefix' => 'mon-hoc'], function () {
            Route::get('/quan-ly', [MonhocController::class, 'viewManageMonhoc'])->name('monhoc.manage');
            Route::get('/cap-nhat/{id?}', [MonhocController::class, 'viewStoreMonhoc'])->name('monhoc.store');
        });
        Route::group(['prefix' => 'mang-sach'], function () {
            Route::get('/quan-ly', [MangsachController::class, 'viewManageMangsach'])->name('mangsach.manage');
            Route::get('/cap-nhat/{id?}', [MangsachController::class, 'viewStoreMangsach'])->name('mangsach.store');
        });
        Route::group(['prefix' => 'mang-sach-cxb'], function () {
            Route::get('/quan-ly', [MangsachCXBController::class, 'viewManageMangsachCXB'])->name('mangsachCXB.manage');
            Route::get('/cap-nhat/{id?}', [MangsachCXBController::class, 'viewStoreMangsachCXB'])->name('mangsachCXB.store');
        });
        Route::group(['prefix' => 'bo-sach'], function () {
            Route::get('/quan-ly', [BosachController::class, 'viewManageBosach'])->name('bosach.manage');
            Route::get('/cap-nhat/{id?}', [BosachController::class, 'viewStoreBosach'])->name('bosach.store');
        });
        Route::group(['prefix' => 'doi-tuong'], function () {
            Route::get('/quan-ly', [DoituongController::class, 'viewManageDoituong'])->name('doituong.manage');
            Route::get('/cap-nhat/{id?}', [DoituongController::class, 'viewStoreDoituong'])->name('doituong.store');
        });
        Route::group(['prefix' => 'doi-tuong-snv'], function () {
            Route::get('/quan-ly', [DoituongSNVController::class, 'viewManageDoituongSNV'])->name('doituongSNV.manage');
            Route::get('/cap-nhat/{id?}', [DoituongSNVController::class, 'viewStoreDoituongSNV'])->name('doituongSNV.store');
        });
        Route::group(['prefix' => 'tu-sach'], function () {
            Route::get('/quan-ly', [TusachController::class, 'viewManageTusach'])->name('tusach.manage');
            Route::get('/cap-nhat/{id?}', [TusachController::class, 'viewStoreTusach'])->name('tusach.store');
        });
        Route::group(['prefix' => 'chuyen-mon'], function () {
            Route::get('/quan-ly', [ChuyenmonController::class, 'viewManageChuyenmon'])->name('chuyenmon.manage');
            Route::get('/cap-nhat/{id?}', [ChuyenmonController::class, 'viewStoreChuyenmon'])->name('chuyenmon.store');
        });
        Route::group(['prefix' => 'chuc-vu'], function () {
            Route::get('/quan-ly', [ChucvuController::class, 'viewManageChucvu'])->name('chucvu.manage');
            Route::get('/cap-nhat/{id?}', [ChucvuController::class, 'viewStoreChucvu'])->name('chucvu.store');
        });
        Route::group(['prefix' => 'loai-xbp'], function () {
            Route::get('/quan-ly', [LoaiXBPController::class, 'viewManageLoaiXBP'])->name('loaibp.manage');
            Route::get('/cap-nhat/{id?}', [LoaiXBPController::class, 'viewStoreLoaiXBP'])->name('loaibp.store');
        });
        Route::group(['prefix' => 'ngoai-ngu'], function () {
            Route::get('/quan-ly', [NgoainguController::class, 'viewManageNgoaingu'])->name('ngoaingu.manage');
            Route::get('/cap-nhat/{id?}', [NgoainguController::class, 'viewStoreNgoaingu'])->name('ngoaingu.store');
        });
        Route::group(['prefix' => 'cong-viec-thiet-ke'], function () {
            Route::get('/quan-ly', [CongviecthietkeController::class, 'viewManageCongviecthietke'])->name('congviecthietke.manage');
            Route::get('/cap-nhat/{id?}', [CongviecthietkeController::class, 'viewStoreCongviecthietke'])->name('congviecthietke.store');
        });
        Route::group(['prefix' => 'cong-viec-che-ban-in'], function () {
            Route::get('/quan-ly', [CongviecchebaninController::class, 'viewManageCongviecchebanin'])->name('congviecchebanin.manage');
            Route::get('/cap-nhat/{id?}', [CongviecchebaninController::class, 'viewStoreCongviecchebanin'])->name('congviecchebanin.store');
        });
        Route::group(['prefix' => 'lich-su-thao-tac'], function () {
            Route::get('/quan-ly', [SystemLogController::class, 'viewManageSystemLog'])->name('systemLog.manage');
        });
        Route::group(['prefix' => 'bien-moi-truong'], function () {
            Route::get('/quan-ly', [BienMoiTruongController::class, 'viewManageBienMoiTruong'])->name('bienMoiTruong.manage');
            Route::get('/cap-nhat/{id?}', [BienMoiTruongController::class, 'viewStoreBienMoiTruong'])->name('bienMoiTruong.store');
        });
        Route::group(['prefix' => 'donvi-lc'], function () {
            Route::get('/quan-ly', [DonviLCController::class, 'viewManageDonviLC'])->name('donviLC.manage');
            Route::get('/cap-nhat/{id?}', [DonviLCController::class, 'viewStoreDonviLC'])->name('donviLC.store');
        });
        Route::group(['prefix' => 'loai-xbp-luu-chieu'], function () {
            Route::get('/quan-ly', [LoaiXBPLCController::class, 'viewManageLoaiXbpLc'])->name('loaiXbpLc.manage');
            Route::get('/cap-nhat/{id?}', [LoaiXBPLCController::class, 'viewStoreLoaiXbpLc'])->name('loaiXbpLc.store');
        });


    });
});
