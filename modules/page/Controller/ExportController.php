<?php

namespace Modules\Page\Controller;

use App\Http\Controllers\Controller;
use ExportFile\phpExcel\EditExcel;
use Illuminate\Http\Request;
use LibreOffice\LibreOfficeCMD;

class ExportController extends Controller
{
    public function test(Request $request)
    {
        $res = new EditExcel("E:\\Dowload\\rpPhieuChuyenBanThao2024.xlsx");
        $res->replacePlaceholderValue($res->getSheet(), '$title$', 'test');
        $res->save("E:\\Dowload\\rpPhieuChuyenBanThao2024_new.xlsx");
    }
}
