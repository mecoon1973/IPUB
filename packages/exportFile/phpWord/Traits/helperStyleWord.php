<?php

namespace ExportFile\phpWord\Traits;

use DOMElement;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\JcTable;
use PhpOffice\PhpWord\SimpleType\VerticalJc;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\SimpleType\NumberFormat;

trait HelperStyleWord {

    public static array $MAP_TAGNAME_NUMBERING = [
        "ol" => "numberingCustom",
        "ul" => NumberFormat::BULLET
    ];

    public static array $MAP_STYLE_HEADING = [
        1 => ['size' => 24, 'bold' => true],
        2 => ['size' => 20, 'bold' => true],
        3 => ['size' => 16, 'bold' => true],
        4 => ['size' => 14, 'bold' => true],
        5 => ['size' => 12, 'bold' => true],
        6 => ['size' => 10, 'bold' => true],
    ];

    public static array $MAP_VERTICAL_JC = [
        'top' => VerticalJc::TOP,
        'middle' => VerticalJc::CENTER,
        'center' => VerticalJc::CENTER,
        'bottom' => VerticalJc::BOTTOM,
        'both' => VerticalJc::BOTH,
    ];

    public static array $MAP_JC_TABLE = [
        'left' => JcTable::START,
        'right' => JcTable::END,
        'center' => JcTable::CENTER,
    ];

    public static array $MAP_JC_TEXT_ALIGN = [
        'start' => Jc::START,
        'left' => Jc::START,
        'right' => Jc::END,
        'center' => Jc::CENTER,
        'justify' => Jc::BOTH,
    ];


    /** @return array<string, string> */
    public static function convertStyleNodeToArray(string $style): array
    {
        $style = explode(';', $style);

        return array_reduce(
            array_filter($style, fn($item) => trim($item) !== ''),
            function ($carry, $item) {
                [$key, $val] = array_map('trim', explode(':', $item, 2));
                if ($key !== '') {
                    $carry[$key] = $val ?? '';
                }
                return $carry;
            },
            []
        );
    }

    /** Style font từ thẻ HTML inline (strong, i, u, ...) */
    public static function fontStyleFromTag(string $tag): array
    {
        return match (strtolower($tag)) {
            'strong', 'b' => ['bold' => true],
            'em', 'i' => ['italic' => true],
            'u' => ['underline' => Font::UNDERLINE_SINGLE],
            's', 'strike' => ['strikethrough' => true],
            'sub' => ['subScript' => true],
            'sup' => ['superScript' => true],
            default => [],
        };
    }

    /** các thẻ là inline */
    public static function isInlineTag(string $tag): bool
    {
        return in_array(strtolower($tag), [
            'span', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup', 'a',
        ], true);
    }

    /** các thẻ là block */
    public static function isBlockTag(string $tag): bool
    {
        return in_array(strtolower($tag), [
            'p', 'div', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'figure', 'table', 'tbody', 'tr', 'td', 'th', 'img',
        ], true);
    }

    /**
     * các thẻ là font style (fStyle)
     * @return array<string, mixed>
     * */
    public static function buildFontStyleFromAttribute(string $style): array
    {
        $font = [];
        foreach (self::convertStyleNodeToArray($style) as $key => $value) {
            $mapped = self::mappingStyleHTML2PhpWordFont($key, $value);
            if ($mapped !== null) {
                $font = array_merge($font, $mapped);
            }
        }
        return $font;
    }

    /**
     * các thẻ là paragraph style (pStyle)
     * @return array<string, mixed>
     * */
    public static function buildParagraphStyleFromAttribute(string $style): array
    {
        $paragraph = [];
        foreach (self::convertStyleNodeToArray($style) as $key => $value) {
            $mapped = self::mappingStyleHTML2PhpWordParagraph($key, $value);
            if ($mapped !== null) {
                $paragraph = array_merge($paragraph, $mapped);
            }
        }
        return $paragraph;
    }

    /**
     * mapping style HTML to PhpWord font style
     * @return array<string, mixed>|null
     * */
    public static function mappingStyleHTML2PhpWordFont(string $key, string $value): ?array
    {
        $key = strtolower(trim($key));
        $value = trim($value);

        switch ($key) {
            case 'color':
                $hex = self::colorToHex($value);
                return $hex ? ['color' => $hex] : null;
            case 'font-size':
                $pt = self::parseCssLength($value);
                return $pt !== null ? ['size' => $pt] : null;
            case 'font-weight':
                return ['bold' => ($value === 'bold' || (is_numeric($value) && (int) $value >= 600))];
            case 'font-style':
                return ($value === 'italic' || $value === 'oblique') ? ['italic' => true] : null;
            case 'font-family':
                return ['name' => trim($value, " \t\n\r\0\x0B\"'")];
            case 'text-decoration':
                $style = [];
                if (str_contains($value, 'underline')) {
                    $style['underline'] = Font::UNDERLINE_SINGLE;
                }
                if (str_contains($value, 'line-through')) {
                    $style['strikethrough'] = true;
                }
                return $style ?: null;
            default:
                return null;
        }
    }

