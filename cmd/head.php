<?php

function headshot($time, $args) {
	global $players;
	
	if($grep = grep_hit($args)) {
		unset($arg);
		$target		= $grep[1];
		$shooter	= $grep[2];
		$part		= $grep[3];
		$weapon		= $grep[4];
		unset($grep);
		
		// Enemy hit
		if ($players[$shooter]->info["team"] == TEAM_FFA or $players[$shooter]->info["team"] != $players[$target]->info["team"]) {
			if ($part == HIT_HEAD or $part == HIT_HELMET) {
				$players[$shooter]->headshots++;
				write_hs($shooter);
			}
		}
	}			
}

function say_hs($id) {
	global $players, $name_color, $text_color, $alt_color;
	
	if ( $players[$id]->headshots == 1 )
		say($name_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
	else
		say($name_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
}

function write_hs($id) {
	global $players, $name_color, $text_color, $alt_color;
	
	if ( $players[$id]->headshots == 1 )
		write($name_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
	else
		write($name_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
}

function cmd_hs ($id, $args = null) {
	global $players;
	
	if( isset($args[0]) ) {
		if(preg_match("/[0-9]+/", $args[0], $id)) {		// If Number
			$id = $id[0];
			if( isset($players[$id]) )
				say_hs($id);
			else {										// If ID not found check if it's a name
				unset($id);
				$id = search($args[0]);
				if( isset($id) )
					say_hs($id);	
			}
		}
		else {
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