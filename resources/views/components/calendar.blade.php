{{-- css --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/main/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/fullcalendar/fullcalendar.css')}}" />


<div class="card mb-3" style="width: 100%; border-radius: 5px">
    <div class="card-body">
        <form id="filter-form">
            @csrf
            <div class="form-row">
                <div class="col">
                    <select id="hoc-ky" name="HocKy" class="form-control">
                        @foreach($hocKys as $hocKy)
                            <option value="{{ $hocKy->id }}">{{ $hocKy->TenHK }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <select id="phong-may" name="PhongMay" class="form-control">
                        @foreach($phongMays as $phongMay)
                            <option value="{{ $phongMay->id }}">{{ $phongMay->TenPhong }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </div>
            </div>
        </form>
    </div>
</div>
@if(Session::has('success'))
<div id="alert" class="alert alert-success" role="alert">
    {{ Session::get('success') }}
</div>
@endif

<div class="pd-20 card-box mb-30">
    <div class="calendar-wrap">
        <div id="calendar"></div>
    </div>
    <!-- calendar modal -->
    <div id="modal-view-event" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"> 
                <div class="modal-body">
                    <h4 class="h4">
                        <span class="event-icon weight-200 mr-3"></span
                        ><span class="">Chi tiết</span>
                    </h4>
                    <table class="table">
                        <thead>
                          <tr>
                                <th></th>
                                <th></th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr class="table-active">
                                <td>Phòng máy:</td>
                                <td class="roomAdd"></td>
                            </tr>
                            <tr>
                                <td>Mã môn học:</td>
                                <td class="maMonHocAdd"></td>
                            </tr>
                            <tr class="table-active">
                                <td>Tên môn học:</td>
                                <td class="tenMonHocAdd"></td>
                            </tr>
                            <tr class="">
                                <td>Lớp:</td>
                                <td class="classAdd"></td>
                            </tr>
                            <tr class="table-active">
                                <td>Nhóm môn học:</td>
                                <td class="nhomMHAdd"></td>
                            </tr>
                            <tr>
                                <td>Tiết học:</td>
                                <td class="tietHocAdd"></td>
                            </tr>
                            <tr class="table-active">
                                <td>Sĩ số:</td>
                                <td class="siSoAdd"></td>
                            </tr>
                            <tr>
                                <td>Giảng viên:</td>
                                <td class="giangVienAdd"></td>
                            </tr>
                        </tbody>
                      </table>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-primary"
                        data-dismiss="modal"
                    >
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-view-event-add" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="add-event" action="{{ route('dang-ky-thoi-khoa-bieu') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <h4 class="text-blue h4 mb-10">Thêm Thời Khóa Biểu</h4>
                        <div class="form-group">
                            <label>Tên Môn Học</label>
                            <input type="text" class="form-control" name="TenMonHoc" required />
                        </div>
                        <div class="form-group">
                            <label>Mã Môn Học</label>
                            <input type="text" class="form-control" name="MaMonHoc" required />
                        </div>
                        <div class="form-group">
                            <label>Lớp</label>
                            <input type="text" class="form-control" name="Lop" required />
                        </div>
                        <div class="form-group">
                            <label>Nhóm Môn Học</label>
                            <input type="text" class="form-control" name="NhomMH" required />
                        </div>
                        <div class="form-group">
                            <label>Sĩ Số</label>
                            <input type="number" class="form-control" name="SiSo" required />
                        </div>
                        <div class="form-group">
                            <label>Chọn Tiết Học</label>
                            <select name="TietHoc[]" class="selectpicker form-control" multiple data-live-search="true" required>
                                @foreach($tietHocs as $tietHoc)
                                    <option value="{{ $tietHoc->id }}">{{ $tietHoc->Ten }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chọn Học Kỳ</label>
                            <select name="HocKy" class="form-control">
                                @foreach($hocKys as $hocKy)
                                    <option value="{{ $hocKy->id }}">{{ $hocKy->TenHK }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chọn Học Kỳ</label>
                            <select name="PhongMay" class="form-control">
                                @foreach($phongMays as $phongMay)
                                    <option value="{{ $phongMay->id }}">{{ $phongMay->TenPhong }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Giảng Viên</label>
                            <input type="text" class="form-control" name="GiangVien" required />
                        </div>
                        <input type="hidden" name="NgayDangKy" id="NgayDangKy" />
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Đăng Ký</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Đóng</button>
                    </div>
                </form>
                
            </div>
        </div>
    </div>
</div>