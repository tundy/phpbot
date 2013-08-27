<?php

$status = out("getstatus\n");							// Ask for server status
$data = explode("\n", $status);							// split answer, datas and players
$cvars = explode('//', $data[1]);						// explode cvars
$key = array_search('g_modversion', $cvars);			// search for mod version
$ver = explode('.', $cvars[$key+1]);					// explode version
require_once($ver[1].'.'.$ver[2].'/'.$cvars[$key+1]);	// include version cons

?>