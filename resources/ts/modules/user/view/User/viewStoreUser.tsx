import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import type { User } from "../../../user/type";
import { useCallback } from "react";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { UserFormFields } from "../../components/User/UserFormFields";
import { UserApi } from "../../api/UserApi";
import { Col, Row } from "antd";
import { useGetDonVi } from "../../../system/hooks/Donvi/useGetDonVi";

function emptyFormState(): Partial<User> {
    return {
        id: 0,
        MaCanBo: "",
        HoTen: "",
        NgaySinh: new Date(),
        ID_ChucVu: 0,
        ChucVuText: "",
        ID_DonVi: 0,
        ID_ChuyenMon: 0,
        SoDienThoai: "",
        Email: "",
        DiaChi: "",
        UserName: "",
        IsActive: false,
        IsEditor: false,
        UserThemes: "",
        NgayHetHan: new Date(),
        SoLuongBanGhi: 0,
        ID_Scale: 0,
        NguoiKi: false,
        KyQDXB: false,
        UQKyQDXB: false,
        NguoiSoanThao: false,
        KyNhayQDXB: false,
        InUsed: false,
        IsDeleted: false,
        DaGui: false,
        KhoaGuiNhan: "",
        MaSoChungChi: "",
        NgayCap: 0,
        NoiCap: "",
        ChucDanhBienTap: "",
    };
}

interface ViewStoreUserPageProps {
    user?: User | null;
}

export const ViewStoreUser = React.memo((props: ViewStoreUserPageProps) => {
    const { user } = props;
    const [form, setForm] = useState<Partial<User>>(() => {
        if (user) {
            return user;
        }
        return {
            ...emptyFormState(),
        };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {

        const mapKeysRequired = {
            "HoTen": "Họ tên",
            "Email": "Email",
            "ID_DonVi" : "Đơn vị",
        };

        if(form.Email && !form.Email.includes("@")) {
            window._toastbox("Email không hợp lệ, vui lòng nhập lại", "error");
            return;
        }

        const messageRequired = Object.keys(mapKeysRequired).map(key => !form[key as keyof User] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "").filter(Boolean).join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);
        UserApi.upsert(form).then((res: User | null) => {
            if (res) {
                window._toastbox("Cập nhật người dùng thành công", "success");
                setForm( (prev: Partial<User>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[form, setForm]);

    const setField = useCallback(<K extends keyof User>(key: K, value: User[K]) => {
        setForm((prev: Partial<User>) => ({ ...prev, [key]: value }));
    }, []);

    const { listDonvi } = useGetDonVi();

    return (
        <div className="px-1">
            <ComponentTitleStore title={user ? "Cập nhật người dùng" : "Thêm mới người dùng"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <Row gutter={[16, 16]}>
                <Col xs={24} lg={16}>
                    <UserFormFields form={form} setField={setField} listDonvi={listDonvi} />
                </Col>
            </Row>
        </div>
    );
});

const ROOT_ID = "root-store-user";
const bladeProps = readRootDataProps<ViewStoreUserPageProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreUser {...bladeProps} />);
