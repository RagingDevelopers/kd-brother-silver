<table id="report_table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Item</th>
			<th>Barcode</th>
			<th>Tag</th>
			<th>PCS</th>
			<th>Gr. Wt.</th>
			<th>L. Wt.</th>
			<th>Nt. Wt.</th>
			<th>Amount</th>
			<th>Created At</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		<?php foreach ($data as $v) : ?>
			<tr>
				<td><?= $i++ ?></td>
				<td class="item" style="color: #0062cc;" data-item_id="<?=$v['item_id']?>"><?= $v['item_name'] ?></td>
				<td><?= $v['barcode'] ?></td>
				<td><?= $v['tag'] ?></td>
				<td><?= $v['piece'] ?></td>
				<td><?= $v['gross_weight'] ?></td>
				<td><?= $v['l_weight'] ?></td>
				<td><?= $v['net_weight'] ?></td>
				<td><?= $v['amt'] ?></td>
				<td><?= date('d-m-Y',strtotime($v['created_at'])) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
