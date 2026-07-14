<?php

namespace Modules\Topic\Object;

use Core\Object\BaseObject;
use MongoDB\BSON\Regex;

/**
 * Filter danh mục sách (ipub_dm_sach) cho màn Phê duyệt đi in.
 *
 * @property ?string $TenSach
 * @property ?string $MaSo
 * @property ?string $NamXBTB
 * @property ?int $ID_DonVi
 * @property ?int $LocTheo
 * @property ?int $TrangThai
 * @property ?int[] $idsDeTai
 */
class FilterPheDuyetDiIn extends BaseObject
{
    public ?string $TenSach = null;
    public ?string $MaSo = null;
    public ?string $NamXBTB = null;
    public ?int $ID_DonVi = null;
    /** -1 tất cả, 1 được phân công đọc duyệt */
    public ?int $LocTheo = null;
    /** TrangThaiDocBanThao: -1 tất cả, 0/1/2 */
    public ?int $TrangThai = null;
    /** @var int[]|null Lọc theo ID_DeTai trên bản ghi sách */
    public ?array $idsDeTai = null;

    public function __construct($input = [])
    {
        parent::__construct($input);
    }

    public function buildConditions(): array
    {
        $and = [];

        $base = [
            'IsDeleted' => false,
        ];

        if ($this->TrangThai !== null && $this->TrangThai !== -1) {
            $base['TrangThaiDocBanThao'] = (int) $this->TrangThai;
        }

        $and[] = $base;

        if (isset($this->ID_DonVi) && $this->ID_DonVi !== 0) {
            $and[] = ['ID_DonVi' => (int) $this->ID_DonVi];
        }

        if (is_array($this->idsDeTai) && count($this->idsDeTai) > 0) {
            $and[] = ['ID_DeTai' => ['$in' => array_map('intval', $this->idsDeTai)]];
        }

        $tenSachOr = $this->buildKeywordOrConditions('TenSach', $this->TenSach);
        if (count($tenSachOr) > 0) {
            $and[] = ['$or' => $tenSachOr];
        }

        $maSoOr = $this->buildKeywordOrConditions('MaSo', $this->MaSo);
        if (count($maSoOr) > 0) {
            $and[] = ['$or' => $maSoOr];
        }

        if (isset($this->NamXBTB) && trim($this->NamXBTB) !== '') {
            $nam = trim($this->NamXBTB);
            $and[] = [
                '$or' => [
                    ['NamTaiBan' => $nam],
                    ['NamXuatBan' => $nam],
                ],
            ];
        }

        if (count($and) === 1) {
            return $and[0];
        }

        return ['$and' => $and];
    }

    private function buildKeywordOrConditions(string $field, ?string $value): array
    {
        if (!isset($value) || trim($value) === '') {
            return [];
        }

        $keywords = array_filter(array_map('trim', explode(';', $value)));
        $conditionsOr = [];

        foreach ($keywords as $keyword) {
            if ($keyword === '') {
                continue;
            }
            $conditionsOr[] = [
                $field => ['$regex' => new Regex(preg_quote($keyword, '/'), 'ui')],
            ];
        }

        return $conditionsOr;
    }
}
