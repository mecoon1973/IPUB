import React, { useCallback } from "react";
import { Input } from "antd";
import type { PhieuDkDetai } from "../../../type/PhieuDkDetai";

/**
 * Các ô nhập tách file + `React.memo` để **chỉ nhánh ô đó** reconcile khi `value` đổi.
 *
 * Điều kiện để memo có tác dụng:
 * - `setField` ổn định (ví dụ `useCallback` trong store — đã có).
 * - Truyền `field` là string literal / keyof cố định, không tạo arrow `() => setField(...)` mới mỗi lần render cha
 *   (ở đây `onChange` được tạo bằng `useCallback` trong chính component, phụ thuộc `[field, setField]`).
 *
 * Lưu ý: Cha (`FormFieldPhieuDkDetai`) vẫn chạy lại mỗi khi `form` đổi — điều đó không tránh được với một state object.
 * Memo giúp **các ô khác** (value không đổi) bỏ qua render sâu, giảm tải Ant/DatePicker/CKEditor ở nhánh khác.
 */

/**
 * Props cho ô nhập **một dòng** hoặc **textarea** (Bootstrap `Form.Control` size `sm`).
 *
 * Luồng dữ liệu: `value` đến từ `form[field]` ở cha → hiển thị controlled → user gõ → `onChange` gọi `setField(field, chuỗi mới)`.
 */
type TextSmProps = {
    /** Tên thuộc tính trên model `PhieuDkDetai` (ví dụ `"TenDeTai"`, `"TacGia"`). Dùng làm khóa khi gọi `setField`. */
    field: keyof PhieuDkDetai;
    /** Giá trị hiện tại đọc từ state form (chuỗi/ số / undefined). `undefined` được hiển thị như chuỗi rỗng. */
    value: string | number | undefined;
    /**
     * Hàm cập nhật một field trên state (thường là `setField` từ `ViewStorePhieuDkDetai`).
     * Phải **ổn định** (`useCallback` ở store) để `useCallback` bên trong component không đổi mỗi render — khi đó `React.memo` mới có ý nghĩa.
     */
    setField: (field: keyof PhieuDkDetai, value: unknown) => void;
    /**
     * Thuộc tính spread lên **thẻ bọc** `<div className="phieu-dk-field">`, không phải lên `<input>`.
     * Thường truyền kết quả `markField("TenDeTai")` từ form cha: gắn `data-field` (để scroll/focus khi validate)
     * và tùy chọn `data-invalid="true"` khi field bị lỗi (viền cảnh báo qua CSS).
     */
    fieldWrapperProps?: React.HTMLAttributes<HTMLDivElement> & {
        "data-field"?: string;
        "data-invalid"?: string;
    };
    /** Placeholder hiển thị trong ô khi rỗng (HTML `placeholder`). */
    placeholder?: string;
    /** Kiểu input HTML, ví dụ `"number"`, `"password"`. Mặc định là input text nếu không truyền (và không dùng `as`). */
    type?: string;
    /**
     * Gợi ý bàn phím trên mobile (HTML `inputMode`): `"numeric"`, `"decimal"`, `"search"`, …
     * Không thay thế validation; chỉ UX nhập liệu.
     */
    inputMode?: React.HTMLAttributes<HTMLInputElement>["inputMode"];
    /** `as="textarea"`: ô nhiều dòng. */
    as?: React.ElementType;
    /** Chỉ dùng khi `as` là textarea: số dòng (HTML `rows`). */
    rows?: number;
    /**
     * `true`: chỉ đọc, không sửa trực tiếp (ví dụ ô **Biên tập viên** mở modal khi click).
     * Khác `disabled`: vẫn focus/click được tùy trình duyệt.
     */
    readOnly?: boolean;
    /**
     * Xử lý click lên vùng control (ví dụ `readOnly` + mở modal chọn BTV).
     * Kiểu rộng `HTMLElement` vì `Form.Control` có thể render input/textarea.
     */
    onClick?: React.MouseEventHandler<HTMLElement>;
};

