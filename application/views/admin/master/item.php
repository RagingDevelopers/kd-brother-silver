<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-header">
				<div class="card-status-top bg-blue"></div>
				<h1 class="card-title"><b>Item</b></h1>
			</div>
			<div class="card-body border-bottom py-3">

				<!-- Form -->
				<div class="col-md-12 mb-3">
					<div class="row ms-1">
						<form class="row g-2 align-items-end" action="<?= (isset($update)) ? base_url("master/item/index/update/{$update['id']}") : base_url('master/item/index/store') ?>" method="post">

							<div class="col-sm-3">
								<label class="form-label">Name</label>
								<input class="form-control" type="text" name="name" placeholder="Enter item name" value="<?= $update['name'] ?? '' ?>" required>
							</div>

							<div class="col-sm-2">
								<label class="form-label">Category</label>
								<select class="form-select select2" name="category_id" required>
									<option value="">Select category</option>
									<?php
									$category = $this->db->get('category')->result();
									foreach ($category as $cat): ?>
										<option value="<?= $cat->id ?>" <?= (isset($update) && $update['category_id'] == $cat->id) ? 'selected' : '' ?>>
											<?= $cat->name ?>
										</option>
									<?php endforeach; ?>
								</select>
							</div>

							<div class="col-sm-2">
								<label class="form-label">Type</label>
								<select class="form-select" name="type" required>
									<option value="">Select type</option>
									<option value="raw_material"  <?= (isset($update) && $update['type'] == 'raw_material')  ? 'selected' : '' ?>>Raw Material</option>
									<option value="metal_type"   <?= (isset($update) && $update['type'] == 'metal_type')   ? 'selected' : '' ?>>Metal Type</option>
									<option value="finish_goods" <?= (isset($update) && $update['type'] == 'finish_goods') ? 'selected' : '' ?>>Finish Goods</option>
								</select>
							</div>

							<div class="col-sm-2">
								<label class="form-label">Opening Stock</label>
								<input class="form-control" type="number" step="0.001" min="0" name="opening_stock" placeholder="0.000" value="<?= $update['opening_stock'] ?? '' ?>" required>
							</div>

							<div class="col-sm-2">
								<button class="btn btn-primary w-100" type="submit">
									<i class="fa-solid fa-<?= isset($update) ? 'pen' : 'plus' ?> me-1"></i>
									<?= isset($update) ? 'Update' : 'Add Item' ?>
								</button>
							</div>

						</form>
					</div>
				</div>

				<!-- Type Filter Tabs -->
				<div class="mt-3 mb-2 ms-1">
					<div class="d-flex flex-wrap gap-2" id="typeTabs">
						<button class="btn btn-primary type-tab active" data-type="">
							<i class="fa-solid fa-list me-1"></i> All
						</button>
						<button class="btn btn-outline-secondary type-tab" data-type="raw_material">
							<i class="fa-solid fa-cubes me-1"></i> Raw Material
						</button>
						<button class="btn btn-outline-secondary type-tab" data-type="metal_type">
							<i class="fa-solid fa-ring me-1"></i> Metal Type
						</button>
						<button class="btn btn-outline-secondary type-tab" data-type="finish_goods">
							<i class="fa-solid fa-gem me-1"></i> Finish Goods
						</button>
					</div>
				</div>

				<!-- DataTable -->
				<div class="mt-2">
					<table id="item_table" class="table table-vcenter card-table">
						<thead>
							<tr>
								<th style="width:60px">Sr.</th>
								<th style="width:90px">Action</th>
								<th>Name</th>
								<th>Category</th>
								<th>Type</th>
								<th>Opening Stock</th>
								<th>Created At</th>
							</tr>
						</thead>
					</table>
				</div>

			</div>
		</div>
	</div>
</div>

<script>
$(document).ready(function () {

	// Select2
	$('.select2').select2({ placeholder: "-- Select --", allowClear: true });

	// Type badge map
	var typeBadge = {
		raw_material:  '<span class="badge bg-blue-lt text-blue fw-bold">Raw Material</span>',
		metal_type:    '<span class="badge bg-green-lt text-green fw-bold">Metal Type</span>',
		finish_goods:  '<span class="badge bg-orange-lt text-orange fw-bold">Finish Goods</span>'
	};

	// DataTable
	var table = $('#item_table').DataTable({
		ajax: {
			url: '<?= base_url("master/item/index/list") ?>',
			type: 'GET',
			dataSrc: 'data'
		},
		columns: [
			{
				data: null,
				orderable: false,
				searchable: false,
				render: function (data, type, row, meta) {
					return meta.row + 1;
				}
			},
			{
				data: 'id',
				orderable: false,
				searchable: false,
				render: function (data) {
					return '<a class="btn btn-success text-white" href="<?= base_url('master/item/edit/') ?>' + data + '" title="Edit">' +
						'<i class="far fa-edit"></i>' +
					'</a>';
				}
			},
			{ data: 'name' },
			{ data: 'category_name' },
			{
				data: 'type',
				render: function (data, type) {
					if (type === 'display') {
						return typeBadge[data] || data;
					}
					return data;
				}
			},
			{
				data: 'opening_stock',
				render: function (data, type) {
					if (type === 'display') {
						return parseFloat(data).toFixed(3);
					}
					return data;
				}
			},
			{ data: 'created_at' }
		],
		order: [],
		pageLength: 10,
		language: {
			search: '',
			searchPlaceholder: 'Search items...',
			emptyTable: 'No items found'
		},
		responsive: true
	});

	// Tab filter (searches on the raw type value, not the badge HTML)
	$('#typeTabs .type-tab').on('click', function () {
		$('#typeTabs .type-tab')
			.removeClass('btn-primary btn-blue')
			.addClass('btn-outline-secondary');
		$(this).removeClass('btn-outline-secondary').addClass('btn-primary');

		var type = $(this).data('type');
		// column(4) = Type, search on filter value (raw data, not display)
		table.column(4).search(type).draw();
	});

});
</script>
