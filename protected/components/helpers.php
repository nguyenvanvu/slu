<?php
function reFormatDate($str) {
	// $date = date_create_from_format('Y-m-d H:i:s', $str);
	// $date = new DateTime($str);
	// return date_format($date, 'Y/m/d');
	$timestamp = strtotime($str);
	return date('Y/m/d', $timestamp);
}

function formatDateToJP($date_str,$format_case=1){
	$date = new DateTime($date_str);
	switch($format_case){
		case 1: return date_format($date, 'Y/m/d'); break;
		case 2: 
			$dayofweek = array(0=>'日',1=>'月',2=>'火',3=>'水',4=>'木',5=>'金',6=>'土');
			return date_format($date, 'Y/m/d').'（'.@$dayofweek[date_format($date, 'w')].'）'; 
			break;
		case 3:
			$dayofweek = array(0=>'日',1=>'月',2=>'火',3=>'水',4=>'木',5=>'金',6=>'土');
			return @$dayofweek[date_format($date, 'w')]; 
			break;
	}
}

function getBaseUrl(){
	return Yii::app()->request->baseUrl;
}
function generateRandomString($length)
{
	$result = '';

	for ($i = 0; $i < $length; $i++)
	{
		// the use of 97 and 122 below will be explained
		$num = rand(97, 122);
		$result .= chr($num);
	}

	return $result;
}

function translate_attention_status($s_object) { // Seminar Object
	$status_code = 0;
	$start_date = strtotime(reFormatDate($s_object->seminar->start_date) ." ". $s_object->seminar->from_time);
	$now = time();
	if ($s_object->attended == 1)
		$status_code = 1;
	elseif ($start_date > $now)
		$status_code = -1;

	$status = array("未受講", "受講済み");
	$status[-1] = "申し込み中";
	return $status[$status_code];
}

function getCurrentRoute()
{
	return Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
}

function getCurrentUrl($param = array())
{
	return Yii::app()->createUrl(getCurrentRoute(),$param);
}