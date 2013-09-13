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
	if ( isset($players) && is_array($players) )
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
		
function c_changed($time, $arg)
{
	global $players;

	if($grep = grep_user($arg))
	{
		unset($arg);
		$id = $grep[1];
		$var = explode("\\", $grep[2]);
		$vars = (substr_count("$grep[2]","\\"));					// Get number of Vars
		unset($grep);
		$i = 0;
		while ($i < $vars)											// new $var's "KEY" is equal to old $var[$i]
		{
			$players[$id]->info["$var[$i]"] = $var[$i + 1];			// new info["KEY"]'s VALUE is equal to old $var[$i+1]
			unset($var[$i]);										// After setting new KEY old one will be unset
			unset($var[$i + 1]);									// After setting new VALUE old one will be unset
			$i += 2;
		}
		unset($i);
		unset($vars);
		unset($var);
	}
}

function c_info ($time, $args)
{
	global $players;
	if($grep = grep_user($args))
	{
		unset($arg);
		$id = $grep[1];
		$var = explode("\\", $grep[2]);
		$vars = (substr_count("$grep[2]","\\"));					// Get number of Vars
		unset($grep);
		$i = 1;
		while ($i < $vars)											// new $var's "KEY" is equal to old $var[$i]
		{
			$players[$id]->info["$var[$i]"] = $var[$i + 1];			// new info["KEY"]'s VALUE is equal to old $var[$i+1]
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
		unset($args);
		$id = $grep[1];
		$name = $grep[2];
		$msg = $grep[3];
		unset($grep);
		// if command with arguments
		if(preg_match("/!(.+) (.+)/", $msg, $grep))
		{
		$cmd = $grep[1];
		$args = explode(' ', $grep[2]);
		unset($grep);
		}
		// if command without arguments
		elseif(preg_match("/!(.+)/", $msg, $grep))
		{
			$cmd = $msg;
			unset($msg);
			unset($grep);
		}
		// else message
				
		if( isset($cmd) )		// if command
		{
			if ( isset($args) )	// if command with arguments
				switch ($cmd)
				{
					case "!":			cmd_chat($id, $args); break;
					case "hs":
					case "headshot":
					case "headshots":	cmd_hs($id, $args); break;
					default:			break;
				}
			else				// else command without arguments
				switch ($cmd)
				{
					case "hs":
					case "headshot":
					case "headshots":	cmd_hs($id, null); break;
					default:			break;
				}
		}
		else					// else message
		{}						// do nothing
	}
}

function c_sayteam ($time, $args)
{	
	if($grep = grep_say($args))
	{
		unset($args);
		$id = $grep[1];
		$name = $grep[2];
		$msg = $grep[3];
		unset($grep);
		// if command with arguments
		if(preg_match("/!(.+) (.+)/", $msg, $grep))
		{
			$cmd = $grep[1];
			$args = explode(' ', $grep[2]);
			unset($grep);
		}
		// if command without arguments
		elseif(preg_match("/!(.+)/", $msg, $grep))
		{
			$cmd = $msg;
			unset($msg);
			unset($grep);
		}
		// else message
				
		if( isset($cmd) )		// if command
		{
			if (isset($args) )	// if command with arguments
				switch ($cmd)
				{
					case "!":			cmd_chat($id, $args); break;
					default:			break;
				}
			else				// else command without arguments
				switch ($cmd)
				{
					default:			break;
				}
		}
		else					// else message
		{}						// do nothing
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

function grep_say($line)	// [1]Player ID, [2]Name, [3]Message
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
function search ($arg, $lower = 0, $color = 0)
{
	global $players;
	
	if( isset($arg) )
	{
		$found = array();
		if( $lower )
			$get = strtolower($arg);
		else
			$get = $arg;
		$pattern = "/(.*)(".$get.")(.*)/";
		foreach ( array_keys($players) as $id)
		{
			$name = $players[$id]->info["name"];
			if( $color )
				$name = preg_replace ("/(\^.)/", "", $name);
			if( $lower )
				$name = strtolower( $name );
			if( preg_match($pattern, $name) )
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
			
			if ( !$lower && !$color)
				return search($arg, 1, 0);
			elseif ( $lower && !$color)
				return search($arg, 1, 1);
			elseif ( $lower && $color)
				return search($arg, 0, 1);
			else
				say("Player not found.");
		}
	}	
}
?>