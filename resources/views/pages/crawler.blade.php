@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

        // Populate edit modal fields with existing data
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var list = button.data('list');
            var modal = $(this);

            modal.find('td[name="mon_hoc"]').text(list.TenMonHoc);
            modal.find('td[name="ma_mon_hoc"]').text(list.MaMonHoc);
            modal.find('td[name="lop"]').text(list.Lop);
            modal.find('td[name="nhom_mh"]').text(list.NhomMH);
            modal.find('td[name="tin_chi"]').text(list.SoTinChi);
            modal.find('td[name="phong_may"]').text(list.phong_may.TenPhong);
            modal.find('td[name="tiet_hoc"]').text(list.TietHoc);
            modal.find('td[name="tuan_hoc"]').text(list.TuanHoc);
            modal.find('td[name="hoc_ky"]').text(list.hoc_ky.TenHK);
        });

        // Populate delete modal with id
        $('.delete').click(function() {
            var id = $(this).data('id');
            console.log(id)
            $('#delete-id').val(id);
        });

        // Handle form submit for delete
    
    });
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
    e.preventDefault();
    var id = document.getElementById('delete-id').value;
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', "{{ route('delete-room') }}", true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                if(response.success){
                    location.reload();
                } else {
                    alert('Xóa thất bại!');
                }
            } else {
                console.error('Request failed:', xhr.status);
            }
        }
    };
    var data = JSON.stringify({ id: id });
    xhr.send(data);
});
</script>

@section('title', 'Phòng Máy')
<style>
    .table-wrapper {
        width: 100%;
        margin: 30px auto;
        background: #fff;
        padding: 20px;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }
    .table-title {
        padding-bottom: 10px;
        margin: 0 0 10px;
    }
    .table-title h2 {
        margin: 6px 0 0;
        font-size: 22px;
    }
    .table-title .add-new {
        float: right;
        height: 30px;
        font-weight: bold;
        font-size: 12px;
        text-shadow: none;
        min-width: 100px;
        border-radius: 50px;
        line-height: 13px;
    }
    .table-title .add-new i {
        margin-right: 4px;
    }
    table.table {
        table-layout: fixed;
    }
    table.table tr th, table.table tr td {
        border-color: #e9e9e9;
    }
    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }
    table.table th:last-child {
        width: 100px;
    }
    table.table td a {
        cursor: pointer;
        display: inline-block;
        margin: 0 5px;
        min-width: 24px;
    }
    table.table td a.add {
        color: #27C46B;
    }
    table.table td a.edit {
        color: #FFC107;
    }
    table.table td a.delete {
        color: #E34724;
    }
    table.table td i {
        font-size: 19px;
    }
    table.table td a.add i {
        font-size: 24px;
        margin-right: -1px;
        position: relative;
        top: 3px;
    }
    table.table .form-control {
        height: 32px;
        line-height: 32px;
        box-shadow: none;
        border-radius: 2px;
    }
    table.table .form-control.error {
        border-color: #f50000;
    }
    table.table td .add {
        display: none;
    }
</style>

@section('content')
<div class="container-lg">
    <form
    action="{{ route('searchDataCraw') }}"
    class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search float-left">
    <div class="input-group">
        <input type="text" name="keyword" class="form-control bg-light border-1 small" placeholder="Nhập từ khóa tìm kiếm ..."
            aria-label="Search" aria-describedby="basic-addon2">
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary" type="button">
                <i class="fas fa-search fa-sm"></i>
            </button>
        </div>
    </div>
    </form>
    <div class="float-right">
        @if (isset($keyword))
            <i class="font-weight-bold text-sm">Kết quả tìm kiếm cho từ khóa "{{ $keyword }}"</i>
        @endif
    </div>
    <!-- Topbar Search -->
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Dữ liệu Craw Đào Tạo Vnua</h2></div>
                    <div class="col-sm-4 d-flex justify-content-end">
                        <button type="button" class="btn btn-info add-new" data-toggle="modal" data-target="#addModal"><i class="fas fa-cloud-download-alt"></i> Lấy dữ liệu</button>
                        <button type="button" class="btn btn-info add-new ml-3" data-toggle="modal" data-target="#updateModal"><i class="fas fa-sync"></i> Cập nhật dữ liệu</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Mã MH</th>
                        <th>Tên MH</th>
                        <th>Lớp</th>
                        <th>Tiết Học</th>
                        <th>Phòng Máy</th>
                        <th>Học Kỳ</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Tính số thứ tự bắt đầu của bản ghi đầu tiên trên trang hiện tại
                        $startIndex = ($datas->currentPage() - 1) * $datas->perPage() + 1;
                    @endphp
                    @foreach($datas as $index => $data)
                    <tr data-id="{{ $data->id }}">
                        <td>{{ $startIndex + $index }}</td>
                        <td>{{ $data->MaMonHoc}}</td>
                        <td>{{ $data->TenMonHoc}}</td>
                        <td>{{ $data->Lop}}</td>
                        <td>{{ $data->TietHoc}}</td>
                        <td>{{ $data->phongMay->TenPhong}}</td>
                        <td>{{ $data->hocKy->TenHK}}</td>
                        <td>
                            <a href="#" class="edit" title="Edit" data-toggle="modal" data-target="#editModal" data-id="{{ $data->id }}" data-list="{{ json_encode($data) }}"><i class="fas fa-eye"></i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="modal" data-target="#deleteModal" data-id="{{ $data->id }}"><i class="fas fa-fw fa-trash"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal HTML -->
