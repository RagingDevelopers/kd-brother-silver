<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-status-top bg-blue"></div>
			<div class="card-header justify-content-between">
				<!--<div class="col-sm-11">-->
					<h1 class="card-title"><b>Proffit And Loss Report </b></h1>
					<span class="btn btn-action bg-primary text-white m-1 p-3 showmerger" data-bs-toggle="tooltip" data-bs-placement="top">
                        <i class="far fa-plus card-title"></i>
                    </span>
				<!--</div>-->
			</div>
			<div class="card-body border-bottom py-3">
				<div class="table-responsive">
					<div class="col-md-12 mb-5 ">
						<div class="row">
							<div class="col-sm-2">
								<label>From date:</label> <br>
								<input type="date" id="fromdate" name="fromdddate" class="form-control from">
							</div>
							<div class="col-sm-2">
								<label>To date:</label> <br>
								<input type="date" id="todate" name="todate" class="form-control to">
							</div>
						</div>
						<div class="mt-3">
						    <cite>
	                            <b>
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">SN</th>
										<th scope="col">Date</th>
										<th scope="col">Type</th>
										<th scope="col">Customer Name</th>
										<th scope="col">Item Name</th>
										<th scope="col">Profit</th>
										<th scope="col">Loss</th>
										<th scope="col">Closing</th>
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
										<td id="closingWeight"></td>
										<td></td>
									</tr>
								</tfoot>
							</table>
	                            </b>
							</cite>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script class="javascript">
	var main_row = '';
	$(document).ready(function() {
	    var metalRow = $(".metalRow")[0]?.outerHTML;
    
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
				'url': "<?= base_url(); ?>report/profit_loss_report/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
				}
			},
			"columns": [
			    {
					data: 'id'
				},
			    {
					data: 'date'
				},
			    {
					data: 'type'
				},
			    {
					data: 'customer_name'
				},
			    {
					data: 'item_name'
				},
			    {
					data: 'profite'
				},
			    {
					data: 'loss'
				},
			    {
					data: 'closing'
				},
			],
// 			drawCallback: function(settings) {
// 				$('[data-bs-toggle="tooltip"]').tooltip();
// 			},
            "drawCallback": function(row, data, start, end, display) {
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
													<td colspan="4" class="text-center fs-2">Opening Amount</td>
													<td colspan="2" class="text-center fs-2"></td>
													<td colspan="2" class='text-center fs-3 pe-3'> ${weight}</td>
													<td colspan="3"></td>
												</tr>`;
						$('#garnu thead').append(openingWeightHtml);
					}
				}
			}
		});
		
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		
		$('#fromdate').on('change', function() {
			table.clear();
			table.draw();
		});
	});
</script>