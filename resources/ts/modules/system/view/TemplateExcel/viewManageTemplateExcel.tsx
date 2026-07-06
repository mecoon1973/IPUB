import {
    mountReactComponentOnReady,
    readRootDataProps,
} from "../../../core/utils/helpers";
import React, { useMemo, useState } from "react";




interface ViewManageTemplateExcelProps {
    

}

export const ViewManageTemplateExcel = React.memo((props: ViewManageTemplateExcelProps) => {
    const {  } = props;


    return (
        <div className="px-2">

        </div>
    );
});


const ROOT_ID = "root-manage-template-excel";
const bladeProps = readRootDataProps<ViewManageTemplateExcelProps>(ROOT_ID) ?? {};
mountReactComponentOnReady(ROOT_ID, <ViewManageTemplateExcel {...bladeProps} />);
