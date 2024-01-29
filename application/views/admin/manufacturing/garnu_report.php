<div class="row">
    <div class="col-sm-12">
        <div class="card mt-3">
            <div class="card-header">
                <div class="card-status-top bg-blue"></div>
                <div class="modal modal-blur fade" id="ReceivedModel" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Received </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body modal-body_2html">

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary">Receipt</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-11">
                    <h1 class="card-title"><b> Garnu Report </b></h1>
                </div>
                <div class="col-sm-1 ms-5 ps-5">
                    <a class="btn btn-action bg-primary text-white"
                        href="<?= base_url('manufacturing/garnu/index/add') ?>">
                        <i class="far fa-plus card-title" aria-hidden="true">
                        </i>
                    </a>
                </div>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="table-responsive">
                    <div class="col-md-12 mb-5 ">
                        <div class="row">
                            <div class="col-sm-2">
                                <label>From date:</label> <br>
                                <input type="date" id="fromdate" name="fromdddate" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <label>To date:</label> <br>
                                <input type="date" id="todate" name="todate" class="form-control">
                            </div>

                        </div>
                        <div class="mt-3">
                            <table id="example_table" class="table table-vcenter card-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Serial No </th>
                                        <th scope="col">Action</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Garnu Weight</th>
                                        <th scope="col">Touch</th>
                                        <th scope="col">Silver</th>
                                        <th scope="col">Copper</th>
                                        <th scope="col">Creation Date</th>
                                        <th scope="col">Received</th>
                                        <th scope="col">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    if (count($data)) {
                                        foreach ($data as $data) {
                                            ?>
                                            <?php if ($data['recieved'] == 'NO') { ?>
                                                <tr class="text-danger">
                                                    <td scope="row">
                                                        <?= $i++ ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a class="btn btn-action bg-success text-white me-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-original-title="Edit"
                                                                href="<?= base_url('manufacturing/garnu/edit/') . $data['id'] ?>">
                                                                <i class="far fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                            <buttton data-receiptid="$data['id']"
                                                                class="btn btn-action bg-danger text-white me-2 Received"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-original-title="Receive"><i class="fa-solid fa-receipt"></i>
                                                            </buttton>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <?= $data['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['garnu_weight']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['touch']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['silver']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['copper']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['creation_date']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['recieved']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['created_at']; ?>
                                                    </td>
                                                </tr>
                                            <?php } else { ?>
                                                <tr class="text-green">
                                                    <td scope="row">
                                                        <?= $i++ ?>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            <a class="btn btn-action bg-success text-white me-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-original-title="Edit"
                                                                href="<?= base_url('manufacturing/garnu/edit/') . $data['id'] ?>">
                                                                <i class="far fa-edit" aria-hidden="true"></i>
                                                            </a>
                                                            <span class="btn btn-action bg-green text-white me-2"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                data-bs-original-title="Receive"><i
                                                                    class="fa-solid fa-receipt"></i></span>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <?= $data['name']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['garnu_weight']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['touch']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['silver']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['copper']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['creation_date']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['recieved']; ?>
                                                    </td>
                                                    <td>
                                                        <?= $data['created_at']; ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
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
<script class="javascript">
    $(document).ready(function () {
        $(document).on('click', '.Received', function () {
            $("#ReceivedModel").modal('show');
            var id = $(this).data('receiveid');
            $('.modal-body_2html').html("");

        });
    });
</script>