<div id="addModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addForm" action="{{ route('run.dusk.test') }}" method="POST">
                @csrf
                <div class="modal-header">						
                    <h4 class="modal-title">Lấy dữ liệu</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">					
                    <div class="form-group">
                        <label>Chọn Học Kỳ</label>
                        <select id="hoc-ky" name="HocKy" class="form-control">
                            @foreach($hocKys as $hocKy)
                                <option value="{{ $hocKy->id }}">{{ $hocKy->TenHK }}</option>
                            @endforeach
                        </select>
                    </div>				
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Hủy">
                    <input type="submit" class="btn btn-success" value="Thêm">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Update Modal HTML -->
<div id="updateModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addForm" action="{{ route('updata-data-craw') }}" method="POST">
                @csrf
                <div class="modal-header">						
                    <h4 class="modal-title">Cập nhật lại dữ liệu mới</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">					
                    <div class="form-group">
                        <label>Chọn học kỳ cần cập nhật</label>
                        <select id="hoc-ky" name="HocKy" class="form-control">
                            @foreach($hocKys as $hocKy)
                                <option value="{{ $hocKy->id }}">{{ $hocKy->TenHK }}</option>
                            @endforeach
                        </select>
                    </div>					
                    <div class="form-group">
                        <i>
                            ( Cập nhật lại dữ liệu sẽ xóa hết dữ liệu craw cũ. Bạn có chắc chắn muốn cập nhật dữ liệu mới? ) 
                        </i>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Hủy">
                    <input type="submit" class="btn btn-success" value="Cập Nhật">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Appover Modal HTML -->
<div id="editModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="row" style="margin:0;padding:0;">
                <div class="col-md mt-3">
                  <table class="table w-100">
                    <thead colspan="2" style="font"><strong style>Xem chi tiết</strong></thead>
                    <tbody>
                        <tr>
                            <td>Phòng Máy:</td>
                            <td name="phong_may"></td>
                        </tr>
                        <tr>
                            <td>Mã Môn Học:</td>
                            <td name="ma_mon_hoc"></td>
                        </tr>
                        <tr>
                            <td>Tên Môn Học:</td>
                            <td name="mon_hoc"></td>
                        </tr>
                        <tr>
                            <td>Lớp:</td>
                            <td name="lop"></td>
                        </tr>
                        <tr>
                            <td>Nhóm MH:</td>
                            <td name="nhom_mh"></td>
                        </tr>
                        <tr>
                            <td>Số Tín Chỉ:</td>
                            <td name="tin_chi"></td>
                        </tr>
                        <tr>
                            <td>Tiết Học:</td>
                            <td name="tiet_hoc"></td>
                        </tr>
                        <tr>
                            <td>Tuần Học:</td>
                            <td name="tuan_hoc"></td>
                        </tr>
                        <tr>
                            <td>Học Kỳ:</td>
                            <td name="hoc_ky"></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>
</div>


<!-- Delete Modal HTML -->
<div id="deleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" action="{{ route('delete-tkb-craw') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Xóa Lịch Học</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa lịch học này?</p>
                    <input type="hidden" name="id" id="delete-id">
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Hủy">
                    <input type="submit" class="btn btn-danger" value="Xóa">
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Hiển thị phân trang -->
@if ($datas->lastPage() > 1)
    <nav class="d-flex justify-content-center">
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($datas->onFirstPage())
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $datas->previousPageUrl() }}" rel="prev">&laquo;</a>
                </li>
            @endif

            {{-- Numbered Page Links --}}
            @php
                $currentPage = $datas->currentPage();
                $lastPage = $datas->lastPage();
                $maxPages = 5; // Số lượng trang tối đa hiển thị
                $halfMaxPages = floor($maxPages / 2);
                $startPage = max($currentPage - $halfMaxPages, 1);
                $endPage = min($currentPage + $halfMaxPages, $lastPage);
            @endphp

            @if ($startPage > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $datas->url(1) }}">1</a>
                </li>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            @endif

            @for ($i = $startPage; $i <= $endPage; $i++)
                <li class="page-item {{ ($datas->currentPage() == $i) ? 'active' : '' }}">
                    <a class="page-link" href="{{ $datas->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            @if ($endPage < $lastPage)
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
                <li class="page-item">
                    <a class="page-link" href="{{ $datas->url($lastPage) }}">{{ $lastPage }}</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($datas->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $datas->nextPageUrl() }}" rel="next">&raquo;</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
@endsection

