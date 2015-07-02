<?php

class UserController extends Controller
{
	public $layout='//layouts/backend';

	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if ($error['code']==403){
				$this->redirect(Yii::app()->createUrl('login'));
			}
			else if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}
	public function actionIndex()
	{
		$this->render('index');
	}
	public function actionLogin()
	{
		if (($uid = Yii::app()->user->id) && isset(Yii::app()->user->loginType) && Yii::app()->user->loginType=='admin' )
		{
			$this->redirect($this->createUrl('admin/student'));
		}
		$model=new LoginForm();

		// collect user input data
		if(isset($_POST['LoginForm']))
		{

			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
			{
				$this->redirect($this->createUrl('admin/student'));
			}

		}
		// display the login form
		$this->renderPartial('login',array('model'=>$model));
	}
	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout(false);
		$this->redirect($this->createUrl('admin/login'));
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}