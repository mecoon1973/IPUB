import React from "react";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { useManagePhieuDkKhxbCxbStore } from "../../store/PhieuDkKhxbCxb/managePhieuDkKhxbCxbStore";
import type { FilterPhieuDkKhxbCxb } from "../../type";

const { RangePicker } = DatePickerAntd;

interface FilterPhieuDkKhxbCxbProps {
    onSearch: (page?: string) => void;
}

export const FilterPhieuDkKhxbCxbComponent = React.memo(({ onSearch }: FilterPhieuDkKhxbCxbProps) => {
    const filter = useManagePhieuDkKhxbCxbStore((state) => state.filter);
    const setFilter = useManagePhieuDkKhxbCxbStore((state) => state.setFilter);
    const isLoadingSearch = useManagePhieuDkKhxbCxbStore((state) => state.isLoadingSearch);

    const updateFilter = <K extends keyof FilterPhieuDkKhxbCxb>(key: K, value: FilterPhieuDkKhxbCxb[K]) => {
        setFilter((prev) => ({ ...prev, [key]: value }));
    };

    return (
        <div className="py-2 px-2 border-y bg-light">
            <div className="d-flex align-items-center gap-3 flex-wrap mb-2">
                <a href="/phieu-dk-khxb-cxb/cap-nhat" className="btn btn-link text-success text-decoration-none p-0 fw-semibold">
                    + Tạo phiếu
                </a>
            </div>

            <div className="row g-2 align-items-end">
                <div className="col-lg-4 col-md-6">
                    <label className="form-label mb-1 small text-muted">Từ khóa tìm kiếm</label>
                    <input
                        type="text"
                        className="form-control form-control-sm"
                        placeholder="Nhập vào mã phiếu, tiêu đề, mã số sách hoặc tên sách"
                        value={filter.TuKhoa ?? ""}
                        onChange={(e) => updateFilter("TuKhoa", e.target.value)}
                    />
                </div>
                <div className="col-lg-3 col-md-6">
                    <label className="form-label mb-1 small text-muted">Từ ngày — Đến ngày</label>
                    <RangePicker
                        style={{ width: "100%" }}
                        size="small"
                        format="DD/MM/YYYY"
                        placeholder={["Từ ngày", "Đến ngày"]}
                        allowClear
                        value={[convertValueToDayjs(filter.startDate), convertValueToDayjs(filter.endDate)]}
                        onChange={(dates) => {
                            if (!dates || !dates[0] || !dates[1]) {
                                updateFilter("startDate", null);
                                updateFilter("endDate", null);
                                return;
                            }
                            updateFilter("startDate", dates[0].toDate());
                            updateFilter("endDate", dates[1].toDate());
                        }}
                    />
                </div>
                <div className="col-lg-auto col-md-12 d-flex justify-content-lg-end justify-content-start">
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
