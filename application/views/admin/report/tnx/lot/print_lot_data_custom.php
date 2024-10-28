<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Sofia">
	<title><?= $title ?></title>
	<style>
		.div_tag_print {
			text-align: center;
		}

		table,
		thead,
		tbody,
		tr,
		th,
		td,
		tfoot {
			padding: 0;
			font-size:7.4px;
			margin: 0;
			/*font-weight: 900;*/
		}

		th {
			text-align: left;
		}

		th p {
			font-size:0.5px;
			font-weight: 900;
			margin: 0;
			padding: 0;
		}

		.qr_code {
			height: 50px;
		}

		body {
			margin: 0;
			padding: 0;
			font-family: "Arial", serif;
		}
	</style>
</head>

<body>
	<?php foreach ($LC as $LC) { ?>
		<?php
		if (empty($LC['term'])) {
			$term = 0;
		} else {
			$term = $LC['term'];
		}
		$tagString = $LC['tag'] . '/' . number_format((float)$LC['gross_weight'], 3, '.', '') . '/' . number_format((float)($LC['gross_weight'] - $LC['net_weight']), 3, '.', '') . '/' . number_format((float)$LC['net_weight'], 3, '.', '') . '/' . number_format((float)$LC['amt'], 0, '.', '') . '/' . '/' . $term.'/'.'/'.'/1';
		$this->db->select('RM.id AS rm_id, LCA.pcs AS qty,LCA.weight AS fweight');
		$this->db->where('lot_creation_id', $LC['id']);
		$this->db->join('ad', 'ad.id = LCA.ad_id', 'left');
		$this->db->join('raw_material RM', 'RM.id = ad.raw_material_id', 'left');
		$LCA = $this->db->get('lot_creation_ad LCA')->result_array();
		$sideArr = ['B' => 0, 'S' => 0, "BU" => 0,'BW'=>0,'SW'=>0,'BUW'=>0,'O'=>0,'OQ'=>0,'M'=>0,'MW'=>0];
		foreach ($LCA as $lca) {
			if ($lca['rm_id'] == 14 || $lca['rm_id'] == 50 || $lca['rm_id'] == 32) {
				$sideArr['B'] += $lca['qty'];
				$sideArr['BW'] += $lca['fweight'];
			} else if ($lca['rm_id'] == 15) {
				$sideArr['S'] += $lca['qty'];
				$sideArr['SW'] += $lca['fweight'];
				
			}else if ($lca['rm_id'] == 54) {
				$sideArr['M'] += $lca['qty'];
				$sideArr['MW'] += $lca['fweight'];
				
			} else if ($lca['rm_id'] == 33) {
				$sideArr['O'] += $lca['qty'];
				$sideArr['OQ'] += $lca['fweight'];
				
			} else if ($lca['rm_id'] == 31  || $lca['rm_id'] == 18) {
				$sideArr['S'] += $lca['qty'];
				$sideArr['SW'] += $lca['fweight'];
			}
		}
		?>
		<div class="div_tag_print" style="page-break-before: always;">
			<table style="margin-top:3px;">
				<thead>
				</thead>
				<tbody>
					<tr>
					    <?php if(!empty($LC['client_logo_image'])){ ?>
						<th rowspan="5">
							<img style="margin-left:25px;" src="<?= base_url('uploads/' . $LC['client_logo_image']) ?>" height="50" />
						</th>
						<th rowspan="5">
							<img class="qr_code" style="margin-left:25px;" src="<?=generate_qr_code($tagString)?>" /><br>
							<!-- <img src="https://chart.googleapis.com/chart?chs=30x30&cht=qr&chl=<?= $LC['tag'] ?>&choe=UTF-8" /><br> -->
							<!-- <img style="margin-left: 16px;" class="qr_code" src="<?= base_url() ?>" /><br> -->
							<!--<p style="margin-left: 16px;">t&c apply</p>-->
						</th>
						<?php }else{ ?>
						<th rowspan="5">
							<img class="qr_code" style="margin-left:128px;" src="<?=generate_qr_code($tagString)?>" /><br>
							<!-- <img src="https://chart.googleapis.com/chart?chs=30x30&cht=qr&chl=<?= $LC['tag'] ?>&choe=UTF-8" /><br> -->
							<!-- <img style="margin-left: 16px;" class="qr_code" src="<?= base_url() ?>" /><br> -->
							<!--<p style="margin-left: 16px;">t&c apply</p>-->
						</th>
						<?php } ?>
						<th><?= $LC['design_code'] ?>(<?= $LC['order_no'] ?>)</th>
						
						
					</tr>
					<tr>
						<th>G: <?= $LC['gross_weight'] ?> <span style="font-size:5.25px;"><?= ($sideArr['B'] > 0) && ($LC['gross_weight'] - $LC['net_weight']) >0 ? 'B:'.$sideArr['B'].'('.$sideArr['BW'].')' : '' ?></span></th>
						
					</tr>
					<tr>
						<th>S: <?= sprintf('%.3f', (($LC['gross_weight'] - $LC['net_weight']))) . "";
								?> <span style="font-size:5.25px;"><?= ($sideArr['S'] > 0) && ($LC['gross_weight'] - $LC['net_weight']) >0  ? 'S:' . $sideArr['S'].'('.$sideArr['SW'].')' : '' ?>&nbsp;</span>
						<?php if (!empty($LC['term'])) {
										echo "*Z " . $LC['term'];
									} else {
										echo "";
									} ?>&nbsp;</th>
						
					</tr>
					<tr>
						<th>N: <?= $LC['net_weight'] ?>
						<span style="font-size:5.25px;"><?= ($sideArr['M'] > 0) && ($LC['gross_weight'] - $LC['net_weight']) >0  ? 'M:' . $sideArr['M'].'('.$sideArr['MW'].')' : '' ?>&nbsp;</span>
						<span style="font-size:5.25px;"><?= ($sideArr['O'] > 0) && ($LC['gross_weight'] - $LC['net_weight']) >0 ? ' O:'.$sideArr['O'].'('.$sideArr['OQ'].')' : '' ?></span>
						
						</th>
						
					</tr>
					<tr>
						<th>RS:<?= ($LC['print_other_amt'] == 'yes') ? round($LC['amt'] * $LC['other_amt']) : round($LC['amt']);  ?>/<?= $LC['items_group_group_name'] ?></th>
					</tr>
				</tbody>
			</table>
		</div>
	<?php } ?>
</body>

</html>
