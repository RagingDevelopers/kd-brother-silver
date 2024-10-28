<?php

const privilege = [

	'process_add' => 1,
	'process_view' => 2,
	'process_edit' => 3,
	'process_delete' => 4,

	'city_add' => 5,
	'city_view' => 6,
	'city_edit' => 7,
	'city_delete' => 8,

	'account_type_add' => 9,
	'account_type_view' => 10,
	'account_type_edit' => 11,
	'account_type_delete' => 12,

	'users_add' => 13,
	'users_view' => 14,
	'users_edit' => 15,
	'users_delete' => 16,

	'customer_add' => 17,
	'customer_view' => 18,
	'customer_edit' => 19,
	'customer_delete' => 20,

	'row_material_type_add' => 21,
	'row_material_type_view' => 22,
	'row_material_type_edit' => 23,
	'row_material_type_delete' => 24,

	'row_material_add' => 25,
	'row_material_view' => 26,
	'row_material_edit' => 27,
	'row_material_delete' => 28,

	'metal_type_add' => 29,
	'metal_type_view' => 30,
	'metal_type_edit' => 31,
	'metal_type_delete' => 32,


	'category_add' => 33,
	'category_view' => 34,
	'category_edit' => 35,
	'category_delete' => 36,

	'item_add' => 37,
	'item_view' => 38,
	'item_edit' => 39,
	'item_delete' => 40,

	'garnu_add' => 41,
	'garnu_view' => 42,
	'garnu_edit' => 43,
	'garnu_delete' => 44,

	'bank_add' => 45,
	'bank_view' => 46,
	'bank_edit' => 47,
	'bank_delete' => 48,

	'stamp_add' => 49,
	'stamp_view' => 50,
	'stamp_edit' => 51,
	'stamp_delete' => 52,

	'unit_add' => 53,
	'unit_view' => 54,
	'unit_edit' => 55,
	'unit_delete' => 56,

	'given_garnu' => 57,
	'given_garnu_view' => 58,
	'given_garnu_edit' => 59,

	'receive_garnu' => 60,
	'receive_garnu_view' => 61,
	'receive_garnu_edit' => 62,

	'main_report' => 63,

	'jama_add' => 64,
	'jama_view' => 65,
	'jama_edit' => 66,
	'jama_delete' => 67,

	'baki_add' => 68,
	'baki_view' => 69,
	'baki_edit' => 70,
	'baki_delete' => 71,

	'row_material_stock' => 72,
	'metal_type_stock' => 73,
	'row_material_closing_stock' => 74,

	'main_garnu_add' => 75,
	'main_garnu_view' => 76,
	'main_garnu_edit' => 77,

	'lot_creation_report' => 78,
	'lot_creation_edit' => 79,
	'lot_creation_add' => 80,
	'lot_creation_delete' => 81,

	'account_ledger' => 82,
	'daybook_report' => 83,
	'balance_sheet_report' => 84,
	'lot_report' => 85,
	
	'purchase_add' => 86,
	'purchase_view' => 87,
	'purchase_edit' => 88,
	'purchase_delete' => 89,
	
	'purchase_return_add' => 90,
	'purchase_return_view' => 91,
	'purchase_return_edit' => 92,
	'purchase_return_delete' => 93,
	
	'sale_add' => 94,
	'sale_view' => 95,
	'sale_edit' => 96,
	'sale_delete' => 97,
	
	'sale_return_add' => 98,
	'sale_return_view' => 99,
	'sale_return_edit' => 100,
	'sale_return_delete' => 101,
	
	'silver_bhav_report' => 102,
	'stock_report' => 103,
	
	'sequence_view' => 104,
	'sequence_add' => 105,
	'sequence_edit' => 106,
	'sequence_delete' => 107,
	
	'lot_wise_rm_report' =>108,
	
	'sub_item_add' => 109,
	'sub_item_view' => 110,
	'sub_item_edit' => 111,
	'sub_item_delete' => 112,
	
	'main_given_report' => 113,

	'ready_for_sale_add' => 114,
	'ready_for_sale_view' => 115,
	'ready_for_sale_edit' => 116,
	'ready_for_sale_delete' => 117,
	
	'given_testing_add' => 118,
	'given_testing_view' => 119,
	'given_testing_edit' => 120,
	'given_testing_delete' => 121,
];

function checkPrivilege($privilegeCode)
{
	$ci = ci();
	if (in_array($privilegeCode, $ci->session->userdata('permission'))) {
		flash()->withError('You dont have permission')->to("dashboard");
		exit();
	}
}

function isRestricted($privilegeCode)
{
	$ci = ci();
	$privileges = [];
	$privileges = $ci->session->userdata('permission');
	if (!empty($privileges)) {
		return in_array($privilegeCode, $privileges);
	} else {
		return false;
	}
}

function permissionCheck($str)
{
	return isRestricted(privilege[$str]);
}

function isRestrictedAll($arr = [])
{
	$bool = false;
	foreach ($arr as $v) {
		$bool = isRestricted(privilege[$v]);
	}
	return $bool;
}

function isRestrictedAllCrud($arr = [])
{
	$bool = false;
	$trueCount = 0;
	foreach ($arr as $v) {
		// echo $v;
		$bool = permissionCheck($v);
		if ($bool) {
			$trueCount++;
		}
	}
	return count($arr) == $trueCount;
}
