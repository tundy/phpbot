<?php
function c_create($id, $name, $team) {
	global $players, $TEAM;
	echo("Creating player[${id}] | ${name} | ".$TEAM[$team]."\r\n");
	$players[$id] = (new player);
	$players[$id]->info["name"] = $name;
	$players[$id]->info["team"] = $team;
	$players[$id]->hello = 1;
}

// what to do if player connect ?
function c_connect($time, $args) {
	global $players;
	echo("Creating new player[${args}].\r\n");
	if( !isset($players[$args]) )
		$players[$args] = (new player);
	else
		echo("player[${args}] already exist.\r\n");

}

// Player enter the game
function c_begin($time, $args) {
	global $players, $text_color, $alt_color;
	echo("Player[${args}] join the game.\r\n");

	if( !empty($players[$args]->info["name"]) and !isset($players[$args]->hello) and empty($players[$args]->hello)) {
		say($text_color."Welcome ".$alt_color.$players[$args]->info["name"]);
		$players[$args]->hello = 1;
	}
	$players[$args]->spree->kill->last	= 0;
	$players[$args]->spree->dead->last	= 0;
	$players[$args]->flags				= 0;
}

// what to do if player disconnect ?
function c_disconnect($time, $args) {
	global $players;
	echo("Removing player[${args}] from memory.\r\n");
	unset($players[$args]);
}

// what to do if server shutdown ?
function g_shutdown($time) {
	global $players;
	echo("Map/Server stopped.\r\n");
	if ( isset($players) && is_array($players) )
		foreach(array_keys($players) as $player)
			c_disconnect($time, $player);
}

function c_hit($time, $args) {
	global $players, $WEAPON_DAMAGE, $WEAPON_HIT, $BODY_PART, $TEAM;

	if($grep = grep_hit($args)) {
		$target		= $grep[1];
		$shooter	= $grep[2];
		$part		= $grep[3];
		$weapon		= $grep[4];
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
	headshot($time, $args);
}

function c_kill($time, $args) {
	global $players, $WEAPON_KILL, $TEAM;

	if($grep = grep_kill($args)) {
		$killer =	$grep[1];
		$target =	$grep[2];
		$weapon =	$grep[3];
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
	spree($time, $args);
}

function c_changed($time, $arg) {
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

function c_info($time, $args) {
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

function c_say($time, $args) {
	if($grep = grep_say($args)) {
		unset($args);
		$id = $grep[1];
		$name = $grep[2];
		$msg = $grep[3];
		unset($grep);

		echo("Got new message. | ");
		echo($msg."\r\n");

		$exp_temp = explode(' ', trim($msg));
		$word_temp = $exp_temp[0];
		unset($exp_temp[0]);
		$args = array_merge(array(), $exp_temp);
		unset($exp_temp);
		if(preg_match("/!(.+)/", $word_temp, $temp)) {
			$cmd = $temp[1];
			unset($msg);
			unset($temp);
			unset($word_temp);
		} else {
			//Do nothing;
		}

		if( isset($cmd) ) {		// if command
			if ( isset($args) ) {	// if command with arguments
				switch ($cmd) {
					case "!":			cmd_chat($id, $args); break;
					case "hs":
					case "headshot":
					case "headshots":	cmd_hs($id, $args); break;
					case "h":
					case "help":		cmd_help($id, $args); break;
					default:			break;
				}
			} else {				// else command without arguments
				switch ($cmd) {
					case "hs":
					case "headshot":
					case "headshots":	cmd_hs($id, null); break;
					case "h":
					case "help":		cmd_help($id, null); break;
					default:			break;
				}
			}
		} else {					// else message
		}							// do nothing
	}
}

function c_sayteam($time, $args) {
	if($grep = grep_say($args)) {
		unset($args);
		$id = $grep[1];
		$name = $grep[2];
		$msg = $grep[3];
		unset($grep);

		echo("Got new team message. | ");
		echo($msg."\r\n");

		$exp_temp = explode(' ', trim($msg));
		$word_temp = $exp_temp[0];
		unset($exp_temp[0]);
		$args = array_merge(array(), $exp_temp);
		unset($exp_temp);
		if(preg_match("/!(.+)/", $word_temp, $temp)) {
			$cmd = $temp[1];
			unset($msg);
			unset($temp);
			unset($word_temp);
		} else {
			//Do nothing;
		}

		if( isset($cmd) ) {		// if command
			if(isset($args) )	// if command with arguments
				switch ($cmd) {
					case "!":			cmd_chat($id, $args); break;
					default:			break;
				}
			else				// else command without arguments
				switch ($cmd) {
					default:			break;
				}
		} else {				// else message
		}						// do nothing
	}
}

function grep_kill($line) {		// [1]Killer, [2]Target, [3]Weapon
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

function grep_hit ($line) {		// [1]Target, [2]Shooter, [3]Part, [4]Weapon
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

function grep_say($line) {		// [1]Player ID, [2]Name, [3]Message
	$pattern=("/([0-9]+) (.*): (.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

function grep_user($line) {		// [1]Player ID, [2]VARs
	$pattern=("/([0-9]+) (.*)/");
	if(preg_match($pattern, $line, $temp))
		return $temp;
	return false;
}

// Get player ID from Name
function search ($arg, $lower = 0, $color = 0, $id = null) {
	global $players, $alt_color, $text_color;

	if( isset($arg) ) {
		$found = array();
		if( $lower )
			$get = strtolower($arg);
		else
			$get = $arg;
		$pattern = "/(.*)(".$get.")(.*)/";
		foreach ( array_keys($players) as $id) {
			$name = $players[$id]->info["name"];
			if( $color )
				$name = preg_replace ("/(\^.)/", "", $name);
			if( $lower )
				$name = strtolower( $name );
			if( preg_match($pattern, $name) )
				$found[$id] = $players[$id]->info["name"];
		}

		if( count($found) > 1 ) {
			$msg = "Found players:";
			foreach ( array_keys($found) as $id )
			{
				$msg .= " [".$alt_color.$id.$text_color."] ".$found[$id];
			}
			if(isset($id) && $players[$id]->info["team"] == TEAM_SPEC)
				tell($msg);
			else
				say($msg);
		}
		elseif( count($found) == 1 ) {
			$id = key($found);
			return $id;
		}
		elseif( count($found) == 0 ) {
			if ( !$lower && !$color)
				return search($arg, 1, 0, $id);
			elseif ( $lower && !$color)
				return search($arg, 0, 1, $id);
			elseif ( !$lower && $color)
				return search($arg, 1, 1, $id);
			else
				say("Player not found.");
		}
	}
}
?>