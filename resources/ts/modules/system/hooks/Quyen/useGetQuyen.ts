import {useEffect, useState } from "react";
import type { HDXB, Quyen } from "../../type";
import { QuyenApi } from "../../api/QuyenApi";

export function useGetQuyen(conditions: Partial<Quyen> = QuyenApi.conditionDefault) {
    const [listQuyen, setListQuyen] = useState<Quyen[]>([]);

    useEffect(() => {
        QuyenApi.getAllQuyen(conditions).then((res) => {
            setListQuyen(res as Quyen[]);
        });
    }, [])

    return { listQuyen, setListQuyen};
}
