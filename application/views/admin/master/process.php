<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Process </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("master/process/index/update/{$update['id']}") : base_url('master/process/index/store') ?>"
                            method="post">

                            <div class="row">
                                <div class="col-sm-2">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter Process Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>

                                </div>
                                <div class="col-sm-2">
                                    <label class="form-label" for="prd"> Sequence: </label>
                                    <input class="form-control" type="text" name="line_no" placeholder="Enter sequence Name"
                                        value="<?= $update['line_no'] ?? null ?>" id="line_no" required>

                                </div>
                                <div class="col-sm-2">
									<label class="form-label" for="prd"> It Is Finished Good: </label>
									<select class="form-select select2 " name="finished_good" id="finished_good">
										<option value="Yes" <?php if (isset($update) && $update['finished_good'] == 'Yes') { echo 'selected'; } ?>>Yes</option>
										<option value="No" <?php if (isset($update) && $update['finished_good'] == 'No') { echo 'selected'; } ?>>No</option>
									</select>
								</div>
                                <div class="col-sm-2">
									<label class="form-label" for="prd"> Defualt Labour Type: </label>
									<select class="form-select select2 " name="labour_type" id="labour_type">
										<option value="">Select Labour Type</option>
										<option value="PCS" <?php if (isset($update) && $update['labour_type'] == 'PCS') { echo 'selected'; } ?>>PCS</option>
										<option value="WEIGHT" <?php if (isset($update) && $update['labour_type'] == 'WEIGHT') { echo 'selected'; } ?>>WEIGHT</option>
										<option value="BOTH" <?php if (isset($update) && $update['labour_type'] == 'BOTH') { echo 'selected'; } ?>>BOTH</option>
									</select>
								</div>
                                <div class="col-sm-2">
									<label class="form-label" for="prd"> Update All Show OR Not: </label>
									<select class="form-select select2 " name="show_or_not" id="show_or_not">
										<option value="">Select Show OR Not</option>
										<option value="YES" <?php if (isset($update) && $update['show_or_not'] == 'YES') { echo 'selected'; } ?>>YES</option>
										<option value="NO" <?php if (isset($update) && $update['show_or_not'] == 'NO') { echo 'selected'; } ?>>NO</option>
									</select>
								</div>
								<div class="col-sm-2">
								    <div class="form-group">
    									<label class="form-label" for="autofillTouch"> Given Touch AutoFill OR Not: </label>
    									<select class="form-select" name="autofill_given_touch" id="autofillTouch">
    										<option value="">Select AutoFill OR Not</option>
    										<option value="YES" <?php if (isset($update) && $update['autofill_given_touch'] == 'YES') { echo 'selected'; } ?>>YES</option>
    										<option value="NO" <?php if (isset($update) && $update['autofill_given_touch'] == 'NO') { echo 'selected'; } ?>>NO</option>
    									</select>
								    </div>
								</div>
                                <div class="col-md-5 md-ms-4">
                                    <label class="form-label" for="prd"> &nbsp </label>
                                    <input class="btn btn-primary " type="submit"
                                        value="<?= isset($update) ? "Update" : "Submit" ?>">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mt-2 ">
                    <div class=" mt-5">
                        <table id="example_table" class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Serial No </th>
                                    <th>Action</th>
                                    <th>Name</th>
                                    <th>Sequence</th>
                                    <th>Finished Good</th>
                                    <th>Defualt Labour Type</th>
                                    <th>Update All Show OR Not</th>
                                    <th>Given Touch AutoFill YES OR NO</th>
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
                                                    <a class="btn btn-action bg-success text-white me-2"  data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-original-title="Edit"
                                                        href="<?= base_url('master/process/edit/') . $data['id'] ?>">
                                                        <i class="far fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <!--<a class="btn btn-action bg-danger text-white me-2"  data-bs-toggle="tooltip" data-bs-placement="top"-->
                                                    <!--    data-bs-original-title="Delete"-->
                                                    <!--    onclick="return confirm('Are you sure want to Delete.?');"-->
                                                    <!--    href="<?= base_url('master/process/delete/') . $data['id'] ?>">-->
                                                    <!--    <i class="fa-solid fa-trash"></i>-->
                                                    <!--</a>-->
                                                </div>
                                            </td>

                                            <td>
                                                <?= $data['name']; ?>
                                            </td>
                                            <td>
                                                <?= $data['line_no']; ?>
                                            </td>
                                            <td>
                                                <?= $data['finished_good']; ?>
                                            </td>
                                            <td>
                                                <?= $data['labour_type']; ?>
                                            </td>
                                            <td>
                                                <?= $data['show_or_not']; ?>
                                            </td>
                                            <td>
                                                <?= $data['autofill_given_touch']; ?>
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
</div>
