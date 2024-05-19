<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class TaiKhoan extends Authenticatable
{
    use HasFactory;

    protected $table = 'tai_khoans';

    protected $fillable = [
        'Email',
        'TenNguoiDung',
        'MatKhau',
        'VaiTroID',
    ];

    protected $hidden = [
        'MatKhau',
    ];

    public function setMatKhauAttribute($value)
    {
        $this->attributes['MatKhau'] = Hash::make($value);
    }

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'VaiTroID');
    }

    public function dangKyLopHocs()
    {
        return $this->hasMany(DangKyLopHoc::class, 'TaiKhoanID');
    }
}