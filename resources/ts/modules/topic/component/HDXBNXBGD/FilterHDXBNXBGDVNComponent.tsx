import React from 'react';
import type { DonVi } from '../../../user/type';
import { ComponentSelectAntObject } from '../../../page/component/componentSelectAnt';
import { useManageHDXBNXBGDVNStore } from '../../store/HDXBNXBGDVN/manageHDXBNXBGDVN';
import type { FilterHDXBNXBGDVN } from '../../type';
import { HDXBNXBGDVN_LOC_THEO_OPTIONS, HDXBNXBGDVN_TRANG_THAI_FILTER_OPTIONS } from '../../constants/hdxbNxbgdvn';

interface FilterHDXBNXBGDVNComponentProps {
    listDonvi: DonVi[];
    getListHDXBNXBGD?: (page?: string, callBack?: () => void) => void;
}

function FilterHDXBNXBGDVNComponent(props: FilterHDXBNXBGDVNComponentProps) {
    const { listDonvi, getListHDXBNXBGD } = props;

    const filter = useManageHDXBNXBGDVNStore((state) => state.filter);
    const setFilter = useManageHDXBNXBGDVNStore((state) => state.setFilter);
    const isLoadingSearch = useManageHDXBNXBGDVNStore((state) => state.isLoadingSearch);

    const updateFilter = <K extends keyof FilterHDXBNXBGDVN>(
        key: K,
        value: FilterHDXBNXBGDVN[K] | undefined,
    ) => {
        setFilter((prev) => ({
            ...prev,
            [key]: value,
        }));
    };

    return (
        <div className="py-2 px-2 border-y bg-light">
            <div className="row g-2 align-items-end">
                <div className="col-md-4">
                    <label className="form-label mb-1 small text-muted">
                        Hỗ trợ tìm kiếm nhiều tên đề tài cách nhau dấu ;
                    </label>
                    <input
                        className="form-control form-control-sm"
                        placeholder="Từ khóa tìm kiếm"
                        value={filter.TenDeTai ?? ""}
                        onChange={(e) => updateFilter('TenDeTai', e.target.value)}
                    />
                </div>

                <div className="col-md-3">
                    <label className="form-label mb-1 small text-muted">Đơn vị tổ chức bản thảo</label>
                    <ComponentSelectAntObject
                        listData={listDonvi}
                        keyValue="id"
                        labelValue="TenDonVi"
                        onChange={(value) => updateFilter('ID_DonVi', value as number)}
                        value={filter.ID_DonVi === 0 ? '' : filter.ID_DonVi}
                        placeholder="Chọn đơn vị"
                        style={{ width: '100%' }}
                        showSearch={true}
                        optionFilterProp="label"
                        filterOption={(input, option) =>
                            option?.label.toLowerCase().indexOf(input.toLowerCase()) >= 0
                        }
                    />
                </div>

                <div className="col-md-2">
                    <label className="form-label mb-1 small text-muted">Lọc theo</label>
                    <select
                        className="form-select form-select-sm"
                        value={filter.PhanCong}
                        onChange={(e) => updateFilter('PhanCong', Number(e.target.value))}
                    >
                        {HDXBNXBGDVN_LOC_THEO_OPTIONS.map((option) => (
                            <option key={option.value} value={option.value}>
                                {option.label}
                            </option>
                        ))}
                    </select>
                </div>

                <div className="col-md-2">
                    <label className="form-label mb-1 small text-muted">Trạng thái</label>
                    <select
                        className="form-select form-select-sm"
                        value={filter.TrangThai}
                        onChange={(e) => updateFilter('TrangThai', Number(e.target.value))}
                    >
                        {HDXBNXBGDVN_TRANG_THAI_FILTER_OPTIONS.map((option) => (
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
                        onClick={() => getListHDXBNXBGD?.()}
                    >
                        Tìm kiếm
                    </button>
                </div>
            </div>
        </div>
    );
}

export default React.memo(FilterHDXBNXBGDVNComponent);
