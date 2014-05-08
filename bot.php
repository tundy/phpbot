<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

	if(PHP_SAPI !== 'cli')
		die("Start this script from console!\r\n");

	$plugins = array();
	$plugins[] = 'main.php';

	ob_start();
	
	$file = "cfg/config.php";
	echo("Including ${file}.\r\n");
	if( !file_exists($file) )
		echo("\r\n'${file}' file not found.\r\n");	
	if( (include_once $file) === false ):
		$logfile = 'bot.log';
		debug('die');
	endif;
	unset($file);
	debug();

	if( !isset($logfile) or empty($logfile) ):
		$logfile = 'bot.log';
		echo("\$logfile adress is not set.\r\n");
		echo("'bot.log' used instead.\r\n");
		debug('show');
	endif;

	file_put_contents($logfile, '');

	$file = "cmd.php";
	echo("Including ${file}.\r\n");
	if( !file_exists($file) )
		echo("'${file}' file not found.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);

	$file = "cfg/colors.php";
	echo("Including ${file}.\r\n");
	if( !file_exists($file))
		echo("'${file}' not found.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);

	echo("Trying to load Server Log.\r\n");
	debug();

	if( !isset($log) or empty($log) ):
		echo("Server '\$log' is not set.\r\n");
		debug('die');
	endif;

	if( !file_exists($log)):
		echo("'${log}' not found.\r\n");
		debug('die');
	endif;

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
	debug();
	if( !isset($port) or empty($port) ):
		echo("\$port is not set.\r\n");
		echo("27960 used instead.\r\n");
		$port = 27960;
	endif;
	debug();
	if( !isset($say_prefix) or empty($say_prefix) ):
		echo("\$say_prefix is not set.\r\n");
		echo("'^0[^8B^0]^9: ' used instead.\r\n");
		$say_prefix = '^0[^8B^0]^9: ';
	endif;
	debug();
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

	$file = "cmd.php";
	echo("Including ${file}.\r\n");
	if( !file_exists($file) )
		echo("'${file}' file not found.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);
	debug();

	echo("Loading Classes.\r\n");
	debug();
	// Include all classes
	foreach (glob("class/*.php") as $file):
		echo("Including ${file}.\r\n");
		if( (include_once $file) === false )
			debug('die');
		else
			debug();		
	endforeach;
	unset($file);

	echo("Checking Game Version.\r\n");
	$file = "games/index.php";
	if( !file_exists($file) )
		echo("'${file}' file not found.\r\n");
	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('die');
	unset($file);
	debug();

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
	global $clients, $TEAM;

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

	echo("Adding already connected clients into memmory.\r\n");
	$status = rcon("status");
	if ( empty($status) )
		return false;
	$status = preg_split('/\n|\r/', $status, 0, PREG_SPLIT_NO_EMPTY);					// Change lines to array

	$pattern=("/map:\s+(.+)/");
	if(preg_match($pattern, $status[0], $temp)):
		$map = $temp[1];
		unset($status[0]);
	endif;

	$temp_client_team = array();

	echo("Searching for Red & Blue Members.\r\n");
	$g_blueteamlist = get_cvar("g_blueteamlist");
	if ( !empty($g_blueteamlist) ) {
		$g_blueteamlist = str_split($g_blueteamlist);
		foreach ( $g_blueteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_client_team[$id] = TEAM_BLUE;
		}
	}

	$g_redteamlist = get_cvar("g_redteamlist");
	if ( !empty($g_redteamlist) ) {
		$g_redteamlist = str_split($g_redteamlist);
		foreach ( $g_redteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_client_team[$id] = TEAM_RED;
		}
	}

	foreach ($status as $client) {
		$pattern=("/(\d+)\s+([-]*\d+)\s+(\d+)\s+(.*)\s+(\d+)\s+(.+)\s+(\d+)\s+(\d+).*/");
		if(preg_match($pattern, $client, $temp)) {
			$id = trim($temp[1]);
			$score = trim($temp[2]);
			$ping = trim($temp[3]);
			$name = trim($temp[4]);
			$lastmsg = trim($temp[5]);
			$address = trim($temp[6]);
			$qport = trim($temp[7]);
			$rate = trim($temp[8]);
			if ( !isset($temp_client_team[$id]) )
				$team = TEAM_SPEC;
			else
				$team = $temp_client_team[$id];
			unset($temp_client_team[$id]);
			
			echo("Creating client[${id}] | ${name} | ".$TEAM[$team]."\r\n");
			$clients[$id] = (new client);
			$clients[$id]->info["name"] = $name;
			$clients[$id]->info["n"] = $name;
			$clients[$id]->info["team"] = $team;
			$clients[$id]->info["score"] = $score;
			$clients[$id]->info["lastmsg"] = $lastmsg;
			$clients[$id]->info["address"] = $address;
			$clients[$id]->info["qport"] = $qport;
			$clients[$id]->info["rate"] = $rate;
			$clients[$id]->hello = 1;
		}
	}

	echo("Sending message to server that BOT is online.\r\n");
	say(" ^9BOT ^1S^2t^3a^4r^5t^6e^7d ^8!");

	unset($temp_client_team);
	unset($temp);

	debug();
}

// Scanning Server Log
function bot_loop() {
	global $log, $loop, $lines;
	global $clients;
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
		file_put_contents('clients.log', print_r($clients, true));
		#file_put_contents('clients.log', print_r($clients, true), FILE_APPEND);
	endwhile;
}

// Decode commands from log
function decode($line) {
	global $time, $cmd, $args;
	global $alt_color, $text_color;
	global $clients, $HELP;
	global $plugins;

	if($temp = grep_logline($line)) {
		$time = $temp[1];
		$cmd = $temp[2];
		if( isset($temp[3]) )
			$args = $temp[3];
		else
			$args = null;
	} elseif($temp = grep_logline_extra($line)) {
		$time = $temp[1];
		$cmd = $temp[2];
		if( isset($temp[3]) )
			$args = $temp[3];
		else
			$args = null;
	} else {
		return false;
	}
	foreach($plugins as $plugin)
		include($plugin);
	return true;
}

?>
