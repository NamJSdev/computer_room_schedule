<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DangKyLopHoc extends Model
{
    use HasFactory;

    protected $table = 'dang_ky_lop_hocs';

    protected $fillable = [
        'NgayDangKy',
        'ThoiKhoaBieuID',
        'TaiKhoanID',
        'Status',
    ];

    public function thoiKhoaBieu()
    {
        return $this->belongsTo(ThoiKhoaBieu::class, 'ThoiKhoaBieuID');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'TaiKhoanID');
    }
}