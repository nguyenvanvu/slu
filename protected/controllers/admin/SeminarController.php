<?php

class SeminarController extends Controller
{
	public $layout='//layouts/backend';

	public function actionIndex()
	{
		$this->render('index');
	}
    // Page 2.2
    public function actionRegisteredSeminarList()
    {
        $criteria = new CDbCriteria();
        $criteria->addCondition("to_timestamp( to_date(start_date::text,'YYYY-MM-DD') || ' ' || COALESCE(to_time,'00:00') || ':00', 'YYYY-MM-DD HH24:MI:SS' )>=NOW()");
        $criteria->order = 'start_date DESC, from_time ASC, to_time ASC';

        $count=RsSeminar::model()->count($criteria);
        $pages=new CPagination($count);

        // results per page
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $models=RsSeminar::model()->findAll($criteria);
        // $seminars = $seminarModel->get_today_seminars();
        $this->render('registeredSeminarList', array('seminars' => $models, "pages" => $pages));
    }
    public function actionEditRegisteredSeminarList()
    {
        //$student_seminar = $_POST['id_seminar'];
        $criteria = new CDbCriteria;
        $criteria->together = true;
        $criteria->with = array('studentSeminars'=>array('select'=>'studentSeminars.seminar_id, studentSeminars.apply_code','together'=>true));
        $criteria->addCondition('seminar_id = :seminar_id AND apply_code IS NOT NULL');
        $criteria->params = array();
        $criteria->params[':seminar_id'] = $_POST['id_seminar'];

        $count = RsStudent::model()->count($criteria);
        $pages=new CPagination($count);
        // results per page
		if (isset($_POST['page']) && (int)$_POST['page']>=0)
			$pages->currentPage = (int)$_POST['page'] - 1;
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $student_seminar = RsStudent::model()->findAll($criteria);
        $this->renderPartial('_editRegisteredSeminarList',array(
			'student_seminar' => $student_seminar,
			'seminar_id' => $_POST['id_seminar'],
			"pages" => $pages));
    }
    public function actionExportRegisteredSeminarList()
    {
        $criteria = new CDbCriteria;
        $criteria->together = true;
        $criteria->with = array('studentSeminars'=>array('select'=>'studentSeminars.seminar_id, studentSeminars.apply_code','together'=>true));
        $criteria->addCondition('seminar_id = :seminar_id AND apply_code IS NOT NULL');
        $criteria->params = array();
        $criteria->params[':seminar_id'] = $_POST['id_seminar'];
        $student_seminar = RsStudent::model()->findAll($criteria);
        $seminar = RsSeminar::model()->findByPk($_POST['id_seminar']);
        $html = '<h1 style="text-align: center;">'.$seminar->name.'受講予定者一覧</h1></p>';
        $html .= '<div class="widget-box">
                    <div class="widget-content">
                        <table class="table table-bordered table-striped td-center">
                        <thead>
                            <tr>
                                <th>受付番号</th>
                                <th>名大ID</th>
                                <th>名前</th>
                                <th>フリガナ</th>
                                <th>所属</th>
                                <th>学籍・教員番号</th>
                                <th>出席</th>
                            </tr>
                        </thead>
                        <tbody>';
        foreach($student_seminar as $value)
        {
            $faculty = Yii::app()->params['faculty_values'][$value->faculty];
            $apply_code ='';
            if(isset($value->studentSeminars[0]))
                $apply_code = $value->studentSeminars[0]->apply_code;
            $html .= '<tr class="odd gradeX">
                        <td>'.$apply_code.'</td>
                        <td>'.$value->student_code.'</td>
                        <td>'.$value->first_name.'　'.$value->last_name.'</td>
                        <td>'.$value->first_kana.'　'.$value->last_kana.'</td>
                        <td>'.$faculty.'</td>
                        <td>'.$value->professor_code.'</td>
                        <td></td>
                    </tr>';
        }
        $html .='</tbody></table></div></div>';
        $mPDF = Yii::app()->ePdf->mpdf('P','A4',9,'SJIS');
        $mPDF->SetFont('times');
        //$path = Yii::app()->request->baseUrl.'/css/style_pdf.css';
        $path = Yii::getPathOfAlias('webroot').'/css/style_pdf.css';
        $stylesheet = file_get_contents($path);
        $mPDF->WriteHTML($stylesheet,1);
        $mPDF->WriteHTML($html);
        $mPDF->Output($seminar->name.'_受講予定者一覧.pdf','D');
    }


