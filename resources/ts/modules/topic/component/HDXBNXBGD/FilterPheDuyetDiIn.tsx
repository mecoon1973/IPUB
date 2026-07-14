import React from "react";
import type { DonVi } from "../../../user/type";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import { usePheDuyetDiInStore } from "../../store/HDXBNXBGDVN/pheDuyetDiInStore";
import type { FilterPheDuyetDiIn } from "../../type";
import {
    PHE_DUYET_DI_IN_LOC_THEO_OPTIONS,
    PHE_DUYET_DI_IN_TRANG_THAI_OPTIONS,
} from "../../constants/hdxbNxbgdvn";

interface FilterPheDuyetDiInProps {
    listDonvi: DonVi[];
    onSearch?: (page?: string) => void;
}

function FilterPheDuyetDiInComponent({ listDonvi, onSearch }: FilterPheDuyetDiInProps) {
    const filter = usePheDuyetDiInStore((state) => state.filter);
    const setFilter = usePheDuyetDiInStore((state) => state.setFilter);
    const isLoadingSearch = usePheDuyetDiInStore((state) => state.isLoadingSearch);

    const updateFilter = <K extends keyof FilterPheDuyetDiIn>(
        key: K,
        value: FilterPheDuyetDiIn[K] | undefined,
    ) => {
        setFilter((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    return (
        <div className="py-2 px-2 border-y bg-light">
            <div className="row g-2 align-items-end">
                <div className="col-md-3">
                    <label className="form-label mb-1 small text-muted">Tìm kiếm theo tên sách</label>
                    <input
                        className="form-control form-control-sm"
                        placeholder="Từ khóa tìm kiếm"
                        value={filter.TenSach ?? ""}
                        onChange={(e) => updateFilter("TenSach", e.target.value)}
                    />
                </div>

                <div className="col-md-3">
                    <label className="form-label mb-1 small text-muted">
                        Hỗ trợ tìm kiếm nhiều sách theo mã sách cách nhau dấu ;
                    </label>
                    <input
                        className="form-control form-control-sm"
                        placeholder="Mã sách"
                        value={filter.MaSo ?? ""}
                        onChange={(e) => updateFilter("MaSo", e.target.value)}
                    />
                </div>

                <div className="col-md-1">
                    <label className="form-label mb-1 small text-muted">Năm XB/TB</label>
                    <input
                        className="form-control form-control-sm"
                        placeholder="Năm"
                        value={filter.NamXBTB ?? ""}
                        onChange={(e) => updateFilter("NamXBTB", e.target.value)}
                    />
                </div>

                <div className="col-md-2">
                    <label className="form-label mb-1 small text-muted">Đơn vị tổ chức bản thảo</label>
                    <ComponentSelectAntObject
                        listData={listDonvi}
                        keyValue="id"
                        labelValue="TenDonVi"
                        onChange={(value) => updateFilter("ID_DonVi", value as number)}
                        value={filter.ID_DonVi === 0 ? "" : filter.ID_DonVi}
                        placeholder="Chọn đơn vị"
                        style={{ width: "100%" }}
                        showSearch
                        optionFilterProp="label"
                        filterOption={(input, option) =>
                            option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                        }
                    />
                </div>

                <div className="col-md-1">
                    <label className="form-label mb-1 small text-muted">Lọc theo</label>
                    <select
                        className="form-select form-select-sm"
                        value={filter.LocTheo}
                        onChange={(e) => updateFilter("LocTheo", Number(e.target.value))}
                    >
                        {PHE_DUYET_DI_IN_LOC_THEO_OPTIONS.map((option) => (
                            <option key={option.value} value={option.value}>
                                {option.label}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="col-md-1">
                    <label className="form-label mb-1 small text-muted">Trạng thái</label>
                    <select
                        className="form-select form-select-sm"
                        value={filter.TrangThai}
                        onChange={(e) => updateFilter("TrangThai", Number(e.target.value))}
                    >
                        {PHE_DUYET_DI_IN_TRANG_THAI_OPTIONS.map((option) => (
                            <option key={option.value} value={option.value}>
                                {option.label}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="col-md-1">
                    <button
                        type="button"
                        className="btn btn-sm btn-secondary w-100"
                        disabled={isLoadingSearch}
                        onClick={() => onSearch?.()}
                    >
                        Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    );
}

export default React.memo(FilterPheDuyetDiInComponent);
