<?php
if( !function_exists('headshot') ) {

	global $players;
	if ( isset($players) && is_array($players) )
		foreach(array_keys($players) as $id)
			$players[$id]->headshots = 0;

	/*$HELP['cmd']['hs'][] = "${alt_color}!hs <name/id>";
	$HELP['cmd']['hs'][] = "${text_color}Aliases: ${alt_color}headshot / headshots";
	$HELP['cmd']['hs'][] = "${text_color}Show number of headshots that player did";
	$HELP['cmd']['hs'][] = "${text_color}If no name or id, show your headshots";
	$HELP['alias']['hs'] = 'hs';
	$HELP['alias']['headshot'] = 'hs';
	$HELP['alias']['headshots'] = 'hs';*/

	function headshot($args) {
		global $players;

		if($grep = grep_hit($args)) {
			unset($args);
			$target		= $grep['target'];
			$shooter	= $grep['shooter'];
			$part		= $grep['part'];
			$weapon		= $grep['weapon'];
			unset($grep);

			// Enemy hit
			if ($players[$shooter]->info["team"] == TEAM_FFA or $players[$shooter]->info["team"] != $players[$target]->info["team"]) {
				if ($part == HIT_HEAD or $part == HIT_HELMET) {
					$players[$shooter]->headshots++;
					write_hs($shooter);
				} else {
				}
			} else {
			}
		}
	}

	function say_hs($id) {
		global $players, $alt_color, $text_color;

		if ( $players[$id]->headshots == 1 )
			say($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
		else
			say($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
	}

	function tell_hs($id, $hs_id) {
		global $players, $alt_color, $text_color;

		if ( $players[$hs_id]->headshots == 1 )
			tell($id, $alt_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshot");
		else
			tell($id, $alt_color.$players[$hs_id]->info["name"].$text_color." made ".$alt_color.$players[$hs_id]->headshots.$text_color." headshots");
	}

	function write_hs($id) {
		global $players, $alt_color, $text_color;

		if ( $players[$id]->headshots == 1 )
			write($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshot");
		else
			write($alt_color.$players[$id]->info["name"].$text_color." made ".$alt_color.$players[$id]->headshots.$text_color." headshots");
	}

	function cmd_hs ($id, $args = null) {
		global $players;

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
}

switch($cmd):
	case "ClientConnect:":
		$players[$args]->headshots = 0;
		break;
	case "Hit:":
		headshot($args);
		break;
	case "say:":
		if($grep = grep_say($args))
			if( isset($grep['cmd']) )
				switch ($grep['cmd']):
					case "hs":
					case "headshot":
					case "headshots":	cmd_hs($grep['id'], $grep['args']); break;
				endswitch;
		break;
endswitch;
?>