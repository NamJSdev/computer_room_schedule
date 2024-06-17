<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DuskTestController extends Controller
{
    public function runDuskTest(Request $request)
    {
        try {
            $exitCode = Artisan::call('dusk');
            $output = Artisan::output();
            // Chạy lệnh Artisan Dusk


            // Kiểm tra kết quả của lệnh
            if ($exitCode === 0) {
                return redirect()->route('showDataCrawler')->with('success', 'Đã chạy thành công lấy dữ liệu từ trang Đào Tạo.');
            } else {
                return redirect()->route('showDataCrawler')->with('error', 'Có lỗi xảy ra khi chạy lệnh Dusk.');
            }
        } catch (\Exception $e) {
            dd('1');
            // Xử lý ngoại lệ và trả về thông báo lỗi
            return redirect()->route('showDataCrawler')->with('error', 'Có lỗi xảy ra khi chạy Dusk test: ' . $e->getMessage());
        }
    }

    // private function replaceHocKyInTest($hocKy)
    // {
    //     // Đường dẫn tới file test của bạn
    //     $testFilePath = base_path('tests/Browser/YourTestFile.php');

    //     // Đọc nội dung từ file
    //     $content = file_get_contents($testFilePath);

    //     // Thực hiện thay thế học kỳ trong nội dung file
    //     $newContent = str_replace('YourHocKyPlaceholder', $hocKy, $content);

    //     // Lưu nội dung mới vào file
    //     file_put_contents($testFilePath, $newContent);
    // }
}