<?php

function cmd_chat($id, $args) {
	global $players, $alt_color, $text_color;

	if( isset($args) ) {
		if($players[$id]->info["team"] == TEAM_SPEC) {	// You have to be spectator
			$msg = implode(" ", $args);
			say($alt_color.$players[$id]->info["name"]."^0: ".$text_color.$msg);
		}
	}
}

?>
