<?php

namespace ExportFile;

use DOMDocument;
use DOMElement;
use DOMXPath;
use DOMNodeList;

class ReadDOMDocument {

    /** DOMDocument sử dụng để đọc html */
    public DOMDocument $dom;

    /** DOMXPath sử dụng để tìm kiếm node trong html */
    public DOMXPath $xpath;

    /** DOMNodeList sử dụng để lấy danh sách node trong html */
    public DOMNodeList $nodes;

    /** DOMNodeList sử dụng để lấy danh sách node image trong html */
    /** @var array<string, string> */
    public array $imageNodes;


    public function __construct(string $html){
        /** tạo DOMDocument và đọc html */
        $this->dom = new DOMDocument('1.0', 'UTF-8');
        $this->dom->formatOutput = true;
        libxml_use_internal_errors(true);
        $this->dom->loadHTML('<?xml encoding="utf-8" ?>' .$html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        /** tạo DOMXPath và tìm kiếm node trong html */
        $this->xpath = new DOMXPath($this->dom);
        /** tạo DOMNodeList và lấy danh sách node trong html */
        $this->nodes = $this->xpath->query('//*');

        $this->imageNodes = $this->downloadImage($this->getImageNodes());
    }

    /** lấy danh sách node trong html */
    public function getNodes() {
        return $this->nodes;
    }

    /** lấy tất cả các thẻ img trong html */
    public function getImageNodes(): DOMNodeList {
        return $this->xpath->query('//img');
    }

    /** tải về tất cả các thẻ img trong html */
    public function downloadImage(DOMNodeList $nodes) {
        $result = [];
        foreach ($nodes as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }
            $src = trim($node->getAttribute('src'));
            if ($src === '' || !filter_var($src, FILTER_VALIDATE_URL)) {
                continue; // bỏ qua nếu không phải link
            }
            if (isset($result[$src])) {
                continue; // cùng link → không tải lại
            }
            $localPath = $this->downloadUrlToTemp($src);
            if ($localPath !== null) {
                $result[$src] = $localPath;
            }
        }
        return $result;
    }
    /** tải về image từ url và lưu vào thư mục temp */
    public static function downloadUrlToTemp(string $url): ?string
    {
        $context = stream_context_create([
            'http' => [
                'method'  => 'GET',
                'header'  => "User-Agent: Mozilla/5.0\r\n",
                'timeout' => 15,
            ],
        ]);
        $content = @file_get_contents($url, false, $context);
        if ($content === false || $content === '') {
            return null;
        }
        $path = parse_url($url, PHP_URL_PATH) ?: '';
        $ext = pathinfo($path, PATHINFO_EXTENSION) ?: 'png';
        $PATH_TEMP_IMAGES = storage_path('app/temp/phpword-images');
        if (!is_dir($PATH_TEMP_IMAGES)) {
            mkdir($PATH_TEMP_IMAGES, 0755, true);
        }
        $localPath = $PATH_TEMP_IMAGES . DIRECTORY_SEPARATOR . uniqid('img_', true) . '.' . $ext;
        return file_put_contents($localPath, $content) !== false ? $localPath : null;
    }

    /** xóa tất cả các image đã tải về */
    public function cleanupTempImages(): void {
        foreach($this->imageNodes as $url => $localPath) {
            if (file_exists($localPath)) {
                unlink($localPath);
            }
        }
    }

    public function __destruct()
    {
        /** xóa tất cả các image đã tải về trong trường hợp bị lỗi chưa chạy đc đến hàm cleanupTempImages ở export */
        $this->cleanupTempImages();
    }
}
