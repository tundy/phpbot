<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

	if(PHP_SAPI !== 'cli')
		die("Start this script from console!\r\n");
	$file = "cfg/config.php";
	if( !file_exists($file) )
		die("'${file}' file not found.\r\n");
	require_once($file);
	unset($file);

	if( !isset($logfile) or empty($logfile) ):
		echo("\$logfile adress is not set.\r\n");
		echo("'bot.log' used instead.\r\n");
		$logfile = 'bot.log';
	endif;
	
	ob_start();
	file_put_contents($logfile, '');
	echo("Loading Configurations.\r\n");
	debug();

	$file = "cfg/colors.php";
	if( !file_exists($file)):
		echo("'${file}' not found.\r\n");
		debug('die');
	endif;
	debug();

	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);

	echo("Trying to load Server Log.\r\n");
	debug();

	if( !isset($log) or empty($log) ):
		echo("Server '\$log' is not set.\r\n");
		debug('die');
	endif;
	debug();

	if( !file_exists($log)):
		echo("'${log}' not found.\r\n");
		debug('die');
	endif;
	debug();

	if( !is_readable($log)):
		echo("Can't read from '${log}'.\r\n");
		debug('die');
	endif;
	echo("Using '${log}' for logfile.\r\n");
	debug();

	if( !isset($rcon) or empty($rcon) ):
		echo("rcon password is not set.\r\n");
		debug('die');
	endif;

	// If not set, use default instead
	if( !isset($ip) or empty($ip) ):
		echo("\$ip adress is not set.\r\n");
		echo("127.0.0.1 used instead.\r\n");
		$ip = '127.0.0.1';
	endif;
	if( !isset($port) or empty($port) ):
		echo("\$port is not set.\r\n");
		echo("27960 used instead.\r\n");
		$port = 27960;
	endif;
	if( !isset($say_prefix) or empty($say_prefix) ):
		echo("\$say_prefix is not set.\r\n");
		echo("'^0[^8B^0]^9: ' used instead.\r\n");
		$say_prefix = '^0[^8B^0]^9: ';
	endif;
	if( !isset($tell_prefix) or empty($tell_prefix) ):
		echo("\$tell_prefix is not set.\r\n");
		echo("'^0[^8PM^0]^9: ' used instead.\r\n");
		$tell_prefix = '^0[^8PM^0]^9: ';
	endif;
	debug();

	$text_color = YELLOW_COLOR;
	$alt_color = WHITE_COLOR;
	$say_prefix = $say_prefix.$text_color;
	$tell_prefix = $tell_prefix.$text_color;
	rcon("set sv_sayprefix \"" . $say_prefix . "\"");
	rcon("set sv_tellprefix \"" . $tell_prefix . "\"");
	unset($say_prefix);
	unset($tell_prefix);

	echo("Loading Functions.\r\n");
	debug();

	$file = "cmd/main.php";
	if( !file_exists($file) ):
		echo("'${file}' file not found.\r\n");
		debug('die');
	endif;

	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);
	debug();

	// Include all other functions
	foreach (glob("cmd/*.php") as $file):
		echo("Including ${file}.\r\n");
		if( (include_once $file) === false )
			debug('die');
		debug();
	endforeach;
	unset($file);

	echo("Loading Classes.\r\n");
	debug();
	// Include all classes
	foreach (glob("class/*.php") as $file)
		echo("Including ${file}.\r\n");
		if( (include_once $file) === false )
			debug('die');
	unset($file);

	echo("Checking Game Version.\r\n");
	$file = "games/index.php";
	if( !file_exists($file) ):
		echo("'${file}' file not found.\r\n");
		debug('die');
	endif;
	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);

bot_initialize();
bot_loop();

// write STDOUT to $logfile
function debug($arg = null) {
	global $logfile;
	file_put_contents($logfile, ob_get_contents(), FILE_APPEND);
	if($arg == 'kill' || $arg == 'die' || $arg == 'stop' || $arg == 'quit' || $arg == 'exit'):
		ob_flush();
		ob_end_clean();
		die("Unexpectable Error!\r\n");
	elseif ($arg):
		ob_flush();
	endif;
	ob_end_clean();
	ob_start();
	return true;
}

