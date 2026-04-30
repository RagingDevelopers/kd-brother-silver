<div class="table-responsive">
	<div class="modal-header">
		<div class="col-md-3">
			<p class="modal-title ProcessName" data-labour_type="<?= $givenData['labour_type']; ?>">Process:- <span class="process_name"><?= $givenData['process_name']; ?></span></p>
		</div>
		<div class="col-md-3">
			<p class="modal-title">Dhal Name:- <span class="garnu_name"><?= $garnuData['name']; ?></span></p>
		</div>
		<div class="col-md-3">
			<p class="modal-title">Dhal Weight:- <span class="garnu_weight"><?= $garnuData['garnu_weight']; ?></span></p>
		</div>
		<div class="col-md-3">
			<p class="modal-title">Dhal Touch:- <span class="garnu_touch"><?= $garnuData['touch']; ?></span></p>
		</div>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	</div>
	<div class="modal-body">
		<div class="d-flex p-2 justify-content-between">
		    <div class="col-md-2 col-sm-3 d-flex gap-3"></div>
			<div class="col-md-2 col-sm-3 d-flex gap-3" style="display:none !important;">
				<label class="form-label mt-2" for="customer">Worker: </label>
				<select class="form-select select2 customer col-md-2 col-sm-3" disabled>
					<option value="">Select Worker</option>
					<?php
					if (!empty($customer)) {
						foreach ($customer as $row) { ?>
							<option value="<?= $row['id']; ?>" <?php if (isset($givenData) && $row['id'] == $givenData['worker_id']) {
								echo 'selected';
							} ?>><?= $row['name']; ?></option>
					<?php }
					} ?>
				</select>
				<input type="hidden" name="given_id" class="form-control" id="given_id" value="<?= $givenData['id'] ?? null ?>">
				<input type="hidden" name="garnu_id" class="form-control" id="garnu_id" value="<?= $garnuData['id'] ?? null ?>">
			</div>
			<div>
				<label class="form-check">
				<input class="form-check-input" id="is_completed" name="is_completed" <?php if (isset($givenData['is_completed']) && $givenData['is_completed'] == "YES") {
						echo 'checked';
					} ?> type="checkbox">
					<h4 class="form-check-label is_completed">Is Complated</h4>
				</label>
			</div>
		</div>

		<table class="table table-vcenter card-table table-striped mb-4">
			<thead>
				<tr>
				    <th>Worker Name</th>
					<th>Given Weight</th>
					<th>Given Row Material Weight</th>
					<th>Given Final Weight</th>
					<th>Given Touch Group</th>
					<th>Given Remark</th>
					<th>Given Date</th>
					<th>Given Created At</th>
				</tr>
			</thead>
			<tbody>
				<tr>
				    <td><?php $customernamed=$this->db->get_where('customer',array('id'=>$givenData['worker_id']))->row_array(); echo $customernamed['name'];?></td>
					<td><?= $givenData['given_weight']; ?></td>
					<td>
						<?php
						if (!empty($GivenRMData)) {
							$rm_string_array = [];
							foreach ($GivenRMData as $rm) {
								$rm_string_array[] = implode(',', [
									$rm['row_material_id'],
									$rm['lot_wise_rm_id'],
									$rm['touch'],
									$rm['weight'],
									$rm['quantity'],
									$rm['id']
								]);
							}
							$row['given_raw_material'] = implode('|', $rm_string_array ?? []) ?? "";
						}
						?>
						<input type="hidden" value="<?= $row['given_raw_material'] ?? null; ?>" class="form-control givenRmdata" placeholder="Enter Weight" autocomplete="off">
						<div class='d-flex gap-5'>
							<?= $givenData['row_material_weight']; ?>
							<!--<a class="btn btn-action text-white bg-primary givenMaterial"><i class="fa-solid fa-info"></i></a>-->
						</div>
					</td>
					<td id="givenTotal_weight"><?= $givenData['total_weight']; ?></td>
					<td><?= $givenData['given_touch']; ?></td>
					<td><?= $givenData['remarks']; ?></td>
					<td><?= $givenData['creation_date']; ?></td>
					<td><?= ($givenData['created_at']) ? date('d-m-Y g:i A', strtotime($givenData['created_at'])) : ""; ?></td>
				</tr>
			</tbody>
		</table>

		<div class="d-flex pt-3 justify-content-between">
			<h3>Data Fetched From: <?= $garnuData['name']; ?></h3>
			<div class="d-flex justify-content-between">
				<div class="col-md-5">
					<input type="number" value="0" class="form-control totalMetalWeight readonly " readonly autocomplete="off">
				</div>
				<div>
					<?php
					$rm_string_array = [];
					foreach ($metalData as $rm) {
						$rm_string_array[] = implode(',', [
							$rm['metal_type_id'],
							$rm['lot'] ?? 0,
							$rm['touch'],
							$rm['weight'],
							$rm['quantity'],
							$rm['id']
						]);
					}
					$row['metal_string'] = implode('|', $rm_string_array ?? []) ?? "";
					?>

					<input type="hidden" name="metalType-data" value="<?= $row['metal_string'] ?? null; ?>" class="form-control metaldata" autocomplete="off">
					<button type="button" class="btn btn-primary btn-primary ProcessMetalType">metal type receive</button>
				</div>
			</div>
		</div>

		<table class="table table-vcenter card-table table-striped" id="Received">
			<thead>
				<tr>
					<th>Lot</th>
					<th>Receive item</th>
					<th>Lot Select</th>
					<th>Receive Pcs</th>
					<th>Receive Weight</th>
					<th>Labour Type</th>
					<th>Labour</th>
					<th>Total Labour</th>
					<th>Row material Weight</th>
					<th>Final Weight</th>
					<th>Touch</th>
					<th>Fine</th>
					<th>Receive Remark</th>
					<th>Created At</th>
					<th></th>
				</tr>
			</thead>
			<tbody id="ReceivedBody">
				<?php
				if (empty($receivedData)) {
					$receivedData[] = [
						'lot_creation'   => "",
						'item_id' 		 => 0,
						'pcs' 			 => 0,
						'weight'         => 0,
						'labour_type'    => "",
						'labour'         => 0,
						'total_labour'   => 0,
						'rm_weight'      => 0,
						'total_weight'   => 0,
						'touch'          => $givenData['given_touch'] ?? 0,
						'fine'           => 0,
						'remark'     	 => '',
						'created_at'     => "",
						'id'             => 0
					];
				}
				foreach ($receivedData as $row) {
					if ($row['id'] > 0) {
						$rawMaterial = $this->dbh->getWhereResultArray('receive_row_material', ['received_id' => $row['id']]);
						$rm_string_array = [];
						$RmFinalLabour = 0;
						foreach ($rawMaterial as $rm) {
							$rm_string_array[] = implode(',', [
								$rm['row_material_id'],
								$rm['lot_wise_rm_id'],
								$rm['touch'],
								$rm['weight'],
								$rm['quantity'],
								$rm['labour_type'] ?? "",
								$rm['labour'],
								$rm['total_labour'],
								$rm['id']
							]);
							
							$RmFinalLabour += $rm['total_labour'];
						}
						$row['raw_material_string'] = implode('|', $rm_string_array ?? []) ?? "";
					}
				?>
					<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
					<tr class="ReceivedMainRow">
						<input type="hidden" class="rcid" name="rcid[]" value="<?= $row['id'] ?? "0"; ?>" />
						<td>
							<label class="form-check">
								<input class="form-check-input lot_creation" name="lot_creation[]" type="checkbox" value="YES" <?php if (isset($row['lot_creation']) && $row['lot_creation'] == "YES") {
									echo 'checked';
								} ?>>
								<input type="hidden" name="lot_creation_value[]" value="NO">
							</label>
						</td>
					<td>
						<select class="form-select select2 item" name="item_id[]">
								<option value=''>Select Item</option>
								<?php
								foreach ($item as $val) {
								?>
									<option value="<?= $val['id'] ?? 0 ?>" <?php if (isset($row) && ($val['id'] == $row['item_id'])) {
											echo 'selected';
										} ?>>
										<?= $val['name']; ?>
									</option>
								<?php } ?>
						</select>
					</td>
					<td>
						<?php
						$selectedReceiveLotId = (int) ($row['received_lot_id'] ?? $row['lot_wise_rm_id'] ?? $row['lot'] ?? 0);
						?>
						<select class="form-select select2 received_lot_id" name="received_lot_id[]" data-selected-lot-id="<?= $selectedReceiveLotId > 0 ? $selectedReceiveLotId : ''; ?>">
							<option value="">Select Lot</option>
							<?php if ($selectedReceiveLotId > 0) { ?>
								<option value="<?= $selectedReceiveLotId; ?>" selected>Loading...</option>
							<?php } ?>
						</select>
					</td>
						<td class="text-muted">
							<input type="number" name="pcs[]" class="form-control Pcs" value="<?= $row['pcs'] ?? "0"; ?>" placeholder="Enter Pcs" autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="hidden" name="raw-material-data[]" value="<?= $row['raw_material_string'] ?? null; ?>" class="form-control rmdata" placeholder="Enter Weight" autocomplete="off">
							<div class='d-flex gap-2'>
								<input type="number" name="weight[]" step="any" value="<?= $row['weight'] ?? "0"; ?>" class="form-control receivedWeight" placeholder="Enter Weight" autocomplete="off">
								<a class="btn btn-action text-white bg-danger Receivedmaterial"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i></a>
							</div>
						</td>
						<td class="text-muted">
							<select class="form-select select2 receiveLabour_type" name="labour_type[]">
							<option value="">Select Labour</option>
								<option value="PCS" <?php if (isset($row) && ('PCS' == $row['labour_type'])) {
									echo 'selected';
								} ?>>PCS</option>
								<option value="WEIGHT" <?php if (isset($row) && ('WEIGHT' == $row['labour_type'])) {
									echo 'selected';
								} ?>>WEIGHT</option>
							</select>
						</td>
						<td class="text-muted">
							<input type="number" name="labour[]" step="any" class="form-control receiveLabour" value="<?= $row['labour'] ?? "0"; ?>" placeholder="Enter Labour" autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="number" name="totalLabour[]" step="any" value="<?= $row['total_labour'] ?? "0"; ?>" class="form-control receiveTotal_labour readonly" value="0" readonly placeholder="Total Labour" autocomplete="off" />
							<input type="hidden" name="finalLabour[]" step="any" value="<?= isset($row['final_labour']) && !empty($row['final_labour']) ? $row['final_labour'] : 0; ?>" data-rmFinalLabour="<?= isset($RmFinalLabour) && !empty($RmFinalLabour) ? $RmFinalLabour : 0;?>" data-final_labour="<?= isset($row['final_labour']) && !empty($row['final_labour']) ? $row['final_labour'] : 0; ?>" class="form-control receiveFinal_labour" value="0" />
						</td>
						<td class="text-muted">
							<input type="number" name="rm_weight[]" step="any" value="<?= $row['row_material_weight'] ?? "0"; ?>" class="form-control receivedRmWeight readonly" readonly autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="number" name="total_weight[]" step="any" value="<?= $row['total_weight'] ?? "0"; ?>" class="form-control receivedfinalWeight readonly" readonly autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="number" name="touch[]" step="any" value="<?= $row['touch'] ?? $givenData['given_touch'] ?? "0"; ?>" step="any" class="form-control receivedTouch" autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="number" name="fine[]" step="any" value="<?= $row['fine'] ?? "0"; ?>" class="form-control receivedFine readonly" value="0" readonly autocomplete="off">
						</td>
						<td class="text-muted">
							<input type="text" name="remark[]" value="<?= $row['remark'] ?? "0"; ?>" class="form-control receivedRemark" placeholder="Enter remark" autocomplete="off">
						</td>
						<td class="text-muted">
							<span><?= ($row['created_at']) ? date('d-m-Y g:i A', strtotime($row['created_at'])) : ""; ?></span>
						</td>
						<td>
							<button type="button" class="btn btn-danger receiveddeleteRow">X</button>
						</td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td>
						<h4><span class='text-end ms-3' id="totalPcs"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="TotalWeight"></span></h4>
					</td>
					<td></td>
					<td>
						<h4><span class='text-end ms-3' id="labour"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="labourTotal"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="rowMaterialWeight"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="totalFinalWeight"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="totalTouch"></span></h4>
					</td>
					<td>
						<h4><span class='text-end ms-3' id="totalFine"></span></h4>
					</td>
					<td colspan="1" id="jama_baki"></td>
					<td colspan="2">
						<label class="form-check">
							<input class="form-check-input" id="is_kasar" name="is_kasar" <?php if (isset($givenData['is_kasar']) && $givenData['is_kasar'] == "YES") { echo 'checked';} ?> type="checkbox">
							<h4 class="form-check-label is_kasar">Is Kasar</h4>
						</label>
							<div class=" parent-div-party" style="display:none">
            				<?php 	$this->db->select('*'); $this->db->from('customer');
		                    $party =  $this->db->get()->result_array();  ?>
            				<select name="transfer_account" class="form-select transfer-account col-md-2 col-sm-3">
            					<option value="">Select Worker</option>
            					<?php
            					if (!empty($party)) {
            						foreach ($party as $row) { ?>
            							<option value="<?= $row['id']; ?>" <?php if (isset($givenData) && $row['id'] == ($givenData['transfer_account'] ?? NULL)) {echo 'selected';} ?>>
            							    <?= $row['name']; ?>
        							    </option>
            					<?php }
            					} ?>
            				</select>
            				<input type="hidden" name="given_id" class="form-control" value="<?= $givenData['id'] ?? null ?>">
            				<input type="hidden" name="garnu_id" class="form-control" value="<?= $garnuData['id'] ?? null ?>">
            			</div>
            			<div>
					</td>
					<input type="hidden" name="jama_baki" value="" class="jama_baki">
				</tr>
			</tfoot>
		</table>
	</div>
</div>
