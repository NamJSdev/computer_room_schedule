<?php

namespace App\Providers;

use App\Models\DangKyLopHoc;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.sidebar', function ($view) {
            $countPendingThoiKhoaBieu = $this->countPendingThoiKhoaBieu();
            $view->with('countPendingThoiKhoaBieu', $countPendingThoiKhoaBieu);
        });

        View::composer('layouts.header', function ($view) {
            // Kiểm tra xem người dùng đã đăng nhập hay chưa
            if (auth()->check()) {
                $userId = auth()->user()->id;
                $notifications = Notification::where('TaiKhoanID', $userId)->get();
                $unreadCount = Notification::where('TaiKhoanID', $userId)->where('read', false)->count();

                // Định dạng ngày tháng cho từng thông báo
                foreach ($notifications as $notification) {
                    $notification->formatted_date = Carbon::parse($notification->created_at)->format('d-m-Y H:i');
                }
                $view->with([
                    'notifications' => $notifications,
                    'unreadCount' => $unreadCount
                ]);
            } else {
                // Nếu người dùng chưa đăng nhập, chia sẻ giá trị mặc định
                $view->with([
                    'notifications' => [],
                    'unreadCount' => 0
                ]);
            }
        });
    }

    public function countPendingThoiKhoaBieu()
    {
        // Sử dụng câu truy vấn Laravel để đếm số lượng các thời khóa biểu chưa được duyệt
        $count = DangKyLopHoc::where('Status', 'pending')->count();

        // Trả về số lượng đã đếm được
        return $count;
    }
}