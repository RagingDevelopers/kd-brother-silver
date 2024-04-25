<!-- Libs JS -->
<?php $time = time() ?>

<script src="<?= base_url(); ?>assets/dist/js/flatpickr.min.js"></script>

<!-- DataTables  & Plugins -->
<script src="<?= base_url("assets/") ?>plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/jszip/jszip.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/pdfmake/pdfmake.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/pdfmake/vfs_fonts.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="<?= base_url("assets/") ?>plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!--<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.min.js"></script>-->

<script src="<?= base_url("assets/") ?>plugins/datatables/dataTables.checkboxes.min.js"></script>
<script src="<?= base_url("assets") ?>/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
<script src="<?= base_url("assets") ?>/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
<script src="<?= base_url("assets") ?>/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
<script src="<?= base_url("assets") ?>/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
<script src="<?= base_url("assets") ?>/dist/js/tabler.min.js?1684106062" defer></script>
<script src="<?= base_url("assets") ?>/dist/js/demo.min.js?1684106062" defer></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= base_url("assets") ?>/dist/libs/block-ui/block-ui.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<script src="<?= base_url("assets") ?>/dist/js/common.js?v=<?= $time ?>"></script>
<!-- <script src="<?= base_url("assets") ?>/dist/js/process.js?v=<?= $time ?>"></script> -->



<script>
	setTimeout(() => {
		const perPageJs = $('.javascript').html();
		$('.appendHere').append(perPageJs);
		$('.javascript').html("");

		// $(' .select2').select2({
		// 	placeholder: "-- Select --",
		// 	allowClear: true,
		// });
	}, 100)
</script>
<script class='appendHere'>

</script>

<script>
	$(function() {
		$("#example_table").DataTable({
				"responsive": false,
				"lengthChange": true,
				"autoWidth": false,
				"buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
			})
			.buttons().container().appendTo('#example_table_wrapper .col-md-6:eq(0)');
		$("#example_tablepataprint").DataTable({
				"responsive": false,
				"lengthChange": false,
				"autoWidth": false,
				"paging": false,
				"ordering": false,
				"info": false,
				"buttons": [{
					extend: 'print',
					title: 'શ્રી ગણેશાય નમઃ',
					customize: function(win) {
						$(win.document.body).find('h1').css('text-align', 'center');
						$(win.document.body).find('th').css('font-size', '19px');
						// $(win.document.body).find('span')
						// .css( 'color','blue');
					},
					footer: true
				}, ]
			})
			.buttons().container().appendTo('#example_tablepataprint_wrapper .col-md-6:eq(0)');
		$("#example_tablevetranprint").DataTable({
				"responsive": false,
				"lengthChange": false,
				"autoWidth": false,
				"paging": false,
				"ordering": false,
				"info": false,
				"buttons": [{
					extend: 'print',
					title: 'શ્રી ગણેશાય નમઃ',
					customize: function(win) {
						$(win.document.body).find('h1').css('text-align', 'center');
						$(win.document.body).find('th').css('font-size', '19px');
						// $(win.document.body).find('span')
						// .css( 'color','blue');
					},
					footer: true
				}, ]
			})
			.buttons().container().appendTo('#example_tablevetranprint_wrapper .col-md-6:eq(0)');
		$("#example_table_wrapper .row:eq(0)").css({
			"align-items": "center",
			"padding": "10px 15px"
		});
		$("#example_table_wrapper .row:eq(2)").css({
			"align-items": "center",
			"padding": "10px 15px"
		});

		$("#example_table1").DataTable({
			"responsive": false,
			"lengthChange": true,
			"autoWidth": false,
		});
		$("#example_table1_wrapper .row:eq(0)").css({
			"align-items": "center",
			"padding": "10px 15px"
		});
		$("#example_table1_wrapper .row:eq(2)").css({
			"align-items": "center",
			"padding": "10px 15px"
		});

		$(document).on("submit", "#changePassword", function(e) {
			e.preventDefault();
			var formData = $(this).serialize();
			var self = $(this);
			$.ajax({
				url: `${BaseUrl}change_password`,
				type: "POST",
				data: formData,
				beforeSend: (data) => {
					self.find(".submit-btn span").show();
					self.find(".submit-btn").attr("disabled", true);
				},
				success: function(response) {
					var response = JSON.parse(response);
					if (response.success === true) {
						$('#changePassword').find('input[type="password"]').val('');
						$("#changePasswordModal").modal("hide");
						SweetAlert("success", response.message);
					} else {
						// $("#changePasswordModal").modal("hide");
						SweetAlert("error", response.message);
					}
				},
				error: function() {
					SweetAlert("error", "Error submitting form");
				},
				complete: () => {
					self.find(".submit-btn span").hide();
					self.find(".submit-btn").attr("disabled", false);
				},
			});
		});

	});
</script>
