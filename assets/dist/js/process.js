var garnuTouch = $("#gatnuTouch").val();
var mainRow = $(".mainRow")[0].outerHTML;
var mainRmRow = $(".main-row")[0]?.outerHTML;
var metalRow = $(".metalRow")[0]?.outerHTML;
var usedmetal = $(".sectiontocopy")[0].outerHTML;
var ReceivedMainRow;
var rmBtn = null;
var receiveBtn = null;

// var receiveCode = $("#receiveCode").val();
// if ($(".receiveCode").parent().hasClass('d-none')) {
// 	$(".given_weight").removeClass("readonly");
// 	$(".given_weight").prop("readonly", false);
// } else {
// 	$(".given_weight").addClass("readonly");
// 	$(".given_weight").prop("readonly", true);
// }

$(document).ready(function () {
	$(".select2").select2({
		placeholder: "-- Select --",
		allowClear: true,
	});
});

$(".ManageProcess").submit(function (e) {
	e.preventDefault();
	var validator = new Validator();
	if (!$(".receiveCode").parent().hasClass("d-none")) {
		validator.addField(".receiveCode", "Please select Receive Code!", (el) =>
			el.select2("open")
		);
	}
	validator
		.addField(".process", "Please select process!", (el) => el.select2("open"))
		.addField("#workers", "Please select Worker!", (el) => el.select2("open"))
		.addField(".given-qty", "Please Enter Given Quantity!")
		.addField(".given_weight", "Please Enter Given Weight!");

	if (!validator.validate()) return;

	if ($(".finalWeight").val() <= 0) {
		$(".finalWeight").val("0");
		// $(".finalWeight").focus();
		SweetAlert("warning", "Final Weight should be greater than 0");
		return;
	}

	$(this).off("submit").submit();
});

function WorkersData(process_id = null, selected_id = null) {
	var optionHTML = "";
	var selected = "";
	optionHTML += `<option value=""> Select <option>`;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: `${BaseUrl}manufacturing/process/getWorkers`,
		method: "POST",
		data: {
			process_id,
			selected_id,
		},
		success: function (response) {
			$.each(response, function (key, value) {
				selected =
					selected_id != null && selected_id == value.id ? "selected" : " ";
				optionHTML += `<option value="${value["id"]}" ${selected}>${value["name"]}</option>`;
			});
			if (selected_id != null) {
				$(".workers").html(optionHTML);
			} else {
				$(".workers").html(optionHTML).select2("open");
			}
		},
	});
}

if ($(".process").val() != "") {
	var process_id = $(".process").val();
	var selected_id = $(".process").find(":selected").data("workerid");
	$(".workers").empty();
	if (process_id) {
		WorkersData(process_id, selected_id);
	} else {
		$(".workers").empty();
		$(".workers").append('<option value="">Select</option>');
	}
}

$(document)
	.on("change", ".process", function () {
		var process_id = $(this).val();
		var selected_id = $(this).find(":selected").data("workerid");
		$(".workers").empty();
		if (process_id) {
			WorkersData(process_id);
		} else {
			$(".workers").empty();
			$(".workers").append('<option value="">Select</option>');
		}
	})
	.trigger("change");

function autoValueEnter() {
	var totalRmWeight = parseFloat($(".totalRmWeight").val()) || 0;
	var given_weight = parseFloat($(".given_weight").val()) || 0;

	var totalRmWeight = 0;
	$(".weight").each(function () {
		totalRmWeight += parseFloat($(this).val()) || 0;
	});
	$(".totalRmWeight").val(formatNumber(totalRmWeight));
	$(".finalWeight").val(
		formatNumber(parseFloat(given_weight) + parseFloat(totalRmWeight))
	);
}

autoValueEnter();

$("button[data-target='#exampleModal']").click(function (event) {
	event.preventDefault();
	var garnu_id = $("#garnu_id").val();
	var given_id = $("#given_id").val();
	if (garnu_id != "" && given_id != "") {
	}
	$("#modal-report").modal("show");
	// $(".lot_creation").each(function () {
	// 	if(this.checked){
	// 		$(this).val('YES');
	// 	}else{
	// 		$(this).val('NO');
	// 	}
	// });
	Rmcalculate();
});

$(document).on("click", ".addButton", function () {
	var metal = $(".row_material").last();
	if (metal.val() == "") {
		return metal.select2("open");
	}
	$("#TBody").append(mainRow);
	const lastTr = $("#TBody tr").last();
	lastTr.find(".rowid").val(0);
	lastTr.find(".row_material,.lot_wise_rm_id").val("");
	lastTr.find(".touch").val(garnuTouch);
	lastTr.find(".weight, .quantity").val(0);
	lastTr.find(".row_material").select2({
		// width: "200",
		dropdownParent: $("#modal-report"),
	});
	lastTr.find(".lot_wise_rm_id").select2({
		// width: "200",
		dropdownParent: $("#modal-report"),
	});
	lastTr.find(".row_material").last().select2("open");
	Rmcalculate();
});

$(document).on("click", ".deleteRow", function () {
	if ($(".deleteRow").length > 1) {
		$(this).parents("tr").remove();
	}
	Rmcalculate();
});

function scrollEvent(target, pixel = 500) {
	var animated = target.animate(
		{
			scrollTop: target.prop("scrollHeight"),
		},
		pixel
	);
}
$(".rowMetalData").each(function () {
	var rowMetalData = $(this).val();
	if (rowMetalData == "") {
		$(this).parent().find(".total-metal-type").hide();
	} else {
		$(this).parent().find(".total-metal-type").show();
	}
});

$(document).on("click", ".save", function () {
	var count = 0;
	$(".row_material").each(function () {
		var row_material = $(this).val();
		if (row_material == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Row Material and Touch.");
		}
	});
	$(".touch").each(function () {
		var touch = $(this).val();
		if (touch == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Row Material and Touch.");
		}
	});
	count == 0 ? $("#modal-report").modal("hide") : null;
	var totalRmWeight = 0;
	$(".weight").each(function () {
		totalRmWeight += parseFloat($(this).val()) || 0;
	});
	$(".totalRmWeight").val(totalRmWeight);
	autoValueEnter();
});

