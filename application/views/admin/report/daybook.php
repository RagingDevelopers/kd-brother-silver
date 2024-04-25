<div class="container-xl">
	<div class="row row-cards">
		<div class="col-12">
			<div class="card">
				<div class="card-status-top bg-primary"></div>
				<div class="mt-3 p-2">
					<div class="card-body">
						<div class="container-fluid">
							<form action="<?= base_url('report/daybook'); ?>" method="POST">
								<div class="row row-cards">
									<div class="col-md-2">
										<div class="form-group">
											<label for="from" class="form-label">From Date</label>
											<input type="date" name="fromDate" value="<?= isset($postData['fromDate']) ? $postData['fromDate'] : date('Y-m-d'); ?>" class="form-control from">
										</div>
									</div>
									<div class="col-md-2 ">
										<div class="form-group">
											<label for="to" class="form-label">To Date</label>
											<input type="date" name="toDate" value="<?= isset($postData['toDate']) ? $postData['toDate'] : date('Y-m-d'); ?>" class="form-control to">
										</div>
									</div>
									<div class="col-md-1">
										<button type="submit" class="btn btn-outline-primary mt-4 mx-3">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php
		if (!empty($processData)) {
			for ($i = 0; $i < count($processData); $i++) {
		?>
				<div class="col-6">
					<div class="card mt-2">
						<div class="card-status-top bg-primary"></div>
						<div class="card-header bg-danger text-white justify-content-between">
							<h3 class="card-title"><b><?= isset($processData[$i]['process_name']) ? $processData[$i]['process_name'] : ""; ?> Given</b></h3>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table card-table table-vcenter text-nowrap processGiven">
									<thead>
										<tr>
											<th>worker</th>
											<th>Quantity</th>
											<th>Weight</th>
											<th>Row Material Weight</th>
											<th>Total Weight</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$GivenQty = 0;
										$GivenWeight = 0;
										$GivenRmWeight = 0;
										$GivenTotalWeight = 0;
										if (!empty($processData[$i]['givenData'])) {
											foreach ($processData[$i]['givenData'] as $j => $row) {
												$GivenQty += $processData[$i]['givenData'][$j]['given_qty'];
												$GivenWeight += $processData[$i]['givenData'][$j]['given_weight'];
												$GivenRmWeight += $processData[$i]['givenData'][$j]['row_material_weight'];
												$GivenTotalWeight += $processData[$i]['givenData'][$j]['total_weight'];
										?>
												<tr>
													<td><?= $processData[$i]['givenData'][$j]['worker_name']; ?></td>
													<td><?= $processData[$i]['givenData'][$j]['given_qty']; ?></td>
													<td><?= $processData[$i]['givenData'][$j]['given_weight']; ?></td>
													<td><?= $processData[$i]['givenData'][$j]['row_material_weight']; ?></td>
													<td><?= $processData[$i]['givenData'][$j]['total_weight']; ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th> </th>
											<th><b><?= $GivenQty; ?></b></th>
											<th><b><?= $GivenWeight; ?></b></th>
											<th><b><?= $GivenRmWeight; ?></b></th>
											<th><b><?= $GivenTotalWeight; ?></b></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-6">
					<div class="card mt-2">
						<div class="card-status-top bg-primary"></div>
						<div class="card-header bg-success text-white justify-content-between">
							<h3 class="card-title"><b><?= isset($processData[$i]['process_name']) ? $processData[$i]['process_name'] : ""; ?> Receive</b></h3>
						</div>
						<div class="card-body">
							<div class="table-responsive">
								<table class="table card-table table-vcenter text-nowrap processReceive">
									<thead>
										<tr>
											<th>worker</th>
											<th>Quantity</th>
											<th>Weight</th>
											<th>Row Material Weight</th>
											<th>Total Weight</th>
											<th>Touch</th>
											<th>Fine</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$receiveQty = 0;
										$receiveWeight = 0;
										$receiveRmWeight = 0;
										$receiveTotalWeight = 0;
										$receiveTouch = 0;
										$receiveFine = 0;
										if (!empty($processData[$i]['receiveData'])) {
											foreach ($processData[$i]['receiveData'] as $k => $row) {
												$receiveQty += $processData[$i]['receiveData'][$k]['qty'];
												$receiveWeight += $processData[$i]['receiveData'][$k]['weight'];
												$receiveRmWeight += $processData[$i]['receiveData'][$k]['row_material_weight'];
												$receiveTotalWeight += $processData[$i]['receiveData'][$k]['total_weight'];
												$receiveTouch += $processData[$i]['receiveData'][$k]['touch'];
												$receiveFine += $processData[$i]['receiveData'][$k]['fine'];
										?>
												<tr>
													<td><?= $processData[$i]['receiveData'][$k]['worker_name']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['qty']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['weight']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['row_material_weight']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['total_weight']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['touch']; ?></td>
													<td><?= $processData[$i]['receiveData'][$k]['fine']; ?></td>
												</tr>
										<?php
											}
										}
										?>
									</tbody>
									<tfoot>
										<tr>
											<th> </th>
											<th><b><?= $receiveQty; ?></b></th>
											<th><b><?= $receiveWeight; ?></b></th>
											<th><b><?= $receiveRmWeight; ?></b></th>
											<th><b><?= $receiveTotalWeight; ?></b></th>
											<th><b><?= $receiveTouch; ?></b></th>
											<th><b><?= $receiveFine; ?></b></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
		<?php }
		} ?>

		<!-- Start Purchase Report -->
		<div class="col-6">
			<div class="card mt-2">
				<div class="card-status-top bg-primary"></div>
				<div class="card-header bg-primary text-white justify-content-between">
					<h3 class="card-title"><b>Purchase</b></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table card-table table-vcenter text-nowrap" id="purchase">
							<thead>
								<tr>
									<th>Code</th>
									<th>Fine</th>
									<th>Amount</th>
									<th>Net Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$purchaseFine = 0;
								$purchaseSubTotal = 0;
								$purchaseNetWeight = 0;
								if (!empty($purchases)) {
									foreach ($purchases as $purchase) {
										$purchaseFine += $purchase['fine'];
										$purchaseSubTotal += $purchase['sub_total'];
										$purchaseNetWeight += $purchase['net_weight'];
								?>
										<tr>
										    <td><a href="<?= site_url('purchase/edit/'.$purchase['id']) ?>" target="_blank"><?= $purchase['code']; ?></a></td>
											<td><?= $purchase['fine']; ?></td>
											<td><?= $purchase['sub_total']; ?></td>
											<td><?= $purchase['net_weight']; ?></td>
										</tr>
								<?php
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
								    <th></th>
									<th><b><?= $purchaseFine; ?></b></th>
									<th><b><?= $purchaseSubTotal; ?></b></th>
									<th><b><?= $purchaseNetWeight; ?></b></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End Purchase Report -->

		<!-- Start Purchase Return Report -->
		<div class="col-6">
			<div class="card mt-2">
				<div class="card-status-top bg-primary"></div>
				<div class="card-header bg-primary text-white  justify-content-between">
					<h3 class="card-title"><b>Purchase Return</b></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table card-table table-vcenter text-nowrap" id="purchase-return">
							<thead>
								<tr>
									<th>Code</th>
									<th>Fine</th>
									<th>Amount</th>
									<th>Net Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$purchaseReturnFine = 0;
								$purchaseReturnSubTotal = 0;
								$purchaseReturnNetWeight = 0;
								if (!empty($purchasesReturn)) {
									foreach ($purchasesReturn as $purchaseReturn) {
										$purchaseReturnFine += $purchaseReturn['fine'];
										$purchaseReturnSubTotal += $purchaseReturn['sub_total'];
										$purchaseReturnNetWeight += $purchaseReturn['net_weight'];
								?>
										<tr>
										    <td><a href="<?= site_url('purchase_return/edit/'.$purchaseReturn['id']) ?>" target="_blank"><?= $purchaseReturn['code']; ?></a></td>
											<td><?= $purchaseReturn['fine']; ?></td>
											<td><?= $purchaseReturn['sub_total']; ?></td>
											<td><?= $purchaseReturn['net_weight']; ?></td>
										</tr>
								<?php
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
								    <th></th>
									<th><b><?= $purchaseReturnFine; ?></b></th>
									<th><b><?= $purchaseReturnSubTotal; ?></b></th>
									<th><b><?= $purchaseReturnNetWeight; ?></b></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End Purchase Return Report -->

		<!-- Start Sales Report -->
		<div class="col-6">
			<div class="card mt-2">
				<div class="card-status-top bg-success"></div>
				<div class="card-header bg-success text-white  justify-content-between">
					<h3 class="card-title"><b>Sales</b></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table card-table table-vcenter text-nowrap" id="sale">
							<thead>
								<tr>
									<th>Code</th>
									<th>Fine</th>
									<th>Amount</th>
									<th>Net Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$saleFine = 0;
								$saleSubTotal = 0;
								$saleNetWeight = 0;
								if (!empty($sales)) {
									foreach ($sales as $sale) {
										$saleFine += $sale['fine'];
										$saleSubTotal += $sale['sub_total'];
										$saleNetWeight += $sale['net_weight'];
								?>
										<tr>
										    <td><a href="<?= site_url('sales/edit/'.$sale['id']) ?>" target="_blank"><?= $sale['code']; ?></a></td>
											<td><?= $sale['fine']; ?></td>
											<td><?= $sale['sub_total']; ?></td>
											<td><?= $sale['net_weight']; ?></td>
										</tr>
								<?php
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
								    <th></th>
									<th><b><?= $saleFine; ?></b></th>
									<th><b><?= $saleSubTotal; ?></b></th>
									<th><?= $saleNetWeight; ?></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End Sales Report -->

		<!-- Start Sales Return Report -->
		<div class="col-6">
			<div class="card mt-2">
				<div class="card-status-top bg-success"></div>
				<div class="card-header bg-success text-white  justify-content-between">
					<h3 class="card-title"><b>Sales Return</b></h3>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table card-table table-vcenter text-nowrap" id="sale-return">
							<thead>
								<tr>
									<th>code</th>
									<th>Fine</th>
									<th>Amount</th>
									<th>Net Weight</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$saleReturnFine = 0;
								$saleReturnSubTotal = 0;
								$saleReturnNetWeight = 0;
								if (!empty($salesReturn)) {
									foreach ($salesReturn as $saleReturn) {
										$saleReturnFine += $saleReturn['fine'];
										$saleReturnSubTotal += $saleReturn['sub_total'];
										$saleReturnNetWeight += $saleReturn['net_weight'];
								?>
										<tr>
											<td><a href="<?= site_url('sales_return/edit/'.$saleReturn['id']) ?>" target="_blank"><?= $saleReturn['code']; ?></a></td>
											<td><?= $saleReturn['fine']; ?></td>
											<td><?= $saleReturn['sub_total']; ?></td>
											<td><?= $saleReturn['net_weight']; ?></td>
										</tr>
								<?php
									}
								}
								?>
							</tbody>
							<tfoot>
								<tr>
								    <th></th>
									<th><b><?= $saleReturnFine; ?></b></th>
									<th><b><?= $saleReturnSubTotal; ?></b></th>
									<th><b><?= $saleReturnNetWeight; ?></b></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End Sales Return Report -->
	</div>
</div>

<script>
	$(document).ready(function() {
		$("#purchase, #purchase-return, #sale, #sale-return,.processReceive,.processGiven").DataTable();
	});
</script>
