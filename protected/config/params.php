<?php
// using Yii::app()->params['paramName']
return array(
	'adminEmail'=>'no-reply@abc.co.jp',
	'adminEmailName'=>'臨床研究認定サイト',
	'auto_hide_msg_time' => 5000,
	'faculty_values' => array(
		'附属病院',
		'医学部',
		'他学部（学内）',
		'その他',
	),
	'holding_values' => array(
		'ＣＡＭＣＲ',
		'生命倫理委員会',
	),
    'status' => array(
        '認定',
        '受講可能',
        '不合格',
    ),
    'pof_values' => array(
        '不合格',
        '合格'
    ),
    'faculty_name' => array(
        '1'=>'学生',
        '2'=>'医師',
        '3'=>'職員'
    ),
    'upload_test_temp'=>dirname(__FILE__)."/../../upload/",
);