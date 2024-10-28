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
	.modal-xxl {
		max-width: 95%;
	}

	.modal-xxl-content {
		width: 100%;
	}
	
	.modal-rm-xl {
		max-width: 70%;
	}
	
	.is_completed {
	    background-color: #e5ffe5 !important; 
	}
	
	td,
	th {
		white-space: nowrap;
		padding: 10px !important;
		align-items: center;
	}
</style>
<div class="">
<!-- Include Font Awesome -->

    <div class="col-sm-6 col-lg-12 mb-4">
        <div class="card shadow-sm border-0">
             	<div class="card-status-top bg-blue"></div>
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Input Field Section -->
                    <div class="col-md-6 mb-2 mb-md-0">
                        <label for="id" class="form-label fw-bold">Enter ID (Format: 13 or 13-11)</label>
                        <input type="text" name="id" id="id" class="form-control form-control-lg" placeholder="Enter 13 And 13-11" autocomplete="off">
                    </div>
                    
                    <!-- Button Section -->
                    <div class="col-md-6 text-md-end d-flex align-items-center justify-content-end flex-column flex-md-row">
                        <!-- Receive Button -->
                        <button type="button" class="btn btn-success  me-3 openReceive">
                            <i class="fas fa-inbox mx-1"></i> Receive
                        </button>
                        
                        <!-- Open Data Button -->
                        <button type="submit" class="btn btn-primary openData d-flex align-items-center">
                            <i class="fas fa-folder-open mx-1"></i> Open Data
                            <span class="spinner-border border-3 ms-2 spinner-border-sm text-white" style="display: none;" role="status"></span>
                        </button>
    
                        <!-- Hidden Audio -->
                        <audio id="audioPlayer" class="d-none">
                            <source src="<?= base_url('assets') ?>/message.mp3" type="audio/mpeg">
                        </audio>
                    </div>
                </div>
            </div>
        </div>
    </div>

	
	<!-- Process -->
	<section class="content">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
					    	<div class="card-status-top bg-blue"></div>
						<div class="col-md-3">
							Process Garnu Overview Report
						</div>
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="dashboard_master_process_report" class="table table-bordered table-striped">
								<thead>
									<tr class="text-center">
										<th>Sr no</th>
										<th>Garnu</th>
										<!--<th>Details</th>-->
										<?php
										$process      = $this->dbh->find("process", [ "status" => true ], false);
										$processCount = count($process);
										?>
										<?php foreach ( $process as $p ): ?>
											<th colspan="1">
												<?= $p['name'] ?>
											</th>
										<?php endforeach; ?>
									</tr>
									<tr class="text-center">
										<th colspan="3">---</th>
										<?php foreach ( range(1, $processCount) as $p ): ?>
											<th>Weight</th>
											<th style="display:none;">Quantity</th>
										<?php endforeach; ?>
									</tr>
								</thead>
								<tbody>
									<?php
									
									function AdClass($condtion , $class) {
									    
									    if($condtion) {
									        return $class;
									    }
									    
									    return "";
									    
									}
									
									$sqlQueryFn = function($params= null)  {
									    return"SELECT
											G.*,
											G.creation_date as given_date,
											R.creation_date as receive_date,
											R.pcs as receive_pcs,
											R.weight as receive_weight,
											GR.name as garnuName
										FROM given G
											LEFT JOIN process P ON P.id = G.process_id
											LEFT JOIN `receive` R ON  G.id  = R.given_id
											LEFT JOIN `garnu` GR ON  GR.id  = G.garnu_id
											" . ($params ? "GROUP BY $params" : "") . " 
											ORDER BY G.id DESC";
									} ;
									$records     = $this->db->query($sqlQueryFn("GR.id"))->result_array();
									$recordsWithGroupBy = (array) $this->db->query($sqlQueryFn("G.id"))->result_array();
									$url         = base_url('admin/manufacturing/process/manage/');

									$getProcessDetails  = function ($processId, $garnuId, $column) use ($recordsWithGroupBy) {
										$filteredRecords = \array_filter($recordsWithGroupBy, function ($record) use ($processId, $garnuId) {
											return $record["process_id"] == $processId && $record['garnu_id'] == $garnuId;
										});
										if (empty($filteredRecords)) return 0;
										$result = \array_reduce(
											$filteredRecords,
											function($carry, $item) use($column) {
											    return  $carry + (double) $item[$column];
											},
											0
										);
										return $result;
									};
									
								$getDaysDifference = function ($processId, $garnuId) use ($recordsWithGroupBy): int {
                                    $filteredRecords = \array_filter($recordsWithGroupBy, function ($record) use ($processId, $garnuId) {
                                        return $record["process_id"] == $processId && $record['garnu_id'] == $garnuId;
                                    });
                                
                                    if (empty($filteredRecords)) {
                                        return 0;
                                    }
                                
                                    $givenDates = \array_column($filteredRecords, 'given_date');
                                    $receivedDates = \array_column($filteredRecords, 'received_date');
                                    
                                    if (empty($givenDates) || empty($receivedDates)) {
                                        return 0;
                                    }
                                
                                    $minCreationDate = \min($givenDates);
                                    $maxReceivedDate = \max($receivedDates);
                                
                                    if (!$minCreationDate || !$maxReceivedDate) {
                                        return 0;
                                    }
                                
                                    return (new \DateTime($minCreationDate))->diff(new \DateTime($maxReceivedDate))->days ?: 0;
                                };

									?>
									<?php foreach ( $records as $i => $row ) { ?>
										<tr>
											<td>
												<?= ++$i; ?>
											</td>
											<td>
												<button class="split_code btn btn-default btn-sm">
													<b> <?= $row['garnuName'] ?></b>
												</button>
											</td>
											<!--<td>-->
											<!--	<cite>-->
											<!--		<b>Touch Group : </b>-->
											<!--		<span class='text-primary mt-1'><?= $row['group_name'] ?? "" ?></span>-->
											<!--	</cite>-->
											<!--	<br />-->
											<!--	<cite>-->
											<!--		<b>Item : </b>-->
											<!--		<span class='text-primary mt-1'><?= $row['item_name'] ?? "" ?> </span>-->
											<!--	</cite>-->
											<!--	<br />-->
											<!--</td>-->
										
											<?php foreach ( $process as $p ):
												$givenWeight = $getProcessDetails($p['id'], $row["garnu_id"], "given_weight");
												$givenPcs    = $getProcessDetails($p['id'], $row["garnu_id"], "given_qty");
												$receivePcs  = 0;
												$rejected_pcs = 0;

												if ($givenPcs > 0) {
													$receivePcs   = $getProcessDetails($p['id'], $row["garnu_id"], "receive_pcs");
												}
												?>
												<td class="text-center p-3 <?php // echo AdClass($row['is_completed'], "is_completed")?>">
													<?php if ($givenWeight > 0) { ?>
														<div >
															<span class="text-danger font-weight-bold">G - <?= $givenWeight ?></span>
														</div>
														<div >
															<span class="text-success font-weight-bold">R -
																<?= $getProcessDetails($p['id'], $row['garnu_id'], "receive_weight"); ?></span>
														</div>
														<div>
															<span class="text-muted">Day -
																<?= $getDaysDifference($p['id'], $row['garnu_id']); ?></span>
														</div>
													<?php } ?>
												</td>

												<td class="text-center p-3"  style="display:none;">
													<?php if ($givenPcs > 0) {
														$receivePcs = $getProcessDetails($p['id'], $row['garnu_id'], "receive_pcs"); 
														?>
														
														<div >
															<span class="text-danger font-weight-bold">G - <?= $givenPcs ?></span>
														</div>

														<div >
															<span class="text-success font-weight-bold">R - <?= $receivePcs ?></span>
															<?php if ($givenWeight > 0 && $rejected_pcs > 0) { ?>
																+ <span class="badge bg-danger ms-2"      data-toggle="tooltip" data-placement="right" title="Rejected PCS in <?= $p['name'] ?> Receive" ><?= $rejected_pcs ?></span>
															<?php } ?>
														</div>

														<?php if (($pending = $givenPcs - ($receivePcs + $rejected_pcs)) > 0) { ?>
															<div>
																<span class="text-warning fw-bold text-blink">Pending -
																	<?= $pending ?></span>
															</div>
														<?php } ?>
													<?php } ?>
												</td>

											<?php endforeach; ?>
										</tr>
									<?php } ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- End Process -->

