<?php

class UserController extends Controller
{
	public function accessRules()
	{
		return array(

			array('deny',
				'actions'=>array('delete'),
				'users'=>array('*'),
			),
		);
	}
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionLogin()
	{
		if (($uid = Yii::app()->user->id) && isset(Yii::app()->user->loginType) && Yii::app()->user->loginType=='front'  )
		{
			$this->redirect($this->createUrl('front/seminar/seminarRegistration'));
		}
		$model=new FrontLoginForm();

		// collect user input data
		if(isset($_POST['FrontLoginForm']))
		{

			$model->attributes=$_POST['FrontLoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				$this->redirect($this->createUrl('/index'));
				//$this->redirect(Yii::app()->user->returnUrl);
			}

		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model));
	}
	public function actionLogout()
	{
		Yii::app()->user->logout(false);
		$this->redirect($this->createUrl('login'));
	}
	public function actionRegister()
	{

		if (($uid = Yii::app()->user->id))
		{

			$this->redirect($this->createUrl('front/seminar/seminarRegistration'));
		}
		$form = new RegisterForm();
		if(isset($_POST['RegisterForm']))
		{
			$form->attributes=$_POST['RegisterForm'];
			if($form->validate()){

				$student = new RsStudent();
				$student->reg_code = '';
				$student->student_code = $form->student_code;
				$student->first_name = $form->first_name;
				$student->last_name = $form->last_name;
				$student->first_kana = $form->first_kana;
				$student->last_kana = $form->last_kana;
				$student->faculty = $form->faculty;
				$student->professor_code = $form->professor_code;
				$student->email = $form->email;
				$student->password = md5($form->password);
                $student->faculty_name = $form->faculty_name;
				if ($student->save())
				{
					$criteria=new CDbCriteria;
					$criteria->select='max(id) AS ID';
					$row = $student->model()->find($criteria);
					$maxID = $row['id'];
					$updateStudent = RsStudent::model()->findByPk($maxID);
					$string_random =$form->generateCode($maxID);
					$updateStudent->reg_code = $string_random;
					$updateStudent->save();

					//send mail
					$message = new YiiMailMessage;
					$message->setSubject('アカウント登録ありがとうございます！');
					$message->setBody(
					'
					<h3>
					'.$student->first_name." ".$student->last_name.'様
					</h3>
					<div style="">
						<p>アカウント情報は下記の通りです。</p>
						<div style ="margin-left: 15px;line-height: 20px;">
							臨床研究認定ID：'.$updateStudent->reg_code.'<br/>
							（ログインID）。<br/>
							パスワード：'.$form->password.'
						</div>
					</div>
					'
					, 'text/html');//
					$message->addTo($student->email);
					$message->from = array(Yii::app()->params['adminEmail'] => Yii::app()->params['adminEmailName']);
					if(Yii::app()->mail->send($message)){
						//login auto
						$identity=new UserIdentity($updateStudent->reg_code,$student->password);
						$identity->authenticate();
						Yii::app()->user->login($identity);

						//set message
						Yii::app()->user->setFlash('showregcode', $string_random );
					}
				}else
				{
					Yii::app()->user->setFlash('error',Yii::t("front",'save.error'));
				}

			}

		}
		$this->renderPartial('register',array('model'=>$form));
	}
	public function actionAccount()
	{
		$user_id = Yii::app()->user->id;
		$info = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));
		$form = new AccountForm;
		if(isset($_POST['AccountForm']))
		{
			$form->attributes=$_POST['AccountForm'];
			if($form->validate())
			{
				$info->student_code = $form->student_code;
				$info->first_name = $form->first_name;
				$info->last_name = $form->last_name;
				$info->first_kana = $form->first_kana;
				$info->last_kana = $form->last_kana;
				$info->faculty = $form->faculty;
				$info->professor_code = $form->professor_code;
				$info->email = $form->email;
				$info->faculty_name = $form->faculty_name;//#8230	141117
				if(!empty($form->password)){
					$info->password = md5($form->password);
				}
				if ($info->save()){
					Yii::app()->user->setFlash('success',Yii::t("front",'update.success'));
				}else{
					Yii::app()->user->setFlash('error',Yii::t("front",'save.error'));
				}
			}
		}else{
			$form["student_code"]=$info->student_code;
			$form["first_name"]=$info->first_name;
			$form["last_name"]=$info->last_name;
			$form["first_kana"]=$info->first_kana;
			$form["last_kana"]=$info->last_kana;
			$form["faculty"]=$info->faculty;
			$form["professor_code"]=$info->professor_code;
			$form["email"]=$info->email;
			$form["password"]='';
			$form["repeat_password"]='';
			$form["faculty_name"]=$info->faculty_name;//#8230	141117
		}
		$this->render('account',array('model'=>$form,'info'=>$info));
	}
	public function actionDeleteAccount()
	{

		header('Content-type: application/json');
		$result = array();
		$result['status'] = -1;
		if (Yii::app()->request->isAjaxRequest)
		{
			$id  = $_POST['id'];
			$result['status'] = RsStudent::model()->deleteAll('id = :id',array('id'=>$id));


			$result['id']  = $id;
			Yii::app()->user->logout(false);
		}
		echo CJSON::encode($result);
		Yii::app()->end();
	}

}