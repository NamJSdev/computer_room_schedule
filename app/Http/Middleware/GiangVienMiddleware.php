<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GiangVienMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->VaiTroID == 2) {
            return $next($request);
        }
        
        return redirect()->route('404')->with('error', 'Bạn không có quyền truy cập.');
    }
}