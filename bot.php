<?php
$options = getopt("q::v::");
// q = be quiet
// v = verbose

// $lvl = how many v options you have to add for verbose
function debug($verbose="DEBUG HIT", $lvl=0) {
	global $options;
	if( !isset($options["q"]) ) {
		if( $lvl == 0 ) {
				echo ($lvl . ") ");
				print_r ($verbose);
				echo ("\r\n");
		} elseif(isset($options["v"])) {
			if( is_array($options["v"]) ) {
				if( count($options["v"]) >= $lvl ) {
					echo ($lvl . ") ");
					print_r ($verbose);
					echo ("\r\n");
				}
			} elseif( $lvl == 1 ) {
				echo ($lvl . ") ");
				print_r ($verbose);
				echo ("\r\n");
			}
		}
	}
}

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

#### STARTUP // START ####

// Check if this script is runned from CLI
debug("Checking if you are running script from CLI.", 2);
if (PHP_SAPI !== 'cli')
	die("Start this script from console !\r\n");

debug("Loading Configurations.");
// Check main bot configuration
$file = "cfg/config.php";
debug("Loading $file.", 2);
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// Check colors definitions
$file = "cfg/colors.php";
debug("Loading $file.", 2);
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

debug("Searching for Log File.");
// This must always work
debug("Is set $log?", 2);
if ( !isset($log) or empty($log) )
	die("\$log file is not set.\r\n");
debug("Exists $log?", 2);
if ( !file_exists($log))
	die($log." not found.\r\n");
debug("Is readable $log?", 2);
if ( !is_readable($log))
	die("Can't read from ".$log."\r\n");
debug("Is rcon password set?", 2);
if ( !isset($rcon) or empty($rcon) )
	die("\$rcon password is not set.\r\n");

// If not set, use default instead
if ( !isset($ip) or empty($ip) ):
	debug("\$ip adress is not set.");
	debug("127.0.0.1 used instead.");
	$ip = '127.0.0.1';
endif;
if ( !isset($port) or empty($port) ):
	debug("\$port is not set.");
	debug("27960 used instead.");
	$port = 27960;
endif;
if ( !isset($say_prefix) or empty($say_prefix) ):
	debug("\$say_prefix is not set.");
	debug("^0[^8B^0]^9: used instead.");
	$say_prefix = '^0[^8B^0]^9: ';
endif;
if ( !isset($tell_prefix) or empty($tell_prefix) ):
	debug("\$tell_prefix is not set.");
	debug("^0[^8PM^0]^9: used instead.");
	$tell_prefix = '^0[^8PM^0]^9: ';
endif;

$text_color = YELLOW_COLOR;
$alt_color = WHITE_COLOR;
$say_prefix = $say_prefix.$text_color;
$tell_prefix = $tell_prefix.$text_color;
rcon("set sv_sayprefix \"" . $say_prefix . "\"");
rcon("set sv_tellprefix \"" . $tell_prefix . "\"");
unset($say_prefix);
unset($tell_prefix);

debug("Loading Functions.");
// Check main functions list
$file = "cmd/main.php";
debug("Loading $file.", 2);
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// Include all other functions
foreach (glob("cmd/*.php") as $file) {
	debug("Loading $file.", 2);
	require_once $file;
}
unset($file);

debug("Loading Classes.");
// Include all classes
foreach (glob("class/*.php") as $file)
	require_once $file;
unset($file);

debug("Checking Game Version.");
$file = "games/index.php";
debug("Loading $file.", 2);
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
	debug("Starting Inicialization.");
	
	$file = new SplFileObject($log);
	$lines = 0;
	
	debug("Seeking to end of Log File.", 1);
	$file->seek($lines);
	while (!$file->eof()) {
		$lines = $file->key();
		$file->current();
		if($file->valid())
			$file->next();
	}
	debug("Last line is $lines.", 2);
	
	debug("Adding already connected players into memmory.", 1);
	$status = rcon("status");
	if ( empty($status) )
		return false;
	$status = preg_split('/\n|\r/', $status, 0, PREG_SPLIT_NO_EMPTY);					// Change lines to array
	
	$pattern=("/map:\s+(.+)/");
	if(preg_match($pattern, $status[0], $temp)) {
		$map = $temp[1];
		unset($status[0]);
	}
	
	$temp_players = array();
	
	debug("Searcching for Blue Members.", 2);
	$g_blueteamlist = get_cvar("g_blueteamlist");	
	if ( !empty($g_blueteamlist) ) {
		$g_blueteamlist = str_split($g_blueteamlist);
		foreach ( $g_blueteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_BLUE;
		}
	}

	debug("Searcching for Red Members.", 2);
	$g_redteamlist = get_cvar("g_redteamlist");
	if ( !empty($g_redteamlist) ) {
		$g_redteamlist = str_split($g_redteamlist);
		foreach ( $g_redteamlist as $member) {
			$id = ( ord($member) - ord('A') );
			$temp_players[$id] = TEAM_RED;
		}
	}
	
	debug("Requesting status from server.", 2);
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
			debug("Adding already connected player $id into memmory.", 2);
			c_create($id, $name, $team);
		}
	}
	
	debug("Sending message to server that BOT is online.");
	say(" ^9BOT ^1S^2t^3a^4r^5t^6e^7d ^8!");
	
	unset($temp_players);
	unset($temp);
}

