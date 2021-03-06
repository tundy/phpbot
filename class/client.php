<?php
class client {
	public $info, $kills, $deads, $hits, $dmg, $rounds, /*$games,*/ $flags;

	function client() {
		$this->kills		= (new kills);
		$this->deads		= (new deads);
		$this->hits			= (new hits);
		$this->dmg			= (new hits);
		$this->rounds		= (new rounds);
		#$this->games		= (new games);
		$this->flags		= (new flags);
		$this->info			= array();
		#$this->info["name"]	= "";
		#$this->info["n"]		= &$this->info["name"];
		$this->info["team"]		= 3;
		$this->info["t"]		= &$this->info["team"];
		$this->info["ip"]		= "";
		$this->info["address"]	= &$this->info["ip"];
	}
}
class kills {
	public /*$target, $weapon,*/ $team, $self, $enemy;

	function kills() {
		#$this->target	= array();		// Kills on specific client
		#$this->weapon	= array();		// Kills with specific weapon
		$this->team		= 0;			// Team Kills
		$this->self		= 0;			// Self Kills
		$this->enemy	= 0;			// Normal Kills
	}
}
class deads {
	public /*$killer, $weapon,*/ $team, $self, $enemy;

	function deads() {
		#$this->killer	= array();		// Killed by ...
		#$this->weapon	= array();		// Killed with ...
		$this->team		= 0;			// Team Deads
		$this->self		= 0;			// Self and World Kills
		$this->enemy	= 0;			// Normal Deads
	}
}
class hits {
	public $enemy, $team/*, $target, $weapon, $part*/;

	function hits() {
		$this->enemy		= new stdClass();
		$this->enemy->got	= 0;		// How many times was hit by enemy
		$this->enemy->hit	= 0;		// How many times hit enemy
		$this->team			= new stdClass();
		$this->team->got	= 0;		// How many times was hit by team mate
		$this->team->hit	= 0;		// How many times hit team mate
		/*$this->target		= new stdClass();
		$this->target->got	= array();	// Hit by specific client
		$this->target->hit	= array();	// Hit specific client
		$this->weapon		= new stdClass();
		$this->weapon->got	= array();	// Hit by specific weapon
		$this->weapon->hit	= array();	// Hit with specific weapon
		$this->part			= new stdClass();
		$this->part->got	= array();	// Was hit to specific part of body
		$this->part->hit	= array();	// Hit to specific part of body*/
	}
}
class flags {
	public $captured, $saved;

	function flags() {
		$this->captured		= 0;			// Captured flags
		$this->saved		= 0;			// Saved flags
	}
}
class rounds {
	public $won, $draw, $lost;

	function rounds() {
		$this->won		= 0;			// Won games
		$this->lost		= 0;			// Lost games
		$this->draw		= 0;			// Draw games
	}
}
?>