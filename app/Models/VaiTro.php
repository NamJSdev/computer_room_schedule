<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;

    protected $table = 'vai_tros';

    protected $fillable = [
        'TenVaiTro',
        'MoTa',
    ];

    public function taiKhoans()
    {
        return $this->hasMany(TaiKhoan::class, 'VaiTroID');
    }

    public function phanQuyens()
    {
        return $this->hasMany(PhanQuyen::class, 'VaiTroID');
    }
}