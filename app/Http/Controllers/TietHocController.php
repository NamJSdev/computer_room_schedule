<?php

namespace App\Http\Controllers;

use App\Models\TietHoc;
use Illuminate\Http\Request;

class TietHocController extends Controller
{
    // Function to display rooms
    public function index()
    {
        $items = TietHoc::all(); // Lấy tất cả các phòng
        return view('pages.tietHoc', ['items' => $items]); // Truyền dữ liệu tới view
    }

    public function addTiet(Request $request)
    {
    //Lấy dữ liệu từ request và tạo mới phòng máy trong cơ sở dữ liệu
        $item = new TietHoc();
        $item->Ten = $request->ten;
        $item->ThoiGianBatDau = date('Y-m-d H:i:s', strtotime($request->thoi_gian_bat_dau));
        $item->ThoiGianKetThuc = date('Y-m-d H:i:s', strtotime($request->thoi_gian_ket_thuc));
        $item->save();

        // Trả về một phản hồi JSON cho client
        return redirect()->route('tiethoc')->with('success', 'Tiết Học đã được thêm thành công.');
    }

    // // Phương thức để cập nhật thông tin phòng máy
    // public function updateRoom(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|integer',
    //         'TenPhong' => 'required|string|max:255',
    //     ]);

    //     $room = PhongMay::find($request->id);
    //     $room->TenPhong = $request->TenPhong;
    //     $room->save();

    //     return redirect()->route('phongmay')->with('success', 'Phòng máy đã được cập nhật thành công.');
    // }

    // // Phương thức để xóa phòng máy
    // public function deleteRoom(Request $request)
    // {
    //     $request->validate([
    //         'id' => 'required|integer',
    //     ]);

    //     PhongMay::destroy($request->id);

    //     return redirect()->route('phongmay')->with('success', 'Phòng máy đã được xóa thành công.');
    // }
}