﻿<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

#### STARTUP // START ####

// Check if this script is runned from CLI
if (PHP_SAPI !== 'cli')
	die("Start this script from console !\r\n");

// Check main bot configuration
$config = "cfg/config.php";
if ( !file_exists($config) ):
	die("'$config' file not found.\r\n");
require_once($config);
unset($config);

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
	
#### STARTUP // END ####

#### LOOP // START ####
	
function loop()
{
	global $log, $loop;

	$file = new SplFileObject($log);
	$lines = 0;
	$loop = 1;
	$first = 1;

	// Loop that will scan log file forever
	while ($loop) 
	{
		$file->seek($lines);
		while (!$file->eof())
		{
			$lines = $file->key();
			$line = $file->current();
			if($file->valid())
				$file->next();
			else
				$last_line = $file->key();
				
			if( isset($first) )	// Do not decode old logs
				unset($first);
			elseif($last_line != $lines)
				decode($line);
		}
		$last_line = -1;
		
		$log = get_object_vars($this);
		$fh = fopen("./logfile.log", 'w');
		fwrite($fh, print_r($log, TRUE));
		fclose($fh);
			$this->flush();
		$this->wait();
	}
}

#### LOOP // END ####

#### Main Functions // START ####

// Send message to server
function out($cmd)
{
	global $server, $ip, $port;
	$errno = null;
	$errstr = null;
	$cmd = "˙˙˙˙" . $cmd;
	$server = fsockopen('udp://' . $ip, $port, $errno, $errstr, 1);
	if (!$server)
		die ("Unable to connect. Error $errno - $errstr\n");
	socket_set_timeout ($this->server, 1, 0);
	fwrite ($server, $cmd);
	$input = '';
	while ($temp = fread ($this->server, 10000))
	{
		$input .= $temp;
	}
	fclose ($this->server);
	return $input;
}

// send command to server
function rcon($cmd)
{
	global $rcon;
	return (out("rcon ".$rcon." ".$cmd));
}

// send message to chat
function say($msg)
{
	global $prefix, $sufix;
	return (rcon("say ".$prefix.$msg.$sufix));
}	

// write message in console
function write($msg)
{
	global $prefix, $sufix;
	return (rcon($prefix.$msg.$sufix));
}

// Input:
// 43:45 ClientConnect: 1
// Output:
// [1] = Time in seconds	(2625)
// [2] = Command			('ClientConnect:')
// [3] = Arguments			('1')
function grep_logline($line)
{
	$pattern=("/([0-9]+:[0-9]{2})([a-zA-Z ]+:)(.*)/");
	if(preg_match($pattern, $line, $grep))
	{
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

function decode($line)
{
	if($temp = grep_logline($line))
	{
		$time	= $temp[1];
		$cmd	= $temp[2];
		if(isset($temp[3]))
			$args	= $temp[3];
		
		switch($cmd)
		{
			#case "ClientConnect:":
			#	c_connect($time, $args);
			#	break;
			#case "ClientUserinfo:":
			#	c_info($time, $args);
			#	break;
			#case "ClientUserinfoChanged:":
			#	c_info($time, $args);
			#	break;
			#case "ClientBegin:":
			#	c_begin($time, $args);
			#	break;
			#case "ClientDisconnect:":
			#	c_disconnect($time, $args);
			#	break;
			#case "ShutdownGame:":
			#	g_shutdown($time);
			#	break;
			#case "SurvivorWinner:":
			#	g_winner($time, $args);
			#	break;
			#case "Warmup:":
			#	g_warmup($time);
			#	break;
			#case "InitAuth:":
			#	a_init($time, $args);
			#	break;
			#case "InitGame:":
			#	g_init($time, $args);
			#	break;
			#case "InitRound:":
			#	r_init($time, $args);
			#	break;
			#case "say:":
			#	c_say($time, $args);
			#	break;
			#case "sayteam:":
			#	c_sayteam($time, $args);
			#	break;
			#case "Hit:":
			#	c_hit($time, $args);
			#	break;
			#case "Kill:":
			#	c_kill($time, $args);
			#	break;
			default:
				break;
		}
	}
}

#### Main Functions // END ####

// Check main functions list
$file = "cmd/main.php";
if ( !file_exists($file) ):
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

// Include all other functions
foreach (glob("cmd/*.php") as $file)
	require_once $file;
unset($file);

?>