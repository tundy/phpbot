<?php

$status = out('getstatus\n');
$pattern=("/.*\/\/g_modversion\/\/(.*)\/");
if(preg_match($pattern, $status, $grep))
{
	$ver = explode('.', $grep[1]);
	require_once($ver[1].'.'.$ver[2].'/'.$grep[1]);
}
?>