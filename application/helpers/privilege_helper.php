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

	'user_add' => 13,
	'user_view' => 14,
	'user_edit' => 15,
	'user_delete' => 16,

];

function checkPrivilege($privilegeCode)
{
	$ci = ci();
	if (in_array($privilegeCode, $ci->session->userdata('privilege'))) {
		flash_message("danger", "Access Denied", "dashboard");
		exit();
	}
}

function isRestricted($privilegeCode)
{
	$ci = ci();
	$privileges = [];
	$privileges = $ci->session->userdata('privilege');
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
