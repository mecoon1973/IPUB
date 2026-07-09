import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import { SachApi } from "../../../book/api/SachApi";
import type { FilterSach, Sach } from "../../../book/type/Sach";
import SelectAntd from "../../../core/utils/SelectAntd";
import type { PhieuChuyenBanThaoSach } from "../../type/PhieuChuyenBanThao";

function buildSachLabel(sach: Pick<Sach, "TenSach" | "MaSo"> | PhieuChuyenBanThaoSach): string {
    const ten = (sach.TenSach ?? "").trim();
    const maSo = (sach.MaSo ?? "").trim();
    if (ten && maSo) return `${ten} / ${maSo}`;
    return ten || maSo;
}

function toSachOption(sach: Sach | PhieuChuyenBanThaoSach): Sach {
    return {
        id: sach.id ?? 0,
        TenSach: sach.TenSach ?? "",
        MaSo: sach.MaSo ?? "",
    } as Sach;
}

interface SelectSachKetChuyenProps {
    value: number | null;
    selectedSach?: PhieuChuyenBanThaoSach | null;
    onChoose: (sach: Sach) => void;
    onClear: () => void;
}

export const SelectSachKetChuyen = React.memo(({
    value,
    selectedSach,
    onChoose,
    onClear,
}: SelectSachKetChuyenProps) => {
    const [options, setOptions] = useState<Sach[]>([]);
    const [loading, setLoading] = useState(false);
    const [searchText, setSearchText] = useState("");

    const selectOptions = useMemo(() => {
        const merged = new Map<number, Sach>();
        if (selectedSach?.id) {
            merged.set(selectedSach.id, toSachOption(selectedSach));
        }
        options.forEach((item) => merged.set(item.id, item));

        return Array.from(merged.values()).map((item) => ({
            value: item.id,
            label: buildSachLabel(item),
        }));
    }, [options, selectedSach]);

    useEffect(() => {
        if (!selectedSach?.id) {
            return;
        }
        setOptions((prev) => {
            if (prev.some((item) => item.id === selectedSach.id)) {
                return prev;
            }
            return [toSachOption(selectedSach), ...prev];
        });
    }, [selectedSach]);

    const fetchOptions = useCallback((keyword: string) => {
        const filter: FilterSach = {
            title: keyword.trim(),
            ID_MangSach: 0,
            ID_DonVi: 0,
            NamXuatBan: "",
            NamTaiBan: "",
            HTXB: 0,
            NgayDK: [],
            IsDeleted: false,
            KetChuyenThanhSach: true,
        };

        setLoading(true);
        SachApi.getPaginate(filter, "page-1").then((res) => {
            setOptions(res.listResult ?? []);
        }).finally(() => {
            setLoading(false);
        });
    }, []);

    const fetchOptionsRef = useRef(fetchOptions);
    fetchOptionsRef.current = fetchOptions;

    const debounceTimerRef = useRef<ReturnType<typeof setTimeout> | null>(null);

    useEffect(() => {
        return () => {
            if (debounceTimerRef.current) {
                clearTimeout(debounceTimerRef.current);
            }
        };
    }, []);

    const handleSearch = useCallback((keyword: string) => {
        setSearchText(keyword);
        if (debounceTimerRef.current) {
            clearTimeout(debounceTimerRef.current);
        }
        debounceTimerRef.current = setTimeout(() => {
            fetchOptionsRef.current(keyword);
        }, 300);
    }, []);

    const handleOpenChange = useCallback((open: boolean) => {
        if (open) {
            fetchOptions(searchText);
        }
    }, [fetchOptions, searchText]);

    return (
        <SelectAntd<number>
            size="small"
            className="w-full"
            showSearch
            allowClear
            placeholder="Nhập tên sách hoặc mã số"
            value={value && value > 0 ? value : null}
            options={selectOptions}
            loading={loading}
            filterOption={false}
            onSearch={handleSearch}
            onOpenChange={handleOpenChange}
            optionFilterProp="label"
            onChange={(nextValue) => {
                if (!nextValue) {
                    onClear();
                    return;
                }
                const sach = options.find((item) => item.id === nextValue);
                if (sach) {
                    onChoose(sach);
                }
            }}
        />
    );
});
