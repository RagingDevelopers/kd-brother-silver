<div class="col-md-2 col-sm-3 d-flex gap-2 mb-3">
	<label class="form-label mt-2" for="customer">Customer: </label>
	<select class="form-select select2 customer">
		<option value="">Select Customer</option>
		<?php
		if (!empty($customer)) {
			foreach ($customer as $row) { ?>
				<option value="<?= $row['id']; ?>" <?php if (isset($givenData) && $row['id'] == $givenData['worker_id']) {
														echo 'selected';
													} ?>><?= $row['name']; ?></option>
		<?php }
		} ?>
	</select>
	<input type="hidden" name="given_id" id="" class="form-control" value="<?= $givenData['id'] ?? null ?>">
	<input type="hidden" name="garnu_id" id="" class="form-control" value="<?= $garnuData['id'] ?? null ?>">
</div>
<table class="table table-vcenter card-table table-striped mb-4">
	<thead>
		<tr>
			<th>Gross Weight</th>
			<th>Received Weight</th>
			<th>Touch Group</th>
			<th>Date</th>
			<th>Created At</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?= $garnuData['garnu_weight']; ?></td>
			<td class="text-muted"><?= $givenData['given_weight']; ?></td>
			<td class="text-muted"><?= $garnuData['touch']; ?></td>
			<td class="text-muted"><?= $garnuData['creation_date']; ?></td>
			<td class="text-muted"><?= $garnuData['created_at']; ?></td>
		</tr>
	</tbody>
</table>

<h3 class="pb-0 mb-2 mt-5 ps-2">Data Fetched From: <?= $garnuData['name']; ?></h3>
<table class="table table-vcenter card-table table-striped">
	<thead>
		<tr>
			<th>Receiveid Pcs</th>
			<th>Receiveid Weight</th>
			<th>Row Meterial Weight</th>
			<th>Final Weight</th>
			<th>Receiveid Remark</th>
			<th></th>
		</tr>
	</thead>
	<tbody id="ReceivedBody">
		<?php
		if (empty($receivedData)) {
			$receivedData[] = [
				'pcs' 			 => 0,
				'weight'         => 0,
				'rm_weight'      => 0,
				'total_weight'   => 0,
				'remark'     	 => '',
				'id'             => 0
			];
		}
		foreach ($receivedData as $row) {
			if($row['id'] > 0){
				$rawMaterial = $this->dbh->getWhereResultArray('receive_row_meterial', ['received_id' => $row['id']]);
				foreach ($rawMaterial as $rm) {
					$rm_string_array[] = implode(',', [
						$rm['row_material_id'],
						$rm['touch'],
						$rm['weight'],
						$rm['quantity'],
						$rm['id']
					]);
				}
				$row['raw_material_string'] = implode('|', $rm_string_array ?? []) ?? [];
			}
		?>
			<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
			<tr class="ReceivedMainRow">
				<input type="hidden" class="rcid" name="rcid[]" value="<?= $row['id'] ?? "0"; ?>" />
				<td class="text-muted">
					<input type="number" name="pcs[]" class="form-control Pcs" value="<?= $row['pcs'] ?? "0"; ?>" placeholder="Enter Pcs" autocomplete="off">
				</td>
				<td class="text-muted">
					<input type="hidden" name="raw-material-data[]" value="<?=$row['raw_material_string'] ?? null;?>" class="form-control rmdata" placeholder="Enter Weight" autocomplete="off">
					<div class='d-flex gap-2'>
						<input type="number" name="weight[]" value="<?= $row['weight'] ?? "0"; ?>" class="form-control receivedWeight" placeholder="Enter Weight" autocomplete="off">
						<a class="bg-danger btn btn-action text-danger-fg me-2 Receivedmeterial" data-demo-color data-bs-toggle="tooltip" data-bs-placement="top" data-garnu_id="<?= $id; ?>" data-given_id="<?= $result->id; ?>" data-bs-original-title="Received" href="#">
							<i class="far fa-edit" aria-hidden="true"></i>
						</a>
					</div>
				</td>
				<td class="text-muted">
					<input type="number" name="rm_weight[]" value="<?= $row['row_meterial_weight'] ?? "0"; ?>" class="form-control receivedRmWeight" value="0" readonly autocomplete="off">
				</td>
				<td class="text-muted">
					<input type="number" name="total_weight[]" value="<?= $row['total_weight'] ?? "0"; ?>" class="form-control receivedfinalWeight" value="0" readonly autocomplete="off">
				</td>
				<td class="text-muted">
					<input type="text" name="remark[]" value="<?= $row['remark'] ?? "0"; ?>" class="form-control receivedRemark" placeholder="Enter remark" autocomplete="off">
				</td>
				<td>
					<button type="button" class="btn btn-danger receiveddeleteRow">X</button>
				</td>
			</tr>
	</tbody>
<?php } ?>
</table>
