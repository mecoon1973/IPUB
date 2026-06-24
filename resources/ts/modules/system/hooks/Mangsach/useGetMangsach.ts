import {useEffect, useState } from "react";
import type { Mangsach } from "../../type";
import { MangsachApi } from "../../api/MangsachApi";

export function useGetMangsach(conditions: Record<string, any> = MangsachApi.conditionDefault) {
    const [listMangsach, setListMangsach] = useState<Mangsach[]>([]);

    useEffect(() => {
        MangsachApi.getListMangsach(conditions).then((res) => {
            setListMangsach(res as Mangsach[]);
        });
    }, [])

    return {listMangsach, setListMangsach};
}
