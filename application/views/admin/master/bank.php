<div class="row">
    <div class="col-sm-12">
        <div class="card  card-link card-link-pop">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <h1 class="card-title"><b> bank </b></h1>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="col-md-12 mb-5 ">
                    <div class="row ms-1">
                        <form class="row" action="<?= (isset($update)) ? base_url("master/bank/index/update/{$update['id']}") : base_url('master/bank/index/store') ?>" method="post">
                            <div class="row">
                                <div class="col-sm-3">
                                    <label class="form-label" for="prd"> Name: </label>
                                    <input class="form-control" type="text" name="name" placeholder="Enter bank Name" value="<?= $update['name'] ?? null ?>" id="name">

                                </div>
                                <div class="col-md-5 md-ms-4">
                                    <label class="form-label" for="prd"> &nbsp </label>
                                    <input class="btn btn-primary submit-btn " type="submit" value="<?= isset($update) ? "Update" : "Submit" ?> ">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-12">
                    <?php if (count($data)) { ?>
                        <div class="mt-2 ">
                            <div class=" mt-5 markdown">
                                <table id="example_table" class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Serial No </th>
                                            <th>Action</th>
                                            <th>Name</th>
                                            <th>Created At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1;

                                        foreach ($data as $data) {
                                        ?>
                                            <tr>
                                                <td>
                                                    <?= $i++ ?>
                                                </td>
                                                <td>
                                                    <div>
                                                        <a class="btn  btn-success btn-icon rounded-circle text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Edit bank" href="<?= base_url('master/bank/edit/') . $data['id'] ?>">
                                                            <i class="far fa-edit" aria-hidden="true"></i>
                                                        </a>

                                                        <a class="btn btn-danger btn-icon rounded-circle text-white me-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete bank" onclick="return confirm('Are you sure want to Delete.?');" href="<?= base_url('master/bank/delete/') . $data['id'] ?>">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?= $data['name']; ?>
                                                </td>
                                                <td>
                                                    <?= $data['created_at']; ?>
                                                </td>
                                            </tr>
                                        <?php }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } else {
                        $this->load->view('utils/not-found');
                    } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script  class="javascript">


    // $(document).ready(function() {
        // $('form').on("submit", function(e) {
        //     e.preventDefault();
        //     console.log("clicked")
        //     $(this).find('.submit-btn').attr('disabled', true).wrapInner(`<div class="spinner-border"></div>`)
        //     $(this).submit();
        // })
    // });
</script>