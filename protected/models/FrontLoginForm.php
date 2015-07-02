<?php
/**
 * Created by JetBrains PhpStorm.
 * User: admin
 * Date: 1/11/13
 * Time: 4:35 PM
 * To change this template use File | Settings | File Templates.
 */
class FrontLoginForm extends CFormModel{
	public $reg_code;
	public $password;
	public $rememberMe;
	public $userType;
	private $_identity;
	public function __construct($arg='Front') { // default it is set to Front
		$this->userType = $arg;
	}
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reg_code', 'match' ,'pattern'=>'/^[A-Za-z0-9_]+$/u',
				'message'=> 'Username can contain only alphanumeric characters and hyphens(-).'),
			// username and password are required
			array('reg_code, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}
	public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
			'reg_code'=>'臨床研究認定ID',
			'password'=>'パスワード',
		);
	}
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->reg_code,$this->password);
			$this->_identity->userType = $this->userType; // this will pass flag to the UserIdentity class
			if(!$this->_identity->authenticate())
				$this->addError('password','臨床研究認定IDまたはパスワードは間違っています。');
		}
	}
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->reg_code,$this->password);
			$this->_identity->userType = $this->userType;
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			//$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity);
			return true;
		}
		else
			return false;
	}





}