<?php

function c_connect($time, $args)
{
	$this->players[$args] = (new player);
}	

function c_disconnect($time, $args)
{
	unset($this->players[$args]);
}

?>