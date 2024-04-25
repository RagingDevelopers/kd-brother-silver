<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Account Type </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("master/account_type/index/update/{$update['id']}") : base_url('master/account_type/index/store') ?>"
                            method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name"
                                        placeholder="Enter Account  Name" value="<?= $update['name'] ?? null ?>"
                                        id="name" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Opening Amount: </label>
                                    <input class="form-control" type="number" name="opening_amount"
                                        placeholder="Enter Opening Amount"
                                        value="<?= $update['opening_amount'] ?? null ?>" id="opening_amount" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-5 md-ms-4">
                                        <label class="form-label" for="prd"> &nbsp </label>
                                        <input class="btn btn-primary " type="submit"
                                            value="<?= isset($update) ? "Update" : "Submit" ?>">
                                    </div>
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
                                    <th>Opening Amount</th>
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
                                                    <a class="btn btn-action bg-success text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit"
                                                        href="<?= base_url('master/account_type/edit/') . $data['id'] ?>">
                                                        <i class="far fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                </div>
                                            </td>

                                            <td>
                                                <?= $data['name']; ?>
                                            </td>
                                            <td>
                                                <?= $data['opening_amount']; ?>
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