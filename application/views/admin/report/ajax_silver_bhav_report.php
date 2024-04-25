<table id="report_table" class="table table-bordered table-striped">
    <thead>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2">Date</th>
            <th rowspan="2">Type</th>
            <th class="text-center" colspan="4">Sales Silver</th>
            <th class="text-center" colspan="4">Purchase Silver</th>
        </tr>
        <tr>
            <th>Party</th>
            <th>Fine</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Party</th>
            <th>Fine</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i = 1;
        $totalSalesFine = 0;
        $totalSalesAmount = 0;
        $totalPurchaseAmount = 0;
        $totalPurchaseFine = 0;
        foreach ($data as $d) { ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= $d['date'] ?></td>
                <td><?php
                if(!empty($d['type']) && $d['type'] == 'ratecutfine'){
                    echo $d['payment_type'] == 'CREDIT' ? "<span>Rate Cute - Fine </span>" : "<span>Rate Cute - Fine </span>";
                }else if(!empty($d['type']) && $d['type'] == 'ratecutrs'){
                    echo $d['payment_type'] == 'CREDIT' ? "<span>Rate Cute - Rs </span>" : "<span>Rate Cute - Rs </span>";
                }
                ?></td>
                <?php if ($d['payment_type'] == 'CREDIT') {
                    $totalSalesFine += $d['fine'];
                    $totalSalesAmount += $d['amount'];
                ?>
                    <td><?= $d['customer_name'] ?></td>
                    <td class="text-success"><?= $d['fine'] ?></td>
                    <td class="text-success"><?= $d['rate'] ?></td>
                    <td class="text-success"><?= $d['amount'] ?></td>
                    <td> --- </td>
                    <td> --- </td>
                    <td> --- </td>
                    <td> --- </td>
                <?php } else if ($d['payment_type'] == 'DEBIT') {
                    $totalPurchaseFine += $d['fine'];
                    $totalPurchaseAmount += $d['amount']; ?>
                    <td> --- </td>
                    <td> --- </td>
                    <td> --- </td>
                    <td> --- </td>
                    <td><?= $d['customer_name'] ?></td>
                    <td class="text-danger"><?= $d['fine'] ?></td>
                    <td class="text-danger"><?= $d['rate'] ?></td>
                    <td class="text-danger"><?= $d['amount'] ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <th rowspan="2">#</th>
            <th rowspan="2"></th>
            <th rowspan="2"></th>
            <th class="text-center" colspan="4"></th>
            <th class="text-center" colspan="4"></th>
        </tr>
        <tr>
            <th></th>
            <th><?= $totalSalesFine; ?></th>
            <th></th>
            <th><?= $totalSalesAmount; ?></th>
            <th></th>
            <th><?= $totalPurchaseFine; ?></th>
            <th></th>
            <th><?= $totalPurchaseAmount; ?></th>
        </tr>
        <tr>
            <th class="text-center text-primary" colspan="6"><span class="blinking-text">Sales Fine - Purchase Fine => <?= $totalSalesFine - $totalPurchaseFine ?></span></th>
            <th class="text-center text-primary" colspan="5"><span class="blinking-text">Sales Amount - Purchase Amount => <?= $totalSalesAmount - $totalPurchaseAmount ?></span></th>
        </tr>
    </tfoot>
</table>