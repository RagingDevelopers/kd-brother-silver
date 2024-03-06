<style>
	.table td {
		font-weight: bold;
	}
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="col-sm-11">
					<h1 class="card-title"><b> Row Material Stock Report </b></h1>
				</div>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" id="fromdate" value="<?= $from_date ?? ""?>" name="fromdddate" class="form-control">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control">
							</div>
							<div class="col-sm-2">
								<label>Row Material</label><br>
								<select class="form-select select2" id="row_material">
									<option value=''>Select Row Material</option>
									<?php
									foreach ($row_material as $r) {
									?>
										<option value="<?= $r->id ?>" <?php if(!empty($row_material_id) && $row_material_id == $r->id){ echo "selected";} ?>>
											<?= $r->name; ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Garnu Name</label><br>
								<select class="form-select select2" id="garnu">
									<option value=''>Select Garnu</option>
									<?php
									foreach ($garnu as $g) {
									?>
										<option value="<?= $g->id ?>">
											<?= $g->name; ?>
										</option>
									<?php } ?>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Process Name</label><br>
								<select class="form-select select2" id="process">
									<option value=''>Select Process</option>
									<?php
									foreach ($process as $p) {
									?>
										<option value="<?= $p->id ?>">
											<?= $p->name; ?>
										</option>
									<?php } ?>
								</select>
							</div>
						</div><br>
						<div class="mt-3">
							<table id="stock" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Row Material Name</th>
										<th scope="col">Garnu Name</th>
										<th scope="col">Process Name</th>
										<th scope="col">Touch</th>
										<th scope="col">Quantity</th>
										<th scope="col">Credit</th>
										<th scope="col">Debit</th>
										<th scope="col">Closing Weight</th>
										<th scope="col">Date</th>
									</tr>
								</thead>
								<tfoot>
									<tr id="openingRow">
									</tr>
									<tr>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
										<td id="closingWeight"></td>
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

<script class="javascript">
	$(document).ready(function() {
		var table = $('#stock').DataTable({
			"iDisplayLength": 10,
			"lengthMenu": [
				[10, 25, 50, 100, 500, 1000, 5000],
				[10, 25, 50, 100, 500, 1000, 5000]
			],
			'processing': true,
			'serverSide': true,
			'destroy': true,
			'serverMethod': 'post',
			'searching': true,
			"ajax": {
				'url': "<?= base_url(); ?>report/Row_material_stock/fetchData",
				'data': function(data) {
					data.todate = ($('#todate').val() ?? null);
					data.fromdate = ($('#fromdate').val() ?? null);
					data.row_material_id = ($('#row_material').val() ?? null);
					data.garnu_id = ($('#garnu').val() ?? null);
					data.process_id = ($('#process').val() ?? null);
					// data.types = ($('#type').val() ?? null);
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'row_material'
				},
				{
					data: 'garnu'
				},
				{
					data: 'process'
				},
				{
					data: 'touch'
				},
				{
					data: 'quantity'
				},
				{
					data: 'credit'
				},
				{
					data: 'debit'
				},
				{
					data: 'closingWeight',
					render: function(data, type, row) {
						if (parseFloat(data) > 0) {
							return `<span class='text-success'>${data} CR /-</span>`;
						} else {
							return `<span class='text-danger'>${data} DR /-</span>`;
						}
					}
				},
				{
					data: 'date'
				},
			],
			"footerCallback": function(row, data, start, end, display) {
				var api = this.api();
				var jsonResponse = api.ajax.json();

				if (jsonResponse) {
					if (jsonResponse.closingWeight !== undefined) {
						var weight = jsonResponse.closingWeight > 0 ?
							`<h3>${jsonResponse.closingWeight} CR /-</h3>` :
							`<h3>${jsonResponse.closingWeight} DR /-</h3>`;
						$('#closingWeight').html(weight);
					}

					if (jsonResponse.openingTouch != undefined || jsonResponse.openingWeight != undefined) {
						$('#openingRow').remove();
						if (jsonResponse.openingWeight > 0) {
							var weight = `${jsonResponse.openingWeight} <b class="text-success"> -/ CR</b>`;
							var color = `style="background-color: #d9ffd9"`;
						} else if (jsonResponse.openingWeight < 0) {
							var weight = `${jsonResponse.openingWeight} <b class="text-danger"> -/ DR</b>`;
							var color = `style="background-color: #ffe1e1"`;
						} else {
							var weight = `${jsonResponse.openingWeight} <b> -/ </b>`;
							var color = `style="background-color: #ffffff"`;
						}

						var openingWeightHtml = `<tr id="openingRow" ${color}>
													<td colspan="4" class="text-center fs-2">Opening Weight</td>
													<td colspan="2" class="text-center fs-2"></td>
													<td colspan="2" class='text-center fs-3 pe-3'> ${weight}</td>
													<td colspan="3"></td>
												</tr>`;
						$('#stock thead').append(openingWeightHtml);
					}
				}
			}
		});
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#row_material,#garnu,#process,#type').on('change', function() {
			table.clear();
			table.draw();
		});
	});
</script>