    /**
     * mapping style HTML to PhpWord paragraph style(căn lề)
     * @return array<string, mixed>|null
     * */
    public static function mappingStyleHTML2PhpWordParagraph(string $key, string $value): ?array
    {
        $key = strtolower(trim($key));
        $value = trim($value);

        switch ($key) {
            case 'text-align':
                return isset(self::$MAP_JC_TEXT_ALIGN[$value]) ? ['alignment' => self::$MAP_JC_TEXT_ALIGN[$value]] : null;
            case 'line-height':
                return is_numeric($value) ? ['lineHeight' => (float) $value] : null;
            case 'margin-left':
                $twip = \PhpOffice\PhpWord\Shared\Converter::cssToTwip($value);
                return ['indentation' => ['left' => $twip]];
            default:
                return null;
        }
    }

    /**
     * build style image from DOMElement
     * @param DOMElement $domElement
     * @return array<string, mixed>
     * */
    public static function buildStyleImageFromDOMElement(DOMElement $domElement): array
    {
        $style = [];

        $stringStyle = $domElement->getAttribute('style');

        $style = array_merge($style, self::buildParagraphStyleFromAttribute($stringStyle));

        /** sử lý trường hợp width, height được đặt trực tiếp trong attribute style="" */
        foreach (self::convertStyleNodeToArray($stringStyle) as $key => $value) {
            $k = strtolower(trim($key));
            if ($k === 'width') {
                $style['width'] = self::parseCssLength($value, 'px');
            }
            if ($k === 'height') {
                $style['height'] = self::parseCssLength($value, 'px');
            }
        }
        /** sử lý trường hợp width, height được đặt trực tiếp trong attribute width="" height="" */
        if($domElement->getAttribute('width')){
            $style['width'] = self::parseCssLength($domElement->getAttribute('width'), 'px');
        }
        if($domElement->getAttribute('height')){
            $style['height'] = self::parseCssLength($domElement->getAttribute('height'), 'px');
        }

        // nếu không có width, height thì set mặc định là height = 100
        if(!isset($style['width']) && !isset($style['height'])){
            $style['height'] = 75;
        }

        return $style;
    }

    /**
     * Style table từ attribute style="" của <table>
     * @return array<string, mixed>
     */
    public static function buildTableStyleFromAttribute(string $style): array
    {
        $table = [];
        foreach (self::convertStyleNodeToArray($style) as $key => $value) {
            $k = strtolower(trim($key));
            if ($k === 'border-width' && is_numeric(trim($value))) {
                $table['borderSize'] = (int) trim($value);
            }
            if ($k === 'border-color') {
                $hex = self::colorToHex($value);
                if ($hex) {
                    $table['borderColor'] = $hex;
                }
            }
        }
        return $table;
    }

    /**
     * Style cell từ attribute style="" của <td>/<th>
     * @return array<string, mixed>
     */
    public static function buildCellStyleFromAttribute(string $style): array
    {
        $cell = [];
        foreach (self::convertStyleNodeToArray($style) as $key => $value) {
            $k = strtolower(trim($key));
            if ($k === 'background-color') {
                $hex = self::colorToHex($value);
                if ($hex) {
                    $cell['bgColor'] = $hex;
                }
            }
            if ($k === 'text-align') {
                if (isset(self::$MAP_JC_TEXT_ALIGN[$value])) {
                    $cell['alignment'] = self::$MAP_JC_TEXT_ALIGN[$value] ?? self::$MAP_JC_TEXT_ALIGN["start"];
                }
            }
            if ($k === 'vertical-align') {
                if (isset(self::$MAP_VERTICAL_JC[$value])) {
                    $cell['valign'] = self::$MAP_VERTICAL_JC[$value];
                }
            }
            if ($k === 'border-width' && $value === '0px') {
                $cell['borderTopStyle'] = "none";
                $cell['borderBottomStyle'] = "none";
                $cell['borderLeftStyle'] = "none";
                $cell['borderRightStyle'] = "none";
                $cell['borderSize'] = 0;
            }
        }
        return $cell;
    }

