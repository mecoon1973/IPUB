import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState, useCallback } from "react";
import type { Nhom, Quyen } from "../../type";
import { NhomApi } from "../../api/NhomApi";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import ComponentTree from "../../../page/component/componentTree";

function emptyFormState(): Partial<Nhom> {
    return {
        MaNhomNSD: "",
        TenNhomNSD: "",
        listIdQuyen: [],
    };
}



interface ViewPermissionSettingsNhomProps {
    nhom: Nhom;
    listQuyen: Quyen[];
}

export const ViewPermissionSettingsNhom = React.memo((props: ViewPermissionSettingsNhomProps) => {
    const { nhom, listQuyen } = props;
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
            if(res){
                window._toastbox("Cập nhật nhóm thành công", "success");
                setFormState(res);
            }
        }).finally(() => {
            setSubmitting(false);
        });
    }, [formState]);


    const handleSelectedId = useCallback((id: number) => {
        setFormState((prev) => {
            if(prev.listIdQuyen && prev.listIdQuyen.includes(id)){
                return {
                    ...prev,
                    listIdQuyen: prev.listIdQuyen.filter((item) => item !== id),
                };
            }
            return {
                ...prev,
                listIdQuyen: [...(prev.listIdQuyen ?? []), id],
            };
        });
    }, []);

    return (
        <div className="px-2">
            <ComponentTitleStore title="Phân quyền nhóm" callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <ComponentTree
                listData={listQuyen}
                getLabel={(quyen: Quyen) => {
                    return quyen.TenQuyen ?? "";
                }}
                usingselectChoose={true}
                handlerSelectedId={handleSelectedId}
                selectedId={formState.listIdQuyen ?? []}
            />
        </div>
    );
});


const ROOT_ID = "root-permission-settings-nhom";
const bladeProps = {
    nhom: null as unknown as Nhom,
    listQuyen: [],
    ...readRootDataProps<ViewPermissionSettingsNhomProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewPermissionSettingsNhom {...bladeProps} />);
