<div class="col-md-2">
	<label for="m_item">Select Item <span class="text-danger">*</span></label>
	<select class="form-control select2" id="m_item" name="m_item">
		<option value="0">Select Item</option>
		<?php foreach ($item as $i): ?>
			<option value="<?= $i['id'] ?>">
				<?= $i['name'] ?>
			</option>
		<?php endforeach; ?>
	</select>
</div>
<div class="col-md-1 d-none">
	<img src="" style="width: 80px; height: auto;"  class="design-code-image"/>
</div>
<div class="col-md-2">
	<label>Nt.Wt. <span class="text-danger">*</span></label>
	<input type="number" step="any" id="input-net-weight" name="m_net_weight" class="form-control" />
</div>
<div class="col-md-1">
	<label>L.Wt. <span class="text-danger">*</span></label><br>
	<input type="number" step="any" id="input-less-weight" name="m_less_weight" class="form-control" />
	<!-- <input type="checkbox" name="m_l_weight" id="isLWeight" /> -->
</div>
<div class="col-md-2">
	<label>Gr.Wt. <span class="text-danger">*</span></label>
	<input type="number" step="any" id="input-gross-weight" name="m_gross_weight" class="form-control" />
</div>
<div class="col-md-2">
	<label>Pcs<span class="text-danger">*</span></label>
	<input type="number" step="any" value="1" readonly id="input-pcs" name="pcs" class="form-control readonly" />
</div>

<div class="col-md-1">
	<label>Amt <span class="text-danger">*</span></label>
	<input type="number" step="any" id="input-amt" name="m_amt" class="form-control" />
</div>

<div class="col-md-2">
	<button class="btn mt-3 btn-primary" id="submit-btn" type="button">Submit</button>
</div>
