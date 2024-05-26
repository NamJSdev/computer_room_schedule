<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\PhongMay;
use Illuminate\Http\Request;

class OutPageController extends Controller
{
    public function index()
    {
        return view("pages.out-page");
    }
}