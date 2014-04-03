<?php
$file = 'games/'.$ver[0].'.'.$ver[1].'/4.2.018.php';
echo("Including ${file}.\r\n");
if( (include_once $file) === false )
	debug('die');
unset($file);
?>