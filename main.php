<?php
if( !function_exists('c_connect') ) {

	function c_connect($id) {
		global $players;
		echo("Creating new player[${id}].\r\n");
		if( !isset($players[$id]) )
			$players[$id] = (new player);
		else
			echo("player[${id}] already exist.\r\n");
	}

	function c_begin($id) {
		global $players, $text_color, $alt_color;
		echo("Player[${id}] join the game.\r\n");

		if( !empty($players[$id]->info["name"]) and !isset($players[$id]->hello) and empty($players[$id]->hello)) {
			say($text_color."Welcome ".$alt_color.$players[$id]->info["name"]);
			$players[$id]->hello = 1;
		}
		$players[$id]->flags = 0;
	}

	function c_disconnect($id) {
		global $players;
		echo("Removing player[${id}] from memory.\r\n");
		unset($players[$id]);
	}

	function g_shutdown() {
		global $players;
		echo("Map/Server stopped.\r\n");
		/*if ( isset($players) && is_array($players) )
			foreach(array_keys($players) as $id)
				c_disconnect($id);*/
	}

	function c_changed($arg) {
		global $players;

		if($grep = grep_user($arg)) {
			unset($arg);
			$id = $grep[1];
			echo("Player[${id}] info changed.\r\n");
			$var = explode("\\", $grep[2]);
			$vars = (substr_count("$grep[2]","\\"));					// Get number of Vars
			unset($grep);
			$i = 0;
			while ($i < $vars) {										// new $var's "KEY" is equal to old $var[$i]
				$players[$id]->info["$var[$i]"] = $var[$i + 1];			// new info["KEY"]'s VALUE is equal to old $var[$i+1]
				unset($var[$i]);										// After setting new KEY old one will be unset
				unset($var[$i + 1]);									// After setting new VALUE old one will be unset
				$i += 2;
			}
			unset($i);
			unset($vars);
			unset($var);
		}
	}

	function c_info($args) {
		global $players;

		if($grep = grep_user($args)) {
			unset($arg);
			$id = $grep[1];
			echo("Player[${id}] info made.\r\n");
			$var = explode("\\", $grep[2]);
			$vars = (substr_count("$grep[2]","\\"));					// Get number of Vars
			unset($grep);
			$i = 1;
			while ($i < $vars) {										// new $var's "KEY" is equal to old $var[$i]
				$players[$id]->info["$var[$i]"] = $var[$i + 1];			// new info["KEY"]'s VALUE is equal to old $var[$i+1]
				unset($var[$i]);										// After setting new KEY old one will be unset
				unset($var[$i + 1]);									// After setting new VALUE old one will be unset
				$i += 2;
			}
			unset($i);
			unset($vars);
			unset($var);
		}
	}

	function c_hit($args) {
		global $players, $WEAPON_DAMAGE, $WEAPON_HIT, $BODY_PART, $TEAM;

		if($grep = grep_hit($args)) {
			unset($args);
			$target		= $grep['target'];
			$shooter	= $grep['shooter'];
			$part		= $grep['part'];
			$weapon		= $grep['weapon'];
			unset($grep);

			echo("player[${shooter}] (");
			echo($TEAM[$players[$shooter]->info["team"]]);
			echo(") hit player[${target}] (");
			echo($TEAM[$players[$target]->info["team"]]);
			echo(") ".$WEAPON_HIT[$weapon]);
			echo(" | ".$BODY_PART[$part]."\r\n");

			if($players[$shooter]->info["team"] == TEAM_FFA or $players[$shooter]->info["team"] != $players[$target]->info["team"]) {
				$players[$shooter]->hits->enemy->hit++;
				$players[$target]->hits->enemy->got++;
				$players[$shooter]->dmg->enemy->hit += $WEAPON_DAMAGE[$weapon][$part];
				$players[$target]->dmg->enemy->got += $WEAPON_DAMAGE[$weapon][$part];
			} else {
				$players[$shooter]->hits->team->hit++;
				$players[$target]->hits->team->got++;
				$players[$shooter]->dmg->team->hit += $WEAPON_DAMAGE[$weapon][$part];
				$players[$target]->dmg->team->got += $WEAPON_DAMAGE[$weapon][$part];
			}
		}
	}

	function c_kill($args) {
		global $players, $WEAPON_KILL, $TEAM;

		if($grep = grep_kill($args)) {
			unset($args);
			$killer = $grep['killer'];
			$target = $grep['target'];
			$weapon = $grep['weapon'];
			unset($grep);

			echo("player[$killer] (");
			if( isset($players[$killer]) )
				echo($TEAM[$players[$killer]->info["team"]]);
			else
				echo("World\r\n");

			echo(") > ".$WEAPON_KILL[$weapon]." > player[$target] (");
			echo($TEAM[$players[$target]->info["team"]]);
			echo(")\r\n");

			// Change World feature to SelfKill
			if ($killer == WORLD or $killer == NON_CLIENT)
				$killer = $target;

			if($weapon == UT_MOD_FLAG) {	// Not Kill
				// do nothing
			} elseif($killer == $target) {	// Self Kill
				$players[$killer]->kills->self++;
				$players[$target]->deads->self++;
			} elseif($players[$killer]->info["team"] == TEAM_FFA or $players[$killer]->info["team"] != $players[$target]->info["team"]) {		// Normal Kill
				$players[$killer]->kills->enemy++;
				$players[$target]->deads->enemy++;
			} else {						// Team Kill
				$players[$killer]->kills->team++;
				$players[$target]->deads->team++;
			}
		}
	}

}

