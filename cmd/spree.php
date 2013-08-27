<?php

// Killing Spree

$file = "cfg/spree.cfg";
if ( !file_exists($file) ):
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
			if ( $this->players[$target]->spree >= $spree_start)
				$this->say($this->players[$target]->info["name"]."^3 stopped his/her killing spree.");
			$this->players[$target]->spree = = 0;
		}
		// Normal Kill
		elseif ($this->players[$killer]->info["team"] == 0 or $this->players[$killer]->info["team"] != $this->players[$target]->info["team"])
		{
			$this->players[$killer]->spree++;
		}
		// TeamKill
		{
			switch($spree_tk):
				case 1:		$this->players[$killer]->spree++;
							break;
				case 2:		if ( $this->players[$killer]->spree > 0)
							{
								$this->players[$killer]->spree--;
								$this->say($this->players[$killer]->info["name"]."^3 lower his/her killing spree after teamkill.");
							}
							break;
				case 3:		if ( $this->players[$killer]->spree > 0)
							{
								$this->players[$killer]->spree = 0;
								$this->say($this->players[$killer]->info["name"]."^3 reset his/her killing spree after teamkill.");
							}
							break;
				default:	break;
		}
		
		if ($this->players[$killer]->spree >= $spree_start)
			$this->say($this->players[$killer]->info["name"]."^3 is on killing spree. ^7".$this->players[$killer]->spree."^3 kills in the row.");	
		if ( $this->players[$target]->spree >= $spree_start)
			$this->say($this->players[$killer]->info["name"]."^3 stopped ".$this->players[$target]->info["name"]."^3's killing spree.");
		$this->players[$target]->spree = 0;
	}
}

?>