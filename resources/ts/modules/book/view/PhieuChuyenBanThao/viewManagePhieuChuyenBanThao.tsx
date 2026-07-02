import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useCallback, useEffect, useMemo, useState } from "react";
import { type PagiInfo } from "../../../page/type";
import { ComponentPagination } from "../../../page/component/pagination";


interface ViewManagePhieuChuyenBanThaoProps {

}

export const ViewManagePhieuChuyenBanThao = React.memo((props: ViewManagePhieuChuyenBanThaoProps) => {



    return (
        <div className="px-2 py-2">

        </div>
    );
});


const ROOT_ID = "root-manage-phieu-chuyen-ban-thao";
const bladeProps: ViewManagePhieuChuyenBanThaoProps = {
    ...readRootDataProps<ViewManagePhieuChuyenBanThaoProps>(ROOT_ID)
};
mountReactComponentOnReady(ROOT_ID, <ViewManagePhieuChuyenBanThao {...bladeProps} />);
