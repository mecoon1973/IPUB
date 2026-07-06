import React, { useState, useCallback }from "react";
import { Button, Modal } from "antd";
import type ChucNang from "../../type/ChucNang";
import ComponentTree from "../../../page/component/componentTree";

interface ModalTreeChucnangProps {
    show: boolean;
    onHide: () => void;
    listChucnang: ChucNang[];
    handlerChooseChucnang: (chucnang: ChucNang) => void;
    usingselectChoose: boolean;
    size?: "sm" | "lg" | "xl";
}

export const ModalTreeChucnang = React.memo((props: ModalTreeChucnangProps) => {
    const { show, onHide, listChucnang, handlerChooseChucnang, usingselectChoose, size } = props;
    const [selectedId, setSelectedId] = useState<number>(0);
    const isSelectChoose = usingselectChoose ?? false;
    const handlerSelectedId = useCallback((id: number) => {
        setSelectedId(id);
    }, [setSelectedId]);
    const handleSubmit = useCallback(() => {
        const chucnang = listChucnang.find((c) => c.id === selectedId);
        if (chucnang) {
            handlerChooseChucnang?.(chucnang);
        }
        onHide();
    }, [onHide, handlerChooseChucnang, listChucnang, selectedId]);
    return (
        <Modal
            open={show}
            onCancel={onHide}
            title="Danh sách chức năng"
            width={size === "xl" ? 1200 : size === "sm" ? 520 : 900}
            footer={[
                <Button key="submit" type="primary" onClick={handleSubmit}>
                    Xác nhận
                </Button>,
            ]}
        >
            <div style={{ maxHeight: "500px", overflowY: "auto" }}>
                <ComponentTree
                    listData={listChucnang}
                    usingselectChoose={isSelectChoose}
                    selectedId={selectedId}
                    handlerSelectedId={(id: number | number[]) => {
                        if(!Array.isArray(id)){
                            handlerSelectedId(id);
                        }
                    }}
                    handlerChooseData={handlerChooseChucnang}
                    getLabel={(chucnang) => chucnang.Title}
                />
            </div>
        </Modal>
    );
});
