@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();

        // Populate edit modal fields with existing data
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var name = button.data('name');
            var thoiGianBatDau = button.data('thoi-gian-bat-dau'); 
            var thoiGianKetThuc = button.data('thoi-gian-ket-thuc');

            thoiGianBatDau = formatDate(thoiGianBatDau);
            thoiGianKetThuc = formatDate(thoiGianKetThuc);

            var modal = $(this);
            modal.find('input[name="id"]').val(id);
            modal.find('input[name="ten"]').val(name);
            modal.find('input[name="thoi_gian_bat_dau"]').val(thoiGianBatDau);
            modal.find('input[name="thoi_gian_ket_thuc"]').val(thoiGianKetThuc);
        });

        // Populate delete modal with id
        $('.delete').click(function() {
            var id = $(this).data('id');
            console.log(id)
            $('#delete-id').val(id);
        });

        // Handle form submit for delete
    
    });
    // Function to format date to YYYY-MM-DD
    function formatDate(dateString) {
        var date = new Date(dateString);
        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, '0'); // Months are zero-based
        var day = date.getDate().toString().padStart(2, '0');
        return `${year}-${month}-${day}`;
    }
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

@section('title', 'Học Kỳ')
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
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-sm-8"><h2>Danh Sách Học Kỳ</h2></div>
                    <div class="col-sm-4">
                        <button type="button" class="btn btn-info add-new" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i> Thêm Mới</button>
                    </div>
                </div>
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Học Kỳ</th>
                        <th>Thời Gian Bắt Đầu</th>
                        <th>Thời gian Kết Thúc</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                    <tr data-id="{{ $item->id }}">
                        <td>{{ $loop->index + 1 }}</td>
                        <td>{{ $item->TenHK }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->ThoiGianBatDau)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->ThoiGianKetThuc)->format('d-m-Y') }}</td>
                        <td>
                            <a href="#" class="edit" title="Edit" data-toggle="modal" data-target="#editModal" data-id="{{ $item->id }}" 
                                data-name="{{ $item->TenHK }}"   
                                data-thoi-gian-bat-dau="{{ $item->ThoiGianBatDau }}" 
                                data-thoi-gian-ket-thuc="{{ $item->ThoiGianKetThuc }}"><i class="fas fa-fw fa-pen"></i></a>
                            <a href="#" class="delete" title="Delete" data-toggle="modal" data-target="#deleteModal" data-id="{{ $item->id }}"><i class="fas fa-fw fa-trash"></i></a>
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
            <form id="addForm" action="{{ route('add-hoc-ky') }}" method="POST">
                @csrf
                <div class="modal-header">						
                    <h4 class="modal-title">Thêm Học Kỳ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">					
                    <div class="form-group">
                        <label>Tên Học Kỳ</label>
                        <input type="text" class="form-control" name="ten" required>
                    </div>				
                    <div class="form-group">
                        <label>Ngày Bắt Đầu</label>
                        <input type="date" class="form-control" name="thoi_gian_bat_dau" required>
                    </div>				
                    <div class="form-group">
                        <label>Ngày Kết Thúc</label>
                        <input type="date" class="form-control" name="thoi_gian_ket_thuc" required>
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

<!-- Edit Modal HTML -->
<div id="editModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" action="{{ route('update-hoc-ky') }}" method="POST">
                @csrf
                <div class="modal-header">						
                    <h4 class="modal-title">Chỉnh Sửa Học Kỳ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">					
                    <div class="form-group">
                        <label>Tên Học Kỳ</label>
                        <input type="hidden" name="id">
                        <input type="text" class="form-control" name="ten" required>
                        <div class="form-group">
                            <label>Ngày Bắt Đầu</label>
                            <input type="date" class="form-control" name="thoi_gian_bat_dau" required>
                        </div>				
                        <div class="form-group">
                            <label>Ngày Kết Thúc</label>
                            <input type="date" class="form-control" name="thoi_gian_ket_thuc" required>
                        </div>			
                    </div>				
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-default" data-dismiss="modal" value="Hủy">
                    <input type="submit" class="btn btn-info" value="Lưu">
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Modal HTML -->
<div id="deleteModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" action="{{ route('delete-hoc-ky') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Xóa Học Kỳ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc chắn muốn xóa học kỳ này?</p>
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
@endsection

