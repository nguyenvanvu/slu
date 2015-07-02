<?php

class StudentController extends Controller
{
	public $layout='//layouts/backend';
	public function actionIndex()
	{
		$this->redirect($this->createUrl('admin/student/studentManagement'));
	}

	public function actionStudentManagement()
	{
        $data = array();
        $criteria = new CDbCriteria;
        $search_params = array();
        if($_REQUEST) {
            $data['post'] = $_REQUEST;
            if (isset($_REQUEST['s_student_code']) && $_REQUEST['s_student_code']!=''){
                $criteria->addCondition('student_code LIKE :student_code');
                $criteria->params[':student_code']= $_REQUEST['s_student_code'] .'%';
                $search_params['s_student_code'] = $_REQUEST['s_student_code'];
            }
            if (isset($_REQUEST['s_name']) && $_REQUEST['s_name']!=''){
                $criteria->addCondition("(first_name || '　' || last_name) LIKE :name");
                $criteria->params[':name']= '%' . $_REQUEST['s_name']  . '%';
                $search_params['s_name'] = $_REQUEST['s_name'];
            }
            if (isset($_REQUEST['s_kana']) && $_REQUEST['s_kana']!=''){
                $criteria->addCondition("(first_kana || '　' || last_kana) LIKE :kana");
                $criteria->params[':kana']= '%' . $_REQUEST['s_kana'] . '%';
                $search_params['s_kana'] = $_REQUEST['s_kana'];
            }
            if (isset($_REQUEST['s_faculty']) AND $_REQUEST['s_faculty'] != ''){
                if (!is_array($_REQUEST['s_faculty'])) {
                    $_REQUEST['s_faculty'] = explode(",", $_REQUEST['s_faculty']);
                }
                $criteria->addCondition('(faculty=' . join($_REQUEST['s_faculty'], ' OR faculty=') . ')');
                $search_params['s_faculty'] = join($_REQUEST['s_faculty'], ",");
            }
            if (isset($_REQUEST['s_professor_code']) && $_REQUEST['s_professor_code']!=''){
                $criteria->addCondition('professor_code LIKE :professor_code');
                $criteria->params[':professor_code']= $_REQUEST['s_professor_code'] . '%';
                $search_params['s_professor_code'] = $_REQUEST['s_professor_code'];
            }
            if (isset($_REQUEST['s_email']) && $_REQUEST['s_email']!=''){
                $criteria->addCondition('email LIKE :email');
                $criteria->params[':email']= $_REQUEST['s_email'] . '%';
                $search_params['s_email'] = $_REQUEST['s_email'];
            }
            $data['search'] = $search_params;
        }
        $count = RsStudent::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $criteria->order = 'student_code ASC';
        $data['list_student'] = RsStudent::model()->findAll($criteria);
        $data['pages'] = $pages;
        $this->render('studentManagement',array(
            'data'=>$data,
            "pages" => $pages,
            'searchParams'=>$search_params
        ));
	}
    public function actionNewStudentManagement()
    {
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
                $student->faculty_name = $form->faculty_name;
                $student->password = md5($form->password);

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
                        echo "[code]".$string_random;
                        return true;
                    }
                }else
                {
                    Yii::app()->user->setFlash('error',Yii::t("front",'save.error'));
                }

            }

        }
        $this->renderPartial('_newStudentManagement',array('model'=>$form));
    }
    public function actionEditStudentManagement()
    {
		$form = new RegisterForm();
		if(isset($_POST['id_student']) && $_POST['id_student'])
		{
			$student = RsStudent::model()->findByPk($_POST['id_student']);
			if ($student){
				$form->id = $student->id;
				$form->reg_code = $student->reg_code;
				$form->student_code = $student->student_code;
				$form->reg_code = $student->reg_code;
				$form->first_name = $student->first_name;
				$form->last_name = $student->last_name;
				$form->first_kana = $student->first_kana;
				$form->last_kana = $student->last_kana;
				$form->faculty = $student->faculty;
				$form->professor_code = $student->professor_code;
				$form->email = $student->email;
				$form->password = $student->password;
				$form->repeat_password = $student->password;
                $form->faculty_name = $student->faculty_name;
			}
		}elseif (isset($_POST['action'])){
			if ($_POST['action']=='update') {
				if (isset($_POST['RegisterForm']) && isset($_POST['RegisterForm']['id'])){
					$student = RsStudent::model()->findByPk($_POST['RegisterForm']['id']);
					$form->attributes=$_POST['RegisterForm'];
					if (($_POST['RegisterForm']['password']=='' && $_POST['RegisterForm']['repeat_password']=='') || ($_POST['RegisterForm']['password']==$_POST['RegisterForm']['repeat_password'] && $_POST['RegisterForm']['password']
								== $student->password)){
						$form->password = $student->password;
						$form->repeat_password = $student->password;
					}else{
						$student->password = md5($form->password);
					}
				}
				if($form->validate()){
					$student->student_code = $form->student_code;
					$student->first_name = $form->first_name;
					$student->last_name = $form->last_name;
					$student->first_kana = $form->first_kana;
					$student->last_kana = $form->last_kana;
					$student->faculty = $form->faculty;
					$student->professor_code = $form->professor_code;
					$student->email = $form->email;
                    $student->faculty_name = $form->faculty_name;
					if($student->save())
                    {
                        Yii::app()->user->setFlash('success',Yii::t("backend", "受講者情報の更新は完了しました。"));
                    }
                    else
                    {
                        Yii::app()->user->setFlash('success',Yii::t("backend", "受講者情報の更新は失敗しました。"));
                    }
					return;
				}
			}elseif ($_POST['action']=='delete'){
				$id = $_POST['id'];
				if(RsStudent::model()->deleteByPk($id))
                {
                    Yii::app()->user->setFlash('success',Yii::t("backend", "受講者情報の削除は完了しました。"));
                }
                else
                {
                    Yii::app()->user->setFlash('success',Yii::t("backend", "受講者情報の削除は失敗しました。"));
                }
				return;
			}
		}elseif ($_POST['RegisterForm']){

		}
		$this->renderPartial('_editStudentManagement',array('model'=>$form));

    }

    // page 4.2 Status Student List
    public function actionStatusStudentList() {
    	$data = array();
    	$criteria = new CDbCriteria;
        $search_params = array();
        if($_REQUEST) {
            if (isset($_REQUEST['s_student_code']) && $_REQUEST['s_student_code']) {
				$criteria->addCondition('student_code LIKE :student_code');
				$criteria->params[':student_code'] = $_REQUEST['s_student_code'] . '%';
				$search_params['s_student_code'] = $_REQUEST['s_student_code'];
			}

			if (isset($_REQUEST['s_professor_code']) && $_REQUEST['s_professor_code']) {
				$criteria->addCondition('professor_code LIKE :professor_code');
				$criteria->params[':professor_code'] = $_REQUEST['s_professor_code'] . '%';
				$search_params['s_professor_code'] = $_REQUEST['s_professor_code'];
			}

			if (isset($_REQUEST['s_name']) && $_REQUEST['s_name']) {
				$criteria->addCondition("(first_name || '　' || last_name) LIKE :name");
				$criteria->params[':name'] = '%'.$_REQUEST['s_name'].'%';
				$search_params['s_name'] = $_REQUEST['s_name'];
			}

			if (isset($_REQUEST['s_kana']) && $_REQUEST['s_kana']) {
				$criteria->addCondition("(first_kana || '　' || last_kana) LIKE :kana");
				$criteria->params[':kana'] = '%'.$_REQUEST['s_kana'].'%';
				$search_params['s_kana'] = $_REQUEST['s_kana'];
			}

			if (isset($_REQUEST['s_kana']) && $_REQUEST['s_kana']) {
				$criteria->addCondition("(first_kana || '　' || last_kana) LIKE :kana");
				$criteria->params[':kana'] = '%'.$_REQUEST['s_kana'].'%';
				$search_params['s_kana'] = $_REQUEST['s_kana'];
			}

			if (isset($_REQUEST['s_faculty']) AND $_REQUEST['s_faculty'] != '') {
    			if (!is_array($_REQUEST['s_faculty'])) {
    				$_REQUEST['s_faculty'] = explode(",", $_REQUEST['s_faculty']);
    			}
    			$criteria->addCondition('(faculty=' . join($_REQUEST['s_faculty'], ' OR faculty=') . ')');
    			$search_params['s_faculty'] = join($_REQUEST['s_faculty'], ",");
    		}

			if (isset($_REQUEST['s_email']) && $_REQUEST['s_email']){
                $criteria->addCondition('email LIKE :email');
                $criteria->params[':email']= $_REQUEST['s_email'] . '%';
                $search_params['s_email'] = $_REQUEST['s_email'];
            }
        }
        $count = RsStudent::model()->count($criteria);
        $pages = new CPagination($count);
        $pages->pageSize=10;
        $pages->applyLimit($criteria);
        $criteria->order = 'student_code ASC';

        $data['post'] = $_REQUEST;
        $data['students'] = RsStudent::model()->findAll($criteria);
        $data['pages'] = $pages;
        $data['searchParams'] = $search_params;
        $this->render('statusStudentList', $data);
    }
    // Page 4.2.1
    public function actionEditStatusStudentList() {
    	$data = array();
    	$st_crit = new CDbCriteria; // criteria to query student infomation
    	$st_id = $_POST['st_id'];
    	$st_crit->params[':st_id'] = $st_id;
    	$st_crit->condition = 'id=:st_id';
    	$student = RsStudent::model()->find($st_crit); //student infomation

    	$criteria = new CDbCriteria; // criteria to query seminar list
    	$criteria->together = true;
    	$criteria->with = array('seminar'=>array('select'=>'seminar.*','together'=>true));
    	$criteria->condition = 'student_id=:st_id';
    	$criteria->params[':st_id'] = $st_id;
    	$criteria->addCondition('attended = 1 OR apply_code IS NOT NULL');

    	$count = RsStudentSeminar::model()->count($criteria);
        $pages= new CPagination($count);
        if (isset($_POST['page']) && (int)$_POST['page']>=0)
            $pages->currentPage = (int)$_POST['page'] - 1;
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $criteria->order = 'start_date DESC, from_time ASC, to_time ASC';
    	$seminars = RsStudentSeminar::model()->findAll($criteria);

    	$data['pages'] = $pages;
    	$data['student'] = $student;
    	$data['seminars'] = $seminars;
    	echo $this->renderPartial('_editStatusStudentList', $data, true);
    }

    public function actionImportData() {
    	// if $_REQUEST info is set, process it and insert into database
    	// then exit
    	if (isset($_POST['action'])) {
    		$data = $_POST['info'];
            $success = 0;
            $fail = 0;
    		foreach ($data as $line => $record) {
    			$line = (int)$line + 2;
    			$criteria = new CDbCriteria;
	    		foreach ($record as $key => $value) {
	    			$criteria->addCondition($key ."='". $value."'");
	    		}
	    		$count = RsStudentPassed::model()->count($criteria);
	    		if ($count == 0) {
		    		$student_pass = new RsStudentPassed;
		    		$student_pass->student_code = $record['student_code'];
		    		$student_pass->school_reg_code = $record['school_reg_code'];
		    		$student_pass->staff_code = $record['staff_code'];
		    		if ($student_pass->save()) {
                        $success++;
                    } else {
                        $fail++;
                    }
		    	} else {
		    		$fail++;
		    	}
		    }
        echo "合格者情報の取り込みは完了しました。<br>成功： ".$success."件<br>失敗： ".$fail."件";
	    	exit;
    	}
    	// else render the page instead
    	$this->render('importData');
    }

    // page 6.2 Attended Student
    public function actionAttendedStudent()
    {
		$search_params = array();
        $model = new RsStudent();
		//#8227	141110
//        $sql_status = $model->sql_status();
//        $sql = $sql_status['sql'];
//        if($_REQUEST) {
//            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 0){
//                $search_params['s_status'] = $_REQUEST['s_status'];
//                $sql = "select DISTINCT ON (rs_student.id) rs_student.*, 0 as status from rs_student inner join rs_test_result on rs_test_result.student_id = rs_student.id and pof = 1";
//                if ((isset($_REQUEST['start_date']) && $_REQUEST['start_date']!='') && (isset($_REQUEST['end_date']) && $_REQUEST['end_date']!=''))
//                {
//                    $sql = $sql." and date BETWEEN '".$_REQUEST['start_date']."' AND '".$_REQUEST['end_date']."'";
//                    $search_params['start_date'] = $_REQUEST['start_date'];
//                    $search_params['end_date'] = $_REQUEST['end_date'];
//                }else{
//                    if((isset($_REQUEST['start_date']) && $_REQUEST['start_date']!='') && ($_REQUEST['end_date']==''))
//                    {
//                        $sql = $sql." and date >= '".$_REQUEST['start_date']."'";
//                        $search_params['start_date'] = $_REQUEST['start_date'];
//                    }
//                    if(($_REQUEST['start_date']=='') && (isset($_REQUEST['end_date']) && $_REQUEST['end_date']))
//                    {
//                        $sql = $sql." and date <= '".$_REQUEST['end_date']."'";
//                        $search_params['end_date'] = $_REQUEST['end_date'];
//                    }
//                }
//            }
//            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 1){
//                $search_params['s_status'] = $_REQUEST['s_status'];
//                $sql = "select DISTINCT ON (id) * from ((".$sql_status['sql_eligible'].") union (".$sql_status['sql_not_pof'].")) as tbl order by id,status";
//            }
//            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 2){
//                $search_params['s_status'] = $_REQUEST['s_status'];
//                if ((isset($_REQUEST['start_date']) && $_REQUEST['start_date']) && (isset($_REQUEST['end_date']) && $_REQUEST['end_date']))
//                {
//                    $sql = "select DISTINCT ON (id) * from (";
//                    $sql .= "(".$sql_status['sql_pof']." and date BETWEEN '".$_REQUEST['start_date']."' AND '".$_REQUEST['end_date']."')";
//                    $sql .= " union (".$sql_status['sql_eligible'].")".
//                            ' union ('.$sql_status['sql_not_pof']." and date BETWEEN '".$_REQUEST['start_date']."' AND '".$_REQUEST['end_date']."')";
//                    $sql .= ")as tbl order by id,status";
//                    $search_params['start_date'] = $_REQUEST['start_date'];
//                    $search_params['end_date'] = $_REQUEST['end_date'];
//                }else
//                {
//                    if((isset($_REQUEST['start_date']) && $_REQUEST['start_date']!='') && ($_REQUEST['end_date']==''))
//                    {
//                        $sql = "select DISTINCT ON (id) * from (";
//                        $sql .= "(".$sql_status['sql_pof']." and date >= '".$_REQUEST['start_date']."')";
//                        $sql .= " union (".$sql_status['sql_eligible'].")".
//                            " union (".$sql_status['sql_not_pof']." and date >= '".$_REQUEST['start_date']."')";
//                        $sql .= ")as tbl order by id,status";
//                        $search_params['start_date'] = $_REQUEST['start_date'];
//                    }
//                    if(($_REQUEST['start_date']=='') && (isset($_REQUEST['end_date']) && $_REQUEST['end_date']))
//                    {
//                        $sql = "select DISTINCT ON (id) * from (";
//                        $sql .= "(".$sql_status['sql_pof']." and date <= '".$_REQUEST['end_date']."')";
//                        $sql .= " union (".$sql_status['sql_eligible'].")".
//                            " union (".$sql_status['sql_not_pof']." and date <= '".$_REQUEST['end_date']."')";
//                        $sql .= ")as tbl order by id,status";
//                        $search_params['end_date'] = $_REQUEST['end_date'];
//                    }
//                }
//            }
//        }
		$sql = '';
        if($_REQUEST) {
			if(isset($_REQUEST['s_status'])) $search_params['s_status'] = $_REQUEST['s_status'];
			$search_params['start_date'] = '';
			$search_params['end_date'] = '';
			$Cond = '';
			if ((isset($_REQUEST['start_date']) && $_REQUEST['start_date']!='') && (isset($_REQUEST['end_date']) && $_REQUEST['end_date']!='')){
				$Cond = "date BETWEEN '".$_REQUEST['start_date']."' AND '".$_REQUEST['end_date']."'";
				$search_params['start_date'] = $_REQUEST['start_date'];
				$search_params['end_date'] = $_REQUEST['end_date'];
			}else{
				if((isset($_REQUEST['start_date']) && $_REQUEST['start_date']!='') && ($_REQUEST['end_date']=='')){
					$Cond = "date >= '".$_REQUEST['start_date']."'";
					$search_params['start_date'] = $_REQUEST['start_date'];
				}
				if((@$_REQUEST['start_date']=='') && (isset($_REQUEST['end_date']) && $_REQUEST['end_date'])){
					$Cond = "date <= '".$_REQUEST['end_date']."'";
					$search_params['end_date'] = $_REQUEST['end_date'];
				}
			}
			
			$sql_status = $model->sql_status(array('date'=>$Cond));
			$sql = $sql_status['sql'];
			
            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 0){
				$sql = $sql_status['sql_pof'];
            }
            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 1){
                $sql = "select DISTINCT ON (id) * from ((".$sql_status['sql_eligible'].") union (".$sql_status['sql_not_pof'].")) as tbl order by id,status_rank";
            }
            if (isset($_REQUEST['s_status']) && $_REQUEST['s_status'] == 2){
				$sql = "select DISTINCT ON (id) * from (";
				$sql .= "(".$sql_status['sql_pof'].")";
				$sql .= " union (".$sql_status['sql_eligible'].")".
						" union (".$sql_status['sql_not_pof'].")";
				$sql .= ")as tbl order by id,status_rank";
            }
        }
		else {
			$sql_status = $model->sql_status();
			$sql = $sql_status['sql'];
		}
        $list_student = $model->data_query($sql);
        $dataProvider = new CArrayDataProvider($list_student, array(
            'pagination'=>array(
                'pageSize'=>10,//#8227	141110
            ),
        ));
        $pages = $dataProvider->pagination;
        $list_student = $dataProvider->getData();
        $this->render('attendedStudent',array(
            'list_student'=>$list_student,
            "pages" => $pages,
            'searchParams'=>$search_params
        ));
    }

    public function actionEditAttendedStudent()
    {
        //$student_seminar = $_POST['id_seminar'];
        if($_POST['student_id'])
        {
            $criteria = new CDbCriteria;
            $student = RsStudent::model()->findByPk($_POST['student_id']);

            $criteria->addCondition('student_id = :student_id');
            $criteria->params = array();
            $criteria->params[':student_id'] = $_POST['student_id'];

            $count = RsTestResult::model()->count($criteria);
            $pages=new CPagination($count);
            // results per page
            if (isset($_POST['page']) && (int)$_POST['page']>=0)
                $pages->currentPage = (int)$_POST['page'] - 1;
            $pages->pageSize = 10;
            $pages->applyLimit($criteria);
            $criteria->order = 'date DESC';
            $list_test = RsTestResult::model()->findAll($criteria);
            $this->renderPartial('_editAttendedStudent',array(
                'student' => $student,
                'list_test' => $list_test,
                "pages" => $pages));
        }
    }
    public function actionExportAttendedStudent()
    {
        Yii::import('ext.ECSVExport');
        $model = new RsStudent();
        $sql_status = $model->sql_status();
        $sql = $sql_status['sql'];
        $list_student = $model->data_query($sql);
        $data_csv = array();
        foreach($list_student as $values)
        {
            $faculty = Yii::app()->params['faculty_values'][$values["faculty"]];
            $status = Yii::app()->params['status'][$values["status"]];
            $item_csv = array(
                "名大ID"=>$values["student_code"],
                "名前"=>$values["first_name"].'　'.$values["last_name"],
                "フリガナ"=>$values["first_kana"].'　'.$values["last_kana"],
                "所属"=>$faculty,
                "学籍・教員番号"=>$values["professor_code"],
                "状況"=>$status
            );
            array_push($data_csv,$item_csv);
        }
        $csv = new ECSVExport($data_csv);
        //$output = $csv->toCSV();
		//$output = mb_convert_encoding($csv->toCSV(),"SJIS", "UTF-8");
		$output = mb_convert_encoding($csv->toCSV(),"SJIS-win", "UTF-8");
        $filename="認定者一覧.csv";
        Yii::app()->getRequest()->sendFile($filename, $output, "text/csv", false);
    }

//	 Uncomment the following methods and override them if needed
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
