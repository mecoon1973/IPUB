import React from "react";
import { ComponentModalTreeDonvi } from "../../../system/component/Donvi/ModalTreeDonvi";

const FilterUser = React.memo(() => {
    return (
        <div>
            <ComponentModalTreeDonvi handlerChooseDonvi={() => {}} />
        </div>
    );
});

export default FilterUser;
