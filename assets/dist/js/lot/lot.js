var isItemPlan = false;

var adTable = $("#data_ad_table");
var mainRow = adTable.children("tbody").html();
adTable.children("tbody").html("");

var reportTable = $("#report_table");
var mainReportTableRow = reportTable.children("tbody").html();
reportTable.children("tbody").html("");

$(document).ready(function () {
	// console.log(mainRow);

	$(document).on("click", ".close-btn-dada-row", function () {
		$(this).parent().parent().remove();
		calculateNetAmount();
	});

	$(document).on("click", "#btn-dada-row-add", function () {
		// console.log(mainRow);
		adTable.children("tbody").append(mainRow);
	});

	var grossWeight = $(".gross-weight");
	var tLotPcs = $(".t-lot-pcs");
	var pendingPcs = $(".pending-pcs");
	var donePcs = $(".done-pcs");
	var selectCustomer = $('select[name="m_customer"]');
	var selectItem = $('select[name="m_item"]');
	var selectGroup = $('select[name="m_group"]');
	var designCode = $("#input-design-code");
	var designCodeImage = $(".design-code-image");

	var inputGrossWeight = $("#input-gross-weight");
	var inputNetWeight = $("#input-net-weight");

	$(document).on("keyup", "#input-gross-weight", function () {
		if (!$('input[name="m_l_weight"]').prop("checked")) {
			inputNetWeight.val($(this).val());
		}
	});

	$(document).on("change", 'input[name="m_l_weight"]', function () {
		if ($(this).prop("checked")) {
			adTable.css("display", "");
		} else {
			adTable.css("display", "none");
		}
	});

	$(document).on("blur", "#barcode", function () {
		var barcode = $(this).val().trim();
		$(".autodatanotshown").hide();
		if (barcode) {
			$.ajax({
				url: `${BaseUrl}manufacturing/lot/receiveBarcode/${barcode}`,
				showLoader: true,
				success: function (data) {
					data = jQuery.parseJSON(data);
					if (data.success) {
						data = data.data;
						SweetAlert("success", "Tag Data Fetched SuccessFully!");
						setReport(data);
						grossWeight.html(data.data.total_weight);
						tLotPcs.html(data.data.pcs);
						selectCustomer.val(data.data.customer_id).trigger("change");
						selectItem.val(data.data.item_id).trigger("change");
						selectGroup.val(data.data.group_id).trigger("change");
						designCode.val(data.data.design_code);

						isItemPlan = data.data.is_item_cat_plan;
						if ($("#isLWeight").prop("checked")) {
							$("#input-extra-amt").val(data.data.ad_price).trigger("keyup");
						}

						$("#source_from").val(data.data.source_from);

						if (data.data.source_from == "in_house") {
							$("#second_polish_id").val(data.data.id);
						} else {
							$("#purchase_items_id").val(data.data.id);
						}
						$("#output-of-barcode").html("");
						adTable.children("tbody").html("");
						var totalAmt = parseFloat(0);
						$("#select-design-code").html("");
						var iOption =
							'<option data-design_code="' +
							0 +
							'" value="">Select Design Code</option>';
						$("#select-design-code").append(iOption);

						var cgwr = parseFloat(data.data.total_weight) || 0;
						var cgrr = parseFloat(data.data.weight) || 0;
						var nrg = cgwr + 0.1;
						var npre = cgwr - 0.1;
					} else {
						SweetAlert("error","Something Went Wrong");
					}
				},
				complete: function (data) {
					$(".to-close").parent().prev().children("button").trigger("click");
					$(".close").trigger("click");
				},
			});
		}
	});

	$(document).on("keyup", ".input-actual-weight", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateGrossWeight();
	});

	$(document).on("keyup", ".input-master-weight", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateGrossWeight();
	});

	$(document).on("keyup", ".input-weight", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateGrossWeight();
	});

	$(document).on("keyup", "#input-extra-amt", function () {
		calculateGrossWeight();
		calculateNetAmount();
	});

	$(document).on("keyup", "#input-gross-weight", function () {
		calculateGrossWeight();
	});

	$(document).on("keyup", ".input-sal-per", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateNetAmount();
	});

	$(document).on("keyup", ".input-sal-amt", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateNetAmount();
	});

	$(document).on("keyup", ".input-sal-rate", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateNetAmount();
	});
	$(document).on("input", "#input-less-weight,#input-net-weight", function () {
	    calculateGrossWeight();
	});

	$(document).on("input", "#input-pcs", function () {
		var totalPcs = parseInt($(".t-lot-pcs").text(), 10) || 0;
		var donePcs = parseInt($(".done-pcs").text(), 10) || 0;
		var pcs = parseInt($(this).val(), 10) || 0;
		var submitType = $("#submit_type").val();
		var maxPcs =
			submitType === "update"
				? parseInt($(".pending-pcs").text(), 10)
				: totalPcs - donePcs;

		if (pcs > maxPcs) {
			$(this).val("");
			if (submitType === "insert") {
				$(".pending-pcs").text(
					submitType === "insert" ? maxPcs : totalPcs - donePcs
				);
			}
			Swal.fire({
				icon: "warning",
				title: "Warning",
				text: `Pcs should be less than or equal to ${maxPcs}`,
			});
		} else if (submitType === "insert") {
			$(".pending-pcs").text(maxPcs - pcs);
		}
	});

	$(document).on("keyup", ".input-ad-rate", function () {
		var row = $(this).parent().parent();
		calculateAmtWr(row);
		calculateNetAmount();
	});

	$(document).on("change", ".select-amt-calc-on", function () {
		var row = $(this).parent().parent().parent();
		calculateAmtWr(row);
		calculateNetAmount();
	});

	$(document).on("click", ".button__select-deselect_all", async function () {
		const text = $(this).html();
		if (text === "Select All") {
			await $(this).html("Deselect All");
			await $(".input-checkbox__to-print:checkbox").each(function (i, v) {
				$(v).prop("checked", true);
			});
		}
		if (text === "Deselect All") {
			await $(this).html("Select All");
			await $(".input-checkbox__to-print:checkbox").each(function (i, v) {
				$(v).prop("checked", false);
			});
		}
	});

	$(document).on("click", ".button__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any barcode");
		} else {
			$('input[name="checked_tags"]').val(selectedBarcodes.join());
			$("#form-print_custom_tags").submit();
		}
	});

	$(document).on("click", ".button_old__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any Tag");
		} else {
			$('input[name="checked_old_tags"]').val(selectedBarcodes.join());
			$("#form-print_old_custom_tags").submit();
		}
	});
	
	$(document).on("click", ".button_old__print-selected-barcode", function () {
		if($('#barcode').val() == "" || $('#barcode').val() == null) {
			alert("Please Enter Barcode");
		}else{
			$('input[name="barcode"]').val($('#barcode').val());
			$("#form-print_old_custom_barcode").submit();
		}
	});
	$(document).on("click", ".button_event__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any barcode");
		} else {
			$('input[name="checked_old_tags"]').val(selectedBarcodes.join());
			$("#form-print_event_custom_tags").submit();
		}
	});
	$(document).on("click", ".button_fla__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any barcode");
		} else {
			$('input[name="checked_fla_tags"]').val(selectedBarcodes.join());
			$("#form-print_fla_custom_tags").submit();
		}
	});

	$(document).on("click", ".button_uk__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any barcode");
		} else {
			$('input[name="checked_uk_tags"]').val(selectedBarcodes.join());
			$("#form-print_uk_custom_tags").submit();
		}
	});
	$(document).on("click", ".button_anex__print-selected-tags", function () {
		let checkCount = 0;
		let selectedBarcodes = [];
		$(".input-checkbox__to-print:checkbox").each(function (i, v) {
			if ($(v).prop("checked")) {
				checkCount++;
				selectedBarcodes.push($(v).val());
			}
		});

		if (checkCount === 0) {
			alert("Please Check any barcode");
		} else {
			$('input[name="checked_anex_tags"]').val(selectedBarcodes.join());
			$("#form-print_anex_custom_tags").submit();
		}
	});
});

