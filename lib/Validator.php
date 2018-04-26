<?php

namespace lib;

class Validator
{
	public static function isValidDateTime($dateTime) {
		
		$timeStamp = strtotime($dateTime);
		$timeArray = getdate($timeStamp);
	   
		if($timeStamp === false) return false;
	    return checkdate($timeArray['mon'], $timeArray['mday'], $timeArray['year']);
	    
	}
}