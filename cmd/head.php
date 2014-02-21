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

function tell_hs($id, $hs_id) {
	global $players, $name_color, $text_color, $alt_color;
	
	if ( $players[$hs_id]->headshots == 1 )
		tell($id, $name_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshot");
	else
		tell($id, $name_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshots");
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
	debug("\t\tcmd_hs()");
			
	if( isset($args[0]) ) {
		debug("\t\t\tARG is set.");
		if(preg_match("/[0-9]+/", $args[0], $hs_id)) {		// If Number
			$hs_id = $hs_id[0];
			if( isset($players[$hs_id]) )
				if($players[$id]->info["team"] == TEAM_SPEC) {
					debug("\t\t\tPLAYER is spec.");
					tell_hs($id, $hs_id);
				} else {
					debug("\t\t\tPLAYER is not spec.");
					say_hs($hs_id);
				}
			else {										// If ID not found check if it's a name
				$hs_id = search($args[0], 0, 0, $id);
				if( isset($hs_id) )
					if($players[$id]->info["team"] == TEAM_SPEC) {
						debug("\t\t\tPLAYER is spec.");
						tell_hs($id, $hs_id);
					} else {
						debug("\t\t\tPLAYER is not spec.");
						say_hs($hs_id);	
					}
			}
		} else {
			$hs_id = search($args[0], 0, 0, $id);
			if( isset($hs_id) )
				if($players[$id]->info["team"] == TEAM_SPEC) {
					debug("\t\t\tPLAYER is spec.");
					tell_hs($id, $hs_id);
				} else {
					debug("\t\t\tPLAYER is not spec.");
					say_hs($hs_id);	
				}
		}
	} elseif($players[$id]->info["team"] == TEAM_SPEC) {
		debug("\t\t\tARG is not set.");
		debug("\t\t\tPLAYER is spec.");
		tell_hs($id, $id);
	} else {
		debug("\t\t\tARG is not set.");
		debug("\t\t\tPLAYER is not spec.");
		say_hs($id);
	}
}

?>