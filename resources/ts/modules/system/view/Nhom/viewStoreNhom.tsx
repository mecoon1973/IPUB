import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState, useCallback } from "react";
import type { Nhom } from "../../type/Nhom";
import { NhomApi } from "../../api/NhomApi";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import { NhomFormFields } from "../../component/Nhom/NhomFormFields";

function emptyFormState(): Partial<Nhom> {
    return {
        MaNhomNSD: "",
        TenNhomNSD: "",
    };
}



interface ViewStoreNhomProps {
    nhom?: Nhom | null;
}

export const ViewStoreNhom = React.memo((props: ViewStoreNhomProps) => {
    const { nhom } = props;
    const [formState, setFormState] = useState<Partial<Nhom>>(() => {
        if(nhom){
            return {
                ...emptyFormState(),
                ...nhom,
            };
        }
        return emptyFormState()
    });
    const [submitting, setSubmitting] = useState(false);

    const handleSubmit = useCallback(() => {
        setSubmitting(true);
        NhomApi.upsert(formState).then((res: Nhom | null) => {
            if(res) {
                window._toastbox("Cập nhật nhóm thành công", "success");
                setFormState(res);
            }
        }).finally(() => {
            setSubmitting(false);
        });
    }, [formState, setSubmitting]);

    const setField = useCallback(<K extends keyof Nhom>(key: K, value: Nhom[K]) => {
        setFormState((prev: Partial<Nhom>) => ({ ...prev, [key]: value }));
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore title={nhom ? "Cập nhật nhóm" : "Thêm mới nhóm"} callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <NhomFormFields form={formState} setField={setField} />
        </div>
    );
});


const ROOT_ID = "root-store-nhom";
const bladeProps = readRootDataProps<ViewStoreNhomProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreNhom {...bladeProps} />);
