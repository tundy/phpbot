<?php
$g_modversion = get_cvar("g_modversion");								// get urt version
$ver = explode('.', $g_modversion);										// explode version

$file = 'games/'.$ver[0].'.'.$ver[1].'/'.$g_modversion.'.php';
echo("Including ${file}.\r\n");
if( (include_once $file) === false )
	debug('die');
unset($file);
?>