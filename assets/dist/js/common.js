const parseF = (str) => {
	var f = parseInt(str);
	if (isNaN(f)) {
		f = 0;
	}
	return f;
};
const SweetAlert = (type, message) => {
	const Toast = Swal.mixin({
		toast: true,
		position: "top-end",
		showConfirmButton: false,
		timer: 3000,
		timerProgressBar: true,
		didOpen: (toast) => {
			toast.onmouseenter = Swal.stopTimer;
			toast.onmouseleave = Swal.resumeTimer;
		},
	});
	Toast.fire({
		icon: (type ||= "warning"),
		title: message,
	});
};
$("body").on("keydown change", "input, select,textarea,.remove-btn", function (e) {
	if (e.key !== "Enter") {
		return;
	}
	var self = $(this),
		form = self.parents("form:eq(0)"),
		focusable,
		next;
	focusable = form
		.find("input,a,select,button,textarea,.select2")
		.filter(":visible");
	next = focusable.eq(focusable.index(this) + 1);
	if (next.length) {
		if ($(next[0]).data("select2") != undefined) {
			$(next[0]).select2("open");
		} else {
			if (next.prop("readonly")) {
				next.select();
			} else {
				if (next.attr("type") == "hidden") {
					next.next().focus();
					next.next().select();
				} else {
					if ($("#use_status").prop("checked") == true) {
						$(this).blur();
					} else {
						next.focus();
						next.select();
					}
				}
			}
		}
	} else {
		form.submit();
	}
	return false;
});
// $(document).on("change", "select", function (e) {
// 	var temp = $(this);
// 	setTimeout(function () {
// 		$(".select2-container--focus").removeClass("select2-container--focus");
// 		$(":focus").blur();
// 		if (
// 			temp.parent().next().children("input").prop("class") ==
// 			"select2-hidden-accessible"
// 		) {
// 			temp.parent().next().children("input").select2("open");
// 		} else {
// 			if (temp.parent().next().children("input").attr("type") == "hidden") {
// 				temp.parent().next().next().children("input").select();
// 			} else {
// 				temp.parent().next().children("input").select();
// 			}
// 		}
// 	}, 50);
// });
