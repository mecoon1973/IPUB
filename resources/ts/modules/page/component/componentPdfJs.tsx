import React, { useCallback, useEffect, useMemo, useRef, useState } from "react";
import {
    getDocument,
    GlobalWorkerOptions,
    type PDFDocumentProxy,
    type RenderTask,
} from "pdfjs-dist";
import pdfWorkerSrc from "pdfjs-dist/build/pdf.worker.min.mjs?url";
import {
    CaretDownOutlined,
    CaretUpOutlined,
    DownloadOutlined,
    MenuOutlined,
    MinusOutlined,
    PlusOutlined,
    PrinterOutlined,
    SearchOutlined,
} from "@ant-design/icons";
import "./componentPdfJs.css";

GlobalWorkerOptions.workerSrc = pdfWorkerSrc;

export interface ComponentPdfJsProps {
    url: string;
    /** Chiều cao khung xem. Mặc định "calc(100vh - 120px)" */
    height?: string | number;
    className?: string;
}

const MIN_SCALE = 0.25;
const MAX_SCALE = 5;
const SCALE_STEP = 0.1;

const ZOOM_OPTIONS: { value: string; label: string }[] = [
    { value: "0.5", label: "50%" },
    { value: "0.75", label: "75%" },
    { value: "1", label: "100%" },
    { value: "1.25", label: "125%" },
    { value: "1.5", label: "150%" },
    { value: "2", label: "200%" },
    { value: "2.5", label: "250%" },
    { value: "3", label: "300%" },
];

async function releasePdfDocument(doc: PDFDocumentProxy | null): Promise<void> {
    if (!doc) {
        return;
    }
    try {
        await doc.cleanup();
    } catch {
        // ignore
    }
    try {
        await doc.loadingTask.destroy();
    } catch {
        // ignore
    }
}

function clampScale(value: number): number {
    return Math.max(MIN_SCALE, Math.min(MAX_SCALE, Number(value.toFixed(3))));
}

function ToolbarIconButton(props: {
    title: string;
    onClick?: () => void;
    disabled?: boolean;
    active?: boolean;
    small?: boolean;
    children: React.ReactNode;
}) {
    const { title, onClick, disabled, active, small, children } = props;
    return (
        <button
            type="button"
            title={title}
            aria-label={title}
            disabled={disabled}
            onClick={onClick}
            className={[
                "pdf-icon-btn",
                small ? "pdf-icon-btn--sm" : "",
                active ? "is-active" : "",
            ]
                .filter(Boolean)
                .join(" ")}
        >
            {children}
        </button>
    );
}