$(document).on(
	"input",
	".touch,.touch2,.metalTouch,.receivedTouch",
	function () {
		var touch = $(this);
		if (touch.val() > 100) {
			SweetAlert("warning", "Touch should be less than equal to 100"),
				touch.val("");
		}
	}
);

$(document).on("input", ".totalRmWeight,.given_weight", function () {
	autoValueEnter();
});

$("#modal-report").on("shown.bs.modal", function (e) {
	var modal = this;
	$(".row_material").each(function () {
		$(this).select2({
			// width: "500",
			dropdownParent: $(modal),
		});
	});
	$(".lot_wise_rm_id").each(function () {
		$(this).select2({
			// width: "500",
			dropdownParent: $(modal),
		});
	});
	var LastRm = $(".row_material").last();
	if (LastRm.val() == "" || LastRm.val() == 0 || LastRm.val() == null) {
		return LastRm.select2("open");
	}
});

// second modal received
$(document).on("click", ".Received", function (event) {
	event.preventDefault();
	receiveBtn = $(this);
	var garnu_id = $(this).data("garnu_id");
	var given_id = $(this).data("given_id");
	$("#receveData").html("");

	if (garnu_id != "" && given_id != "") {
		$.ajax({
			url: `${BaseUrl}manufacturing/process/receiveGarnu`,
			method: "POST",
			showLoader: true,
			data: {
				garnu_id,
				given_id,
			},
		}).then(function (response) {
			var response = JSON.parse(response);
			if (response.success) {
				$("#receveData").html(response.data);
				$("#id").val("");
				$("#received1-report").modal("show");
			} else {
				SweetAlert("warning", response.message);
			}
		});
	}
});

$("#received1-report").on("shown.bs.modal", function (e) {
	ReceivedMainRow = $(".ReceivedMainRow")[0].outerHTML;

	var modal = this;
	$(".customer").each(function () {
		$(this).select2({
			width: "200",
			dropdownParent: $(modal),
		});
	});
	$(".item").each(function () {
		$(this).select2({
			width: "300",
			dropdownParent: $(modal),
		});
	});
	isCompleted($("#is_completed"));
	isKasar($("#is_kasar"));
	ProcessMetalType();
	$(".totalMetalWeight").val($(".metal-total-weight").text());
	TotalCalculation();
});

$(document).on("click", "#is_completed", function () {
	isCompleted($(this));
});
$(document).on("click", "#is_kasar", function () {
	isKasar($(this));
});

function isCompleted(ref) {
	if (ref.is(":checked")) {
		$(".is_completed").css("color", "green");
	} else {
		$(".is_completed").css("color", "red");
	}
}
function isKasar(ref) {
	if (ref.is(":checked")) {
		$(".is_kasar").css("color", "green");
		
	} else {
		$(".is_kasar").css("color", "red");
	}
	$(".parent-div-party").toggle(ref.is(":checked"));
	
}

$(document).on("click", ".receivedAddButton2", function () {
	// var item  = $(".item ").last();
	// if (item .val() == "") {
	// 	return item .select2("open");
	// }

	$("#ReceivedBody").append(ReceivedMainRow);
	const lastTr = $("#ReceivedBody tr").last();
	lastTr.find(".rcid").val("");
	lastTr
		.find(
			".rcid,.Pcs, .receivedWeight,.receivedFine,.receiveLabour,.receiveTotal_labour "
		)
		.val(0);
	lastTr
		.find(
			".receivedRemark,.rmdata,.item,.receiveLabour_type,.receiveLabour_type"
		)
		.val("");
	lastTr.find(".item").select2({
		width: "300",
		dropdownParent: $("#received1-report"),
	});
	
	lastTr.find(".receiveFinal_labour").attr('data-rmfinallabour', 0);
	lastTr.find(".receiveFinal_labour").attr('data-final_labour', 0);
	lastTr.find(".receiveFinal_labour").val(0);
	lastTr.find(".receivedRmWeight").val(0);
	lastTr.find(".receivedTouch").val(garnuTouch);
	lastTr.find(".receivedfinalWeight").val(0);
	var modalBody = $("#received1-report .modal-body");
	scrollEvent(modalBody, 550);
	TotalCalculation();
});

$(document).on("click", ".receiveddeleteRow", function () {
	if ($(".receiveddeleteRow").length > 1) {
		$(this).parents("tr").remove();
		TotalCalculation();
	}
});

$("#received-report").on("shown.bs.modal", function (e) {
	var modal = this;
	$(".row_material2").each(function () {
		$(this).select2({
			// width: "300",
			dropdownParent: $(modal),
		});
	});
	$(".lot_wise_rm_id2").each(function () {
		$(this).select2({
			width: "200",
			tags: true,
			dropdownParent: $(modal),
		});
	});
	$(".labour_type").each(function () {
		$(this).select2({
			// width: "300",
			dropdownParent: $(modal),
		});
	});
	var LastRm = $(".row_material2").last();
	if (LastRm.val() == "" || LastRm.val() == 0 || LastRm.val() == null) {
		return LastRm.select2("open");
	}
});

$(document).on("click", ".addButton2", function () {
	var LastRm = $(".row_material2").last();
	if (LastRm.val() == "") {
		return LastRm.select2("open");
	}
	$("#JBody").append(mainRmRow);
	const lastTr = $("#JBody tr").last();
	lastTr.find(".rowid2").val(0);
	lastTr.find(".touch2").val(garnuTouch);
	lastTr.find(".weight2, .quantity2,.labour,.total_labour").val(0);
	lastTr.find(".row_material2").select2({
		// width: "200",
		dropdownParent: $("#received-report"),
	});
	lastTr.find(".lot_wise_rm_id2").select2({
		width: "200",
		tags: true,
		dropdownParent: $("#received-report"),
	});
	lastTr.find(".labour_type").select2({
		// width: "300",
		dropdownParent: $("#received-report"),
	});
	lastTr.find(".row_material2").last().select2("open");
	var modalBody = $("#received-report .modal-body");
	scrollEvent(modalBody, 550);
	RmcalculateMain($(".touch2").last());
});

