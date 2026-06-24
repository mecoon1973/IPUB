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
export function convertValueToDayjs(date: string | number | Date | undefined | null | dayjs.Dayjs): dayjs.Dayjs | undefined {
    return date && dayjs(date).isValid() ? dayjs(date) : undefined;
}

/**
 * Chuỗi ISO-8601 UTC có millis + offset +00:00 (khớp ví dụ backend 2009-04-14T09:32:00.000+00:00).
 * Dùng trước khi gửi API vì jQuery form-urlencoded sẽ gọi Date.toString() nếu để nguyên Date.
 */
export function formatDateToIso8601UtcOffset(
    date: string | number | Date | undefined | null | dayjs.Dayjs
): string | null {
    if (date === null || date === undefined || date === "" || date === 0) {
        return null;
    }
    const d = dayjs(date);
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
    if (value instanceof Date || typeof value === "string") {
        return formatDateToIso8601UtcOffset(value);
    }
    if (typeof value === "number") {
        return formatDateToIso8601UtcOffset(dayjs.unix(value));
    }
    return null;
};
