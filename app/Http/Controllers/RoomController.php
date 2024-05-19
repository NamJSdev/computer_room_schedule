<?php

namespace App\Http\Controllers;

use App\Models\PhongMay;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Function to display rooms
    public function index()
    {
        $rooms = PhongMay::all(); // Lấy tất cả các phòng
        return view('pages.room', ['rooms' => $rooms]); // Truyền dữ liệu tới view
    }

    public function addRoom(Request $request)
    {
        // Lấy dữ liệu từ request và tạo mới phòng máy trong cơ sở dữ liệu
        $room = new PhongMay();
        $room->TenPhong = $request->ten_phong;
        $room->save();

        // Trả về một phản hồi JSON cho client
        return redirect()->route('phongmay')->with('success', 'Phòng máy đã được thêm thành công.');
    }

    // Phương thức để cập nhật thông tin phòng máy
    public function updateRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'TenPhong' => 'required|string|max:255',
        ]);

        $room = PhongMay::find($request->id);
        $room->TenPhong = $request->TenPhong;
        $room->save();

        return redirect()->route('phongmay')->with('success', 'Phòng máy đã được cập nhật thành công.');
    }

    // Phương thức để xóa phòng máy
    public function deleteRoom(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        PhongMay::destroy($request->id);

        return redirect()->route('phongmay')->with('success', 'Phòng máy đã được xóa thành công.');
    }
}