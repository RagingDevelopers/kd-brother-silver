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
                        <form class="row" action="<?= (isset($update_data)) ?  base_url('master/Process/index/update') : base_url('master/Process/index/add') ?>" method="post">
                            <input type="hidden" name="id" value="<?php if (isset($update_data)) {
                                                                        echo $update_data['id'];
                                                                    } ?>">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter Process Name" value="<?php if (isset($update_data)) {
                                                                                                                                    echo $update_data['name'];
                                                                                                                                } ?>" id="name" required>

                                </div>
                                <div class="col-md-5 md-ms-4">
                                    <label class="form-label" for="prd"> &nbsp </label>
                                    <input class="btn btn-primary " type="submit" value="<?= isset($update_data) ? "Update" : "Submit" ?> ">
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
                                    <th>User id</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
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
                                                    <a class="btn btn-action bg-success text-white me-2" href="<?= base_url('master/Process/index/edit/') . $data['id'] ?>">
                                                        <i class="far fa-edit" aria-hidden="true"></i>
                                                    </a>


                                                    <a class="btn btn-action bg-danger text-white me-2" onclick="return confirm('Are you sure want to Delete.?');" href="<?= base_url('master/Process/index/delete/') . $data['id'] ?>">
                                                    <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>

                                            <td>
                                                <?= $data['name']; ?>
                                            </td>
                                            <td>
                                                <?= $data['uname']; ?>
                                            </td>
                                            <td>
                                                <?= $data['created_at']; ?>
                                            </td>
                                            <td>
                                                <?= $data['updated_at']; ?>
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