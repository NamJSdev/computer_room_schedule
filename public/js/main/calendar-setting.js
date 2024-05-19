jQuery(document).ready(function () {
	jQuery("#add-event").submit(function () {
		alert("Submitted");
		var values = {};
		$.each($("#add-event").serializeArray(), function (i, field) {
			values[field.name] = field.value;
		});
		console.log(values);
	});
});

(function () {
	"use strict";
	// ------------------------------------------------------- //
	// Calendar
	// ------------------------------------------------------ //
	jQuery(function () {
		// page is ready
		jQuery("#calendar").fullCalendar({
			dayNamesShort: ['CN', 'Th 2', 'Th 3', 'Th 4', 'Th 5', 'Th 6', 'Th 7'],
			dayNames: ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy'],
			monthNamesShort: ['thg 1', 'thg 2', 'thg 3', 'thg 4', 'thg 5', 'thg 6', 'thg 7', 'thg 8', 'thg 9', 'thg 10', 'thg 11', 'thg 12'],
			titleFormat: 'D MMM, YYYY', // Định dạng tiêu đề mong muốn
			columnHeaderFormat: 'ddd D/MM',
			slotLabelFormat: 'H',
			firstDay: 1,
			minTime: '06:00:00', // Bắt đầu từ 6 giờ sáng
			maxTime: '21:00:00', // Kết thúc vào 8 giờ tối
			selectable: true, // Cho phép chọn vùng để tạo sự kiện
			// Xử lý khi một vùng được chọn
			// selectStart: function(start, end, jsEvent, view) {
			// 	// start: Thời gian bắt đầu của vùng được chọn
			// 	// end: Thời gian kết thúc của vùng được chọn
			// 	// jsEvent: Sự kiện JavaScript liên quan đến việc chọn
			// 	// view: Đối tượng view hiện tại của lịch trình
		
			// 	// Hiển thị thông tin thời gian bắt đầu và kết thúc
			// 	$('#startTime').text(start.format('HH:mm'));
			// 	$('#endTime').text(end.format('HH:mm'));
			// },
			themeSystem: "bootstrap4",
			defaultView: "agendaWeek",
			editable: true,
			displayEventTime: false, // Ẩn văn bản all-day
			header: {
				left: "today",
				center: "title",
				right: "prev,next",
			},
			events: [
				// Danh sách các sự kiện ở đây
			],
			dayClick: function () {
				jQuery("#modal-view-event-add").modal();
			},
			eventClick: function (event, jsEvent, view) {
				jQuery(".event-icon").html("<i class='fa fa-" + event.icon + "'></i>");
				jQuery(".event-title").html(event.title);
				jQuery(".event-body").html(event.description);
				jQuery(".eventUrl").attr("href", event.url);
				jQuery("#modal-view-event").modal();
			},
		});

	});
})(jQuery);
