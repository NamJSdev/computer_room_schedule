<?php

namespace App\Http\Controllers;

use App\Models\HocKy;
use App\Models\PhongMay;
use App\Models\ThoiKhoaBieuCraw;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Http\Request;

class CrawlerController extends Controller
{
    protected function index(Request $request)
    {
        try {
            $html = file_get_contents(storage_path('app/public/crawled_page.html'));
            // Tạo một đối tượng Crawler từ chuỗi HTML
            $crawler = new Crawler($html);

            // Lọc ra các bảng có width="100%" và chứa thẻ td dữ liệu có chứa ký tự "CNTT-ND"
            $datas = $this->crawlTables($crawler);

            // dd($datas);
            // Lấy giá trị HocKyID từ request
            $hocKyID = $request->input('HocKy');

            // Chuyển đổi và lưu dữ liệu vào cơ sở dữ liệu
            $this->storeData($datas, $hocKyID);

            // Trả về một phản hồi cho client với thông báo thành công
            return redirect()->route('showDataCrawler')->with('success', 'Phòng máy đã được thêm thành công.');
        } catch (\Exception $e) {
            // Trả về một phản hồi cho client với thông báo không thành công
            return redirect()->route('showDataCrawler')->with('error', 'Có lỗi xảy ra khi thêm phòng máy: ' . $e->getMessage());
        }
    }

    protected function storeData($datas, $hocKyID)
    {

        // Xóa tất cả các bản ghi với HocKyID đã cho
        ThoiKhoaBieuCraw::where('HocKyID', $hocKyID)->delete();

        foreach ($datas as $data) {
            // Lấy tên phòng từ dữ liệu
            $tenPhong = $data[7];

            // Tìm ID của phòng máy theo tên phòng
            $phongMay = PhongMay::where('TenPhong', $tenPhong)->first();

            if ($phongMay) {
                $phongMayID = $phongMay->id;
            } else {
                // Nếu không tìm thấy phòng, bạn có thể chọn tạo mới phòng hoặc bỏ qua
                $phongMayID = null; // hoặc tạo mới
            }

            // Chuyển đổi "Tiết học" thành chuỗi
            $tietHocArray = $data[6];
            $tietHocString = $this->convertTietHocToString($tietHocArray);
            // dd($tietHocString);

            // Kiểm tra các giá trị đầu vào trước khi tạo bản ghi
            if (!isset($phongMayID) || !isset($hocKyID) || empty($data) || !isset($tietHocString)) {
                // Trả về lỗi hoặc xử lý lỗi ở đây
                dd('Thiếu dữ liệu đầu vào', compact('phongMayID', 'hocKyID', 'data', 'tietHocString'));
            }

            // Kiểm tra trùng lặp trước khi lưu vào cơ sở dữ liệu
            ThoiKhoaBieuCraw::firstOrCreate([
                'PhongMayID' => $phongMayID,
                'HocKyID' => $hocKyID,
                'TenMonHoc' => $data[1],
                'MaMonHoc' => $data[0],
                'Lop' => $data[4],
                'SoTinChi' => $data[3],
                'NhomMH' => $data[2],
                'Thu' => $data[5],
                'TuanHoc' => $data[8],
                'TietHoc' => $tietHocString,
            ]);
        }
    }
    protected function convertTietHocToString($tietHocArray)
    {
        $tietHocString = [];

        $start = (int)$tietHocArray[0];  // Tiết bắt đầu
        $length = (int)$tietHocArray[1];  // Số lượng tiết

        // Tạo dãy số từ start đến start + length - 1
        $end = $start + $length - 1;
        $range = range($start, $end);

        // Kết hợp dãy số này vào mảng $tietHocString
        $tietHocString = array_merge($tietHocString, $range);

        // Kiểm tra giá trị $tietHocString
        // dd($tietHocString);

        return implode(',', $tietHocString);
    }

    public function searchData(Request $request)
    {
        // Lấy từ khóa tìm kiếm từ input form
        $keyword = $request->input('keyword');

        $hocKys = HocKy::all();
        // Query dữ liệu từ bảng ThoiKhoaBieuCraw với điều kiện lọc theo từ khóa
        $datas = ThoiKhoaBieuCraw::where('TenMonHoc', 'like', '%' . $keyword . '%')
            ->orWhere('MaMonHoc', 'like', '%' . $keyword . '%')
            ->orWhere('Lop', 'like', '%' . $keyword . '%')
            ->orWhere('NhomMH', 'like', '%' . $keyword . '%')
            ->orWhere('Thu', 'like', '%' . $keyword . '%')
            ->orWhere('TuanHoc', 'like', '%' . $keyword . '%')
            ->orWhere('TietHoc', 'like', '%' . $keyword . '%')
            ->paginate(10); // Số lượng dòng dữ liệu trên mỗi trang

        // Trả về view với dữ liệu tìm được và từ khóa để hiển thị lại trong form tìm kiếm
        return view('pages.crawler', compact('datas', 'keyword','hocKys'));
    }


