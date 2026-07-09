import React from "react";
import DatePickerAntd from "../../../core/utils/DatePicker";
import SelectAntd from "../../../core/utils/SelectAntd";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import type { DonVi } from "../../../user/type";
import { useManagePhieuChuyenBanThaoStore } from "../../store/PhieuChuyenBanThao/managePhieuChuyenBanThaoStore";
import type { FilterPhieuChuyenBanThao } from "../../type";

interface FilterPhieuChuyenBanThaoProps {
    listDonvi: DonVi[];
    onSearch: (page?: string) => void;
}

export const FilterPhieuChuyenBanThaoComponent = React.memo(({
    listDonvi,
    onSearch,
}: FilterPhieuChuyenBanThaoProps) => {
    const filter = useManagePhieuChuyenBanThaoStore((state) => state.filter);
    const setFilter = useManagePhieuChuyenBanThaoStore((state) => state.setFilter);
    const isLoadingSearch = useManagePhieuChuyenBanThaoStore((state) => state.isLoadingSearch);

    const updateFilter = <K extends keyof FilterPhieuChuyenBanThao>(
        key: K,
        value: FilterPhieuChuyenBanThao[K],
    ) => {
        setFilter((prev) => ({ ...prev, [key]: value }));
    };

    return (
        <div className="py-2 px-2 border-y bg-light mb-2">
            <div className="d-flex align-items-end justify-content-between gap-3 flex-wrap">
                <a
                    href="/phieu-chuyen-ban-thao/cap-nhat"
                    className="btn btn-link text-success text-decoration-none p-0 fw-semibold"
                >
                    + Tạo phiếu
                </a>

                <div className="d-flex align-items-end gap-2 flex-wrap">
                    <div>
                        <label className="form-label mb-1 small text-muted">Từ ngày</label>
                        <DatePickerAntd
                            style={{ width: 140 }}
                            size="small"
                            format="DD/MM/YYYY"
                            placeholder="Từ ngày"
                            allowClear
                            value={convertValueToDayjs(filter.startDate)}
                            onChange={(date) => updateFilter("startDate", date ? date.toDate() : null)}
                        />
                    </div>
                    <div>
                        <label className="form-label mb-1 small text-muted">Đến ngày</label>
                        <DatePickerAntd
                            style={{ width: 140 }}
                            size="small"
                            format="DD/MM/YYYY"
                            placeholder="Đến ngày"
                            allowClear
                            value={convertValueToDayjs(filter.endDate)}
                            onChange={(date) => updateFilter("endDate", date ? date.toDate() : null)}
                        />
                    </div>
                    <div style={{ minWidth: 220 }}>
                        <label className="form-label mb-1 small text-muted">Đơn vị giao</label>
                        <SelectAntd<number>
                            className="w-100"
                            size="small"
                            allowClear
                            showSearch
                            placeholder="Đơn vị giao"
                            value={filter.ID_DV && filter.ID_DV > 0 ? filter.ID_DV : null}
                            options={listDonvi.map((donvi) => ({
                                value: donvi.id,
                                label: donvi.TenDonVi,
                            }))}
                            onChange={(value) => updateFilter("ID_DV", value ?? null)}
                            optionFilterProp="label"
                        />
                    </div>
                    <div>
                        <label className="form-label mb-1 small text-muted">Từ khóa</label>
                        <input
                            type="text"
                            className="form-control form-control-sm"
                            style={{ width: 180 }}
                            placeholder="Từ khóa"
                            value={filter.TuKhoa ?? ""}
                            onChange={(e) => updateFilter("TuKhoa", e.target.value)}
                            onKeyDown={(e) => {
                                if (e.key === "Enter") onSearch();
                            }}
                        />
                    </div>
                    <button
                        type="button"
                        className="btn btn-sm btn-secondary"
                        disabled={isLoadingSearch}
                        onClick={() => onSearch()}
                    >
                        Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    );
});
