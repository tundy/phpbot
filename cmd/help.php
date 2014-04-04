<?php
function cmd_chat($id, $args) {
	global $players, $alt_color, $text_color;

	if( isset($args) ) {
		switch($args) {
			case "!":
			case "!!":
				tell($id, "${alt_color}!! <text>");
				tell($id, "${text_color}Will write server message with our name");
				tell($id, "${text_color}This command is available only for spectator's team");
				break;
			case "hs":
			case "!hs":
			case "headshot":
			case "!headshot":
			case "headshots":
			case "!headshots":
				tell($id, "${alt_color}!hs <name/id>");
				tell($id, "${text_color}Aliases: ${alt_color}!headshot / !headshots");
				tell($id, "${text_color}Show number of headshots that player did");
				tell($id, "${text_color}If no name or id, show your headshots");
				break;
			case "h":
			case "!h":
			case "help":
			case "!help":
				tell($id, "${text_color}Available commands:");
				tell($id, "${alt_color}!hs <name/id> ${text_color}> show number of headshots");
				tell($id, "${alt_color}!! <text> ${text_color}> Write server message if you are Spec");
				tell($id, "${alt_color}!help <cmd> ${text_color}> Show command information");
				break;
			default:
				tell($id, "${text_color}Unknown command!");
				break;
		}
	} else {
		tell($id, "${text_color}Available commands:");
		tell($id, "${alt_color}!hs <name/id> ${text_color}> show number of headshots");
		tell($id, "${alt_color}!! <text> ${text_color}> Write server message if you are Spec");
		tell($id, "${alt_color}!help <cmd> ${text_color}> Show command information");
	}
}
?>
