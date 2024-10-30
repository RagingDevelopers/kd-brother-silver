var mainFunction = (function () {
	let main = {
		isInit: false,
		IsTagScan: false,
		baseUrl: "<?= base_url() ?>",
		datatable: null,
		mainRow: null,
		mainRmRow: null,
		customerData: null,
		lotData: null,
		IsUsedTag: new Array(),
		init: function () {
			try {
				if (!this.isInit) {
					this.isInit = true;
					mainFunction = null;
					if (this.isInit) {
						main.ready.call();
					}
				}
			} catch (error) {
				console.log(`mainFunction in isInit error : ${error}`);
			}
		},
		select2: (el, config = {}) => {
			Object.assign(config, {
				placeholder: "-- Select --",
				allowClear: true,
				width: "500px",
			});
			if (!$(el).data("select2")) {
				$(el).select2(config);
			}
			return $(el);
		},
		lastTr: $(".append-here tr").last(),
		ready: function () {
			main.mainRow = $(".main-row")[0].outerHTML;
			main.mainRmRow = $(".mainRow")[0]?.outerHTML;

			$(document).ready(function () {
				// $("#party_id").select2();
				$(".item").each(function () {
					main.select2(this);
				});
				$(".sub_item").each(function () {
					main.select2(this);
				});
				$(".stamp").each(function () {
					main.select2(this);
				});
				$(".unit").each(function () {
					// 	main.select2(this)
				});
				$(this).on("click", "#add", function () {
					if ($(".item").last().val() == "") {
						return $(".item").last().select2("open");
					}
					$(".append-here").append(main.mainRow);
					const lastTr = $(".append-here tr").last();
					lastTr
						.find(
							".rowid,.raw_material_string,.gross_weight,.less_weight,.net_weight"
						)
						.val(0);
					lastTr.find(".remark,.item,.sub_item,.stamp,.rmdata").val("");
					main.select2(lastTr.find(".item")).select2("open");
					main.select2(lastTr.find(".sub_item"));
					main.select2(lastTr.find(".stamp"));

					var modalBody = $("table .append-here");
					scrollEventRight(modalBody, 1000);
				});
				$(this).on("click", ".remove-btn", function (e) {
					var $this = this;
					var metal_type = $(".item").length;
					if (metal_type > 1) {
						alert_if("Confirm delete this", () => {
							var removeItem = $(this)
								.parent()
								.parent()
								.find(".hiddenTag")
								.val();
							var y = main.IsUsedTag;
							y.splice($.inArray(removeItem, y), 1);
							$(this).parent().parent().remove(), main.Totalcalculate();
						});
					}
				});

				$(".main-form").submit(function (e) {
					e.preventDefault();
					var validator = new Validator(true);
					validator
						// .addField("#party_id", "Please select Party!", (el) =>
						// 	el.select2("open")
						// )
						.addField("#product_type", "Please select product type!", (el) =>
							el.select2("open")
						)
						.addField(".item", "Please select item!", (el) =>
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
						".gross_weight,.less_weight, .touchData, .wastage, .quantity, .rmrate,.labour,.other_amount,.piece,.rate",
						function () {
							main.handleInputFocusAndBlur(this, "focus");
						}
					)
					.on(
						"blur",
						".gross_weight,.less_weight, .touchData, .wastage, .quantity, .rmrate,.labour,.other_amount,.piece,.rate",
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
					".gross_weight,.touchData,.wastage,.piece,.less_weight,.rate,.sub_total,.labour, .gross_weight, .piece, .other_amount",
					function () {
						main.calculateMain($(this));
						main.Totalcalculate();
					}
				);

				$(this).on("input", ".quantity,.rmrate", function () {
					main.RmcalculateMain($(this));
				});

				main.Totalcalculate();

				$(document).on("input", "#tag", function () {
					var inputValue = $(this).val();
					inputValue = inputValue.replace(/\D/g, "");
					$(this).val(inputValue);
					if (inputValue.length > 6) {
						$(this).val(inputValue.substring(0, 6));
						SweetAlert("error", "Please enter exactly 6 digits.");
					}
				});

				$(document).on("blur", "#tag", async function () {
					var Tag = $(this).val().trim();
					if (Tag) {
						if ($.inArray(Tag, main.IsUsedTag) === -1) {
							main.getTagCustomerData(Tag);
							setTimeout(() => {
								var row = $(".append-here").children("tr:last");
								var selected_id = row.find(".item").attr("data-sub_item");
								main.setSubItem(row.find(".item").val(),row.find(".item"),selected_id);
							}, 1000);
						} else {
							// If the tag is already used
							$("#tag").val("");
							$("#tag").focus();
							SweetAlert(
								"error",
								`${Tag} Is Already Scan, please try other tag.`
							);
						}
					}
				});

				// $(document).on("change", "#party_id", function () {
				// 	if ($(this).val()) {
				// 		var Tag = $("#tag").val().trim();
				// 		if (Tag) {
				// 			if ($.inArray(Tag, main.IsUsedTag)) {
				// 				main.getTagCustomerData(Tag);

				// 				setTimeout(() => {
				// 					var row = $(".append-here").children("tr:last");
				// 					var selected_id = row.find(".item").attr("data-sub_item");
				// 					main.setSubItem(
				// 						row.find(".item").val(),
				// 						row.find(".item"),
				// 						selected_id
				// 					);
				// 				}, 1000);
				// 			} else {
				// 				$("#tag").val("");
				// 				$("#tag").focus();
				// 				SweetAlert(
				// 					"error",
				// 					`${Tag} Is Already Scan, please try other tag.`
				// 				);
				// 			}
				// 		}
				// 	} else {
				// 		var row = $(".append-here").children("tr:last");
				// 		main.setLotData(row, []);
				// 	}
				// });

				$("#tag").on("contextmenu", function (e) {
					e.preventDefault();
					navigator.clipboard
						.readText()
						.then((text) => {
							if ($.isNumeric(text)) {
								$("#tag").val(text);
								if (text != "") {
									SweetAlert("success", `${text}  Past Successfully!!`);
								} else {
									SweetAlert("error", `Text Not Found`);
								}
							} else {
								SweetAlert("error", `Past Only Numbers`);
							}
						})
						.catch((err) => {
							SweetAlert("error", `Failed to read clipboard contents: ${err}`);
						});
				});

				$(document).on("change", ".item", function () {
					main.setSubItem($(this).val(), $(this));
				});

				if ($(".item").val() != " " && $(".item").val() != null) {
					$(".item").each(function () {
						var selected_id = $(this).data("sub_item");
						main.setSubItem($(this).val(), $(this), selected_id);
					});
				}
			});
		},

		setLotData: function (row, data) {
			if (data != "" && data != null) {
				row.children("td").find(".touchData").val(data.touch);
				row.children("td").find(".piece").val(data.piece);
				row.children("td").find(".less_weight").val(data.l_weight);
				row.children("td").find(".gross_weight").val(data.gross_weight);
				row.children("td").find(".net_weight").val(data.net_weight);
			} else {
				row.children("td").find(".touchData").val(0);
				row.children("td").find(".less_weight").val(0);
				row.children("td").find(".gross_weight").val(0);
				row.children("td").find(".net_weight").val(0);
				row.children("td").find(".item").val(0).trigger("change");
			}
		},

		getTagCustomerData: function (Tag) {
			if (Tag) {
				if ($.inArray(Tag, main.IsUsedTag) === -1) {
					$.ajax({
						url: `${BaseUrl}/manufacturing/ready_for_sale/receiveTag/${Tag}`,
						success: async function (data) {
							data = jQuery.parseJSON(data);
							$("#input-tag").val("");
							if (data.success) {
								lotData = data.lot_creation;
								if (lotData != "" && lotData != null) {
									var isTagExistsInList = false;
									$(".append-here")
										.children("tr")
										.each(function (i, v) {
											if ($(v).data("tag") == Tag) {
												isTagExistsInList = true;
											}
										});

									if (isTagExistsInList) {
									} else {
										var item_id = lotData.item_id;

										let row = "";
										if (
											$(".append-here").children("tr").length > 1 ||
											main.IsTagScan
										) {
											var LastRow = $(".append-here").children("tr:first");
											if (LastRow.find(".item").val() != lotData.item_id) {
												$("#tag").val("");
												return SweetAlert("error", "Please select a valid tag. The last item comparison did not match.");
											}
											$(".append-here").append(main.mainRow);
											row = $(".append-here").children("tr:last");
											main.select2(
												row.find(".item").val("").trigger("change")
											);
											main.select2(
												row.find(".sub_item").val("").trigger("change")
											);
											main.select2(
												row.find(".stamp").val("").trigger("change")
											);
											row.find(".rowid").val("0");
										} else {
											row = $(".append-here").children("tr:last");
										}

										main.select2(row.find(".item"));
										main.select2(row.find(".sub_item"));
										main.select2(row.find(".stamp"));

										main.setLotData(row, lotData);
										row.find(".item").val(item_id).trigger("change");
										row
											.find(".item")
											.attr("data-sub_item", lotData.sub_item_id);
										row
											.find(".stamp")
											.val(lotData.stamp_id)
											.trigger("change");
										row.find(".hiddenTag").val(Tag);

										main.calculateMain(row.children("td"));
										main.Totalcalculate();
										$("#tag").val("");
										$("#tag").focus();
									}

									main.IsTagScan = true;
									main.IsUsedTag.push(Tag);
								} else {
									var row = $(".append-here").children("tr:last");
									main.setLotData(row, []);
								}
							} else {
								$("#tag").val("");
								$("#tag").focus();
								SweetAlert("error", data.message);
							}
						},
					});
				} else {
					$("#tag").val("");
					$("#tag").focus();
					SweetAlert(
						"error",
						`${Tag} Is Already Scan, please try other tag.`
					);
				}
			}
		},

		setSubItem: function (item_id = null, ref, selected_id = null) {
			if (item_id != "" && item_id != null) {
				ref.parents("tr").find(".sub_item").html("");
				$.ajax({
					type: "POST",
					showloader: true,
					dataType: "json",
					url: `${BaseUrl}manufacturing/ready_for_sale/getSubItem`,
					method: "POST",
					data: {
						item_id,
					},
					success: async function (response) {
						if (response.success) {
							var getSubItem = setOptions(response.data, selected_id);
							if (selected_id != null) {
								ref.parents("tr").find(".sub_item").html(getSubItem);
								ref.parents("tr").find(".sub_item").val(selected_id);
							} else {
								ref.parents("tr").find(".sub_item").html(getSubItem);
							}
						} else {
							SweetAlert("error", response.message);
						}
					},
				});
			} else {
				ref.parents("tr").find(".sub_item").html("");
			}
		},

		calculateMain: function (ref) {
			var container = ref.parents("tr");
			var gross_weight = container.find(".gross_weight").val() || 0;
			var less_weight = container.find(".less_weight").val() || 0;
			container
				.find(".net_weight")
				.val(parseFloat(gross_weight) - parseFloat(less_weight));
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
			var piece = 0;
			var touch = 0;

			$(".gross_weight").each(function () {
				gross_weight += parseFloat($(this).val() || 0);
			});
			$(".less_weight").each(function () {
				less_weight += parseFloat($(this).val() || 0);
			});
			$(".net_weight").each(function () {
				net_weight += parseFloat($(this).val() || 0);
			});
			$(".piece").each(function () {
				piece += parseFloat($(this).val() || 0);
			});
			$(".touchData").each(function () {
				touch += parseFloat($(this).val() || 0);
			});

			$(".TotalGross_weight").text("");
			$(".TotalGross_weight").text(gross_weight.toFixed(2));
			$(".TotalLess_weight").text("");
			$(".TotalLess_weight").text(less_weight.toFixed(2));
			$(".TotalNet_weight").text("");
			$(".TotalNet_weight").text(net_weight.toFixed(2));
			$(".TotalPiece").text("");
			$(".TotalPiece").text(piece.toFixed(2));
			$(".TotalTouchData").text("");
			$(".TotalTouchData").text(touch.toFixed(2));
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
