<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <div class="col-sm-11">
                    <h1 class="card-title"><b> Customer Report </b></h1>
                </div>
                <div class="col-sm-1 ms-5 ps-5">
                    <a class="btn btn-action bg-primary text-white"
                        href="<?= base_url('registration/customer/index/add') ?>">
                        <i class="far fa-plus card-title" aria-hidden="true">
                        </i>
                    </a>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="table-responsive">
                    <div class="col-md-12 mb-5 ">
                        <div class="mt-3">
                            <table id="example_table" class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Serial No </th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Mobile Number</th>
                                        <th scope="col">City</th>
                                        <th scope="col">Account Type</th>
                                        <th scope="col">Opening Amount</th>
                                        <th scope="col">Opening Amount Type</th>
                                        <th scope="col">Opening Fine</th>
                                        <th scope="col">Opening Fine Type</th>
                                        <th scope="col">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    if (count($data)) {
                                        foreach ($data as $data) {
                                            ?>
                                            <tr>
                                                <td scope="row">
                                                    <?= $i++ ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <a class="btn btn-action bg-success text-white me-2"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit"
                                                            href="<?= base_url('registration/customer/edit/') . $data['id'] ?>">
                                                            <i class="far fa-edit" aria-hidden="true"></i>
                                                        </a>
                                                        <a class="btn btn-action bg-danger text-white me-2"
                                                        data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete"
                                                            onclick="return confirm('Are you sure want to Delete.?');"
                                                            href="<?= base_url('registration/customer/delete/') . $data['id'] ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>

                                                <td>
                                                    <?= $data['name']; ?>
                                                </td>
                                                <td>
                                                    <?= $data['mobile']; ?>
                                                </td>
                                                <td>
                                                    <?= $data['city_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $data['account_type_name']; ?>
                                                </td>
                                                <td>
                                                    <?= $data['opening_amount']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($data['opening_amount_type'] == "JAMA") { ?>
                                                        <div class="badge bg-blue">
                                                            <?= $data['opening_amount_type']; ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="badge bg-red">
                                                            <?= $data['opening_amount_type']; ?>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?= $data['opening_fine']; ?>
                                                </td>
                                                <td>
                                                    <?php if ($data['opening_fine_type'] == "JAMA") { ?>
                                                        <div class="badge bg-blue">
                                                            <?= $data['opening_fine_type']; ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="badge bg-red">
                                                            <?= $data['opening_fine_type']; ?>
                                                        </div>
                                                    <?php } ?>
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

</div>