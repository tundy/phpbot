<?php

function cmd_chat ($id, $args)
{
	global $players;
  
	if( isset($args) )
	{
		if($players[$killer]->info["team"] == TEAM_SPEC)	// You have to be spectator
		{
			$msg = implode(" ", $args);
			say($players[$id]."^3: ".$msg);
		}
	}
}

?>
