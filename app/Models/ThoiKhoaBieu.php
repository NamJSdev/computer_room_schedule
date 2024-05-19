<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThoiKhoaBieu extends Model
{
    use HasFactory;

    protected $table = 'thoi_khoa_bieus';

    protected $fillable = [
        'PhongMayID',
        'HocKyID',
        'TenMonHoc',
        'MaMonHoc',
        'Lop',
        'NhomMH',
        'SiSo',
        'TuanHoc',
        'TietHoc',
        'status',
    ];

    public function phongMay()
    {
        return $this->belongsTo(PhongMay::class, 'PhongMayID');
    }

    public function hocKy()
    {
        return $this->belongsTo(HocKy::class, 'HocKyID');
    }

    public function dangKyLopHocs()
    {
        return $this->hasMany(DangKyLopHoc::class, 'ThoiKhoaBieuID');
    }
}