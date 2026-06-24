import $ from "jquery";
import Toast from "bootstrap/js/src/toast.js";
// ============================================================================
// TYPE DEFINITIONS
// ============================================================================
interface ApiOptions {
    dataType?: string;
    contentType?: string | false;
    form?: boolean;
    modalMessage?: boolean;
    signal?: AbortSignal;
}

export type DataApi = Record<string, any>;

interface AjaxError {
    status?: number;
    statusText?: string;
    responseJSON?: {
        errors?: Record<string, string[]>;
        message?: string | string[];
    };
}

// ============================================================================
// GLOBAL FUNCTIONS
// ============================================================================
const dfOptionsApi = { form: false, modalMessage: false };

// Helper function để lấy CSRF token
const getCSRFToken = (): string => {
    return $('meta[name="csrf-token"]').attr("content") || "";
};

// Re-implement _getMessageFormError function in global.js
const getMessageFormError = (error: AjaxError): string => {
    let message = "";

    if (error?.status === 0) {
        message = "Không thể kết nối đến máy chủ! Vui lòng kiểm tra kết nối internet của bạn";
    } else if (error?.responseJSON?.errors) {
        const errors = error.responseJSON.errors;
        for (const key in errors) {
            if (Object.prototype.hasOwnProperty.call(errors, key)) {
                const msgs = errors[key];
                if (msgs?.length) message += msgs.join(", ") + ", ";
            }
        }
        message = message.slice(0, -2);
    } else if (error?.responseJSON?.message) {
        message = Array.isArray(error.responseJSON.message) ? error.responseJSON.message.join(", ") : error.responseJSON.message;
    }

    return message;
};

// Re-implement _messageError function in global.js
const messageError = (
    error: AjaxError,
    form: boolean = false,
    modalMessage: boolean = false
): void => {
    let message = getMessageFormError(error);

    if (message === "Server Error" || message === "") {
        return;
    }

    if (form) {
        // window._toastForm(form, message, "danger");
    } else if (modalMessage) {
        const $modalAlertMessage = $("#modal-alert-message");
        $modalAlertMessage.find(".text-message").html(message);
        ($modalAlertMessage as any).modal("hide").modal("show");
    } else {
        // window._toastbox(message, "danger", "top-right", 100000);
    }
};

// Re-implement _apiCreate function
export const apiCreate = async (
    url: string,
    data: DataApi,
    _options: ApiOptions = {}
): Promise<any> => {
    const options = { ...dfOptionsApi, ..._options };

    const result = await $.ajax({
        url,
        headers: {
            "X-CSRF-TOKEN": getCSRFToken(),
        },
        type: "POST",
        dataType: options.dataType || "json",
        contentType: options.contentType,
        data,
        success: (_res: any) => {
            // Success handler
        },
        error: (error: AjaxError) => {
            messageError(error, options.form, options.modalMessage);
        },
    });
    return result;
};

window._apiCreate = apiCreate;

// Re-implement _apiGet function
export const apiGet = async (
    url: string,
    data: DataApi,
    _options: ApiOptions = {}
): Promise<any> => {
    const options = { ...dfOptionsApi, ..._options };

    const result = await $.ajax({
        url,
        type: "GET",
        data,
        dataType: options.dataType || "json",
        contentType: options.contentType,
        beforeSend: function (jqXHR: any) {
            if (_options.signal) {
                // Khi signal bị abort, hủy request của jQuery
                _options.signal.addEventListener("abort", () =>
                    jqXHR.abort()
                );
            }
        },
        success: (_res: any) => {
            // Success handler
        },
        error: (error: AjaxError) => {
            if (error.statusText !== "abort") {
                messageError(error, options.form, options.modalMessage);
            }
        },
    });
    return result;
};

window._apiGet = apiGet;

// Re-implement _apiUpdate function
export const apiUpdate = async (
    url: string,
    data: DataApi,
    _options: ApiOptions = {}
): Promise<any> => {
    const options = { ...dfOptionsApi, ..._options };

    const result = await $.ajax({
        url,
        headers: {
            "X-CSRF-TOKEN": getCSRFToken(),
        },
        type: "PUT",
        dataType: options.dataType || "json",
        contentType: options.contentType,
        data,
        success: (_res: any) => {
            // Success handler
        },
        error: (error: AjaxError) => {
            messageError(error, options.form, options.modalMessage);
        },
    });
    return result;
};

