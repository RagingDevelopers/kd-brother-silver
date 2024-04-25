
<div class="row mt-3">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
    			<div class="row">
    			    <div class="col-md-12">
    				    <h2 class="box-title"><?php if (isset($row_data)) {echo 'Edit';} else {echo 'Add';} ?> Sequence</h2>
    			    </div>
    			    <div class="col-md-12">
    					<div class="alert alert-important alert-warning alert-dismissible" role="alert" style="background-color: #a50000; color: white;">
                            <h4><i class="icon fa fa-circle"></i> Message:</h4>
                            <div class="d-flex">
                                <div>
                                  <i class="fa fa-exclamation-triangle me-2" aria-hidden="true" style="font-size: 24px;"></i>
                                </div>
                                <div style="font-size: 16px;">
                                  Any changes will be reflected only in new entries.
                                </div>
                            </div>
                            <a class="btn-close" style="color: white;" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
    			    </div>
    			</div>
			</div>
			<div class="card-body">
				<form role="form" action="<?php if (!isset($row_data)) {echo site_url('setting/sequence/sequence/create');} else {echo site_url('setting/sequence/sequence/update/' . $row_data['id']);} ?>" method="post" enctype="multipart/form-data">
					<!--<input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">-->
					<div class="col-md-12 p-2">
						<label for="form-label" class="form-label">Prefix</label>
						<input name="prefix" type="text" class="form-control" value="<?php if (isset($row_data)) {echo $row_data['prefix'];} ?>" placeholder="Enter prefix" />
					</div>
					<div class="col-md-12 p-2">
						<label class="form-label" for="title">Start With*</label>
						<input name="number" type="number" class="form-control" value="<?php if (isset($row_data)) {echo $row_data['number'];} else {echo '1';} ?>" placeholder="Initial Number" <?= (isset($row_data) && $row_data['model'] !== 'filing_remark') ? "disabled" : "required"; ?> />
					</div>
					<div class="col-md-12 p-2">
						<label class="form-label" for="suffix">Suffix</label>
						<input name="suffix" type="text" class="form-control" value="<?php if (isset($row_data)) {echo $row_data['suffix'];} ?>" placeholder="Enter suffix" />
					</div>
					<div class="col-md-12 p-2">
						<label class="form-label" for="title">Padding</label>
						<input name="padding" type="number" class="form-control" value="<?php if (isset($row_data)) {echo $row_data['padding'];} else {echo '0';} ?>" placeholder="Enter padding" required />
					</div>
					<div class="col-md-12 p-2">
						<label class="form-label" for="title">Model*</label>
						<select name="model" class="select-model form-control">
							<option value="0">--SELECT MODEL--</option>
							<?php foreach ($models as $key => $value) : ?>
								<option value="<?= $key; ?>" <?php if (isset($row_data) && $row_data['model'] == $key) {echo 'selected';} ?>><?= $value; ?></option>
							<?php endforeach ?>
						</select>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<h3 class="card-title">Sequence Report</h3>
			</div>
			<div class="card-body">
				<div class="table-responsive">
			        <cite>
				        <b>
        					<table id="example1" class="table table-bordered table-striped">
        						<thead>
        							<tr>
        
        								<th>#</th>
        								<th>Action</th>
        								<th>Prefix</th>
        								<th>Suffix</th>
        								<th>Padding</th>
        								<th>Pattern</th>
        								<th>Model</th>
        								<th>Created At</th>
        
        							</tr>
        						</thead>
        						<tbody>
        							<?php
        							$i = 1;
        							foreach ($data as $row) :
        							?>
        								<tr>
        
        									<td><?php echo $i++; ?></td>
        									<td>
        										<?php if (!isRestricted(privilege['sequence_edit'])) { ?>
        											<a href="<?php echo site_url('setting/sequence/sequence/edit/' . $row['id']); ?>"><i class="fas fa-pencil-alt text-yellow"></i></a>&nbsp;&nbsp;
        										<?php }
        										if (!isRestricted(privilege['sequence_delete'])) { ?>
        											<a href="<?php echo site_url('setting/sequence/sequence/delete/' . $row['id']); ?>"><i class="fa fa-trash text-red"></i></a>
        										<?php } ?>
        									</td>
        									<td><?= $row['prefix']; ?></td>
        									<td><?= $row['suffix']; ?></td>
        									<td><?= $row['padding']; ?></td>
        									<td><?= $row['sequence']; ?></td>
        									<td><?= $row['model']; ?></td>
        									<td><?= date('d-m-Y', strtotime($row['created_at'])); ?></td>
        								</tr>
        							<?php endforeach; ?>
        						</tbody>
        					</table>
				        </b>
			        </cite>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$(document).on('submit', 'form', function() {
			if ($('.select-model').val() == 0) {
				alert('please select model');
				return false;
			} else {
				return true;
			}
		});
	});
</script>