<table id="report_table" class="table table-bordered table-striped">
	<thead>
		<tr>
			<th>Sr.No</th>
			<th>Action</th>
			<th>Barcode</th>
			<th>Tag</th>
			<th>Item Name</th>
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
		$tg=0;
		$tl=0;
		$tn=0;
		$tam=0;
		$pcs=0;
		foreach ($data as $v) :
		$tg +=$v['gross_weight'];
		$tl +=$v['l_weight'];
		$tn +=$v['net_weight'];
		$tam +=$v['amt'];
		$pcs +=$v['piece'];
		?>
			<tr>
				<td><?= $i++ ?></td>
				<td>
					<a href="<?= site_url('/manufacturing/lot/index/' . $v['barcode']) ?>"  target="_blank" class="btn btn-secondary btn-sm">View</a>
				</td>
				<td><?=$v['barcode']?></td>
				<td><?= $v['tag'] ?></td>
				<td><?= $v['item_name'] ?></td>
				<td><?= $v['piece'] ?></td>
				<td><?= $v['gross_weight'] ?></td>
				<td><?= $v['l_weight'] ?></td>
				<td><?= $v['net_weight'] ?></td>
				<td><?= $v['amt'] ?></td>
				<td><?= ($v['status'] == 1) ? 'Yes' : 'No'; ?></td>
				<td><?= date('d-m-Y',strtotime($v['created_at'])) ?></td>
			</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<th>Sr.No</th>
			<th>Action</th>
			<th>Barcode</th>
			<th>Tag</th>
			<th>Item Name</th>
			<th><?=$pcs;?></th>
			<th><?=$tg;?></th>
			<th><?=$tl;?></th>
			<th><?=$tn;?></th>
			<th><?=$tam;?></th>
			<th>Status</th>
			<th>Created At</th>
		</tr>
	</tfoot>
</table>
