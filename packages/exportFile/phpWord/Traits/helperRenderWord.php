<?php

namespace ExportFile\phpWord\Traits;

use DOMElement;

trait HelperRenderWord {

    /** Lấy tất cả <tr> trong table (kể cả trong tbody/thead) */
    public static function collectTableRows(DOMElement $table): array
    {
        $rows = [];
        foreach ($table->getElementsByTagName('tr') as $tr) {
            if ($tr instanceof DOMElement) {
                $rows[] = $tr;
            }
        }
        return $rows;
    }

    /**
     * Dựng ma trận ô từ các <tr>.
     *
     * HTML rowspan/colspan không tạo <td> ở hàng bên dưới → cần ma trận ảo:
     * - origin: ô gốc có nội dung
     * - continue: ô tiếp tục rowspan (Word: vMerge continue)
     *
     * @param DOMElement[] $trs
     * @return array<int, array<int, array<string, mixed>>>
     */
    public static function buildTableCellMatrix(array $trs): array
    {
        $occupancy = [];
        $matrix = [];

        $isOccupied = static function (int $row, int $col) use (&$occupancy): bool {
            return isset($occupancy["{$row}:{$col}"]);
        };

        $markOccupied = static function (int $row, int $col, int $rowspan, int $colspan) use (&$occupancy): void {
            for ($r = 0; $r < $rowspan; $r++) {
                for ($c = 0; $c < $colspan; $c++) {
                    $occupancy[($row + $r) . ':' . ($col + $c)] = true;
                }
            }
        };

        foreach ($trs as $rowIndex => $tr) {
            $colIndex = 0;
            $isTheadRow = $tr->parentNode instanceof DOMElement
                && strtolower($tr->parentNode->tagName) === 'thead';

            foreach ($tr->childNodes as $cellNode) {
                if (!$cellNode instanceof DOMElement) {
                    continue;
                }
                $tag = strtolower($cellNode->tagName);
                if (!in_array($tag, ['td', 'th'], true)) {
                    continue;
                }

                // Bỏ qua cột đã bị rowspan từ hàng trên chiếm (đã có 'continue' trong matrix)
                while ($isOccupied($rowIndex, $colIndex)) {
                    $colIndex += $matrix[$rowIndex][$colIndex]['colspan'] ?? 1;
                }

                $colspan = max(1, (int) $cellNode->getAttribute('colspan'));
                $rowspan = max(1, (int) $cellNode->getAttribute('rowspan'));

                $scope = strtolower(trim($cellNode->getAttribute('scope')));

                $matrix[$rowIndex][$colIndex] = [
                    'type' => 'origin',
                    'node' => $cellNode,
                    'colspan' => $colspan,
                    'rowspan' => $rowspan,
                    'cellTag' => $tag,
                    'headerScope' => $scope,
                    'isTheadRow' => $isTheadRow,
                ];

                // Đăng ký ô continue cho các hàng bên dưới (rowspan > 1)
                for ($r = 1; $r < $rowspan; $r++) {
                    $matrix[$rowIndex + $r][$colIndex] = [
                        'type' => 'continue',
                        'colspan' => $colspan,
                        'isTheadRow' => $isTheadRow,
                    ];
                }

                $markOccupied($rowIndex, $colIndex, $rowspan, $colspan);
                $colIndex += $colspan;
            }
        }

        // Chuyển ma trận sparse → mảng hàng đã sắp theo cột
        $result = [];
        if ($matrix === []) {
            return $result;
        }

        $maxRow = max(array_keys($matrix));
        for ($r = 0; $r <= $maxRow; $r++) {
            if (!isset($matrix[$r])) {
                continue;
            }
            ksort($matrix[$r]);
            $result[] = array_values($matrix[$r]);
        }

        return $result;
    }

    /**
     * Phân tích cây ol/ul lồng nhau thành danh sách phẳng {value, level}.
     *
     * @return array<int, array{value: DOMElement, level: int}>
     */
    public static function buildDataNumbering(DOMElement $node): array
    {
        $result = [];
        $tag = strtolower($node->tagName);

        if (in_array($tag, ['ol', 'ul'], true)) {
            self::collectListItems($node, 0, $result);
        } else {
            foreach ($node->childNodes as $child) {
                if (!$child instanceof DOMElement) {
                    continue;
                }
                $childTag = strtolower($child->tagName);
                if (in_array($childTag, ['ol', 'ul'], true)) {
                    self::collectListItems($child, 0, $result);
                }
            }
        }

        return $result;
    }

    /**
     * @param array<int, array{value: DOMElement, level: int}> $result
     */
    public static function collectListItems(DOMElement $listNode, int $level, array &$result): void
    {
        foreach ($listNode->childNodes as $child) {
            if (!$child instanceof DOMElement || strtolower($child->tagName) !== 'li') {
                continue;
            }

            $result[] = [
                'value' => $child,
                'level' => $level,
            ];

            foreach ($child->childNodes as $nested) {
                if (!$nested instanceof DOMElement) {
                    continue;
                }
                $nestedTag = strtolower($nested->tagName);
                if (in_array($nestedTag, ['ol', 'ul'], true)) {
                    self::collectListItems($nested, $level + 1, $result);
                }
            }
        }
    }

}
