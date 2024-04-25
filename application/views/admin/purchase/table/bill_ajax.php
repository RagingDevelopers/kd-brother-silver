<table id="example_table" class="table card-table table-vcenter text-nowrap datatable">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Actions</th>
            <th>Code</th>
            <th>Customer</th>
            <th>City</th>
            <th>Date</th>
            <th>Gross Weight</th>
            <th>Less Weight</th>
            <th>Net Weight</th>
            <th>Fine Silver</th>
            <th>Net Amount</th>
            <th>Remark</th>
            <th>Create At</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;  ?>
        <?php
        $gross_wt = 0;
        $less_wt = 0;
        $net_wt = 0;
        $fine = 0;
        $net_amount = 0;
        foreach ($data as $v) :
            $gross_wt += $v['gross_wt'];
            $less_wt += $v['less_wt'];
            $net_wt += $v['net_wt'];
            $fine += $v['fine'];
            $net_amount += $v['amount'];
        ?>
            <tr>
                <td>
                    <?= $i++ ?>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="<?= base_url("$url/bill/" . $v['purchase_id']); ?>" target="_blank" class="btn btn-action bg-primary text-white me-2"><i class="fa fa-print" aria-hidden="true"></i></a>
                        <a href="<?= base_url("$url/edit/" . $v['purchase_id']); ?>" class="btn btn-action bg-warning text-white me-2"><i class="fas fa-edit"></i></a>
                        <a href="<?= base_url("$url/delete/" . $v['purchase_id']); ?>" onclick="return confirm('Delete This Record?');" class="btn btn-action bg-danger text-white me-2"><i class="fas fa-trash"></i></a>
                    </div>
                </td>
                <td><?= $v['sequence_code'] ?></td>
                <td><?= $v['customer'] ?></td>
                <td><?= $v['city']; ?></td>
                <td><?= $v['date']; ?></td>
                <td><?= sprintf('%0.2f', $v['gross_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['less_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['net_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['fine']) ?></td>
                <td><?= sprintf('%0.2f', $v['amount']) ?></td>
                <td><?= $v['remark']; ?></td>
                <td><?= date("d-m-Y", strtotime($v['createdAt'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <thead>
            <tr>
                <th><b>Total</b></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><b><?= $gross_wt ?></b></th>
                <th><b><?= $less_wt ?></b></th>
                <th><b><?= $net_wt ?></b></th>
                <th><b><?= $fine ?></b></th>
                <th><b><?= $net_amount ?></b></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
    </tfoot>
</table>

<?php

include './application/views/admin/assets.php';

?>
