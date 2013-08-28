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
				if ( $players[$shooter]->headshots == 1 )
					write($players[$shooter]->info["name"]."^3 made ^7".$players[$shooter]->headshots."^3 headshot");
				else
					write($players[$shooter]->info["name"]."^3 made ^7".$players[$shooter]->headshots."^3 headshots");
			}
		}
	}			
}
?>