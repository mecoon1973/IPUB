import React, { useCallback, useState } from "react";
import type { Dayjs } from "dayjs";
import DatePickerAntd from "../../../core/utils/DatePicker";
import { convertValueToDayjs } from "../../../core/utils/helpersDayjs";
import type { QDInFilter } from "../../type";

const { RangePicker } = DatePickerAntd;

interface FilterQDInProps {
    filter: QDInFilter;
    onChangeFilter: (key: keyof QDInFilter, value: QDInFilter[keyof QDInFilter]) => void;
    isLoadingSearch: boolean;
    handleSearch: (page?: string) => void;
}

export const FilterQDIn = React.memo(({ filter, onChangeFilter, isLoadingSearch, handleSearch }: FilterQDInProps) => {
    const { SoQD, ID_DV_QD, startDate, endDate } = filter as QDInFilter;

    return (
        <React.Fragment>
            <div className="py-2 px-2 border-y bg-light">
                <div className="d-flex align-items-center gap-3 flex-wrap mb-2">
                    <a href="/qd-in/cap-nhat" className="btn btn-link text-success text-decoration-none p-0 fw-semibold">
                        + Thêm quyết định in
                    </a>
                </div>

                <div className="row g-2 align-items-end">
                    <div className="col-lg-4 col-md-6">
                        <label className="form-label mb-1 small text-muted">Từ khóa tìm kiếm</label>
                        <input
                            type="text"
                            className="form-control form-control-sm"
                            placeholder="Tìm kiếm theo số QĐ"
                            value={SoQD}
                            onChange={(e) => onChangeFilter("SoQD", e.target.value)}
                        />
                    </div>
                    <div className="col-lg-4 col-md-6">
                        <label className="form-label mb-1 small text-muted">Đơn vị ra quyết định</label>
                        <input
                            type="text"
                            className="form-control form-control-sm"
                            placeholder="Đơn vị ra quyết định"
                            value={ID_DV_QD}
                            onChange={(e) => onChangeFilter("ID_DV_QD", e.target.value)}
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
                            value={[convertValueToDayjs(startDate), convertValueToDayjs(endDate)]}
                            onChange={(dates) => {
                                if (!dates || !dates[0] || !dates[1]) {
                                    onChangeFilter("startDate", null);
                                    onChangeFilter("endDate", null);
                                    return;
                                }
                                onChangeFilter("startDate", dates[0].toDate());
                                onChangeFilter("endDate", dates[1].toDate());
                            }}
                        />
                    </div>
                    <div className="col-lg-auto col-md-12 d-flex justify-content-lg-end justify-content-start">
                        <button
                            type="button"
                            className="btn btn-sm btn-secondary"
                            disabled={isLoadingSearch}
                            onClick={() => handleSearch()}
                        >
                            Tìm kiếm
                        </button>
                    </div>
                </div>
            </div>
        </React.Fragment>
    );
});
