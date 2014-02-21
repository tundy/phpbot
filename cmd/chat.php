<?php

function cmd_chat($id, $args) {
	global $players, $name_color, $text_color;
	debug("\t\tcmd_chat()");
	
	if( isset($args) ) {
		if($players[$id]->info["team"] == TEAM_SPEC) {	// You have to be spectator
			$msg = implode(" ", $args);
			say($name_color.$players[$id]->info["name"]."^0: ".$text_color.$msg);
		}
	}
}

?>
