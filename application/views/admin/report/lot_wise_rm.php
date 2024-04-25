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
	
</style>
<div class="row">
	<div class="col-sm-12">
		<div class="card mt-3">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<div class="col-sm-11">
					<h1 class="card-title"><b>Lot Wise Row Material Report </b></h1>
				</div>
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
							<div class="col-sm-2">
								<label>Type :</label> <br>
								<select class="form-select select2" id="type">
									<option value="">Select Type</option>
									<option value="RECEIVE">Receive</option>
									<option value="GIVEN">Given</option>
									<option value="PURCHASE">Purchase</option>
								</select>
							</div>
							<div class="col-sm-2">
								<label>Row Material :</label> <br>
								<select class="form-select select2" id="rm">
									<option value="">Select Row Material</option>
									<?php
									if(!empty($rm)){
										foreach($rm as $row){ ?>
											<option value="<?=$row['id'];?>"><?=$row['name'];?></option>
									<?php } } ?>
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
						    <cite>
	                            <b>
							<table id="garnu" class="table table-vcenter card-table">
								<thead>
									<tr>
										<th scope="col">SN</th>
										<th scope="col">Code</th>
										<th scope="col">Type</th>
										<th scope="col">Row Material</th>
										<th scope="col">Touch</th>
										<th scope="col">Weight</th>
										<th scope="col">Quantity</th>
										<th scope="col">Given Weight</th>
										<th scope="col">Given Quantity</th>
										<th scope="col">Receive Weight</th>
										<th scope="col">Receive Quantity</th>
										<th scope="col">Used Weight</th>
										<th scope="col">Used Quantity</th>
										<th scope="col">Is Complated</th>
										<th scope="col">Created At</th>
									</tr>
								</thead>
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
<div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Used Row Material</p>
				</div>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table card-table table-vcenter text-center text-nowrap w-100" id="rowMaterial">
					<thead class="thead-light">
						<th scope="col">Sr.No</th>
						<th scope="col">Last Process</th>
						<th scope="col">Type</th>
						<th scope="col">Row Material</th>
						<th scope="col">Touch (%)</th>
						<th scope="col">Weight(Gm)</th>
						<th scope="col">Quantity</th>
					</thead>
				</table>
			</div>
			<div class="modal-footer justify-content-end">
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
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
				'url': "<?= base_url(); ?>report/Lot_wise_rm/getlist",
				'data': function(data) {
					data.todate = $('#todate').val();
					data.fromdate = $('#fromdate').val();
					data.row_material = $('#rm').val();
					data.type = $('#type').val();
					data.isComplated = $('#status').val();
				}
			},
			"columns": [{
					data: 'id'
				},
				{
					data: 'code'
				},
				{
					data: 'type'
				},
				{
					data: 'rm'
				},
				{
					data: 'touch'
				},
				{
					data: 'weight'
				},
				{
					data: 'quantity'
				},
				{
					data: 'given_weight'
				},
				{
					data: 'given_quantity'
				},
				{
					data: 'receive_weight'
				},
				{
					data: 'receive_quantity'
				},
				{
					data: 'rem_weight'
				},
				{
					data: 'rem_quantity'
				},
				{
					data: 'is_complated'
				},
				{
					data: 'created_at'
				},
			],
			drawCallback: function(settings) {
				$('[data-bs-toggle="tooltip"]').tooltip();
			},
		});
		$('#todate').on('change', function() {
			table.clear();
			table.draw();
		});
		$('#fromdate,#rm,#type,#status').on('change', function() {
			table.clear();
			table.draw();
		});

		$('#rm,#type,#status').select2();
		
		var rmTable = null; 
        $(document).on('click', '.showUsed', function() {
            var id = $(this).data('id');
            if (rmTable !== null) {
                rmTable.destroy();
                rmTable = null;
            }
        
            UsedLotRm(id).done(function(response) {
                if (!response.success || response.data === "") {
                    SweetAlert('warning', 'Data Not Found !!');
                    return;
                }
                rmTable = initializeDataTable(response);
                $("#ReceivedModel").modal('show');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                SweetAlert('error', 'Error fetching data: ' + errorThrown);
            });
        });
        
        function initializeDataTable(response) {
            var dataToLoad = response.data.map(function(item, index) {
                return [
                    index + 1, // SR
                    item.Process_name, // Process
                    item.type, // Type
                    item.row_material, // Rm
                    item.touch, // Touch
                    item.weight, // Weight
                    item.quantity // Quantity
                ];
            });
        
            return $("#rowMaterial").DataTable({
                data: dataToLoad,
                columns: [
                    { title: "SR" },
                    { title: "Process" },
                    { title: "Type" },
                    { title: "RM" },
                    { title: "Touch" },
                    { title: "Weight" },
                    { title: "Quantity" }
                ],
                destroy: true,
                retrieve: true
            });
        }
        
        function UsedLotRm(id) {
            return $.ajax({
                url: '<?= base_url("report/lot_wise_rm/UsedRowMaterial"); ?>',
                type: 'POST',
                dataType: 'json',
                data: { id: id },
            });
        }
	});
</script>