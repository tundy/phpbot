<?php

// Send message to server
function out($cmd) {
	global $server, $ip, $port;
	echo("Querying Server with: ");
	echo($cmd);
	echo("\r\n");

	$errno = null;
	$errstr = null;
	$cmd = "\xFF\xFF\xFF\xFF" . $cmd;										// Every query must start with 4 chars 0xFF
	$server = fsockopen('udp://' . $ip, $port, $errno, $errstr, 1);
	if (!$server)
		die ("Unable to connect. Error $errno - $errstr\n");
	socket_set_timeout ($server, 0, 125000);								// maybe the lowest possible timeout for avarage connection

	$cycles = 5;
	$cycle = 0;
	$input = '';
	while ( empty($input) ):
		if ( $cycle++ == $cycles )
			break;

		fwrite ($server, $cmd);
		$temp = '';
		while ($temp = fread ($server, 1000))
			$input .= $temp;
		if ( empty($input) )
			sleep(1);
	endwhile;
	fclose ($server);

	$temp = $input;
	$pattern = "/\xFF\xFF\xFF\xFF.*(\n|\r)/";
	$replacement = "";
	$input = preg_replace($pattern, $replacement, $input);

	if( empty($input) ):
		echo("Answer from server: ");
		print_r($temp);
		echo("\0\r\n");
		unset($temp);
		return false;
	endif;
	echo("Answer from server: ");
	print_r(trim($input));
	echo("\r\n");
	return trim($input);
}

// get cvar value from server
function get_cvar ($cvar) {
	global $rcon;
	$temp = rcon($cvar);

	$pattern = "/\".+\"\s+is:\"(.*)\^7\"\s+default:.*/";
	$subject = $temp;
	unset($temp);
	preg_match($pattern, $subject, $temp);
	if ( count($temp) < 2 ):
		$pattern = "/\".+\"\s+is:\"(.*)\^7\"/";
		preg_match($pattern, $subject, $temp);
	endif;

	if ( isset($temp[1]) )
		return trim($temp[1]);
	return false;
}

// send command to server
function rcon($cmd) {
	global $rcon;
	return (out("rcon ".$rcon." ".$cmd));
}

// send message to chat
function say($msg) {
	return (rcon("say ".$msg));
}

// send private message to player
function tell($id, $msg) {
	return (rcon("tell ".$id." ".$msg));
}

// write message in console
function write($msg) {
	global $text_color;
	return (rcon($text_color.$msg));
}

// [1] = Time in seconds, [2] = Command, [3] = Arguments
function grep_logline($line) {
	$pattern=("/([0-9]+:[0-9]{2})([a-zA-Z ]+:)(.*)/");
	if(preg_match($pattern, $line, $grep)) {
		$grep[1] = trim($grep[1]);
		$grep[2] = trim($grep[2]);
		$pattern=("/([0-9]+):([0-9]{2}).*/");
		preg_match($pattern, $grep[1], $temp);
		$grep[1] = ($temp[1]*60)+$temp[2];
		if( isset($grep[3]) )
			$grep[3] = trim($grep[3]);

		$grep['time'] = &$grep[1];
		$grep['cmd'] = &$grep[2];
		$grep['args'] = &$grep[3];
		return $grep;
	}
	return false;
}

// [1] = Time in seconds, [2] = Command, [3] = Arguments
function grep_logline_extra($line) {
	$pattern=("/([0-9]+:[0-9]{2})(.*)/");
	if(preg_match($pattern, $line, $grep)) {
		$grep[1] = trim($grep[1]);
		$grep[2] = trim($grep[2]);
		$pattern=("/([0-9]+):([0-9]{2}).*/");
		preg_match($pattern, $grep[1], $temp);
		$grep[1] = ($temp[1]*60)+$temp[2];
		
		$exp_temp = explode(' ', trim($grep[2]));		// split words into temp array
		$grep[2] = $exp_temp[0];						// First word should be command
		unset($exp_temp[0]);							// remove first word from temp array
		$grep[3] = array_merge(array(), $exp_temp);		// Other words are agruments
		unset($exp_temp);								// remove temp array
		
		$grep['time'] = &$grep[1];
		$grep['cmd'] = &$grep[2];
		$grep['args'] = &$grep[3];
		return $grep;
	}
}

// ['killer'], ['target'], ['weapon']
function grep_kill($line) {
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp)) {
		unset($line);

		$grep[1] = $temp[1];
		$grep[2] = $temp[2];
		$grep[3] = $temp[3];
		unset($temp);

		$grep['killer'] = &$grep[1];
		$grep['target'] = &$grep[2];
		$grep['weapon'] = &$grep[3];
		return $grep;
	}
	return false;
}

// ['target'], ['shooter'], ['part'], ['weapon']
function grep_hit ($line) {
	$pattern=("/([0-9]+) ([0-9]+) ([0-9]+) ([0-9]+):(.*)/");
	if(preg_match($pattern, $line, $temp)) {
		unset($line);
		
		$grep[1] = $temp[1];
		$grep[2] = $temp[2];
		$grep[3] = $temp[3];
		$grep[4] = $temp[4];
		unset($temp);

		$grep['target'] = &$grep[1];
		$grep['shooter'] = &$grep[2];
		$grep['part'] =&$grep[3];
		$grep['weapon'] = &$grep[4];
		return $grep;
	}
	return false;
}

// ['id'], ['name'], ['msg'], ['cmd'], ['args']
function grep_say($line) {
	$pattern=("/([0-9]+) (.*): (.*)/");
	if(preg_match($pattern, $line, $temp)) {
		unset($line);
		$grep['id'] = $temp[1];
		$grep['name'] = $temp[2];
		$grep['msg'] = $temp[3];
		unset($temp);
		
		$exp_temp = explode(' ', trim($grep['msg']));		// split words into temp array
		$word_temp = $exp_temp[0];							// First word should be command
		unset($exp_temp[0]);								// remove first word from temp array
		$grep['args'] = array_merge(array(), $exp_temp);	// Other words are agruments
		unset($exp_temp);									// remove temp array
		
		if(preg_match("/!(.+)/", $word_temp, $temp)) {		// If first word is !<something>
			$grep['cmd'] = $temp[1];						// set it as command
			unset($temp);
			unset($word_temp);
		} else {											// else is only message and set cmd & args to empty
			$grep['cmd'] = null;
			$grep['args'] = null;
		}
		
		return $grep;
	}
	return false;
}

// ['id'], ['vars']
function grep_user($line) {
	$pattern=("/([0-9]+) (.*)/");
	if(preg_match($pattern, $line, $grep)) {
		$grep['id'] = &$grep[1];
		$grep['vars'] = &$grep[2];
		return $grep;
	}
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

function c_create($id, $name, $team) {
	global $players, $TEAM;
	echo("Creating player[${id}] | ${name} | ".$TEAM[$team]."\r\n");
	$players[$id] = (new player);
	$players[$id]->info["name"] = $name;
	$players[$id]->info["team"] = $team;
	$players[$id]->hello = 1;
}

?>