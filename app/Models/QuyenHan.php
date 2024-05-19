<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuyenHan extends Model
{
    use HasFactory;

    protected $table = 'quyen_hans';

    protected $fillable = [
        'TenQuyenHan',
        'MoTa',
    ];

    public function phanQuyens()
    {
        return $this->hasMany(PhanQuyen::class, 'QuyenHanID');
    }
}