#### LOOP // START ####

function loop() {
	global $log, $loop, $lines;
	global $players;
	debug("Entered Loop.");
	
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

		// Just Debug stuff
		file_put_contents('bot.log', print_r($players, true));
	}
}

#### LOOP // END ####

#### Main Functions // START ####

// Send message to server
function out($cmd) {
	global $server, $ip, $port;
	debug("Querying Server with:", 1);
	debug($cmd, 1);
	
	$errno = null;
	$errstr = null;
	$cmd = "\xFF\xFF\xFF\xFF" . $cmd;										// Every query must start with 4 chars 0xFF
	debug("Real query to Server:", 3);
	debug($cmd, 3);
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
	
	debug("Real answer from server:", 3);
	debug($input, 3);
	$pattern = "/\xFF\xFF\xFF\xFF.*(\n|\r)/";
	$replacement = "";
	$input = preg_replace($pattern, $replacement, $input);
	
	if ( empty($input) )
		return false;	
	debug("Answer from server:", 1);
	debug(trim($input), 1);
	return trim($input);
}

// get cvar value from server
function get_cvar ($cvar) {	
	global $rcon;
	debug("get_cvar(${cvar})", 2);
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
	debug("rcon(${cmd})", 3);
	return (out("rcon ".$rcon." ".$cmd));
}

// send message to chat
function say($msg) {
	debug("say(${msg})", 2);
	return (rcon("say ".$msg));
}

// send private message to player
function tell($id, $msg) {
	debug("tell(${id}, ${msg})", 2);
	return (rcon("tell ".$id." ".$msg));
}
// write message in console
function write($msg) {
	global $text_color;
	debug("write(${msg})", 2);
	return (rcon($text_color.$msg));
}

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