    /**
     * mapping style CSS chuyển về chung 1 định dạng pt|px
     * @return float|null
     * */
    public static function parseCssLength(?string $cssValue, string $toUnit = 'pt'): ?float
    {
        if ($cssValue === null || trim($cssValue) === '') {
            return null;
        }
        $cssValue = strtolower(trim($cssValue));
        switch($toUnit){
            case 'pt':
                if (str_ends_with($cssValue, 'px')) {
                    return round((float) $cssValue * 0.75, 2);
                }
                if (str_ends_with($cssValue, 'pt')) {
                    return (float) $cssValue;
                }
                return null;
            case 'px':
                if (str_ends_with($cssValue, 'px')) {
                    return (float) $cssValue;
                }
                return null;
        }

        if (is_numeric($cssValue)) {
            return (float) $cssValue;
        }
        return null;
    }

    /**
     * Chuyển màu CSS (#hex, rgb, hsl) về hex cho PhpWord.
     */
    public static function colorToHex(string $value): ?string
    {
        $value = trim($value);
        if (preg_match('/^#([0-9a-f]{3}|[0-9a-f]{6})$/i', $value)) {
            $hex = ltrim($value, '#');
            if (strlen($hex) === 3) {
                $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
            }
            return strtoupper($hex);
        }
        if (preg_match('/^rgba?\(/i', $value)) {
            return self::rgbToHex($value);
        }
        if (preg_match('/^hsla?\(/i', $value)) {
            return self::hslToHex($value);
        }
        return null;
    }

    /** Chuyển màu từ rgb/rgba về hex */
    public static function rgbToHex(string $rgb): string
    {
        $rgb = str_replace(['rgb(', 'rgba(', ')'], '', $rgb);
        $parts = array_map('trim', explode(',', $rgb));
        return strtoupper(sprintf('%02x%02x%02x', (int) $parts[0], (int) $parts[1], (int) $parts[2]));
    }

    /** Chuyển màu từ hsl/hsla về hex, vd: hsl(0, 75%, 60%) */
    public static function hslToHex(string $hsl): ?string
    {
        if (!preg_match(
            '/hsla?\(\s*([\d.]+)\s*,\s*([\d.]+)%\s*,\s*([\d.]+)%/i',
            $hsl,
            $matches
        )) {
            return null;
        }

        $h = (float) $matches[1] / 360;
        $s = (float) $matches[2] / 100;
        $l = (float) $matches[3] / 100;

        if ($s <= 0) {
            $r = $g = $b = (int) round($l * 255);
            return strtoupper(sprintf('%02x%02x%02x', $r, $g, $b));
        }

        $hue2rgb = static function (float $p, float $q, float $t): float {
            if ($t < 0) {
                $t += 1;
            }
            if ($t > 1) {
                $t -= 1;
            }
            if ($t < 1 / 6) {
                return $p + ($q - $p) * 6 * $t;
            }
            if ($t < 1 / 2) {
                return $q;
            }
            if ($t < 2 / 3) {
                return $p + ($q - $p) * (2 / 3 - $t) * 6;
            }
            return $p;
        };

        $q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
        $p = 2 * $l - $q;

        $r = (int) round($hue2rgb($p, $q, $h + 1 / 3) * 255);
        $g = (int) round($hue2rgb($p, $q, $h) * 255);
        $b = (int) round($hue2rgb($p, $q, $h - 1 / 3) * 255);

        return strtoupper(sprintf('%02x%02x%02x', $r, $g, $b));
    }


    /**
     * hàm gộp các style font
     * @param array<string, mixed> ...$styles
     * */
    public static function mergeFontStyles(array ...$styles): array
    {
        $merged = [];
        foreach ($styles as $style) {
            foreach ($style as $key => $value) {
                $merged[$key] = $value;
            }
        }
        return $merged;
    }

    /**
     * Lấy gộp style từ toàn bộ cây con của DOMElement (đệ quy).
     *
     * @param DOMElement $domElement
     * @param bool $getStyle true → font style từ thẻ inline | false → paragraph style từ thẻ block
     * @return array<string, mixed>
     */
    public static function getStyleChildrenFromDOMElement(DOMElement $domElement, bool $getStyle = true): array
    {
        $style = [];

        foreach ($domElement->childNodes as $child) {
            if (!$child instanceof DOMElement) {
                continue;
            }

            $tag = strtolower($child->tagName);

            if ($getStyle && self::isInlineTag($tag)) {
                $style = self::mergeFontStyles(
                    $style,
                    self::fontStyleFromTag($tag),
                    self::buildFontStyleFromAttribute($child->getAttribute('style'))
                );
            }

            if (!$getStyle && self::isBlockTag($tag)) {
                $style = self::mergeFontStyles(
                    $style,
                    self::buildParagraphStyleFromAttribute($child->getAttribute('style'))
                );
            }

            $style = self::mergeFontStyles(
                $style,
                self::getStyleChildrenFromDOMElement($child, $getStyle)
            );
        }

        return $style;
    }
}
