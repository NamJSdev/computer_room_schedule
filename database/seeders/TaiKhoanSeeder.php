<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash;

class TaiKhoanSeeder extends Seeder
{
    public function run()
    {
        TaiKhoan::create([
            'Email' => 'admin@gmail.com',
            'TenNguoiDung' => 'admin',
            'MatKhau' => '12345678', 
            'VaiTroID' => 1, // Giả sử VaiTroID 1 là Quản trị viên
        ]);

        TaiKhoan::create([
            'Email' => 'gv@gmail.com',
            'TenNguoiDung' => 'Hiếu Lê',
            'MatKhau' => '12341234', 
            'VaiTroID' => 2, // Giả sử VaiTroID 2 là Giáo viên
        ]);
    }
}