$(document).on("click", ".deleteRow2", function () {
	if ($(".deleteRow2").length > 1) {
		$(this).parents("tr").remove();
	}
	RmcalculateMain($(this).parents("tr").parents("tr"));
});

$(document).on("input", ".touch2,.weight2,.quantity2,.labour", function () {
	RmcalculateMain($(this));
});
$(document).on("change", ".labour_type ", function () {
	RmcalculateMain($(this));
});

$(document).on("click", ".saveRmData", function () {
	var count = 0;
	$(".row_material2").each(function () {
		var row_material = $(this).val();
		if (row_material == 0 || row_material == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Row Material and Touch.");
		}
	});

	$(".touch2").each(function () {
		var touch = $(this).val();
		if (touch == 0 || touch == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Row Material and Touch.");
		}
	});
	count == 0 ? $("#received-report").modal("hide") : null;

	var modal = $("#received-report");
	var container = rmBtn.parents("tr");
	var mainSection = modal.find(".main-row");
	var mainSectionLength = modal.find("tbody tr").length;
	let FilterVar = (el) => {
		if (el == "" || el == undefined || el == NaN) {
			return 0;
		}
		return el;
	};
	var string = "";
	var totalRmWeight = 0;
	var RMtotalLabour = 0;
	for (var i = 0; i < mainSectionLength; i++) {
		var row = mainSection.eq(i);
		var rm = row.find(".row_material2 option:selected").val();
		var lwrm = row.find(".lot_wise_rm_id2 option:selected").val();
		var touch = FilterVar(row.find(".touch2").val());
		var weight = FilterVar(row.find(".weight2").val());
		var quantity = FilterVar(row.find(".quantity2").val());
		var labour_type = FilterVar(row.find(".labour_type").val());
		var labour = FilterVar(row.find(".labour").val());
		var total_labour = FilterVar(row.find(".total_labour ").val());
		var received_detail_id = FilterVar(row.find(".received_detail_id").val());
		totalRmWeight += parseFloat(weight) || 0;
		RMtotalLabour += parseFloat(total_labour) || 0;

		string += [
			rm,
			lwrm,
			touch,
			weight,
			quantity,
			labour_type,
			labour,
			total_labour,
			received_detail_id,
		].join(",");
		if (mainSectionLength > i + 1) string += "|";
	}
	container.find(".rmdata").val(string);
	container.find(".receivedRmWeight").val(totalRmWeight);
	
	container.find(".receiveFinal_labour").attr('data-rmfinallabour', RMtotalLabour);
	var receiveTotal_labour = container.find(".receiveTotal_labour").val();
    container.find(".receiveFinal_labour").val(parseFloat(RMtotalLabour) + parseFloat(receiveTotal_labour));
	finalCalculation(rmBtn);
	TotalCalculation();
});

function finalCalculation(i) {
	var container = i.parents("tr");
	var receivedWeight = container.find(".receivedWeight").val() || 0;
	var Pcs = container.find(".Pcs").val() || 0;
	var labourdata = container.find(".receiveLabour").val() || 0;
	var receivedRmWeight = container.find(".receivedRmWeight").val() || 0;
	var receivedTouch = container.find(".receivedTouch").val() || 0;
	var receiveLabour = 0;
	var receiveTotal_labour = 0;
	var receivedRmfinalWeight = formatNumber(
		parseFloat(receivedWeight) + parseFloat(receivedRmWeight)
	);
	var receivedFine = formatNumber(
		(receivedRmfinalWeight * parseFloat(receivedTouch)) / 100
	);

	container.find(".receivedfinalWeight").val(receivedRmfinalWeight);
	container.find(".receivedFine").val(receivedFine);
	
	var labour_type = container.find(".receiveLabour_type");
	if (labour_type.val() == "PCS") {
		container.find(".receiveTotal_labour").val(formatNumber(Pcs * labourdata));
	} else if (labour_type.val() == "WEIGHT") {
		container
			.find(".receiveTotal_labour")
			.val(formatNumber(receivedWeight * labourdata));
	} else {
		container.find(".receiveTotal_labour").val("0");
	}

	$(".receiveLabour").each(function () {
		receiveLabour += parseFloat($(this).val() || 0);
	});
	$(".receiveTotal_labour").each(function () {
		receiveTotal_labour += parseFloat($(this).val() || 0);
	});

	$("#labour").text(formatNumber(receiveLabour));
	$("#labourTotal").text(formatNumber(receiveTotal_labour));
	
	var receiveTotal_labour = container.find(".receiveTotal_labour").val();
	var RMtotalLabour = container.find(".receiveFinal_labour").attr('data-rmfinallabour');
    container.find(".receiveFinal_labour").val(parseFloat(RMtotalLabour) + parseFloat(receiveTotal_labour));
}

$(document).on(
	"input",
	".receivedWeight,.receivedTouch,.Pcs,.receiveLabour",
	function () {
		finalCalculation($(this));
		TotalCalculation();
	}
);
$(document).on("change", ".receiveLabour_type", function () {
	finalCalculation($(this));
	TotalCalculation();
});
// $(document).on("input", ".Pcs,.receivedTouch", function () {
// 	finalCalculation($(this));
// 	TotalCalculation();
// });