function decode($line) {
	debug("_____________________", 1);
	debug("New Line in Log File.", 2);
	debug($line, 4);
	if($temp = grep_logline($line)) {
		$time	= $temp[1];
		$cmd	= $temp[2];
		if(isset($temp[3]))
			$args	= $temp[3];			
		switch($cmd) {
			case "ClientConnect:":
				// 0:06 ClientConnect: 4
				debug("$time: ClientConnect.", 1);
				c_connect($time, $args);
				break;
			case "ClientUserinfo:":
				//  0:06 ClientUserinfo: 4 \ip\188.120.11.151 ... \weapmodes\000001112200000200020
				debug("$time: ClientUserinfo.", 1);
				c_info($time, $args);
				break;
			case "ClientUserinfoChanged:":
				// 0:06 ClientUserinfoChanged: 4 n\-ANIKI-PaRaMeSHWaR\t\3\r\1\tl\0\a0\151\a1\151\a2\0
				debug("$time: ClientUserinfoChanged.", 1);
				c_changed($time, $args);
				break;
			case "ClientBegin:":
				// 0:36 ClientBegin: 4 
				debug("$time: ClientBegin.", 1);
				c_begin($time, $args);
				break;
			case "ClientDisconnect:":
				// 6:09 ClientDisconnect: 8
				debug("$time: ClientDisconnect.", 1);
				c_disconnect($time, $args);
				break;
			case "ShutdownGame:":
				// 21:26 ShutdownGame:
				debug("$time: ShutdownGame.", 1);
				g_shutdown($time);
				break;
			case "Item:":
				// 2:32 Item: 4 ut_weapon_ump45
				debug("$time: Item.", 1);
				#g_item($time, $args);
				break;
			case "ClientSpawn:":
				// 72:32 ClientSpawn: 16
				debug("$time: ClientSpawn.", 1);
				#c_spawn($time, $args);
				break;
			case "SurvivorWinner:":
				// 1:58 SurvivorWinner: Red
				debug("$time: SurvivorWinner.", 1);
				#g_winner($time, $args);
				break;
			case "Warmup:":
				// 0:00 Warmup:
				debug("$time: Warmup.", 1);
				#g_warmup($time);
				break;
			case "InitGame:":
				// 0:00 InitGame: \sv_allowdownload\0\g_matc ... th\0\auth_status\init\g_modversion\4.2.010
				debug("$time: InitGame.", 1);
				#g_init($time, $args);
				break;
			case "InitRound:":
				// 1:11 InitRound: \sv_allowdownload\0\g_match ... lePrecip\0\auth\1\auth_status\public\g_modversion\4.2.010
				debug("$time: InitRound.", 1);
				#r_init($time, $args);
				break;
			case "say:":
				// 5:18 say: 4 -ANIKI-PaRaMeSHWaR: Lorem i..adasd
				debug("$time: say.", 1);
				c_say($time, $args);
				break;
			case "sayteam:":
				// 7:08 sayteam: 6 zabijak:D: 20
				debug("$time: sayteam.", 1);
				c_sayteam($time, $args);
				break;
			case "Hit:":
				// 4:00 Hit: 2 16 2 19: ThunderBird hit =lvl6=fMAQWRA in the Helmet
				debug("$time: Hit.", 1);
				c_hit($time, $args);
				break;
			case "Kill:":
				// 1:58 Kill: 5 4 19: Freza killed -ANIKI-PaRaMeSHWaR by UT_MOD_LR300
				debug("$time: Kill.", 1);
				c_kill($time, $args);
				break;
			case "Exit:":
				// 60:23 Exit: Timelimit hit.
				debug("$time: Round ended.", 1);
				#g_exit($time, $args);
				break;
			case "red:":
				// 60:23 red:-41  blue:35
				debug("$time: Got final Score for Teams.", 1);
				#g_score($time, $args);
				break;
			case "score:":
				// 60:23 score: 29  ping: 106  client: 16 ruben
				debug("$time: Got Score for Player.", 1);
				#c_score($time, $args);
				break;
			case "InitAuth:":
				// 0:00 InitAuth: \auth\0\auth_status\init\auth_cheaters\1\auth_tags\1\auth_notoriety\0\auth_groups\\auth_owners\\auth_verbosity\1
				debug("$time: InitAuth.", 1);
				#a_init($time, $args);
				break;
			case "AccountValidated:":
				// 0:03 AccountValidated: 16 -  - 0 - "0"
				// 0:07 AccountValidated: 4 - parameshwar - -1 - "basic"
				debug("$time: AccountValidated.", 1);
				#a_valid($time, $args);
				break;
			case "AccountKick:":
				// 24:47 AccountKick: 18 - Notorcan rejected: bad challenge
				debug("$time: AccountKick.", 1);
				#a_kick($time, $args);
				break;
			case "AccountRejected:":
				// 24:34 AccountRejected: 18 -  - "bad challenge"
				debug("$time: AccountRejected.", 1);
				#a_reject($time, $args);
				break;
			case "Radio:":
				// 50:59 Radio: 16 - 5 - 1 - "Reception" - "Enemy spotted at Reception"
				// 51:00 Radio: 16 - 7 - 2 - "Reception" - "I'm going for the flag"
				// 51:01 Radio: 16 - 5 - 5 - "Reception" - "Incoming!"
				debug("$time: Radio.", 1);
				#c_radio($time, $args);
				break;
			case "ClientSavePosition:":
				// 39:46 ClientSavePosition: 4 - -132.564514 - -168.874969 - -3703.875000
				debug("$time: ClientSavePosition.", 1);
				#c_savePos($time, $args);
				break;
			case "ClientLoadPosition:":
				// 39:46 ClientLoadPosition: 4 - -132.564514 - -168.874969 - -3703.875000
				debug("$time: ClientLoadPosition.", 1);
				#c_loadPos($time, $args);
				break;
			case "Flag:":
				// 60:37 Flag: 4 2: team_CTF_redflag
				// 60:41 Flag: 4 2: team_CTF_blueflag
				// 79:22 Flag: 6 0: team_CTF_blueflag
				debug("$time: Flag.", 1);
				#c_flag($time, $args);
				break;
			case "FlagCaptureTime:":
				// 60:37 FlagCaptureTime: 4: 2508600
				debug("$time: FlagCaptureTime.", 1);
				#c_flagCap($time, $args);
				break;
			default:
				debug("$time: Unknown.");
				debug($line);
				break;
		}
	} else {
		// 8:29 Session data initialised for client on slot 0 at 155024873
		// 0:40 ------------------------------------------------------------
		debug("Unknown.", 1);
		debug($line, 1);
	}
}

#### Main Functions // END ####
?>
