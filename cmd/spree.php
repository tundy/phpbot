<?php

// Killing Spree

$file = "cfg/spree.php";
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

function spree ($time, $args)
{
	global $spree_start, $spree_tk;

	if($grep = $this->grep_kill($arg))
	{
		unset($arg);
		$killer =	$grep[1];
		$target =	$grep[2];
		$weapon =	$grep[3];
		unset($grep);
		
		// Change World feature to SelfKill
		if ($killer == WORLD)
			$killer = $target;
			
		// Self Kill
		if($killer == $target)
		{
			if ( $players[$target]->spree >= $spree_start)
				say($players[$target]->info["name"]."^3 stopped his/her killing spree.");
			$players[$target]->spree = 0;
		}
		// Normal Kill
		elseif ($players[$killer]->info["team"] == 0 or $players[$killer]->info["team"] != $players[$target]->info["team"])
		{
			$players[$killer]->spree++;
		}
		// TeamKill
		{
			switch($spree_tk):
				case 1:		$players[$killer]->spree++;
							break;
				case 2:		if ( $players[$killer]->spree > 0)
							{
								$players[$killer]->spree--;
								say($players[$killer]->info["name"]."^3 lower his/her killing spree after teamkill.");
							}
							break;
				case 3:		if ( $players[$killer]->spree > 0)
							{
								$players[$killer]->spree = 0;
								say($players[$killer]->info["name"]."^3 reset his/her killing spree after teamkill.");
							}
							break;
				default:	break;
			endswitch;
		}
		
		if ($players[$killer]->spree >= $spree_start)
			say($players[$killer]->info["name"]."^3 is on killing spree. ^7".$players[$killer]->spree."^3 kills in the row.");	
		if ( $players[$target]->spree >= $spree_start)
			say($players[$killer]->info["name"]."^3 stopped ".$players[$target]->info["name"]."^3's killing spree.");
		$players[$target]->spree = 0;
	}
}

?>