import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useState, useCallback } from "react";
import type Nhom from "../../type/Nhom";

function emptyFormState(): Partial<Nhom> {
    return {

    };
}

interface ViewStoreNhomProps {
    nhom?: Nhom | null;
}

export const ViewStoreNhom = React.memo((props: ViewStoreNhomProps) => {
    const { nhom } = props;

    return (
        <div className="px-2">

        </div>
    );
});


const ROOT_ID = "root-store-nhom";
const bladeProps = readRootDataProps<ViewStoreNhomProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewStoreNhom {...bladeProps} />);
