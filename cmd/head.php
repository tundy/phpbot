<?php

function headshot ($time, $args)
{
	global $players;

	if($grep = grep_hit($args))
	{
		unset($arg);
		$target		= $grep[1];
		$shooter	= $grep[2];
		$part		= $grep[3];
		$weapon		= $grep[4];
		unset($grep);
		
		// Enemy hit
		if ($players[$shooter]->info["team"] == TEAM_FFA or $players[$shooter]->info["team"] != $players[$target]->info["team"])
		{
			if ($part == HIT_HEAD or $part == HIT_HELMET)
			{
				$players[$shooter]->headshots++;
				write_hs($shooter);
			}
		}
	}			
}

function say_hs ($id)
{
	global $players;
	
	if ( $players[$id]->headshots == 1 )
		say($players[$id]->info["name"]."^3 made ^7".$players[$id]->headshots."^3 headshot");
	else
		say($players[$id]->info["name"]."^3 made ^7".$players[$id]->headshots."^3 headshots");
}

function write_hs ($id)
{
	global $players;
	
	if ( $players[$id]->headshots == 1 )
		write($players[$id]->info["name"]."^3 made ^7".$players[$id]->headshots."^3 headshot");
	else
		write($players[$id]->info["name"]."^3 made ^7".$players[$id]->headshots."^3 headshots");
}

function cmd_hs ($id, $args);
{
	global $players;
	
	if( isset($args[0]) )
	{
		if(preg_match("/[0-9]+/", $args[0], $id))
		{
			$id = $id[0];
			if( isset($players[$id]) )
				say_hs($id);
			else						
				say("ID not found !");
		}
		else
		{
			unset($id);
			$id = search($args[0]);
			if( isset($id) )
				say_hs($id);
		}
	}
	else
		say_hs($id);
}

?>