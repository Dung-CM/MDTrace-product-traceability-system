<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Nhớ thêm dòng này
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Nông sản / Thực phẩm', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Đồ uống / Giải khát', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Dược phẩm / Y tế', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Đồ chơi / Trẻ em', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['name' => 'Hàng tiêu dùng khác', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('categories')->insert($categories);
    }
}