<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markNotificationAsRead($id)
    {
        $notification = Notification::find($id);
        if ($notification && $notification->TaiKhoanID == auth()->user()->id) {
            $notification->markAsRead();
        }

        return redirect()->back();
    }
    public function deleteAll()
    {
        Notification::where('TaiKhoanID', Auth::id())->delete();
        return redirect()->back()->with('success', 'Tất cả thông báo đã được xóa.');
    }
}