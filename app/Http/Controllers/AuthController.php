<?php

namespace App\Http\Controllers;

use App\Models\TaiKhoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // public function showRegisterForm()
    // {
    //     return view('auth.register');
    // }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    
        $user = TaiKhoan::where('Email', $request->email)->first();
    
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Email không tồn tại!',
            ]);
        }
        
        if ($request->password != $user->MatKhau) {
            throw ValidationException::withMessages([
                'password' => 'Mật khẩu không đúng!',
            ]);
        }
    
        Auth::login($user);
        // Lưu thông báo vào session
        Session::flash('success', 'Đăng nhập thành công!');
    
        return redirect()->route('thoikhoabieu');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }
}