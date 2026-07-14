<?php
namespace ExportFile;

use Exception;
use ExportFile\phpExcel\EditExcel;
use ExportFile\phpWord\EditWord;
use LibreOffice\LibreOfficeCMD;
use Modules\System\Model\DM_TEMPLATE_EXPORT;
use Modules\System\Object\ContentEditTemplate;
use Modules\System\Service\TemplateExportService;

class ExportFile {
    /** @var DM_TEMPLATE_EXPORT $template */
    public DM_TEMPLATE_EXPORT $template;

    /** @var TemplateExportService $templateExportService */
    public TemplateExportService $templateExportService;

    /** @var array $data dữ liệu xuất file */
    public array $data;

    /** @var string $type định dạng muốn xuất */
    public string $type;

    public function __construct(string $keyTemplate, array $data, string $type){
        /** @var TemplateExportService $templateExportService */
        $this->templateExportService = app(TemplateExportService::class);

        $this->template = $this->getTemplate($keyTemplate);
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Lấy template từ database
     * @param string $keyTemplate
     * @return DM_TEMPLATE_EXPORT
     */
    public function getTemplate(string $keyTemplate): DM_TEMPLATE_EXPORT {
        /** @var DM_TEMPLATE_EXPORT $template */
        $template = $this->templateExportService->findOne("no-cache", ["key" => $keyTemplate]);
        if(!$template){
            throw new Exception("Template không tồn tại");
        }
        return $template;
    }

    protected function getKeyTemplate(): string {
        return $this->template->key;
    }

    public function getArrayContentEdit(): array{
        return ContentEditTemplate::listFromArray($this->template->content_edit ?? []);
    }

    /**
     * @return string URL public tới file .xlsx
     */
    public function exportExcel(): string {
        $pathFile = $this->templateExportService->getTemplateFileAbsolutePath($this->template->path_file_template);
        $editExcel = new EditExcel($pathFile);
        $contentText = ContentEditTemplate::getTypeData($this->getArrayContentEdit(), $this->data, ['isExcel' => true]);
        $outputPath = '';

        switch($this->getKeyTemplate()){
            case "Template_Phieu_DK_DE_TAI":
                $editExcel->appendContentToSheet($contentText);
                $outputPath = core_build_unique_public_tmp_path('rpPhieuDkDetai', 'xlsx');
                $editExcel->save($outputPath);
                break;
            default:
                throw new Exception("Template này đang không hỗ trợ xuất file excel");
        }

        return core_public_absolute_path_to_url($outputPath);
    }

    /**
     * Xuất DOCX ra public/file_tmp và trả đường dẫn tuyệt đối (dùng nội bộ / LibreOffice).
     */
    protected function exportDocxToAbsolutePath(): string {
        $pathFile = $this->templateExportService->getTemplateFileAbsolutePath($this->template->path_file_template_doc);
        $editDocx = new EditWord($pathFile);
        $contentText = ContentEditTemplate::getTypeData($this->getArrayContentEdit(), $this->data, ['getText' => true]);
        $contentHtml = ContentEditTemplate::getTypeData($this->getArrayContentEdit(), $this->data, ['getHtml' => true]);
        $outputPath = '';

        switch($this->getKeyTemplate()){
            case "Template_Phieu_DK_DE_TAI":
                $editDocx->replateContent($contentText);
                $editDocx->replateHtmlContent($contentHtml);
                $editDocx->applyLoopRows(ContentEditTemplate::getTypeDataLoop($this->getArrayContentEdit(), $this->data));
                $outputPath = core_build_unique_public_tmp_path('rpPhieuDkDetai', 'docx');
                $editDocx->save($outputPath);
                break;
            default:
                throw new Exception("Template này đang không hỗ trợ xuất file docx");
        }

        return $outputPath;
    }

    /**
     * @return string URL public tới file .docx
     */
    public function exportDocx(): string {
        return core_public_absolute_path_to_url($this->exportDocxToAbsolutePath());
    }

    /**
     * @return string URL public tới file .pdf
     */
    public function exportPdf(): string {
        $docxPath = $this->exportDocxToAbsolutePath();
        if ($docxPath === '' || !is_file($docxPath)) {
            throw new Exception("không tìm thấy file gốc .docx");
        }
        $pdfPath = LibreOfficeCMD::convert(
            $docxPath,
            LibreOfficeCMD::FORMAT_PDF,
            core_public_file_tmp_directory()
        );

        return core_public_absolute_path_to_url($pdfPath);
    }

    /**
     * @return string URL public tới file .txt
     */
    public function exportTxt(): string {
        $docxPath = $this->exportDocxToAbsolutePath();
        if ($docxPath === '' || !is_file($docxPath)) {
            throw new Exception("không tìm thấy file gốc .docx");
        }
        $txtPath = LibreOfficeCMD::convert(
            $docxPath,
            LibreOfficeCMD::FORMAT_TXT,
            core_public_file_tmp_directory()
        );

        return core_public_absolute_path_to_url($txtPath);
    }

    /**
     * @return string URL public tới file .html
     */
    public function exportHtml(): string {
        $docxPath = $this->exportDocxToAbsolutePath();
        if ($docxPath === '' || !is_file($docxPath)) {
            throw new Exception("không tìm thấy file gốc .docx");
        }
        $htmlPath = LibreOfficeCMD::convert(
            $docxPath,
            LibreOfficeCMD::FORMAT_HTML,
            core_public_file_tmp_directory()
        );

        return core_public_absolute_path_to_url($htmlPath);
    }

    /**
     * @return string URL public tới file đã xuất
     */
    public function export(): string {
        switch($this->type){
            case "excel":
                return $this->exportExcel();
            case "docx":
                return $this->exportDocx();
            case "pdf":
                return $this->exportPdf();
            case "txt":
                return $this->exportTxt();
            case "html":
                return $this->exportHtml();
            default:
                throw new Exception("Định dạng xuất không hỗ trợ");
        }
    }
}