    public function crawlerIndex()
    {
        $hocKys = HocKy::all();
        $datas = ThoiKhoaBieuCraw::with('phongMay', 'hocKy')->paginate(10);
        return view('pages.crawler', compact('hocKys', 'datas')); // Truyền dữ liệu tới view
    }
    protected function crawlTables(Crawler $crawler)
    {
        $tablesData = [];
        $infoTablesData = []; // Mảng mới để lưu trữ dữ liệu đã ánh xạ
        // Lọc ra các bảng có width="100%"

        $crawler->filter('table[width="100%"]')->each(function (Crawler $tableCrawler) use (&$tablesData) {
            $tableData = [];
            // Lấy các ô trực tiếp của bảng chính và lưu trữ chúng
            $tableCrawler->filter('td, div')->each(function (Crawler $elementCrawler) use (&$tableData) {
                // Kiểm tra xem phần tử này có chứa bảng con hay không
                if ($elementCrawler->filter('table')->count() === 0) {
                    $elementContent = $elementCrawler->text();
                    // Kiểm tra nếu nội dung không rỗng thì lưu vào mảng
                    if (!empty(trim($elementContent)) && $elementContent !== "DSSV") {
                        $tableData['cells'][] = $elementContent;
                    }
                }
            });

            $tablesData[] = $tableData;
        });

        $targetArray = ["Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy", "CN"];

        $dayData = []; // Mảng kết hợp để lưu các mảng con
        $weekData = []; // Mảng để lưu các giá trị cuối cùng từ tablesData
        $roomData = [];
        $lessonsData = [];
        $lessonsDataConvert = [];

        foreach ($tablesData as $tableData) {
            $i = 0;
            $subArray = []; // Mảng con để lưu các giá trị của mỗi mảng con trong tablesData
            if (isset($tableData['cells'])) {
                foreach ($tableData['cells'] as $index => $value) {
                    // Kiểm tra nếu giá trị thuộc mảng đích
                    if (in_array($value, $targetArray)) {
                        $subArray[] = $value; // Thêm giá trị vào mảng con
                        $i++;
                        $lastValues = array_slice($tableData['cells'], -$i, $i);
                        unset($tableData['cells'][$index]); // Xóa giá trị đã được sử dụng để tránh trùng lặp
                    }
                }
                //Lấy ra i x 2 giá trị trong mảng từ dưới lên
                $roomValues = array_slice($tableData['cells'], -$i * 2, $i * 2);
                $lessonValues = array_slice($tableData['cells'], -$i * 4, $i * 4);
                // Bỏ đi i giá trị cuối cùng
                $roomData[] = array_slice($roomValues, 0, -$i);
                // Bỏ đi 2*i giá trị cuối cùng
                $lessonsData[] = array_slice($lessonValues, 0, - ($i * 2));
            }
            // Thêm mảng con vào mảng kết hợp
            $dayData[] = $subArray;
            $weekData[] = $lastValues;
        }
        foreach ($lessonsData as $subArray) {
            $halfLength = count($subArray) / 2; // Lấy chiều dài của mảng con và chia đôi
            $pairs = []; // Mảng chứa các cặp phần tử từ mảng con

            // Duyệt qua mảng con và tạo cặp phần tử
            for ($i = 0; $i < $halfLength; $i++) {
                // Lấy ra cặp phần tử từ mảng con
                $pair = [$subArray[$i], $subArray[$i + $halfLength]];
                $pairs[] = $pair;
            }

            // Thêm mảng con mới vào mảng chứa các mảng con sau khi xử lý
            $lessonsDataConvert[] = $pairs;
        }
        // Ánh xạ dữ liệu từ mảng hiện tại sang mảng mới
        foreach ($tablesData as $tableData) {
            $infoTablesData[] = $this->mapDataToFields($tableData);
        }

        //Hàm lấy ra vị trí của một giá trị xác định trong một mảng
        function findIndicesContainingSubstring($array, $substring)
        {
            $indices = [];
            foreach ($array as $index => $subArray) {
                $indicesInSubArray = [];
                foreach ($subArray as $elementIndex => $element) {
                    if (strpos($element, $substring) !== false) {
                        $indicesInSubArray[] = $elementIndex;
                    }
                }
                // Kiểm tra nếu có phần tử thỏa mãn điều kiện, mới thêm vào mảng chỉ số
                if (!empty($indicesInSubArray)) {
                    $indices[$index] = $indicesInSubArray;
                }
            }
            return $indices;
        }

        // Hàm lấy giá trị từ các vị trí tương ứng theo một mảng
        function getValuesAtIndices($array, $indices)
        {
            $values = [];
            foreach ($indices as $index => $indexArray) {
                $valuesInSubArray = [];
                foreach ($indexArray as $indexValue) {
                    // Kiểm tra nếu chỉ số tồn tại trong mảng con thì mới lấy giá trị
                    if (isset($array[$index][$indexValue])) {
                        $valuesInSubArray[] = $array[$index][$indexValue];
                    }
                }
                // Thêm mảng giá trị mới vào mảng kết quả nếu có giá trị
                if (!empty($valuesInSubArray)) {
                    $values[] = $valuesInSubArray;
                }
            }
            return $values;
        }

        //Hàm chuyển đồi convert tuần học (VD: '-----678901-2-45' --> "6,7,8,9,10,11,13,15,16")
        function convertWeekStringToNumbers($weekString)
        {
            $weekNumbers = []; // Mảng chứa các số tương ứng với vị trí của chúng trong chuỗi

            // Biến đếm vị trí của số
            $position = 0; // Bắt đầu từ 0 vì các tuần thường bắt đầu từ 1, không phải từ 0

            // Duyệt qua từng ký tự trong chuỗi
            for ($i = 0; $i < strlen($weekString); $i++) {
                $char = $weekString[$i]; // Lấy ký tự tại vị trí $i trong chuỗi

                // Kiểm tra nếu ký tự là một con số từ 1 đến 9 hoặc '-'
                if (ctype_digit($char) || $char == '-') {
                    if ($char == '-') {
                        // Nếu là dấu '-', tăng vị trí lên 1
                        $position++;
                    } else {
                        // Chuyển đổi ký tự sang số và thêm vào mảng
                        $weekNumbers[] = ++$position; // Tăng vị trí trước khi thêm vào mảng
                    }
                }
            }

            // Chuyển mảng số thành chuỗi, ngăn cách bằng dấu phẩy
            return implode(',', $weekNumbers);
        }

        //Chuyển đổi các tuần về dạng chuẩn
        foreach ($weekData as $index => $weekString) {
            $weekNumbers = []; // Mảng con để lưu các số của tuần học

            // Duyệt qua từng chuỗi trong mảng và chuyển đổi từng ký tự thành số
            foreach ($weekString as $char) {
                $number = convertWeekStringToNumbers($char); // Chuyển đổi ký tự thành số
                $weekNumbers[] = $number; // Thêm số vào mảng con
            }

            // Gán mảng con các số tuần vào lại phần tử tương ứng của $weekData
            $weekData[$index] = $weekNumbers;
        }

        // Đảm bảo rằng mảng $weekData đã được chuyển đổi thành mảng con các số tuần
        // Tìm các vị trí trong mảng của các phần tử có chứa chuỗi "CNTT-ND"
        $indicesContainingSubstring = findIndicesContainingSubstring($roomData, "CNTT-ND");
        // Lấy giá trị từ các vị trí tương ứng trong mỗi mảng con của weekData
        $weekFiltered = getValuesAtIndices($weekData, $indicesContainingSubstring);
        $lessonFiltered = getValuesAtIndices($lessonsDataConvert, $indicesContainingSubstring);
        $dayFiltered = getValuesAtIndices($dayData, $indicesContainingSubstring);
        $roomFiltered = getValuesAtIndices($roomData, $indicesContainingSubstring);

        // Gộp mảng
        $mergedArray = [];

        // Hàm để kết hợp các phần tử từ infoTablesData với các phần tử tương ứng từ extraArrays
        foreach ($infoTablesData as $aIndex => $aElement) {
            // Kiểm tra nếu có phần tử tương ứng trong các mảng bổ sung
            if (isset($dayFiltered[$aIndex], $lessonFiltered[$aIndex], $roomFiltered[$aIndex], $weekFiltered[$aIndex])) {
                // Lấy các phần tử tương ứng trong các mảng bổ sung
                $bElements = $dayFiltered[$aIndex];
                $cElements = $lessonFiltered[$aIndex];
                $dElements = $roomFiltered[$aIndex];
                $eElements = $weekFiltered[$aIndex];

                // Lặp qua từng phần tử tương ứng trong các mảng bổ sung
                foreach ($bElements as $index => $bElement) {
                    // Tạo một mảng mới chứa các phần tử từ các mảng bổ sung
                    $combinedElement = array_merge($aElement, [$bElement], [$cElements[$index]], [$dElements[$index]], [$eElements[$index]]);
                    // Thêm mảng mới vào mảng kết quả
                    $mergedArray[] = $combinedElement;
                }
            }
        }

        // dd(convertWeekStringToNumbers($weekString1));
        return $mergedArray;
    }
    protected function mapDataToFields($tableData)
    {
        $mappedData = [];

        // Tạo một mảng ánh xạ tên trường tương ứng với chỉ số của nó
        $fieldNames = ['0', '1', '2', '3', '4'];

        // Kiểm tra xem có đủ số lượng trường trong $tableData hay không
        if (count($tableData['cells']) >= count($fieldNames)) {
            // Lặp qua từng phần tử trong $fieldNames và ánh xạ vào dữ liệu tương ứng trong $tableData
            foreach ($fieldNames as $index => $fieldName) {
                // Thêm một mảng mới với tên trường và giá trị tương ứng
                $mappedData[$fieldName] = $tableData['cells'][$index];
            }
        }

        return $mappedData;
    }

    public function deleteData(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        ThoiKhoaBieuCraw::destroy($request->id);

        return redirect()->route('showDataCrawler')->with('success', 'Lịch học đã được xóa thành công.');
    }
}