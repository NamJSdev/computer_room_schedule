<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\Notification;
use App\Models\PhongMay;
use App\Models\TietHoc;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $hocKys = HocKy::all();
        $phongMays = PhongMay::all();
        $tietHocs = TietHoc::all();

        return view("pages.index",compact('hocKys', 'phongMays','tietHocs'));
    }
    public function roomPage()
    {
        return view("pages.room");
    }
}