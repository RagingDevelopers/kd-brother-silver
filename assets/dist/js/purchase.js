var mainFunction = (function () {
	let main = {
		isInit: false,
		baseUrl: "<?= base_url() ?>",
		datatable: null,
		mainRow: null,
		mainRmRow: null,
		init: function () {
			try {
				if (!this.isInit) {
					this.isInit = true;
					mainFunction = null;
					if (this.isInit) {
						main.ready();
					}
				}
			} catch (error) {
				console.log(`mainFunction in isInit error : ${error}`);
			}
		},
		select2: (el, config = {}) => {
			config = Object.assign({
				placeholder: "-- Select --",
				allowClear: true,
				width: "500px",
			}, config);
			if (!$(el).data("select2")) {
				$(el).select2(config);
			}
			return $(el);
		},
		lastTr: $(".append-here tr").last(),
		ready: function () {
			const self = this;
			main.mainRow = $(".main-row")[0].outerHTML;
			main.mainRmRow = $(".mainRow")[0]?.outerHTML;

			$(document).ready(function () {
				$("#party_id").select2();
				$(".item").each(function () {
					var { selected, name } = $(this).data();
					if (selected != 0 || name != "") {
						$(this).html(new Option(name, selected, false, true));
						main.setItemOption($(this), selected);
					}
					main.setItemOption($(this), selected);
					main.setLotOption($(this), selected, $(this).parents("tr").find(".lot").data("selected-id"));
				});
				$(".lot").each(function () {
					main.select2(this, { width: "250px" });
				});
				$(".stamp").each(function () {
					main.select2(this, { placeholder: "Stamp", allowClear: false, width: "80px" });
				});
				$(".unit").each(function () {
					// main.select2(this);
				});
				$(".labour_type").each(function () {
					// main.select2(this);
					main.calculateMain($(this));
				});

				$(this).on("click", "#add", function (e, trigger) {
					if (
						$(".item").last().val() == "" ||
						$(".item").last().val() == null
					) {
						return $(".item").last().select2("open");
					}
					// if ($(".stamp").last().val() == "") {
					// 	return $(".stamp").last().select2("open");
					// }
					// if ($(".unit").last().val() == "") {
					// 	return $(".unit").last().select2("open");
					// }
					// if ($(".labour_type").last().val() == "") {
					// 	return $(".labour_type").last().select2("open");
					// }
					const lastTr = $(".append-here")
						.append(main.mainRow)
						.children()
						.last();
					lastTr
						.find(
							".rowid,.raw_material_string,.gross_weight,.less_weight,.net_weight,.touchData,.wastage,.piece,.labour,.rate,.sub_total,.fine,.other_amount"
							// "input"
						)
						.val(0);
					lastTr.find(".ids").val(0);
					lastTr.find(".remark,.stamp,.unit,.labour_type,.rmdata,.lot").val("");
					lastTr.find(".lot").html('<option value="">Select Lot</option>').attr("data-selected-id", "").data("selected-id", "");
					main.select2(lastTr.find(".stamp"), { placeholder: "Stamp", allowClear: false, width: "80px" });
					main.select2(lastTr.find(".lot"), { width: "250px" });
					// main.select2(lastTr.find(".unit"));
					// main.select2(lastTr.find(".labour_type"));
					main.setItemOption(lastTr.find(".item"));
					main.select2(lastTr.find(".item")).select2("open");

					var modalBody = $("table .append-here");
					scrollEventRight(modalBody, 1000);
				});
				$(this).on("click", ".remove-btn", function (e, trigger) {
					var metal_type = $(".item").length;
					if (trigger?.delete) {
						$(this).parent().parent().remove(), $(".item").fi;
						main.Totalcalculate();
					} else {
						if (metal_type > 1) {
							alert_if("Confirm delete this", () => {
								$(this).parent().parent().remove(),
									// main.calculation($this);
									main.Totalcalculate();
							});
						}
					}
				});

				$(".main-form").submit(function (e) {
					e.preventDefault();
					var validator = new Validator(true);
					validator
						.addField("#party_id", "Please select Party!", (el) =>
							el.select2("open")
						)
						.addField(".item", "Please select item!", (el) =>
							el.select2("open")
						)
						.addField(".lot", "Please select lot!", (el) =>
							el.select2("open")
						);
					if (!validator.validate()) return;

					main.validateSubmit(this);
				});

				function scrollEvent(target, pixel = 500) {
					var animated = target.animate(
						{
							scrollTop: target.prop("scrollHeight"),
						},
						pixel
					);
				}

				function scrollEventRight(target, duration = 1000) {
					var $target = $(target);
					var scrollPosition =
						$target.prop("scrollWidth") - $target.innerWidth();
					$target.animate(
						{
							scrollLeft: scrollPosition,
						},
						duration
					);
				}

				$(this).on("click", ".Receivedmaterial", function () {
					rmBtn = $(this);
					var modal = $("#received-report");
					var givenContainer = rmBtn.parents("tr");
					var mainSection = modal.find(".mainRow");
					modal.find("tbody").html("");
					var string = givenContainer.find(".rmdata").val();
					var data = string?.split("|");
					mainSectionLength = data?.length ?? 0;
					if (mainSectionLength > 0) {
						for (var i = 0; i < mainSectionLength; i++) {
							modal.find("tbody").append(main.mainRmRow);
							var row = modal.find(".mainRow").eq(i),
								splitByHash = data[i]?.split(","),
								row_material = splitByHash[0] ?? 0,
								quantity = splitByHash[1] ?? 0;
							(rmrate = splitByHash[2] ?? 0),
								(rmsub_total = splitByHash[3] ?? 0);
							received_detail_id = splitByHash[4] ?? 0;
							row.find(".row_material").val(row_material).trigger("change");
							row.find(".quantity").val(quantity);
							row.find(".rmrate").val(rmrate);
							row.find(".rmsub_total").val(rmsub_total);
							row.find(".received_detail_id").val(received_detail_id);
							row.find(".row_material").select2({
								width: "200",
								dropdownParent: $("#received-report"),
							});
							main.RmcalculateMain(rmBtn);
							// main.select2(row.find(".row_material"));
						}
					} else {
						modal.find("tbody").append(main.mainRmRow);
					}
					modal.modal("show");
				});

				$("#received-report").on("shown.bs.modal", function (e) {
					var lastrm = $(".row_material").last().val();
					if (lastrm == "") {
						$(".row_material").last().select2("open");
					}
					var lastquantity = $(".quantity").last().val();
					if (lastquantity == 0) {
						$(".quantity").last().focus();
					}
					var lastrmrate = $(".rmrate").last().val();
					if (lastrmrate == 0) {
						$(".rmrate").last().focus();
					}
				});

				$(document)
					.on(
						"focus",
						".gross_weight,.less_weight, .touchData, .wastage, .quantity, .rmrate,.piece,.rate,.labour,.other_amount ",
						function () {
							main.handleInputFocusAndBlur(this, "focus");
						}
					)
					.on(
						"blur",
						".gross_weight,.less_weight, .touchData, .wastage, .quantity, .rmrate,.piece,.rate,.labour,.other_amount ",
						function () {
							main.handleInputFocusAndBlur(this, "blur");
						}
					);

				$(".addButton2").click(function () {
					var LastRm = $(".row_material").last();
					if (LastRm.val() == "") {
						return LastRm.select2("open");
					}
					$("#JBody").append(main.mainRmRow);
					const lastTr = $("#JBody tr").last();
					// lastTr.find('.rowid').val(0);
					lastTr.find(".quantity,.rmrate,.rmsub_total").val(0);
					lastTr.find(".row_material").select2({
						width: "200",
						dropdownParent: $("#received-report"),
					});
					lastTr.find(".row_material").last().select2("open");
					var modalBody = $("#received-report .modal-body");
					scrollEvent(modalBody, 550);
				});

				$(this).on("click", ".deleteRow2", function () {
					if ($(".deleteRow2").length > 1) {
						$(this).parents("tr").remove();
					}
				});

				$(this).on("click", ".saveRmData", function () {
					var count = 0;
					$(".row_material").each(function () {
						var row_material = $(this).val();
						if (row_material == 0 || row_material == "") {
							count += 1;
							$(this).select2("open");
							SweetAlert("warning", "Please fill all rows.");
						}
					});
					$(".rmrate").each(function () {
						var rmrate = $(this).val();
						if (rmrate == 0 || rmrate == "") {
							count += 1;
							$(this).focus();
							SweetAlert("warning", "Please fill all rows.");
						}
					});
					$(".quantity").each(function () {
						var quantity = $(this).val();
						if (quantity == 0 || quantity == "") {
							count += 1;
							$(this).focus();
							SweetAlert("warning", "Please fill all rows.");
						}
					});
					count == 0 ? $("#received-report").modal("hide") : null;

					var modal = $("#received-report");
					var container = rmBtn.parents("tr");
					var mainSection = modal.find(".mainRow");
					var mainSectionLength = modal.find("tbody tr").length;
					let FilterVar = (el) => {
						if (el == "" || el == undefined || el == NaN) {
							return 0;
						}
						return el;
					};
					var string = "";
					var totalRmSub_total = 0;
					for (var i = 0; i < mainSectionLength; i++) {
						var row = mainSection.eq(i);
						var rm = row.find(".row_material option:selected").val();
						var quantity = FilterVar(row.find(".quantity").val());
						var rmrate = FilterVar(row.find(".rmrate").val());
						var rmsub_total = FilterVar(row.find(".rmsub_total").val());
						var received_detail_id = FilterVar(
							row.find(".received_detail_id").val()
						);
						totalRmSub_total += parseFloat(rmsub_total) || 0;
						string += [
							rm,
							quantity,
							rmrate,
							rmsub_total,
							received_detail_id,
						].join(",");
						if (mainSectionLength > i + 1) string += "|";
					}
					container.find(".rmdata").val(string);
					container.find(".less_weight").val(totalRmSub_total / 1000);
					// finalCalculation(rmBtn);
					main.calculateMain(rmBtn);
					main.Totalcalculate();
				});

				$(this).on("input", ".touch", function () {
					var touch = $(this);
					if (touch.val() > 100) {
						SweetAlert("warning", "Touch should be less than equal to 100"),
							touch.val("");
					}
				});

				$(this).on(
					"input",
					".gross_weight,.touchPreData,.touchData,.wastage,.less_weight,.piece,.rate,.sub_total,.labour, .gross_weight, .piece, .other_amount",
					function () {
						main.calculateMain($(this));
						main.Totalcalculate();
						main.calculateLabour($(this));
					}
				);

				$(this).on("input", ".quantity,.rmrate", function () {
					main.RmcalculateMain($(this));
				});

				$(this).on("change", ".item", function () {
					main.setLotOption($(this), $(this).val());
				});

				$(this).on("change",".labour, .gross_weight, .piece, .other_amount,.labour_type",
					function () {
						main.calculateLabour($(this));
					}
				);
				main.Totalcalculate();
			});
		},

		setItemOption: function (ref, selected_id = null) {
			ref.select2({
				placeholder: "Select an item",
				ajax: {
					url: `${BaseUrl}purchase/productType`,
					dataType: "json",
					type: "POST",
					data: function (params) {
						return {
							searchTerm: params.term,
						};
					},
					processResults: function (response) {
						return {
							results: $.map(response.data, function (item) {
								var option = {
									id: item.id,
									text: item.name,
								};
								if (selected_id !== null && selected_id == item.id) {
									option.selected = true;
								}
								return option;
							}),
						};
					},
				},
			});
		},

		setLotOption: function (ref, item_id = null, selected_id = null) {
			const lotEl = ref.parents("tr").find(".lot");
			lotEl.html('<option value="">Select Lot</option>');
			if (!item_id || item_id == 0) {
				if (lotEl.data("select2")) {
					lotEl.select2("destroy");
				}
				main.select2(lotEl, { width: "250px" });
				return;
			}

			$.ajax({
				type: "POST",
				showloader: true,
				dataType: "json",
				url: `${BaseUrl}purchase_return/getItemLots`,
				data: {
					item_id,
					lot_wise_rm_id: selected_id,
				},
				success: function (response) {
					if (lotEl.data("select2")) {
						lotEl.select2("destroy");
					}
					if (response.success) {
						lotEl.html(main.getLotOptions(response.data, selected_id));
						main.select2(lotEl, { width: "250px" });
						if (!selected_id) {
							lotEl.select2("open");
						}
					} else {
						lotEl.html('<option value="">Select Lot</option>');
						main.select2(lotEl, { width: "250px" });
					}
				},
			});
		},

		getLotOptions: function (response, selected_id = null) {
			var options = '<option value="">Select Lot</option>';
			$.each(response, function (key, value) {
				if (!value || value.id === undefined || value.id === null) {
					return;
				}
				var id = value.id ?? "";
				var touch = value.touch ?? 0;
				var remWeight = value.rem_weight ?? 0;
				var remQuantity = value.rem_quantity ?? 0;
				var code = value.code || "";
				var selected = selected_id != null && String(selected_id) === String(id) ? "selected" : "";
				var option = id + " - " + code + " Weight: " + remWeight + " Touch: " + touch + " Quantity: " + remQuantity;
				options += `<option value="${id}" ${selected} data-touch="${touch}" data-weight="${remWeight}" data-quantity="${remQuantity}">${option}</option>`;
			});
			return options;
		},

		calculateMain: function (ref) {
			var container = ref.parents("tr");
			var gross_weight = container.find(".gross_weight").val() || 0;
			var less_weight = container.find(".less_weight").val() || 0;
			container
				.find(".net_weight")
				.val(parseFloat(gross_weight) - parseFloat(less_weight));

			var net_weight = container.find(".net_weight").val() || 0;
			var touch = container.find(".touchData").val() || 0;
			var wastage = container.find(".wastage").val() || 0;

			var fine = parseFloat(touch) + parseFloat(wastage);
			if(container.find(".rate").val() > 0){
			    container.find(".fine").val(0);
			}else{
			    container.find(".fine").val(formatNumber(fine * parseFloat(net_weight)) / 100);
			}
			
			main.Totalcalculate();
		},

		calculateLabour: function (ref) {
			var container = ref.parents("tr");
			var labour_type = container.find(".labour_type option:selected").val() || 0;

			// if (labour_type === "") {
			// 	container.find(".labour").val("");
			// }

			var labour = container.find(".labour").val();
			var otherAmount = parseFloat(container.find(".other_amount").val()) || 0;

			var totalAmount = 0;

			if (labour_type === "net") {
				var net_weight = container.find(".net_weight").val() || 0;

				totalAmount = parseFloat(net_weight) * parseFloat(labour) || 0;
			} else if (labour_type === "pcs") {
				var pcs = container.find(".piece").val();

				totalAmount = parseFloat(pcs) * parseFloat(labour) || 0;
			} else if (labour_type === "fixed") {
				totalAmount = parseFloat(labour);
			} else if (labour_type === "gross") {
				var gross = container.find(".gross_weight").val();

				totalAmount = parseFloat(gross) * parseFloat(labour) || 0;
			}

            var net_weight = container.find(".net_weight").val() || 0;
			var touch = container.find(".touchData").val() || 0;
			var wastage = container.find(".wastage").val() || 0;
			var fine = parseFloat(touch) + parseFloat(wastage);
			var FinalFine = formatNumber(fine * parseFloat(net_weight) / 100);
			var FineAmount = (parseFloat(FinalFine || 0) * parseFloat(container.find(".rate").val() || 0)) / 100;

			if (otherAmount != "" && otherAmount != 0) {
			    if(container.find(".rate").val() > 0){
    				var finalAmount = parseFloat(otherAmount) + parseFloat(totalAmount || 0) + parseFloat(FineAmount || 0);
    				container.find(".sub_total").val(finalAmount.toFixed(2) || 0);
    				main.Totalcalculate();
			    }else{
				    container.find(".sub_total").val(parseFloat(totalAmount.toFixed(2)) + parseFloat(otherAmount || 0 ));
			    }
			} else {
			    if(container.find(".rate").val() > 0){
    				var finalAmount = parseFloat(otherAmount || 0) + parseFloat(totalAmount || 0) + + parseFloat(FineAmount || 0);
    				container.find(".sub_total").val(finalAmount.toFixed(2) || 0);
    				main.Totalcalculate();
			    }else{
				    container.find(".sub_total").val(parseFloat(totalAmount.toFixed(2)) + parseFloat(otherAmount || 0 ));
			    }
			}

			main.Totalcalculate();
		},

		RmcalculateMain: function (ref) {
			var Totalquantity = 0;
			var Totalrmrate = 0;
			var Totalrmsub_total = 0;

			var container = ref.parents("tr");
			var quantity = container.find(".quantity").val() || 0;
			var rmrate = container.find(".rmrate").val() || 0;
			container
				.find(".rmsub_total")
				.val(parseFloat(quantity) * parseFloat(rmrate));

			$(".quantity").each(function () {
				Totalquantity += parseFloat($(this).val() || 0);
			});
			$(".rmrate").each(function () {
				Totalrmrate += parseFloat($(this).val() || 0);
			});
			$(".rmsub_total").each(function () {
				Totalrmsub_total += parseFloat($(this).val() || 0);
			});

			$(".total-qty").text(formatNumber(Totalquantity));
			$(".total-rmrate").text(formatNumber(Totalrmrate));
			$(".total-rmsub_total").text(formatNumber(Totalrmsub_total));
		},

		Totalcalculate: function () {
			var gross_weight = 0;
			var less_weight = 0;
			var net_weight = 0;
			var touchData = 0;
			var touchPreData = 0;
			var wastage = 0;
			var Fine = 0;
			var piece = 0;
			var rate = 0;
			var labour = 0;
			var otherAmount = 0;
			var sub_total = 0;

			$(".gross_weight").each(function () {
				gross_weight += parseFloat($(this).val() || 0);
			});
			$(".less_weight").each(function () {
				less_weight += parseFloat($(this).val() || 0);
			});
			$(".net_weight").each(function () {
				net_weight += parseFloat($(this).val() || 0);
			});
			$(".touchData").each(function () {
				touchData += parseFloat($(this).val() || 0);
			});
			$(".touchPreData").each(function () {
				touchPreData += parseFloat($(this).val() || 0);
			});
			$(".wastage").each(function () {
				wastage += parseFloat($(this).val() || 0);
			});
			$(".fine").each(function () {
				Fine += parseFloat($(this).val() || 0);
			});
			$(".piece").each(function () {
				piece += parseFloat($(this).val() || 0);
			});
			$(".rate").each(function () {
				rate += parseFloat($(this).val() || 0);
			});
			$(".labour").each(function () {
				labour += parseFloat($(this).val() || 0);
			});
			$(".other_amount").each(function () {
				otherAmount += parseFloat($(this).val() || 0);
			});
			$(".sub_total").each(function () {
				sub_total += parseFloat($(this).val() || 0);
			});

			$(".TotalGross_weight").text("");
			$(".TotalGross_weight").text(gross_weight.toFixed(2));
			$(".TotalLess_weight").text("");
			$(".TotalLess_weight").text(less_weight.toFixed(2));
			$(".TotalNet_weight").text("");
			$(".TotalNet_weight").text(net_weight.toFixed(2));
			$(".TotalTouchData").text("");
			$(".TotalTouchData").text(touchData.toFixed(2));
			$(".TotalPreTouchData").text("");
			$(".TotalPreTouchData").text(touchPreData.toFixed(2));
			$(".TotalWastage").text("");
			$(".TotalWastage").text(wastage.toFixed(2));
			$(".TotalFine").text("");
			$(".TotalFine").text(Fine.toFixed(2));
			$(".TotalPiece").text("");
			$(".TotalPiece").text(piece.toFixed(2));
			$(".TotalRate").text("");
			$(".TotalRate").text(rate.toFixed(2));
			$(".TotalLabourAmount").text("");
			$(".TotalLabourAmount").text(labour.toFixed(2));
			$(".TotalOtherAmount").text("");
			$(".TotalOtherAmount").text(otherAmount.toFixed(2));
			$(".Sub_total").text("");
			$(".Sub_total").text(sub_total.toFixed(2));
		},

		handleInputFocusAndBlur: function (element, eventType) {
			var $element = $(element);
			if (
				(eventType === "focus" && $element.val() == "0") ||
				$element.val() == "0.00"
			) {
				$element.val("");
			} else if (eventType === "blur" && $element.val() == "") {
				$element.val("0");
			}
		},
		validateSubmit: function (ref) {
			$(ref).off("submit").submit();
		},
	};

	return {
		init: main.init,
	};
})();
mainFunction.init.call();
