import { Button, Modal, Table, type TableProps } from "antd";
import React from "react";

interface ModalCreateSupplementaryReleaseDecisionProps {
    open: boolean;
    onCancel: () => void;
}

export const ModalCreateSupplementaryReleaseDecision = React.memo((props: ModalCreateSupplementaryReleaseDecisionProps) => {
    const { open, onCancel } = props;

    const columns: TableProps<any>["columns"] = [
        {
            title: "STT",
            key: "stt",
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
            title: "Tác giả",
            key: "tacgia",
            render: (_v, _r, i) => i + 1,
        },
        {
            title: "",
            key: "action",
            render: (_v, _r, i) => <Button type="dashed" onClick={onCancel}>
                Tạo QĐPH bổ xung
            </Button>,
        },
    ];

    return <Modal title="Tạo quyết định bổ sung" open={open} onCancel={onCancel} width={1140} footer={[

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
