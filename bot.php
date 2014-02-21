<?php
$options = getopt("v::");
function debug($log_msg) {
	global $options;
	if(isset($options["v"])) {
		echo ("\r\n");
		print_r ($log_msg);
	}
}

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

#### STARTUP // START ####

// Check if this script is runned from CLI
if (PHP_SAPI !== 'cli')
	die("Start this script from console !\r\n");

// Check main bot configuration
$file = "cfg/config.php";
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// This must always work
if ( !isset($log) or empty($log) )
	die("\$log file is not set.\r\n");
if ( !file_exists($log))
	die($log." not found.\r\n");
if ( !is_readable($log))
	die("Can't read from ".$log."\r\n");
if ( !isset($rcon) or empty($rcon) )
	die("\$rcon password is not set.\r\n");

// If not set, use default instead
if ( !isset($ip) or empty($ip) ):
	echo("\$ip adress is not set.\r\n");
	echo("127.0.0.1 used instead.\r\n");
	$ip = '127.0.0.1';
endif;
if ( !isset($port) or empty($port) ):
	echo("\$port is not set.\r\n");
	echo("27960 used instead.\r\n");
	$port = 27960;
endif;
if ( !isset($prefix) or empty($prefix) ):
	echo("\$prefix is not set.\r\n");
	echo("^3 used instead.\r\n");
	$prefix = '^3';
endif;
if ( !isset($text_color) or empty($text_color) ):
	echo("\$text_color is not set.\r\n");
	echo(YELLOW_COLOR . " used instead.\r\n");
	$text_color = YELLOW_COLOR;
endif;
if ( !isset($name_color) or empty($name_color) ):
	echo("\$name_color is not set.\r\n");
	echo(WHITE_COLOR . " used instead.\r\n");
	$name_color = WHITE_COLOR;
endif;

$prefix = $prefix.$text_color;

if($text_color == WHITE_COLOR)
	$alt_color = YELLOW_COLOR;
else
	$alt_color = WHITE_COLOR;

// Check main functions list
$file = "cmd/main.php";
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// Include all other functions
foreach (glob("cmd/*.php") as $file)
	require_once $file;
unset($file);

// Include all classes
foreach (glob("class/*.php") as $file)
	require_once $file;
unset($file);

$file = "games/index.php";
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// Start loop
initialize();
loop();

#### STARTUP // END ####

function initialize() {
	global $log, $lines;
	debug("* Starting Inicialization.");
	
	$file = new SplFileObject($log);
	$lines = 0;
	
	$file->seek($lines);
	while (!$file->eof()) {
		$lines = $file->key();
		$file->current();
		if($file->valid())
			$file->next();
	}
	
	$status = rcon("status");
	if ( empty($status) )
		return false;
	$status = preg_split('/\n|\r/', $status, 0, PREG_SPLIT_NO_EMPTY);					// Change lines to array
	
	$pattern=("/map:\s+(.+)/");
	if(preg_match($pattern, $status[0], $temp)) {
		$map = $temp[1];
		unset($status[0]);
	}
	
	$g_blueteamlist = get_cvar("g_blueteamlist");
	$g_redteamlist = get_cvar("g_redteamlist");
		
	$temp_players = array();
	
	if ( !empty($g_blueteamlist) ) {
		$g_blueteamlist = str_split($g_blueteamlist);
		foreach ( $g_blueteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_BLUE;
		}
	}

	if ( !empty($g_redteamlist) ) {
		$g_redteamlist = str_split($g_redteamlist);
		foreach ( $g_redteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_RED;
		}
	}
	
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
	
	say(" ^9BOT ^1S^2t^3a^4r^5t^6e^7d ^8!");
	
	unset($temp_players);
	unset($temp);
}

#### LOOP // START ####

function loop() {
	global $log, $loop, $lines;
	global $players;
	debug("* Entered Loop.");
	
	$file = new SplFileObject($log);
	$loop = 1;

	// Loop that will scan log file forever
	while ($loop) {
		$last_line = -1;
		$file->seek($lines);
		while (!$file->eof()) {
			$lines = $file->key();
			$line = $file->current();
			if($file->valid())
				$file->next();
			else
				$last_line = $file->key();
				
			if($last_line != $lines)
				decode($line);
		}
		file_put_contents('bot.log', print_r($players, true));
	}
}

