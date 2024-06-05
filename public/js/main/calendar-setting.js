jQuery(document).ready(function () {
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
        // editable: true,
        buttonText: {
            today: "Hôm Nay",
        },
        allDayText: "Tuần 21",
        displayEventTime: false,
        header: {
            left: "today",
            center: "title",
            right: "prev,next",
        },
        events: [
            {
                title: "Math 101",
                start: "2024-06-04T07:00:00",
                end: "2024-06-04T13:00:00",
                allDay: false,
                lecturer: "Dr. Smith",
                class: "Room 101",
                lesson: "Lesson 1",
            }
        ],
        dayClick: function (date, jsEvent, view) {
            // Lưu ngày được chọn vào input ẩn
            $('#NgayDangKy').val(date.format());
            jQuery("#modal-view-event-add").modal();
        },
        eventClick: function (event, jsEvent, view) {
            jQuery(".event-icon").html("<i class='fa fa-" + event.icon + "'></i>");
            jQuery(".event-title").html(event.title);
            jQuery(".event-body").html(event.description);
            jQuery(".eventUrl").attr("href", event.url);
            jQuery("#modal-view-event").modal();
        },
        eventRender: function (event, element) {
            var timeText = event.start.format("HH:mm") + " - " + event.end.format("HH:mm");
            element.find('.fc-title').html(
                timeText + "<br/>" +
                event.title + "<br/>" +
                "Lecturer: " + event.lecturer + "<br/>" +
                "Class: " + event.class + "<br/>" +
                "Lesson: " + event.lesson
            );
        }
    });
});