// Load all configurations and get current server information
function bot_initialize() {
	global $log, $lines;

	echo("Starting Initialization.\r\n");
	debug();

	$file = new SplFileObject($log);
	$lines = 0;

	echo("Seeking to end of Log File.");
	$file->seek($lines);
	while (!$file->eof()) {
		$lines = $file->key();
		$file->current();
		if($file->valid())
			$file->next();
	}
	echo(" | Last line is $lines.\r\n");

	echo("Adding already connected players into memmory.\r\n");
	$status = rcon("status");
	if ( empty($status) )
		return false;
	$status = preg_split('/\n|\r/', $status, 0, PREG_SPLIT_NO_EMPTY);					// Change lines to array

	$pattern=("/map:\s+(.+)/");
	if(preg_match($pattern, $status[0], $temp)):
		$map = $temp[1];
		unset($status[0]);
	endif;

	$temp_players = array();

	echo("Searching for Red & Blue Members.\r\n");
	$g_blueteamlist = get_cvar("g_blueteamlist");
	if ( !empty($g_blueteamlist) ) {
		$g_blueteamlist = str_split($g_blueteamlist);
		foreach ( $g_blueteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_BLUE;
		}
	}

	$g_redteamlist = get_cvar("g_redteamlist");
	if ( !empty($g_redteamlist) ) {
		$g_redteamlist = str_split($g_redteamlist);
		foreach ( $g_redteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_RED;
		}
	}

	echo("Requesting status from server.\r\n");
	foreach ($status as $player) {
		$pattern=("/(\d+)\s+([-]*\d+)\s+(\d+)\s+(.*)\s+(\d+)\s+(.+)\s+(\d+)\s+(\d+).*/");
		if(preg_match($pattern, $player, $temp)) {
			$id = trim($temp[1]);
			$score = trim($temp[2]);
			$ping = trim($temp[3]);
			$name = trim($temp[4]);
			$lastmsg = trim($temp[5]);
			$address = trim($temp[6]);
			$qport = trim($temp[7]);
			$rate = trim($temp[8]);
			if ( !isset($temp_players[$id]) )
				$team = TEAM_SPEC;
			else
				$team = $temp_players[$id];

			unset($temp_players[$id]);
			c_create($id, $name, $team);
		}
	}

	echo("Sending message to server that BOT is online.\r\n");
	say(" ^9BOT ^1S^2t^3a^4r^5t^6e^7d ^8!");

	unset($temp_players);
	unset($temp);

	debug();
}

// Scanning Server Log
function bot_loop() {
	global $log, $loop, $lines;
	global $players;
	echo("Entered Loop.\r\n");

	$file = new SplFileObject($log);
	$loop = 1;

	// Loop that will scan log file forever
	while($loop):
		time_sleep_until(microtime(true)+0.2);
		$last_line = -1;
		$file->seek($lines);
		while(!$file->eof()):
			$lines = $file->key();
			$line = $file->current();
			if($file->valid())
				$file->next();
			else
				$last_line = $file->key();

			if($last_line != $lines)
				decode($line);
		endwhile;

		debug();
		#file_put_contents('players.log', print_r($players, true));
	endwhile;
}

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
		echo("\\0\r\n");
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
// Input:
// 43:45 ClientConnect: 1
// Output:
// [1] = Time in seconds	(2625)
// [2] = Command			('ClientConnect:')
// [3] = Arguments			('1')
	$pattern=("/([0-9]+:[0-9]{2})([a-zA-Z ]+:)(.*)/");
	if(preg_match($pattern, $line, $grep)) {
		$grep[1] = trim($grep[1]);
		$grep[2] = trim($grep[2]);
		$pattern=("/([0-9]+):([0-9]{2}).*/");
		preg_match($pattern, $grep[1], $temp);
		$grep[1] = ($temp[1]*60)+$temp[2];
		if( isset($grep[3]) )
			$grep[3] = trim($grep[3]);
		return $grep;
	}
	return false;
}

