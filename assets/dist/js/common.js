// const BaseUrl = $("meta[name=baseurl]").attr("content");
var fpFrom = flatpickr(".from", {
    dateFormat: "Y-m-d",
});

var fpTo = flatpickr(".to", {
    dateFormat: "Y-m-d",
});

		
const parseF = (str) => {
	var f = parseFloat(str);
	if (isNaN(f)) {
		f = 0;
	}
	return f;
};
const { format: formatCurrency } = new Intl.NumberFormat("hi-in", {
	style: "currency",
	currency: "INR",
});

const getOptions = function (response, selected_id = null) {
	var options = "";
	var selected = "";
	options += `<option value=""> Select <option>`;
	$.each(response, function (key, value) {
		selected =selected_id != null && selected_id == value? "selected": " ";
			options += `<option value="${value}" ${selected}>${value}</option>`;
	});
	return options;
};

function handelFooterTotal(api, indexes = []) {
    indexes.forEach((index) => {
        const Total = api
            .column(index)
            .data()
            .reduce((a, b) => +a + +b, 0);
        $(api.column(index).footer()).html(intVal(Total.toFixed(2)));
    });
}

var intVal = function(i) {
    return typeof i === "string" ?
        i.replace(/[\$,]/g, "") * 1 :
        typeof i === "number" ?
        i :
        0;
};

const setOptions = function (response, selected_id = null) {
	var options = "";
	var selected = "";
	options += `<option value=""> Select <option>`;
	$.each(response, function (key, value) {
		selected = selected_id != null && selected_id == value.id ? "selected" : " ";
		options += `<option value="${value.id}" ${selected}>${value.name}</option>`;
	});
	return options;
};

const setLotWiseRmOptions = function (response, selected_id = null) {
// 	var options = "";
	var selected = "";
	var options = `<option value=""> Select RM<option>`;
	$.each(response, function (key, value) {
		selected = selected_id != null && selected_id == value.id ? "selected" : " ";
		options += `<option value="${value.id}" ${selected} data-weight="${value.rem_weight}" data-touchData="${value.touch}" data-quantity="${value.rem_quantity}" data-oldWeight="${value.weight || 0}" data-oldQuantity="${value.quantity || 0}" >${value.id} - ${value.code} &nbsp; Weight: ${value.rem_weight} Quantity :  ${value.rem_quantity}</option>`;
	});
	return options;
};

const getWorker = function (process_id, selected_id = null) {
	if (process_id) {
		var optionHTML = "";
		var selected = "";
		optionHTML += `<option value=""> Select <option>`;

		$.ajax({
			type: "POST",
			dataType: "json",
			url: `${BaseUrl}manufacturing/process/getWorkers`,
			method: "POST",
			data: { process_id, selected_id },
			success: function (response) {
				$(".workers").empty();
				$.each(response, function (key, value) {
					selected =
						selected_id != null && selected_id == value.id ? "selected" : " ";
					optionHTML += `<option value="${value["id"]}" ${selected}>${value["name"]}</option>`;
				});
				$(".workers").html(optionHTML);
			},
		});
	} else {
		$(".workers").empty();
		$(".workers").append('<option value="">Select</option>');
	}
};

function formatNumber(number = null) {
	let formattedTotal;
	if (!Number.isInteger(number)) {
		const parts = number.toString().split(".");
		if (parts[1] && parseInt(parts[1].length) > 2) {
			formattedTotal = number.toFixed(2);
		} else {
			formattedTotal = number.toString();
		}
	} else {
		formattedTotal = number.toString();
	}
	return formattedTotal;
}

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

