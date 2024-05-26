<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function getGiangVienAccount()
    {
        $giangViens = TaiKhoan::where('VaiTroID', 2)->get();
        return view('pages.gv', compact('giangViens'));
    }
    public function createAccountGV(Request $request)
    {
        // Lấy dữ liệu từ request và tạo mới tài khoản trong cơ sở dữ liệu
        $data = new TaiKhoan();
        $data->Email = $request->email;
        $data->TenNguoiDung = $request->user_name;
        $data->MatKhau = $request->password;
        $data->VaiTroID = 2;
        $data->save();

        // Trả về một phản hồi JSON cho client
        return redirect()->route('taikhoangiangvien')->with('success', 'Tài Khoản đã được tạo thành công.');
    }
    public function updateAccountGV(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);
        $password = $request->password;
        $data = TaiKhoan::find($request->id);
        $data->TenNguoiDung = $request->user_name;
        $data->Email = $request->email;
        if($password != ""){
            $data->MatKhau = $password;
        }
        $data->save();

        return redirect()->route('taikhoangiangvien')->with('success', 'Tài Khoản đã được cập nhật thành công.');
    }
    public function deleteAccountGV(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        TaiKhoan::destroy($request->id);

        return redirect()->route('taikhoangiangvien')->with('success', 'Tài khoản đã được xóa thành công.');
    }
    public function getAdminAccount()
    {
        $datas = TaiKhoan::where('VaiTroID', 1)->get();
        return view('pages.admin', compact('datas'));
    }
    public function createAccountAdmin(Request $request)
    {
        // Lấy dữ liệu từ request và tạo mới tài khoản trong cơ sở dữ liệu
        $data = new TaiKhoan();
        $data->Email = $request->email;
        $data->TenNguoiDung = $request->user_name;
        $data->MatKhau = $request->password;
        $data->VaiTroID = 1;
        $data->save();

        // Trả về một phản hồi JSON cho client
        return redirect()->route('taikhoanhethong')->with('success', 'Tài Khoản đã được tạo thành công.');
    }
    public function updateAccountAdmin(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'user_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
        ]);
        $password = $request->password;
        $data = TaiKhoan::find($request->id);
        $data->TenNguoiDung = $request->user_name;
        $data->Email = $request->email;
        if($password != ""){
            $data->MatKhau = $password;
        }
        $data->save();

        return redirect()->route('taikhoanhethong')->with('success', 'Tài Khoản đã được cập nhật thành công.');
    }
    public function deleteAccountAdmin(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        TaiKhoan::destroy($request->id);

        return redirect()->route('taikhoanhethong')->with('success', 'Tài khoản đã được xóa thành công.');
    }
}