import React, { useCallback, useEffect, useState } from "react";
import { Button, Modal } from "antd";
import type { DonVi } from "../../../user/type";
import { DonviApi } from "../../api/DonviApi";
import TreeDonvi from "./TreeDonvi";

interface TriggerTreeDonviProps {
    title?: string;
    onShow: () => void;
}

export const TriggerTreeDonvi = React.memo((props: TriggerTreeDonviProps) => {
    const { title, onShow } = props;
    return (
        <Button type="primary" onClick={onShow}>
            {title || "Danh sách đơn vị"}
        </Button>
    );
});

interface ModalTreeDonviProps {
    show: boolean;
    onHide: () => void;
    listDonvi: DonVi[];
    handlerChooseDonvi?: (donvi: DonVi) => void;
    usingselectChoose?: boolean;
    size?: "sm" | "lg" | "xl";
}

export const ModalTreeDonvi = React.memo((props: ModalTreeDonviProps) => {
    const { show, onHide, listDonvi, handlerChooseDonvi, usingselectChoose, size } = props;
    const isSelectChoose = usingselectChoose ?? false;
    const [selectedId, setSelectedId] = useState<number>(0);

    const handlerSelectedId = useCallback((id: number) => {
        setSelectedId(id);
    }, [setSelectedId]);

    const handleSubmit = useCallback(() => {
        const donvi = listDonvi.find((d) => d.id === selectedId);
        if (donvi) {
            handlerChooseDonvi?.(donvi);
        }
        onHide();
    }, [onHide, handlerChooseDonvi, listDonvi, selectedId]);

    return (
        <Modal open={show} onCancel={onHide} title="Danh sách đơn vị" width={size === "xl" ? 1200 : size === "sm" ? 520 : 900}
            footer={[
                <Button key="submit" type="primary" onClick={handleSubmit}>
                    Xác nhận
                </Button>,
            ]}
        >
            <div style={{ maxHeight: "500px", overflowY: "auto" }}>
                <TreeDonvi
                    listDonvi={listDonvi}
                    usingselectChoose={isSelectChoose}
                    selectedId={selectedId}
                    handlerSelectedId={handlerSelectedId}
                    {...(handlerChooseDonvi && { handlerChooseDonvi })}
                />
            </div>
        </Modal>
    );
});

interface ComponentModalTreeDonviProps {
    /** hàm để ở bên ngoài có thể sử lý khi sau khi đã chọn đơn vị */
    handlerChooseDonvi: (donvi: DonVi) => void;
}

export const ComponentModalTreeDonvi = React.memo((props: ComponentModalTreeDonviProps) => {
    const { handlerChooseDonvi } = props;
    const [show, setShow] = useState(false);
    const [listDonvi, setListDonvi] = useState<DonVi[]>([]);
    const onShow = useCallback(() => {
        setShow(true);
    }, []);
    const onHide = useCallback(() => {
        setShow(false);
    }, []);

    useEffect(() => {
        DonviApi.getAllDonvi(DonviApi.conditionDefault).then((res) => {
            setListDonvi(res as DonVi[]);
        });
    }, []);
    return (
        <React.Fragment>
            <ModalTreeDonvi
                show={show}
                onHide={onHide}
                listDonvi={listDonvi}
                handlerChooseDonvi={handlerChooseDonvi}
            />
        </React.Fragment>
    );
});
