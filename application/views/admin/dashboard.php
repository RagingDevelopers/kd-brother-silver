<style>
	.readonly {
		background-color: #ebebeb;
		color: black;
	}

	.receivedWeight {
		background-color: #e9f7ff;
		color: black;
	}

	tr .given {
		background-color: #ffe1e1;
		color: black;
	}

	tr .received {
		background-color: #d9ffd9;
		color: black;
	}

	.table td {
		font-weight: bold;
	}
</style>
<div class="">
	<div class="col-sm-6 col-lg-12 mb-3">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-md-4">
						<input type="text" name="id" id="id" class="form-control" placeholder="Enter 13 And 13_11" autocomplete="off">
					</div>
					<div class="col-md-4">
						<button type="button" class="btn btn-success btn-success openReceive">
							<span class="mx-1">Receive </span>
						</button>
						<button type="submit" class="input-icon btn btn-primary btn-primary openData">Open Data
							<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row row-deck row-cards">
		<div class="col-sm-6 col-lg-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="subheader">Revenue</div>
						<div class="ms-auto lh-1">
							<div class="dropdown">
								<a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a class="dropdown-item active" href="#">Last 7 days</a>
									<a class="dropdown-item" href="#">Last 30 days</a>
									<a class="dropdown-item" href="#">Last 3 months</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-baseline">
						<div class="h1 mb-0 me-2">$4,300</div>
						<div class="me-auto">
							<span class="text-green d-inline-flex align-items-center lh-1">
								8% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
								<svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M3 17l6 -6l4 4l8 -8" />
									<path d="M14 7l7 0l0 7" />
								</svg>
							</span>
						</div>
					</div>
				</div>
				<div id="chart-revenue-bg" class="chart-sm"></div>
			</div>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="subheader">Revenue</div>
						<div class="ms-auto lh-1">
							<div class="dropdown">
								<a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a class="dropdown-item active" href="#">Last 7 days</a>
									<a class="dropdown-item" href="#">Last 30 days</a>
									<a class="dropdown-item" href="#">Last 3 months</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-baseline">
						<div class="h1 mb-0 me-2">$4,300</div>
						<div class="me-auto">
							<span class="text-green d-inline-flex align-items-center lh-1">
								8% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
								<svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M3 17l6 -6l4 4l8 -8" />
									<path d="M14 7l7 0l0 7" />
								</svg>
							</span>
						</div>
					</div>
				</div>
				<div id="chart-revenue-bg" class="chart-sm"></div>
			</div>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="subheader">New clients</div>
						<div class="ms-auto lh-1">
							<div class="dropdown">
								<a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a class="dropdown-item active" href="#">Last 7 days</a>
									<a class="dropdown-item" href="#">Last 30 days</a>
									<a class="dropdown-item" href="#">Last 3 months</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-baseline">
						<div class="h1 mb-3 me-2">6,782</div>
						<div class="me-auto">
							<span class="text-yellow d-inline-flex align-items-center lh-1">
								0% <!-- Download SVG icon from http://tabler-icons.io/i/minus -->
								<svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M5 12l14 0" />
								</svg>
							</span>
						</div>
					</div>
					<div id="chart-new-clients" class="chart-sm"></div>
				</div>
			</div>
		</div>
		<div class="col-sm-6 col-lg-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="subheader">Active users</div>
						<div class="ms-auto lh-1">
							<div class="dropdown">
								<a class="dropdown-toggle text-muted" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Last 7 days</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a class="dropdown-item active" href="#">Last 7 days</a>
									<a class="dropdown-item" href="#">Last 30 days</a>
									<a class="dropdown-item" href="#">Last 3 months</a>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex align-items-baseline">
						<div class="h1 mb-3 me-2">2,986</div>
						<div class="me-auto">
							<span class="text-green d-inline-flex align-items-center lh-1">
								4% <!-- Download SVG icon from http://tabler-icons.io/i/trending-up -->
								<svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none" />
									<path d="M3 17l6 -6l4 4l8 -8" />
									<path d="M14 7l7 0l0 7" />
								</svg>
							</span>
						</div>
					</div>
					<div id="chart-active-users" class="chart-sm"></div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="row row-cards">
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto">
									<span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
											<path d="M12 3v3m0 12v3" />
										</svg>
									</span>
								</div>
								<div class="col">
									<div class="font-weight-medium">
										132 Sales
									</div>
									<div class="text-muted">
										12 waiting payments
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto">
									<span class="bg-green text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/shopping-cart -->
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
											<path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
											<path d="M17 17h-11v-14h-2" />
											<path d="M6 5l14 1l-1 7h-13" />
										</svg>
									</span>
								</div>
								<div class="col">
									<div class="font-weight-medium">
										78 Orders
									</div>
									<div class="text-muted">
										32 shipped
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto">
									<span class="bg-twitter text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-twitter -->
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M22 4.01c-1 .49 -1.98 .689 -3 .99c-1.121 -1.265 -2.783 -1.335 -4.38 -.737s-2.643 2.06 -2.62 3.737v1c-3.245 .083 -6.135 -1.395 -8 -4c0 0 -4.182 7.433 4 11c-1.872 1.247 -3.739 2.088 -6 2c3.308 1.803 6.913 2.423 10.034 1.517c3.58 -1.04 6.522 -3.723 7.651 -7.742a13.84 13.84 0 0 0 .497 -3.753c0 -.249 1.51 -2.772 1.818 -4.013z" />
										</svg>
									</span>
								</div>
								<div class="col">
									<div class="font-weight-medium">
										623 Shares
									</div>
									<div class="text-muted">
										16 today
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-sm-6 col-lg-3">
					<div class="card card-sm">
						<div class="card-body">
							<div class="row align-items-center">
								<div class="col-auto">
									<span class="bg-facebook text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/brand-facebook -->
										<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none" />
											<path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3" />
										</svg>
									</span>
								</div>
								<div class="col">
									<div class="font-weight-medium">
										132 Likes
									</div>
									<div class="text-muted">
										21 today
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<form action="#" id="received-garnu">
	<div class="modal modal-blur fade modal-xl" data-bs-backdrop="static" data-bs-keyboard="false" id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xl" role="document">
			<div class="modal-content">
				<div id="receveData"></div>
				<div class="modal-footer justify-content-between">
					<button type="button" class="btn btn-success btn-success receivedAddButton2">
						<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
					</button>
					<div>
						<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="input-icon btn btn-primary btn-primary submit-btn">Save Changes
							<span style="display: none;" class="spinner-border border-3 ms-2 spinner-border-sm text-white" role="status"></span>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Row Material</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="TBody">
						<?php
						if (empty($given_row_material)) {
							$given_row_material[] = [
								'row_material_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($given_row_material as $row) { ?>
							<input type="hidden" class="ids" name="ids[]" value="<?= $row['id'] ?? "0"; ?>" />
							<tr class="mainRow">
								<td>
									<input type="hidden" class="rowid" name="rowid[]" value="<?= $row['id'] ?? "0"; ?>" />
									<select class="form-select select2 row_material" name="row_material[]">
										<option value="">Select RM</option>
										<?php
										if (!empty($row_material)) {
											for ($i = 0; $i < count($row_material); $i++) { ?>
												<option value="<?= $row_material[$i]['id']; ?>" <?php if (isset($row) && $row_material[$i]['id'] == $row['row_material_id']) {
																									echo 'selected';
																								} ?>><?= $row_material[$i]['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" name="rmTouch[]" value="<?= $row['touch'] ?? 0 ?>" required class="form-control touch" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" name="rmWeight[]" value="<?= $row['weight'] ?? 0 ?>" class="form-control weight" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" name="rmQuantity[]" value="<?= $row['quantity'] ?? 0 ?>" class="form-control quantity" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger deleteRow">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success addButton">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<button type="button" class="btn btn-primary btn-primary save">Save Changes</button>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Row Material</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="JBody">
						<?php
						if (empty($given_row_materials)) {
							$given_row_materials[] = [
								'row_material_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($given_row_materials as $row) { ?>
							<input type="hidden" class="ids" value="0" />
							<tr class="mainRow2 main-row">
								<td>
									<input type="hidden" class="received_detail_id" />
									<select class="form-select select2 row_material2">
										<option value="">Select RM</option>
										<?php
										if (!empty($row_material)) {
											foreach ($row_material as $rm) { ?>
												<option value="<?= $rm['id']; ?>" <?php if (isset($row) && $rm['id'] == $row['row_material_id']) {
																						echo 'selected';
																					} ?>><?= $rm['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" class="form-control touch2" value="0" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control weight2" value="0" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control quantity2" value="0" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger deleteRow2">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success addButton2">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-primary saveRmData">Save Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade modal-lg" data-bs-backdrop="static" data-bs-keyboard="false" id="metalType-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Metal Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<table class="table table-vcenter card-table table-striped">
					<thead>
						<tr>
							<th>Metal Type</th>
							<th>Touch %</th>
							<th>Weight</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody id="MetalBody">
						<?php
						if (empty($metalData)) {
							$metalData[] = [
								'metal_type_id' => 0,
								'rmWeight'        => 0,
								'rmTouch'         => 0,
								'rmQuantity'      => 0,
								'id'              => 0
							];
						}
						foreach ($metalData as $row) { ?>
							<!-- <input type="hidden" class="ids" value="0" /> -->
							<tr class="metalRow">
								<td>
									<input type="hidden" class="process_metal_type" value="0" />
									<select class="form-select select2 metal_type">
										<option value="">Select Metal Type</option>
										<?php
										if (!empty($metal_type)) {
											foreach ($metal_type as $mt) { ?>
												<option value="<?= $mt['id']; ?>" <?php if (isset($row) && $mt['id'] == $row['metal_type_id']) {
																						echo 'selected';
																					} ?>><?= $mt['name']; ?>
												</option>
										<?php }
										} ?>
									</select>
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalTouch" value="0" placeholder="Enter Touch" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalWeight" value="0" placeholder="Enter Weight" autocomplete="off">
								</td>
								<td class="text-muted">
									<input type="number" class="form-control metalQuantity" value="0" placeholder="Enter Quantity" autocomplete="off">
								</td>
								<td>
									<button type="button" class="btn btn-danger metalDeleteRow">X</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 metal-total-qty'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-success btn-success metalAddButton">
					<span class="mx-1">Add </span><i class="fa-solid fa-plus"></i>
				</button>
				<div>
					<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary btn-primary saveMetalData">Save Changes</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-blur fade" id="givenRowMaterial" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<div class="col-md-3">
					<p class="modal-title">Issue Weight </p>
				</div>
				<div class="col-md-4">
					<p class="modal-title">Garnu Weight:- <span class="garnu_weight"></span></p>
				</div>
				<div class="col-md-4 text-center">
					<p class="modal-title">Garnu Name:- <span class="garnu_name"></span></p>
				</div>
				<!-- <div class="col-md-1"> -->
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				<!-- </div> -->
			</div>
			<div class="modal-body">
				<table class="table card-table table-vcenter text-center text-nowrap ">
					<thead class="thead-light">
						<th>Row Material</th>
						<th scope="col">Touch (%)</th>
						<th scope="col">Weight(Gm)</th>
						<th scope="col">Quantity</th>
					</thead>

					<tbody class="paste append-here">
						<tr class="sectiontocopy">
							<td>
								<select class="form-select select2 given-row_material_id" disabled readonly>
									<option value="">Select Metal</option>
									<?php
									$row_material = $this->db->get('row_material')->result();
									foreach ($row_material as $value) { ?>
										<option value="<?= $value->id; ?>">
											<?= $value->name; ?>
										</option>
									<?php } ?>
								</select>
							</td>

							<td>
								<input class="form-control given-touch" type="number" disabled readonly placeholder="Enter touch(%)" value="0">
							</td>
							<td>
								<input class="form-control given-weight" type="number" disabled readonly placeholder="Enter Weight" value="0">
							</td>
							<td>
								<input class="form-control given-quantity" type="number" value="0" disabled readonly>
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h3>Total :</h3>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-touch'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-weight'>0</span></h4>
								</div>
							</td>
							<td>
								<div class="d-flex">
									<h4><span class='text-end ms-3 given-total-quantity'>0</span></h4>
								</div>
							</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="modal-footer justify-content-between">
				<span></span>
				<button type="button" class="btn btn-danger btn-danger" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<?php $time = time() ?>
<script src="<?= base_url("assets") ?>/dist/js/process.js?v=<?= $time ?>"></script>
<script>
	$(document).ready(function() {
		$('.openData').click(function() {
			$('#id').val(function(index, value) {
				return value.replace(/\D/g, '');
			});
			var enteredId = $('#id').val();
			if (enteredId != "" && enteredId != " ") {
				var targetUrl = `${BaseUrl}manufacturing/process/manage/` + enteredId;
				window.open(targetUrl, '_blank', 'noopener noreferrer');
				$('#id').val("");
			} else {
				SweetAlert("warning", "ID is empty, Please Enter ID.");
			}
		});
	});

	$(document).on("click", ".openReceive", function() {
		event.preventDefault();
		receiveBtn = $(this);
		var enteredId = $('#id').val();
		$("#receveData").html("");

		if (enteredId && enteredId.includes("_")) {
			var splitArray = enteredId.split("_");

			if (splitArray.length === 2 && !isNaN(splitArray[0]) && !isNaN(splitArray[1])) {
				var garnu_id = splitArray[0];
				var given_id = splitArray[1];

				$.ajax({
					url: `${BaseUrl}manufacturing/process/receiveGarnu`,
					method: "POST",
					showLoader: true,
					data: {
						garnu_id,
						given_id,
					},
				}).then(function(response) {
					var response = JSON.parse(response)
					if (response.success) {
						$("#receveData").html(response.data);
						$('#id').val("");
						$("#received1-report").modal("show");
					} else {
						SweetAlert("warning", response.message);
					}
				});

			} else {
				SweetAlert("warning", "Invalid ID: ID does not split into two numeric parts.");
			}
		} else {
			SweetAlert("warning", "Invalid ID: No underscore present or ID is empty.");
		}
	});
</script>
