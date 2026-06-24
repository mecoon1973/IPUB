import {useEffect, useState } from "react";
import type { ChucNang } from "../../type";
import { ChucnangApi } from "../../api/ChucnangApi";

export function useGetChucnang(conditions: Record<string, any> = ChucnangApi.conditionDefault) {
    const [listChucnang, setListChucnang] = useState<ChucNang[]>([]);

    useEffect(() => {
        ChucnangApi.getAllChucnang(conditions).then((res) => {
            setListChucnang(res as ChucNang[]);
        });
    }, [])

    return {listChucnang, setListChucnang};
}
