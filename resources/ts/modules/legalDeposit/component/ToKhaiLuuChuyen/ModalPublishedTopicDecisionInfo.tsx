import { Button, Table, type TableProps } from "antd"

import { Modal } from "antd"
import React from "react"

interface ModalPublishedTopicDecisionInfoProps {
    open: boolean;
    onCancel: () => void;
}

export const ModalPublishedTopicDecisionInfo = React.memo((props: ModalPublishedTopicDecisionInfoProps) => {
    const { open, onCancel } = props;

    const columns: TableProps<any>["columns"] = [
        {
            title: "STT",
            key: "stt",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Số QĐXB",
            key: "soQDXB",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Ngày cấp",
            key: "Ngaycap",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Mã số sách",
            key: "masosach",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Tên sách",
            key: "tensach",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "SL in",
            key: "slin",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Đơn vị được cấp",
            key: "donvicap",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Số tờ khai",
            key: "sotokhai",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Ngày XN",
            key: "Ngaycap",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "Trạng thái ký số",
            key: "trangthaikyso",
            render: (_v, _r, i) => i + 1,
        },
    ];

    return <Modal title="Thông tin đề tài đã cấp quyết định xuất bản" open={open} onCancel={onCancel} width={1140} footer={[
        <Button type="default" key="create-decision-info" onClick={onCancel}>
            Tạo QĐPH bổ xung
        </Button>,
        <Button type="default" key="close" onClick={onCancel}>
            Đóng
        </Button>,
    ]} styles={{ body: { maxHeight: "70vh", overflowY: "auto" } }}>
        <Table
            columns={columns}
            dataSource={[]}
        />
    </Modal>;
});
