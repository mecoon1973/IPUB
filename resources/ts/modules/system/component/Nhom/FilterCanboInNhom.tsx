import React, { useCallback, useState } from "react";
import { useManageCanboInNhomStore } from "../../store/Nhom/manageCanboInNhomStore";
import { ModalTreeDonvi } from "../Donvi/ModalTreeDonvi";
import { ModalChooseUser } from "../../../user/components/User/ModalChooseUser";
import type { User } from "../../../user/type/User";
import { NhomApi } from "../../api/NhomApi";


interface FilterCanboInNhomProps {
    handleSearch: () => void;
}

export const FilterCanboInNhom = React.memo((props: FilterCanboInNhomProps) => {
    const { handleSearch } = props;
    const [showModalChooseDonvi, setShowModalChooseDonvi] = useState(false);
    const [showModalChooseUser, setShowModalChooseUser] = useState(false);
    const nhom = useManageCanboInNhomStore((state) => state.nhom);
    const setUsernameSearch = useManageCanboInNhomStore((state) => state.setUsernameSearch);
    const setSelectedDonvi = useManageCanboInNhomStore((state) => state.setSelectedDonvi);
    const setListCanbo = useManageCanboInNhomStore((state) => state.setListCanbo);
    const selectedDonvi = useManageCanboInNhomStore((state) => state.selectedDonvi);
    const listDonvi = useManageCanboInNhomStore((state) => state.listDonvi);
    const usernameSearch = useManageCanboInNhomStore((state) => state.usernameSearch);

    const onShowModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(true);
    }, [setShowModalChooseDonvi]);

    const onHideModalChooseDonvi = useCallback(() => {
        setShowModalChooseDonvi(false);
    }, [setShowModalChooseDonvi]);

    const onShowModalChooseUser = useCallback(() => {
        setShowModalChooseUser(true);
    }, [setShowModalChooseUser]);

    const onHideModalChooseUser = useCallback(() => {
        setShowModalChooseUser(false);
    }, [setShowModalChooseUser]);

    const handleSubmitChooseUser = useCallback((listSelectedUser: User[]) => {
        if(listSelectedUser.length === 0){
            window._toastbox("Vui lòng chọn cán bộ để thêm vào nhóm", "error");
            return;
        }
        NhomApi.addCanboToNhom(nhom?.id ?? 0, listSelectedUser.map((user) => user.id)).then((res) => {
            if(res){
                window._toastbox("Thêm cán bộ vào nhóm thành công", "success");
                setListCanbo((prev: User[]) => [...prev, ...listSelectedUser]);
            }
        }).finally(() => {
            onHideModalChooseUser();
        });
    }, [setListCanbo, nhom, onHideModalChooseUser]);

    return (
        <React.Fragment>
            <div className="d-flex align-items-center justify-content-between gap-2 w-100 flex-nowrap">
                <div className="d-flex align-items-center gap-2">
                    <button
                        className="btn btn-sm btn-primary"
                        onClick={onShowModalChooseUser}
                    >
                        Thêm cán bộ vào nhóm
                    </button>
                </div>
                <div className="d-flex align-items-center gap-2 flex-nowrap">
                    <input
                        type="text"
                        value={selectedDonvi?.TenDonVi ?? ""}
                        style={{ width: "350px" }}
                        className="form-control form-control-sm"
                        placeholder="Chọn đơn vị"
                        readOnly
                        onClick={onShowModalChooseDonvi}
                        />
                    <input
                        type="text"
                        style={{ width: "350px" }}
                        value={usernameSearch}
                        onChange={(e) => setUsernameSearch(e.target.value)}
                        className="form-control form-control-sm"
                        placeholder="Tìm kiếm theo tên đăng nhập"
                    />
                    <button
                        type="button"
                        className="btn btn-sm btn-primary text-nowrap shrink-0"
                        onClick={() => handleSearch()}
                    >
                        Tìm kiếm
                    </button>
                </div>
            </div>
            <ModalTreeDonvi
                show={showModalChooseDonvi}
                onHide={onHideModalChooseDonvi}
                listDonvi={listDonvi}
                usingselectChoose={true}
                handlerChooseDonvi={(donvi) => {
                    setSelectedDonvi(donvi);
                }}
            />
            <ModalChooseUser
                show={showModalChooseUser}
                onHide={onHideModalChooseUser}
                listDonVi={listDonvi}
                handleSubmitChooseUser={handleSubmitChooseUser}
            />
        </React.Fragment>
    );
})
