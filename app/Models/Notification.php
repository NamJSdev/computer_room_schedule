<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'TaiKhoanID', 'message', 'read','created_at'
    ];

    public function user()
    {
        return $this->belongsTo(TaiKhoan::class);
    }
    public function markAsRead()
    {
        $this->read = true;
        $this->save();
    }

}