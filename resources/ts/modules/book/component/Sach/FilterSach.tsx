import React, { useCallback, useState } from "react";
import DatePicker from "../../../core/utils/DatePicker";
import { ModalTree } from "../../../page/component/componentModalTree";
import { useDataViewStore } from "../../../system/store/useDataViewStore";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import { useManageSachStore } from "../../store/useManageSachStore";
import type { FilterSach } from "../../type";

interface FilterSachProps {
    getListSach: (page?: string) => void;
}
export const FilterSachComponent = React.memo((props: FilterSachProps) => {
    const { getListSach } = props;
    const { RangePicker } = DatePicker;
    const setFilter = useManageSachStore((state) => state.setFilterSach);
    const filter = useManageSachStore((state) => state.filterSach);
    const listDonvi = useDataViewStore((state) => state.listDonvi);
    const listMangsach = useDataViewStore((state) => state.listMangsach);
    const resetFilter = useManageSachStore((state) => state.resetFilter);
    const isLoadingSearch = useManageSachStore((state) => state.isLoadingSearch);

    const [showModalChooseMangsach, setShowModalChooseMangsach] = useState(false);
    const onShowModalChooseMangsach = useCallback(() => {
        setShowModalChooseMangsach(true);
    }, []);
    const onHideModalChooseMangsach = useCallback(() => {
        setShowModalChooseMangsach(false);
    }, []);

    const updateFilter = <K extends keyof FilterSach>(
        key: K,
        value: FilterSach[K] | undefined,
    ) => {
        setFilter((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    const handleResetFilter = useCallback(() => {
        resetFilter();
        getListSach();
    }, [resetFilter, getListSach]);
    return <React.Fragment>
    <div className="py-2 px-2 border-y bg-light">
        <div className="row g-2 align-items-end">
            <div className="col-md-6">
                <label className="form-label mb-1 small text-muted">Từ khóa tìm kiếm (mã dạng không cần nhập đủ, tên sách, tác giả hoặc biên tập viên)</label>
                <input
                    className="form-control form-control-sm"
                    placeholder="Từ khóa tìm kiếm"
                    value={filter.title ?? ""}
                    onChange={(e) => {
                        updateFilter("title", e.target.value);
                    }}
                />
            </div>
            <div className="col-md-2">
                <label className="form-label mb-1 small text-muted">Năm XB/TB</label>
                <input
                    className="form-control form-control-sm"
                    placeholder="Năm XB/TB"
                    value={filter.NamXuatBan ?? ""}
                    onChange={(e) => {
                        updateFilter("NamXuatBan", e.target.value)
                        updateFilter("NamTaiBan", e.target.value)
                    }
                    }
                />
            </div>
            <div className="col-md-3">
                <label className="form-label mb-1 small text-muted">Mảng sách</label>
                <input
                    className="form-control form-control-sm"
                    placeholder="Nhập mảng sách"
                    value={filter.ID_MangSach ? listMangsach.find((m) => m.id === filter.ID_MangSach)?.TenMang ?? "" : ""}
                    readOnly
                    onClick={onShowModalChooseMangsach}
                />
            </div>
            <div className="col-md-1">
                <label className="form-label mb-1 small text-muted">HXB</label>
                <select
                    className="form-select form-select-sm"
                    value={filter.HTXB}
                    onChange={(e) => updateFilter("HTXB", Number(e.target.value))}
                >
                    <option value="-1">-- Tất cả --</option>
                    <option value="1">Mới</option>
                    <option value="0">Tái bản</option>
                </select>
            </div>
        </div>

        <div className="row g-2 align-items-end mt-0">
            <div className="col-md-3">
                <div className="mb-1">
                    <label className="form-check-label small text-muted" htmlFor="timKiemNgayDangKy">
                        Tìm kiếm theo ngày đăng ký
                    </label>
                </div>
                <div className="d-flex gap-2">
                    <RangePicker
                        style={{ width: "100%" }}
                        placeholder={["Từ ngày", "Đến ngày"]}
                        value={
                            filter.NgayDK?.[0] != null && filter.NgayDK?.[1] != null
                                ? [
                                      convertValueToDayjs(filter.NgayDK[0]),
                                      convertValueToDayjs(filter.NgayDK[1]),
                                  ]
                                : null
                        }
                        onChange={(dates) => {
                            if (!dates?.[0] || !dates[1]) {
                                updateFilter("NgayDK", undefined);
                                return;
                            }
                            updateFilter("NgayDK", [dates[0].toDate(), dates[1].toDate()]);
                        }}
                        />
                </div>
            </div>
            <div className="col-md-3">
                <label className="form-label mb-1 small text-muted">Đơn vị</label>
                <ComponentSelectAntObject
                    listData={listDonvi}
                    keyValue="id"
                    labelValue="TenDonVi"
                    onChange={(value) => updateFilter("ID_DonVi", value as number)}
                    value={filter.ID_DonVi == 0 ? "" : filter.ID_DonVi}
                    placeholder="Chọn đơn vị"
                    style={{ width: "100%" }}
                    showSearch={true}
                    optionFilterProp="label"
                    filterOption={(input, option) =>
                        option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                    }
                />
            </div>
            <div className="col-md-3">
                <label className="form-label mb-1 small text-muted">Chức năng</label>
                <div className="d-flex gap-2">
                    <button
                        disabled={isLoadingSearch}
                        type="button"
                        className="btn btn-sm btn-secondary"
                        onClick={() => getListSach()}
                    >
                        Tìm kiếm
                    </button>
                    <button
                        disabled={isLoadingSearch}
                        type="button"
                        className="btn btn-sm btn-outline-secondary"
                        onClick={handleResetFilter}
                    >
                        Tải lại
                    </button>
                </div>
            </div>
        </div>
    </div>
    <ModalTree
        title="Danh sách mảng sách"
        show={showModalChooseMangsach}
        onHide={onHideModalChooseMangsach}
        listData={listMangsach}
        handlerChoose={(mangsach) => updateFilter("ID_MangSach", mangsach.id)}
        getLabel={(mangsach) => mangsach.TenMang}
        usingselectChoose={true}
    />
</React.Fragment>;
});