$(document).on("click", ".Receivedmaterial", function () {
	$(".addButton2").show();
	$(".saveRmData").show();
	$(".hide_labour").show();

	rmBtn = $(this);
	var modal = $("#received-report");
	var givenContainer = rmBtn.parents("tr");
	var mainSection = modal.find(".main-row");
	modal.find("tbody").html("");
	var string = givenContainer.find(".rmdata").val();
	var data = string?.split("|");
	mainSectionLength = data?.length ?? 0;
	if (mainSectionLength > 0) {
		for (var i = 0; i < mainSectionLength; i++) {
			modal.find("tbody").append(mainRmRow);
			var row = modal.find(".main-row").eq(i),
				splitByHash = data[i]?.split(","),
				row_material2 = splitByHash[0] ?? 0,
				lot_wise_rm_id2 = splitByHash[1] ?? "",
				touch2 = splitByHash[2] ?? garnuTouch,
				weight2 = splitByHash[3] ?? 0;
			quantity2 = splitByHash[4] ?? 0;
			labour_type = splitByHash[5] ?? "";
			labour = splitByHash[6] ?? 0;
			total_labour = splitByHash[7] ?? 0;
			received_detail_id = splitByHash[8] ?? 0;
			row.find(".row_material2").prop("disabled", false);
			row.find(".row_material2").val(row_material2).trigger("change");
			if (!isNaN(lot_wise_rm_id2) && Number.isInteger(parseFloat(lot_wise_rm_id2))) {
				row.find(".lot_wise_rm_id2").val(lot_wise_rm_id2);
			} else {
				var newOption = new Option(lot_wise_rm_id2, lot_wise_rm_id2, true, true);
				row.find(".lot_wise_rm_id2").append(newOption).trigger('change');
			}
			
			row.find(".touch2").val(touch2);
			row.find(".weight2").val(weight2);
			row.find(".quantity2").val(quantity2);
			row.find(".labour_type").val(labour_type);
			row.find(".labour").val(labour);
			row.find(".total_labour").val(total_labour);
			row.find(".received_detail_id").val(received_detail_id);

			RmcalculateMain(row);
		}
	} else {
		modal.find("tbody").append(mainRmRow);
	}
	modal.find(".modal-lg").removeClass("modal-lg").addClass("modal-xl");
	modal.modal("show");
});

function RmcalculateMain(ref) {
	var row = ref.parents("tr");
	var Totaltouch = 0;
	var Totalweight = 0;
	var Totalqty = 0;
	var labour = 0;
	var totalLabour = 0;

	var weight = row.find(".weight2");
	var qty = row.find(".quantity2");
	var labourdata = row.find(".labour");
	var labour_type = row.find(".labour_type");

	if (labour_type.val() == "PCS") {
		row.find(".total_labour").val(formatNumber(qty.val() * labourdata.val()));
	} else if (labour_type.val() == "WEIGHT") {
		row
			.find(".total_labour")
			.val(formatNumber(weight.val() * labourdata.val()));
	} else {
		row.find(".total_labour").val("0");
	}

	$(".touch2").each(function () {
		Totaltouch += parseFloat($(this).val() || 0);
	});
	$(".weight2").each(function () {
		Totalweight += parseFloat($(this).val() || 0);
	});
	$(".quantity2").each(function () {
		Totalqty += parseFloat($(this).val() || 0);
	});

	$(".labour").each(function () {
		labour += parseFloat($(this).val() || 0);
	});
	$(".total_labour").each(function () {
		totalLabour += parseFloat($(this).val() || 0);
	});

	$(".total-touch").text(formatNumber(Totaltouch));
	$(".total-weight").text(formatNumber(Totalweight));
	$(".total-qty").text(formatNumber(Totalqty));
	$(".total-labour").text(formatNumber(labour));

	$(".final-labour").text(formatNumber(totalLabour));
}

$(document).on("submit", "#received-garnu", function (e) {
	e.preventDefault();
	$('.lot_creation').each(function(index) {
		var hiddenInput = $('input[name="lot_creation_value[]"]').eq(index);
		hiddenInput.val(this.checked ? "YES" : "NO");
	});

	var formData = $(this).serialize();
	var self = $(this);
	var metalData = $(".metaldata").val();
	$.ajax({
		url: `${BaseUrl}manufacturing/process/receiveGarnuAdd`,
		type: "POST",
		data: formData,
		beforeSend: (data) => {
			self.find(".submit-btn span").show();
			self.find(".submit-btn").attr("disabled", true);
			// ShowBlockUi('#received1-report');
		},
		success: function (response) {
			var response = JSON.parse(response);
			var row = receiveBtn.parents("tr");
			if (response.success === true) {
				location.reload();
				row.find(".totalPcs").text($("#totalPcs").text());
				row.find(".totalWeight").text($("#TotalWeight").text());
				row.find(".rowMaterialWeight").text($("#rowMaterialWeight").text());
				row.find(".totalFinalWeight").text($("#totalFinalWeight").text());
				var jamaBaki = "";
				if ($(".jama_baki").val() > 0) {
					jamaBaki = `<h4 class='text-danger'>ધટાડો : ${$(
						".jama_baki"
					).val()} </h4>`;
				} else if ($(".jama_baki").val() == 0) {
					jamaBaki = `<h4 class='text-success'>સરભર : ${$(
						".jama_baki"
					).val()} </h4>`;
				} else {
					jamaBaki = `<h4 class='text-success'>વધારો : ${$(
						".jama_baki"
					).val()} </h4>`;
				}
				row.find(".vadharo_dhatado").html(jamaBaki);
				if ($("#is_completed").is(":checked")) {
					row.find(".is_completed").prop("checked", true);
				} else {
					row.find(".is_completed").prop("checked", false);
				}
				row.find(".rowMetalData").val($(".metaldata").val());
				if (row.find(".rowMetalData").val() != "") {
					row.find(".total-metal-type").show();
				}
				FetchCode();
				$("#received1-report").modal("hide");
				SweetAlert("success", response.message);
			} else {
				if ($("#is_completed").is(":checked")) {
					row.find(".is_completed").prop("checked", true);
				} else {
					row.find(".is_completed").prop("checked", false);
				}
				$("#received1-report").modal("hide");
				SweetAlert("error", response.message);
			}
		},
		error: function () {
			SweetAlert("error", "Error submitting form");
		},
		complete: () => {
			self.find(".submit-btn span").hide();
			self.find(".submit-btn").attr("disabled", false);
		},
	});
});

