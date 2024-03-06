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
					<h1 class="card-title"><b> Row Material Closing </b></h1>
				</div>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="mt-3">
							<table id="stock" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">Serial No </th>
										<th scope="col">Row Material Name</th>
										<th scope="col">Closing Weight</th>
										<th scope="col">Date</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
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
				'url': "<?= base_url(); ?>report/row_material_closing/fetchData",
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
		});

		$('#fromdate, #todate,#metal_type,#garnu,#process').on('change', function() {
			table.ajax.reload();
		});
		$('#touch').on('input', function() {
			table.ajax.reload();
		});
	});
</script>
