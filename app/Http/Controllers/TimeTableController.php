<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\PhongMay;
use App\Models\ThoiKhoaBieu;
use App\Models\ThoiKhoaBieuCraw;
use App\Models\TietHoc;
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
            ->where('Status', "approved")
            ->where('HocKyID', $hocKyId)
            ->where('PhongMayID', $phongMayId)
            ->get();

        // Lấy thời gian bắt đầu và kết thúc của học kỳ từ cơ sở dữ liệu
        $hocKy = HocKy::findOrFail($hocKyId);

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

    public function getTimetableCrawled(Request $request)
    {
        $hocKyId = $request->HocKy;
        $phongMayId = $request->PhongMay;

        // Truy vấn thời gian bắt đầu và kết thúc của học kỳ
        $hocKy = HocKy::find($hocKyId);
        $phongMay = PhongMay::find($phongMayId)->TenPhong;
        
        if (!$hocKy) {
            return response()->json(['error' => 'Học kỳ không tồn tại'], 404);
        }

        $startDateHK = Carbon::parse($hocKy->ThoiGianBatDau); // Giả sử cột ThoiGianBatDau trong bảng học kỳ lưu thời gian bắt đầu
        $endDateHK = Carbon::parse($hocKy->ThoiGianKetThuc); // Giả sử cột ThoiGianKetThuc trong bảng học kỳ lưu thời gian kết thúc

        // Tính toán số thứ tự các tuần trong khoảng thời gian học kỳ
        $weeks = [];
        $currentDate = $startDateHK->copy();
        $weekNumber = 1;

        while ($currentDate->lessThanOrEqualTo($endDateHK)) {
            $weeks[$weekNumber] = [
                'start_date' => $currentDate->copy()->startOfWeek()->format('Y-m-d'),
                'end_date' => $currentDate->copy()->endOfWeek()->format('Y-m-d')
            ];

            $currentDate->addWeek();
            $weekNumber++;
        }

        $timeTableCrawData = ThoiKhoaBieuCraw::where('HocKyID', $hocKyId)
            ->where('PhongMayID', $phongMayId)
            ->get();

        // Mapping giữa thứ và số thứ tự trong tuần
        $dayMapping = [
            'CN' => 0,
            'Hai' => 1,
            'Ba' => 2,
            'Tư' => 3,
            'Năm' => 4,
            'Sáu' => 5,
            'Bảy' => 6,
        ];

        // Mảng để lưu kết quả
        $events = [];

        // Xử lý từng bản ghi
        foreach ($timeTableCrawData as $record) {
            $thu = $dayMapping[$record['Thu']];
            $tuanHocs = explode(',', $record['TuanHoc']);
            $tietHocs = explode(',', $record['TietHoc']);

            // Lấy tiết đầu và tiết cuối
            $tietDau = intval($tietHocs[0]);
            $tietCuoi = intval(end($tietHocs));

            // Truy vấn thời gian bắt đầu của tiết đầu và thời gian kết thúc của tiết cuối từ bảng TietHoc
            $tietDauThoiGian = TietHoc::where('Ten', $tietDau)->first();
            $tietCuoiThoiGian = TietHoc::where('Ten', $tietCuoi)->first();

            if ($tietDauThoiGian && $tietCuoiThoiGian) {
                $thoiGianBatDau = Carbon::parse($tietDauThoiGian->ThoiGianBatDau);
                $thoiGianKetThuc = Carbon::parse($tietCuoiThoiGian->ThoiGianKetThuc);



                foreach ($tuanHocs as $tuan) {
                    if (isset($weeks[$tuan])) {
                        $weekStartDate = Carbon::parse($weeks[$tuan]['start_date']);
                        $dayDate = $weekStartDate->addDays($thu);

                        // Ghép ngày với thời gian bắt đầu và kết thúc
                        $startDateTime = $dayDate->format('Y-m-d') . 'T' . $thoiGianBatDau->format('H:i:s');
                        $endDateTime = $dayDate->format('Y-m-d') . 'T' . $thoiGianKetThuc->format('H:i:s');

                        $events[] = [
                            'id' => $record['id'],
                            'title' => $record['TenMonHoc'],
                            'start' =>  $startDateTime,
                            'end' => $endDateTime,
                            'allDay' => false,
                            'lecturer' => "N/A",
                            'maMonHoc' => $record['MaMonHoc'],
                            'nhomMonHoc' => $record['NhomMH'],
                            'siSo' => 'N/A',
                            'room' => $phongMay,
                            'lesson' => $record['TietHoc'], // Gộp các tiết học thành chuỗi
                            'class' => $record['Lop'],
                        ];
                    }
                }
            }
        }
        // dd($events);
         // Trả về kết quả dưới dạng JSON
        return response()->json([
            'events' => $events,
            'weeks' => $weeks // Trả về cả mảng tuần
        ]);
    }
}