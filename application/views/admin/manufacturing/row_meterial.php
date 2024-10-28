<div class="table-responsive">
    <b>
	    <table class="table card-table table-vcenter text-center text-nowrap ">
		<thead class="thead-light">
			<th>Row Material</th>
			<th>Lot Wise RM</th>
			<th>Touch (%)</th>
			<th>Weight(Gm)</th>
			<th>Quantity</th>
		</thead>

		<tbody class="paste append-here">
		    <?php
		    
		    	$row_material = $this->db->get('row_material')->result();
		        $total = [
		            "touch" => 0,
		            "weight" => 0,
		            "quantity" => 0,
	            ];
	            
            if(!empty($data)) {
                
            
		    foreach($data as $key => $row){
		        
		    $total["touch"] += $row['touch'];
		    $total["weight"] += $row['weight'];
		    $total["quantity"] += $row['quantity'];
		    ?>
		    
			<tr class="sectiontocopy">
				<td>
					<select class="form-select select2 given-row_material_id" disabled readonly>
						<option value="">Select Metal</option>
						<?php
						foreach ($row_material as $value) { ?>
							<option value="<?= $value->id; ?>"   <?= $value->id == $row['row_material_id'] ? "selected" : "" ?>>
							  
								<?= $value->name; ?>
							</option>
						<?php } ?>
					</select>
				</td>
                <td>
					<select class="form-select select2" disabled readonly>
						<option value="">Select LW RM</option>
						<?php
						$lot_wise_rm = $this->db->get_where('lot_wise_rm',array('row_material_id' => $row['row_material_id']))->result();
						foreach ($lot_wise_rm as $value) { ?>
							<option value="<?= $value->id; ?>" <?= $value->id == $row['lot_wise_rm_id'] ? "selected" : "" ?>>
								<?= $value->id; ?> - <?= $value->code; ?> Weight :<?=$value->weight; ?>  Quantity : <?= $value->quantity; ?>
							</option>
						<?php } ?>
					</select>
				</td>
				<td>
					<input class="form-control given-touch" type="number" step="any" disabled readonly placeholder="Enter touch(%)" value="<?=$row['touch']?>">
				</td>
				<td>
					<input class="form-control given-weight" type="number" disabled readonly placeholder="Enter Weight" value="<?=$row['weight']?>">
				</td>
				<td>
					<input class="form-control given-quantity" type="number" value="<?=$row['quantity']?>" disabled readonly>
				</td>
			</tr>
			<?php } 
			} else {?>
			<tr>
			    <td colspan="4">
			        <h3>Given Row Material not found</h3>
			    </td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="2">
					<h3>Total :</h3>
				</td>
				<td>
					<div class="d-flex">
						<h4><span class='text-end ms-3 given-total-touch'><?=$total['touch']?></span></h4>
					</div>
				</td>
				<td>
					<div class="d-flex">
						<h4><span class='text-end ms-3 given-total-weight'><?=$total['weight']?></span></h4>
					</div>
				</td>
				<td>
					<div class="d-flex">
						<h4><span class='text-end ms-3 given-total-quantity'><?=$total['quantity']?></span></h4>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
	</b>
</div>