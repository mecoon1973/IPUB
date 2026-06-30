import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useMemo, useState } from "react";
import type { User } from "../../../user/type";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { Col, Divider, Row } from "antd";
import type { Nhom, Quyen } from "../../../system/type";
import ComponentTree from "../../../page/component/componentTree";
import type { BaseTreeEntity, DataTree } from "../../../core/types/baseTreeEntity";
import { UserApi } from "../../api/UserApi";

interface TreeObjectProps {
    listNhom: Nhom[];
    listQuyen: Quyen[];
    form : Partial<User>;
    setForm : React.Dispatch<React.SetStateAction<Partial<User>>>;
}

const TreeObject = React.memo((props: TreeObjectProps) => {
    const { form, listNhom, listQuyen, setForm } = props;

    const listNhomTree = useMemo(() => {
        return listNhom.map((nhom) => {
            return {
                ...nhom,
                id: nhom.id,
                ParentID: 0,
                ThuTu: 0,
            } as BaseTreeEntity & Nhom;
        });
    }, [listNhom]);

    const listQuyenInNhom = useMemo<Quyen[]>(() => {
        const allowedQuyenIds = new Set<number>(
            listNhom
                .filter((nhom) => form.nhom_ids?.includes(nhom.id))
                .flatMap((nhom) => nhom.listIdQuyen ?? [])
        );
        return listQuyen
            .filter((quyen) => allowedQuyenIds.has(quyen.id))
            .map((quyen) => ({ ...quyen }));
    }, [listQuyen, listNhom, form.nhom_ids]);

    return (
        <Row gutter={12}>
            <Col span={8}>
                <ComponentTree
                    listData={listNhomTree}
                    getLabel={(nhom: Nhom & BaseTreeEntity) => {
                        return nhom.TenNhomNSD;
                    }}
                    usingselectChoose={true}
                    openByDefault={true}
                    autoSelectChild={true}
                    handlerSelectedId={(id: number | number[]) => {
                        setForm((prev) => {
                            if (Array.isArray(id)) {
                                return { ...prev, nhom_ids: id };
                            }
                            const current = prev.nhom_ids ?? [];
                            const nhom_ids = current.includes(id)
                                ? current.filter((x) => x !== id)
                                : [...current, id];
                            return { ...prev, nhom_ids };
                        });
                    }}
                    selectedId={form.nhom_ids || []}
                    rootParent={{
                        id: "-1",
                        name: "Danh mục nhóm",
                        children: [],
                    } as DataTree}
                />
            </Col>
            <Col span={8}>
                <ComponentTree
                    listData={listQuyen}
                    getLabel={(quyen: Quyen) => `${quyen.TenQuyen} (${quyen.MaQuyen})`}
                    usingselectChoose={true}
                    openByDefault={true}
                    autoSelectChild={true}
                    rootParent={{
                        id: "-1",
                        name: "Danh mục quyền",
                        children: [],
                    } as DataTree}
                    selectedId={form.quyen_ids || []}
                    handlerSelectedId={(id: number | number[]) => {
                        setForm((prev) => {
                            if (Array.isArray(id)) {
                                return { ...prev, quyen_ids: id };
                            }
                            const current = prev.quyen_ids ?? [];
                            const quyen_ids = current.includes(id)
                                ? current.filter((x) => x !== id)
                                : [...current, id];
                            return { ...prev, quyen_ids };
                        });
                    }}
                />
            </Col>
            <Col span={8}>
                <ComponentTree
                    listData={listQuyenInNhom}
                    getLabel={(quyen: Quyen) => `${quyen.TenQuyen} (${quyen.MaQuyen})`}
                    openByDefault={true}
                    rootParent={{
                        id: "-1",
                        name: "Danh mục quyền theo nhóm",
                        children: [],
                    } as DataTree}
                />
            </Col>
        </Row>
    );
});

const InforUser = React.memo((props: {user: User}) => {
    const { user } = props;
    return (
        <React.Fragment>
            <Row className="mb-1">
                <span>Họ và tên: <b>{user.HoTen}</b></span>
            </Row>
            <Row className="mb-1">
                <span>Chức vụ: <b>{user.chucvu?.TenChucVu}</b></span>
            </Row>
            <Row className="mb-1">
                <span>Phòng ban: <b>{user.donvi?.TenDonVi}</b></span>
            </Row>
        </React.Fragment>
    );
});

interface ViewAssignPermissionsProps {
    user: User;
    listNhom: Nhom[];
    listQuyen: Quyen[];
}

export const ViewAssignPermissions = React.memo((props: ViewAssignPermissionsProps) => {
    const { user, listNhom, listQuyen } = props;
    const [form, setForm] = useState<Partial<User>>(() => {
        return {
            ...user,
            nhom_ids: user.nhom_ids || [],
            quyen_ids: user.quyen_ids || [],
        };
    });
    const [submitting, setSubmitting] = useState(false);
    const handleSubmit = useCallback(() => {
        setSubmitting(true);
        UserApi.upsert({
            id: user.id,
            HoTen: user.HoTen,
            Email: user.Email,
            ID_DonVi: user.ID_DonVi || 0,
            nhom_ids: form.nhom_ids || [],
            quyen_ids: form.quyen_ids || [],
        }).then((res: User | null) => {
            if(res){
                window._toastbox("Cập nhật thành công", "success");
            }
        }).finally(() => {
            setSubmitting(false);
        });
    }, [form]);

    return (
        <div className="px-2">
            <ComponentTitleStore title="Cấp tài khoản cán bộ" callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <InforUser user={user} />
            <Divider className="my-2"/>
            <TreeObject form={form} setForm={setForm} listNhom={listNhom} listQuyen={listQuyen} />
        </div>
    );
});

const ROOT_ID = "root-assign-permissions";
const bladeProps: ViewAssignPermissionsProps = {
    user: {} as User,
    listNhom: [],
    listQuyen: [],
    ...readRootDataProps<ViewAssignPermissionsProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewAssignPermissions {...bladeProps} />);
