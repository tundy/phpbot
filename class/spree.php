<?php
class spree {
	public $kill, $dead, $flag;

	function spree() {
		$this->kill				= new stdClass();
		$this->kill->last		= 0;			// Killing spree
		$this->kill->high		= 0;			// Killing best
		$this->dead				= new stdClass();
		$this->dead->last		= 0;			// Dead spree (longest)
		$this->dead->high		= 0;			// Dead spree worst (longest)
		$this->flag				= new stdClass();
		$this->flag->save		= 0;			// Saved Flags without dead
		$this->flag->capture	= 0;			// Captured Flags without dead
	}
}
?>