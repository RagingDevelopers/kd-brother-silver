<style>
	#cutomerJamaReport_filter {
		float: right;
		padding-right: 15px;
	}

	#cutomerJamaReport_length {
		padding: 10px 15px;
	}

	.dt-buttons {
		padding: 0px 0px 5px 15px;
	}

	#cutomerJamaReport_info {
		float: left;
		padding: 15px;
	}

	#cutomerJamaReport_paginate {
		float: right;
		padding: 10px 15px;
	}
	
	.totalColor {
        color:black;
    }
</style>
<div class="page-body">
	<div class="container-xl">
		<div class="row row-cards">
			<div class="col-12">
				<div class="card">
					<div class="card-status-top bg-primary"></div>
					<div class="card-header justify-content-between">
						<h3 class="card-title"><b><?= $page_title; ?> </b></h3>
						<a class="btn btn-action bg-primary text-white m-1 p-3" href="<?= base_url(); ?>payment/jama">
							<i class="far fa-plus card-title"></i>
						</a>
					</div>
					<div class="card-body">
						<div class="container-fluid">
							<div class="row row-cards">
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">From Date</label>
										<input type="date" value="<?=date('Y-m-01');?>" class="form-control" placeholder="YYY-MM-DD" id="from">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">End Date</label>
										<input type="date" class="form-control" placeholder="YYY-MM-DD" id="to">
									</div>
								</div>

								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Type</label>
										<select class="form-control" id="group_by" required>
											<option value="">all</option>
											<option value="fine">Fine</option>
											<option value="cash">Cash</option>
											<option value="bank">Bank</option>
											<option value="ratecutfine">Rate Cut - Fine</option>
											<option value="ratecutrs">Rate Cut - Rs</option>
										</select>
									</div>
								</div>
								
								<div class="col-md-3">
									<div class="form-group">
										<label class="form-label">Report Type</label>
										<select class="form-control" id="report_type" required>
											<option value="">Select Type</option>
											<option value="single_length">Single Length</option>
											<option value="multiply_length">Multiply Length</option>
										</select>
									</div>
								</div>
								
								<div class="card-footer">
                                    <button type="button" class="btn btn-outline-primary float-end" id="search">Search</button>
                                </div>
							</div>
						</div>
					</div>
					<div class="table-responsive" id="report">
						<!--<b>-->
						<!--    <div class="table-responsive" ></div>-->
							<!--<table class="table card-table table-vcenter text-center text-nowrap datatable" id="cutomerJamaReport">-->
							<!--	<thead>-->
							<!--		<tr>-->
							<!--			<th>Sl No</th>-->
							<!--			<th>Action</th>-->
							<!--			<th>Party Name</th>-->
							<!--			<th>Date</th>-->
							<!--			<th>Payment Type</th>-->
							<!--			<th>Type</th>-->
							<!--			<th>mode</th>-->
							<!--			<th>Gross</th>-->
							<!--			<th>Purity</th>-->
							<!--			<th>W/B</th>-->
							<!--			<th>Fine</th>-->
							<!--			<th>Metal Type</th>-->
							<!--			<th>Rate</th>-->
							<!--			<th>Amount</th>-->
							<!--			<th>Remark</th>-->
							<!--		</tr>-->
							<!--	</thead>-->
							<!--	<tbody>-->

							<!--	</tbody>-->
							<!--	<tfoot>-->

							<!--	</tfoot>-->
							<!--</table>-->
						<!--</b>-->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {

	    $("#from").flatpickr();
		$("#to").flatpickr();

        $(document).on("click", "#search", function(e) {
            e.preventDefault();
            $('#report').html(null);

            var from = $("#from").val();
			var to = $("#to").val();
			var group_by = $("#group_by").find("option:selected").val();
			var report_type = $("#report_type").find("option:selected").val();
            data = {
    			'from' : from,
    			'to' : to,
    			'group_by' : group_by,
    			'report_type' : report_type,
    			'jama_code' : $('#jama_code').val(),
            }

            $.ajax({
                showLoader: true,
                url: "<?php echo base_url(); ?>payment/jama_report/report",
                timeout: 8000,
                type: "POST",
                processData: true,
                data: data,
                datatype : 'json',
                success: function(response) {
                    console.log(response);
                    $('#report').html(response);
                }
            });
        });

		$('#search').click();
    });
</script>
