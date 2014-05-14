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

	global $clients;
	if ( isset($clients) && is_array($clients) )
		foreach(array_keys($clients) as $id)
			$clients[$id]->spree = (new spree);

	function higest_spree($killer, $target) {
		global $clients;

		if ( !isset($clients[$killer]->spree->kill->high) )
			$clients[$killer]->spree->kill->high = $clients[$killer]->spree->kill->last;
		elseif ($clients[$killer]->spree->kill->last > $clients[$killer]->spree->kill->high)
			$clients[$killer]->spree->kill->high = $clients[$killer]->spree->kill->last;

		if ( !isset($clients[$target]->spree->dead->high) )
			$clients[$target]->spree->dead->high = $clients[$target]->spree->dead->last;
		elseif ($clients[$target]->spree->dead->last > $clients[$target]->spree->dead->high)
			$clients[$target]->spree->dead->high = $clients[$target]->spree->dead->last;
	}
	function spree($args) {
		global $clients, $spree_start, $spree_tk, $alt_color, $text_color;
		global $WEAPON_KILL;

		echo("Counting Killing Spree. | ");

		if($grep = grep_kill($args)) {
			unset($args);
			$killer = $grep['killer'];
			$target = $grep['target'];
			$weapon = $grep['weapon'];
			unset($grep);

			// Change World feature to SelfKill
			if (!is_kill_client($killer, $weapon))
				$killer = $target;

			// Not Kill
			if($weapon == UT_MOD_FLAG) {
				echo("Flag captured, not kill.\r\n");
			} // Self Kill
			elseif($killer == $target) {
				if ( $clients[$target]->spree->kill->last >= $spree_start)
					say($alt_color.$clients[$target]->info["name"].$text_color." stopped his/her killing spree.");
				$clients[$target]->spree->dead->last++;
				echo("Self Kill, RESET.\r\n");
				higest_spree($killer, $target);
				$clients[$killer]->spree->kill->last = 0;
			}
			// Normal Kill
			elseif ($clients[$killer]->info["team"] == TEAM_FFA or $clients[$killer]->info["team"] == TEAM_SPEC or $clients[$killer]->info["team"] != $clients[$target]->info["team"]) {
				$clients[$target]->spree->dead->last++;
				$clients[$killer]->spree->kill->last++;
				$clients[$killer]->spree->dead->last = 0;
				echo("Normal Kill, COUNT.\r\n");
				higest_spree($killer, $target);
			}
			// TeamKill
			else {
				echo("Team Kill, SPECIAL.\r\n");
				switch($spree_tk) {
					case 1:		$clients[$killer]->spree->kill->last++;
								$clients[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					case 2:		if ( $clients[$killer]->spree->kill->last > 0) {
									$clients[$killer]->spree->kill->last--;
									say($alt_color.$clients[$killer]->info["name"].$text_color." lower his/her killing spree after teamkill.");
								}
								$clients[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					case 3:		if ( $clients[$killer]->spree->kill->last > 0) {
									$clients[$killer]->spree->kill->last = 0;
									say($alt_color.$clients[$killer]->info["name"].$text_color." reset his/her killing spree after teamkill.");
								}
								$clients[$target]->spree->dead->last++;
								higest_spree($killer, $target);
								break;
					default:	break;
				}
			}

			if ($clients[$killer]->spree->kill->last >= $spree_start)
				say($alt_color.$clients[$killer]->info["name"].$text_color." is on killing spree. ".$alt_color.$clients[$killer]->spree->kill->last.$text_color." kills in the row.");
			if ( $clients[$target]->spree->kill->last >= $spree_start)
				say($alt_color.$clients[$killer]->info["name"].$text_color." stopped ".$alt_color.$clients[$target]->info["name"].$text_color."'s killing spree.");
			$clients[$target]->spree->kill->last = 0;
		}
	}
}

switch($cmd) {
	case "ClientConnect:":
		$clients[$args]->spree = (new spree);
		break;
	case "Kill:":
		spree($args);
		break;
	case "ClientBegin:":
		$clients[$args]->spree->kill->last	= 0;
		$clients[$args]->spree->dead->last	= 0;
		break;
}
?>