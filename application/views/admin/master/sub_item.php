<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<h1 class="card-title"><b> Sub Item </b></h1>
			</div>
			<div class="card-body border-bottom py-3">
				<div class="col-md-12">
					<div class="row ms-1">
						<form class="row" action="<?= (isset($update)) ? base_url("master/sub_item/index/update/{$update['id']}") : base_url('master/sub_item/index/store') ?>" method="post">

							<div class="row">
								<div class="col-sm-3">
									<label class="form-label" for="prd"> Name: </label>
									<input class="form-control" type="text" name="name" placeholder="Enter Sub item Name" value="<?= $update['name'] ?? null ?>" id="name" required>

								</div>

								<div class="col-sm-3">
									<label class="form-label" for="prd"> Item: </label>
									<select class="form-select select2 " name="item_id" id="item_id">
										<option>Select Item</option>
										<?php
										$item = $this->db->get('item')->result();
										foreach ($item as $value) {
										?>
											<option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['item_id']) {
																					echo 'selected';
																				} ?>><?= $value->name; ?></option>
										<?php } ?>
									</select>
								</div>

								<div class="col-md-5 md-ms-4">
									<label class="form-label" for="prd"> &nbsp </label>
									<input class="btn btn-primary " type="submit" value="<?= isset($update) ? "Update" : "Submit" ?>">
								</div>
							</div>
						</form>
					</div>
				</div>
				<div class="mt-2 ">
					<table id="example_table" class="table table-vcenter card-table">
						<thead>
							<tr>
								<th>Serial No </th>
								<th>Action</th>
								<th>Sub Item</th>
								<th>Item</th>
								<th>Created At</th>
							</tr>
						</thead>
						<tbody>
							<?php $i = 1;
							if (count($data)) {
								foreach ($data as $data) {
							?>
									<tr>
										<td>
											<?= $i++ ?>
										</td>
										<td>
											<div>
												<a class="btn btn-action bg-success text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit" href="<?= base_url('master/sub_item/index/edit/') . $data['id'] ?>">
													<i class="far fa-edit" aria-hidden="true"></i>
												</a>
											</div>
										</td>
										<td>
											<?= $data['name']; ?>
										</td>
										<td>
											<?= $data['item_name']; ?>
										</td>
										<td>
											<?= $data['created_at']; ?>
										</td>
									</tr>
							<?php }
							} ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function() {
		$('.select2').select2({
			placeholder: "-- Select --",
			allowClear: true,
		});
	});
</script>