function calculateAmtWr(row) {
	var rate = row.children("td.td-rate").children(".input-sal-rate").val();
	var pcs = row.children("td.td-pcs").children(".input-pcs").val();
	var ad_price = row.children("td.td-pcs").children(".input-price").val();
	var per = row.children("td.td-per").children(".input-sal-per").val();
	var weight = row
		.children("td.td-actual-weight")
		.children(".input-actual-weight")
		.val();
	var masterWeight = row
		.children("td.td-master-weight")
		.children(".input-master-weight")
		.val();
	var calcType = row
		.children("td.td-calc")
		.children(".form-group")
		.children(".select-amt-calc-on")
		.val();

	var adRate = row.children("td.td-ad-rate").children(".input-ad-rate").val();

	rate = parseF(rate);
	pcs = parseF(pcs);
	per = parseF(per);
	weight = parseF(weight);
	masterWeight = parseF(masterWeight);
	adRate = parseF(adRate);

	finalWeight = weight - masterWeight;

	var finalRate = rate + adRate;
	row
		.children("td.td-final-rate")
		.children(".input-final-rate")
		.val(finalRate.toFixed(3));
	row
		.children("td.td-weight")
		.children(".input-weight")
		.val(finalWeight.toFixed(3));

	var amt;
	if (calcType == "pcs") {
		amt1 = finalRate * pcs;
		amt2 = pcs * ad_price;
		amt = amt1 + amt2;
	} else if (calcType == "weight") {
		amt = finalWeight * 1000;
	} else if (calcType == "into400") {
		amt = finalWeight * 400;
	}
	row.children("td.td-amt").children(".input-sal-amt").val(amt.toFixed(3));
	var wr = finalWeight;
	if (per > 0) {
		var temp = (per * finalWeight) / 100;
		wr = temp;
	}
	row.children("td.td-wr").children(".input-sal-wr").val(wr.toFixed(3));
	calculateNetAmount();
	calculateGrossWeight();
}

