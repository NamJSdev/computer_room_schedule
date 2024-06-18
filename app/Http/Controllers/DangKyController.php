<?php

namespace App\Http\Controllers;

use App\Models\DangKyLopHoc;
use App\Models\Notification;
use App\Models\ThoiKhoaBieu;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DangKyController extends Controller
{
    public function index()
    {

        $dangKyLopHocs = DangKyLopHoc::with('thoiKhoaBieu.phongMay', 'thoiKhoaBieu.tietHocs')->where('Status', 'pending')->orderBy('id', 'desc')->get();
        return view('pages.duyet-tkb', ['dangKyLopHocs' => $dangKyLopHocs]); // Truyền dữ liệu tới view
    }
    public function getListForGV()
    {

        // Lấy ID người dùng đang đăng nhập
        $taiKhoanId = Auth::id();

        $dangKyLopHocs = DangKyLopHoc::with('thoiKhoaBieu.phongMay', 'thoiKhoaBieu.tietHocs')->where('TaiKhoanID', $taiKhoanId)->orderBy('id', 'desc')->get();
        return view('pages.list-tkb-gv', ['dangKyLopHocs' => $dangKyLopHocs]); // Truyền dữ liệu tới view
    }
    public function getListForAdmin()
    {

        $dangKyLopHocs = DangKyLopHoc::with('thoiKhoaBieu.phongMay', 'thoiKhoaBieu.tietHocs')->where('Status', 'approved')->orderBy('id', 'desc')->get();
        return view('pages.list-tkb-admin', ['dangKyLopHocs' => $dangKyLopHocs]); // Truyền dữ liệu tới view
    }
    public function dangKy(Request $request)
    {
        // Validate input
        $request->validate([
            'TenMonHoc' => 'required|string|max:255',
            'MaMonHoc' => 'required|string|max:255',
            'Lop' => 'required|string|max:255',
            'NhomMH' => 'required|string|max:255',
            'SiSo' => 'required|integer',
            'TietHoc' => 'required|array',
            'GiangVien' => 'required|string|max:255',
        ]);

        // Tạo mới thời khóa biểu
        $thoiKhoaBieu = new ThoiKhoaBieu();
        $thoiKhoaBieu->PhongMayID = 1; // Thay thế bằng ID phòng máy tương ứng
        $thoiKhoaBieu->HocKyID = 1; // Thay thế bằng ID học kỳ tương ứng
        $thoiKhoaBieu->TenMonHoc = $request->TenMonHoc;
        $thoiKhoaBieu->MaMonHoc = $request->MaMonHoc;
        $thoiKhoaBieu->Lop = $request->Lop;
        $thoiKhoaBieu->NhomMH = $request->NhomMH;
        $thoiKhoaBieu->SiSo = $request->SiSo;
        $thoiKhoaBieu->PhongMayID = $request->PhongMay;
        $thoiKhoaBieu->HocKyID = $request->HocKy;
        $thoiKhoaBieu->NgayHoc = $request->NgayDangKy;
        $thoiKhoaBieu->GiangVien = $request->GiangVien;
        $thoiKhoaBieu->status = 'pending'; // Trạng thái mặc định là pending
        $thoiKhoaBieu->save();

        // Lưu tiết học vào bảng liên kết
        foreach ($request->TietHoc as $tietHocId) {
            DB::table('tiet_hoc_va_thoi_khoa_bieu')->insert([
                'ThoiKhoaBieuID' => $thoiKhoaBieu->id,
                'TietHocID' => $tietHocId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Lưu đăng ký lớp học
        $dangKyLopHoc = new DangKyLopHoc();
        $dangKyLopHoc->NgayDangKy = now();
        $dangKyLopHoc->ThoiKhoaBieuID = $thoiKhoaBieu->id;
        $dangKyLopHoc->TaiKhoanID = auth()->user()->id; // Hoặc ID của người dùng đăng nhập
        $dangKyLopHoc->Status = 'pending'; // Trạng thái mặc định là pending
        $dangKyLopHoc->save();

        // Điều hướng về trang chủ hoặc trang danh sách thời khóa biểu với thông báo thành công
        return redirect()->route('thoikhoabieu')->with('success', 'Đăng ký thời khóa biểu thành công! Chờ duyệt.');
    }

    public function duyet(Request $request)
    {
        // Tìm và cập nhật bản ghi trong bảng DangKyLopHoc
        $dangKyLopHoc = DangKyLopHoc::find($request->id);
        $dangKyLopHoc->Status = 'approved'; // Đặt trạng thái là đã duyệt
        $dangKyLopHoc->save();

        // Tìm và cập nhật tất cả các bản ghi trong bảng ThoiKhoaBieu có ThoiKhoaBieuID tương ứng
        $thoiKhoaBieu = ThoiKhoaBieu::where('id', $dangKyLopHoc->ThoiKhoaBieuID)->first();
        $thoiKhoaBieu->status = 'approved'; // Đặt trạng thái là đã duyệt
        $thoiKhoaBieu->save();

        // Tạo thông báo sau khi duyệt
        $this->createNotification($dangKyLopHoc->TaiKhoanID, 'Thời khóa biểu của bạn đã được duyệt. Hãy kiểm tra!');


        return redirect()->route('danh-sach-dang-ky-tkb')->with('success', 'Đã duyệt thời khóa biểu thành công.');
    }
    public function reject(Request $request)
    {
        // Tìm và cập nhật bản ghi trong bảng DangKyLopHoc
        $dangKyLopHoc = DangKyLopHoc::find($request->id);
        $dangKyLopHoc->Status = 'rejected'; // Đặt trạng thái là từ chối
        $dangKyLopHoc->save();

        // Tìm và cập nhật tất cả các bản ghi trong bảng ThoiKhoaBieu có ThoiKhoaBieuID tương ứng
        $thoiKhoaBieu = ThoiKhoaBieu::where('id', $dangKyLopHoc->ThoiKhoaBieuID)->first();
        $thoiKhoaBieu->status = 'rejected'; // Đặt trạng thái là từ chối
        $thoiKhoaBieu->save();

        // Tạo thông báo sau khi từ chối
        $this->createNotification($dangKyLopHoc->TaiKhoanID, 'Thời khóa biểu của bạn đã bị từ chối. Vui lòng kiểm tra lại!');

        return redirect()->route('danh-sach-dang-ky-tkb')->with('success', 'Đã từ chối duyệt thời khóa biểu thành công.');
    }
    public function createNotification($userId, $message)
    {
        Notification::create([
            'TaiKhoanID' => $userId,
            'message' => $message,
        ]);
    }

    public function delete(Request $request)
    {
        $id = $request->id;
        $dangKyLopHoc = DangKyLopHoc::find($id);

        if ($dangKyLopHoc) {
            if ($dangKyLopHoc->Status === 'pending' || $dangKyLopHoc->Status === 'rejected' || $dangKyLopHoc->Status === 'approved') {
                // Xóa các mục liên quan trong bảng tiet_hoc_va_thoi_khoa_bieu
                DB::table('tiet_hoc_va_thoi_khoa_bieu')->where('ThoiKhoaBieuID', $dangKyLopHoc->ThoiKhoaBieuID)->delete();

                // Xóa ThoiKhoaBieu tương ứng
                ThoiKhoaBieu::where('id', $dangKyLopHoc->ThoiKhoaBieuID)->delete();
            }

            // Xóa mục DangKyLopHoc
            $dangKyLopHoc->delete();

            return redirect()->route('danh-sach-tkb-giangvien')->with('success', 'Đã từ xóa đăng ký thành công.');
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy đăng ký.']);
    }
    public function deleteForAdmin(Request $request)
    {
        $id = $request->id;
        $dangKyLopHoc = DangKyLopHoc::find($id);

        if ($dangKyLopHoc) {
            if ($dangKyLopHoc->Status === 'approved') {
                // Xóa các mục liên quan trong bảng tiet_hoc_va_thoi_khoa_bieu
                DB::table('tiet_hoc_va_thoi_khoa_bieu')->where('ThoiKhoaBieuID', $dangKyLopHoc->ThoiKhoaBieuID)->delete();

                // Xóa ThoiKhoaBieu tương ứng
                ThoiKhoaBieu::where('id', $dangKyLopHoc->ThoiKhoaBieuID)->delete();
            }

            // Xóa mục DangKyLopHoc
            $dangKyLopHoc->delete();

            return redirect()->route('danh-sach-tkb-giangvien')->with('success', 'Đã xóa thời khóa biểu thành công.');
        }

        return response()->json(['success' => false, 'message' => 'Không tìm thấy đăng ký.']);
    }
}