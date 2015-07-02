<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
require_once( dirname(__FILE__) . '/../components/helpers.php');
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'RinShou',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.*',
		'ext.customPager',
		'ext.yii-mail.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool

		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'sm@rtnet',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	'behaviors' => array(
		'onBeginRequest' => array(
			'class' => 'application.components.RequireLogin'
		)
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'loginUrl'=>array('admin/user/login'),
			'allowAutoLogin'=>true,
			'stateKeyPrefix'=>'admin-session'
		),
		'mail' => array(
			'class' => 'ext.yii-mail.YiiMail',
			'transportType' => 'smtp',
			'transportOptions' => array(
				'host'=>'smtp.gmail.com',
				'username'=>'free.tester1@gmail.com',
				'password'=>'sm@rtnet',
				'port'=>'465',
				'encryption'=>'ssl'
			),
			'viewPath' => 'application.views.mail',
			'logging' => true,
			'dryRun' => false
		),
		'assetManager' => array(
			'linkAssets' => true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'caseSensitive'=>false,
			'rules'=>array(
				'admin'=>'admin/student/index', //b-1
				'admin/student'=>'admin/student/studentManagement',//b-2.1
				'admin/login'=>'admin/user/login', //b-1
				'admin/logout'=>'admin/user/logout',
				'admin/applying'=>'admin/seminar/registeredSeminarList',//b-2.2
				'admin/starting'=>'admin/seminar/todaySeminarList',//b-3.1
				'admin/attending'=>'admin/seminar/editTodaySeminarList',//b-3.1.1
				'admin/started'=>'admin/seminar/finishedSeminarList',//b-4.1
				'admin/attended'=>'admin/student/statusStudentList',//b-4.2
				'admin/seminar'=>'admin/seminar/seminarManagement',//b-5
				'admin/result'=>'admin/student/attendedStudent',//b-6.1
				'admin/test'=>'admin/test/testList',//b-6.3
				'admin/category'=>'admin/test/testCategoryList',//b-6.3.1
				'admin/question'=>'admin/test/questionList',//b-6.4
				'admin/import'=>'admin/student/importData',//b-7
			

//				'admin/<controller:\w+>/<id:\d+>'=>'admin/<controller>/view',
//				'admin/<controller:\w+>/<action:\w+>/<id:\d+>'=>'admin/<controller>/<action>',
//				'admin/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
			),
		),

		'db'=>array(
			'connectionString' => 'pgsql:host=192.168.100.188;port=5432;dbname=rinsho',
			'username' => 'dbadmin',
			'password' => 'admin@kis',
			'charset' => 'utf8',
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages

//				array(
//					'class'=>'CWebLogRoute',
//				),

			),
		),
        'ePdf' => array(
            'class'         => 'ext.yii-pdf.EYiiPdf',
            'params'        => array(
                'mpdf'     => array(
                    'librarySourcePath' => 'application.vendor.mpdf.*',
                    'constants'         => array(
                        '_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),
                    ),
                    'class'=>'mPDF',
                )
            ),
        ),
	),
	'defaultController' => 'student/index',
	'layout' => 'backend',
	'sourceLanguage' => 'ja-jp',
	'language' => 'ja',
	// using Yii::app()->params['paramName']
	'params' =>  require(dirname(__FILE__) . '/params.php'),
);