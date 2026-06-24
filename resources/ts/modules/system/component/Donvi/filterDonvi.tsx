import React, { useCallback, useState } from "react";
import { useManageDonviStore } from "../../store/Donvi/manageDonviStore";
import { DonviApi } from "../../api/DonviApi";
import { useSearchTextDonvi } from "../../hooks/Donvi/useSearchTextDonvi";

const FilterDonvi = React.memo(() => {
    const filterNoiBo = useManageDonviStore(state => state.filterNoiBo);
    const filterNhaIn = useManageDonviStore(state => state.filterNhaIn);
    const filterLienKet = useManageDonviStore(state => state.filterLienKet);
    const setFilterNoiBo = useManageDonviStore(state => state.setFilterNoiBo);
    const setFilterNhaIn = useManageDonviStore(state => state.setFilterNhaIn);
    const setFilterLienKet = useManageDonviStore(state => state.setFilterLienKet);
    const setListDonvi = useManageDonviStore(state => state.setListDonvi);
    const setSelectedDonvi = useManageDonviStore(state => state.setSelectedDonvi);
    const { textSearch, handleChangeTextSearch } = useSearchTextDonvi();
    const [disabled, setDisabled] = useState(false);

    const handleSearch = useCallback(() => {
        setDisabled(true);
        window._toastbox("Đang tải dữ liệu, vui lòng chờ...", "info");
        DonviApi.getAllDonvi({ NhaIn : filterNhaIn, NoiBo: filterNoiBo, LienKet: filterLienKet }).then((res) => {
            setListDonvi(res);
            setSelectedDonvi(null);
            window._toastbox("Tải dữ liệu thành công", "success");
        }).catch((err) => {
            window._toastbox("Tải dữ liệu thất bại", "error");
        }).finally(() => {
            setDisabled(false);
        });
    }, [filterNoiBo, filterNhaIn, filterLienKet]);


    return (
        <div className="py-2 px-2 border-bottom">
            <div className="mb-2">
                <a href="/he-thong/don-vi/cap-nhat" className="btn btn-link text-success text-decoration-none border p-0 fw-semibold">
                    + Thêm đơn vị
                </a>
            </div>
            <div className="d-flex align-items-end gap-2 flex-wrap">
                <div style={{ minWidth: 320, flex: 1 }}>
                    <label className="form-label mb-1 small text-muted">Từ khóa tìm kiếm</label>
                    <input
                        type="text"
                        className="form-control form-control-sm"
                        placeholder="Tìm kiếm theo tên đơn vị"
                        value={textSearch}
                        onChange={handleChangeTextSearch}
                    />
                </div>

                <div className="form-check mb-1">
                    <input className="form-check-input" type="checkbox" id="cbNoiBo" defaultChecked={filterNoiBo} onChange={() => setFilterNoiBo(!filterNoiBo)} />
                    <label className="form-check-label small" htmlFor="cbNoiBo">
                        Nội bộ
                    </label>
                </div>

                <div className="form-check mb-1">
                    <input className="form-check-input" type="checkbox" id="cbNhaIn" defaultChecked={filterNhaIn} onChange={() => setFilterNhaIn(!filterNhaIn)} />
                    <label className="form-check-label small" htmlFor="cbNhaIn">
                        Nhà in
                    </label>
                </div>

                <div className="form-check mb-1">
                    <input className="form-check-input" type="checkbox" id="cbLienKet" defaultChecked={filterLienKet} onChange={() => setFilterLienKet(!filterLienKet)} />
                    <label className="form-check-label small" htmlFor="cbLienKet">
                        Liên kết
                    </label>
                </div>

                <button
                    type="button"
                    className="btn btn-sm btn-outline-secondary mb-1 px-3"
                    onClick={handleSearch}
                    disabled={disabled}
                >
                    Tìm kiếm
                </button>
            </div>
        </div>
    );
});

export default FilterDonvi;
