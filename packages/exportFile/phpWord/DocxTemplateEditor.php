<?php

namespace ExportFile\phpWord;

use PhpOffice\PhpWord\Escaper\Xml;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * TemplateProcessor mở rộng: thay chuỗi placeholder tùy ý (vd. [!TenSach!], !TenSach!)
 * mà không bọc macro mặc định ${...}.
 *
 * Làm việc trực tiếp trên XML trong ZIP DOCX nên giữ nguyên DrawingML/VML,
 * textbox, và các phần PhpWord IOFactory::load() thường làm mất.
 */
class DocxTemplateEditor extends TemplateProcessor
{
    /**
     * @param string $documentTemplate
     */
    public function __construct($documentTemplate)
    {
        parent::__construct($documentTemplate);

        $this->tempDocumentMainPart = $this->fixBrokenBangPlaceholders($this->tempDocumentMainPart);
        foreach ($this->tempDocumentHeaders as $index => $headerXml) {
            $this->tempDocumentHeaders[$index] = $this->fixBrokenBangPlaceholders($headerXml);
        }
        foreach ($this->tempDocumentFooters as $index => $footerXml) {
            $this->tempDocumentFooters[$index] = $this->fixBrokenBangPlaceholders($footerXml);
        }
    }

    /**
     * Thay mọi lần xuất hiện $search bằng $replace trong body/header/footer.
     *
     * Nếu không tìm thấy exact match và $search dạng !Name!, thử không phân biệt hoa/thường
     * (vd. config !TacGia! vs Word !Tacgia!).
     */
    public function replaceLiteral(string $search, string $replace): void
    {
        if ($search === '') {
            return;
        }

        $replace = static::ensureUtf8Encoded($replace);

        if (Settings::isOutputEscapingEnabled()) {
            $xmlEscaper = new Xml();
            $replace = $xmlEscaper->escape($replace);
        }

        $replace = $this->replaceCarriageReturns($replace);
        $searchKeys = $this->resolveSearchKeys($search);

        foreach ($searchKeys as $searchKey) {
            $this->tempDocumentHeaders = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentHeaders,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
            $this->tempDocumentMainPart = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentMainPart,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
            $this->tempDocumentFooters = $this->setValueForPart(
                $searchKey,
                $replace,
                $this->tempDocumentFooters,
                self::MAXIMUM_REPLACEMENTS_DEFAULT
            );
        }
    }

    /**
     * Word thường tách placeholder !Name! thành nhiều <w:t> (vd. "!" + "Name" + "!").
     * Nối lại thành một chuỗi liền trước khi replace.
     */
    protected function fixBrokenBangPlaceholders(string $xml): string
    {
        if ($xml === '' || !str_contains($xml, '!')) {
            return $xml;
        }

        $fixed = preg_replace_callback(
            '/!(?:(?:(?![!<>])[^<])|<[^>]+>)+?!/u',
            static function (array $match): string {
                $stripped = preg_replace('/<[^>]+>/', '', $match[0]);
                if (!is_string($stripped) || !preg_match('/^![A-Za-z0-9_]+!$/', $stripped)) {
                    return $match[0];
                }

                return $stripped;
            },
            $xml
        );

        return is_string($fixed) ? $fixed : $xml;
    }

    /**
     * @return list<string>
     */
    protected function resolveSearchKeys(string $search): array
    {
        if ($this->documentContains($search)) {
            return [$search];
        }

        if (!preg_match('/^![A-Za-z0-9_]+!$/', $search) && !preg_match('/^\[![A-Za-z0-9_]+!\]$/', $search)) {
            return [$search];
        }

        $caseVariant = $this->findCaseInsensitivePlaceholder($search);
        if ($caseVariant !== null && $caseVariant !== $search) {
            return [$caseVariant];
        }

        return [$search];
    }

    protected function documentContains(string $needle): bool
    {
        if (str_contains($this->tempDocumentMainPart, $needle)) {
            return true;
        }

        foreach ($this->tempDocumentHeaders as $headerXml) {
            if (str_contains($headerXml, $needle)) {
                return true;
            }
        }

        foreach ($this->tempDocumentFooters as $footerXml) {
            if (str_contains($footerXml, $needle)) {
                return true;
            }
        }

        return false;
    }

    protected function findCaseInsensitivePlaceholder(string $search): ?string
    {
        $parts = [$this->tempDocumentMainPart];
        foreach ($this->tempDocumentHeaders as $headerXml) {
            $parts[] = $headerXml;
        }
        foreach ($this->tempDocumentFooters as $footerXml) {
            $parts[] = $footerXml;
        }

        $pattern = '/' . preg_quote($search, '/') . '/iu';
        foreach ($parts as $xml) {
            if (preg_match($pattern, $xml, $match) === 1) {
                return $match[0];
            }
        }

        return null;
    }
}
