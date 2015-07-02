<?php
class AccountForm extends CFormModel {
	public $id;
	public $reg_code;
	public $student_code;
	public $first_name;
	public $last_name;
	public $first_kana;
	public $last_kana;
	public $faculty;
	public $professor_code;
	public $email;
	public $password;
	public $repeat_password;
	public $faculty_name;

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_code ,professor_code ', 'match' ,'pattern'=>'/^[A-Za-z0-9_]+$/u',
				'message'=>Yii::t('front','code.alphanumeric')),
			array(' student_code, first_name , last_name, first_kana, last_kana, faculty, faculty_name, professor_code, email', 'required'),//#8230	141117
			array('faculty', 'numerical', 'integerOnly'=>true),
			array('reg_code, student_code, first_name, last_name, first_kana, last_kana, professor_code', 'length', 'max'=>20),
			array('password', 'length', 'max'=>50),
			array('email','email','message'=>Yii::t('front','email.rule')),
			array('id', 'safe'),
			array('password','passwordCustom'),
			array('repeat_password','repeatPasswordCustom'),
			array('repeat_password','compareCustom'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reg_code, student_code, first_name, last_name, first_kana, last_kana, faculty, professor_code, email, password', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'reg_code' => '臨床研究認定ID',
			'student_code' => '名大ID',
			'first_name' => '名前（性）',
			'last_name' => '名前（名）',
			'first_kana' => 'フリガナ（セイ）',
			'last_kana' => 'フリガナ（メイ）',
			'faculty' => '所属分類',//#8230	141117
			'professor_code' => '学籍・教員番号',
			'email' => 'メールアドレス',
			'password' => 'パスワード',
			'repeat_password' => 'パスワード確認',
			'faculty_name'=>'所属名'//#8230	141117
		);
	}
	public function  generateCode($id){
		$reg_code = '';
		$code = generateRandomString(8).$id;
		$reg_code = substr($code, 2, 8);
		return $reg_code;
	}
	public function compareCustom($attribute,$params)
	{
		if(!empty($this->repeat_password) && !empty($this->password)){
			if($this->repeat_password != $this->password){
				$this->addError('compare',Yii::t("front","repeat_password.rule"));
			}
		}
	}
	public function passwordCustom($attribute,$params)
	{
		if(empty($this->password) && !empty($this->repeat_password)){
			$this->addError('password',Yii::t("front","password.empty"));
		}
	}
	public function repeatPasswordCustom($attribute,$params)
	{
		if(!empty($this->password) && empty($this->repeat_password)){
			$this->addError('repeat_password',Yii::t("front","repeat_password.empty"));
		}
	}

} 