function TotalCalculation() {
	var totalPcs = 0;
	var receivedWeight = 0;
	var receivedRmWeight = 0;
	var receivedfinalWeight = 0;
	var receivedTouch = 0;
	var receivedFine = 0;
	var receiveLabour = 0;
	var receiveTotal_labour = 0;

	$(".Pcs").each(function () {
		totalPcs += parseFloat($(this).val() || 0);
	});
	$(".receivedWeight").each(function () {
		receivedWeight += parseFloat($(this).val() || 0);
	});
	$(".receivedRmWeight").each(function () {
		receivedRmWeight += parseFloat($(this).val() || 0);
	});
	$(".receivedfinalWeight").each(function () {
		receivedfinalWeight += parseFloat($(this).val() || 0);
	});
	$(".receivedTouch").each(function () {
		receivedTouch += parseFloat($(this).val() || 0);
	});
	$(".receivedFine").each(function () {
		receivedFine += parseFloat($(this).val() || 0);
	});

	$(".receiveLabour").each(function () {
		receiveLabour += parseFloat($(this).val() || 0);
	});
	$(".receiveTotal_labour").each(function () {
	    var row = $(this);
		receiveTotal_labour += parseFloat($(this).val() || 0);
	    var RMtotalLabour = row.next().data('rmfinallabour');
    	if(row.parent().find(".receiveFinal_labour").val() < 0){
    	    row.parent().find(".receiveFinal_labour").val(parseFloat(RMtotalLabour) + parseFloat($(this).val() || 0));
    	}
	});

	$("#totalPcs").text("");
	$("#totalPcs").text(totalPcs);
	$("#TotalWeight").text("");
	$("#TotalWeight").text(formatNumber(receivedWeight));
	$("#rowMaterialWeight").text("");
	$("#rowMaterialWeight").text(formatNumber(receivedRmWeight));
	$("#totalFinalWeight").text("");
	$("#totalFinalWeight").text(formatNumber(receivedfinalWeight));
	$("#totalFinalWeight").text("");
	$("#totalFinalWeight").text(formatNumber(receivedfinalWeight));
	$("#totalFinalWeight").text("");
	$("#totalFinalWeight").text(formatNumber(receivedfinalWeight));
	$("#totalTouch").text("");
	$("#totalTouch").text(formatNumber(receivedTouch));
	$("#totalFine").text("");
	$("#totalFine").text(formatNumber(receivedFine));
	$("#labour").text("");
	$("#labour").text(formatNumber(receiveLabour));
	$("#labourTotal").text("");
	$("#labourTotal").text(formatNumber(receiveTotal_labour));

	var Total =
		$("#givenTotal_weight").text() -
		(receivedfinalWeight + parseFloat($(".totalMetalWeight").val()));
	let formattedTotal = formatNumber(Total);
	var jamaBaki = "";
	if (formattedTotal > 0) {
		jamaBaki = `<h4 class='text-danger'>ધટાડો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
	} else if (formattedTotal == 0) {
		jamaBaki = `<h4 class='text-success'>સરભર :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
	} else {
		jamaBaki = `<h4 class='text-success'>વધારો :- <span class='ps-3'> ${formattedTotal} </span></h4>`;
	}
	$(".jama_baki").val(formattedTotal);
	$("#jama_baki").html("");
	$("#jama_baki").html(jamaBaki);
}

// $(document).on("input", ".touch,.weight,.quantity", function () {
// 	Rmcalculate();
// });

$(document).on("input", ".weight, .quantity", function () {
	var ref = $(this).closest("tr");
	var lot_wise_rm_id = ref.find(".lot_wise_rm_id");
	var weight = lot_wise_rm_id.find("option:selected").data("weight");
	var quantity = lot_wise_rm_id.find("option:selected").data("quantity");
	var oldweight = lot_wise_rm_id.find("option:selected").data("oldweight");
	var oldquantity = lot_wise_rm_id.find("option:selected").data("oldquantity");

	
	if (lot_wise_rm_id.find("option").length > 1 && lot_wise_rm_id.val() === "") {
		ref.find(".weight").val("0");
		ref.find(".quantity").val("0");
		lot_wise_rm_id.select2("open");
	}
	if($('.given_id').val() != ""){
		var  totalWeight = parseFloat(weight) + parseFloat(oldweight);
		var  totalquantity = parseFloat(quantity) + parseFloat(oldquantity);
		if (totalWeight < parseFloat(ref.find(".weight").val())) {
			ref.find(".weight").val("");
			SweetAlert("warning", `weight should be less than equal to ${totalWeight}`);
		}
		if (totalquantity < parseFloat(ref.find(".quantity").val())) {
			ref.find(".quantity").val("");
			SweetAlert("warning", `quantity should be less than equal to ${totalquantity}`);
		}
	}else{
		if (weight < parseFloat(ref.find(".weight").val())) {
			ref.find(".weight").val("");
			SweetAlert("warning", `weight should be less than equal to ${weight}`);
		}
		if (quantity < parseFloat(ref.find(".quantity").val())) {
			ref.find(".quantity").val("");
			SweetAlert("warning", `quantity should be less than equal to ${quantity}`);
		}
	}
	Rmcalculate();
});

function Rmcalculate() {
	var Totaltouch = 0;
	var Totalweight = 0;
	var Totalqty = 0;

	$(".weight").each(function () {
		Totalweight += parseFloat($(this).val() || 0);
	});
	$(".touch").each(function () {
		Totaltouch += parseFloat($(this).val() || 0);
	});
	$(".quantity").each(function () {
		Totalqty += parseFloat($(this).val() || 0);
	});

	$(".total-touch").text("");
	$(".total-weight").text("");
	$(".total-net_weight").text("");

	$(".total-touch").text(formatNumber(Totaltouch));
	$(".total-weight").text(formatNumber(Totalweight));
	$(".total-qty").text(formatNumber(Totalqty));
}

