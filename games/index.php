<?php

$g_modversion = get_cvar("g_modversion");								// get urt version
$ver = explode('.', $g_modversion);										// explode version
require_once('games/'.$ver[0].'.'.$ver[1].'/'.$g_modversion.'.php');	// include version cons

?>