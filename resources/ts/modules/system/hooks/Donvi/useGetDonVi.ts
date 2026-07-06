import {useEffect, useCallback, useMemo, useState } from "react";
import { useManageDonviStore } from "../../store/Donvi/manageDonviStore";
import type { DonVi } from "../../../user/type/DonVi";
import { DonviApi } from "../../api/DonviApi";

export function useGetDonVi(conditions: Record<string, any> = DonviApi.conditionDefault) {
    const [listDonvi, setListDonvi] = useState<DonVi[]>([]);

    useEffect(() => {
        DonviApi.getAllDonvi(conditions).then((res) => {
            setListDonvi(res as DonVi[]);
        });
    }, [])

    return { listDonvi, setListDonvi};
}
