import {useEffect, useState } from "react";
import type HDXB from "../../type/HDXB";
import type Quyen from "../../type/Quyen";
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
