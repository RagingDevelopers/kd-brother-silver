<style>
	.table td {
		font-weight: bold;
	}
</style>
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-status-top bg-primary"></div>
                <div class="card-header justify-content-between">
                    <h3 class="card-title"><b>Account Ledger </b></h3>
                </div>
    			<div class="mt-3 p-2">
                    <div class="card-body">
                        <div class="container-fluid">
    						<div class="row row-cards">
    							<div class="col-sm-2">
    								<label>From date:</label> <br>
    								<input type="date" id="fromdate" name="fromdddate" class="form-control from">
    							</div>
    							<div class="col-sm-2">
    								<label>To date:</label> <br>
    								<input type="date" id="todate" value="<?= date('Y-m-d') ?>" name="todate" class="form-control to">
    							</div>
    							<div class="col-md-2">
    								<label>Account Category:</label>
    								<select class="form-control select2" id="account_category">
    									<optgroup label="account category">
    										<?php
    										if (!empty($account_type)) {
    											foreach ($account_type as $row) { ?>
    												<option value="<?= $row['id'] ?>" data-master_type="account_category"><?= $row['name'] ?></option>
    
    										<?php }
    										} ?>
    									</optgroup>
    									<optgroup label="bank">
    										<option value="10" data-master_type="bank">Bank</option>
    									</optgroup>
    								</select>
    							</div>
    							<div class="col-sm-2">
    								<label>Customer</label><br>
    								<select class="form-select select2" id="customer">
    									<option value=''>Select Customer</option>
    								</select>
    							</div>
    							<div class="col-sm-2">
    								<label></label><br>
    								<input class="btn btn-primary button search_btn" id="search_btn" type="button" value="Search">
    							</div>
    						</div><br>
    					</div>
    				</div>
    			</div>
    		</div>
            <div class="card mt-2">
                <div class="card-status-top bg-primary"></div>
                <div class="card-header justify-content-between">
                    <h3 class="card-title"><b>Account Ledger Report </b></h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="report"></div>
                </div>
            </div>
		</div>
	</div>
</div>

<script class="javascript">
	$(document).ready(function() {
		$(document).on('click', '#search_btn', function() {
			var fromDate = ($('#fromdate').val() ?? null);
			var toDate = ($('#todate').val() ?? null);
			var customer_id = ($('#customer').val() ?? null);
			var ac_cat = ($('#account_category').val() ?? null);
			var master_type = ($('#account_category').find("option:selected").data("master_type") ?? null);

			$.ajax({
				beforeSend: function() {
					$("#report").html("Please Wait...");
				},
				showLoader: true,
				url: "<?= base_url(); ?>report/account_ledger/getLedgerReport",
				method: 'POST',
				data: {
					fromDate: fromDate,
					toDate: toDate,
					ac_cat: ac_cat,
					customer_id: customer_id,
					master_type: master_type
				},
				success: function(data) {
					$("#report").html(data);
				},
				complete: function(data) {

				}
			});
		});

		function getCustomer(account_type = null, master_type = null) {
			return $.ajax({
				showLoader: true,
				url: `${BaseUrl}report/account_ledger/getCustomer`,
				type: "POST",
				dataType: "json",
				data: {
					account_type,
					master_type,
				},
				success: function(response) {
					if (response.data != "") {
						var setOption = setOptions(response.data);
						$('#customer').html(setOption);
					} else {
						SweetAlert("warning", response.message);
						return false;
					}
				},
				error: function() {
					alert("An error occurred.");
				},
			});
		}

		$(document).on("change", "#account_category", function() {
			$('#customer').html("");
			getCustomer($(this).val(), $('#account_category').find("option:selected").data("master_type"));
		});
		getCustomer($('#account_category').val(), $('#account_category').find("option:selected").data("master_type"));

		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
	});
</script>