window._apiUpdate = apiUpdate;

// Re-implement _apiDelete function
export const apiDelete = async (
    url: string,
    data: DataApi,
    _options: ApiOptions = {}
): Promise<any> => {
    const options = { ...dfOptionsApi, ..._options };

    const result = await $.ajax({
        url,
        headers: {
            "X-CSRF-TOKEN": getCSRFToken(),
        },
        data,
        type: "DELETE",
        dataType: "json",
        success: (_res: any) => {
            // Success handler
        },
        error: (error: AjaxError) => {
            messageError(error, options.form, options.modalMessage);
        },
    });
    return result;
};

window._apiDelete = apiDelete;

// Re-implement _apiUpload function
export const apiUpload = async (
    url: string,
    data: any,
    _options: ApiOptions = {}
): Promise<any> => {
    const options = { ...dfOptionsApi, ..._options };

    const result = await $.ajax({
        url,
        headers: {
            "X-CSRF-TOKEN": getCSRFToken(),
        },
        type: "POST",
        data,
        cache: false,
        contentType: false,
        processData: false,
        success: (_data: any) => {
            // Success handler
        },
        error: (error: AjaxError) => {
            messageError(error, options.form, options.modalMessage);
        },
    });
    return result;
};

window._apiUpload = apiUpload;

const toastTypeToBgClass = (
    type: "success" | "warning" | "danger" | "info" | "error"
): string => {
    if (type === "error") {
        return "text-bg-danger";
    }
    return `text-bg-${type}`;
};

/**
 * hàm hỗ trợ hiển thị toast message
 * @param message - message cần hiển thị
 * @param type - loại toast (success, warning, danger, info, error)
 * @param position - vị trí hiển thị (top, bottom)
 * @param time - thời gian hiển thị (ms)
 */
export const toastbox = (
    message?: string,
    type: "success" | "warning" | "danger" | "info" | "error" = "success",
    position: "top" | "bottom" = "top",
    time: number = 5000
): void => {
    if (message === undefined || message === "") {
        return;
    }

    const $toastContainer = $("#toast-container");
    if (!$toastContainer.length) {
        return;
    }

    const $rootHeader = $("#root-header");
    const $legacyHeader = $(".header-olm");
    const headerH =
        ($rootHeader.length ? $rootHeader.outerHeight(true) : undefined) ??
        ($legacyHeader.length ? $legacyHeader.outerHeight(true) : undefined) ??
        0;

    if (position === "top") {
        $toastContainer.css({
            top: `${Number(headerH) + 8}px`,
            bottom: "auto",
            right: "1rem",
            left: "auto",
            alignItems: "flex-end",
        });
    } else {
        $toastContainer.css({
            top: "auto",
            bottom: "1rem",
            right: "1rem",
            left: "auto",
            alignItems: "flex-end",
        });
    }

    const el = document.createElement("div");
    el.setAttribute("role", "alert");
    el.setAttribute("aria-live", "assertive");
    el.setAttribute("aria-atomic", "true");
    el.className = `toast fade ${toastTypeToBgClass(type)} border-0`;
    const body = document.createElement("div");
    body.className = "toast-body";
    body.textContent = message;
    el.appendChild(body);

    const containerEl = $toastContainer.get(0);
    if (!containerEl) {
        return;
    }
    containerEl.appendChild(el);

    const toast = new Toast(el, {
        animation: true,
        autohide: true,
        delay: time,
    });

    el.addEventListener(
        "hidden.bs.toast",
        () => {
            el.remove();
        },
        { once: true }
    );

    toast.show();
};

window._toastbox = toastbox;

// ============================================================================
// EXPORT FOR GLOBAL USE
// ============================================================================

//Xuất ra global để dùng trong các file js thuần
if (typeof window !== "undefined") {

    (window as any).CoreOlm = {
        apiCreate: apiCreate,
        apiUpdate: apiUpdate,
        apiDelete: apiDelete,
        apiUpload: apiUpload,
        apiGet: apiGet,
        toastbox: toastbox,
    };

}

export default {
    apiCreate: apiCreate,
    apiUpdate: apiUpdate,
    apiDelete: apiDelete,
    apiUpload: apiUpload,
    apiGet: apiGet,
    toastbox: toastbox,
};
