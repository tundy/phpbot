<?php

// Killing Spree

$file = "cfg/spree.php";
if ( !file_exists($file) )
	die("'$file' file not found.\r\n");
require_once($file);
unset($file);

function higest_spree ($killer, $target)
{
	global $players;
	
	if ( !isset($players[$killer]->spree->kill->high) )
		$players[$killer]->spree->kill->high = $players[$killer]->spree->kill->last;
	elseif ($players[$killer]->spree->kill->last > $players[$killer]->spree->kill->high)
		$players[$killer]->spree->kill->high = $players[$killer]->spree->kill->last;
		
	if ( !isset($players[$target]->spree->dead->high) )
		$players[$target]->spree->dead->high = $players[$target]->spree->dead->last;
	elseif ($players[$target]->spree->dead->last > $players[$target]->spree->dead->high)
		$players[$target]->spree->dead->high = $players[$target]->spree->dead->last;
}

function spree ($time, $args)
{
	global $players, $spree_start, $spree_tk;

	if($grep = grep_kill($args))
	{
		unset($args);
		$killer =	$grep[1];
		$target =	$grep[2];
		$weapon =	$grep[3];
		unset($grep);
		
		// Change World feature to SelfKill
		if ($killer == WORLD)
			$killer = $target;
			
		// Self Kill
		if($killer == $target && $weapon != UT_MOD_FLAG)
		{
			if ( $players[$target]->spree->kill->last >= $spree_start)
				say($players[$target]->info["name"]."^3 stopped his/her killing spree.");
			$players[$target]->spree->dead->last++;
			higest_spree($killer, $target);
			$players[$killer]->spree->kill->last = 0;
		}
		// Normal Kill
		elseif ($players[$killer]->info["team"] == TEAM_FFA or $players[$killer]->info["team"] != $players[$target]->info["team"])
		{
			$players[$target]->spree->dead->last++;
			$players[$killer]->spree->kill->last++;
			$players[$killer]->spree->dead->last = 0;
			higest_spree($killer, $target);
		}
		// TeamKill
		else{
			switch($spree_tk):
				case 1:		$players[$killer]->spree->kill->last++;
							$players[$target]->spree->dead->last++;
							higest_spree($killer, $target);
							break;
				case 2:		if ( $players[$killer]->spree->kill->last > 0)
							{
								$players[$killer]->spree->kill->last--;
								say($players[$killer]->info["name"]."^3 lower his/her killing spree after teamkill.");
							}
							$players[$target]->spree->dead->last++;
							higest_spree($killer, $target);
							break;
				case 3:		if ( $players[$killer]->spree->kill->last > 0)
							{
								$players[$killer]->spree->kill->last = 0;
								say($players[$killer]->info["name"]."^3 reset his/her killing spree after teamkill.");
							}
							$players[$target]->spree->dead->last++;
							higest_spree($killer, $target);
							break;
				default:	break;
			endswitch;
		}
		
		if ($players[$killer]->spree->kill->last >= $spree_start)
			say($players[$killer]->info["name"]."^3 is on killing spree. ^7".$players[$killer]->spree->kill->last."^3 kills in the row.");	
		if ( $players[$target]->spree->kill->last >= $spree_start)
			say($players[$killer]->info["name"]."^3 stopped ".$players[$target]->info["name"]."^3's killing spree.");
		$players[$target]->spree->kill->last = 0;
	}
}

?>