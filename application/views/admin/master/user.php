<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> User </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("master/user/index/update/{$update['id']}") : base_url('master/user/index/store') ?>"
                            method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter User  Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>
                                </div>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Mobile Number: </label>
                                    <input class="form-control" type="number" name="mobile"
                                        placeholder="Enter Mobile Number"
                                        value="<?= $update['mobile'] ?? null ?>" id="mobile" required>
                                </div>  
                                <?php if(!isset($update)){  ?>
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Password: </label>
                                    <input class="form-control" type="number" name="password"
                                        placeholder="Enter password"
                                        value="<?= $update['password'] ?? null ?>" id="password" required>
                                </div>
                                <?php } ?>

                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Type: </label>
                                    <select class="form-select select2" id="select" name="type" >
                                <option selected value="">Select User Type</option>
                                <option value="ADMIN" <?php if (!empty($update) && $update['type'] == 'ADMIN') {
                                                                    echo 'selected';
                                                                } ?>>Admin</option>                      
                                <option value="OTHER" <?php if (!empty($update) && $update['type'] == 'OTHER') {
                                                            echo 'selected';
                                                        } ?>>Other</option>
                                    </select>
                                </div>


                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Status: </label>
                                    <select class="form-select select2" id="status" name="status" >
                                <option selected value="">Select Status Type</option>
                                <option value="ACTIVE" <?php if (!empty($update) && $update['status'] == 'ACTIVE') {
                                                                    echo 'selected';
                                                                } ?>>Active</option>                      
                                <option value="INACTIVE" <?php if (!empty($update) && $update['status'] == 'INACTIVE') {
                                                            echo 'selected';
                                                        } ?>>In-active</option>
                                    </select>
                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Balance: </label>
                                    <input class="form-control" type="number" name="balance"
                                        placeholder="Enter balance"
                                        value="<?= $update['balance'] ?? null ?>" id="balance" required>
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
                                    <th>Mobile Number</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Balance</th>
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
                                                        href="<?= base_url('master/user/edit/') . $data['id'] ?>">
                                                        <i class="far fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="btn btn-action bg-danger text-white me-2"
                                                        onclick="return confirm('Are you sure want to Delete.?');"
                                                        href="<?= base_url('master/user/delete/') . $data['id'] ?>">
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
                                                <?= $data['type']; ?>
                                            </td>
                                            <td>
                                                <?= $data['status']; ?>
                                            </td>
                                            <td>
                                                <?= $data['balance']; ?>
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