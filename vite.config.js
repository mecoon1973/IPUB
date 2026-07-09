import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import react from '@vitejs/plugin-react'
import tailwindcss from '@tailwindcss/vite'
import path from 'node:path'
import { fileURLToPath } from 'node:url'

const __dirname = path.dirname(fileURLToPath(import.meta.url))

function injectSetupImport() {
    const setupModuleId = '@appSetup'
    const setupImportLine = `import "${setupModuleId}";\n`

    return {
        name: 'inject-setup-import',
        enforce: 'pre',
        transform(code, id) {
            if (!id) return null
            if (id.includes('node_modules')) return null
            if (!id.match(/\.(tsx)$/)) return null
            if (id.endsWith('.d.ts')) return null

            // tránh inject vào chính file setup
            if (id.endsWith(`${path.sep}_setup.ts`) || id.endsWith('/_setup.ts')) return null

            // nếu đã có import setup thì không inject thêm
            if (code.includes(`import "${setupModuleId}"`) || code.includes('_setup')) return null

            return {
                code: setupImportLine + code,
                map: null,
            }
        },
    }
}

export default defineConfig({
    resolve: {
        alias: {
            '@appSetup': path.resolve(__dirname, 'resources/ts/_setup.ts'),
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/ts/modules/core/core.ts',

                'resources/ts/modules/page/component/header/Header.tsx',
                'resources/ts/modules/page/view/Login/viewLogin.tsx',
                'resources/ts/modules/page/view/Login/viewForgetPassword.tsx',

                'resources/ts/modules/user/view/User/viewManageUser.tsx',
                'resources/ts/modules/user/view/User/viewStoreUser.tsx',
                'resources/ts/modules/user/view/User/viewCreateAccount.tsx',
                'resources/ts/modules/user/view/User/viewAssignPermissions.tsx',

                'resources/ts/modules/system/view/Donvi/viewManageDonvi.tsx',
                'resources/ts/modules/system/view/Donvi/viewStoreDonvi.tsx',
                'resources/ts/modules/system/view/Quyen/viewStoreQuyen.tsx',
                'resources/ts/modules/system/view/Quyen/viewManageQuyen.tsx',
                'resources/ts/modules/system/view/Quyen/viewPermissionSettings.tsx',
                'resources/ts/modules/system/view/Nhom/viewManageNhom.tsx',
                'resources/ts/modules/system/view/Nhom/viewStoreNhom.tsx',
                'resources/ts/modules/system/view/Nhom/viewManageCanboInNhom.tsx',
                'resources/ts/modules/system/view/Lop/viewStoreLop.tsx',
                'resources/ts/modules/system/view/Lop/viewManageLop.tsx',
                'resources/ts/modules/system/view/Monhoc/viewStoreMonhoc.tsx',
                'resources/ts/modules/system/view/Monhoc/viewManageMonhoc.tsx',
                'resources/ts/modules/system/view/Mangsach/viewStoreMangsach.tsx',
                'resources/ts/modules/system/view/Mangsach/viewManageMangsach.tsx',
                'resources/ts/modules/system/view/Doituong/viewStoreDoituong.tsx',
                'resources/ts/modules/system/view/Doituong/viewManageDoituong.tsx',
                'resources/ts/modules/system/view/Tusach/viewStoreTusach.tsx',
                'resources/ts/modules/system/view/Tusach/viewManageTusach.tsx',
                'resources/ts/modules/system/view/Chuyenmon/viewStoreChuyenmon.tsx',
                'resources/ts/modules/system/view/Chuyenmon/viewManageChuyenmon.tsx',
                'resources/ts/modules/system/view/Chucvu/viewStoreChucvu.tsx',
                'resources/ts/modules/system/view/Chucvu/viewManageChucvu.tsx',
                'resources/ts/modules/system/view/LoaiXBP/viewStoreLoaiXBP.tsx',
                'resources/ts/modules/system/view/LoaiXBP/viewManageLoaiXBP.tsx',
                'resources/ts/modules/system/view/MangsachCXB/viewStoreMangsachCXB.tsx',
                'resources/ts/modules/system/view/MangsachCXB/viewManageMangsachCXB.tsx',
                'resources/ts/modules/system/view/Ngoaingu/viewStoreNgoaingu.tsx',
                'resources/ts/modules/system/view/Ngoaingu/viewManageNgoaingu.tsx',
                'resources/ts/modules/system/view/Congviecchebanin/viewStoreCongviecchebanin.tsx',
                'resources/ts/modules/system/view/Congviecchebanin/viewManageCongviecchebanin.tsx',
                'resources/ts/modules/system/view/Congviecthietke/viewStoreCongviecthietke.tsx',
                'resources/ts/modules/system/view/Congviecthietke/viewManageCongviecthietke.tsx',
                'resources/ts/modules/system/view/BienMoiTruong/viewStoreBienMoiTruong.tsx',
                'resources/ts/modules/system/view/BienMoiTruong/viewManageBienMoiTruong.tsx',
                'resources/ts/modules/system/view/LoaiXBPLC/viewStoreLoaiXbpLc.tsx',
                'resources/ts/modules/system/view/LoaiXBPLC/viewManageLoaiXbpLc.tsx',
                'resources/ts/modules/system/view/DoituongSNV/viewManageDoituongSNV.tsx',
                'resources/ts/modules/system/view/DoituongSNV/viewStoreDoituongSNV.tsx',
                'resources/ts/modules/system/view/TemplateExcel/viewManageTemplateExcel.tsx',
                'resources/ts/modules/system/view/TemplateExcel/viewStoreTemplateExcel.tsx',

                'resources/ts/modules/topic/view/PhieuDkDetai/viewManagePhieuDkDetai.tsx',
                'resources/ts/modules/topic/view/PhieuDkDetai/viewStorePhieuDkDetai.tsx',
                'resources/ts/modules/topic/view/PhieuDkDetai/viewTaiBanPhieuDkDetai.tsx',
                'resources/ts/modules/topic/view/PhieuDkDetai/viewChuyenKeHoachPhieuDkDetai.tsx',

                'resources/ts/modules/topic/view/HDXBNXBGDVN/viewManageHDXBNXBGDVN.tsx',
                'resources/ts/modules/topic/view/HDXBNXBGDVN/viewPheDuyetDiInHDXBNXBGDVN.tsx',

                'resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewManagePhieuDkKhxbCxb.tsx',
                'resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewStorePhieuDkKhxbCxb.tsx',
                'resources/ts/modules/topic/view/PhieuDkKhxbCxb/viewCapMaIsbnPhieuDkKhxbCxb.tsx',
                'resources/ts/modules/topic/view/PhieuChuyenBanThao/viewStorePhieuChuyenBanThao.tsx',
                'resources/ts/modules/topic/view/PhieuChuyenBanThao/viewManagePhieuChuyenBanThao.tsx',

                'resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewManagePhieuNhapLC.tsx',
                'resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewStorePhieuNhapLC.tsx',
                'resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewManageTokhaiLuuChuyen.tsx',
                'resources/ts/modules/legalDeposit/view/PhieuNhapLC/viewStoreToKhaiLuuChuyen.tsx',

                'resources/ts/modules/qualityAssessment/view/DsDocRaSoat/viewManageDsDocRaSoat.tsx',
                'resources/ts/modules/qualityAssessment/view/DsDocRaSoat/viewStoreDsDocRaSoat.tsx',

                'resources/ts/modules/book/view/Sach/viewManageSach.tsx',
                'resources/ts/modules/book/view/Sach/viewPrintISBN.tsx',
            ],
            refresh: true,
        }),
        react(),
        tailwindcss(),
        injectSetupImport(),
    ],
    server: {
        host: 'nxbgd.xyz',
        port: 8000,
        cors: true,
        hmr: {
            host: 'nxbgd.xyz',
        },
    },
})