$(document)
	.on(
		"focus",
		".touch,.weight,.quantity,.Pcs,.receivedWeight,.receiveLabour,.touch2, .weight2, .quantity2,.given-qty,.labour,.metalQuantity,.metalWeight,.metalTouch,.receivedTouch,.receivedFine,.given_weight",
		function () {
			handleInputFocusAndBlur(this, "focus");
		}
	)
	.on(
		"blur",
		".touch,.weight,.quantity,.Pcs,.receivedWeight,.receiveLabour,.touch2, .weight2, .quantity2,.given-qty,.labour,.metalQuantity,.metalWeight,.metalTouch,.receivedTouch,.receivedFine,.given_weight",
		function () {
			handleInputFocusAndBlur(this, "blur");
		}
	);

function handleInputFocusAndBlur(element, eventType) {
	var $element = $(element);
	if (eventType === "focus" && $element.val() == "0") {
		$element.val("");
	} else if (eventType === "blur" && $element.val() == "") {
		$element.val("0");
	}
}

function ProcessMetalType() {
	$(".metalAddButton").show();
	$(".saveMetalData").show();
	$(".metalDeleteRow").each(function () {
		$(this).show();
	});

	var modal = $("#metalType-report");
	var mainSection = modal.find(".metalRow");
	modal.find("tbody").html("");
	var string = $(".ProcessMetalType").parent().find(".metaldata").val();
	var data = string?.split("|");
	mainSectionLength = data?.length ?? 0;
	if (mainSectionLength > 0) {
		for (var i = 0; i < mainSectionLength; i++) {
			modal.find("tbody").append(metalRow);
			var row = modal.find(".metalRow").eq(i),
				splitByHash = data[i]?.split(","),
				metal_type = splitByHash[0] ?? 0,
				touch = splitByHash[1] ?? garnuTouch,
				weight = splitByHash[2] ?? 0;
			quantity = splitByHash[3] ?? 0;
			process_metal_type = splitByHash[4] ?? 0;
			row.find(".metal_type ").val(metal_type).trigger("change");
			row.find(".metalTouch").val(touch);
			row.find(".metalWeight").val(weight);
			row.find(".metalQuantity").val(quantity);
			row.find(".process_metal_type").val(process_metal_type);
			Metalcalculate();
		}
	} else {
		modal.find("tbody").append(metalRow);
	}
}

$(document).on("click", ".ProcessMetalType", function () {
	ProcessMetalType();
	var modal = $("#metalType-report");
	modal.modal("show");
});

$("#metalType-report").on("shown.bs.modal", function () {
	Metalcalculate();
	var modal = this;
	$(".metal_type").each(function () {
		$(this).select2({
			width: "250",
			dropdownParent: $(modal),
		});
	});
});

function Metalcalculate() {
	var metalTotalweight = 0;
	var metalTotaltouch = 0;
	var metalTotalqty = 0;

	$(".metalWeight").each(function () {
		metalTotalweight += parseFloat($(this).val() || 0);
	});
	$(".metalTouch").each(function () {
		metalTotaltouch += parseFloat($(this).val() || 0);
	});
	$(".metalQuantity").each(function () {
		metalTotalqty += parseFloat($(this).val() || 0);
	});

	$(".metal-total-touch").text("");
	$(".metal-total-weight").text("");
	$(".metal-total-net_weight").text("");

	$(".metal-total-weight").text(formatNumber(metalTotalweight));
	$(".metal-total-touch").text(formatNumber(metalTotaltouch));
	$(".metal-total-qty").text(formatNumber(metalTotalqty));
}

$(document).on("click", ".metalAddButton", function () {
	var LastRm = $(".metal_type").last();
	if (LastRm.val() == "") {
		return LastRm.select2("open");
	}
	$("#MetalBody").append(metalRow);
	const lastTr = $("#MetalBody tr").last();
	lastTr.find(".metalTouch").val(garnuTouch);
	lastTr.find(".metalWeight,.metalQuantity").val(0);
	lastTr.find(".metal_type").select2({
		width: "250",
		dropdownParent: $("#metalType-report"),
	});
	lastTr.find(".metal_type").last().select2("open");
	var modalBody = $("#metalType-report .modal-body");
	scrollEvent(modalBody, 550);
	Metalcalculate();
});

$(document).on("click", ".metalDeleteRow", function () {
	if ($(".metalDeleteRow").length > 1) {
		$(this).parents("tr").remove();
	}
	Metalcalculate();
});

$(document).on("input", ".metalWeight,.metalTouch,.metalQuantity", function () {
	Metalcalculate();
});

$(document).on("click", ".saveMetalData", function () {
	var count = 0;
	$(".metal_type").each(function () {
		var metal_type = $(this).val();
		if (metal_type == 0 || metal_type == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Metal Type and Touch.");
		}
	});

	$(".metalTouch").each(function () {
		var metalTouch = $(this).val();
		if (metalTouch == 0 || metalTouch == "") {
			count += 1;
			SweetAlert("warning", "Please Enter Metal Type and Touch.");
		}
	});
	count == 0 ? $("#metalType-report").modal("hide") : null;

	var modal = $("#metalType-report");
	var container = $(".ProcessMetalType").parent();
	var mainSection = modal.find(".metalRow");
	var mainSectionLength = modal.find("tbody tr").length;
	let FilterVar = (el) => {
		if (el == "" || el == undefined || el == NaN) {
			return 0;
		}
		return el;
	};
	var string = "";
	var totalRmWeight = 0;
	for (var i = 0; i < mainSectionLength; i++) {
		var row = mainSection.eq(i);
		var mt = row.find(".metal_type  option:selected").val();
		var metalTouch = FilterVar(row.find(".metalTouch").val());
		var metalWeight = FilterVar(row.find(".metalWeight").val());
		var metalQuantity = FilterVar(row.find(".metalQuantity").val());
		var process_metal_type = FilterVar(row.find(".process_metal_type").val());
		string += [
			mt,
			metalTouch,
			metalWeight,
			metalQuantity,
			process_metal_type,
		].join(",");
		if (mainSectionLength > i + 1) string += "|";
	}
	container.find(".metaldata").val(string);
	$(".totalMetalWeight").val($(".metal-total-weight").text()); //
	TotalCalculation();
});

