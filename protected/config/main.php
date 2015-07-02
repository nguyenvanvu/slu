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
			'loginUrl'=>array('front/user/login'),
			'allowAutoLogin'=>true,
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
				'login' => 'front/user/login', //f-1
				'logout' => 'front/user/logout', //f-1
				'register' => 'front/user/register', //f-2
				'apply' => 'front/seminar/seminarRegistration', //f-3
				'status' => 'front/test/status', //f-4
				'test' => 'front/test/test', //f-5
				'profile' => 'front/user/account', //f-6
				'join-seminar' => 'front/seminar/joinSeminar',
				'history' => 'front/seminar/SeminarHistory', //issue 8219
				'index' => 'front/seminar/SeminarIndex', //issue 8220
                             

//
//				'admin/<controller:\w+>/<id:\d+>'=>'admin/<controller>/view',
//				'admin/<controller:\w+>/<action:\w+>/<id:\d+>'=>'admin/<controller>/<action>',
//				'admin/<controller:\w+>/<action:\w+>'=>'admin/<controller>/<action>',
				//'service' => 'admin/seminar/AjaxTest',
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
	'defaultController' => 'front/seminar/index',
	'layout' => 'frontend',
	'sourceLanguage' => 'ja-jp',
	'language' => 'ja',
	// using Yii::app()->params['paramName']
	'params' =>  require(dirname(__FILE__) . '/params.php'),
);