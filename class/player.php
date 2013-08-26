<?php

class player
{
	public $info, $kills, $deads, $hits, $dmg, $rounds, /*$games,*/ $spree, $flags;
	
	function player ()
	{
		$this->kills		= (new kills);
		$this->deads		= (new deads);
		$this->hits			= (new hits);
		$this->dmg			= (new hits);
		unset($this->dmg->part);
		$this->rounds		= (new rounds);
		#$this->games		= (new games);
		$this->flags		= (new flags);
		$this->spree		= (new spree);
		$this->info			= array();
		$this->info["n"]	= &$this->info["name"];
		$this->info["t"]	= &$this->info["team"];
	}	
}

class kills
{
	public $target, $weapon, $team, $self, $enemy;
	
	function kills ()
	{
		$this->target	= array();		// Kills on specific player
		$this->weapon	= array();		// Kills with specific weapon
		$this->team		= 0;			// Team Kills
		$this->self		= 0;			// Self Kills
		$this->enemy	= 0;			// Normal Kills
	}
}

class deads
{
	public $killer, $weapon, $team, $self, $enemy;
		
	function deads ()
	{
		$this->killer	= array();		// Killed by ...
		$this->weapon	= array();		// Killed with ...
		$this->team		= 0;			// Team Deads
		$this->self		= 0;			// Self and World Kills
		$this->enemy	= 0;			// Normal Deads
	}
}

class hits
{
	public $enemy, $team, $target, $weapon, $part;

	function hits ()
	{
		$this->enemy->got	= 0;		// How many times was hit by enemy
		$this->enemy->hit	= 0;		// How many times hit enemy
		$this->team->got	= 0;		// How many times was hit by team mate
		$this->team->hit	= 0;		// How many times hit team mate
		$this->target->got	= array();	// Hit by specific player
		$this->target->hit	= array();	// Hit specific player
		$this->weapon->got	= array();	// Hit by specific weapon
		$this->weapon->hit	= array();	// Hit with specific weapon
		$this->part->got	= array();	// Was hit to specific part of body
		$this->part->hit	= array();	// Hit to specific part of body
	}
}

class spree
{
	public $kill, $dead, $flag;
		
	function deads ()
	{
		$this->kill->last		= 0;			// Killing spree
		$this->kill->high		= 0;			// Killing best
		$this->dead->last		= 0;			// Dead spree (longest)
		$this->dead->high		= 0;			// Dead spree worst (longest)
		$this->flag->save		= 0;			// Saved Flags without dead
		$this->flag->capture	= 0;			// Captured Flags without dead
	}
}

class flags
{
	public $captured, $save;
		
	function deads ()
	{
		$this->captured		= 0;			// Captured flags
		$this->saved		= 0;			// Saved flags
	}
}

class rounds
{
	public $won, $draw, $lost;
		
	function deads ()
	{
		$this->won		= 0;			// Won games
		$this->lost		= 0;			// Lost games
		$this->draw		= 0;			// Draw games
}

?>