// given row material
$(document).on("click", ".given-row-material", function () {
	// trRef = $(this);
	var given_id = $(this).data("given_id");
	var garnu_id = $(this).data("garnu_id");
	$(".sectiontocopy").not(":first").remove();
	$(".append-here tr").first().find(".metal-weight,.metal-quantity").val(0);
	$(".append-here tr").first().find(".metal_type_id").val("");
	$(".append-here tr")
		.first()
		.find(".metal_type_id")
		.select2({
			width: "100",
			dropdownParent: $("#givenRowMaterial"),
		});

	$(".metal_type_id").each(function () {
		$(this).select2({
			width: "100",
			dropdownParent: $("#givenRowMaterial"),
		});
	});

	givenRowMaterial(given_id, garnu_id).done(function () {
		usedRowMaterialCalculation();
	});
});

function setAddRow(response) {
	if (response.success) {
		garnuName = response.garnuData.name;
		garnuWeight = response.garnuData.garnu_weight;

		$(".garnu_weight").text("");
		$(".garnu_name").text("");
		$(".garnu_weight").text(garnuWeight);
		$(".garnu_name").text(garnuName);

		$(".append-here").not(":first").remove();
		// var $lastRow;
		var $lastRow = $(".append-here tr").last();
		$lastRow.find(".given-touch").val("0").trigger("change");
		$lastRow.find(".given-weight").val("0").trigger("change");
		$lastRow.find(".given-quantity").val("0").trigger("change");
		$lastRow.find(".given-row_material_id").val("0");

		if (response.data != "") {
			$(response.data).each(function (index, value) {
				if (index == 0) {
					$lastRow = $(".append-here tr").last();
					$lastRow.parent().find(".ids").val(value.id);
				} else {
					$(".append-here").append(usedmetal);
					$lastRow = $(".append-here tr").last();
				}

				$lastRow.find(".given-touch").val(value.touch).trigger("change");
				$lastRow.find(".given-weight").val(value.weight).trigger("change");
				$lastRow.find(".given-quantity").val(value.quantity).trigger("change");
				$lastRow
					.find(".given-row_material_id")
					.val(value.row_material_id)
					.trigger("change")
					.select2({
						width: "100%",
						dropdownParent: $("#givenRowMaterial"),
					});

				var LastRm = $lastRow.find(".given-row_material_id");
				if (
					LastRm.val() == " " ||
					LastRm.val() == "0" ||
					LastRm.val() == null
				) {
					return LastRm.select2("open");
				}
			});
		} else {
			$(".given-touch").last().val("0");
		}
	}
}

function givenRowMaterial(given_id = null, garnu_id = null) {
	return $.ajax({
		showLoader: true,
		url: `${BaseUrl}manufacturing/process/givenRowMaterial`,
		type: "POST",
		dataType: "json",
		data: {
			given_id,
			garnu_id,
		},
		success: function (response) {
			if (response.data != "") {
				setAddRow(response);
				$("#givenRowMaterial").modal("show");
			} else {
				SweetAlert("warning", "Row Material was Not Found.");
				return false;
			}
		},
		error: function () {
			alert("An error occurred.");
		},
	});
}

function usedRowMaterialCalculation() {
	var Totaltouch = 0;
	var Totalweight = 0;
	var Totalnet_weight = 0;

	$(".given-weight").each(function () {
		Totalweight += parseFloat($(this).val() || 0);
	});
	$(".given-touch").each(function () {
		Totaltouch += parseFloat($(this).val() || 0);
	});
	$(".given-quantity").each(function () {
		Totalnet_weight += parseFloat($(this).val() || 0);
	});

	$(".given-total-touch").text("");
	$(".given-total-weight").text("");
	$(".given-total-quantity").text("");

	$(".given-total-touch").text(formatNumber(Totaltouch));
	$(".given-total-weight").text(formatNumber(Totalweight));
	$(".given-total-quantity").text(formatNumber(Totalnet_weight));
}

// received row material
$(document).on("click", ".receive-row-material", function () {
	// trRef = $(this);
	var given_id = $(this).data("given_id");
	var garnu_id = $(this).data("garnu_id");
	$(".sectiontocopy").not(":first").remove();
	$(".append-here tr").first().find(".metal-weight,.metal-quantity").val(0);
	$(".append-here tr").first().find(".metal_type_id").val("");
	$(".append-here tr")
		.first()
		.find(".metal_type_id")
		.select2({
			width: "100",
			dropdownParent: $("#givenRowMaterial"),
		});

	$(".metal_type_id").each(function () {
		$(this).select2({
			width: "100",
			dropdownParent: $("#givenRowMaterial"),
		});
	});

	receiveRowMaterial(given_id, garnu_id).done(function () {
		usedRowMaterialCalculation();
	});
});

function receiveRowMaterial(given_id = null, garnu_id = null) {
	return $.ajax({
		showLoader: true,
		url: `${BaseUrl}manufacturing/process/receiveRowMaterial`,
		type: "POST",
		dataType: "json",
		data: {
			given_id,
			garnu_id,
		},
		success: function (response) {
			if (response.data != "") {
				setAddRow(response);
				$("#givenRowMaterial").modal("show");
			} else {
				SweetAlert("warning", "Row Material was Not Found.");
				return false;
			}
		},
		error: function () {
			alert("An error occurred.");
		},
	});
}

