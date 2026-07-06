import { mountReactComponentOnReady } from "../../../core/utils/helpers";
import { ComponentPagination } from "../../../page/component/pagination";
import { defaultPagiInfo, type PagiInfo } from "../../../page/type";
import { UserApi } from "../../api/UserApi";
import type { User } from "../../type/User";
import FilterUser from "../../components/User/FilterUser";
import { TableUser } from "../../components/User/TableUser";
import { useCallback, useEffect, useState } from "react";

export default function ManageUserView() {
    const [pagiInfo, setPagiInfo] = useState<PagiInfo>(defaultPagiInfo);
    const [listUser, setListUser] = useState<User[]>([]);

    const getPaginateUser = useCallback((page?: string) => {
        UserApi.getPaginateUser({}, page).then((res: { listResult: User[]; pagiInfo: PagiInfo }) => {
            setPagiInfo(res.pagiInfo);
            setListUser(res.listResult);
        });
    }, []);

    const handleDeleteUser = useCallback((id: number) => {
        UserApi.delete(id).then((res: boolean) => {
            if (res) {
                window._toastbox("Người dùng đã được xóa", "success");
                setListUser((prev: User[]) => prev.filter((user: User) => user.id !== id));
            }
        });
    }, []);

    useEffect(() => {
        getPaginateUser();
    }, []);

    return (
        <div className="px-2">
            <div className="py-2 px-2 border-bottom">
                <a href="/tai-khoan/cap-nhat" className="btn btn-link text-success text-decoration-none border p-0 fw-semibold">
                    + Thêm người dùng
                </a>
            </div>
            <FilterUser />
            <TableUser listUser={listUser} handleDeleteUser={handleDeleteUser} />
            <ComponentPagination pagiInfo={pagiInfo} callBack={getPaginateUser} />
        </div>
    );
}

mountReactComponentOnReady("root-manage-user", <ManageUserView />);
