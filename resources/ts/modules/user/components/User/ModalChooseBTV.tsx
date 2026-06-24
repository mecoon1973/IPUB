import React from "react";
import { Button, Col, Modal, Row, Table } from "antd";
import type { TableProps } from "antd";
import type { User } from "../../type";
import { ComponentSelectAntObject } from "../../../page/component/componentSelectAnt";
import { ComponentIcon } from "../../../page/component/componentIcon";

interface TableBTVComponentProps {
    listBTV: User[];
    listChooseBTV: number[];
    handlerDeleteBTV: (user: User) => void;
}

const TableBTVComponent = React.memo((props: TableBTVComponentProps) => {
    const { listBTV, listChooseBTV, handlerDeleteBTV } = props;
    const dataSource = listChooseBTV
        .map((idBtv) => listBTV.find((user) => Number(user.id) === Number(idBtv)))
        .filter((u): u is User => u != null);

    const columns: TableProps<User>["columns"] = [
        { title: "TT", key: "tt", width: 50, render: (_v, _r, i) => i + 1 },
        { title: "Họ tên", dataIndex: "HoTen", key: "HoTen" },
        { title: "Mã số chứng chỉ", dataIndex: "MaSoChungChi", key: "MaSoChungChi" },
        { title: "Ngày cấp", dataIndex: "NgayCap", key: "NgayCap" },
        { title: "Nơi cấp", dataIndex: "NoiCap", key: "NoiCap" },
        { title: "Chức danh biên tập", dataIndex: "ChucDanhBienTap", key: "ChucDanhBienTap" },
        { title: "Chuyên môn", key: "cm", render: (_, u) => u.chuyenmon?.TenChuyenMon },
        { title: "Tên đơn vị", key: "dv", render: (_, u) => u.donvi?.TenDonVi },
        {
            title: "",
            key: "del",
            width: 72,
            render: (_v, btv) => (
                <Button danger size="small" onClick={() => handlerDeleteBTV(btv)}>
                    <ComponentIcon nameIcon="close-md-svgrepo-com" width={20} height={20} />
                </Button>
            ),
        },
    ];

    return (
        <Table<User>
            rowKey="id"
            columns={columns}
            dataSource={dataSource}
            pagination={false}
            locale={{
                emptyText: "Chọn biên tập viên để thêm vào danh sách",
            }}
        />
    );
});

interface ModalChooseBTVProps {
    show: boolean;
    onHide: () => void;
    listChooseBTV: number[];
    listBTV: User[];
    handlerChooseBTV: (user: User) => void;
    handlerDeleteBTV: (user: User) => void;
}

export const ModalChooseBTVComponent = React.memo((props: ModalChooseBTVProps) => {
    const { show, onHide, listChooseBTV, handlerChooseBTV, handlerDeleteBTV, listBTV } = props;

    return (
        <Modal
            title="CHỌN BIÊN TẬP VIÊN"
            open={show}
            onCancel={onHide}
            footer={null}
            width={1140}
            styles={{ body: { height: "80vh", overflow: "auto" } }}
        >
            <Row className="mb-3 pb-2 border-bottom" gutter={12}>
                <Col xs={24} md={4}>
                    <label className="mb-0 text-nowrap d-block pt-1" htmlFor="select-btv">
                        Biên tập viên
                    </label>
                </Col>
                <Col xs={24} md={20}>
                    <ComponentSelectAntObject
                        styles={{
                            popup: {
                                root: { zIndex: 9999 },
                            },
                        }}
                        style={{ width: "100%" }}
                        listData={listBTV}
                        keyValue="id"
                        labelValue="HoTen"
                        onCustomLabel={(user) => `${user.HoTen} ${user.donvi?.TenDonVi ? `- ${user.donvi?.TenDonVi}` : ""}`}
                        onChange={(id) => {
                            const user = listBTV.find((u) => Number(u.id) === Number(id));
                            if (user) {
                                handlerChooseBTV(user);
                            }
                        }}
                        value=""
                        placeholder="Chọn biên tập viên"
                        showSearch={true}
                        optionFilterProp="label"
                        filterOption={(input, option) => {
                            const label = String(option?.label ?? "").toLowerCase();
                            const q = String(input ?? "").toLowerCase();
                            return label.indexOf(q) >= 0;
                        }}
                    />
                </Col>
            </Row>
            <TableBTVComponent listBTV={listBTV} listChooseBTV={listChooseBTV} handlerDeleteBTV={handlerDeleteBTV} />
        </Modal>
    );
});