$(document).ajaxSend(function (event, jqXHR, { showLoader }) {
	if (showLoader) {
		$.blockUI({
			message: `<div class="sk-wave mx-auto">
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div>
				  </div>`,
			// message: `<div class="spinner-border text-white mx-auto">
			// 		  </div>`,
			css: {
				backgroundColor: "transparent",
				border: "0",
			},
			overlayCSS: {
				opacity: 0.5,
			},
		});
	}
});
$(document).ajaxComplete(() => $.unblockUI());
/* common loader  */
function ShowBlockUi(selector, timer = 1000) {
	// console.log(selector);
	if (selector == "body") {
		$.blockUI({
			message: `<div class="sk-wave mx-auto">
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div> 
					  <div class="sk-rect sk-wave-rect"></div>
				  </div>`,
			css: {
				backgroundColor: "transparent",
				border: "0",
			},
			overlayCSS: {
				opacity: 0.5,
			},
		});
	} else {
		$(selector).block({
			message: '<div class="spinner-border text-primary" role="status"></div>',
			timeout: timer,
			css: {
				backgroundColor: "transparent",
				border: "0",
			},
			overlayCSS: {
				backgroundColor: "#fff",
				opacity: 0.8,
			},
		});
	}
}

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
	"input, select,textarea,.remove-btn,.Receivedmaterial,.select2",
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

$(document).on("change", "select", function (e) {
    var temp = $(this);
    
    setTimeout(function () {
        // Remove focus class from any Select2 container
        $(".select2-container--focus").removeClass("select2-container--focus");

        // Blur the currently focused element
        $(":focus").select();

        var nextElement = temp.parent().next().find("select");

        if (nextElement.hasClass("select2-hidden-accessible")) {
            // If the next input is a Select2 element, open the dropdown
            nextElement.select2("open");
        } else {
            if (nextElement.attr("type") === "hidden") {
                // If the next input is hidden, find the next visible input and focus on it
                temp.parent().next().next().find("select").focus().select();
            } else {
                // Otherwise, focus and select the next input
                nextElement.focus().select();
            }
        }
    }, 50);
});
	
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

function Validator(multiple = false) {
    this.fieldConfigs = [];
    this.multiple = multiple;
  }
  Validator.prototype.addField = function (field, message, actionOnFail) {
    this.fieldConfigs.push({ field, message, actionOnFail });
    return this;
  };
  Validator.prototype.validate = function () {
    var index = 0;
    var error = false;

    for (let { field, message, actionOnFail } of this.fieldConfigs) {
      // console.log(message)
      if (this.multiple) {
        if (!(field instanceof jQuery)) field = $(field);
        for (let i = 0; i < field.length; i++) {
          const element = $(field)[i];
          if (!$(element).val() || $(element).val().trim() == "") {
            if (
              ["textarea", "number", "text"].indexOf(
                $(element).attr("type") || "text"
              ) != -1
            ) {
              $(element).focus();
            }
            SweetAlert("warning", message);
            actionOnFail instanceof Function && actionOnFail($(element));
            return false;
          }
        }
      } else {
        if (!(field instanceof jQuery)) {
          field = $(field);
        }
        if (!field.val()) {
          if (
            ["textarea", "number", "text"].indexOf(
              field.attr("type") || "text"
            ) != -1
          ) {
            field.focus();
          }
          SweetAlert("warning", message);
          actionOnFail instanceof Function && actionOnFail(field);
          return false;
        }
      }
    }
    return true;
  };

// $("form").on("submit", function (e) {
// 	let isValid = true;
// 	$(this)
// 	  .find(".required")
// 	  .each(function () {
// 		if ($(this).val().trim() === "") {
// 		  $(this).addClass("is-invalid");
// 		  if ($(this).parent().next(".error-message").length == 0) {
// 			$(this)
// 			  .parent()
// 			  .after("<div class='error-message text-danger'></div>");
// 			if ($(this).hasClass("select2")) {
// 			  var dataLabelValue = $(this).data("lable");
// 			  $(this)
// 				.parent()
// 				.next(".error-message")
// 				.html(dataLabelValue + " field is required.<br>");
// 			} else {
// 			  $(this)
// 				.parent()
// 				.next(".error-message")
// 				.html(
// 				  $(this).siblings("label").text() + " field is required.<br>"
// 				);
// 			}
// 		  }
// 		  isValid = false;
// 		} else {
// 		  $(this).removeClass("is-invalid");
// 		  $(this).parent().next(".error-message").html("");
// 		}
// 	  });
// 	if (!isValid) {
// 	  e.preventDefault();
// 	}
//   });