#### LOOP // END ####

#### Main Functions // START ####

// Send message to server
function out($cmd) {
	global $server, $ip, $port;
	debug("Querying Server with:");
	debug($cmd);
	
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
	while ( empty($input) ) {
		if ( $cycle++ == $cycles )
			break;
		
		fwrite ($server, $cmd);
		$temp = '';
		while ($temp = fread ($server, 1000)) {
			$input .= $temp;
		}
		if ( empty($input) )
			sleep(1);
	}
	fclose ($server);
	
	$pattern = "/\xFF\xFF\xFF\xFF.*(\n|\r)/";
	$replacement = "";
	$input = preg_replace($pattern, $replacement, $input);
	
	if ( empty($input) )
		return false;	
	debug("GOT:");
	debug( trim($input) );
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
	if ( count($temp) < 2 ) {
		$pattern = "/\".+\"\s+is:\"(.*)\^7\"/";
		preg_match($pattern, $subject, $temp);
	}

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
	global $prefix, $sufix;
	return (rcon("say ".$prefix.$msg.$sufix));
}

// send private message to player
function tell($id, $msg) {
	global $prefix, $sufix;
	return (rcon("tell ".$id." ".$prefix.$msg.$sufix));
}
// write message in console
function write($msg) {
	global $prefix, $sufix;
	return (rcon($prefix.$msg.$sufix));
}

// Input:
// 43:45 ClientConnect: 1
// Output:
// [1] = Time in seconds	(2625)
// [2] = Command			('ClientConnect:')
// [3] = Arguments			('1')
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
		return $grep;
	}
}

function decode($line) {
	debug("_____________________");
	debug("New Line in Log File.");
	if($temp = grep_logline($line)) {
		$time	= $temp[1];
		$cmd	= $temp[2];
		if(isset($temp[3]))
			$args	= $temp[3];			
		switch($cmd) {
			case "ClientConnect:":
				debug("\tClientConnect.");
				c_connect($time, $args);
				break;
			case "ClientUserinfo:":
				debug("\tClientUserinfo.");
				c_info($time, $args);
				break;
			case "ClientUserinfoChanged:":
				debug("\tClientUserinfoChanged.");
				c_changed($time, $args);
				break;
			case "ClientBegin:":
				debug("\tClientBegin.");
				c_begin($time, $args);
				break;
			case "ClientDisconnect:":
				debug("\tClientDisconnect.");
				c_disconnect($time, $args);
				break;
			case "ShutdownGame:":
				debug("\tShutdownGame.");
				g_shutdown($time);
				break;
			case "Item:":
				debug("\tItem.");
				#g_item($time, $args);
				break;
			case "ClientSpawn:":
				debug("\tClientSpawn.");
				#c_spawn($time, $args);
				break;
			case "SurvivorWinner:":
				debug("\tSurvivorWinner.");
				#g_winner($time, $args);
				break;
			case "Warmup:":
				debug("\tWarmup.");
				#g_warmup($time);
				break;
			case "InitAuth:":
				debug("\tInitAuth.");
				#a_init($time, $args);
				break;
			case "InitGame:":
				debug("\tInitGame.");
				#g_init($time, $args);
				break;
			case "InitRound:":
				debug("\tInitRound.");
				#r_init($time, $args);
				break;
			case "say:":
				debug("\tsay.");
				c_say($time, $args);
				break;
			case "sayteam:":
				debug("\tsayteam.");
				c_sayteam($time, $args);
				break;
			case "Hit:":
				debug("\tHit.");
				c_hit($time, $args);
				break;
			case "Kill:":
				debug("\tKill.");
				c_kill($time, $args);
				break;
			default:
				debug("\tUnknown.");
				debug("\t\t$line");
				break;
		}
	}
}

#### Main Functions // END ####
?>