switch($cmd) {
	case "ClientConnect:":
		// 0:06 ClientConnect: 4
		echo("$time: ClientConnect.\r\n");
		c_connect($args);
		break;
	case "ClientUserinfo:":
		//  0:06 ClientUserinfo: 4 \ip\188.120.11.151 ... \weapmodes\000001112200000200020
		echo("$time: ClientUserinfo.\r\n");
		c_info($args);
		break;
	case "ClientUserinfoChanged:":
		// 0:06 ClientUserinfoChanged: 4 n\-ANIKI-PaRaMeSHWaR\t\3\r\1\tl\0\a0\151\a1\151\a2\0
		echo("$time: ClientUserinfoChanged.\r\n");
		c_changed($args);
		break;
	case "ClientBegin:":
		// 0:36 ClientBegin: 4
		echo("$time: ClientBegin.\r\n");
		c_begin($args);
		break;
	case "ClientDisconnect:":
		// 6:09 ClientDisconnect: 8
		echo("$time: ClientDisconnect.\r\n");
		c_disconnect($args);
		break;
	case "ShutdownGame:":
		// 21:26 ShutdownGame:
		echo("$time: ShutdownGame.\r\n");
		g_shutdown();
		break;
	case "Item:":
		// 2:32 Item: 4 ut_weapon_ump45
		echo("$time: Item.\r\n");
		break;
	case "ClientSpawn:":
		// 72:32 ClientSpawn: 16
		echo("$time: ClientSpawn.\r\n");
		break;
	case "SurvivorWinner:":
		// 1:58 SurvivorWinner: Red
		echo("$time: SurvivorWinner.\r\n");
		break;
	case "Warmup:":
		// 0:00 Warmup:
		echo("$time: Warmup.\r\n");
		break;
	case "InitGame:":
		// 0:00 InitGame: \sv_allowdownload\0\g_matc ... th\0\auth_status\init\g_modversion\4.2.010
		echo("$time: InitGame.\r\n");
		break;
	case "InitRound:":
		// 1:11 InitRound: \sv_allowdownload\0\g_match ... lePrecip\0\auth\1\auth_status\public\g_modversion\4.2.010
		echo("$time: InitRound.\r\n");
		break;
	case "say:":
		// 5:18 say: 4 -ANIKI-PaRaMeSHWaR: Lorem i..adasd
		echo("$time: say.\r\n");
		break;
	case "sayteam:":
		// 7:08 sayteam: 6 zabijak:D: 20
		echo("$time: sayteam.\r\n");
		break;
	case "Hit:":
		// 4:00 Hit: 2 16 2 19: ThunderBird hit =lvl6=fMAQWRA in the Helmet
		echo("$time: Hit.\r\n");
		c_hit($args);
		break;
	case "Kill:":
		// 1:58 Kill: 5 4 19: Freza killed -ANIKI-PaRaMeSHWaR by UT_MOD_LR300
		echo("$time: Kill.\r\n");
		c_kill($args);
		break;
	case "Exit:":
		// 60:23 Exit: Timelimit hit.
		echo("$time: Round ended.\r\n");
		break;
	case "red:":
		// 60:23 red:-41  blue:35
		echo("$time: Got final Score for Teams.\r\n");
		break;
	case "score:":
		// 60:23 score: 29  ping: 106  client: 16 ruben
		echo("$time: Got Score for Player.\r\n");
		break;
	case "InitAuth:":
		// 0:00 InitAuth: \auth\0\auth_status\init\auth_cheaters\1\auth_tags\1\auth_notoriety\0\auth_groups\\auth_owners\\auth_verbosity\1
		echo("$time: InitAuth.\r\n");
		break;
	case "AccountValidated:":
		// 0:03 AccountValidated: 16 -  - 0 - "0"
		// 0:07 AccountValidated: 4 - parameshwar - -1 - "basic"
		echo("$time: AccountValidated.\r\n");
		break;
	case "AccountKick:":
		// 24:47 AccountKick: 18 - Notorcan rejected: bad challenge
		echo("$time: AccountKick.\r\n");
		break;
	case "AccountRejected:":
		// 24:34 AccountRejected: 18 -  - "bad challenge"
		echo("$time: AccountRejected.\r\n");
		break;
	case "Radio:":
		// 50:59 Radio: 16 - 5 - 1 - "Reception" - "Enemy spotted at Reception"
		// 51:00 Radio: 16 - 7 - 2 - "Reception" - "I'm going for the flag"
		// 51:01 Radio: 16 - 5 - 5 - "Reception" - "Incoming!"
		echo("$time: Radio.\r\n");
		break;
	case "ClientSavePosition:":
		// 39:46 ClientSavePosition: 4 - -132.564514 - -168.874969 - -3703.875000
		echo("$time: ClientSavePosition.\r\n");
		break;
	case "ClientLoadPosition:":
		// 39:46 ClientLoadPosition: 4 - -132.564514 - -168.874969 - -3703.875000
		echo("$time: ClientLoadPosition.\r\n");
		break;
	case "Flag:":
		// 60:37 Flag: 4 2: team_CTF_redflag
		// 60:41 Flag: 4 2: team_CTF_blueflag
		// 79:22 Flag: 6 0: team_CTF_blueflag
		echo("$time: Flag.\r\n");
		break;
	case "FlagCaptureTime:":
		// 60:37 FlagCaptureTime: 4: 2508600
		echo("$time: FlagCaptureTime.\r\n");
		break;
	default:
		break;
	}

?>