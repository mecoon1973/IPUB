import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useMemo, useState } from "react";
import { useGetQuyen } from "../../hooks/Quyen/useGetQuyen";
import { Button, Col, Row, Typography } from "antd";
import ComponentTree from "../../../page/component/componentTree";
import type Quyen from "../../type/Quyen";
import type { DataTree } from "../../../core/types/baseTreeEntity";
import { QuyenApi } from "../../api/QuyenApi";

const FilterQuyen = React.memo(() => {
    return (
        <React.Fragment>
            <div className="py-2 px-2 border-bottom">
                <Typography.Title level={4} className="mb-0">Danh mục quyền</Typography.Title>
            </div>
            <div className="py-2 px-2 border-bottom">
                <Button type="link" href="/he-thong/quyen/cap-nhat" className="text-success fw-semibold px-0">
                    + Thêm quyền
                </Button>
            </div>
        </React.Fragment>
    );
});

interface ViewManageQuyenProps {

}

export const ViewManageQuyen = React.memo((props: ViewManageQuyenProps) => {
    const {  } = props;
    const { listQuyen, setListQuyen } = useGetQuyen();
    const [contextQuyen, setContextQuyen] = useState<Quyen | null>(null);

    const contextMenuItems = useMemo(() => {
        const parentId = contextQuyen?.id ?? "";
        return [
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/quyen/cap-nhat?parentId=${parentId}`;
                }}
            >
                Thêm
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/quyen/cap-nhat/${contextQuyen?.id}`;
                }}
            >
                Sửa quyền
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    QuyenApi.delete(contextQuyen?.id ?? 0).then((result) => {
                        if(result){
                            window._toastbox("Xóa quyền thành công", "success");
                            setListQuyen(listQuyen.filter((quyen) => quyen.id !== contextQuyen?.id));
                        }
                    });
                }}
            >
                Xóa quyền
            </button>,
            <button
                type="button"
                className="dropdown-item"
                onClick={() => {
                    window.location.href = `/he-thong/quyen/gan-chuc-nang-vao-quyen/${contextQuyen?.id}`;
                }}
            >
                Gán chức năng cho quyền
            </button>,
        ];
    }, [setListQuyen, contextQuyen]);

    return (
        <div className="px-2">
            <FilterQuyen />
            <Row gutter={12}>
                <Col span={16}>
                    <ComponentTree
                        listData={listQuyen}
                        getLabel={(quyen: Quyen) => {
                            return quyen.TenQuyen + " (" + quyen.MaQuyen + ")";
                        }}
                        usingselectChoose={false}
                        openByDefault={true}
                        contextMenuItems={contextMenuItems}
                        onContextMenuRecordChange={setContextQuyen}
                        rootParent={{
                            id: "-1",
                            name: "Danh mục quyền",
                            children: [],
                        } as DataTree}
                    />
                </Col>
            </Row>
        </div>
    );
});


const ROOT_ID = "root-manage-quyen";
const bladeProps = readRootDataProps<ViewManageQuyenProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageQuyen {...bladeProps} />);
