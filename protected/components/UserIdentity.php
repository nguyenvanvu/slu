<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public $userType = 'Front';
	public function authenticate()
	{
		if($this->userType=='Admin') // This is admin login
		{

			// check if login details exists in database
			$record=RsAdmin::model()->findByAttributes(array('username'=>$this->username));
			if($record===null)
			{
				$this->errorCode=self::ERROR_USERNAME_INVALID;

			}
			else if(md5($this->password)!==$record->password )
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				/* $this->setState('userId',$record->userId);
					$this->setState('name', $record->firstName.' '.$record->lastName);*/
				$this->setState('loginType','admin');
				$this->errorCode=self::ERROR_NONE;
			}
			return !$this->errorCode;
		}
		if($this->userType=='Front')// This is user login
		{

		// check if login details exists in database
			$record=RsStudent::model()->findByAttributes(array('reg_code'=>$this->username));
			if($record===null)
			{
				$this->errorCode=self::ERROR_USERNAME_INVALID;

			}
			else if(md5($this->password)!==$record->password )
			{
				$this->errorCode=self::ERROR_PASSWORD_INVALID;
			}
			else
			{
				$this->setState('loginType','front');
				$this->errorCode=self::ERROR_NONE;
			}
			return !$this->errorCode;
		}
	}

}