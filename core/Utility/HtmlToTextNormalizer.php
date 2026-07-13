<?php

namespace Core\Utility;

use DOMDocument;
use DOMElement;
use DOMNode;

/**
 * Chuyển HTML thành plain text: decode entity, bỏ thẻ, giữ xuống dòng, ol/ul.
 */
class HtmlToTextNormalizer
{
    public static function toString(string $html): string
    {
        $html = trim($html);
        if ($html === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $dom->loadHTML(
            '<?xml encoding="utf-8" ?><div>' . $html . '</div>',
            LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        $wrapper = $dom->documentElement;
        if (!$wrapper instanceof DOMElement) {
            return self::cleanText(strip_tags($html));
        }

        $instance = new self();
        $lines = [];

        foreach ($wrapper->childNodes as $child) {
            $instance->collectLines($child, $lines);
        }

        $normalizedLines = array_map(
            static fn (string $line) => rtrim($line),
            $lines
        );

        return rtrim(implode("\n", $normalizedLines));
    }

    /**
     * @param array<int, string> $lines
     */
    private function collectLines(DOMNode $node, array &$lines): void
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            $text = self::cleanText($node->textContent);
            if ($text !== '') {
                $lines[] = $text;
            }

            return;
        }

        if (!($node instanceof DOMElement)) {
            return;
        }

        $tag = strtolower($node->tagName);

        switch ($tag) {
            case 'ul':
                foreach (self::directChildren($node, 'li') as $li) {
                    $text = self::inlineText($li, false);
                    $text = ltrim($text, "\n\r\t");
                    $lines[] = '·' . $text;
                }
                break;

            case 'ol':
                $counter = 1;
                foreach (self::directChildren($node, 'li') as $li) {
                    if ($li->hasAttribute('value')) {
                        $counter = max(1, (int) $li->getAttribute('value'));
                    }

                    $text = self::inlineText($li);
                    $lines[] = $counter . '. ' . $text;
                    $counter++;
                }
                break;

            case 'p':
            case 'div':
            case 'h1':
            case 'h2':
            case 'h3':
            case 'h4':
            case 'h5':
            case 'h6':
                $lines[] = self::inlineText($node, false);
                break;

            case 'br':
                $lines[] = '';
                break;

            case 'body':
            case 'html':
                foreach ($node->childNodes as $child) {
                    $this->collectLines($child, $lines);
                }
                break;

            default:
                foreach ($node->childNodes as $child) {
                    $this->collectLines($child, $lines);
                }
                break;
        }
    }

    private static function inlineText(DOMNode $node, bool $trimEdges = true): string
    {
        if ($node->nodeType === XML_TEXT_NODE) {
            return self::cleanText($node->textContent, $trimEdges);
        }

        if (!($node instanceof DOMElement)) {
            return '';
        }

        $tag = strtolower($node->tagName);
        if ($tag === 'br') {
            return "\n";
        }

        $text = '';
        foreach ($node->childNodes as $child) {
            $text .= self::inlineText($child, false);
        }

        return self::cleanText($text, $trimEdges);
    }

    private static function cleanText(string $text, bool $trimEdges = true): string
    {
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = str_replace(["\xc2\xa0", '&nbsp;'], ' ', $text);
        $text = preg_replace('/[ \t\n\r]+/u', ' ', $text) ?? $text;

        return $trimEdges ? trim($text) : rtrim($text);
    }

    /**
     * @return array<int, DOMElement>
     */
    private static function directChildren(DOMElement $parent, string $tagName): array
    {
        $children = [];
        $tagName = strtolower($tagName);

        foreach ($parent->childNodes as $child) {
            if ($child instanceof DOMElement && strtolower($child->tagName) === $tagName) {
                $children[] = $child;
            }
        }

        return $children;
    }
}
