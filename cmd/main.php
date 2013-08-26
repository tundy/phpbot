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

function c_info($time, $arg)
{
	if($temp = $this->grep_user($arg))
	{
		unset($arg);
		$id = $temp[1];
		$var = explode("\\", $temp[2]);
		$vars = (substr_count("$temp[2]","\\"));					// Get number of Vars
		unset($temp);
		$i = 1;
		while ($i < $vars)											// new $var's "KEY" is equal to old $var[$i]
		{
			$this->players[$id]->info["$var[$i]"] = $var[$i + 1];	// new info["KEY"]'s VALUE is equal to old $var[$i+1]
			unset($var[$i]);										// After setting new KEY old one will be unset
			unset($var[$i + 1]);									// After setting new VALUE old one will be unset
			$i += 2;
		}
		unset($i);
		unset($vars);
		unset($var);
	}
}

// what to do if player disconnect ?
function c_disconnect($time, $args)
{
	unset($this->players[$args]);
}

?>