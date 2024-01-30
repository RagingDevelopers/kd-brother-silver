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

/* Swal */
const swalAlert = (icon, title, text = "") => {
	return Swal.fire({
		title,
		text,
		icon,
	});
};
/* Alert IF */
const alert_if = (message, callBack) => {
	Swal.fire({
		title: "Are you sure?",
		text: message,
		icon: "warning",
		showCancelButton: true,
		confirmButtonColor: "#3085d6",
		cancelButtonColor: "#d33",
		confirmButtonText: "Yes",
	}).then((result) => {
		if (result.isConfirmed) {
			if (callBack) {
				callBack();
			}
		}
	});
};

$("body").on(
	"keydown change",
	"input, select,textarea,.remove-btn",
	function (e) {
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
	}
);

/* common Jquery validation for all input just add */
jQuery.validator.setDefaults({
	debug: false,
	ignore: ":hidden:not(.required)",
	ignoreTitle: true,
	errorPlacement: function (error, element) {
		if (element.closest(".form-floating").length) {
			error.insertAfter(element.closest(".form-floating"));
		} else {
			error.insertAfter(element); // default error placement
		}
	},
});
/* For Mobile Number Validation */
jQuery.validator.addMethod(
	"mobileCheck",
	function (value, element) {
		return this.optional(element) || /^[0-9]{10}$/i.test(value);
	},
	"Please enter valid mobile."
);
/* Positive Number Validation */
jQuery.validator.addMethod(
	"positiveNumber",
	function (value, element) {
		return this.optional(element) || value >= 0;
	},
	"Please enter a positive number."
);
/* For Email Validation */
jQuery.validator.addMethod(
	"emailCheck",
	function (value, element) {
		return (
			this.optional(element) ||
			/^[A-Z0-9][A-Z0-9\._\-]+@(?:[A-Z0-9][A-Z0-9_\-]+)\.(?:[A-Z0-9][A-Z0-9_\-]{1,9})(?:(?:\.[A-Z0-9]{2,5})?)$/i.test(
				value
			)
		);
	},
	"Please enter valid email address."
);
jQuery.validator.addMethod(
	"emailCheck1",
	function (value, element) {
		return (
			this.optional(element) ||
			/^[A-Z0-9][A-Z0-9\._\-]+@(?:[A-Z0-9][A-Z0-9_\-]+)\.(?:[A-Z0-9][A-Z0-9_\-]{1,9})(?:(?:\.[A-Z0-9]{2,5})?)$/i.test(
				value
			)
		);
	},
	"Please enter valid email address."
);
/* Matches Italian postcode (CAP) */
jQuery.validator.addMethod(
	"pincodeCheck",
	function (value, element) {
		return this.optional(element) || /^\d{6}$/.test(value);
	},
	"Please specify a valid postal code"
);
/*  MaxLength */
jQuery.validator.addMethod(
	"maxLength",
	function (value, element, params) {
		return this.optional(element) || value.length <= params;
	},
	jQuery.validator.format("Maximum {0} characters allowed.")
);
/* For Pancard Validation */
jQuery.validator.addMethod(
	"checkPAN",
	function (value, element) {
		return (
			this.optional(element) ||
			/^[A-Za-z]{5}([0-9]{4})[A-Za-z]{1}$/i.test(value)
		);
	},
	"Invalid PAN No."
);
/* For Aadhaar card validation */
jQuery.validator.addMethod(
	"checkAdhaar",
	function (value, element) {
		return (
			this.optional(element) || /^[0-9]{4}-[0-9]{4}-[0-9]{4}$/i.test(value)
		);
	},
	"Invalid Adhaar No."
);
/* For GSTNumber Check validation */
jQuery.validator.addMethod(
	"checkGSTIN",
	function (value, element) {
		return (
			this.optional(element) ||
			/^[0-9][0-9][A-Za-z]{5}([0-9]{4})[A-Za-z]{1}[0-9][a-z][0-9a-z]$/i.test(
				value
			)
		);
	},
	"Invalid GST No."
);
jQuery.validator.addMethod(
	"checkCallback",
	function (value, element) {
		return this.optional(element) || /^[a-z]([a-z0-9])*$/i.test(value);
	},
	"Callback must start with alphabets and contains only alpha numeric value."
);
/* Check for alphanumericCheck value validation */
jQuery.validator.addMethod(
	"alphanumericCheck",
	function (value, element) {
		return this.optional(element) || /^[a-z0-9]+$/i.test(value);
	},
	"Only letters and numbers are allowed."
);
/* Class Rules for Validation */
jQuery.validator.addClassRules({
	requiredCheck: {
		required: true,
	},
	requiredCheck1: {
		required: {
			depends: function (el) {
				var finalReturn = false;
				$.each($(el).parents("tr").find("input"), function (k, v) {
					console.log($(v).val());
					if ($(v).val() != "" && $(v).val() != 0) {
						finalReturn = true;
					}
				});
				return finalReturn;
			},
		},
	},
	password: {
		required: true,
		minlength: 8,
	},
	confirmPassword: {
		required: true,
		equalTo: "#txtPassword",
	},
	confirmPayoutBankAccountNo: {
		required: true,
		equalTo: "#txtPayoutBankAccountNo",
	},
	confirmPIN: {
		required: true,
		equalTo: "#txtPIN",
	},
	pin: {
		required: true,
		digits: true,
		maxLength: 4,
	},
	onlyDigits: {
		required: true,
		digits: true,
	},
	alphanumeric: {
		required: true,
		alphanumericCheck: true,
	},
	onlyDigitsNoRequired: {
		digits: true,
	},
	isDecimal: {
		number: true,
	},
	mobileCheck: {
		required: true,
		mobileCheck: 10,
	},
	requiredInput: {
		required: true,
	},
	mobileCheck1: {
		mobileCheck: 10,
		required: true,
	},
	emailCheck: {
		required: true,
		emailCheck: true,
	},
	emailCheck1: {
		emailCheck: true,
	},
	max: {
		max: function (element) {
			return Number($(element).attr("data-max"));
		},
	},
	maxLength: {
		maxLength: function (element) {
			return Number($(element).attr("data-max"));
		},
	},
	pincodeCheck: {
		pincodeCheck: true,
	},
	checkPAN: {
		checkPAN: true,
	},
	checkAdhaar: {
		checkAdhaar: true,
	},
	checkGSTIN: {
		checkGSTIN: true,
	},
	checkCallback: {
		checkCallback: true,
	},
	positiveNumber: {
		positiveNumber: {
			depends: (e) => {},
		},
		number: true,
	},
});
/* for Make Custom Message for every Input */
jQuery.validator.messages.required = function (param, input) {
	if (
		$(input).hasClass("requiredCheck") ||
		$(input).hasClass("requiredCheck1")
	) {
		var requiredMessage = "Please select {field}";
		return requiredMessage.replace(
			"{field}",
			input.hasAttribute("data-validation-name")
				? input.getAttribute("data-validation-name").toLowerCase()
				: input.getAttribute("placeholder").toLowerCase()
		);
	} else {
		var requiredMessage = "Please enter {field}";
		return requiredMessage.replace(
			"{field}",
			input.hasAttribute("data-validation-name")
				? input.getAttribute("data-validation-name").toLowerCase()
				: input.getAttribute("placeholder").toLowerCase()
		);
	}
};
/* Form Validation Just addde  "validateForm" class of your submit button*/
$(".validateForm").on("click", function (e) {
	$(this).parents("form").valid();
});
/* Form reset and remove all error Validation Tag */
$(".validateForm-reset").on("click", function () {
	var form = $(this).parents("form");
	form.find("label.error").each(function (e) {
		if ($(this).hasClass("error")) $(this).removeClass("error").remove();
	});
});
