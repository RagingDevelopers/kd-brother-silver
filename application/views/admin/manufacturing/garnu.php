<style>
	.fine-input {
		background-color: #ebebeb;
		color: black;
	}

	.weight-input {
		background-color: #e6fdff;
	}

	/* .copper-input {
		background-color: #ffeeee;
	} */
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<form action="<?= (isset($update)) ? base_url("manufacturing/garnu/index/update/{$update['id']}") : base_url('manufacturing/garnu/index/store') ?>" method="post" class="main-form" novalidate>
				<div class="card-header">
					<div class="card-status-top bg-blue"></div>
					<h1 class="card-title"><b> Garnu </b></h1>
				</div>
				<div class="card-body border-bottom py-3">
					<div class="col-md-12 mb-5 ">
						<div class="row ms-1">
							<div class="row">
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Name: </label>
									<input class="form-control" type="text" name="name" placeholder="Enter Garnu Name" value="<?= $update['name'] ?? null ?>" id="name" required>
								</div>
							</div>
							<div class="card mt-5">
								<div class="row">
									<table class="table card-table table-vcenter text-center text-nowrap ">
										<thead class="thead-light">
											<th>Metal Type</th>
											<th scope="col">Weight(Gm)</th>
											<th scope="col">Touch (%)</th>
											<th scope="col">Fine</th>
											<th scope="col"></th>
										</thead>

										<tbody class="paste append-here">
											<?php
											if (empty($items)) {
												$items[] = [
													'metal_type_id' => '',
													'weight'        => '',
													'touch'         => 0,
													'fine'          => '',
													'id'            => 0
												];
											}
											foreach ($items as $row) { ?>
												<tr class="main-row">
													<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? null; ?>" />
													<td>
														<select class="form-select select2 metal_type_id" name="metal_type_id[]">
															<option value="">Select Metal</option>
															<?php
															$metal_type = $this->db->get('metal_type')->result();
															foreach ($metal_type as $value) {
															?>
																<option value="<?= $value->id; ?>" <?php if (isset($row) && $value->id == $row['metal_type_id']) {
																										echo 'selected';
																									} ?>><?= $value->name; ?></option>
															<?php } ?>
														</select>
													</td>
													<td>
														<input class="form-control weight-input weight" type="number" name="weight[]" placeholder="Enter Weight" value="<?= $row['weight'] ?? null ?>" required>
													</td>
													<td>
														<div class="form-group input-icon">
															<input class="form-control touch" type="number" name="touch[]" placeholder="Enter touch(%)" value="<?= $row['touch'] ?? null ?>" required>
															<span class="input-icon-addon"><i class="fa-light fa-percent" aria-hidden="true"></i></span>
														</div>
													</td>
													<td>
														<input class="form-control fine fine-input" type="number" name="fine[]" placeholder="Fine" value="<?= $row['fine'] ?? null ?>" required>
													</td>
													<!-- <td>
														<input class="form-control copper-input copper" type="number" name="copper[]" placeholder="Copper(Gm)" value="<?= $row['copper'] ?? null ?>" required readonly>
													</td> -->
													<td>
														<button type="button" class="btn btn-danger remove-btn">X</button>
													</td>
												</tr>
											<?php } ?>
										</tbody>
										<tfoot>
											<tr class="border border-none">
												<td colspan="1" class="d-flex border border-0 align-content-start flex-wrap">
													<button type="button" class="btn btn-success " id="add">
														Add row <i class="ms-2 fa-solid fa-plus"></i>
													</button>
												</td>
												<td>
													<label class="form-label"> Garnu Weight(Gm): </label>
													<input class="form-control weight-input mweight" type="number" name="garnu_weight" readonly placeholder="Enter Garnu Weight(Gm)" value="<?= $update['garnu_weight'] ?? null ?>" required>
												</td>
												<td>
													<label class="form-label">Garnu Touch (%): </label>
													<div class="form-group input-icon">
														<input class="form-control mtouch" type="number" name="touchs" placeholder="Enter Touch (%)" value="<?= $update['touch'] ?? 0 ?>" required>
														<span class="input-icon-addon"><i class="fa-light fa-percent" aria-hidden="true"></i></span>
													</div>
												</td>
												<td>
													<label class="form-label">Total Fine: </label>
													<input class="form-control fine-input total_used_fine" type="number" name="mfine" placeholder="Fine(Gm)" value="<?= $update['fine'] ?? null ?>" required readonly>
												</td>
											</tr>
											<!-- <tr>
											</tr> -->
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer ">
					<button type="submit" class="btn btn-primary ms-auto">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script class="javascript">
	var mainFunction = (function() {
		let main = {
			isInit: false,
			baseUrl: "<?= base_url() ?>",
			datatable: null,
			mainRow: null,
			init: function() {
				try {
					if (!this.isInit) {
						this.isInit = true;
						mainFunction = null;
						console.log("main Function Enabled")
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
				});
				if (!$(el).data('select2')) {
					$(el).select2(config);
				}
				return $(el)
			},
			lastTr: $('.append-here tr').last(),
			calculateMain: function(ref) {
				const row = $(ref).parents('.row');
				const weight = parseF(row.find('.mweight').val());
				const fine = parseF(row.find('.fine').val());
			},
			ready: function() {
				main.mainRow = $(".main-row")[0].outerHTML;
				$(document).ready(function() {
					$('.metal_type_id').each(function() {
						main.select2(this)
					});
					$(this).on('click', "#add", function() {
						var metal = $('.metal_type_id').last();
						if (metal.val() == '') {
							return metal.select2('open');
						}
						$(".append-here").append(main.mainRow);
						const lastTr = $('.append-here tr').last();
						lastTr.find('.rowid,.touch').val(0);
						lastTr.find('.weight,.fine').val('');
						main.select2(lastTr.find('.metal_type_id')).select2('open');
					});
					$(this).on('click', '.remove-btn', function(e) {
						var $this = this;
						var metal_type = $(".metal_type_id").length;
						if (metal_type > 1) {
							alert_if("Confirm delete this", () => {
								$(this).parent().parent().remove(),
									main.calculation($this);
							})
						}
					});
					$('.main-form').submit(function(e) {
						e.preventDefault();
						main.validateSubmit(this)
					});

					$(this).on('keyup', '.fine,.weight,.touch', function() {
						main.calculateMain(this)
						main.calculation($('.append-here tr').eq(0).find('.touch'));
					})
					$(this).on('keyup', '.fine,.weight,.touch', function() {
						main.calculation(this);
					});
					
					$(this).on(
                		"focus",
                		".touch",
                		function () {
                			main.handleInputFocusAndBlur(this, "focus");
                		}).on(
                		"blur",
                		".touch",
                		function () {
                			main.handleInputFocusAndBlur(this, "blur");
                		}
                	);
				});
			},
			
			handleInputFocusAndBlur: function(element, eventType){
			    var $element = $(element);
            	if (eventType === "focus" && $element.val() == "0") {
            		$element.val("");
            	} else if (eventType === "blur" && $element.val() == "") {
            		$element.val("0");
            	}
			},
			calculation: function(ref) {
				var valid = true;
				const mainWeight = parseF($('.mweight').val()),
					row = $(ref).parents('tr'),
					weight = parseF(row.find('.weight').val()),
					touch = parseF(row.find('.touch').val()),
					fine = parseF(row.find('.fine').val());

				if (touch > 100) {
					return SweetAlert('warning', 'Touch should be less than equal to 100'), $(ref).val(0.00);
				}
				var totalUsedWeight = 0,
					totalUsedFine = 0;

				$('.append-here tr').each(function() {
					var row = $(this),
						rowWeight = parseF(row.find('.weight').val()),
						rowFine = parseF(row.find('.fine').val());

					totalUsedWeight += rowWeight;
					totalUsedFine += rowFine;
				});
				$('.mweight').val(formatNumber(totalUsedWeight));
				$('.total_used_fine').val((totalUsedFine));
				var mainTouch = (totalUsedFine / totalUsedWeight) * 100;
				$('.mtouch').val(formatNumber(mainTouch));

			},
			validateSubmit: function(ref) {
				var preventEnter = false;
				const metal_type = $('.metal_type_id');
				const form = $(ref);
				mainWeight = parseF($('.mweight').val());
				if (mainWeight < 1) {
					return SweetAlert('warning', 'Garnu Weight must be greater then: 1'), preventEnter = true;
				}
				if (metal_type < 1)
					return SweetAlert('warning', 'Metal Type should not be empty'), $('.metal_type_id').focus(), preventEnter = true;;
				var rows = $('.append-here tr');
				for (var i = 0; i < rows.length; i++) {
					var row = $(rows[i]),
						weight = row.find('.weight'),
						touch = row.find('.touch');

					if (weight.val() == '') {
						SweetAlert('warning', 'Weight should not be empty');
						weight.focus();
						preventEnter = true;
						break;
					} else if (touch.val() == '') {
						SweetAlert('warning', 'Touch should not be empty');
						touch.focus();
						preventEnter = true;
						break;
					}
				}

				if (!preventEnter) {
					form.unbind('submit').submit();
				}
			}
		};
		return {
			init: main.init
		}
	})();
	mainFunction.init.call();
</script>
