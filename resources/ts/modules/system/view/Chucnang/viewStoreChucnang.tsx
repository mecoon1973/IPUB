import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import type { ChucNang, PhanHe } from "../../type";
import { ChucnangApi } from "../../api/ChucnangApi";
import { ChucnangFormFields } from "../../component/Chucnang/ChucnangFormFields";
import { useGetChucnang } from "../../hooks/Chucnang/useGetChucnang";

function emptyFormState(): Partial<ChucNang> {
    return {
        id: 0,
        Code: "",
        Title: "",
        Href: "",
        ChildFunctionCode: "",
        FunctionCode: "",
        isLinkFull: false,
        Target: "_blank",
        Description: "",
        OnMenu: false,
        ThuTu: 0,
        ParentID: 0,
        PhanHeID: 0,
        Crumb: '',

    };
}

interface ViewStoreChucnangPageProps {
    chucnang?: ChucNang | null;
    parentId?: number;
    listPhanhe: PhanHe[];
}

export const ViewStoreChucnang = React.memo((props: ViewStoreChucnangPageProps) => {
    const { chucnang, parentId, listPhanhe } = props;
    const { listChucnang } = useGetChucnang();
    const [form, setForm] = useState<Partial<ChucNang>>(() => {
        if (chucnang) {
            return chucnang;
        }
        return {
            ...emptyFormState(),
            ParentID: parentId ?? 0,
        };
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {

        const mapKeysRequired = {
            "Code": "Mã chức năng",
            "Title": "Tên chức năng",
            "FunctionCode": "Mã trên url",
        };

        const messageRequired = Object.keys(mapKeysRequired).map(key => !form[key as keyof Partial<ChucNang>] ? mapKeysRequired[key as keyof typeof mapKeysRequired] : "").filter(Boolean).join(", ");
        if (messageRequired) {
            window._toastbox(`Vui lòng nhập đầy đủ thông tin: ${messageRequired}`, "error");
            return;
        }

        setSubmitting(true);

        ChucnangApi.upsert(form).then((res: ChucNang | null) => {
            if (res) {
                window._toastbox("Cập nhật chức năng thành công", "success");
                setForm( (prev: Partial<ChucNang>) => ({ ...prev, ...res}));
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[form, setForm]);

    const setField = useCallback(<K extends keyof ChucNang>(key: K, value: ChucNang[K]) => {
            setForm((prev: Partial<ChucNang>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-1">
            <ComponentTitleStore title={chucnang ? "Cập nhật chức năng" : "Thêm mới chức năng"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <ChucnangFormFields form={form} setField={setField} listPhanhe={listPhanhe} listChucnang={listChucnang}/>
        </div>
    );
});

const ROOT_ID = "root-store-chucnang";
const bladeProps: ViewStoreChucnangPageProps = {
    listPhanhe: [],
    ...readRootDataProps<ViewStoreChucnangPageProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewStoreChucnang {...bladeProps} />);
