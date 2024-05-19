<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CrawlerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TietHocController;
use App\Models\TietHoc;
use Illuminate\Support\Facades\Route;

//Craw
Route::get('/laydulieu', [CrawlerController::class, 'index'])->name('craw');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.form')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/', [PageController::class, 'index'])->name('thoikhoabieu')->middleware('auth');

// Route Phong May
Route::get('/phongmay', [RoomController::class, 'index'])->name('phongmay')->middleware('auth');
// Route cho việc thêm phòng máy mới
Route::post('/themPhong', [RoomController::class, 'addRoom'])->name('add-room')->middleware('auth');;
// Route cho việc cập nhật thông tin phòng máy
Route::post('/suaPhong', [RoomController::class, 'updateRoom'])->name('update-room')->middleware('auth');;
// Route cho việc xóa phòng máy
Route::post('/xoaPhong', [RoomController::class, 'deleteRoom'])->name('delete-room')->middleware('auth');;


//Route Tiet Hoc
Route::get('/tiethoc', [TietHocController::class, 'index'])->name('tiethoc')->middleware('auth');
// Route cho việc thêm phòng máy mới
Route::post('/themTiet', [TietHocController::class, 'addTiet'])->name('add-tiet')->middleware('auth');