    //page 5
    public function actionSeminarManagement()
    {
        $criteria = new CDbCriteria();
        $criteria->order = 'start_date DESC, from_time ASC, to_time ASC';
        $count=RsSeminar::model()->count($criteria);
        $pages=new CPagination($count);

        // results per page
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $list_seminar = RsSeminar::model()->findAll($criteria);
        // $seminars = $seminarModel->get_today_seminars();
        $this->render('seminarManagement', array('list_seminar' => $list_seminar, "pages" => $pages));
    }
    public function actionNewSeminarManagement()
    {
        $form = new SeminarForm();
        $this->renderPartial('_newSeminarManagement',array('model'=>$form));
    }
    public  function actionEditSeminarManagement()
    {
        $form = new SeminarForm();
		if (isset($_POST['action'])) {
			// update or delete
			if ($_POST['action']=='update') {
				if (isset($_POST['SeminarForm']) && isset($_POST['SeminarForm']['id'])){
					$form->attributes=$_POST['SeminarForm'];
					if ($form->validate()){
						$seminar = RsSeminar::model()->findByPk($_POST['SeminarForm']['id']);
						if ($seminar){
							//#8226	141106
							$seminar->attributes = $form->attributes;
//							$seminar->name = $form->name;
//							$seminar->from_time = $form->from_time;
//							$seminar->to_time = $form->to_time;
//							$seminar->start_date = $form->start_date;
//							$seminar->location = $form->location;
//							$seminar->holding = $form->holding;
							if($seminar->save())
                            {
                                Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の登録は完了しました。"));
                            }else
                            {
                                Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の登録は失敗しました。"));
                            }

							return;
						}
					}
				}
			}elseif ($_POST['action']=='delete' && isset($_POST['id'])){
				if(RsSeminar::model()->deleteByPk($_POST['id'])){
                    Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の削除は完了しました。"));
                }else{
                    Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の削除は失敗しました。"));
                }
				return;
			}
		}else if(isset($_POST['id_seminar']) && $_POST['id_seminar']){
			// edit seminar
            $seminar = RsSeminar::model()->findByPk($_POST['id_seminar']);
            if ($seminar){
				//#8226	141106
				$form->attributes = $seminar->attributes;
                $form->id = $seminar->id;
				$form->start_date = formatDateToJP($form->start_date);
				$form->apply_from_date = formatDateToJP($form->apply_from_date);
				$form->apply_to_date = formatDateToJP($form->apply_to_date);				
//                $form->name = $seminar->name;
//                $form->from_time = $seminar->from_time;
//                $form->to_time = $seminar->to_time;
//                $form->start_date = reFormatDate($seminar->start_date);
//                $form->location = $seminar->location;
//                $form->holding = $seminar->holding;
            }
        }else if (isset($_POST['SeminarForm'])){
			// new Seminar
			$form->attributes=$_POST['SeminarForm'];
			if ($form->validate()){
				$seminar = new RsSeminar();
				//#8226	141106
				$seminar->attributes = $form->attributes;
//				$seminar->name = $form->name;
//				$seminar->from_time = $form->from_time;
//				$seminar->to_time = $form->to_time;
//				$seminar->start_date = $form->start_date;
//				$seminar->location = $form->location;
//				$seminar->holding = $form->holding;
				if($seminar->save()){
                    Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の登録は完了しました。"));
                }else{
                    Yii::app()->user->setFlash('success',Yii::t("backend", "セミナー情報の登録は失敗しました。"));
                }
				return;
			}
            $this->renderPartial('_newSeminarManagement',array('model'=>$form));
            return;
		}else{
			$this->renderPartial('_newSeminarManagement',array('model'=>$form));
			return;
		}
        $this->renderPartial('_editSeminarManagement',array('model'=>$form));
    }
	// 3.1 Seminar list - show list of today's seminars only
	public function actionTodaySeminarList()
    {
    	$criteria=new CDbCriteria();
    	$criteria->condition='date(start_date)=:today';
    	$criteria->params=array('today'=>date('Y/m/d'));

	    $count=RsSeminar::model()->count($criteria);
	    $pages=new CPagination($count);

	    // results per page
	    $pages->pageSize=10;
	    $pages->applyLimit($criteria);
        $criteria->order = "from_time ASC";
	    $models=RsSeminar::model()->findAll($criteria);
        $this->render('todaySeminarList', array('seminars' => $models, "pages" => $pages));
    }
    // page 3.1.1 manage the seminar - manage today's seminar
    public function actionEditTodaySeminarList() {
    	if (!isset($_GET['s_id'])) {
    		$this->redirect(array('admin/seminar/todaySeminarList'));
    	}
    	$s_id = $_GET['s_id'];
    	
    	$criteria = new CDbCriteria;
        $criteria->together = true;
        $criteria->with = array(
			'studentSeminars'=>array(
				'select'=>'studentSeminars.seminar_id, studentSeminars.apply_code, studentSeminars.attended',
				'on' => 'seminar_id = ' . $s_id,
			)
		);

        $criteria->params = array();

		if($_REQUEST) {    		
    		$search_params = array();
    		if (isset($_REQUEST['s_today_register']) && $_REQUEST['s_today_register'] == '1') {
    			$criteria->addCondition('apply_code IS NULL');
    			$search_params['s_today_register'] = 1 ;
               
    		} else {
                $criteria->addCondition('apply_code IS NOT NULL');
    			if (isset($_REQUEST['s_apply_code']) && $_REQUEST['s_apply_code']) {
    				$criteria->addCondition('apply_code LIKE :apply_code');
    				$criteria->params[':apply_code'] = '%' . $_REQUEST['s_apply_code'];
    				$search_params['s_apply_code'] = $_REQUEST['s_apply_code'];
    			}
    		}

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
    	}

        $count = RsStudent::model()->count($criteria);
        $pages=new CPagination($count);
        // results per page
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $criteria->order = "student_code ASC";
        $students = RsStudent::model()->findAll($criteria);

		$seminar_name = RsSeminar::model()->findByPk($s_id) ? RsSeminar::model()->findByPk($s_id)->name : "";


		$data['post'] = $_REQUEST;
   		$data['seminar_name'] = $seminar_name;
	    $data['s_id'] = $s_id;
	    $data['pages'] = $pages;
	    $data['search_params'] = $search_params;
	    $data['students'] = $students;
        $this->render('editTodaySeminarList', $data);
    }

