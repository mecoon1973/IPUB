import type { User } from "./modules/user/type/User";

export {};
declare global {
    interface Window {
        _apiCreate: (url: string, data: any, _options?: {}) => Promise<any>;
        _apiUpdate: (url: string, data: any, _options?: {}) => Promise<any>;
        _apiGet: (url: string, data?: any, _options?: {}) => Promise<any>;
        _apiDelete: (url: string, data?: any) => Promise<any>;
        _apiUpload: (url: any, data: any, _options?: {}) => Promise<any>;
        _toastbox: (
            message?: string,
            type?: "info" | "success" | "warning" | "danger" | "error",
            position?: "top" | "bottom",
            time?: number
        ) => void;
        _toastForm: (
            formElm: JQuery<HTMLElement>,
            message: string,
            type: "success" | "error" | "danger" = "success"
        ) => void;
        _debounce: <Fn extends Function>(
            fn: Fn,
            wait: number,
            maxTime: number
        ) => Fn;
        _dialogConfirm: (
            title: string,
            message: string,
            callback: (res: boolean) => void,
            $preModal: JQuery<HTMLElement> | null = null,
            type?: DialogConfirmType,
            buttonTertiaryText?: string,
            buttonText?: string,
            onCancel?: () => void
        ) => void;
        __AUTH__: User|null,
        /** Fallback theo tài liệu CKEditor 5 khi `config.licenseKey` chưa được merge kịp (tránh `license-key-missing`). */
        CKEDITOR_GLOBAL_LICENSE_KEY: string = "GPL";
    }
}
