<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\ThoiKhoaBieu;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimeTableController extends Controller
{
    public function getTimetable(Request $request)
    {
        $hocKyId = $request->HocKy;
        $phongMayId = $request->PhongMay;

        // Lấy dữ liệu thời khóa biểu dựa trên Học kỳ và Phòng máy
        $timetableData = ThoiKhoaBieu::with('phongMay', 'tietHocs')
            ->where('Status',"approved")
            ->where('HocKyID', $hocKyId)
            ->where('PhongMayID', $phongMayId)
            ->get();

        // Lấy thời gian bắt đầu và kết thúc của học kỳ từ cơ sở dữ liệu
        $hocKy = HocKy::findOrFail($hocKyId);
        $startDate = Carbon::parse($hocKy->ThoiGianBatDau);
        $endDate = Carbon::parse($hocKy->ThoiGianKetThuc);

        // Định dạng dữ liệu để đổ vào FullCalendar
        $events = [];
        foreach ($timetableData as $tkb) {
            $tietHocs = $tkb->tietHocs;
            $minStart = null;
            $maxEnd = null;
            $lessons = []; // Reset mảng cho mỗi sự kiện mới
            $date = Carbon::parse($tkb->NgayHoc);
            foreach ($tietHocs as $tiet) {
                $startTime = Carbon::parse($tiet->ThoiGianBatDau); // Lấy giờ bắt đầu
                $endTime = Carbon::parse($tiet->ThoiGianKetThuc); // Lấy giờ kết thúc
                        
                // Ghép ngày với thời gian bắt đầu và kết thúc
                $startDateTime = $date->format('Y-m-d') . 'T' . $startTime->format('H:i:s');
                $endDateTime = $date->format('Y-m-d') . 'T' . $endTime->format('H:i:s');

                if ($minStart === null || $startTime->lessThan($minStart)) {
                    $minStart = $startTime;
                }

                if ($maxEnd === null || $endTime->greaterThan($maxEnd)) {
                    $maxEnd = $endTime;
                }
                $lessons[] = $tiet->Ten;
            }

            if ($minStart && $maxEnd) {
                $events[] = [
                    'title' => $tkb->TenMonHoc,
                    'start' =>  $date->format('Y-m-d') . 'T' . $minStart->format('H:i:s'),
                    'end' => $date->format('Y-m-d') . 'T' . $maxEnd->format('H:i:s'),
                    'allDay' => false,
                    'lecturer' => $tkb->GiangVien,
                    'class' => $tkb->Lop,
                    'maMonHoc' => $tkb->MaMonHoc,
                    'nhomMonHoc' => $tkb->NhomMH,
                    'siSo' => $tkb->SiSo,
                    'id' => $tkb->id,
                    'room' => $tkb->phongMay->TenPhong,
                    'lesson' => implode(', ', $lessons), // Gộp các tiết học thành chuỗi
                ];
            }
        }
        
        return response()->json($events);
    }
}