<?php

namespace Modules\Page\Controller;

use App\Http\Controllers\Controller;
use ExportFile\phpExcel\EditExcel;
use Illuminate\Http\Request;
use LibreOffice\LibreOfficeCMD;
use Modules\System\Object\ContentEditTemplate;
use Modules\System\Service\TemplateExcelService;
use Modules\Topic\Service\PhieuChuyenBanThaoService;

class ExportController extends Controller
{
    public function test(Request $request)
    {
        /** @var TemplateExcelService $templateExcelService */
        $templateExcelService = app(TemplateExcelService::class);
        $templateExcel = $templateExcelService->findOne("no-cache", ["key" => "template1"]);
        $pathFile = $templateExcelService->getTemplateFileAbsolutePath($templateExcel->path_file_template);
        $editExcel = new EditExcel($pathFile);
        /** @var PhieuChuyenBanThaoService $phieuChuyenBanThaoService */
        $phieuChuyenBanThaoService = app(PhieuChuyenBanThaoService::class);
        $phieuChuyenBanThao = $phieuChuyenBanThaoService->findOne("no-cache", ["id" => 36861]);
        $data = ["phieuChuyenBanThao" => $phieuChuyenBanThao];

        $dataRender = [];
        $contentEditItems = ContentEditTemplate::listFromArray($templateExcel->content_edit ?? []);

        foreach ($contentEditItems as $contentEdit) {
            if ($contentEdit->type === ContentEditTemplate::TYPE_TEXT) {
                $dataRender = array_merge($dataRender, $contentEdit->getDataText($data));
            }
        }

        $editExcel->appendContentToSheet($dataRender);
        $editExcel->save("E:\\Dowload\\rpPhieuChuyenBanThao2024_new.xlsx");
        dd("done");
    }

    public function testForeach(Request $request){
        $listItem = [];
        for($i=0; $i<10; $i++){
            $listItem[] = [
                'a' => "a".$i,
                'b' => "b".$i,
                'c' => "c".$i,
                'd' => "d".$i,
                'e' => "e".$i,
                'f' => "f".$i,
            ];
        }
        /** @var TemplateExcelService $templateExcelService */
        $templateExcelService = app(TemplateExcelService::class);
        $templateExcel = $templateExcelService->findOne("no-cache", ["key" => "template1"]);
        $pathFile = $templateExcelService->getTemplateFileAbsolutePath($templateExcel->path_file_template);
        $editExcel = new EditExcel($pathFile);
        $data = ["listItem" => $listItem];
        $contentEditItems = ContentEditTemplate::listFromArray($templateExcel->content_edit ?? []);
        foreach ($contentEditItems as $contentEdit) {
            if ($contentEdit->type !== ContentEditTemplate::TYPE_LOOP) {
                continue;
            }

            $matrixContent = $contentEdit->getDataLoop($data);

            if ($matrixContent === []) {
                continue;
            }

            $columnKeys = $contentEdit->getLoopColumnKeys();
            $editExcel->duplicateRowCellsBelow($columnKeys, count($matrixContent) - 1);
            $editExcel->fillDuplicatedRowValues($columnKeys, $matrixContent);
        }

        $editExcel->save("E:\\Dowload\\new_foreach.xlsx");
        dd("done");
    }

}
