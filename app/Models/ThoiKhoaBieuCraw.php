<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThoiKhoaBieuCraw extends Model
{
    use HasFactory;
    protected $table = 'thoi_khoa_bieu_craws';

    protected $fillable = [
        'PhongMayID',
        'HocKyID',
        'TenMonHoc',
        'MaMonHoc',
        'Lop',
        'NhomMH',
        'Thu',
        'SoTinChi',
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
}