const _ComponentPdfJs = (props: ComponentPdfJsProps) => {
    const { url, height = "calc(100vh - 120px)", className } = props;

    const canvasRef = useRef<HTMLCanvasElement | null>(null);
    const renderTaskRef = useRef<RenderTask | null>(null);
    const thumbCanvasRefs = useRef<Map<number, HTMLCanvasElement>>(new Map());

    const [pdfDoc, setPdfDoc] = useState<PDFDocumentProxy | null>(null);
    const [pageNumber, setPageNumber] = useState(1);
    const [numPages, setNumPages] = useState(0);
    const [scale, setScale] = useState(1);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);
    const [showSidebar, setShowSidebar] = useState(false);
    const [showSearch, setShowSearch] = useState(false);
    const [searchQuery, setSearchQuery] = useState("");
    const [searchStatus, setSearchStatus] = useState<string | null>(null);
    const [pageInput, setPageInput] = useState("1");

    useEffect(() => {
        setPageInput(String(pageNumber));
    }, [pageNumber]);

    useEffect(() => {
        let cancelled = false;

        async function loadPdf() {
            setPdfDoc(null);
            setNumPages(0);
            setPageNumber(1);
            setScale(1);
            setError(null);
            setSearchStatus(null);

            if (!url?.trim()) {
                setError("Chưa có URL file PDF.");
                return;
            }

            setLoading(true);
            try {
                const doc = await getDocument({ url: url.trim(), withCredentials: true }).promise;
                if (cancelled) {
                    await releasePdfDocument(doc);
                    return;
                }
                setPdfDoc(doc);
                setNumPages(doc.numPages);
                setPageNumber(1);
            } catch (err) {
                if (!cancelled) {
                    setError(err instanceof Error ? err.message : "Không tải được file PDF.");
                    setPdfDoc(null);
                    setNumPages(0);
                }
            } finally {
                if (!cancelled) {
                    setLoading(false);
                }
            }
        }

        void loadPdf();
        return () => {
            cancelled = true;
        };
    }, [url]);

    useEffect(() => {
        return () => {
            void releasePdfDocument(pdfDoc);
        };
    }, [pdfDoc]);

    useEffect(() => {
        if (!pdfDoc || !canvasRef.current || pageNumber < 1 || pageNumber > numPages) {
            return;
        }

        const doc = pdfDoc;
        const canvas = canvasRef.current;
        let cancelled = false;

        async function renderPage() {
            try {
                renderTaskRef.current?.cancel();
                renderTaskRef.current = null;

                const page = await doc.getPage(pageNumber);
                if (cancelled) {
                    return;
                }

                const viewport = page.getViewport({ scale });
                const context = canvas.getContext("2d");
                if (!context) {
                    setError("Trình duyệt không hỗ trợ canvas 2D.");
                    return;
                }

                const dpr = window.devicePixelRatio || 1;
                canvas.width = Math.floor(viewport.width * dpr);
                canvas.height = Math.floor(viewport.height * dpr);
                canvas.style.width = `${Math.floor(viewport.width)}px`;
                canvas.style.height = `${Math.floor(viewport.height)}px`;

                const transform = dpr !== 1 ? [dpr, 0, 0, dpr, 0, 0] : undefined;
                const task = page.render({ canvasContext: context, canvas, viewport, transform });
                renderTaskRef.current = task;
                await task.promise;
            } catch (err) {
                if (cancelled || (err instanceof Error && err.name === "RenderingCancelledException")) {
                    return;
                }
                setError(err instanceof Error ? err.message : "Không render được trang PDF.");
            }
        }

        void renderPage();
        return () => {
            cancelled = true;
            renderTaskRef.current?.cancel();
            renderTaskRef.current = null;
        };
    }, [pdfDoc, pageNumber, numPages, scale]);

    useEffect(() => {
        if (!showSidebar || !pdfDoc || numPages < 1) {
            return;
        }

        const doc = pdfDoc;
        let cancelled = false;

        async function renderThumbs() {
            for (let page = 1; page <= numPages; page++) {
                const canvas = thumbCanvasRefs.current.get(page);
                if (!canvas || cancelled) {
                    continue;
                }
                try {
                    const pdfPage = await doc.getPage(page);
                    if (cancelled) {
                        return;
                    }
                    const viewport = pdfPage.getViewport({ scale: 0.2 });
                    const ctx = canvas.getContext("2d");
                    if (!ctx) {
                        continue;
                    }
                    canvas.width = Math.floor(viewport.width);
                    canvas.height = Math.floor(viewport.height);
                    await pdfPage.render({ canvasContext: ctx, canvas, viewport }).promise;
                } catch {
                    // ignore
                }
            }
        }

        void renderThumbs();
        return () => {
            cancelled = true;
        };
    }, [showSidebar, pdfDoc, numPages]);

    const canGoPrev = !loading && pageNumber > 1;
    const canGoNext = !loading && pageNumber < numPages;

    const goPrev = () => setPageNumber((page) => Math.max(1, page - 1));
    const goNext = () => setPageNumber((page) => Math.min(numPages, page + 1));

    const commitPageInput = () => {
        const parsed = Number.parseInt(pageInput, 10);
        if (!Number.isFinite(parsed) || numPages < 1) {
            setPageInput(String(pageNumber));
            return;
        }
        const next = Math.min(numPages, Math.max(1, parsed));
        setPageNumber(next);
        setPageInput(String(next));
    };

    const setManualScale = (value: number) => {
        setScale(clampScale(value));
    };

    const handleZoomSelect = (value: string) => {
        setManualScale(Number(value));
    };

    const zoomSelectValue = useMemo(() => {
        const matched = ZOOM_OPTIONS.find((opt) => Math.abs(Number(opt.value) - scale) < 0.01);
        return matched?.value ?? String(scale);
    }, [scale]);

    const zoomOptions = useMemo(() => {
        if (ZOOM_OPTIONS.some((opt) => opt.value === zoomSelectValue)) {
            return ZOOM_OPTIONS;
        }
        return [
            ...ZOOM_OPTIONS,
            { value: zoomSelectValue, label: `${Math.round(Number(zoomSelectValue) * 100)}%` },
        ];
    }, [zoomSelectValue]);

    const handlePrint = useCallback(() => {
        if (!url) {
            return;
        }
        const win = window.open(url, "_blank", "noopener,noreferrer");
        if (!win) {
            return;
        }
        // Cùng origin: in khi document sẵn sàng. Khác origin: tab mở sẵn, user tự in.
        const timer = window.setInterval(() => {
            try {
                if (win.closed) {
                    window.clearInterval(timer);
                    return;
                }
                if (win.document.readyState === "complete") {
                    window.clearInterval(timer);
                    win.focus();
                    win.print();
                }
            } catch {
                window.clearInterval(timer);
            }
        }, 400);
    }, [url]);

    const handleDownload = useCallback(() => {
        if (!url) {
            return;
        }
        const link = document.createElement("a");
        link.href = url;
        link.download = url.split("/").pop()?.split("?")[0] || "document.pdf";
        link.rel = "noopener";
        document.body.appendChild(link);
        link.click();
        link.remove();
    }, [url]);

    const runSearch = useCallback(async () => {
        const query = searchQuery.trim().toLowerCase();
        if (!pdfDoc || !query) {
            setSearchStatus(null);
            return;
        }

        setSearchStatus("Đang tìm…");
        try {
            for (let page = 1; page <= pdfDoc.numPages; page++) {
                const textContent = await (await pdfDoc.getPage(page)).getTextContent();
                const text = textContent.items
                    .map((item) => ("str" in item ? String(item.str) : ""))
                    .join(" ")
                    .toLowerCase();
                if (text.includes(query)) {
                    setPageNumber(page);
                    setSearchStatus(`Tìm thấy ở trang ${page}.`);
                    return;
                }
            }
            setSearchStatus("Không tìm thấy.");
        } catch {
            setSearchStatus("Lỗi khi tìm kiếm.");
        }
    }, [pdfDoc, searchQuery]);

    const heightStyle = typeof height === "number" ? `${height}px` : height;

    return (
        <div id="pdf-js-container" className={`pdf-viewer ${className ?? ""}`.trim()}>
            <div className="pdf-toolbar">
                <div className="pdf-toolbar__side pdf-toolbar__side--left">
                    <ToolbarIconButton
                        title="Thu gọn / mở bảng thu nhỏ"
                        active={showSidebar}
                        onClick={() => setShowSidebar((open) => !open)}
                    >
                        <MenuOutlined />
                    </ToolbarIconButton>
                    <ToolbarIconButton
                        title="Tìm kiếm"
                        active={showSearch}
                        disabled={!pdfDoc}
                        onClick={() => setShowSearch((open) => !open)}
                    >
                        <SearchOutlined />
                    </ToolbarIconButton>

                    <div className="pdf-page-nav">
                        <div className="pdf-page-arrows">
                            <ToolbarIconButton title="Trang trước" small onClick={goPrev} disabled={!canGoPrev}>
                                <CaretUpOutlined />
                            </ToolbarIconButton>
                            <ToolbarIconButton title="Trang sau" small onClick={goNext} disabled={!canGoNext}>
                                <CaretDownOutlined />
                            </ToolbarIconButton>
                        </div>

                        <div className="pdf-page-label">
                            <input
                                type="text"
                                inputMode="numeric"
                                value={pageInput}
                                onChange={(e) => setPageInput(e.target.value.replace(/[^\d]/g, ""))}
                                onBlur={commitPageInput}
                                onKeyDown={(e) => {
                                    if (e.key === "Enter") {
                                        commitPageInput();
                                    }
                                }}
                                className="pdf-page-input"
                                aria-label="Số trang"
                            />
                            <span className="pdf-page-total">trên {numPages || 0}</span>
                        </div>
                    </div>

                    {loading && <span className="pdf-busy">Đang tải…</span>}
                </div>

                <div className="pdf-toolbar__center">
                    <ToolbarIconButton
                        title="Thu nhỏ"
                        onClick={() => setManualScale(scale - SCALE_STEP)}
                        disabled={loading || scale <= MIN_SCALE}
                    >
                        <MinusOutlined />
                    </ToolbarIconButton>

                    <select
                        className="pdf-zoom-select"
                        value={zoomSelectValue}
                        onChange={(e) => handleZoomSelect(e.target.value)}
                        aria-label="Mức zoom"
                        disabled={loading && !pdfDoc}
                    >
                        {zoomOptions.map((opt) => (
                            <option key={opt.value} value={opt.value}>
                                {opt.label}
                            </option>
                        ))}
                    </select>

                    <ToolbarIconButton
                        title="Phóng to"
                        onClick={() => setManualScale(scale + SCALE_STEP)}
                        disabled={loading || scale >= MAX_SCALE}
                    >
                        <PlusOutlined />
                    </ToolbarIconButton>
                </div>

                <div className="pdf-toolbar__side pdf-toolbar__side--right">
                    <ToolbarIconButton title="In" onClick={handlePrint} disabled={!url || loading}>
                        <PrinterOutlined />
                    </ToolbarIconButton>
                    <ToolbarIconButton title="Tải xuống" onClick={handleDownload} disabled={!url || loading}>
                        <DownloadOutlined />
                    </ToolbarIconButton>
                </div>
            </div>

            {showSearch && (
                <div className="pdf-search-bar">
                    <SearchOutlined />
                    <input
                        type="search"
                        value={searchQuery}
                        autoFocus
                        onChange={(e) => setSearchQuery(e.target.value)}
                        onKeyDown={(e) => {
                            if (e.key === "Enter") {
                                void runSearch();
                            }
                        }}
                        placeholder="Tìm trong tài liệu…"
                        className="pdf-search-input"
                    />
                    <button type="button" className="pdf-search-btn" onClick={() => void runSearch()}>
                        Tìm
                    </button>
                    {searchStatus && <span className="pdf-search-status">{searchStatus}</span>}
                </div>
            )}

            {error && <div className="pdf-error">{error}</div>}

            <div className="pdf-body" style={{ height: heightStyle }}>
                {showSidebar && (
                    <aside className="pdf-sidebar">
                        {Array.from({ length: numPages }, (_, i) => i + 1).map((page) => (
                            <button
                                key={page}
                                type="button"
                                onClick={() => setPageNumber(page)}
                                className={`pdf-thumb${page === pageNumber ? " is-active" : ""}`}
                            >
                                <canvas
                                    ref={(el) => {
                                        if (el) {
                                            thumbCanvasRefs.current.set(page, el);
                                        } else {
                                            thumbCanvasRefs.current.delete(page);
                                        }
                                    }}
                                />
                                <span className="pdf-thumb__label">{page}</span>
                            </button>
                        ))}
                    </aside>
                )}

                <div className="pdf-stage">
                    <div className="pdf-stage__inner">
                        <canvas ref={canvasRef} />
                    </div>
                </div>
            </div>
        </div>
    );
};

_ComponentPdfJs.displayName = "ComponentPdfJs";
export const ComponentPdfJs = _ComponentPdfJs as (props: ComponentPdfJsProps) => React.ReactElement;
