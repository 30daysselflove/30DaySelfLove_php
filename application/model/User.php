<?php


namespace model;

use lib\UtilityFunctions;
use model\MySQLActiveRecord;
use lib\exception\DatabaseException;
use lib\exception\ArgumentException;
use lib\Session;


class User extends MySQLActiveRecord
{

	const CRYPT_SALT_PREFIX = '$2a$07$';

    const TABLE_NAME = 'users';
	const BASIC_FIELDS = "users.id, username, users.email, realName, password, profileHeader, enabled, signupDate, startDate, videoCount, securityCode, securityCodeExpiration";

	const SECURITY_CODE_EXPIRATION_DEFAULT = 259200; //In seconds (3 days)

    const IDENT_TYPE_OAUTH = 2;

    public static function findAll($objectForm = false)
    {
        $query = "  SELECT users.id, username, email, realName, enabled, dateRegistered, type
                    FROM " . self::TABLE_NAME;

        return self::mapObject($query, $objectForm, true, null, null);
    }

	public static function generateSecureCode()
	{
		return md5(uniqid(md5(rand(0, 100000000), true)));
	}

	public static function validatePassword($password)
	{
	   $r1='/[0-9]/';  //numbers
	   $r2='/[a-zA-Z]/'; //Lowercase
	   //$r3='/[A-Z]/'; //Uppercase
	
	   if(!preg_match($r1,$password)) return FALSE;
	
	   if(!preg_match($r2,$password))  return FALSE;

	   //if(!preg_match_($r3,$password))  return FALSE;
	
	   if(strlen($password)<8) return FALSE;
		
	   return TRUE;

	}


	public static function encryptPassword($password, $salt = null)
	{
		if(!$salt) $salt = md5(mt_rand(1, 1000000)); //Auto generate a salt (used for newly created passwords). Salts arguments would only be provided for password checking against existing passwords in a database
		return crypt($password, self::CRYPT_SALT_PREFIX . $salt);
	}

    public function validateSecurityCode($code)
    {
        if(!$this->securityCode) return false;
        if(!$this->securityCodeExpiration || $this->securityCodeExpiration < time()) return false;
        if($this->securityCode != $code)  return false;

        return true;
    }

	public static function create($username,$email, $realName, $password, $autoEnable = true)
	{

		$db = self::$database;
		//if(!self::validatePassword($password)) throw new ArgumentException("Password is too weak");

		$activationCode = self::generateSecureCode();
		$securityCodeExpiration = time() + self::SECURITY_CODE_EXPIRATION_DEFAULT;;
		
		$pwHash = self::encryptPassword($password);

		$values = array("username" => &$username, "email"=>&$email, "realName"=> &$realName);
		self::sanitizeArguments($values, self::SANITIZE_STRING);
        $date = self::getFormattedDateTime();

        $enabled = (int) $autoEnable;
		
		$query = "	INSERT INTO users
						(username, email, password, realName, enabled, signupDate, securityCode, securityCodeExpiration)
					VALUES('$username', '$email','$pwHash','$realName', $enabled, '$date', '$activationCode', $securityCodeExpiration)";

		$result = $db->db_query($query);
		
		if(!$result)
		{
			if($db->db_errno() == 1062) throw new ArgumentException("An account with this email already exists");
			else throw new DatabaseException($db->db_error());
		}
		
		$userID = $db->last_insert_id();
		$values["password"] = $pwHash;
		$values["securityCode"] = $activationCode;
		$values["securityCodeExpiration"] = $securityCodeExpiration;

		return new User($userID, $values);
	}

    public static function createExternal($email, $identity, $identType, $provider,  $realName, $username)
    {
        $db = self::$database;

        $values = array("realName" => &$realName,"email" =>&$email, "username" => &$username);
        self::sanitizeArguments($values, self::SANITIZE_STRING);

        $query = "	SELECT " .  self::BASIC_FIELDS .", COUNT(identity) as externalCount
					FROM users
                    LEFT JOIN user_account_linkage as linkage
                        ON linkage.userID = users.id
                    WHERE users.email = '$email'";
        $result = $db->db_query_error_check($query);
        $row = $db->db_fetch_assoc($result);
        if($db->db_num_rows($result) && $row['id'])
        {
            $values = array_merge($values, $row); //Merge and overwrite any new values with the previous values from the database.
            $userID = $row['id'];

            $externalOnly = ($row['password'] == "");
            $values['externalCount'] = $row['externalCount'] + 1;
        }else
        {
            $query = sprintf("	INSERT INTO users
								(email, realName, enabled, signupDate, username)
							VALUES('%s','%s', 1, NOW(), '$username')",
                $email, $realName);
            $result = $db->db_query_error_check($query);
            $userID = $db->last_insert_id();
            $externalOnly = true;
            $values['externalCount'] = 1;
        }

        $query = sprintf("	INSERT INTO user_account_linkage
								(userID, identity, email, type, provider)
							VALUES($userID,'%s', '$email', $identType, '%s')
							ON DUPLICATE KEY UPDATE identity='%s'",
            $db->db_real_escape_string($identity),
            $db->db_real_escape_string($provider),
            $db->db_real_escape_string($identity));


        $result = $db->db_query($query);
        if(!$result)
        {
            if($db->db_errno() == 1062) throw new ArgumentException("Linkage account already exists");
            else throw new DatabaseException($db->db_error(), 0, $query);
        }

        $values["externalOnly"] = $externalOnly;
        $values["inactive"] = 0;
        return new User($userID, $values);
    }

