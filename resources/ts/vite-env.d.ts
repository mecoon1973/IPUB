/// <reference types="vite/client" />

declare module "bootstrap/js/src/toast.js" {
    export default class BootstrapToast {
        constructor(
            element: Element,
            options?: { animation?: boolean; autohide?: boolean; delay?: number },
        );
        show(): void;
    }
}
