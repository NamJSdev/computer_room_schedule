{{-- css --}}
<link rel="stylesheet" type="text/css" href="{{asset('css/main/core.css')}}" />
<link rel="stylesheet" type="text/css" href="{{asset('js/fullcalendar/fullcalendar.css')}}" />

<div class="pd-20 card-box mb-30">
    <div class="calendar-wrap">
        <div id="calendar"></div>
    </div>
    @if(Session::has('success'))
    <div id="alert" class="alert alert-success" role="alert">
        {{ Session::get('success') }}
    </div>
    @endif

    
    <!-- calendar modal -->
    <div id="modal-view-event" class="modal modal-top fade calendar-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content"> 
                <div class="modal-body">
                    <h4 class="h4">
                        <span class="event-icon weight-400 mr-3"></span
                        ><span class="event-title"></span>
                    </h4>
                    <div class="event-body"></div>
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
                <form id="add-event">
                    <div class="modal-body">
                        <h4 class="text-blue h4 mb-10">Thêm Thời Khóa Biểu</h4>
                        <div class="form-group">
                            <label>Tên Môn Học</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Mã Môn Học</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Lớp</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Nhóm Môn Học</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Sĩ Số</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Tuần Học</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Tiết Học</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        <div class="form-group">
                            <label>Giảng Viên</label>
                            <input type="text" class="form-control" name="ename" />
                        </div>
                        {{-- <div class="form-group">
                            <label>Event Date</label>
                            <input
                                type="text"
                                class="datetimepicker form-control"
                                name="edate"
                            />
                        </div>
                        <div class="form-group">
                            <label>Event Description</label>
                            <textarea class="form-control" name="edesc"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Event Color</label>
                            <select class="form-control" name="ecolor">
                                <option value="fc-bg-default">fc-bg-default</option>
                                <option value="fc-bg-blue">fc-bg-blue</option>
                                <option value="fc-bg-lightgreen">
                                    fc-bg-lightgreen
                                </option>
                                <option value="fc-bg-pinkred">fc-bg-pinkred</option>
                                <option value="fc-bg-deepskyblue">
                                    fc-bg-deepskyblue
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Event Icon</label>
                            <select class="form-control" name="eicon">
                                <option value="circle">circle</option>
                                <option value="cog">cog</option>
                                <option value="group">group</option>
                                <option value="suitcase">suitcase</option>
                                <option value="calendar">calendar</option>
                            </select>
                        </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Đăng Ký
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            data-dismiss="modal"
                        >
                            Đóng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>