    public function actionConfirmAttended() {
    	$action = $_POST['action'];
    	$st_id = $_POST['st_id'];
    	$s_id = $_POST['s_id'];
    	$student = RsStudentSeminar::model()->find('student_id=:st_id AND seminar_id=:s_id',array(
    		':st_id' => $st_id,
    		':s_id' => $s_id,
    		));
    	switch ($action) {
    		case 1:
    			$student->attended = 1;
                if ($student->save()) {
                    Yii::app()->user->setFlash('success',Yii::t("backend", Yii::t("admin", "seminar.confirm.attention.success")));
                } else {
                    Yii::app()->user->setFlash('success',Yii::t("backend", Yii::t("admin", "seminar.confirm.attention.fail")));
                }
    			break;
    		case 2:
    			$student = new RsStudentSeminar;
				$student->student_id = $st_id;
				$student->seminar_id = $s_id;
				$student->attended = 1;
                if ($student->save()) {
                    Yii::app()->user->setFlash('success',Yii::t("backend", Yii::t("admin", "seminar.todayRegister.success")));
                } else {
                    Yii::app()->user->setFlash('error',Yii::t("backend", Yii::t("admin", "seminar.todayRegister.fail")));
                }
				break;
    		case 3: // change attention status to 0
    			$student->attended = 0;
    			if ($student->save()) {
                    Yii::app()->user->setFlash('success',Yii::t("backend", Yii::t("admin", "seminar.remove.attention.success")));
                } else {
                    Yii::app()->user->setFlash('error',Yii::t("backend", Yii::t("admin", "seminar.remove.attention.fail")));
                }
    			break;
    		default:
    			# code...
    			break;
    	}
        // if ($student->update() OR $student->save()) {
        //     Yii::app()->user->setFlash('success',Yii::t("backend", "出席登録は完了しました。"));
        // } else {
        //     Yii::app()->user->setFlash('success',Yii::t("backend", "出席登録は失敗しました。"));
        // }
    }

