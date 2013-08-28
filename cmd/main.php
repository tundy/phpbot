<?php

// what to do if player connect ?
function c_connect ($time, $args)
{
	$players[$args] = (new player);
}	

// Player enter the game	
function c_begin ($time, $args)
{
	if ( !empty($players[$args]->info["name"]) )
		say("Welcome ".$players[$args]->info["name"]);
	$players[$args]->spree		= 0;
	$players[$args]->flags		= 0;
}

// what to do if player disconnect ?
function c_disconnect ($time, $args)
{
	unset($players[$args]);
}

// what to do if server shutdown ?
function g_shutdown ($time)
{
	foreach($players as $player)
		c_disconnect($time, $player);
}

function c_hit ($time, $args)
{
	headshot($time, $args);
}

function c_kill ($time, $args)
{
	spree($time, $args);
}

function c_info ($time, $args)
{
	if($temp = grep_user($args))
	{
		unset($arg);
		$id = $temp[1];
		$var = explode("\\", $temp[2]);
		$vars = (substr_count("$temp[2]","\\"));					// Get number of Vars
		unset($temp);
		$i = 1;
		while ($i < $vars)											// new $var's "KEY" is equal to old $var[$i]
		{
			$players[$id]->info["$var[$i]"] = $var[$i + 1];	// new info["KEY"]'s VALUE is equal to old $var[$i+1]
			unset($var[$i]);										// After setting new KEY old one will be unset
			unset($var[$i + 1]);									// After setting new VALUE old one will be unset
			$i += 2;
		}
		unset($i);
		unset($vars);
		unset($var);
	}
}
	
function grep_kill ($line)	// [1]Killer, [2]Target, [3]Weapon
{
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

function grep_hit ($line)	// [1]Target, [2]Shooter, [3]Part, [4]Weapon
{
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}
	
?>