    public static function fetchByExternalID($ident, $identType, $provider, $attributes)
    {
        $db = self::$database;
        $query = "	SELECT
					    ". self::BASIC_FIELDS .",
						COUNT(linkageCount.identity)as externalCount
					FROM 	users
					JOIN 	user_account_linkage
						ON user_account_linkage.userID = users.id
                    LEFT JOIN user_account_linkage as linkageCount
                        ON linkageCount.userID = users.id
					WHERE 	user_account_linkage.identity 	= '$ident'
						AND user_account_linkage.type = $identType";

        $result = $db->db_query_error_check($query);
        $userRow = $db->db_fetch_assoc($result);
        if(!$db->db_num_rows($result) || !$userRow['id'])
        {

            $realName = isset($attributes['name']) ? $attributes['name'] : null;
            $username = str_replace(" ", "", $realName);
            $username = strtolower($username);
            $user = self::createExternal($attributes["email"], $ident, $identType, $provider, $realName, $username);
            return $user;
        }

        $userID = $userRow['id'];
        unset($userRow['userID']);
        $userRow['externalOnly'] = empty($userRow['password']);
        return new User($userID, $userRow);
    }

	
	public static function fetchByEmail($email)
	{
		//if(!isset($this->sessionTypeArray[$sessionType])) throw new Exception("INVALID SESSION TYPE");
		//$sessionClass = $this->sessionTypeArray[$sessionType];
		//$destroyOld = false;

        self::sanitizeArguments($email, self::SANITIZE_STRING);
		$db = self::$database;
		$query = "	SELECT 
						"
						. self::BASIC_FIELDS .
						"						
					FROM 	users
					WHERE 	email ='$email'
					";

		$result = $db->db_query_error_check($query);
		
		if(!$db->db_num_rows($result)) {
			return null;
		} 
		
		$userRow = $db->db_fetch_assoc($result);

		$userID = $userRow['id'];
		return new User($userID, $userRow);
	}

    public static function fetchByUsername($name)
    {
        //if(!isset($this->sessionTypeArray[$sessionType])) throw new Exception("INVALID SESSION TYPE");
        //$sessionClass = $this->sessionTypeArray[$sessionType];
        //$destroyOld = false;
        self::sanitizeArguments($name, self::SANITIZE_STRING);
        $db = self::$database;
        $query = "	SELECT
						"
            . self::BASIC_FIELDS .
            "
					FROM 	users
					WHERE 	username ='$name'
					";


        $result = $db->db_query_error_check($query);

        if(!$db->db_num_rows($result)) {
            return null;
        }

        $userRow = $db->db_fetch_assoc($result);

        $userID = $userRow['id'];
        return new User($userID, $userRow);
    }

	public function verifyPassword($password)
	{
	    if(!$password) return false;
		$salt = substr($this->password,0, 29);
		$passwordHash = crypt($password, $salt);
		if($passwordHash != $this->password) {
			return false;
		}
		return true;
	}

	public function set($keyValueArray, $disallowedFields = null)
	{
		$disallowedFields = array("id", "password");
		$id 	= $this->id;


		$query = "	UPDATE users 
					SET " . self::createUpdateSQLFromKeyValue($keyValueArray, $disallowedFields) . " 
					WHERE id=$id";

		$result = self::$database->db_query($query);
		
		if(!$result) throw new DatabaseException(self::$database->db_error());
		foreach ($keyValueArray as $key => $value) {
		    $this->injectSet($key, $value);
		}
		return $result;
	}
	
	public function get()
	{
		$id = $this->id;
		$query = "	SELECT " . 
						self::BASIC_FIELDS . "
					FROM 	users
					WHERE 	users.id = $id;
					";
	
		$result = $this->getSingleResult($query);

		return $result;
	}

    public function setPassword($newPw)
    {
        if(!self::validatePassword($newPw)) throw new ArgumentException("Password is too weak");

        $id = $this->id;
        $salt = md5(mt_rand(1, 1000000));
        $pwHash = self::encryptPassword($newPw, $salt);
        $query = "	UPDATE users
					SET password = '$pwHash'
					WHERE id=$id";

        $result = self::$database->db_query($query);
        if(!$result) throw new DatabaseException(self::$database->db_error());

        $this->injectSet("password", $pwHash);
    }
	
	public function serializableForm()
	{
		$this->fillDataObject();
		
		$serializableArray = $this->dataObject;
		
		if(isset($serializableArray['password'])) unset($serializableArray['password']);
		$serializableArray["id"] = $this->id;
		return $serializableArray;
	}

    public function follow($followingID)
    {
        $query = "  INSERT INTO follows
                        (followerID, followingID)
                    VALUES
                        ($this->id, $followingID)
                    ";
        $result = self::$database->db_query($query);

    }

    public function unfollow($followingID)
    {
        $query = "  DELETE FROM follows
                    WHERE followingID = $followingID AND followerID = $this->id";
        $result = self::$database->db_query_error_check($query);
    }

    public function isFollowing($userID)
    {
        $query = "  SELECT followingID
                    FROM follows
                    WHERE followerID = $this->id AND followingID = $userID";
        $result = self::getSingleResult($query);
        if($result) return true;
        return false;
    }

    public function logActivity($info, $context = "")
    {
        self::sanitizeArguments($info, self::SANITIZE_STRING);
        $date = self::getFormattedDateTime();
        $query = "  INSERT into users_activity
                        (userID, actionInfo, `context` `date`)
                    VALUES
                        ($this->id, '$info', '$context', '$date')";
        return self::$database->db_query($query);
    }

	
}