</div>

<form action="#" id="received-garnu">
	<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="received1-report" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-xxl" role="document">
			<div class="modal-content modal-xxl-content">
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

<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="received-report" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-rm-xl modal-dialog-scrollable" role="document">
		<div class="modal-content modal-rm-content">
			<div class="modal-header">
				<h5 class="modal-title">Received Row Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12 text-center">
						<h3> Given Row Material</h3>
					</div>
					<div class="col-md-12">
						<div class="row-material-section">

						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-vcenter card-table table-striped receivedRmTable">
						<thead>
							<tr>
								<th>Row Material</th>
								<th>Lot Wise RM</th>
								<th>Touch %</th>
								<th>Weight</th>
								<th>Pcs</th>
								<th class="hide_labour">Labour</th>
								<th class="hide_labour">Labour</th>
								<th class="hide_labour">Total Labour</th>
								<th></th>
							</tr>
						</thead>
						<tbody id="JBody">
							<?php
							if (empty($given_row_materials)) {
								$given_row_materials[] = [
									'row_material_id' => 0,
									'lot_wise_rm_id'  => 0,
									'rmWeight'        => 0,
									'rmTouch'         => 0,
									'rmQuantity'      => 0,
									'labour_type'     => "",
									'labour'          => 0,
									'total_labour'    => 0,
									'id'              => 0
								];
							}
							foreach ($given_row_materials as $row) { ?>
								<input type="hidden" class="ids" value="0" />
								<tr class="mainRow2 main-row">
									<td>
										<input type="hidden" class="received_detail_id" />
										<select class="form-select select2 row_material2 rowMateralWiseLot2" data-given_id="<?= $process_data['id'] ?? null ?>" data-garnu_id="<?= $data['id'] ?? null ?>" data-lot_wise_rm_id="<?php if (isset($row) && $row['lot_wise_rm_id']) { echo $row['lot_wise_rm_id']; } ?>">
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
									<td>
										<select class="form-select select2 lot_wise_rm_id2">
											<option value="">Select LW RM</option>
										</select>
									</td>
									<td class="text-muted">
										<input type="number" class="form-control touch2" value="0" placeholder="Enter Touch" step="any" autocomplete="off">
									</td>
									<td class="text-muted">
										<input type="number" class="form-control weight2" value="0" placeholder="Enter Weight" autocomplete="off">
									</td>
									<td class="text-muted">
										<input type="number" class="form-control quantity2" value="0" placeholder="Enter Quantity" autocomplete="off">
									</td>
									<td class="text-muted hide_labour">
										<select class="form-select select2 labour_type defualt_labour_type" name="labour_type">
											<option value="">Select Labour</option>
											<option value="PCS">PCS</option>
											<option value="WEIGHT">WEIGHT</option>
										</select>
									</td>
									<td class="text-muted hide_labour">
										<input type="number" class="form-control labour" value="0" placeholder="Enter Labour" autocomplete="off">
									</td>
									<td class="text-muted hide_labour">
										<input type="number" class="form-control total_labour readonly" value="0" readonly placeholder="Total Labour" autocomplete="off">
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
								</td>
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
								<td class="hide_labour">
									<div class="d-flex">
										<h4><span class='text-end ms-3 total-labour'>0</span></h4>
									</div>
								</td>
								<td class="hide_labour">
									<div class="d-flex">
										<h4><span class='text-end ms-3 final-labour'>0</span></h4>
									</div>
								</td>
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

<div class="modal modal-blur fade" data-bs-backdrop="static" data-bs-keyboard="false" id="showDiffrence" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable modal-lgg" role="document">
		<div class="modal-content modal-lgg-content">
			<div class="modal-header">
				<h5 class="modal-title">Given & Received Row Material Diffrence</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="table-responsive">
					<table class="table table-vcenter card-table table-striped rowMaterial">
						<thead>
							<tr>
								<th>Row Material</th>
								<th>Touch %</th>
								<th>Weight</th>
								<th>Quantity</th>
								<th>Row Material</th>
								<th>Touch %</th>
								<th>Weight</th>
								<th>Quantity</th>
								<th>Touch Diff</th>
								<th>Weight Diff</th>
								<th>Quantity Diff</th>
							</tr>
						</thead>
						<tbody id="diffTBody">
							<tr class="diffmainRow">
								<td class="given givenRmDiff"></td>
								<td class="given givenTouchDiff"></td>
								<td class="given givenWeightDiff"></td>
								<td class="given givenQtyDiff"></td>
								<td class="received receiveRmDiff"></td>
								<td class="received receiveTouchDiff"></td>
								<td class="received receiveWeightDiff"></td>
								<td class="received receiveQtyDiff"></td>
								<td class="diffrence TouchDiff"></td>
								<td class="diffrence WeightDiff"></td>
								<td class="diffrence QtyDiff"></td>
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<td class=""> Total  =></td>
								<td class="givenTotaltouchDiff "></td>
								<td class="givenTotalweightDiff "></td>
								<td class="givenTotalqtyDiff "></td>
								<td class=""></td>
								<td class="receiveTotaltouchDiff"></td>
								<td class="receiveTotalweightDiff"></td>
								<td class="receiveTotalqtyDiff"></td>
								<td colspan="3"></td>
							</tr>
							<tr>
								<td colspan='2' class="diffrence"> Total Diffrence =></td>
								<td colspan='2' class="diffrence TotaltouchDiff"></td>
								<td colspan='2' class="diffrence TotalweightDiff"></td>
								<td colspan='2' class="diffrence TotalqtyDiff"></td>
								<td colspan='3' class="AverageTouch diffrence" style="color:red;"></td>
							</tr>
						</tfoot>
					</table>
				</div>
				<div class="modal-footer justify-content-between">
				    <div></div>
					<div>
						<button type="button" class="btn btn-danger btn-danger close" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
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
		
		if (enteredId && enteredId.includes("-")) {
			var splitArray = enteredId.split("-");
			
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
						const audioPlayer = $('#audioPlayer')[0];
						audioPlayer.play();
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
			SweetAlert("warning", "Invalid ID: No Desh present or ID is empty.");
		}
	});
</script>
