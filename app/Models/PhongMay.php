<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhongMay extends Model
{
    use HasFactory;

    protected $table = 'phong_mays';

    protected $fillable = [
        'TenPhong',
    ];
}