// Decode commands from log
function decode($line) {
	if($temp = grep_logline($line)) {
		$time	= $temp[1];
		$cmd	= $temp[2];
		if( isset($temp[3]) )
			$args = $temp[3];
		else
			$args = null;
		switch($cmd) {
			case "ClientConnect:":
				// 0:06 ClientConnect: 4
				echo("$time: ClientConnect.\r\n");
				c_connect($time, $args);
				break;
			case "ClientUserinfo:":
				//  0:06 ClientUserinfo: 4 \ip\188.120.11.151 ... \weapmodes\000001112200000200020
				echo("$time: ClientUserinfo.\r\n");
				c_info($time, $args);
				break;
			case "ClientUserinfoChanged:":
				// 0:06 ClientUserinfoChanged: 4 n\-ANIKI-PaRaMeSHWaR\t\3\r\1\tl\0\a0\151\a1\151\a2\0
				echo("$time: ClientUserinfoChanged.\r\n");
				c_changed($time, $args);
				break;
			case "ClientBegin:":
				// 0:36 ClientBegin: 4
				echo("$time: ClientBegin.\r\n");
				c_begin($time, $args);
				break;
			case "ClientDisconnect:":
				// 6:09 ClientDisconnect: 8
				echo("$time: ClientDisconnect.\r\n");
				c_disconnect($time, $args);
				break;
			case "ShutdownGame:":
				// 21:26 ShutdownGame:
				echo("$time: ShutdownGame.\r\n");
				g_shutdown($time);
				break;
			case "Item:":
				// 2:32 Item: 4 ut_weapon_ump45
				echo("$time: Item.\r\n");
				#g_item($time, $args);
				break;
			case "ClientSpawn:":
				// 72:32 ClientSpawn: 16
				echo("$time: ClientSpawn.\r\n");
				#c_spawn($time, $args);
				break;
			case "SurvivorWinner:":
				// 1:58 SurvivorWinner: Red
				echo("$time: SurvivorWinner.\r\n");
				#g_winner($time, $args);
				break;
			case "Warmup:":
				// 0:00 Warmup:
				echo("$time: Warmup.\r\n");
				#g_warmup($time);
				break;
			case "InitGame:":
				// 0:00 InitGame: \sv_allowdownload\0\g_matc ... th\0\auth_status\init\g_modversion\4.2.010
				echo("$time: InitGame.\r\n");
				#g_init($time, $args);
				break;
			case "InitRound:":
				// 1:11 InitRound: \sv_allowdownload\0\g_match ... lePrecip\0\auth\1\auth_status\public\g_modversion\4.2.010
				echo("$time: InitRound.\r\n");
				#r_init($time, $args);
				break;
			case "say:":
				// 5:18 say: 4 -ANIKI-PaRaMeSHWaR: Lorem i..adasd
				echo("$time: say.\r\n");
				c_say($time, $args);
				break;
			case "sayteam:":
				// 7:08 sayteam: 6 zabijak:D: 20
				echo("$time: sayteam.\r\n");
				c_sayteam($time, $args);
				break;
			case "Hit:":
				// 4:00 Hit: 2 16 2 19: ThunderBird hit =lvl6=fMAQWRA in the Helmet
				echo("$time: Hit.\r\n");
				c_hit($time, $args);
				break;
			case "Kill:":
				// 1:58 Kill: 5 4 19: Freza killed -ANIKI-PaRaMeSHWaR by UT_MOD_LR300
				echo("$time: Kill.\r\n");
				c_kill($time, $args);
				break;
			case "Exit:":
				// 60:23 Exit: Timelimit hit.
				echo("$time: Round ended.\r\n");
				#g_exit($time, $args);
				break;
			case "red:":
				// 60:23 red:-41  blue:35
				echo("$time: Got final Score for Teams.\r\n");
				#g_score($time, $args);
				break;
			case "score:":
				// 60:23 score: 29  ping: 106  client: 16 ruben
				echo("$time: Got Score for Player.\r\n");
				#c_score($time, $args);
				break;
			case "InitAuth:":
				// 0:00 InitAuth: \auth\0\auth_status\init\auth_cheaters\1\auth_tags\1\auth_notoriety\0\auth_groups\\auth_owners\\auth_verbosity\1
				echo("$time: InitAuth.\r\n");
				#a_init($time, $args);
				break;
			case "AccountValidated:":
				// 0:03 AccountValidated: 16 -  - 0 - "0"
				// 0:07 AccountValidated: 4 - parameshwar - -1 - "basic"
				echo("$time: AccountValidated.\r\n");
				#a_valid($time, $args);
				break;
			case "AccountKick:":
				// 24:47 AccountKick: 18 - Notorcan rejected: bad challenge
				echo("$time: AccountKick.\r\n");
				#a_kick($time, $args);
				break;
			case "AccountRejected:":
				// 24:34 AccountRejected: 18 -  - "bad challenge"
				echo("$time: AccountRejected.\r\n");
				#a_reject($time, $args);
				break;
			case "Radio:":
				// 50:59 Radio: 16 - 5 - 1 - "Reception" - "Enemy spotted at Reception"
				// 51:00 Radio: 16 - 7 - 2 - "Reception" - "I'm going for the flag"
				// 51:01 Radio: 16 - 5 - 5 - "Reception" - "Incoming!"
				echo("$time: Radio.\r\n");
				#c_radio($time, $args);
				break;
			case "ClientSavePosition:":
				// 39:46 ClientSavePosition: 4 - -132.564514 - -168.874969 - -3703.875000
				echo("$time: ClientSavePosition.\r\n");
				#c_savePos($time, $args);
				break;
			case "ClientLoadPosition:":
				// 39:46 ClientLoadPosition: 4 - -132.564514 - -168.874969 - -3703.875000
				echo("$time: ClientLoadPosition.\r\n");
				#c_loadPos($time, $args);
				break;
			case "Flag:":
				// 60:37 Flag: 4 2: team_CTF_redflag
				// 60:41 Flag: 4 2: team_CTF_blueflag
				// 79:22 Flag: 6 0: team_CTF_blueflag
				echo("$time: Flag.\r\n");
				#c_flag($time, $args);
				break;
			case "FlagCaptureTime:":
				// 60:37 FlagCaptureTime: 4: 2508600
				echo("$time: FlagCaptureTime.\r\n");
				#c_flagCap($time, $args);
				break;
			default:
				echo("$time: Unknown command.\r\n");
				echo($line);
				break;
		}
	} else {
		// 8:29 Session data initialised for client on slot 0 at 155024873
		// 0:40 ------------------------------------------------------------
		echo("Unable to decode.\r\n");
		echo($line);
	}
}

?>
