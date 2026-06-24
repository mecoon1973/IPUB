import React, { useCallback, useEffect, useState } from "react";
import { Button, Modal } from "antd";
import ComponentTree from "./componentTree";
import type { BaseTreeEntity } from "../../core/types/baseTreeEntity";

interface ModalTreeProps<T> {
    title: string;
    show: boolean;
    onHide: () => void;
    listData: T[];
    getLabel: (data: T) => string;
    handlerChoose?: (data: T) => void;
    usingselectChoose?: boolean;
    selectedIdDefault?: number;
    size?: string | number | Partial<Record<"xl" | "xxxl" | "xxl" | "lg" | "md" | "sm" | "xs", string | number>>;
}

function ModalTreeInner<T extends BaseTreeEntity>(props: ModalTreeProps<T>) {
    const { title, show, onHide, listData, handlerChoose, usingselectChoose, size, getLabel, selectedIdDefault } = props;
    const isSelectChoose = usingselectChoose ?? false;
    const [selectedId, setSelectedId] = useState<number>(selectedIdDefault ?? 0);

    useEffect(() => {
        if (show) {
            setSelectedId(selectedIdDefault ?? 0);
        }
    }, [show, selectedIdDefault]);

    const handlerSelectedId = useCallback((id: number | number[]) => {
        if (Array.isArray(id)) {

        } else {
            setSelectedId(id);
        }
    }, []);

    const handleSubmit = useCallback(() => {
        const result = listData.find((q) => q.id === selectedId);
        if (result) {
            handlerChoose?.(result);
        }
        onHide();
    }, [onHide, handlerChoose, listData, selectedId]);

    const modalWidth = size ?? "xl";

    return (
        <Modal
            title={title}
            open={show}
            onCancel={onHide}
            width={modalWidth}
            footer={[
                <Button key="ok" type="primary" onClick={handleSubmit}>
                    Xác nhận
                </Button>,
            ]}
            styles={{ body: { maxHeight: 500, overflowY: "auto" } }}
        >
            <ComponentTree
                listData={listData}
                getLabel={getLabel}
                usingselectChoose={isSelectChoose}
                selectedId={selectedId}
                handlerSelectedId={handlerSelectedId}
            />
        </Modal>
    );
}

/** memo làm mất generic trên component; cast để JSX suy luận T từ listData / callbacks */
export const ModalTree = React.memo(ModalTreeInner) as <T extends BaseTreeEntity>(
    props: ModalTreeProps<T>
) => React.ReactElement;
