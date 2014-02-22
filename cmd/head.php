<?php

function headshot($time, $args) {
	global $players;
	
	debug("Was hit headshot?", 2);
		
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
				debug("Headshot counted.", 2);
			}
		} else {
			debug("Team Hit don't care anymore.", 2);		
		}
	}			
}

function say_hs($id) {
	global $players, $alt_color, $text_color;
	
	debug("DEBUG: Say Headshots function.", 3);
	
	if ( $players[$id]->headshots == 1 )
		say($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
	else
		say($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
}

function tell_hs($id, $hs_id) {
	global $players, $alt_color, $text_color;
	
	debug("DEBUG: Tell Headshots function.", 3);
	
	if ( $players[$hs_id]->headshots == 1 )
		tell($id, $alt_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshot");
	else
		tell($id, $alt_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshots");
}

function write_hs($id) {
	global $players, $alt_color, $text_color;
	
	debug("DEBUG: Write Headshots function.", 3);
	
	if ( $players[$id]->headshots == 1 )
		write($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
	else
		write($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
}

function cmd_hs ($id, $args = null) {
	global $players;

	debug("Someone wanna see some headshots.", 2);
			
	if( isset($args[0]) ) {
		if(preg_match("/[0-9]+/", $args[0], $hs_id)) {		// If Number
			$hs_id = $hs_id[0];
			if( isset($players[$hs_id]) )
				if($players[$id]->info["team"] == TEAM_SPEC) {
					tell_hs($id, $hs_id);
				} else {
					say_hs($hs_id);
				}
			else {										// If ID not found check if it's a name
				$hs_id = search($args[0], 0, 0, $id);
				if( isset($hs_id) )
					if($players[$id]->info["team"] == TEAM_SPEC) {
						tell_hs($id, $hs_id);
					} else {
						say_hs($hs_id);	
					}
			}
		} else {
			$hs_id = search($args[0], 0, 0, $id);
			if( isset($hs_id) )
				if($players[$id]->info["team"] == TEAM_SPEC) {
					tell_hs($id, $hs_id);
				} else {
					say_hs($hs_id);	
				}
		}
	} elseif($players[$id]->info["team"] == TEAM_SPEC) {
		tell_hs($id, $id);
	} else {
		say_hs($id);
	}
}

?>