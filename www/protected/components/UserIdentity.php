<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
    private $_id;

    const ERROR_USER_NEW = 3;
    const ERROR_USER_INACTIVE = 4;


	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate($check_pass = true)
	{
		$user=Usuario::model()->find('LOWER(email)=?',array(strtolower($this->username)));
                
		if($user === null) {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
        } elseif($check_pass && !$user->validatePassword($this->password)) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        } elseif ($user->isNew()) {
        	$this->errorCode=self::ERROR_USER_NEW;
        } elseif (!$user->isActive()) {
        	$this->errorCode=self::ERROR_USER_INACTIVE;
        }else {
			$this->_id=$user->id;
			$user->save();
			$this->username=$user->email;
			$this->errorCode=self::ERROR_NONE;
		}
        return array('result'=>$this->errorCode==self::ERROR_NONE, 'error'=>$this->errorCode);
	}
        
    /**
	 * @return integer the ID of the user record
	 */
	public function getId()
	{
		return $this->_id;
	}	
}