    public function actionFinishedSeminarList() {
    	$data = array();

    	$criteria=new CDbCriteria;
    	$criteria->condition = "to_timestamp(to_date(start_date::text,'YYYY-MM-DD') || ' ' || COALESCE(to_time,'00:00') || ':00', 'YYYY-MM-DD HH24:MI:SS')<NOW()";
        $criteria->order = 'start_date DESC, from_time ASC, to_time ASC';
	    $count=RsSeminar::model()->count($criteria);
	    $pages=new CPagination($count);

	    // results per page
	    $pages->pageSize=10;
	    $pages->applyLimit($criteria);
	    $seminars=RsSeminar::model()->findAll($criteria);
	    $data = array(
	    	'seminars' => $seminars,
	    	"pages" => $pages
	    	);
        $this->render('finishedSeminarList', $data);
    }

    public function actionEditFinishedSeminarList() {
    	if (!isset($_POST['s_id'])) {
    		echo "There's no such Seminar";
    		return;
    	}
    	$data = array();
        $response = array();
    	$s_id = $_POST['s_id'];
    	
    	$criteria = new CDbCriteria;
        $criteria->together = true;
        $criteria->with = array('studentSeminars'=>array('select'=>'studentSeminars.seminar_id, studentSeminars.apply_code,studentSeminars.attended','together'=>true));

        $criteria->params = array();
        $criteria->addCondition('seminar_id = :s_id');
        $criteria->addCondition('attended = 1');
        $criteria->params[':s_id'] = $s_id;
        $count = RsStudent::model()->count($criteria);
        $pages=new CPagination($count);
        // results per page
        if (isset($_POST['page']) && (int)$_POST['page']>=0)
            $pages->currentPage = (int)$_POST['page'] - 1;
        $pages->pageSize = 10;
        $pages->applyLimit($criteria);
        $criteria->order = "reg_code ASC";
        $students = RsStudent::model()->findAll($criteria);

	    $data['s_id'] = $s_id;
	    $data['pages'] = $pages;
	    $data['students'] = $students;
    	
        // print_r($pages); return;
        $response['title'] = $seminar_name = RsSeminar::model()->findByPk($s_id)->name;
        $response['content'] = $this->renderPartial('_editFinishedSeminarList', $data, true); //set true to return html string instead of rendering it out.
        echo json_encode($response);
    }
        /*
	public function actionAjaxTest() {
		$response = array();
		$response['save']="ok";
		$response['save1']="ok";
		//header('Content-Type: application/json; charset="UTF-8"');
		//echo json_encode($response);
		 echo date('d',strtotime("2014-11-5 00:00:00")); 
	
	}
        */
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