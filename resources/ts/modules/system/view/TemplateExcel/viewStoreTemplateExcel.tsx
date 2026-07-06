import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState } from "react";
import { useCallback } from "react";
import { Col, Form, Input, Row } from "antd";
import type { TemplateExcel } from "../../type/TemplateExcel";


function emptyFormState(): Partial<any> {
    return {
        id: 0,

    };
}


interface ViewStoreTemplateExcelProps {
    templateExcel?: TemplateExcel | null;
}

export const ViewStoreTemplateExcel = React.memo((props: ViewStoreTemplateExcelProps) => {
    const { templateExcel } = props;
    const [form, setForm] = useState<Partial<any>>(() => {
        let dataForm = emptyFormState();
        return dataForm;
    });
    const [submitting, setSubmitting] = useState(false);


    const setField = useCallback(<K extends keyof any>(key: K, value: any[K]) => {
        setForm((prev: Partial<any>) => ({ ...prev, [key]: value }));
    }, []);



    const handleSubmit = useCallback(() => {


    },[form, setForm]);



    return (
        <div className="px-2">

        </div>
    );
});

const ROOT_ID = "root-store-template-excel";
const bladeProps = readRootDataProps<ViewStoreTemplateExcelProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreTemplateExcel {...bladeProps} />);
