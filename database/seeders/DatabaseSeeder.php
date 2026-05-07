<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Product;
use App\Models\Batch;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $defaultPassword = Hash::make('12345678');
        $now = Carbon::now();

        // Danh sách 8 doanh nghiệp và sản phẩm mẫu (Dữ liệu chuẩn Demo)
        $enterprises = [
            [
                'name' => 'Công ty CP Sữa Việt Nam Vinamilk',
                'email' => 'admin@vinamilk.demo', // Đuôi .demo để không gửi mail thật
                'tax_code' => '0300588569',
                'address' => '10 Tân Trào, Phường Tân Phú, Quận 7, TP.HCM',
                'products' => [
                    ['name' => 'Sữa tươi tiệt trùng Vinamilk 100%', 'cat_id' => 2, 'gtin' => '8932026001001', 'batch' => 'VL-MLK-2401', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Sữa chua uống Probi', 'cat_id' => 2, 'gtin' => '8932026001002', 'batch' => 'PB-2203', 'mfg' => $now->copy()->subDays(10), 'exp' => $now->copy()->addDays(50)],
                    ['name' => 'Sữa đặc Ông Thọ', 'cat_id' => 5, 'gtin' => '8932026001003', 'batch' => 'OT-8891', 'mfg' => $now->copy()->subMonths(2), 'exp' => $now->copy()->addMonths(10)],
                    ['name' => 'Sữa hạt Vinamilk 9 loại hạt', 'cat_id' => 2, 'gtin' => '8932026001004', 'batch' => 'SH-001', 'mfg' => $now->copy()->subDays(15), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Sữa bột Dielac Alpha', 'cat_id' => 3, 'gtin' => '8932026001005', 'batch' => 'DA-102', 'mfg' => $now->copy()->subMonths(3), 'exp' => $now->copy()->addMonths(21)],
                ]
            ],
            [
                'name' => 'Tập đoàn TH True Milk',
                'email' => 'admin@thmilk.demo',
                'tax_code' => '2901122681',
                'address' => 'Xã Nghĩa Sơn, Huyện Nghĩa Đàn, Nghệ An',
                'products' => [
                    ['name' => 'Sữa tươi sạch TH true MILK', 'cat_id' => 2, 'gtin' => '8932026002001', 'batch' => 'TH-001', 'mfg' => $now->copy()->subDays(5), 'exp' => $now->copy()->addMonths(6)],
                    ['name' => 'Nước gạo rang TH', 'cat_id' => 2, 'gtin' => '8932026002002', 'batch' => 'TH-002', 'mfg' => $now->copy()->subDays(10), 'exp' => $now->copy()->addMonths(6)],
                    ['name' => 'Sữa hạt óc chó TH', 'cat_id' => 2, 'gtin' => '8932026002003', 'batch' => 'TH-003', 'mfg' => $now->copy()->subDays(2), 'exp' => $now->copy()->addMonths(6)],
                    ['name' => 'Sữa chua TH', 'cat_id' => 1, 'gtin' => '8932026002004', 'batch' => 'TH-004', 'mfg' => $now->copy()->subDays(15), 'exp' => $now->copy()->addDays(45)],
                    ['name' => 'Kem TH', 'cat_id' => 1, 'gtin' => '8932026002005', 'batch' => 'TH-005', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(5)],
                ]
            ],
            [
                'name' => 'Công ty CP Masan Consumer',
                'email' => 'admin@masan.demo',
                'tax_code' => '0302017440',
                'address' => 'Tòa nhà MPlaza, 39 Lê Duẩn, Quận 1, TP.HCM',
                'products' => [
                    ['name' => 'Nước mắm Nam Ngư', 'cat_id' => 1, 'gtin' => '8932026003001', 'batch' => 'MS-NN-01', 'mfg' => $now->copy()->subMonths(2), 'exp' => $now->copy()->addMonths(10)],
                    ['name' => 'Mì Omachi', 'cat_id' => 1, 'gtin' => '8932026003002', 'batch' => 'MS-OM-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Tương ớt Chinsu', 'cat_id' => 1, 'gtin' => '8932026003003', 'batch' => 'MS-CS-01', 'mfg' => $now->copy()->subDays(20), 'exp' => $now->copy()->addMonths(8)],
                    ['name' => 'Nước tương Tam Thái Tử', 'cat_id' => 1, 'gtin' => '8932026003004', 'batch' => 'MS-TT-01', 'mfg' => $now->copy()->subMonths(3), 'exp' => $now->copy()->addMonths(9)],
                    ['name' => 'Cà phê Wake-up', 'cat_id' => 2, 'gtin' => '8932026003005', 'batch' => 'MS-WU-01', 'mfg' => $now->copy()->subDays(5), 'exp' => $now->copy()->addMonths(12)],
                ]
            ],
            [
                'name' => 'Công ty CP Acecook Việt Nam',
                'email' => 'admin@acecook.demo',
                'tax_code' => '0300808687',
                'address' => 'KCN Tân Bình, P. Tây Thạnh, Q. Tân Phú, TP.HCM',
                'products' => [
                    ['name' => 'Mì Hảo Hảo Tôm Chua Cay', 'cat_id' => 1, 'gtin' => '8932026004001', 'batch' => 'AC-HH-01', 'mfg' => $now->copy()->subDays(10), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Mì SiuKay', 'cat_id' => 1, 'gtin' => '8932026004002', 'batch' => 'AC-SK-01', 'mfg' => $now->copy()->subDays(12), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Phở ăn liền Đệ Nhất', 'cat_id' => 1, 'gtin' => '8932026004003', 'batch' => 'AC-PH-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Bún ăn liền Hằng Nga', 'cat_id' => 1, 'gtin' => '8932026004004', 'batch' => 'AC-BU-01', 'mfg' => $now->copy()->subDays(20), 'exp' => $now->copy()->addMonths(5)],
                    ['name' => 'Miến trộn Phú Hương', 'cat_id' => 1, 'gtin' => '8932026004005', 'batch' => 'AC-MT-01', 'mfg' => $now->copy()->subDays(5), 'exp' => $now->copy()->addMonths(5)],
                ]
            ],
            [
                'name' => 'Công ty Dược Hậu Giang',
                'email' => 'admin@dhg.demo',
                'tax_code' => '6300044146',
                'address' => '288 Bis Nguyễn Văn Cừ, P. An Hòa, Q. Ninh Kiều, Cần Thơ',
                'products' => [
                    ['name' => 'Hapacol 500', 'cat_id' => 3, 'gtin' => '8932026005001', 'batch' => 'DHG-HP-01', 'mfg' => $now->copy()->subMonths(5), 'exp' => $now->copy()->addMonths(31)],
                    ['name' => 'DHG Vitamin C', 'cat_id' => 3, 'gtin' => '8932026005002', 'batch' => 'DHG-VC-01', 'mfg' => $now->copy()->subMonths(2), 'exp' => $now->copy()->addMonths(22)],
                    ['name' => 'Naturenz', 'cat_id' => 3, 'gtin' => '8932026005003', 'batch' => 'DHG-NT-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(35)],
                    ['name' => 'Bocalex', 'cat_id' => 3, 'gtin' => '8932026005004', 'batch' => 'DHG-BC-01', 'mfg' => $now->copy()->subDays(15), 'exp' => $now->copy()->addMonths(23)],
                    ['name' => 'Klamentin', 'cat_id' => 3, 'gtin' => '8932026005005', 'batch' => 'DHG-KL-01', 'mfg' => $now->copy()->subMonths(6), 'exp' => $now->copy()->addMonths(30)],
                ]
            ],
            [
                'name' => 'Công ty CP Bibica',
                'email' => 'admin@bibica.demo',
                'tax_code' => '0301833711',
                'address' => '443 Lý Thường Kiệt, P. 8, Q. Tân Bình, TP.HCM',
                'products' => [
                    ['name' => 'Bánh Chocopie Bibica', 'cat_id' => 1, 'gtin' => '8932026006001', 'batch' => 'BB-CP-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(11)],
                    ['name' => 'Kẹo cứng trái cây', 'cat_id' => 1, 'gtin' => '8932026006002', 'batch' => 'BB-KC-01', 'mfg' => $now->copy()->subDays(20), 'exp' => $now->copy()->addMonths(17)],
                    ['name' => 'Bánh trung thu Bibica', 'cat_id' => 1, 'gtin' => '8932026006003', 'batch' => 'BB-TT-01', 'mfg' => $now->copy()->subDays(5), 'exp' => $now->copy()->addMonths(1)],
                    ['name' => 'Bánh quy bơ', 'cat_id' => 1, 'gtin' => '8932026006004', 'batch' => 'BB-BQ-01', 'mfg' => $now->copy()->subMonths(2), 'exp' => $now->copy()->addMonths(10)],
                    ['name' => 'Kẹo mềm Sumi', 'cat_id' => 1, 'gtin' => '8932026006005', 'batch' => 'BB-KM-01', 'mfg' => $now->copy()->subDays(10), 'exp' => $now->copy()->addMonths(11)],
                ]
            ],
            [
                'name' => 'Công ty CP Nhựa Duy Tân',
                'email' => 'admin@duytan.demo',
                'tax_code' => '0300705602',
                'address' => '298 Hồ Học Lãm, Phường An Lạc, Quận Bình Tân, TP.HCM',
                'products' => [
                    ['name' => 'Thùng nhựa Duy Tân 15L', 'cat_id' => 5, 'gtin' => '8932026007001', 'batch' => 'DT-TN-01', 'mfg' => $now->copy()->subMonths(3), 'exp' => $now->copy()->addYears(10)],
                    ['name' => 'Chai nhựa PET 500ml', 'cat_id' => 5, 'gtin' => '8932026007002', 'batch' => 'DT-CN-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addYears(5)],
                    ['name' => 'Hộp đựng thực phẩm Matsu', 'cat_id' => 5, 'gtin' => '8932026007003', 'batch' => 'DT-HT-01', 'mfg' => $now->copy()->subDays(15), 'exp' => $now->copy()->addYears(5)],
                    ['name' => 'Bình nước nhựa học sinh', 'cat_id' => 5, 'gtin' => '8932026007004', 'batch' => 'DT-BN-01', 'mfg' => $now->copy()->subDays(2), 'exp' => $now->copy()->addYears(5)],
                    ['name' => 'Thau nhựa gia dụng', 'cat_id' => 5, 'gtin' => '8932026007005', 'batch' => 'DT-TH-01', 'mfg' => $now->copy()->subMonths(4), 'exp' => $now->copy()->addYears(10)],
                ]
            ],
            [
                'name' => 'Công ty CP Sữa Mộc Châu',
                'email' => 'admin@mocchau.demo',
                'tax_code' => '2400120389',
                'address' => 'Thị trấn Nông trường Mộc Châu, Huyện Mộc Châu, Sơn La',
                'products' => [
                    ['name' => 'Sữa tươi Mộc Châu', 'cat_id' => 2, 'gtin' => '8932026008001', 'batch' => 'MC-ST-01', 'mfg' => $now->copy()->subDays(3), 'exp' => $now->copy()->addMonths(6)],
                    ['name' => 'Sữa chua nếp cẩm', 'cat_id' => 1, 'gtin' => '8932026008002', 'batch' => 'MC-SC-01', 'mfg' => $now->copy()->subDays(5), 'exp' => $now->copy()->addDays(40)],
                    ['name' => 'Sữa thanh trùng Mộc Châu', 'cat_id' => 2, 'gtin' => '8932026008003', 'batch' => 'MC-TT-01', 'mfg' => $now->copy()->subDays(1), 'exp' => $now->copy()->addDays(14)],
                    ['name' => 'Kem Mộc Châu', 'cat_id' => 2, 'gtin' => '8932026008004', 'batch' => 'MC-KE-01', 'mfg' => $now->copy()->subDays(20), 'exp' => $now->copy()->addMonths(6)],
                    ['name' => 'Sữa đặc Mộc Châu', 'cat_id' => 5, 'gtin' => '8932026008005', 'batch' => 'MC-SD-01', 'mfg' => $now->copy()->subMonths(1), 'exp' => $now->copy()->addMonths(11)],
                ]
            ],
        ];

        DB::beginTransaction();
        try {
            foreach ($enterprises as $ent) {
                // 1. Tạo Tài khoản Doanh nghiệp (Đã duyệt)
                $user = User::create([
                    'name' => $ent['name'],
                    'email' => $ent['email'],
                    'password' => $defaultPassword,
                    'role' => 'enterprise',
                    'status' => 'active', // Trạng thái 'active' dựa theo DB SQL Dump
                ]);

                // 2. Tạo Hồ sơ Doanh nghiệp (Profile)
                DB::table('user_profiles')->insert([
                    'user_id' => $user->id,
                    'company_name' => $ent['name'],
                    'tax_code' => $ent['tax_code'],
                    'address' => $ent['address'],
                    'contact_email' => $ent['email'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                // 3. Đổ danh sách 5 Sản phẩm và Lô hàng
                foreach ($ent['products'] as $prod) {
                    $product = Product::create([
                        'user_id' => $user->id,
                        'category_id' => $prod['cat_id'],
                        'gtin_code' => $prod['gtin'],
                        'name' => $prod['name'],
                        'company_name' => $ent['name'],
                        'is_authentic' => 1,
                    ]);

                    Batch::create([
                        'product_id' => $product->id,
                        'batch_code' => $prod['batch'],
                        'manufacturing_date' => $prod['mfg'],
                        'expiry_date' => $prod['exp'],
                        'quantity' => rand(1000, 5000), // Random số lượng
                        // 'status' => 'active',
                        'qr_code_url' => 'qrcodes/demo_qr.png', // Tạm gắn link giả cho Seeder
                        'created_by' => $user->id,
                    ]);
                }
            }
            DB::commit();
            $this->command->info('Tuyệt vời! Đã nạp thành công 8 Doanh nghiệp và 40 Sản phẩm vào Database!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Lỗi: ' . $e->getMessage());
        }
    }
}