jQuery(document).ready(function () {
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }

    // Lưu lựa chọn vào cookie
    function saveUserSelections() {
        var hocKyId = $('#hoc-ky').val();
        var phongMayId = $('#phong-may').val();
        setCookie('hocKyId', hocKyId, 2); // Cookie sẽ tồn tại trong 2 ngày
        setCookie('phongMayId', phongMayId, 2); // Cookie sẽ tồn tại trong 2 ngày
    }

    // Tải lựa chọn từ cookie
    function loadUserSelections() {
        var hocKyId = getCookie('hocKyId');
        var phongMayId = getCookie('phongMayId');

        if (hocKyId) {
            $('#hoc-ky').val(hocKyId);
        }
        if (phongMayId) {
            $('#phong-may').val(phongMayId);
        }
        $('.selectpicker').selectpicker('refresh');
    }

    // Tự động tải dữ liệu khi trang được load
    loadUserSelections();
    loadTimetable();

    // Tự động gọi hàm loadTimetable khi trang được load lại
    function loadTimetable() {
        var formData = $('#filter-form').serialize();

        // Gửi request Ajax đến route '/get-timetable'
        $.ajax({
            type: 'POST',
            url: '/get-timetable',
            data: formData,
            success: function (response) {
                console.log(response); // Log dữ liệu trả về từ server
                // Xử lý dữ liệu và hiển thị trên trang web
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', response);

                // Gửi request Ajax đến route '/get-crawled-data'
                $.ajax({
                    type: 'POST',
                    url: '/get-timetable-crawled',
                    data: formData,
                    success: function (crawledData) {
                        console.log(crawledData);
                        $('#calendar').fullCalendar('addEventSource', crawledData);
                        // Khai báo biến để lưu trữ allDayText
                        //  console.log(crawledData.weeks);
                        var currentWeekText = '';

                        // Cập nhật allDayText khi di chuyển giữa các tuần trên lịch
                        $('#calendar').fullCalendar('option', 'viewRender', function (view, element) {
                            var currentWeekStart = view.intervalStart.format('YYYY-MM-DD');
                            var currentWeekEnd = view.intervalEnd.format('YYYY-MM-DD');
                            
                            currentWeekText = ''; // Khởi tạo biến để lưu trữ thông tin về tuần hiện tại

                            // Tìm và cập nhật allDayText tương ứng với tuần hiện tại
                            $.each(crawledData.weeks, function (index, week) {
                                var startDate = week.start_date.trim();
                                var endDate = week.end_date.trim();

                                // In kiểm tra điều kiện so sánh
                                if (startDate === currentWeekStart || endDate === currentWeekEnd) {
                                    currentWeekText = 'Tuần ' + (index); // Vị trí index + 1 tương ứng với số thứ tự tuần
                                    return false; // Dừng vòng lặp khi tìm được tuần hiện tại
                                } else {
                                    console.log("No match for week: ", index);
                                }
                            });

                            if (currentWeekText) {
                                $('.fc-currentWeekButton-button').text(currentWeekText).show();
                            } else {
                                $('.fc-currentWeekButton-button').hide();
                            }
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error); // Log lỗi nếu có
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error(error); // Log lỗi nếu có
            }
        });
    }

    $('#filter-form').submit(function (e) {
        e.preventDefault(); // Ngăn chặn việc gửi form một cách thông thường
        saveUserSelections();
        loadTimetable();
    });

    $('.selectpicker').selectpicker();
    jQuery("#calendar").fullCalendar({
        dayNamesShort: ['CN', 'Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7'],
        dayNames: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
        monthNamesShort: ['thg 1', 'thg 2', 'thg 3', 'thg 4', 'thg 5', 'thg 6', 'thg 7', 'thg 8', 'thg 9', 'thg 10', 'thg 11', 'thg 12'],
        titleFormat: 'D MMM, YYYY',
        columnHeaderFormat: 'ddd D/MM',
        slotLabelFormat: 'H',
        firstDay: 1,
        minTime: '06:00:00',
        maxTime: '21:00:00',
        selectable: true,
        themeSystem: "bootstrap4",
        defaultView: "agendaWeek",
        editable: false, // Không cho phép chỉnh sửa sự kiện bằng kéo thả
        droppable: false, // Không cho phép kéo thả từ ngoài vào
        eventResizableFromStart: false, // Không cho phép thay đổi kích thước từ đầu sự kiện
        buttonText: {
            today: "Hôm Nay",
        },
        allDayText: "H",
        displayEventTime: false,
        header: {
            left: "today",
            center: "title",
            right: "currentWeekButton prev,next",
        },
        customButtons: {
            currentWeekButton: {
                text: 'Tuần',
            }
        },
        dayClick: function (date, jsEvent, view) {
            // Lưu ngày được chọn vào input ẩn
            $('#NgayDangKy').val(date.format());
            jQuery("#modal-view-event-add").modal();
        },
        eventClick: function (event, jsEvent, view) {
            jQuery(".tenMonHocAdd").html(event.title);
            jQuery(".roomAdd").html(event.room);
            jQuery(".maMonHocAdd").html(event.maMonHoc);
            jQuery(".classAdd").html(event.class);
            jQuery(".nhomMHAdd").html(event.nhomMonHoc);
            jQuery(".tietHocAdd").html(event.lesson);
            jQuery(".siSoAdd").html(event.siSo);
            jQuery(".giangVienAdd").html(event.lecturer);
            jQuery("#modal-view-event").modal();
        },
        eventRender: function (event, element) {
            var timeText = event.start.format("HH:mm") + " - " + event.end.format("HH:mm");
            element.find('.fc-title').html(
                timeText + "<br/>" +
                event.title + "<br/>" +
                "Giảng Viên: " + event.lecturer + "<br/>" +
                "Lớp: " + event.class + "<br/>" +
                "Tiết: " + event.lesson
            );
        }
    });
});
