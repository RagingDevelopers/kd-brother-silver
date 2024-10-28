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
					<h1 class="card-title"><b> Metal Type Report </b></h1>
				</div>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" id="fromdate" value="<?=date('Y-m-01');?>" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
							<div class="col-sm-2">
								<label>Metal Type</label><br>
								<select class="form-select select2" id="metal_type">
									<option value=''>Select Metal Type</option>
									<?php
									foreach ($metal_type as $r) {
									?>
										<option value="<?= $r->id ?>">
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
								<label>Touch</label> <br>
								<input type="number" id="touch" name="todate" class="form-control">
							</div>
						</div><br>
						<div class="mt-3">
							<table id="stock" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Metal Name</th>
										<th scope="col">Garnu Name</th>
										<th scope="col">Process Name</th>
										<th>Touch</th>
										<th>Credit</th>
										<th>Debit</th>
										<th scope="col">Closing Weight</th>
										<th scope="col">Date</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
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
			    'showLoader': true,
				'url': "<?= base_url(); ?>report/metal_type_stock/getData",
				'data': function(data) {
					data.todate = ($('#todate').val() ?? null);
					data.fromdate = ($('#fromdate').val() ?? null);
					data.metal_type_id = ($('#metal_type').val() ?? null);
					data.garnu_id = ($('#garnu').val() ?? null);
					data.touch = ($('#touch').val() ?? null);
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
					data: 'cweight'
				},
				{
					data: 'dweight'
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

					if (jsonResponse.openingWeight != undefined) {
						$('#openingRow').remove();
						if (jsonResponse.openingWeight > 0) {
							var weight = `${jsonResponse.openingWeight} <b class="text-success"> -/ CR</b>`;
							var color = `style="background-color: #d9ffd9"`;
						}else if(jsonResponse.openingWeight < 0){
							var weight = `${jsonResponse.openingWeight} <b class="text-danger"> -/ DR</b>`;
							var color = `style="background-color: #ffe1e1"`;
						}else{
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

		$(document).ready(function() {
			$('#stock tbody').on('draw.dt', function() {
				$(this).find('tr').each(function() {
					$(this).find('td:eq(3), td:eq(4), td:eq(5)').css('background-color', '#d0f2d0');
					$(this).find('td:eq(6), td:eq(7), td:eq(8)').css('background-color', '#fecece');
				});
			});
		});

		$('#fromdate, #todate,#metal_type,#garnu,#process').on('change', function() {
			table.ajax.reload();
		});
		$('#touch').on('input', function() {
			table.ajax.reload();
		});

		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
	});
</script>
