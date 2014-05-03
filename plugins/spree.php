<?php
if( !function_exists('higest_spree') ) {
	global $spree_start, $spree_tk;

	debug();
	$file = "cfg/spree.php";
	if( !file_exists($file))
		echo("'${file}' not found.\r\n");
	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);
	
	global $players;
	if ( isset($players) && is_array($players) )
		foreach(array_keys($players) as $id)
			$players[$id]->spree = (new spree);

	function higest_spree($killer, $target) {
		global $players;

		if ( !isset($players[$killer]->spree->kill->high) )
			$players[$killer]->spree->kill->high = $players[$killer]->spree->kill->last;
		elseif ($players[$killer]->spree->kill->last > $players[$killer]->spree->kill->high)
			$players[$killer]->spree->kill->high = $players[$killer]->spree->kill->last;

		if ( !isset($players[$target]->spree->dead->high) )
			$players[$target]->spree->dead->high = $players[$target]->spree->dead->last;
		elseif ($players[$target]->spree->dead->last > $players[$target]->spree->dead->high)
			$players[$target]->spree->dead->high = $players[$target]->spree->dead->last;
	}

	function spree($args) {
		global $players, $spree_start, $spree_tk, $alt_color, $text_color;
		global $WEAPON_KILL;

		echo("Counting Killing Spree. | ");

		if($grep = grep_kill($args)) {
			unset($args);
			$killer =	$grep['killer'];
			$target =	$grep['target'];
			$weapon =	$grep['weapon'];
			unset($grep);

			// Change World feature to SelfKill
			if ($killer == WORLD or $killer == NON_CLIENT)
				$killer = $target;

			// Not Kill
			if($weapon == UT_MOD_FLAG) {
				echo("Flag captured, not kill.\r\n");
			} // Self Kill
			elseif($killer == $target) {
				if ( $players[$target]->spree->kill->last >= $spree_start)
					say($alt_color.$players[$target]->info["name"].$text_color." stopped his/her killing spree.");
				$players[$target]->spree->dead->last++;
				echo("Self Kill, RESET.\r\n");
				higest_spree($killer, $target);
				$players[$killer]->spree->kill->last = 0;
			}
			// Normal Kill
			elseif ($players[$killer]->info["team"] == TEAM_FFA or $players[$killer]->info["team"] != $players[$target]->info["team"]) {
				$players[$target]->spree->dead->last++;
				$players[$killer]->spree->kill->last++;
				$players[$killer]->spree->dead->last = 0;
				echo("Normal Kill, COUNT.\r\n");
				higest_spree($killer, $target);
			}
			// TeamKill
			else {
				echo("Team Kill, SPECIAL.\r\n");
				switch($spree_tk):
					case 1:		$players[$killer]->spree->kill->last++;
								$players[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					case 2:		if ( $players[$killer]->spree->kill->last > 0) {
									$players[$killer]->spree->kill->last--;
									say($alt_color.$players[$killer]->info["name"].$text_color." lower his/her killing spree after teamkill.");
								}
								$players[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					case 3:		if ( $players[$killer]->spree->kill->last > 0) {
									$players[$killer]->spree->kill->last = 0;
									say($alt_color.$players[$killer]->info["name"].$text_color." reset his/her killing spree after teamkill.");
								}
								$players[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					default:	break;
				endswitch;
			}

			if ($players[$killer]->spree->kill->last >= $spree_start)
				say($alt_color.$players[$killer]->info["name"].$text_color." is on killing spree. ".$alt_color.$players[$killer]->spree->kill->last.$text_color." kills in the row.");
			if ( $players[$target]->spree->kill->last >= $spree_start)
				say($alt_color.$players[$killer]->info["name"].$text_color." stopped ".$alt_color.$players[$target]->info["name"].$text_color."'s killing spree.");
			$players[$target]->spree->kill->last = 0;
		}
	}
}

switch($cmd):
	case "ClientConnect:":
		$players[$args]->spree = (new spree);
		break;
	case "Kill:":
		spree($args);
		break;
	case "ClientBegin:":
		$players[$args]->spree->kill->last	= 0;
		$players[$args]->spree->dead->last	= 0;
		break;
endswitch;
?>