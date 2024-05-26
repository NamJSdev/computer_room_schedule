<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use Illuminate\Http\Request;

class SemesterController extends Controller
{
     // Function to display rooms
     public function index()
     {
         $items = HocKy::all(); // Lấy tất cả các học kỳ
         return view('pages.semester', ['items' => $items]); // Truyền dữ liệu tới view
     }
 
     public function addHocKy(Request $request)
     {
     //Lấy dữ liệu từ request và tạo mới phòng máy trong cơ sở dữ liệu
         $item = new HocKy();
         $item->TenHK = $request->ten;
         $item->ThoiGianBatDau = date('Y-m-d H:i:s', strtotime($request->thoi_gian_bat_dau));
         $item->ThoiGianKetThuc = date('Y-m-d H:i:s', strtotime($request->thoi_gian_ket_thuc));
         $item->save();
 
         // Trả về một phản hồi JSON cho client
         return redirect()->route('hocky')->with('success', 'Học kỳ đã được thêm thành công.');
     }
 
     // Phương thức để cập nhật thông tin học kỳ
     public function updateHocKy(Request $request)
     {
         $request->validate([
             'id' => 'required|integer',
             'ten' => 'required|string|max:255',
             'thoi_gian_bat_dau' => 'required',
             'thoi_gian_ket_thuc' => 'required'
         ]);
 
         $item = HocKy::find($request->id);
         $item->TenHK = $request->ten;
         $item->ThoiGianBatDau = date('Y-m-d H:i:s', strtotime($request->thoi_gian_bat_dau));
         $item->ThoiGianKetThuc = date('Y-m-d H:i:s', strtotime($request->thoi_gian_ket_thuc));
         $item->save();
 
         return redirect()->route('hocky')->with('success', 'Học kỳ đã được cập nhật thành công.');
     }
 
     // Phương thức để xóa học kỳ
     public function deleteHocKy(Request $request)
     {
         $request->validate([
             'id' => 'required|integer',
         ]);
 
         HocKy::destroy($request->id);
 
         return redirect()->route('hocky')->with('success', 'Học kỳ đã được xóa thành công.');
     }
}