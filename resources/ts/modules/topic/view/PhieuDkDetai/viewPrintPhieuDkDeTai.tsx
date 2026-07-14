import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState, useCallback } from "react";
import { ComponentPdfJs } from "../../../page/component/componentPdfJs";

interface ViewPrintPhieuDkDeTaiProps {
    url: string
}

export const ViewPrintPhieuDkDeTai = React.memo((props: ViewPrintPhieuDkDeTaiProps) => {
    const { url } = props;


    return (
        <div className="px-2">
            <ComponentPdfJs url={url} />
        </div>
    );
});

const ROOT_ID = "root-print-phieu-dk-de-tai";
const bladeProps: ViewPrintPhieuDkDeTaiProps = {
    url: "",
    ...readRootDataProps<ViewPrintPhieuDkDeTaiProps>(ROOT_ID),
};
mountReactComponentOnReady(ROOT_ID, <ViewPrintPhieuDkDeTai {...bladeProps} />);
