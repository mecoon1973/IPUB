import React, { useCallback, useEffect, useMemo, useState } from "react";
import { mountReactComponentOnReady } from "../../../core/utils/helpers";
import FilterDonvi from "../../component/Donvi/filterDonvi";
import { useManageDonviStore } from "../../store/Donvi/manageDonviStore";
import { DonviApi } from "../../api/DonviApi";
import type { DonVi } from "../../../user/type/DonVi";
import TreeDonvi from "../../component/Donvi/TreeDonvi";
import { Col, Row } from "antd";
import { useSearchTextDonvi } from "../../hooks/Donvi/useSearchTextDonvi";

function displayText(value: string | number | undefined | null): string {
    const s = value == null ? "" : String(value).trim();
    return s === "" ? "(Chưa có)" : s;
}

function displayBool(value: boolean): string {
    return value ? "Có" : "Không";
}

const InfoDonvi = React.memo(() => {
    const selectedDonvi = useManageDonviStore(state => state.selectedDonvi);
    if (!selectedDonvi) {
        return (
            <div className="text-muted small py-3">
                Chọn một đơn vị trên cây để xem chi tiết.
            </div>
        );
    }

    return (
        <div className="py-2">
            <h5 className="mb-3 border-bottom pb-2">Thông tin đơn vị</h5>
            <dl className="mb-0 small">
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Mã đơn vị:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.MaDonVi)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Tên đơn vị:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.TenDonVi)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Mã số phụ:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.MaSoPhu)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Địa chỉ:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.DiaChi)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Mã số thuế:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.MST)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Nhà in:</dt>{" "}
                    <dd className="d-inline m-0">{displayBool(selectedDonvi.NhaIn)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Đấu thầu:</dt>{" "}
                    <dd className="d-inline m-0">{displayBool(selectedDonvi.DauThau)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Biên tập:</dt>{" "}
                    <dd className="d-inline m-0">{displayBool(selectedDonvi.BienTap)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Liên kết:</dt>{" "}
                    <dd className="d-inline m-0">{displayBool(selectedDonvi.LienKet)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Nội bộ:</dt>{" "}
                    <dd className="d-inline m-0">{displayBool(selectedDonvi.NoiBo)}</dd>
                </div>
                <div className="mb-2">
                    <dt className="d-inline fw-bold">Kí hiệu - Xuất bản mới:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.KiHieuMoi)}</dd>
                </div>
                <div className="mb-0">
                    <dt className="d-inline fw-bold">Kí hiệu - Tái bản:</dt>{" "}
                    <dd className="d-inline m-0">{displayText(selectedDonvi.KiHieuTaiBan)}</dd>
                </div>
            </dl>
        </div>
    );
});

export const ManageDonvi = React.memo(() => {

    const { listDonviFiltered } = useSearchTextDonvi();
    const setListDonvi = useManageDonviStore(state => state.setListDonvi);
    const setSelectedDonvi = useManageDonviStore(state => state.setSelectedDonvi);

    const handleChooseDonvi = useCallback((donvi: DonVi) => {
        setSelectedDonvi(donvi);
    }, [setSelectedDonvi]);

    useEffect(() => {
        DonviApi.getAllDonvi(DonviApi.conditionDefault).then((res) => {
            setListDonvi(res as DonVi[]);
        }).catch((err) => {
            setListDonvi([] as DonVi[]);
        });
    }, []);

    const handleContextAction = useCallback((action: "delete" | "edit" | "add-child", donvi: DonVi) => {
        if (action === "delete") {
            const isConfirmed = window.confirm("Bạn có chắc chắn muốn xóa đơn vị này không?");
            if (!isConfirmed) return;
            DonviApi.delete(donvi.id).then((res) => {
                if (res) {
                    window._toastbox("Xóa đơn vị thành công", "success");
                    setListDonvi((prev: DonVi[]) => prev.filter((item: DonVi) => item.id !== donvi.id));
                }
            }).catch((err) => {
                window._toastbox(err.responseJSON?.message || "Có lỗi xảy ra, vui lòng thử lại", "danger");
            });
            return;
        }
        if (action === "edit") {
            window.location.href = `/he-thong/don-vi/cap-nhat/${donvi.id}`;
            return;
        }
        if (action === "add-child") {
            window.location.href = `/he-thong/don-vi/cap-nhat?parentId=${donvi.id}`;
        }
    }, []);

    return <div className="px-2">
        <FilterDonvi />
        <Row gutter={12}>
            <Col span={14}>
                <TreeDonvi
                    listDonvi={listDonviFiltered || []}
                    handlerChooseDonvi={handleChooseDonvi}
                    onContextAction={handleContextAction}
                />
            </Col>
            <Col span={10}>
                <InfoDonvi />
            </Col>
        </Row>
    </div>
})

mountReactComponentOnReady("root-manage-donvi", <ManageDonvi />);
