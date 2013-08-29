<?php

// what to do if player connect ?
function c_connect ($time, $args)
{
	global $players;
	$players[$args] = (new player);
}	

// Player enter the game	
function c_begin ($time, $args)
{
	global $players;
	
	if ( !empty($players[$args]->info["name"]) and !isset($players[$args]->hello))
	{
		say("Welcome ".$players[$args]->info["name"]);
		$players[$args]->hello = 1;
	}
		
	$players[$args]->spree->kill->last	= 0;
	$players[$args]->spree->dead->last	= 0;
	$players[$args]->flags				= 0;
}

// what to do if player disconnect ?
function c_disconnect ($time, $args)
{
	global $players;
	unset($players[$args]);
}

// what to do if server shutdown ?
function g_shutdown ($time)
{
	global $players;
	foreach(array_keys($players) as $player)
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
	global $players;
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

function c_say ($time, $args)
{	
	if($grep = grep_say($args))
	{
		unset($arg);
		$id = $grep[1];
		$name = $grep[2];
		$msg = $grep[3];
		unset($grep);
		if(preg_match("/(.*) (.*)/", $msg, $grep))
		{
		$cmd = $grep[1];
		$args = explode(' ', $grep[2]);
		unset($grep);
		}
		else
		{
			$cmd = $msg;
			unset($msg);
		}
		
		switch ($cmd)
		{
			case "!hs":
			case "!headshot":
			case "!haedshots":	cmd_hs($id, $args); break;
			default:			break;
		}
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

function grep_say($line)	// [1]Player ID, [2]VARs
{
	$pattern=("/([0-9]+) (.*): (.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

function grep_user($line)	// [1]Player ID, [2]VARs
{
	$pattern=("/([0-9]+) (.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

// Get player ID from Name
function search ($arg)
{
	global $players;
	
	if( isset($arg) )
	{
		$pattern = "/(.*)(".$arg.")(.*)/";
		foreach ( array_keys($players) as $id)
		{
			if( preg_match($pattern, $players[$id]->info["name"]) )
				$found[$id] = $players[$id]->info["name"];
		}
		
		if( count($found) > 1 )
		{
			$msg = "Found players:";
			foreach ( array_keys($found) as $id )
			{
				$msg .= " [".$id."] ".$found[$id];
			}
			say($msg);
		}
		elseif( count($found) == 1 )
		{
			$id = key($found);
			return $id;
		}
		elseif( count($found) == 0 )
		{
			
			if( strtolower($arg) == $arg )
				say("Player not found.");
			else
			{
			$arg = strtolower($arg);
			return search($arg);
			}
		}
	}	
}

?>