// used Metal type
$(document).on("click", ".total-metal-type", function () {
	$(".metalAddButton").hide();
	$(".saveMetalData").hide();

	var modal = $("#metalType-report");
	var mainSection = modal.find(".metalRow");
	modal.find("tbody").html("");
	var string = $(this).parent().find(".rowMetalData").val();
	var data = string?.split("|");
	mainSectionLength = data?.length ?? 0;
	if (mainSectionLength > 0) {
		for (var i = 0; i < mainSectionLength; i++) {
			modal.find("tbody").append(metalRow);
			var row = modal.find(".metalRow").eq(i),
				splitByHash = data[i]?.split(","),
				metal_type = splitByHash[0] ?? 0,
				touch = splitByHash[1] ?? 0,
				weight = splitByHash[2] ?? 0;
			quantity = splitByHash[3] ?? 0;
			process_metal_type = splitByHash[4] ?? 0;
			row.find(".metal_type ").val(metal_type).trigger("change");
			row.find(".metal_type ").prop("disabled", true);
			row.find(".metalTouch").val(touch);
			row.find(".metalWeight").val(weight);
			row.find(".metalQuantity").val(quantity);
			row.find(".process_metal_type").val(process_metal_type);
			row.find(".metalDeleteRow").hide();
		}
		Metalcalculate();
	} else {
		modal.find("tbody").append(metalRow);
	}
	modal.modal("show");
});

$(document).on("change", ".receiveCode", function () {
	var code = $(this).val();
	$.ajax({
		showLoader: true,
		url: `${BaseUrl}manufacturing/process/fechWeight`,
		type: "POST",
		dataType: "json",
		data: {
			code,
		},
		success: function (response) {
			if (response.success) {
				$(".given_weight").val(response.data.total_weight);
			} else {
				$(".given_weight").val(0);
				SweetAlert("warning", response.message);
			}
			autoValueEnter();
		},
		error: function () {
			alert("An error occurred.");
		},
	});
});

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

function FetchCode() {
	var garnu_id = $(".garnu_id").val();
	$.ajax({
		type: "POST",
		url: `${BaseUrl}manufacturing/process/fechCode`,
		data: { garnu_id: garnu_id },
		dataType: "json",
		success: function (response) {
			if (response.success) {
				// $(".receiveCode ").parent().removeClass("d-none");
				var option = '<option value=""> Select Code </option>';
				if (response.data != "") {
					$(".receiveCode").html("");
					response.data.forEach((element) => {
						option += `<option value="${element.code}"> ${element.code} </option>`;
					});
				}
				$(".receiveCode").html(option);
			} else {
				$(".receiveCode ").parent().addClass("d-none");
			}
		},
	});
}

$(document).on("click", ".givenMaterial", function () {
	rmBtn = $(this);
	var modal = $("#received-report");
	var givenContainer = rmBtn.parents("tr");
	var mainSection = modal.find(".main-row");
	modal.find("tbody").html("");
	var string = givenContainer.find(".givenRmdata").val();
	var data = string?.split("|");
	mainSectionLength = data?.length ?? 0;
	if (mainSectionLength > 0) {
		for (var i = 0; i < mainSectionLength; i++) {
			modal.find("tbody").append(mainRmRow);
			var row = modal.find(".main-row").eq(i),
				splitByHash = data[i]?.split(","),
				row_material2 = splitByHash[0] ?? 0,
				lot_wise_rm_id2 = splitByHash[1] ?? 0,
				touch2 = splitByHash[2] ?? 0,
				weight2 = splitByHash[3] ?? 0;
			quantity2 = splitByHash[4] ?? 0;
			received_detail_id = splitByHash[5] ?? 0;
			row.find(".row_material2 ").val(row_material2).trigger("change");
			row.find(".lot_wise_rm_id2 ").val(lot_wise_rm_id2).trigger("change");
			row.find(".touch2").val(touch2);
			row.find(".weight2").val(weight2);
			row.find(".quantity2").val(quantity2);
			row.find(".received_detail_id").val(received_detail_id);
			row.find(".deleteRow2").hide();
			row.find(".row_material2 ").prop("disabled", true);
			row.find(".lot_wise_rm_id2 ").prop("disabled", true);

			$(".addButton2").hide();
			$(".saveRmData").hide();
			$(".hide_labour").hide();

			modal.find(".modal-xl").removeClass("modal-xl").addClass("modal-lg");

			RmcalculateMain(rmBtn);
		}
	} else {
		modal.find("tbody").append(mainRmRow);
	}
	modal.modal("show");
});

$(".given_print").click(function (event) {
	event.preventDefault();
	var width = 1200;
	var height = 600;
	var left = (screen.width - width) / 2;
	var top = (screen.height - height) / 2;
	var options =
		"width=" + width + ",height=" + height + ",top=" + top + ",left=" + left;
	window.open($(this).attr("href"), "_blank", options);
});
$(document).ready(function () {
	var table = $("#row_materialTable").DataTable({
		iDisplayLength: 6,
		lengthMenu: [
			[6,10, 25, 50, 100, 500, 1000, 5000],
			[6,10, 25, 50, 100, 500, 1000, 5000],
		],
		processing: true,
		serverSide: true,
		destroy: true,
		serverMethod: "post",
		searching: true,
		ajax: {
			url: `${BaseUrl}manufacturing/process/getRowMaterials`,
			data: function (data) {
				data.garnu_id = $(".garnu_id").val() ?? null;
			},
		},
		columns: [
			{
				data: "id",
			},
			{
				data: "row_material",
			},
			{
				data: "process_name",
			},
			{
				data: "type",
				render: function (data, type, row) {
					if (row["type"] == "Received") {
						return `<span class='text-success'>Received</span>`;
					} else if (row["type"] == "Given") {
						return `<span class='text-danger'>Given</span>`;
					}
				},
			},
			{
				data: "touch",
			},
			{
				data: "weight",
			},
			{
				data: "quantity",
			},
		],
	});
});