function setReport(data) {
	var mainRow = mainReportTableRow;
	var srNo = parseInt(1);
	var totalPcs = 0;
	var totalGrossWeight = 0;
	var totalLessWeight = 0;
	var totalNetWeight = 0;
	var totalAmount = 0;
	var pcs = data.data.pcs;

	reportTable.children("tbody").html("");

	data.lot_creation.forEach((element) => {
		reportTable.children("tbody").append(mainRow);
		var row = reportTable.children("tbody").children("tr:last");

		var tdSrNo = row.children(".report-td-sr-no");
		var tagNo = row.children(".report-td-tag-no");
		var item = row.children(".report-td-item");
		var gross_weight = row.children(".report-td-gross-weight");
		var l_weight = row.children(".report-td-l-weight");
		var pcs = row.children(".report-td-pcs");
		var nt_weight = row.children(".report-td-nt-weight");
		var amount = row.children(".report-td-amount");
		var crerated_at = row.children(".report-td-created-at");

		var action = row.children(".report-td-lot-creation_id");
		action.data("id", element.id);
		if (element.status == "1") {
			row
				.children(".report-td-lot-creation_id")
				.children("button.tohide")
				.each(function (i, v) {
					$(v).css("display", "none");
				});
		}
		const checkBoxWithSrNo = `${srNo} <input type="checkbox" class="input-checkbox__to-print" data-barcode="${element.barcode}" value="${element.tag}" />`;

		tdSrNo.html(checkBoxWithSrNo);
		srNo++;
		tagNo.html(element.tag);
		item.html(element.item_name);
		pcs.html(element.piece);
		gross_weight.html(element.gross_weight);
		l_weight.html(element.l_weight);
		nt_weight.html(element.net_weight);
		amount.html(element.amt);
		crerated_at.html(element.created_at);

		totalGrossWeight += parseFloat(element.gross_weight);
		totalLessWeight += parseFloat(element.l_weight);
		totalNetWeight += parseFloat(element.net_weight);
		totalAmount += parseFloat(element.amt);
		totalPcs += parseFloat(element.piece);
	});

	$(".gr-weight").text(totalGrossWeight);
	$(".l-weight").text(totalLessWeight);
	$(".nt-weight").text(totalNetWeight);
	$(".other-amt").text(totalAmount);
	$(".done-pcs").text(totalPcs);
	$(".pending-pcs").text(pcs - totalPcs);
}

function calculateNetAmount() {
	var totalInputAmt = parseF(0);
	if ($("#isLWeight").prop("checked")) {
		$(".input-sal-amt").each(function (i, v) {
			totalInputAmt += parseF($(v).val());
		});
	}
	$("#input-amt").val(totalInputAmt.toFixed(3));
}

function calculateExtraAmount() {
	var totalInputAmt = parseF(0);

	var inputAmt = parseF($("#input-amt").val());

	if ($("#isLWeight").prop("checked")) {
		$(".input-sal-amt").each(function (i, v) {
			totalInputAmt += parseF($(v).val());
		});
	}

	var totalExtraAmt = inputAmt - totalInputAmt;
	$("#input-extra-amt").val(totalExtraAmt.toFixed(3));
}

function calculateGrossWeight() {
	var lessWeight = parseF(0);
	var netWeight = $("#input-net-weight").val();
	lessWeight = $("#input-less-weight").val();

	var grossWeight = parseFloat(netWeight || 0) + parseFloat(lessWeight || 0);
	$("#input-gross-weight").val(grossWeight.toFixed(2));
}

$(document).on("change", "#isLWeight", function () {
	calculateNetAmount();
	calculateGrossWeight();
});
