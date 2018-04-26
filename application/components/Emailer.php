<?php

namespace components;
use lib\exception\UnknownException;

use lib\phpmailer\SMTP;

use lib\phpmailer\PHPMailer;

use lib\exception\ArgumentException;


class Emailer
{
	const SMTP_HOST 	= CONF_SMTP_HOST;
	const SMTP_PORT 	= CONF_SMTP_PORT;
	const SMTP_AUTH 	= CONF_SMTP_AUTH;
	const SMTP_USERNAME = CONF_SMTP_USERNAME;
	const SMTP_PASSWORD = CONF_SMTP_PASSWORD;
	
	public static $internalMailer;

	public $from;

	public function __construct($from = array("developers@kingdm.com", "30DaysSelfLove.com"))
	{
		$this->from = $from;
	}
	public function email($to, $subject, $body, $cc = null, $bcc = null)
	{
		$fromFormat = vsprintf("%s <%s>", $this->from);
		$headers   = array(
				"MIME-Version" => "1.0",
				"Content-Type" => "text/html; charset=ISO-8859-1",
				"To" => $to,
				"From" => "$fromFormat",
				"Subject" => "{$subject}",
				"Reply-To" => $this->from[1],
				"X-Mailer" => "PHP".phpversion()
				);

		/*$smtp = \Mail::factory('smtp',
		 array ('host' => self::SMTP_HOST,
		 		'port' => self::SMTP_PORT,
		 		'auth' => self::SMTP_AUTH,
		 		'username' => self::SMTP_USERNAME,
		 		'password' => self::SMTP_PASSWORD));

		$mail = $smtp->send($to, $headers, $body);
		if (\PEAR::isError($mail)) {
		throw new ArgumentException($mail->getMessage());
		}*/
		 
		
		$mail             = self::$internalMailer ? self::$internalMailer : self::$internalMailer = new PHPMailer();

		$mail->IsSMTP(); // telling the class to use SMTP
		$mail->Host       = self::SMTP_HOST; 			// SMTP server
		$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
		$mail->SMTPKeepAlive = true;
		// 1 = errors and messages
		// 2 = messages only

		$mail->SMTPAuth   = self::SMTP_AUTH;                  // enable SMTP authentication
		$mail->Port       = self::SMTP_PORT;                    // set the SMTP port for the GMAIL server
		$mail->Username   = self::SMTP_USERNAME; // SMTP account username
		$mail->Password   = self::SMTP_PASSWORD;        // SMTP account password

		$mail->SetFrom($this->from[0], $this->from[1]);

		$mail->AddReplyTo($this->from[0], $this->from[1]);

		$mail->Subject    = $subject;

		//$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
		$mail->MsgHTML($body);

		if($to)
		{
			if(is_array($to)) $addressArray = $to;
			else $addressArray = explode(",", $to);
			
			$l = count($addressArray);
			for($i = 0; $i < $l; $i++)
			{
			$mail->AddAddress($addressArray[$i]);
			}
		}
		
		if($cc)
		{
			if(is_array($cc)) $addressArray = $cc;
			else $addressArray = explode(",", $cc);
				
			$l = count($addressArray);
			for($i = 0; $i < $l; $i++)
			{
				$mail->AddCC($addressArray[$i]);
			}
		}
		
		if($bcc)
		{
			if(is_array($bcc)) $addressArray = $bcc;
			else $addressArray = explode(",", $bcc);
		
			$l = count($addressArray);
			for($i = 0; $i < $l; $i++)
			{
				$mail->AddBCC($addressArray[$i]);
			}
		}
		


		if(!$mail->Send()) {
			throw new UnknownException("Email could not be sent");
		}
		 
		return true;
	}
	
	
}