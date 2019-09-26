<?php

class Course_Completed
{
	private $userid;
    private $code;
	
	public function __construct($userid, $code)
	{
		$this->userid = $userid;
        $this->code = $code;
	}

	public function getUserId()
	{
		return $this->userid;
	}

    public function getCode()
	{
		return $this->code;
    }

}

