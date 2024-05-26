<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\PhongMay;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $hocKys = HocKy::all();
        $phongMays = PhongMay::all();

        return view("pages.index",compact('hocKys', 'phongMays'));
    }
    public function roomPage()
    {
        return view("pages.room");
    }
}