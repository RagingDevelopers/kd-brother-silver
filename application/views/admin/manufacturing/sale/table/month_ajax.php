<table id="example_table" class="table card-table table-vcenter text-nowrap datatable">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Year</th>
            <th>Gross Weight</th>
            <th>Less Weight</th>
            <th>Net Weight</th>
            <th>Touch</th>
            <th>Create At</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;  ?>
        <?php
        $gross_wt = 0;
        $less_wt = 0;
        $net_wt = 0;
        $touch = 0;
        foreach ($data as $v) :
            $gross_wt += $v['gross_wt'];
            $less_wt += $v['less_wt'];
            $net_wt += $v['net_wt'];
            $touch += $v['touch'];
        ?>
            <tr>
                <td>
	                <?= $i++ ?>
                </td>
                <td><a href="#" class="text-primary me-2 date"><?= $v['year']; ?></a></td>
                <td><?= sprintf('%0.2f', $v['gross_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['less_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['net_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['touch']) ?></td>
                <td><?= date("d-m-Y", strtotime($v['createdAt'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <thead>
            <tr>
                <th><b>Total</b></th>
                <th></th>
                <th><b><?= $gross_wt ?></b></th>
                <th><b><?= $less_wt ?></b></th>
                <th><b><?= $net_wt ?></b></th>
                <th><b><?= $touch ?></b></th>
                <th></th>
            </tr>
        </thead>
    </tfoot>
</table>

<?php

include './application/views/admin/assets.php';

?>
