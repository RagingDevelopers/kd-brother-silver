<div class="row mt-3">
	<div class="col-md-12">
	    <cite>
	        <b>
    		<table class="table table-bordered">
    			<thead>
    				<tr>
    					<th rowspan="2">Item Name</th>
    					<th colspan="5">Opening</th>
    					<th colspan="5">Purchase</th>
    					<th colspan="5">Purchase Return</th>
    					<th colspan="5">Sales</th>
    					<th colspan="5">Sales Return</th>
    					<th colspan="5">Lot Creation</th>
    					<th colspan="5">Closing</th>
    				</tr>
    				<tr>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    					<th>PCS</th>
    					<th>Gr.Wt.</th>
    					<th>Nt.Wt.</th>
    					<th>Fn.Wt.</th>
    					<th>O.Amt</th>
    				</tr>
    			</thead>
    			<tbody>
    				<?php foreach ($data as $v) { ?>
    					<?php //console_log($v); 
    					?>
    					<tr>
    						<td>
    							<?= $v['item_name'] ?>
    						</td>
    						<td><?= empty($v['o_pcs']) ? 0 : number_format((float)$v['o_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['o_gross_weight']) ? 0 : number_format((float)$v['o_gross_weight'], 2, '.', ''); ?></td>
    					
    						<td><?= empty($v['o_net_weight']) ? 0 : number_format((float)$v['o_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['o_fine_weight']) ? 0 : number_format((float)$v['o_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['o_other_amt']) ? 0 : number_format((float)$v['o_other_amt'], 2, '.', ''); ?></td>
    						<td><?= empty($v['p_pcs']) ? 0 : number_format((float)$v['p_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['p_gross_weight']) ? 0 : number_format((float)$v['p_gross_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['p_net_weight']) ? 0 : number_format((float)$v['p_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['p_fine_weight']) ? 0 : number_format((float)$v['p_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['p_other_amt']) ? 0 : number_format((float)$v['p_other_amt'], 2, '.', ''); ?></td>
    						<td><?= empty($v['pr_pcs']) ? 0 : number_format((float)$v['pr_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['pr_gross_weight']) ? 0 : number_format((float)$v['pr_gross_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['pr_net_weight']) ? 0 : number_format((float)$v['pr_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['pr_fine_weight']) ? 0 : number_format((float)$v['pr_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['pr_other_amt']) ? 0 : number_format((float)$v['pr_other_amt'], 2, '.', ''); ?></td>
    						<td><?= empty($v['s_pcs']) ? 0 : number_format((float)$v['s_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['s_gross_weight']) ? 0 : number_format((float)$v['s_gross_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['s_net_weight']) ? 0 : number_format((float)$v['s_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['s_fine_weight']) ? 0 : number_format((float)$v['s_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['s_other_amt']) ? 0 : number_format((float)$v['s_other_amt'], 2, '.', ''); ?></td>
    						<td><?= empty($v['sr_pcs']) ? 0 : number_format((float)$v['sr_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['sr_gross_weight']) ? 0 : number_format((float)$v['sr_gross_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['sr_net_weight']) ? 0 : number_format((float)$v['sr_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['sr_fine_weight']) ? 0 : number_format((float)$v['sr_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['sr_other_amt']) ? 0 : number_format((float)$v['sr_other_amt'], 2, '.', ''); ?></td>
    						<td><?= empty($v['lr_pcs']) ? 0 : number_format((float)$v['lr_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['lr_gross_weight']) ? 0 : number_format((float)$v['lr_gross_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['lr_net_weight']) ? 0 : number_format((float)$v['lr_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['lr_fine_weight']) ? 0 : number_format((float)$v['lr_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['lr_other_amt']) ? 0 : number_format((float)$v['lr_other_amt'], 2, '.', ''); ?></td>
    						
    						<td><?= empty($v['c_pcs']) ? 0 : number_format((float)$v['c_pcs'], 2, '.', ''); ?></td>
    						<td><?= empty($v['c_gross_weight']) ? 0 : number_format((float)$v['c_gross_weight'], 2, '.', ''); ?></td>
    						
    						<td><?= empty($v['c_net_weight']) ? 0 : number_format((float)$v['c_net_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['c_fine_weight']) ? 0 : number_format((float)$v['c_fine_weight'], 2, '.', ''); ?></td>
    						<td><?= empty($v['c_other_amt']) ? 0 : number_format((float)$v['c_other_amt'], 2, '.', ''); ?></td>
    					</tr>
    				<?php } ?>
    			</tbody>
    		</table>
    		</b>
		</cite>
	</div>
</div>