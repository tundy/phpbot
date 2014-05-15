<?php
if( !function_exists('bots_vs_players_forceteam') ) {

	$file = "cfg/bots_vs_players.php";
	if( !file_exists($file))
		echo("'${file}' not found.\r\n");
	echo("Including ${file}.\r\n");
	if( (include_once $file) === false )
		debug('show');
	unset($file);

	function bots_vs_players_forceteam($arg) {
		global $BOT_TEAM, $PLAYER_TEAM;
		global $clients;

		if(!isset($BOT_TEAM)) {
			if(!isset($PLAYER_TEAM)) {
				// default settings
				$BOT_TEAM = TEAM_RED;
				$PLAYER_TEAM = TEAM_BLUE;
			} elseif($PLAYER_TEAM == TEAM_RED) {
				$BOT_TEAM = TEAM_BLUE;
			} else {
				$BOT_TEAM = TEAM_RED;
				$PLAYER_TEAM = TEAM_BLUE;
			}
		}

		if($grep = grep_user($arg)) {
			$id = $grep["id"];
			unset($grep);
			unset($arg);
		}

		if ( isset($clients[$id]) ) {
			if ($clients[$id]->info["team"] == $PLAYER_TEAM) {
				if (is_bot($id)) {
					forceteam($id, $BOT_TEAM);
				}
			}
			if ($clients[$id]->info["team"] == $BOT_TEAM) {
				if (is_player($id)) {
					forceteam($id, $PLAYER_TEAM);
				}
			}
		}
	}
}

switch($cmd) {
	case "ClientUserinfoChanged:":
		bots_vs_players_forceteam($args);
		break;
}
?>