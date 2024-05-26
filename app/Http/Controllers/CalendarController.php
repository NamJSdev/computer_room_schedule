<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HocKy;
use App\Models\PhongMay;

class CalendarController extends Controller
{
    // public function index()
    // {
    //     $hocKys = HocKy::all();
    //     $phongMays = PhongMay::all();

    //     return view('components.calendar', compact('hocKys', 'phongMays'));
    // }

    // public function fetchEvents(Request $request)
    // {
    //     $hocKy = $request->input('hoc_ky');
    //     $phongMay = $request->input('phong_may');

    //     $events = Event::where('hoc_ky_id', $hocKy)
    //                    ->where('phong_may_id', $phongMay)
    //                    ->get();

    //     return response()->json($events);
    // }
}