<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrawlerController;
use App\Http\Controllers\DangKyController;
use App\Http\Controllers\DuskTestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OutPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\TietHocController;
use App\Http\Controllers\TimeTableController;
use Illuminate\Support\Facades\Route;

//Craw data
Route::get('/crawlerDataTKB', [CrawlerController::class, 'crawlerIndex'])->name('showDataCrawler')->middleware('admin');
Route::post('/capNhatDuLieu', [CrawlerController::class, 'index'])->name('updata-data-craw')->middleware('admin');
Route::get('/search', [CrawlerController::class, 'searchData'])->name('searchDataCraw')->middleware('admin');
Route::post('/deleteCrawler', [CrawlerController::class, 'deleteData'])->name('delete-tkb-craw')->middleware('admin');



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [PageController::class, 'index'])->name('thoikhoabieu');
Route::get('/khong-co-quyen-truy-cap', [OutPageController::class, 'index'])->name('404');

// Route Phong May
Route::get('/phongmay', [RoomController::class, 'index'])->name('phongmay')->middleware('admin');
// Route cho việc thêm phòng máy mới
Route::post('/themPhong', [RoomController::class, 'addRoom'])->name('add-room')->middleware('admin');
// Route cho việc cập nhật thông tin phòng máy
Route::post('/suaPhong', [RoomController::class, 'updateRoom'])->name('update-room')->middleware('admin');
// Route cho việc xóa phòng máy
Route::post('/xoaPhong', [RoomController::class, 'deleteRoom'])->name('delete-room')->middleware('admin');

//Route Tiet Hoc
Route::get('/tiethoc', [TietHocController::class, 'index'])->name('tiethoc')->middleware('admin');
// Route cho việc thêm tiết học
Route::post('/themTiet', [TietHocController::class, 'addTiet'])->name('add-tiet')->middleware('admin');
// Route cho việc cập nhật thông tin tiết học
Route::post('/suaTiet', [TietHocController::class, 'updateTiet'])->name('update-tiet')->middleware('admin');
// Route cho việc xóa tiết học
Route::post('/xoaTiet', [TietHocController::class, 'deleteTiet'])->name('delete-tiet')->middleware('admin');

//Route Học Kỳ
Route::get('/hocky', [SemesterController::class, 'index'])->name('hocky')->middleware('admin');
// Route cho việc thêm học kỳ
Route::post('/themHocKy', [SemesterController::class, 'addHocKy'])->name('add-hoc-ky')->middleware('admin');
// Route cho việc cập nhật thông tin học kỳ
Route::post('/suaHocKy', [SemesterController::class, 'updateHocKy'])->name('update-hoc-ky')->middleware('admin');
// Route cho việc xóa học kỳ
Route::post('/xoaHocKy', [SemesterController::class, 'deleteHocKy'])->name('delete-hoc-ky')->middleware('admin');

//Route Tài Khoản Giảng Viên
Route::get('/tai-khoan-giang-vien', [AccountController::class, 'getGiangVienAccount'])->name('taikhoangiangvien')->middleware('admin');
// Route cho việc tạo tài khoản giảng viên
Route::post('/taotaikhoangiangvien', [AccountController::class, 'createAccountGV'])->name('create-account-gv')->middleware('admin');
// Route cho việc cập nhật thông tin tài khoản giảng viên
Route::post('/suataikhoangv', [AccountController::class, 'updateAccountGV'])->name('update-account-gv')->middleware('admin');
// Route cho việc xóa học kỳ
Route::post('/xoataikhoangv', [AccountController::class, 'deleteAccountGV'])->name('delete-account-gv')->middleware('admin');

//Route Tài Khoản Hệ Thống
Route::get('/tai-khoan-he-thong', [AccountController::class, 'getAdminAccount'])->name('taikhoanhethong')->middleware('admin');
// Route cho việc tạo tài khoản hệ thống
Route::post('/taotaikhoanhethong', [AccountController::class, 'createAccountAdmin'])->name('create-account-admin')->middleware('admin');
// Route cho việc cập nhật thông tin tài khoản hệ thống
Route::post('/suataikhoanhethong', [AccountController::class, 'updateAccountAdmin'])->name('update-account-admin')->middleware('admin');
// Route cho việc xóa tài khoản
Route::post('/xoataikhoanadmin', [AccountController::class, 'deleteAccountAdmin'])->name('delete-account-admin')->middleware('admin');

//Route cho việc đăng ký thời khóa biểu
Route::post('/dang-ky-thoi-khoa-bieu', [DangKyController::class, 'dangKy'])->name('dang-ky-thoi-khoa-bieu')->middleware('auth');
//Route lấy danh sách thời khóa biểu đăng ký
Route::get('/duyetTKB', [DangKyController::class, 'index'])->name('danh-sach-dang-ky-tkb')->middleware('admin');
//Route duyệt thời khóa biểu
Route::post('/duyet-thoi-khoa-bieu-dang-ky', [DangKyController::class, 'duyet'])->name('approve-tkb-admin')->middleware('admin');
//Route từ chối duyệt thời khóa biểu
Route::post('/tu-choi-duyet-thoi-khoa-bieu-dang-ky', [DangKyController::class, 'reject'])->name('reject-tkb-admin')->middleware('admin');

//Xác nhận đã đọc thông báo
Route::get('/notifications/read/{id}', [NotificationController::class, 'markNotificationAsRead'])->name('notifications.read')->middleware('auth');
// Xóa tất cả thông báo
Route::get('/notifications/delete-all', [NotificationController::class, 'deleteAll'])->name('notifications.deleteAll')->middleware('auth');

//Route lấy danh sách đăng ký thời khóa biểu của giảng viên
Route::get('/thoiKhoaBieuDangKy', [DangKyController::class, 'getListForGV'])->name('danh-sach-tkb-giangvien')->middleware('giangvien');
Route::post('/xoaThoiKhoaBieuDangKy', [DangKyController::class, 'delete'])->name('xoa-dang-ky-tkb-giangvien')->middleware('giangvien');

//Route lấy danh sách đăng ký thời khóa biểu cho admin
Route::get('/listTKBDK', [DangKyController::class, 'getListForAdmin'])->name('ds-tkb-dang-ky-tk')->middleware('admin');
Route::post('/xoaThoiKhoaBieuDangKyForAdmin', [DangKyController::class, 'deleteForAdmin'])->name('xoa-dang-ky-tkb-for-admin')->middleware('auth');

// Lấy dữ liệu thời khóa biểu
Route::post('/get-timetable', [TimeTableController::class, 'getTimetable'])->name('get-timetable');
Route::post('/get-timetable-crawled', [TimeTableController::class, 'getTimetableCrawled'])->name('get-timetable-crawled');

// Cào data
Route::post('/run-dusk-test', [DuskTestController::class, 'runDuskTest'])->name('run.dusk.test')->middleware('admin');