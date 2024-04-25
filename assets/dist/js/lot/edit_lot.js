function setDataEveryWhere(data) {
	var grossWeight = $("#input-gross-weight");
	var selectItem = $('select[name="m_item"]');
	var pcs = $("#input-pcs");
	var netWeight = $("#input-net-weight");
	var other_amt = $("#input-amt");
	var l_weight = $("#input-less-weight");

	grossWeight.val(data.data.gross_weight);
	selectItem.val(data.data.item_id).trigger("change");
	netWeight.val(data.data.net_weight);
	other_amt.val(data.data.amt);
	pcs.val(data.data.piece);
	l_weight.val(data.data.l_weight);

	$("#output-of-barcode").html("");
	adTable.children("tbody").html("");
	$("#input-amt").val(data.data.amt);

	var totalPcs = parseInt($(".t-lot-pcs").text()) || 0;
	var donePcs = parseInt($(".done-pcs").text()) || 0;
	$('.pending-pcs').text((totalPcs - donePcs) + parseFloat(data.data.piece));

	calculateExtraAmount();
}
