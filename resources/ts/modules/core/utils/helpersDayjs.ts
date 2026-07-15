import dayjs from "dayjs";

/**
 * Chuyển đổi ngày tháng năm thành chuỗi ngày tháng năm
 * @param date Ngày tháng năm
 * @returns Chuỗi ngày tháng năm
 */
export function formatDateToString(date: number | string | Date | undefined, format: string = "DD/MM/YYYY"): string {
    if (!date) {
        return "";
    }
    return dayjs(date).isValid() ? dayjs(date).format(format) : "";
}

/**
 * Chuyển đổi ngày tháng năm thành số giây
 * @param date Ngày tháng năm
 * @returns Số giây
 */
export function formatDateToNumber(date: number | string | Date | undefined): number {
    if (!date) {
        return 0;
    }
    return dayjs(date).isValid() ? dayjs(date).unix() : 0;
}

/**
 * Chuyển đổi chuỗi ngày tháng năm thành số giây
 * @param date Chuỗi ngày tháng năm
 * @returns Số giây
 */
export function convertValueToDayjs(date: string | number | Date | undefined | null | dayjs.Dayjs | unknown): dayjs.Dayjs | undefined {
    const normalized = normalizeDateInput(date);
    if (normalized === null) {
        return undefined;
    }
    const d = dayjs(normalized);
    return d.isValid() ? d : undefined;
}

/**
 * Chuẩn hoá input ngày về Date/string/number mà dayjs parse được.
 * Hỗ trợ thêm Carbon JSON `{ date: "..." }` và Mongo `{ $date: "..." }`.
 */
export function normalizeDateInput(value: unknown): string | number | Date | dayjs.Dayjs | null {
    if (value === null || value === undefined || value === "" || value === 0) {
        return null;
    }
    if (value instanceof Date || typeof value === "string" || typeof value === "number") {
        return value;
    }
    if (dayjs.isDayjs(value)) {
        return value;
    }
    if (typeof value === "object") {
        const obj = value as Record<string, unknown>;
        if (typeof obj.date === "string") {
            return obj.date;
        }
        if (typeof obj.$date === "string" || typeof obj.$date === "number") {
            return obj.$date;
        }
        if (obj.$date && typeof obj.$date === "object") {
            const inner = obj.$date as Record<string, unknown>;
            if (typeof inner.$numberLong === "string" || typeof inner.$numberLong === "number") {
                return Number(inner.$numberLong);
            }
        }
        if (typeof (obj as { toDate?: () => Date }).toDate === "function") {
            return (obj as { toDate: () => Date }).toDate();
        }
    }
    return null;
}

/**
 * Chuỗi ISO-8601 UTC có millis + offset +00:00 (khớp ví dụ backend 2009-04-14T09:32:00.000+00:00).
 * Dùng trước khi gửi API vì jQuery form-urlencoded sẽ gọi Date.toString() nếu để nguyên Date.
 */
export function formatDateToIso8601UtcOffset(
    date: string | number | Date | undefined | null | dayjs.Dayjs | unknown
): string | null {
    const normalized = normalizeDateInput(date);
    if (normalized === null) {
        return null;
    }
    const d = dayjs(normalized);
    if (!d.isValid()) {
        return null;
    }
    return d.toDate().toISOString().replace("Z", "+00:00");
}

/**
 * Chuyển đổi ngày tháng năm thành chuỗi ISO-8601 UTC có millis + offset +00:00
 * chuyên dùng để gửi lên API định dạng cho field datetime
 * @param value Ngày tháng năm
 * @returns Chuỗi ISO-8601 UTC có millis + offset +00:00
 */
export function toIso8601UtcOffset(value: unknown): string | null {
    if (value === null || value === undefined || value === 0) {
        return null;
    }
    if (typeof value === "number") {
        return formatDateToIso8601UtcOffset(dayjs.unix(value));
    }
    return formatDateToIso8601UtcOffset(value);
};
