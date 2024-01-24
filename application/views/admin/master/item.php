<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> Item </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row"
                            action="<?= (isset($update)) ? base_url("master/item/index/update/{$update['id']}") : base_url('master/item/index/store') ?>"
                            method="post">

                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter item Name"
                                        value="<?= $update['name'] ?? null ?>" id="name" required>

                                </div>

                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Category: </label>
                                    <select class="form-select select2 " name="category_id" id="category_id">
                                        <option>Select category</option>
                                        <?php
                                        $category = $this->db->get('category')->result();
                                        foreach ($category as $value) {
                                            ?>
                                            <option value="<?= $value->id; ?>" <?php if (isset($update) && $value->id == $update['category_id']) {
                                                  echo 'selected';
                                              } ?>><?= $value->name; ?></option>
                                        <?php } ?>
                                    </select>
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
                                    <th>Category</th>
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
                                                        href="<?= base_url('master/item/edit/') . $data['id'] ?>">
                                                        <i class="far fa-edit" aria-hidden="true"></i>
                                                    </a>
                                                    <a class="btn btn-action bg-danger text-white me-2"  data-bs-toggle="tooltip" data-bs-placement="top"
                                                        data-bs-original-title="Delete"
                                                        onclick="return confirm('Are you sure want to Delete.?');"
                                                        href="<?= base_url('master/item/delete/') . $data['id'] ?>">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                            <td>
                                                <?= $data['name']; ?>
                                            </td>
                                            <td>
                                                <?= $data['category_name']; ?>
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