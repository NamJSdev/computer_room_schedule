<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhanQuyen extends Model
{
    use HasFactory;

    protected $table = 'phan_quyens';

    protected $fillable = [
        'VaiTroID',
        'QuyenHanID',
    ];

    public function vaiTro()
    {
        return $this->belongsTo(VaiTro::class, 'VaiTroID');
    }

    public function quyenHan()
    {
        return $this->belongsTo(QuyenHan::class, 'QuyenHanID');
    }
}