import { useCallback, useMemo, useState } from "react";
import { useManageDonviStore } from "../../store/Donvi/manageDonviStore";
import type { DonVi } from "../../../user/type";

export function useSearchTextDonvi() {
    const listDonvi = useManageDonviStore(state => state.listDonvi);
    const textSearch = useManageDonviStore(state => state.textSearch);
    const setTextSearch = useManageDonviStore(state => state.setTextSearch);
    const setSelectedDonvi = useManageDonviStore(state => state.setSelectedDonvi);
    const handleChangeTextSearch = useCallback((e: React.ChangeEvent<HTMLInputElement>) => {
        setTextSearch(e.target.value);
        setSelectedDonvi(null);
    }, [setTextSearch, setSelectedDonvi]);

    const listDonviFiltered = useMemo(() => {
        const keyword = textSearch.trim().toLowerCase();
        if (!keyword) {
            return listDonvi;
        }

        const byId = new Map<number, DonVi>();
        listDonvi.forEach((donvi) => {
            byId.set(donvi.id, donvi);
        });

        const result = new Map<number, DonVi>();
        const includeWithParents = (item: DonVi) => {
            let current: DonVi | undefined = item;
            while (current) {
                if (result.has(current.id)) {
                    break;
                }
                result.set(current.id, current);
                if (current.ParentID == null || current.ParentID < 0) {
                    break;
                }
                current = byId.get(current.ParentID);
            }
        };

        listDonvi.forEach((donvi) => {
            if ((donvi.TenDonVi ?? "").toLowerCase().includes(keyword)) {
                includeWithParents(donvi);
            }
        });

        return Array.from(result.values());
    }, [listDonvi, textSearch]);

    return { textSearch, listDonviFiltered, handleChangeTextSearch };
}
