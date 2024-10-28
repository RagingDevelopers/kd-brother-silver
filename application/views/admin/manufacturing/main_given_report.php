<style>
	.net_weight {
		background-color: #ebebeb;
		color: black;
	}

	#garnu,
	#garnu th,
	#garnu td {
		font-weight: bold;
		/* Make text bold */
	}

	tr .given {
		background-color: #ffe1e1;
		color: black;
	}

	tr .received {
		background-color: #d9ffd9;
		color: black;
	}
	
	.readonly {
		background-color: #ebebeb;
		color: black;
	}

	.receivedWeight {
		background-color: #e9f7ff;
		color: black;
	}

	tr .given {
		background-color: #ffe1e1;
		color: black;
	}

	tr .received {
		background-color: #d9ffd9;
		color: black;
	}

	.table td {
		font-weight: bold;
	}
	.modal-xxl {
		max-width: 95%;
	}

	.modal-xxl-content {
		width: 100%;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<div class="col-sm-11">
					<h1 class="card-title"><b>Main Given Report </b></h1>
				</div>
				<!-- <div class="col-sm-1 ms-5 ps-5">
					<a class="btn btn-action bg-primary text-white" href="<?= base_url('manufacturing/garnu/index/add') ?>">
						<i class="far fa-plus card-title" aria-hidden="true">
						</i>
					</a>
				</div> -->
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" value="<?= date('Y-m-01'); ?>" id="fromdate" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
							<div class="col-sm-2">
								<label>Garnu :</label> <br>
								<select class="form-select select2" id="product">
									<option value="">Select Garnu</option>
									<?php
									if (!empty($product)) {
										foreach ($product as $row) { ?>
											<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Process :</label> <br>
								<select class="form-select select2" id="process">
									<option value="">Select Process</option>
									<?php
									if (!empty($process)) {
										foreach ($process as $row) { ?>
											<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Worker :</label> <br>
								<select class="form-select select2" id="worker">
									<option value="">Select Worker</option>
									<?php
									if (!empty($worker)) {
										foreach ($worker as $row) { ?>
											<option value="<?= $row['id']; ?>"><?= $row['name']; ?></option>
									<?php }
									} ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Status :</label> <br>
								<select class="form-select select2" id="status">
									<option value="">Select</option>
									<option value="YES">Complated</option>
									<option value="NO">Not Complated</option>
								</select>
							</div>
						</div>
						<div class="mt-3">
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<th>SR.No</th>
									<th>Garnu Name</th>
									<th>Given Date</th>
									<th>Process</th>
									<th>Worker</th>
									<th>Given Quantity</th>
									<th>Given Weight</th>
									<th>Row Material Weight</th>
									<th>Total Weight</th>
									<th>Receive Pcs</th>
									<th>Receive Weight</th>
									<th>Receive RM Weight</th>
									<th>Receive Final Weight</th>
								</thead>
								<tfoot>
								    <tr>
								        <td class="text-center text-primary" colspan="4"><h3 class="blinking-text">Totals ==></h3></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								        <td></td>
								    </tr>
								</tfoot>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<form action="#" id="received-garnu">
	<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xxl" role="document">
			<div class="modal-content modal-xxl-content">
				<div id="receveData"></div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-success btn-success receivedAddButton2">
						<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
					</button>
					<div>
						<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="input-icon btn btn-primary btn-primary submit-btn">Save Changes
							<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Row Material</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="TBody">
						<?php
						if (empty($given_row_material)) {
							$given_row_material[] = [
								'row_material_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($given_row_material as $row) { ?>
							<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
							<tr class="mainRow">
								<td>
									<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? "0"; ?>" />
									<select class="form-select select2 row_material" name="row_material[]">
										<option value="">Select RM</option>
										<?php
										if (!empty($row_material)) {
											for ($i = 0; $i < count($row_material); $i++) { ?>
												<option value="<?= $row_material[$i]['id']; ?>" <?php if (isset($row) && $row_material[$i]['id'] == $row['row_material_id']) {
																									echo 'selected';
																								} ?>><?= $row_material[$i]['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? 0 ?>" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" name="rmWeight[]" value="<?= $row['weight'] ?? 0 ?>" class="form-control weight" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" name="rmQuantity[]" value="<?= $row['quantity'] ?? 0 ?>" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger deleteRow">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success addButton">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<button type="button" class="btn btn-primary btn-primary save">Save Changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Row Material</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="JBody">
					    
					     <div class="row">
					        <div class="col-md-12 text-center">
					           <h3> Given Row Material</h3>
					        </div>
					        <div class="col-md-12">
					            <div class="row-material-section">
					                
					            </div>
					        </div>
					    </div>
						<?php
						if (empty($given_row_materials)) {
							$given_row_materials[] = [
								'row_material_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($given_row_materials as $row) { ?>
							<input type="hidden" class="ids" value="0" />
							<tr class="mainRow2 main-row">
								<td>
									<input type="hidden" class="received_detail_id" />
									<select class="form-select select2 row_material2">
										<option value="">Select RM</option>
										<?php
										if (!empty($row_material)) {
											foreach ($row_material as $rm) { ?>
												<option value="<?= $rm['id']; ?>" <?php if (isset($row) && $rm['id'] == $row['row_material_id']) {
																						echo 'selected';
																					} ?>><?= $rm['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" class="form-control touch2" value="0" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control weight2" value="0" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control quantity2" value="0" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger deleteRow2">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success addButton2">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-primary saveRmData">Save Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="metalType-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Metal Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Metal Type</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="MetalBody">
						<?php
						if (empty($metalData)) {
							$metalData[] = [
								'metal_type_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($metalData as $row) { ?>
							<!-- <input type="hidden" class="ids" value="0" /> -->
							<tr class="metalRow">
								<td>
									<input type="hidden" class="process_metal_type" value="0" />
									<select class="form-select select2 metal_type">
										<option value="">Select Metal Type</option>
										<?php
										if (!empty($metal_type)) {
											foreach ($metal_type as $mt) { ?>
												<option value="<?= $mt['id']; ?>" <?php if (isset($row) && $mt['id'] == $row['metal_type_id']) {
																						echo 'selected';
																					} ?>><?= $mt['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalTouch" value="0" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalWeight" value="0" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalQuantity" value="0" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger metalDeleteRow">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success metalAddButton">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-primary saveMetalData">Save Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade" id="givenRowMaterial" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Issue Weight </p>
				</div>
				<div class="col-md-4">
					<p class="modal-title">Garnu Weight:- <span class="garnu_weight"></span></p>
				</div>
				<div class="col-md-4 text-center">
					<p class="modal-title">Garnu Name:- <span class="garnu_name"></span></p>
				</div>
				<!-- <div class="col-md-1"> -->
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<!-- </div> -->
			</div>
			<div class="modal-body">
				<table class="table card-table table-vcenter text-center text-nowrap ">
					<thead class="thead-light">
						<th>Row Material</th>
						<th scope="col">Touch (%)</th>
						<th scope="col">Weight(Gm)</th>
						<th scope="col">Quantity</th>
					</thead>

					<tbody class="paste append-here">
						<tr class="sectiontocopy">
							<td>
								<select class="form-select select2 given-row_material_id" disabled readonly>
									<option value="">Select Metal</option>
									<?php
									$row_material = $this->db->get('row_material')->result();
									foreach ($row_material as $value) { ?>
										<option value="<?= $value->id; ?>">
											<?= $value->name; ?>
										</option>
									<?php } ?>
								</select>
							</td>

							<td>
								<input class="form-control given-touch" type="number" disabled readonly placeholder="Enter touch(%)" value="0">
							</td>
							<td>
								<input class="form-control given-weight" type="number" disabled readonly placeholder="Enter Weight" value="0">
							</td>
							<td>
								<input class="form-control given-quantity" type="number" value="0" disabled readonly>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-quantity'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<span></span>
				<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php $time = time() ?>
<script class="javascript" src="<?= base_url("assets") ?>/dist/js/process.js?v=<?= $time ?>"></script>
<script class="javascript">
	var main_row = '';
	$(document).ready(function() {
		var table = $('#garnu').DataTable({
			"iDisplayLength": 10,
			"lengthMenu": [
				[5, 10, 25, 50, 100, 500, 1000, 5000],
				[5, 10, 25, 50, 100, 500, 1000, 5000]
			],
			'processing': true,
			'serverSide': true,
			'destroy': true,
			'serverMethod': 'post',
			'searching': true,
			"ajax": {
				'showLoader': true,
				'url': "<?= base_url(); ?>manufacturing/product/getGivenlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.isComplated = $('#status').val();
					data.process_id = $('#process').val();
					data.worker_id = $('#worker').val();
					data.product_id = $('#product').val();
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'garnu_name'
				},
				{
					data: 'given_date'
				},
				{
					data: 'process_name'
				},
				{
					data: 'worker_name'
				},
				{
					data: 'given_qty'
				},
				{
					data: 'given_weight'
				},
				{
					data: 'given_rm_weight'
				},
				{
					data: 'given_total_weight'
				},
				{
					data: 'receive_qty'
				},
				{
					data: 'receive_weight'
				},
				{
					data: 'receive_rm_weight'
				},
				{
					data: 'receive_total_weight'
				},
			],
			"columnDefs": [{
					"className": "given",
					"targets": [3,4,5, 6, 7, 8]
				},
				{
					"className": "received",
					"targets": [9,10, 11, 12]
				}
			],
			footerCallback: function(row, data, start, end, display) {
                handelFooterTotal(
                    this.api(),
                    [5,6,7,8,9,10,11,12]
                );
            },
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
		});
		
		table.columns([3, 4, 5, 6, 7, 8]).header().to$().removeClass('given');
		table.columns([9,10, 11, 12]).header().to$().removeClass('received');
		table.columns([3, 4, 5, 6, 7, 8]).footer().to$().removeClass('given');
		table.columns([9,10, 11, 12]).footer().to$().removeClass('received');
		
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#process,#worker,#product,#status').on('change', function() {
			table.clear();
			table.draw();
		});

		$('#process,#worker,#product,#status').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
	});
</script>
