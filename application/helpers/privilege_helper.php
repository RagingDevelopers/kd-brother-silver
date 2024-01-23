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
];

function checkPrivilege($privilegeCode)
{
	$ci = ci();
	if (in_array($privilegeCode, $ci->session->userdata('permission'))) {
		flash()->withError('"Access Denied"')->to("dashboard");
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
