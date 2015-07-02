<?php

class TestController extends Controller
{
	public function actionTest()
	{

		$user_id = Yii::app()->user->id;
		$studentInfo = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));

		$studentTestCri = new CDbCriteria;
		$studentTestCri->condition = 'student_id=:st_id';
		$studentTestCri->together = true;
    	$studentTestCri->with = array('student'=>array('select'=>'student.*','together'=>true));
    	$studentTestCri->with = array('test'=>array('select'=>'test.*','together'=>true));
		$studentTestCri->addCondition('date(date)=:today');
		$studentTestCri->order = 't.id DESC';
		$studentTestCri->params = array(':st_id' => $studentInfo->id, ':today' => date('Y/m/d'));
		$studentTest = RsStudentTest::model()->find($studentTestCri);


		if($studentTest)
		{
			// $checkCri = new CDbCriteria;
			// $checkCri->condition = 'student_id=:student_id';
			// $checkCri->addCondition('test_id=:test_id');
			// $checkCri->params = array(
			// 	'test_id' => $studentTest->test_id,
			// 	'student_id' => $studentTest->student_id
			// 	);
			// if (RsTestResult::model()->find($checkCri)) {
			// 	$this->redirect(array('front/test/status'));
			// }
			$questions = array();
			foreach (explode(",", $studentTest->category_no) as $key => $value) {
				$questionsCri = new CDbCriteria;
				$questionsCri->condition = 'test_id=:test_id';
				$questionsCri->addCondition('category=:category');
				$questionsCri->addCondition('no=:question_no');
				$questionsCri->params = array(
					'test_id' => $studentTest->test_id,
					'category' => $key+1,
					'question_no' => $value,
					);
				array_push($questions, RsQuestion::model()->find($questionsCri));
			}
			if (isset($_POST['answer'])) {
				// print_r($_POST['answer']); exit;
				$modalData = array();
				$i = 0;
				foreach ($_POST['answer'] as $key => $value) {
					if ($questions[$key]["answer"] == $value) {
						$i++;
					}
				}
				$result = new RsTestResult;
				$result->test_id = $studentTest->test_id;
				$result->student_id = $studentTest->student_id;
				$result->date = date("Y/m/d H:i:s");
				$result->point = $i;
				$result->am = $studentTest->test->am;
				$result->pof = $i >= $studentTest->test->point ? 1 : 0;
				$result->answer_list = join($_POST['answer'], ",");
				$result->test_title = $studentTest->test->title;
				$result->student_name = $studentTest->student->first_name . "　" .  $studentTest->student->last_name;
				$result->category = $studentTest->category;
				$result->category_no = $studentTest->category_no;
				// echo "<pre>"; var_dump($result);
				$result->save();

				$modalData['questions'] = $questions;
				$modalData['answer'] = $_POST['answer'];
				$modalData['correct'] = $i;
				$modalData['pass'] = $result->pof;
				echo $this->renderPartial('_test', $modalData, true);
			} else {
				$data = array();
				$data['questions'] = $questions;
				$data['test'] = $studentTest;
				$this->render('test', $data);
			}
		} else {
			$this->redirect($this->createUrl('front/test/status'));
		}
	}
	public function actionStatus()
	{
		$can_text = "";
		$hideButton = true;
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));

		$studentPassedModel = new  RsStudentPassed();
		$get_student = $studentPassedModel->studentCanTest($infoStudent->id,$infoStudent->student_code);
		$allData=Yii::app()->db->createCommand($get_student);
		$studentPassed = $allData->queryAll();

		if(count($studentPassed)>0){
			$can_text = "合格";
			$hideButton = true;
		}else{
			$can_text = "受講できません";
			$hideButton = false;
		}
		$this->render('status',array('studentPassed'=>$can_text ,'hideButton' => $hideButton, 'student_id'=>$infoStudent->id));
	}
	public function actionCreateTest()
	{
		$response = array();
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));
		$currentDate = date("Y-m-d H:i:s");
		//var_dump($infoStudent);exit;
		if($infoStudent===null)
		{
			$this->redirect($this->createUrl('/login'));
		}else{
			$criteria = new CDbCriteria;
			$criteria->order = 'id DESC';
			//$criteria->addCondition("id = (SELECT MAX(id) FROM rs_test)");
			$criteria->addCondition("date1 <= '$currentDate'");
			$criteria->addCondition("date2 >= '$currentDate'");
			$criteria->addCondition('flag = 1');

			$tests = RsTest::model()->findAll($criteria);

			$response['status'] = 0;
			if($tests){
				//create test
				$studentTest = new RsStudentTest();
				$studentTest->test_id = $tests[0]->id;
				$studentTest->student_id = $infoStudent->id;
				$studentTest->date = $currentDate;
				$studentTest->category = $tests[0]->category;
				//get number question in category
				$number = $tests[0]->category_am;
				$arr_category = explode(",", $number);
				$arr_random = array();
				foreach($arr_category as $category){
					array_push($arr_random,rand(1,$category));
				}
				$category_no = implode(',',$arr_random);
				//get random category no
				$studentTest->category_no = $category_no;
				if ($studentTest->save()){
					$response['status'] = 1;
				}
			}else{

			}

			echo json_encode($response);
		}
	}
	public function actionHistory()
	{
		$data = array();
		$user_id = Yii::app()->user->id;
		$infoStudent = RsStudent::model()->findByAttributes(array('reg_code'=>$user_id));
		//var_dump($infoStudent);exit;
		if($infoStudent===null)
		{
			$this->redirect($this->createUrl('/login'));
		}else{
			$criteria = new CDbCriteria;
			$criteria->order = 'date DESC';
			$criteria->addCondition('student_id = '.$infoStudent->id.'');

			$count=RsTestResult::model()->count($criteria);
			$pages=new CPagination($count);
			if (isset($_POST['page']) && (int)$_POST['page']>=0)
				$pages->currentPage = (int)$_POST['page'] - 1;
			// results per page
			$pages->pageSize = 10;
			$pages->applyLimit($criteria);

			$history = RsTestResult::model()->findAll($criteria);

			$data['pages'] = $pages;
			$data['testHistory'] = $history;
			echo $this->renderPartial('history', $data, true);

		}
	}

}
