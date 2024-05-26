<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VaiTro;

class VaiTroSeeder extends Seeder
{
    public function run()
    {
        VaiTro::create([
            'TenVaiTro' => 'admin',
            'MoTa' => 'Quản Trị Viên',
        ]);

        VaiTro::create([
            'TenVaiTro' => 'giangvien',
            'MoTa' => 'Giảng Viên',
        ]);
    }
}