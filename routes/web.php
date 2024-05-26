<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrawlerController;
use App\Http\Controllers\OutPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ScrapeController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\TietHocController;
use Illuminate\Support\Facades\Route;

//Craw
Route::get('/laydulieu', [CrawlerController::class, 'index'])->name('craw');
Route::get('/schedules', [ScrapeController::class, 'index']);
Route::post('/scrape', [ScrapeController::class, 'scrape']);


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