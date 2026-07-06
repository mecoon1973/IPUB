import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import type ChucNang from "../../type/ChucNang";
import type Quyen from "../../type/Quyen";
import { ComponentTitleStore } from "../../../page/component/componentTitleStore";
import ComponentTree from '../../../page/component/componentTree';
import { QuyenApi } from "../../api/QuyenApi";

interface ViewPermissionSettingsProps {
    quyen: Quyen;
    listChucnang: ChucNang[];
}

export const ViewPermissionSettings = React.memo((props: ViewPermissionSettingsProps) => {
    const { quyen, listChucnang } = props;
    const [submitting, setSubmitting] = useState(false);
    const [selectedIds, setSelectedIds] = useState<number[]>(quyen.listIdFunctions);
    const handleSubmit = useCallback(() => {
        setSubmitting(true);
        QuyenApi.upsert({
            id: quyen.id,
            MaQuyen: quyen.MaQuyen,
            TenQuyen: quyen.TenQuyen,
            listIdFunctions: selectedIds,
        }).then((res) => {
            if(res){
                window._toastbox("Cập nhật thành công", "success");
            }
        }).finally(() => {
            setSubmitting(false);
        });
    },[quyen, selectedIds, setSubmitting]);

    const handleSelectedId = useCallback((id: number) => {
        setSelectedIds((prev) => {
            if(prev.includes(id)){
                return prev.filter((item) => item !== id);
            }
            return [...prev, id];
        });
    }, [setSelectedIds]);

    return (
        <div className="px-2">
            <ComponentTitleStore title="Gán chức năng vào quyền" callbackSubmit={handleSubmit} disabledSubmit={submitting} />
            <ComponentTree
                listData={listChucnang}
                getLabel={(chucnang: ChucNang) => {
                    return chucnang.Title ?? "";
                }}
                usingselectChoose={true}
                handlerSelectedId={(id: number | number[]) => {
                    if(Array.isArray(id)){
                        setSelectedIds(id);
                    }
                }}
                selectedId={selectedIds}
            />
        </div>
    );
});

const ROOT_ID = "root-permission-settings";
const bladeProps = {
    quyen: null as unknown as Quyen,
    listChucnang: [],
    ...readRootDataProps<ViewPermissionSettingsProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewPermissionSettings {...bladeProps} />);
