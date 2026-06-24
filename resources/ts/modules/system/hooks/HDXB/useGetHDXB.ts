import {useEffect, useState } from "react";
import { HDXBApi } from "../../api/HDXBApi";
import type { HDXB } from "../../type";

export function useGetHDXB(conditions: Partial<HDXB> = HDXBApi.conditionDefault) {
    const [listHDXB, setListHDXB] = useState<HDXB[]>([]);

    useEffect(() => {
        HDXBApi.getAllHDXB(conditions).then((res) => {
            setListHDXB(res as HDXB[]);
        });
    }, [])

    return { listHDXB, setListHDXB};
}
