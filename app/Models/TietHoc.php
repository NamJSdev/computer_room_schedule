<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TietHoc extends Model
{
    use HasFactory;

    protected $table = 'tiet_hocs';

    protected $fillable = [
        'Ten',
        'ThoiGianBatDau',
        'ThoiGianKetThuc',
    ];
}