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

	public function getSection()
	{
		return $this->section;
	}

	public function getAmount()
	{
		return $this->amount;
	}

}

?>