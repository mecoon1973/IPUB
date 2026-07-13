<?php

namespace Modules\Page\Controller;

use App\Http\Controllers\Controller;
use ExportFile\html\EditHtml;
use ExportFile\html\HtmlToDocxExporter;
use ExportFile\phpExcel\EditExcel;
use ExportFile\phpWord\EditWord;
use Illuminate\Http\Request;
use LibreOffice\LibreOfficeCMD;
use Modules\System\Object\ContentEditTemplate;
use Modules\System\Service\TemplateExportService;
use Modules\Topic\Service\PhieuChuyenBanThaoService;
use Modules\Topic\Service\PhieuDkDetaiService;

class ExportController extends Controller
{
    public function test(Request $request)
    {
        /** @var TemplateExportService $templateExcelService */
        $templateExcelService = app(TemplateExportService::class);
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
        /** @var TemplateExportService $templateExcelService */
        $templateExcelService = app(TemplateExportService::class);
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

    public function testPhieudk(Request $request){

        /** @var TemplateExportService $templateExcelService */
        $templateExcelService = app(TemplateExportService::class);
        $templateExcel = $templateExcelService->findOne("no-cache", ["key" => "Template_Phieu_DK_DE_TAI"]);
        $pathFile = $templateExcelService->getTemplateFileAbsolutePath($templateExcel->path_file_template_doc);
        $editHtml = new EditHtml($pathFile);
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        $phieu_dk_detai = $phieuDkDetaiService->findOne("no-cache", ["_id" => 171252]);
        $data = ["phieu_dk_detai" => $phieu_dk_detai];

        $contentEditItems = ContentEditTemplate::listFromArray($templateExcel->content_edit ?? []);
        $dataRender = [];
        foreach ($contentEditItems as $contentEdit) {
            if ($contentEdit->type === ContentEditTemplate::TYPE_TEXT) {
                $dataRender = array_merge($dataRender, $contentEdit->getDataText($data));
            }
        }
        $editHtml->replateContent($dataRender);
        $editHtml->save("E:\\Dowload\\rpPhieuDkDetai_new.html");
        dd("done");

    }
    public function testHtml(Request $request){

        /** @var TemplateExportService $templateExcelService */
        $templateExcelService = app(TemplateExportService::class);
        $templateExcel = $templateExcelService->findOne("no-cache", ["key" => "Template_Phieu_DK_DE_TAI"]);
        $pathFile = $templateExcelService->getTemplateFileAbsolutePath($templateExcel->path_file_template_doc);
        $editHtml = new EditHtml($pathFile);
        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        $phieu_dk_detai = $phieuDkDetaiService->findOne("no-cache", ["_id" => 171252]);
        $data = ["phieu_dk_detai" => $phieu_dk_detai];

        $contentEditItems = ContentEditTemplate::listFromArray($templateExcel->content_edit ?? []);
        $dataRender = [];
        foreach ($contentEditItems as $contentEdit) {
            if ($contentEdit->type === ContentEditTemplate::TYPE_TEXT) {
                $dataRender = array_merge($dataRender, $contentEdit->getDataText($data, true));
            }
        }
        dd($dataRender);
        $editHtml->replateContent($dataRender);
        $editHtml->save("E:\\Dowload\\rpPhieuDkDetai_new.html");
        dd("done");

    }
    /**
     * Test export DOCX: lấy template + dữ liệu phiếu → map placeholder → ghi file .docx.
     *
     * Luồng:
     * 1. TemplateExport (key) → path .docx tuyệt đối
     * 2. EditWord mở ZIP/XML (DocxTemplateEditor), nối placeholder !Name! bị Word tách run
     * 3. PhieuDkDetai → $data["phieu_dk_detai"]
     * 4. content_edit type=text → getDataText: placeholder => giá trị (data_get + callback)
     * 5. replateContent → replaceLiteral trên body/header/footer
     * 6. saveAs → file output
     */
    public function testDocx(Request $request){

        /** @var TemplateExportService $templateExcelService */
        $templateExcelService = app(TemplateExportService::class);
        // Cấu hình template: path_file_template_doc + content_edit (map placeholder)
        $templateExcel = $templateExcelService->findOne("no-cache", ["key" => "Template_Phieu_DK_DE_TAI"]);
        $pathFile = $templateExcelService->getTemplateFileAbsolutePath($templateExcel->path_file_template_doc);

        // Clone DOCX vào temp; fixBrokenBangPlaceholders nối "!"" + "Name" + "!" → "!Name!"
        $editDocx = new EditWord($pathFile);

        /** @var PhieuDkDetaiService $phieuDkDetaiService */
        $phieuDkDetaiService = app(PhieuDkDetaiService::class);
        $phieu_dk_detai = $phieuDkDetaiService->findOne("no-cache", ["_id" => 171252]);
        // Root key phải khớp content_edit.map_replate[].value (vd. "phieu_dk_detai.ten_de_tai")
        $data = ["phieu_dk_detai" => $phieu_dk_detai];

        // content_edit → DTO; TYPE_TEXT thay placeholder đơn; TYPE_LOOP → applyLoopRows / duplicate+fill
        $contentEditItems = ContentEditTemplate::listFromArray($templateExcel->content_edit ?? []);
        $dataRender = [];
        foreach ($contentEditItems as $contentEdit) {
            if ($contentEdit->type === ContentEditTemplate::TYPE_TEXT) {
                // Ví dụ: ["!TenDeTai!" => "Đề tài ABC", "!TacGia!" => "..."]
                $dataRender = array_merge($dataRender, $contentEdit->getDataText($data));
            }
        }

        // HelperEditWord: từng cặp → DocxTemplateEditor::replaceLiteral (escape XML, case-insensitive)
        $editDocx->replateContent($dataRender);
        $editDocx->save("E:\\Dowload\\rpPhieuDkDetai_new.docx");
        dd("done");

    }

    public function testConvertHtml2docx(Request $request) {

        $htmlPath = 'E:\\Dowload\\rptPhieuDangKiDeTaiMauMoi.html';
        $outputDir = 'E:\\Dowload';

        $docxPath = LibreOfficeCMD::convert($htmlPath, LibreOfficeCMD::FORMAT_DOCX, $outputDir);
        dd($docxPath);
    }
    public function testConvertXlsx2Html(Request $request) {
        $inputpDir = 'E:\\Dowload\\rptPhieuDangKiDeTaiMauMoi.xlsx';
        $outputDir = 'E:\\Dowload';

        $docxPath = LibreOfficeCMD::convert($inputpDir, LibreOfficeCMD::FORMAT_HTML, $outputDir);
        dd($docxPath);
    }

}
