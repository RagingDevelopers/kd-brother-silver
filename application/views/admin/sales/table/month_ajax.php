<table id="example_table" class="table card-table table-vcenter text-nowrap datatable">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th>Year</th>
            <th>Pre Touch</th>
            <th>Gross Weight</th>
            <th>Less Weight</th>
            <th>Net Weight</th>
            <th>Fine Silver</th>
            <th>Net Amount</th>
            <th>Create At</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1;  ?>
        <?php
        $gross_wt = 0;
        $pre_touch = 0;
        $less_wt = 0;
        $net_wt = 0;
        $fine = 0;
        $net_amount = 0;
        foreach ($data as $v) :
            $gross_wt += $v['gross_wt'];
            $pre_touch += $v['pre_touch'];
            $less_wt += $v['less_wt'];
            $net_wt += $v['net_wt'];
            $fine += $v['fine'];
            $net_amount += $v['amount'];
        ?>
            <tr>
                <td>
	                <?= $i++ ?>
                </td>
                <td><a href="#" class="text-primary me-2 date"><?= $v['year']; ?></a></td>
                <td><?= sprintf('%0.2f', $v['pre_touch']) ?></td>
                <td><?= sprintf('%0.2f', $v['gross_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['less_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['net_wt']) ?></td>
                <td><?= sprintf('%0.2f', $v['fine']) ?></td>
                <td><?= sprintf('%0.2f', $v['amount']) ?></td>
                <td><?= date("d-m-Y", strtotime($v['createdAt'])); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <thead>
            <tr>
                <th><b><h3 class="totalColor">Total</h3></b></th>
                <th></th>
                <th><b><h3 class="totalColor"><?= $pre_touch ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $gross_wt ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $less_wt ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $net_wt ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $fine ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $net_amount ?></h3></b></th>
                <th></th>
            </tr>
        </thead>
    </tfoot>
</table>

<?php

include './application/views/admin/assets.php';

?>