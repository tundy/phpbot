<?php

if( !function_exists('cmd_chat') ) {
	function cmd_chat($id, $args) {
		global $players, $alt_color, $text_color;

		if( isset($args) ) {
			if($players[$id]->info["team"] == TEAM_SPEC) {	// You have to be spectator
				$msg = implode(" ", $args);
				say($alt_color.$players[$id]->info["name"]."^0: ".$text_color.$msg);
			}
		}
	}
}

switch($cmd):
	case "say:":
		if($grep = grep_say($args))
			if( isset($grep['cmd']) )
				switch ($grep['cmd']):
					case "!": cmd_chat($grep['id'], $grep['args']); break;
				endswitch;
		break;
endswitch;

?>
