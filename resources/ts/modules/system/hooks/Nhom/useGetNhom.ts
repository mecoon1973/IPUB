import {useEffect, useState } from "react";
import { HDXBApi } from "../../api/HDXBApi";
import type Nhom from "../../type/Nhom";
import { NhomApi } from "../../api/NhomApi";

export function useGetNhom(conditions: Partial<Nhom> = NhomApi.conditionDefault) {
    const [listNhom, setListNhom] = useState<Nhom[]>([]);

    useEffect(() => {
        NhomApi.getAllNhom(conditions).then((res) => {
            setListNhom(res as Nhom[]);
        });
    }, [])

    return { listNhom, setListNhom};
}
