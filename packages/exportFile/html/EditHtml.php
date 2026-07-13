<?php

namespace ExportFile\html;

use DOMDocument;
use DOMNodeList;
use DOMXPath;
use ExportFile\html\Traits\HelperEditHtml;
use RuntimeException;

/**
 * Chỉnh sửa file HTML template có sẵn bằng DOMDocument.
 *
 * Tương tự {@see \ExportFile\phpExcel\EditExcel} nhưng thao tác trên cây DOM HTML
 * thay vì ô Excel. Dùng để thay placeholder (vd: `[!TenSach!]`) bằng dữ liệu thực
 * trước khi export PDF/Word hoặc trả HTML đã điền.
 *
 * Luồng sử dụng cơ bản:
 *   1. new EditHtml($pathFile)  — đọc file HTML vào DOM
 *   2. replateContent($map)      — thay từng placeholder bằng value
 *   3. prepareForLibreOfficeDocxExport() — chuẩn hóa layout trước convert DOCX (nếu cần)
 *   4. save() / getHtml()        — lấy HTML đã chỉnh sửa
 */
class EditHtml
{
    use HelperEditHtml;

    /** Đường dẫn tuyệt đối file HTML gốc */
    protected string $originalPath;

    /** DOMDocument — cây DOM đại diện toàn bộ nội dung HTML */
    public DOMDocument $dom;

    /** DOMXPath — truy vấn node trong DOM (vd: //img, //*) */
    public DOMXPath $xpath;

    /** Danh sách tất cả element node (`//*`), cập nhật lại sau mỗi lần replate */
    public DOMNodeList $nodes;

    /** @var array<string, string> Map URL ảnh → đường dẫn local (dùng khi xử lý img) */
    public array $imageNodes = [];

    /**
     * Đọc file HTML từ đĩa và parse vào DOMDocument.
     *
     * @param string $pathFile Đường dẫn tuyệt đối hoặc tương đối tới file .html
     *
     * @throws RuntimeException File không tồn tại, rỗng, hoặc parse HTML thất bại
     */
    public function __construct(string $pathFile)
    {
        $this->originalPath = core_normalize_path($pathFile);
        assert_file_exists($this->originalPath, null);

        $html = file_get_contents($this->originalPath);
        if ($html === false || $html === '') {
            throw new RuntimeException('Không thể đọc file HTML: ' . $this->originalPath);
        }

        $this->dom = $this->loadDomDocument($html);
        $this->initializeDomQueries();
    }

    /** @return string Đường dẫn tuyệt đối file HTML gốc */
    public function getOriginalPath(): string
    {
        return $this->originalPath;
    }

    /** @return DOMNodeList Danh sách tất cả element trong DOM */
    public function getNodes(): DOMNodeList
    {
        return $this->nodes;
    }

    /**
     * Thay hàng loạt placeholder trong HTML bằng giá trị tương ứng.
     *
     * Mỗi cặp key → value: tìm chuỗi `$placeholder` trong text node, thay bằng `$value`.
     * Key thường lấy từ {@see \Modules\System\Object\ContentEditTemplate::getDataText()}.
     *
     * @param array<string, mixed> $content Map placeholder → value
     *                                      Vd: ['[!TenSach!]' => 'Toán 1', '[!TacGia!]' => 'Nguyễn Văn A']
     */
    public function replateContent(array $content): void
    {
        foreach ($content as $placeholder => $value) {
            if (!is_string($placeholder) || $placeholder === '') {
                continue;
            }

            $this->replacePlaceholderValue($placeholder, $value);
        }

        $this->refreshNodeList();
    }

    /**
     * Lấy tất cả thẻ &lt;img&gt; trong HTML.
     *
     * Dùng khi cần tải/thay src ảnh trước export (tương tự ReadDOMDocument::getImageNodes).
     *
     * @return DOMNodeList Danh sách node img
     */
    public function getImageNodes(): DOMNodeList
    {
        return $this->xpath->query('//img');
    }

    public function save(string $pathFile): void {
        $this->dom->saveHTMLFile($pathFile);
        $this->refreshNodeList();
    }

    public function getHtml(): string {
        return $this->dom->saveHTML();
    }
}
