import React, { useCallback, useState } from "react";
import { Button, Flex, Input, Modal } from "antd";
import type { DonVi } from "../../type/DonVi";
import type { User } from "../../type/User";
import { ModalTreeDonvi } from "../../../system/component/Donvi/ModalTreeDonvi";
import { UserApi } from "../../api/UserApi";
import { TableSelectUser } from "./TableSelectUser";

interface ModalChooseUserProps {
    show: boolean;
    onHide: () => void;
    listDonVi: DonVi[];
    handleSubmitChooseUser: (listSelectedUser: User[]) => void;
}

export const ModalChooseUser = React.memo((props: ModalChooseUserProps) => {
    const { show, onHide, listDonVi, handleSubmitChooseUser } = props;
    const [textSearch, setTextSearch] = useState("");
    const [donviSelected, setDonviSelected] = useState<DonVi | null>(null);
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const [listUser, setListUser] = useState<User[]>([]);
    const [selectUser, setSelectUser] = useState<User[]>([]);

    const onShowModalChooseDonvi = useCallback(() => setShowModalChooseDonvi(true), []);
    const onHideModalChooseDonvi = useCallback(() => setShowModalChooseDonvi(false), []);

    const handleSelectDonvi = useCallback((donvi: DonVi) => {
        setDonviSelected(donvi);
    }, []);

    const handleSearch = useCallback(() => {
        UserApi.getListUser({
            IsDeleted: false,
            textSearch,
            ID_DonVi: donviSelected?.id,
            relations: ["donvi"],
        }).then((res: User[]) => {
            setListUser(res);
        });
    }, [textSearch, donviSelected]);

    return (
        <React.Fragment>
            <Modal
                title="THÊM MỚI CÁN BỘ VÀO NHÓM"
                open={show}
                onCancel={onHide}
                width={1140}
                footer={[
                    <Button key="cancel" onClick={onHide}>
                        Hủy
                    </Button>,
                    <Button
                        key="ok"
                        type="primary"
                        disabled={listUser.length === 0}
                        onClick={() => handleSubmitChooseUser(selectUser)}
                    >
                        Xác nhận
                    </Button>,
                ]}
                styles={{ body: { paddingTop: 12 } }}
            >
                <Flex gap={8} align="center" className="mb-2 border-bottom pb-2" wrap="wrap">
                    <Input
                        allowClear
                        value={textSearch}
                        onChange={(e) => setTextSearch(e.target.value)}
                        placeholder="Tìm kiếm theo tên đăng nhập"
                        className="min-w-0"
                        style={{ flex: 1, minWidth: 160 }}
                    />
                    <Input
                        readOnly
                        value={donviSelected?.TenDonVi ?? ""}
                        placeholder="Chọn đơn vị"
                        onClick={onShowModalChooseDonvi}
                        className="min-w-0"
                        style={{ flex: 1, minWidth: 160, cursor: "pointer" }}
                    />
                    <Button type="primary" onClick={handleSearch}>
                        Tìm kiếm
                    </Button>
                </Flex>
                <div className="max-h-96 overflow-y-auto">
                    {listUser.length > 0 ? (
                        <TableSelectUser listUser={listUser} selectUser={selectUser} setSelectUser={setSelectUser} />
                    ) : (
                        <div className="text-center">
                            <p className="text-muted">Vui lòng chọn đơn vị hoặc tên đăng nhập để tìm thành viên</p>
                        </div>
                    )}
                </div>
            </Modal>
            <ModalTreeDonvi
                show={showModalChooseDonvi}
                onHide={onHideModalChooseDonvi}
                listDonvi={listDonVi}
                handlerChooseDonvi={handleSelectDonvi}
                usingselectChoose={true}
            />
        </React.Fragment>
    );
});
