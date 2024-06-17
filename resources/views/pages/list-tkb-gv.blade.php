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
            modal.find('td[name="si_so"]').text(list.SiSo);
            modal.find('td[name="phong_may"]').text(list.phong_may.TenPhong);
            modal.find('td[name="giang_vien"]').text(list.GiangVien);
            modal.find('td[name="ngay_hoc"]').text(new Date(list.NgayHoc).toLocaleDateString());
            modal.find('td[name="status"]').text(list.status);
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
@if(Session::has('success'))
<div id="alert" class="alert alert-success" role="alert">
    {{ Session::get('success') }}
</div>
@endif
<div class="container-lg">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-12"><h2>Danh Sách Thời Khóa Biểu Đăng Ký Cần Duyệt</h2></div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Tên Môn Học</th>
                        <th>Lớp</th>
                        <th>Tiết Học</th>
                        <th>Phòng</th>
                        <th>Ngày Học</th>
                        <th>Trạng Thái</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dangKyLopHocs as $dangKyLopHoc)
                    <tr data-id="{{ $dangKyLopHoc->id }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $dangKyLopHoc->thoiKhoaBieu->TenMonHoc }}</td>
                        <td>{{ $dangKyLopHoc->thoiKhoaBieu->Lop }}</td>
                        <td>
                            @foreach($dangKyLopHoc->thoiKhoaBieu->tietHocs as $key => $tietHoc)
                                {{ $tietHoc->Ten }}
                                @if($key < count($dangKyLopHoc->thoiKhoaBieu->tietHocs) - 1)
                                    -
                                @endif
                            @endforeach
                        </td>
                        <td>{{$dangKyLopHoc->thoiKhoaBieu->phongMay->TenPhong}}</td>
                        <td>{{ date('d/m/Y', strtotime($dangKyLopHoc->thoiKhoaBieu->NgayHoc)) }}</td>
                        <td>
                            @if ($dangKyLopHoc->Status === "approved")
                                Đã Duyệt
                            @endif
                            @if ($dangKyLopHoc->Status === "pending")
                                Chờ Duyệt
                            @endif
                            @if ($dangKyLopHoc->Status === "rejected")
                                Từ Chối
                            @endif
                        </td>
                        <td>
                            <a title="Xem" href="#" class="success" title="Edit" data-toggle="modal" data-target="#editModal" data-id="{{ $dangKyLopHoc->id }}" data-list="{{ json_encode($dangKyLopHoc->thoiKhoaBieu) }}"><i class="fas fa-eye" style="color: #63b2e6;"></i></a>
                            <a href="#" class="delete" title="Xóa" data-toggle="modal" data-target="#deleteModal" data-id="{{ $dangKyLopHoc->id }}"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
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
                            <td>Sĩ Số:</td>
                            <td name="si_so"></td>
                        </tr>
                        <tr>
                            <td>Ngày Học:</td>
                            <td name="ngay_hoc"></td>
                        </tr>
                        <tr>
                            <td>Giảng Viên:</td>
                            <td name="giang_vien"></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
              </div>
        </div>
    </div>
</div>

<!-- Reject Modal HTML -->
<div id="deleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" action="{{ route('xoa-dang-ky-tkb-giangvien') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Bạn chắc chắn muốn xóa?</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <input type="hidden" name="id" id="delete-id">
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Hủy">
                    <input type="submit" class="btn btn-danger" value="Xác Nhận">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection