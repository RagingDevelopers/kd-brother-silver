<table id="example_table" class="table card-table table-vcenter text-nowrap datatable">
	<thead>
		<tr>
			<th>Sl No</th>
			<th>Action</th>
			<th>Party Name</th>
			<th>Date</th>
			<th>Payment Type</th>
			<th>Type</th>
			<th>mode</th>
			<th>Gross</th>
			<th>Purity</th>
			<th>W/B</th>
			<th>Fine</th>
			<th>Metal Type</th>
			<th>Rate</th>
			<th>Amount</th>
			<th>Remark</th>
		</tr>
	</thead>
	<?php $i = 1;  ?>
        <?php
        $gross_wt = 0;
        $less_wt = 0;
        $net_wt = 0;
        $fine = 0;
        $net_amount = 0;
        $rateTotal = 0;
        $wbTotal = 0;
        $purityTotal = 0;
        foreach ($data as $v) :
            $gross_wt += $v['grossTotal'];
            // $less_wt += $v['less_wt'];
            $purityTotal += $v['purityTotal'];
            $fine += $v['fineTotal'];
            $net_amount += $v['amountTotal'];
            $rateTotal += $v['rateTotal'];
            $wbTotal += $v['wbTotal'];
        ?>
            <tr>
                <td>
	                <?= $v['sno'] ?>
                </td>
                <td>
                    <?= $v['action']?>
                </td>
                <td><?= $v['party'] ?></td>
                <td><?= $v['date'] ?></td>
                <td><?= $v['payment_type']; ?></td>
                <td><?= $v['type']; ?></td>
                <td><?= $v['mode'] ?></td>
                <td><?= $v['gross'] ?></td>
                <td><?= $v['purity'] ?></td>
                <td><?= $v['wb'] ?></td>
                <td><?= $v['fine'] ?></td>
                <td><?= $v['metal_type']; ?></td>
                <td><?= $v['rate']; ?></td>
                <td><?= $v['amount']; ?></td>
                <td><?= $v['remark']; ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <thead>
            <tr>
                <th><b><h3 class="totalColor">Total</h3></b></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><b><h3 class="totalColor"><?= $gross_wt ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $purityTotal ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $wbTotal ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $fine ?></h3></b></th>
                <th></th>
                <th><b><h3 class="totalColor"><?= $rateTotal ?></h3></b></th>
                <th><b><h3 class="totalColor"><?= $net_amount ?></h3></b></th>
                <th></th>
            </tr>
        </thead>
    </tfoot>
</table>
<?php

include './application/views/admin/assets.php';

?>