<table id="report_table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Barcode</th>
			<th>Tag</th>
			<th>Item</th>
			<th>PCS</th>
			<th>Gr. Wt.</th>
			<th>L. Wt.</th>
			<th>Nt. Wt.</th>
			<th>Amount</th>
			<th>Status</th>
			<th>Created At</th>
		</tr>
	</thead>
	<tbody>
		<?php $i = 1; ?>
		<?php 
		$pcs=0;
		$tg=0;
		$tl=0;
		$tn=0;
		$tam=0;
		foreach ($data as $v) : 
		$pcs +=$v['piece'];
		$tg +=$v['gross_weight'];
		$tl +=$v['l_weight'];
		$tn +=$v['net_weight'];
		$tam +=$v['amt'];
		?>
			<tr class="<?= ($v['status'] == 1) ? 'text-success' : 'text-danger' ?>">
				<td><?= $i++ ?></td>
				<td>
					<a href="<?= site_url('manufacturing/lot/index/' . $v['barcode']) ?>" target="_blank">
						<?= $v['barcode'] ?>
					</a>
				</td>
				<td><?= $v['tag'] ?></td>
				<td><?= $v['item_name'] ?></td>
				<td><?= $v['piece'] ?></td>
				<td><?= $v['gross_weight'] ?></td>
				<td><?= $v['l_weight'] ?></td>
				<td><?= $v['net_weight'] ?></td>
				<td><?= $v['amt'] ?></td>
				<td><?= ($v['status'] == 1) ? 'Yes' : 'No'; ?></td>
				<td><?= date('d-m-Y', strtotime($v['created_at'])) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Barcode</th>
			<th>Tag</th>
			<th>Item</th>
			<th><?=$pcs;?></th>
			<th><?=$tg;?></th>
			<th><?=$tl;?></th>
			<th><?=$tn;?></th>
			<th><?=$tam;?></th>
			<th>Status</th>
			<th>Created At</th>
		</tr>
	</thead>
</table>
