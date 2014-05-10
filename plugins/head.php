<?php
if( !function_exists('headshot') ) {

	global $clients;
	if ( isset($clients) && is_array($clients) )
		foreach(array_keys($clients) as $id)
			$clients[$id]->headshots = 0;

	/*$HELP['cmd']['hs'][] = "${alt_color}!hs <name/id>";
	$HELP['cmd']['hs'][] = "${text_color}Aliases: ${alt_color}headshot / headshots";
	$HELP['cmd']['hs'][] = "${text_color}Show number of headshots that client did";
	$HELP['cmd']['hs'][] = "${text_color}If no name or id, show your headshots";
	$HELP['alias']['hs'] = 'hs';
	$HELP['alias']['headshot'] = 'hs';
	$HELP['alias']['headshots'] = 'hs';*/

	function headshot($args) {
		global $clients;

		if($grep = grep_hit($args)) {
			unset($args);
			$target		= $grep['target'];
			$shooter	= $grep['shooter'];
			$part		= $grep['part'];
			$weapon		= $grep['weapon'];
			unset($grep);

			// Enemy hit
			if ($clients[$shooter]->info["team"] == TEAM_FFA or $clients[$killer]->info["team"] == TEAM_SPEC or $clients[$shooter]->info["team"] != $clients[$target]->info["team"]) {
				if ($part == HIT_HEAD or $part == HIT_HELMET) {
					$clients[$shooter]->headshots++;
					write_hs($shooter);
				} else {
				}
			} else {
			}
		}
	}

	function say_hs($id) {
		global $clients, $alt_color, $text_color;

		if ( $clients[$id]->headshots == 1 )
			say($alt_color.$clients[$id]->info["name"].$text_color." made ".$alt_color.$clients[$id]->headshots.$text_color." headshot");
		else
			say($alt_color.$clients[$id]->info["name"].$text_color." made ".$alt_color.$clients[$id]->headshots.$text_color." headshots");
	}

	function tell_hs($id, $hs_id) {
		global $clients, $alt_color, $text_color;

		if ( $clients[$hs_id]->headshots == 1 )
			tell($id, $alt_color.$clients[$hs_id]->info["name"].$text_color." made ".$alt_color.$clients[$hs_id]->headshots.$text_color." headshot");
		else
			tell($id, $alt_color.$clients[$hs_id]->info["name"].$text_color." made ".$alt_color.$clients[$hs_id]->headshots.$text_color." headshots");
	}

	function write_hs($id) {
		global $clients, $alt_color, $text_color;

		if ( $clients[$id]->headshots == 1 )
			write($alt_color.$clients[$id]->info["name"].$text_color." made ".$alt_color.$clients[$id]->headshots.$text_color." headshot");
		else
			write($alt_color.$clients[$id]->info["name"].$text_color." made ".$alt_color.$clients[$id]->headshots.$text_color." headshots");
	}

	function cmd_hs ($id, $args = null) {
		global $clients;

		if( isset($args[0]) ) {
			if(preg_match("/[0-9]+/", $args[0], $hs_id)) {		// If Number
				$hs_id = $hs_id[0];
				if( isset($clients[$hs_id]) )
					if($clients[$id]->info["team"] == TEAM_SPEC) {
						tell_hs($id, $hs_id);
					} else {
						say_hs($hs_id);
					}
				else {										// If ID not found check if it's a name
					$hs_id = search($args[0], 0, 0, $id);
					if( isset($hs_id) )
						if($clients[$id]->info["team"] == TEAM_SPEC) {
							tell_hs($id, $hs_id);
						} else {
							say_hs($hs_id);
						}
				}
			} else {
				$hs_id = search($args[0], 0, 0, $id);
				if( isset($hs_id) )
					if($clients[$id]->info["team"] == TEAM_SPEC) {
						tell_hs($id, $hs_id);
					} else {
						say_hs($hs_id);
					}
			}
		} elseif($clients[$id]->info["team"] == TEAM_SPEC) {
			tell_hs($id, $id);
		} else {
			say_hs($id);
		}
	}
}

switch($cmd):
	case "ClientConnect:":
		$clients[$args]->headshots = 0;
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