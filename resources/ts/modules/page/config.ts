import type { NodeDashboard } from "./type";

export const dashboard: NodeDashboard[] = [
    {
        title: "BÀN LÀM VIỆC",
        type: "navbar",
        children: [],
    },
    {
        title: "2. QUẢN LÝ XUẤT BẢN",
        type: "navbar",
        children: [
            {
                title: "2.1 Quản lý Đề tài",
                type: "select",
                children: [
                    {
                        title: "2.1.1 Danh sách đề tài",
                        type: "link",
                        routes: "/phieu-dk-detai/quan-ly"
                    },
                    {
                        title: "2.1.1.A Danh sách đề tài - năm xuất bản",
                        type: "link",
                        routes: "/phieu-dk-detai/quan-ly",
                    },
                    { title: "2.1.2 HĐXB Miền", type: "link", routes: "" },
                    {
                        title: "2.1.3.A Phiếu trình HĐ Nhà",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.1.3.B HĐXB NXBGDVN",
                        type: "link",
                        routes: "/hdxb-nxbgdvn/quan-ly",
                    },
                    { title: "2.1.4 Cục xuất bản", type: "link", routes: "" },
                    {
                        title: "2.1.5 Phiếu trình CXB tăng số lượng",
                        type: "link",
                        routes: "",
                    },
                    {   title: "2.1.6 Danh mục sách",
                        type: "link",
                        routes: "/sach/quan-ly"
                    },
                    {
                        title: "2.1.7 Phiếu chuyển bản thảo",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.1.8 Danh mục sách thầu",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.1.9 Quản lý sách nghiệp vụ",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.1.10 Phiếu bàn giao và nghiệm thu SP",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.1.11 Danh mục hồ sơ đăng kí đề tài",
                        type: "link",
                        routes: "",
                    },
                ],
            },
            { title: "2.2 Quyết định in", type: "link", routes: "/qd-in/quan-ly" },
            {
                title: "2.3 Quản lý phí xuất bản",
                type: "select",
                children: [
                    {
                        title: "2.3.1 Cập Quyết định xuất bản",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.2 Xuất hóa đơn thu phí",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.3 Tổng hợp số liệu QLBX",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.4 Tính hình cấp QĐXB theo ĐV",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.5 Bảng kê chi tiết phí QLBX",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.6 Tổng hợp số liệu theo mảng sách",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.7 Tổng hợp QĐXB",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.8 Thống kê theo QĐXB",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.3.9 Bảng kê HD khoản thu từ hoạt động XB",
                        type: "link",
                        routes: "",
                    },
                ],
            },
            {
                title: "2.4 Quyết định phát hành",
                type: "select",
                children: [
                    {
                        title: "2.4.1 Quyết định phát hành",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.4.2 Báo cáo số liệu QDPH",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.4.3 Báo cáo số lượng QDPH",
                        type: "link",
                        routes: "",
                    },
                ],
            },
            {
                title: "2.5 Báo cáo",
                type: "select",
                children: [
                    {
                        title: "2.5.1 Báo cáo thực hiện xuất bản",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.2 Báo cáo hoạt động xuất bản",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.3 Quyết định xuất bản đã xoá",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.4 Báo cáo tổng hợp tờ khai lưu chiểu",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.5 Thống kê sách nghiệp vụ",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.6 Thống kê đề tài được thay đổi/ hủy",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.7 Thống kê đề tài theo mạng STK",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.8 Thống kê theo sách thầu",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.9 Báo cáo thống kê số lượng STK",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.10. Quản lý đề tài từ HĐXB - Miền",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.11. Báo cáo tăng số lượng",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.12 Tình hình thực hiện xuất bản",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "2.5.13. Báo cáo BĐNT phân mềm iPub",
                        type: "link",
                        routes: "",
                    },
                ],
            },
        ],
    },
    {
        title: "3. QUẢN LÝ KIỂM ĐỊNH",
        type: "navbar",
        children: [
            { title: "3.1 Lập danh sách đọc", type: "link", routes: "/quan-ly-kiem-dinh/ds-doc-ra-soat/quan-ly" },
            { title: "3.2 Phân công đọc", type: "link", routes: "" },
            {
                title: "3.3 Tình trạng dọc kiểm định",
                type: "link",
                routes: "",
            },
            {
                title: "3.4 Tình trạng dọc rà soát",
                type: "link",
                routes: "",
            },
            {
                title: "3.5 Báo cáo",
                type: "select",
                children: [
                    {
                        title: "3.5.4 Danh mục dọc rà soát",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "3.5.1 Chi tiết loại dọc rà soát",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "3.5.2 Danh mục dọc kiểm định",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "3.5.3 Kết quả dọc kiểm định",
                        type: "link",
                        routes: "",
                    },
                ],
            },
        ],
    },
    {
        title: "4. QUẢN LÝ LƯU CHIỂU",
        type: "navbar",
        children: [
            { title: "4.1 Nhập lưu chiểu", type: "link", routes: "/quan-ly-luu-chieu/phieu-nhap-lc/quan-ly" },
            {
                title: "4.2 Quản lý tờ khai lưu chiểu",
                type: "link",
                routes: "/quan-ly-luu-chieu/to-khai-luu-chuyen/quan-ly",
            },
            {
                title: "4.3 Phiếu nhập lưu chiểu đơn vị",
                type: "link",
                routes: "",
            },
            {
                title: "4.4 Thống kê báo cáo",
                type: "select",
                children: [
                    {
                        title: "4.4.1 Chi tiết nhập lưu chiểu",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "4.4.2 Báo cáo danh mục sách in nội bản",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "4.4.3 Báo cáo in nội bản toàn nhà",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "4.4.4 Báo cáo tổng hợp số liệu lưu chiểu",
                        type: "link",
                        routes: "",
                    },
                ],
            },
        ],
    },
    {
        title: "5. HỆ THỐNG",
        type: "navbar",
        children: [
            { title: "5.1 Nhật ký hệ thống", type: "link", routes: "/he-thong/lich-su-thao-tac/quan-ly" },
            {
                title: "5.2 Thiết lập tham số hệ thống",
                type: "link",
                routes: "/he-thong/bien-moi-truong/quan-ly",
            },
            {
                title: "5.3 Danh mục 1",
                type: "select",
                children: [
                    {
                        title: "5.3.1 Danh mục bộ sách",
                        type: "link",
                        routes: "/he-thong/bo-sach/quan-ly",
                    },
                    {
                        title: "5.3.2 Danh mục cấp",
                        type: "link",
                        routes: "/he-thong/cap/quan-ly",
                    },
                    {
                        title: "5.3.3 Danh mục chức vụ",
                        type: "link",
                        routes: "/he-thong/chuc-vu/quan-ly",
                    },
                    {
                        title: "5.3.4 Danh mục đối tượng nhận lưu chiểu",
                        type: "link",
                        routes: "/he-thong/donvi-lc/quan-ly",
                    },
                    {
                        title: "5.3.5 Danh mục đối tượng sử dụng",
                        type: "link",
                        routes: "/he-thong/doi-tuong/quan-ly",
                    },
                    {
                        title: "5.3.6 Danh mục loại XBP",
                        type: "link",
                        routes: "/he-thong/loai-xbp/quan-ly",
                    },
                    {
                        title: "5.3.7 Danh mục loại XBP lưu chiểu",
                        type: "link",
                        routes: "/he-thong/loai-xbp-luu-chieu/quan-ly",
                    },
                    {
                        title: "5.3.8 Danh mục lớp",
                        type: "link",
                        routes: "/he-thong/lop/quan-ly",
                    },
                    {
                        title: "5.3.9 Danh mục chuyển môn",
                        type: "link",
                        routes: "/he-thong/chuyen-mon/quan-ly",
                    },
                ],
            },
            {
                title: "5.4 Danh mục 2",
                type: "select",
                children: [
                    {
                        title: "5.4.1 Danh mục mảng sách",
                        type: "link",
                        routes: "/he-thong/mang-sach/quan-ly",
                    },
                    {
                        title: "5.4.1.B Danh mục mảng sách thống kê",
                        type: "link",
                        routes: "",
                    },
                    {
                        title: "5.4.2 Danh mục mảng sách CXB",
                        type: "link",
                        routes: "/he-thong/mang-sach-cxb/quan-ly",
                    },
                    {
                        title: "5.4.3 Danh mục môn học",
                        type: "link",
                        routes: "/he-thong/mon-hoc/quan-ly",
                    },
                    {
                        title: "5.4.4 Danh mục ngoại ngữ",
                        type: "link",
                        routes: "/he-thong/ngoai-ngu/quan-ly",
                    },
                    {
                        title: "5.4.5 Danh mục tủ sách",
                        type: "link",
                        routes: "/he-thong/tu-sach/quan-ly",
                    },
                    {
                        title: "5.4.6 Danh mục HĐXB",
                        type: "link",
                        routes: "/he-thong/hdxb/quan-ly",
                    },
                    {
                        title: "5.4.7 Danh mục XBP sách nghiệp vụ",
                        type: "link",
                        routes: "/he-thong/loai-xbp/quan-ly",
                    },
                    {
                        title: "5.4.8 Danh mục đối tượng nhận SNV",
                        type: "link",
                        routes: "/he-thong/doi-tuong-snv/quan-ly",
                    },
                    {
                        title: "5.4.9 Danh mục công việc thiết kế",
                        type: "link",
                        routes: "/he-thong/cong-viec-thiet-ke/quan-ly",
                    },
                    {
                        title: "5.4.10 Danh mục công việc chế bản in",
                        type: "link",
                        routes: "/he-thong/cong-viec-che-ban-in/quan-ly",
                    },
                ],
            },
            {
                title: "5.5 Quản lý cán bộ",
                type: "select",
                children: [
                    {
                        title: "5.5.1 Danh mục đơn vị",
                        type: "link",
                        routes: "/he-thong/don-vi/quan-ly",
                    },
                    {
                        title: "5.5.2 Danh mục cán bộ",
                        type: "link",
                        routes: "/tai-khoan/quan-ly",
                    },
                    {
                        title: "5.5.3 Danh mục quyền",
                        type: "link",
                        routes: "/he-thong/quyen/quan-ly",
                    },
                    {
                        title: "5.5.4 Danh mục chức năng",
                        type: "link",
                        routes: "/he-thong/chuc-nang/quan-ly",
                    },
                    {
                        title: "5.5.5 Danh mục nhóm",
                        type: "link",
                        routes: "/he-thong/nhom/quan-ly",
                    },
                ],
            },
        ],
    },
    {
        title: "6. TRỢ GIÚP",
        type: "navbar",
        children: [
            { title: "6.1 Thông tin phiên bản", type: "link", routes: "" },
            { title: "6.2 Thông tin bản quyền", type: "link", routes: "" },
            {
                title: "6.3 Hướng dẫn sử dụng",
                type: "link",
                routes: "http://test.nxbgd.vn/Resources/Tutorial_iPub.pdf",
            },
            {
                title: "Các văn bản - Quy định",
                type: "link",
                routes: "https://drive.google.com/drive/folders/1EiXZ6oJ9lJWQKRqsstsDVIed04zmC6rr",
            },
        ],
    },
];