/**
 * Ô nhập text/textarea cỡ nhỏ, bọc `React.memo`.
 *
 * - Tự tạo `onChange` bằng `useCallback([field, setField])` để không tạo hàm mới mỗi lần render cha (điều kiện để memo hiệu quả).
 * - Các prop tùy chọn (`type`, `placeholder`, …) chỉ được spread xuống `Form.Control` khi **khác undefined** (tương thích `exactOptionalPropertyTypes`).
 */
export const PhieuDkTextFieldSm = React.memo(function PhieuDkTextFieldSm({
    field,
    value,
    setField,
    fieldWrapperProps,
    placeholder,
    type,
    inputMode,
    as,
    rows,
    readOnly,
    onClick,
}: TextSmProps) {
    const onChange = useCallback(
        (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
            setField(field, e.target.value as PhieuDkDetai[typeof field]);
        },
        [field, setField],
    );

    const strVal = String(value ?? "");

    return (
        <div className="phieu-dk-field" {...fieldWrapperProps}>
            {as === "textarea" ? (
                <Input.TextArea
                    size="small"
                    value={strVal}
                    onChange={onChange}
                    {...(rows !== undefined ? { rows } : {})}
                    {...(placeholder !== undefined ? { placeholder } : {})}
                    {...(readOnly === true ? { readOnly: true } : {})}
                    {...(onClick !== undefined ? { onClick } : {})}
                />
            ) : (
                <Input
                    size="small"
                    value={strVal}
                    onChange={onChange}
                    {...(type !== undefined ? { type } : {})}
                    {...(inputMode !== undefined ? { inputMode } : {})}
                    {...(placeholder !== undefined ? { placeholder } : {})}
                    {...(readOnly === true ? { readOnly: true } : {})}
                    {...(onClick !== undefined ? { onClick } : {})}
                />
            )}
        </div>
    );
});

/**
 * Props cho ô nhập **số** (`Form.Control` với `type="number"`).
 *
 * Khác `PhieuDkTextFieldSm`: không gọi `setField` trực tiếp với chuỗi, mà gọi `setNum(key, rawString)` —
 * trong form cha, `setNum` sẽ parse chuỗi → `number` (và xử lý rỗng/NaN) rồi mới `setField`.
 */
type NumProps = {
    /** Tên field kiểu số trên `PhieuDkDetai` (ví dụ `"LanTaiBan"`, `"SoLuongDK"`). */
    field: keyof PhieuDkDetai;
    /** Giá trị số hiện tại từ form. `undefined` hiển thị như `0` trong ô (theo code hiện tại). */
    value: number | undefined;
    /**
     * Hàm nhận **chuỗi** từ input (ví dụ `"12"`, `""`) và tự quy đổi sang number trong logic cha.
     * Ổn định nhờ `useCallback` ở `FormFieldPhieuDkDetai`.
     */
    setNum: (key: keyof PhieuDkDetai, raw: string) => void;
    /** Giống `TextSmProps.fieldWrapperProps`: spread lên div bọc, thường từ `markField(...)`. */
    fieldWrapperProps?: React.HTMLAttributes<HTMLDivElement> & {
        "data-field"?: string;
        "data-invalid"?: string;
    };
    /** Placeholder cho ô số. */
    placeholder?: string;
};

/**
 * Ô nhập số cỡ nhỏ, bọc `React.memo`.
 * Luôn dùng `type="number"`; `onChange` gọi `setNum(field, e.target.value)`.
 */
export const PhieuDkNumberFieldSm = React.memo(function PhieuDkNumberFieldSm({
    field,
    value,
    setNum,
    fieldWrapperProps,
    placeholder,
}: NumProps) {
    const onChange = useCallback(
        (e: React.ChangeEvent<HTMLInputElement>) => {
            setNum(field, e.target.value);
        },
        [field, setNum],
    );

    return (
        <div className="phieu-dk-field" {...fieldWrapperProps}>
            <Input size="small" type="number" value={value ?? 0} onChange={onChange} {...(placeholder !== undefined ? { placeholder } : {})} />
        </div>
    );
});
