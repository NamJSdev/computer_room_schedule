<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ScrapeController extends Controller
{
    public function index()
    {
        return view('schedules.index');
    }

    public function scrape(Request $request)
    {
        Artisan::call('scrape:schedule');
        $output = Artisan::output();
        return response()->json(['html' => $output]);
    }
}