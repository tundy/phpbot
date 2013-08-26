<?php

// what to do if player connect ?
function c_connect($time, $args)
{
	players[$args] = (new player);
}	

// Player enter the game	
function c_begin($time, $arg)
{
	players[$arg]->spree		= 0;
	players[$arg]->flags		= 0;
}	

// what to do if player disconnect ?
function c_disconnect($time, $args)
{
	unset($this->players[$args]);
}

?>