<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Customer Report </b></h1>
                <a class="btn btn-action bg-primary text-white m-1 p-3"
                    href="<?=base_url('master/customer_details/index/add')  ?>">
                    <i class="far fa-plus card-title" aria-hidden="true">
                    </i>
                </a>
            </div>
             <div class="mt-3">
                <table id="example_table" class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Serial No </th>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Mobile Number</th>
                            <th>City</th>
                            <th>Account Type</th>
                            <th>Opening Amount</th>
                            <th>Opening Amount Type</th>
                            <th>Opening Fine</th>
                            <th>Opening Fine Type</th>
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
                                            <a class="btn btn-action bg-success text-white me-2"
                                                href="<?= base_url('master/customer_details/edit/') . $data['id'] ?>">
                                                <i class="far fa-edit" aria-hidden="true"></i>
                                            </a>
                                            <a class="btn btn-action bg-danger text-white me-2"
                                                onclick="return confirm('Are you sure want to Delete.?');"
                                                href="<?= base_url('master/customer_details/delete/') . $data['id'] ?>">
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