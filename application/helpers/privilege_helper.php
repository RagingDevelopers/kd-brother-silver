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

	'row_meterial_type_add' => 21,
	'row_meterial_type_view' => 22,
	'row_meterial_type_edit' => 23,
	'row_meterial_type_delete' => 24,

	'row_meterial_add' => 25,
	'row_meterial_view' => 26,
	'row_meterial_edit' => 27,
	'row_meterial_delete' => 28,

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
];

function checkPrivilege($privilegeCode)
{
	$ci = ci();
	if (!in_array($privilegeCode, $ci->session->userdata('permission')) && session('is_admin')) {
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