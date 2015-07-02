<?php

class SeminarController extends Controller
{
	public function actionIndex()
	{
		$this->redirect($this->createUrl('front/seminar/seminarRegistration'));
	}
	
	public function  actionjoinSeminar(){
		$nowYear = date("Y");
		$sql = "SELECT 	name,
						from_time,
						to_time,
						start_date,
						location,
						lecturer,
						location_url,
						apply_from_date, 
						apply_to_date, 
									CASE WHEN now() < apply_from_date  THEN 1 
										WHEN now() > apply_to_date THEN 2 
									ELSE 0 
						END AS disable_apply 			
				FROM rs_seminar 
				WHERE apply_from_date is not null 
						AND apply_to_date is not null 
						AND (
								(apply_from_date >= '".$nowYear."0101' AND apply_from_date <= '".$nowYear."1231' )
								OR 
								(apply_to_date >= '".$nowYear."0101' AND apply_to_date <= '".$nowYear."1231' )
								OR 
								(apply_from_date < '".$nowYear."0101' AND apply_to_date > '".$nowYear."1231' )
							)
				ORDER BY start_date DESC";
				
		$arrResults = Yii::app()->db->createCommand($sql)->queryAll();
		
		$this->renderPartial('join-seminar', array('arrResults' => $arrResults
											)
					);
	}
	public function actionSeminarRegistration()
	{
		//get info user login
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));

		$criteria = new CDbCriteria;
		$criteria->order = 'start_date DESC, from_time ASC, to_time ASC';
		$criteria->together = true;
		$criteria->with = array(
			'seminarStudents'=>array(
				'select'=>'seminarStudents.student_id,seminarStudents.seminar_id, seminarStudents.attended , seminarStudents.apply_code',
				'together'=>true,
				'on' => 'student_id = ' . $infoStudent->id
			)
		);
		$count=RsSeminar::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$student_id =  $infoStudent->id;
		$seminarStudents = RsSeminar::model()->findAll($criteria);

		$this->render('seminarRegistration', array('seminars' => $seminarStudents ,"pages" => $pages,"student_id"=>$student_id));
	}
	public function actionSeminarHistory()
	{
		//issue 8219
		//get info user login
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));

		$criteria = new CDbCriteria;
		$criteria->alias = 'rs_seminar';
		$criteria->join='left join rs_student_seminar 
						on  rs_seminar.id = seminar_id  and student_id = ' . $infoStudent->id ;
		
		$criteria->condition = "(apply_code IS NULL and to_char(CURRENT_TIMESTAMP,'yyyy-mm-dd') > to_char(apply_to_date, 'yyyy-mm-dd' ) ) or	( (apply_code IS NOT NULL  AND  attended != 1) and to_char(CURRENT_TIMESTAMP,'yyyy-mm-dd') > to_char(start_date, 'yyyy-mm-dd' ) )";
		$criteria->order = 'start_date DESC, from_time ASC, to_time ASC';
		$criteria->together = true;
		
		$count=RsSeminar::model()->count($criteria);
		$pages=new CPagination($count);

		// results per page
		$pages->pageSize = 10;
		$pages->applyLimit($criteria);
		$student_id =  $infoStudent->id;
		$seminarStudents = RsSeminar::model()->findAll($criteria);

		$this->render('seminarHistory', array('seminars' => $seminarStudents ,"pages" => $pages,"student_id"=>$student_id));
	}
	
	public function actionUpdateSeminarHistory(){
		
		//issue 8219
		$response = array();

		if(isset($_POST['s_id']) && isset($_POST['student_id'])){
			/*get ID student seminars save*/
			$student_id = $_POST['student_id'];
			$s_id = $_POST['s_id'];
			
			$criteria = new CDbCriteria;
			$criteria->addCondition('student_id = '.$_POST['student_id'].'');
			$criteria->addCondition('seminar_id = '.$_POST['s_id'].'');
			$studentSeminars = RsStudentSeminar::model()->find($criteria);
			if($studentSeminars){
				$studentSeminars->attended = 1;
				$studentSeminars->save();
				$response['title']=$studentSeminars->apply_code;
				$response['save']="ok";
				echo json_encode($response);
			}else{
				/*New Student Seminar*/
				$studentSeminars = new RsStudentSeminar();
				$studentSeminars->student_id = $student_id;
				$studentSeminars->seminar_id = $s_id;
				$studentSeminars->attended = 1;
				$studentSeminars->extra_course_date =  NULL;;

				$criteria=new CDbCriteria;
				$criteria->select='max(id) AS ID';
				$row = $studentSeminars->model()->find($criteria);
				$maxID = $row['id'];
				$updateStudent = RsStudentSeminar::model()->findByPk($maxID);

				if($maxID){
					$apply_code = $updateStudent->apply_code;
					$autoApplyCode = str_pad(intval($apply_code) + 1, 8, "0", STR_PAD_LEFT);
					$studentSeminars->apply_code = $autoApplyCode;

				}else{
					$studentSeminars->apply_code = '00000001';
				}
				if ($studentSeminars->save()){
					$response['title']=$studentSeminars->apply_code;
					$response['save']="ok";
					echo json_encode($response);
				}
			}	
		}
		
	
	}
    public function actionSeminarIndex(){
		/****issue 8220****/
		if(isset($_POST['schedule_date'])){
			$response = array();
			$schedule_date = $_POST['schedule_date'];
			$user_id = Yii::app()->user->id;
			$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));
			
			if($infoStudent->updateAll(array('schedule_date'=>$schedule_date),'reg_code = :user_id', array('user_id'=>$user_id))){
				$response['save']= "ok";
				echo json_encode($response);
			}
			else {
				$response['save']="fail";
				echo json_encode($response);
			}
			return;
		}
		$can_text = "";
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));

		$studentPassedModel = new  RsStudentPassed();
		$get_student = $studentPassedModel->studentCanTest($infoStudent->id,$infoStudent->student_code);
		$allData=Yii::app()->db->createCommand($get_student);
		$studentPassed = $allData->queryAll();

		if(count($studentPassed)>0){
			$can_text = "合格";
			
		}else{
			$can_text = "受講できません";
			
		}
		
		
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));
		$student_id =  $infoStudent->id;
		
		/*************申し込み中セミナー一覧**********/
		//#8665	141126
		$sSQL = "SELECT
					*
				FROM
					rs_seminar,
					rs_student_seminar
				WHERE
					rs_seminar.id = seminar_id
					AND student_id = $infoStudent->id
					AND attended = 0
					AND to_char(CURRENT_TIMESTAMP,'yyyy-mm-dd') >= to_char(apply_from_date, 'yyyy-mm-dd' ) 
					AND to_char(CURRENT_TIMESTAMP,'yyyy-mm-dd hh24:i:ss') <= ( to_char(start_date, 'yyyy-mm-dd') || ' ' || to_time || ':00') 
				ORDER BY start_date DESC, from_time ASC, to_time ASC
				";		
		$listRegisted=Yii::app()->db->createCommand($sSQL)->queryAll();
		
		
		/*************受講済みセミナー一覧**********/
		$sSQL = "SELECT
					*
				FROM
					rs_seminar,
					rs_student_seminar
				WHERE
					rs_seminar.id = seminar_id
					AND student_id = $infoStudent->id
					AND attended = 1
				";		
		$listAttended=Yii::app()->db->createCommand($sSQL)->queryAll();
		
		$this->render(	'seminarIndex', 
						array(	"student_id"=>$student_id,
								"schedule_date"=>$infoStudent->schedule_date,
								'studentPassed'=>$can_text, 
								'listAttended'=>$listAttended,
								'listRegisted'=>$listRegisted	//#8665	141126
						)
		);
	}	
	public function actionRegisterSeminar() {

		 /*Get info form post ajax*/
		if (!isset($_POST['s_id']) || !isset($_POST['ex_date']) || !isset($_POST['student_id'])) {
			echo "There's no such Seminar";
			return;
		}

		$response = array();
		$s_id = $_POST['s_id'];
		$ex_date = $_POST['ex_date'];
		if($ex_date==''){
			$ex_date = NULL;
		}
		$student_id = $_POST['student_id'];

		/*Get Student existing*/
		$criteria = new CDbCriteria;
		$criteria->addCondition('student_id = '.$student_id.'');
		$criteria->addCondition('seminar_id = '.$s_id.'');
		$studentSeminarInfo = RsStudentSeminar::model()->findAll($criteria);
		/* Form model*/
		$form = new SeminarForm();
		if(!$studentSeminarInfo){
			/*New Student Seminar*/
			$studentSeminars = new RsStudentSeminar();
			$studentSeminars->student_id = $student_id;
			$studentSeminars->seminar_id = $s_id;
			$studentSeminars->attended = 0;
			$studentSeminars->extra_course_date = $ex_date;

			$criteria=new CDbCriteria;
			$criteria->select='max(id) AS ID';
			$row = $studentSeminars->model()->find($criteria);
			$maxID = $row['id'];
			$updateStudent = RsStudentSeminar::model()->findByPk($maxID);

			if($maxID){
				$apply_code = $updateStudent->apply_code;
				$autoApplyCode = str_pad(intval($apply_code) + 1, 8, "0", STR_PAD_LEFT);
				$studentSeminars->apply_code = $autoApplyCode;

			}else{
				$studentSeminars->apply_code = '00000001';
			}
			if ($studentSeminars->save()){
				$response['title']=$studentSeminars->apply_code;
				$response['save']="ok";
				echo json_encode($response);
			}
			
		}
		/*else{
			// update

			$string_random =$form->generateCodeID($studentSeminarInfo[0]->id);
			$criteria = new CDbCriteria;
			$criteria->addCondition('student_id = '.$student_id.'');
			$criteria->addCondition('seminar_id = '.$s_id.'');
			$studentSeminar = RsStudentSeminar::model()->find($criteria);
			$studentSeminar->extra_course_date = $ex_date;
			$studentSeminar->apply_code = $string_random;
			$studentSeminar->save();

			$response['title']=$string_random;
			$response['save']="ok";
			echo json_encode($response);
		}*/
	}
	public function actionDeleteSeminar() {
		$response = array();

		if(isset($_POST['s_id']) && isset($_POST['student_id'])){
			/*get ID student seminars save*/
			$criteria = new CDbCriteria;
			$criteria->addCondition('student_id = '.$_POST['student_id'].'');
			$criteria->addCondition('seminar_id = '.$_POST['s_id'].'');
			$studentSeminar = RsStudentSeminar::model()->find($criteria);
			if($studentSeminar->attended == 0){
				RsStudentSeminar::model()->deleteAll('id = :id',array('id'=>$studentSeminar->id));
			}else{
				$studentSeminar->apply_code = NULL;
				$studentSeminar->save();
				/*set response*/
				$response['save']="ok";
			}
			echo json_encode($response);
		}
	}
         public  function actionSeminarDetail()
        {
            $form = new SeminarForm();
            if(isset($_POST['id_seminar']) && $_POST['id_seminar']){
                          
                $seminar = RsSeminar::model()->findByPk($_POST['id_seminar']);
                if ($seminar){                
                    $form->attributes = $seminar->attributes;
                    $form->id = $seminar->id;
                    $form->start_date = formatDateToJP($form->start_date);
                    $form->apply_from_date = formatDateToJP($form->apply_from_date);
                    $form->apply_to_date = formatDateToJP($form->apply_to_date);				
                }
            }
            $this->renderPartial('_detailSeminar',array('model'=>$form));
        }
		

}