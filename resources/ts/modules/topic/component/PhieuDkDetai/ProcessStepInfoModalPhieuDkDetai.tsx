import React, { useEffect, useState } from "react";
import { Button, Col, Modal, Row, Table, type TableProps } from "antd";
import type { Detai_Congdoan } from "../../type";
import { useManagePhieuDkDetaiStore } from "../../store/PhieuDkDetai/managePhieuDkDetaiStore";
import { formatDateToString } from "../../../core/utils/helpersDayjs";
import { DetaiCongDoanApi } from "../../api/DetaiCongDoanApi";

const TableInfo = () => {
    const listDetaiCongdoan = useManagePhieuDkDetaiStore((state) => state.listDetaiCongdoan);
    const columns: TableProps<Detai_Congdoan>["columns"] = [
        { title: "Mã công đoạn", key: "stt", width: 56, render: (_, detai_condoan, index) => detai_condoan.MaCD },
        { title: "Tên công đoạn", key: "stt", width: 56, render: (_, detai_condoan, index) => detai_condoan.NoiDung },
        { title: "Thời điểm", key: "stt", width: 56, render: (_, detai_condoan, index) => formatDateToString(detai_condoan.CreatedOn) },
        { title: "Tên người tạo", key: "stt", width: 56, render: (_, detai_condoan, index) => detai_condoan.user_create?.HoTen },
    ];
    return (
        <Row gutter={[8, 8]}>
            <Col span={24}>
                <Table<Detai_Congdoan>
                    rowKey={(r, i) => String(r.id ?? i)}
                    columns={columns}
                    dataSource={listDetaiCongdoan}
                    pagination={false}
                    size="small"
                    className="text-sm"
                />
            </Col>
        </Row>
    );
};

interface ProcessStepInfoModalProps {

}

export const ProcessStepInfoModalComponent = React.memo(({  }: ProcessStepInfoModalProps) => {

    const PhieuDkDetaiContext = useManagePhieuDkDetaiStore((state) => state.PhieuDkDetaiContext);
    const showProcessStepInfoModal = useManagePhieuDkDetaiStore((state) => state.showProcessStepInfoModal);
    const setShowProcessStepInfoModal = useManagePhieuDkDetaiStore((state) => state.setShowProcessStepInfoModal);
    const setListDetaiCongdoan = useManagePhieuDkDetaiStore((state) => state.setListDetaiCongdoan);
    useEffect(() => {
        if (PhieuDkDetaiContext && showProcessStepInfoModal) {
            DetaiCongDoanApi.getList({ IDDeTai: PhieuDkDetaiContext.id, relations: ["user_create"] }).then((res: Detai_Congdoan[]) => {
                setListDetaiCongdoan(res);
            });
        }
    }, [PhieuDkDetaiContext, showProcessStepInfoModal]);

    return (
        <Modal
            title="THÔNG TIN CÔNG ĐOẠN CỦA ĐỀ TÀI"
            open={showProcessStepInfoModal}
            onCancel={() => setShowProcessStepInfoModal(false)}
            width={1140}
            footer={[
                <Button key="close" onClick={() => setShowProcessStepInfoModal(false)}>
                    Đóng
                </Button>,
            ]}
            styles={{ body: { maxHeight: "70vh" } }}
        >
            <Row gutter={[8, 8]}>
                <Col span={24} className="mx-2 border-b">
                    <div>
                        <span className="fw-semibold">Mã sách: {PhieuDkDetaiContext?.MaSo}</span>
                    </div>
                    <div>
                        <span className="fw-semibold">Tên sách: {PhieuDkDetaiContext?.TenDeTai}</span>
                    </div>
                    <div>
                        <span className="fw-semibold">Tên tác giả: {PhieuDkDetaiContext?.TacGia}</span>
                    </div>
                    <div>
                        <span className="fw-semibold">Năm sản xuất: {PhieuDkDetaiContext?.NamXuatBan}</span>
                    </div>
                </Col>
            </Row>
            <TableInfo />
        </Modal>
    );
});
