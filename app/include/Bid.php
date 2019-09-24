<?php

/**
 * 
 */
class Bid
{
	private $amount;
	private $section;
	function __construct($section, $amount)
	{
		$this->section = $section;
		$this->amount = $amount;
	}

	public function validate()
	{
		
	}

}

?>