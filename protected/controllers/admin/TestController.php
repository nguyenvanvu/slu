<?php

class TestController extends Controller
{
	public $layout='//layouts/backend';

	public function actionIndex()
	{
		$this->render('index');

	}

	public function actionTestList() {
		$form = new TestControlForm();
        $mess_info="";
		// for page's events
	    if (isset($_POST['action'])) {
	    	$result = array();
	    	switch ($_POST['action']) {
	    		case 1: // register
					$form = new TestControlForm();
	    			$form->attributes=$_POST['TestControlForm'];
	    			if ($form->validate()) {
		    			$test = new RsTest;

	    				$test->attributes=$_POST['TestControlForm'];

	    				$temp1 = array(); // temporary for category string
	    				$temp2 = array(); // temporary for category_am string
	    				for ($i=1; $i <= $test->am; $i++) {
	    					array_push($temp1, $i);
	    					array_push($temp2, 0);
	    				}
	    				// init category values, it will be 1,2,3,4...
	    				$test->category = join($temp1, ",");
	    				// init category_am values, it will be 0,0,0,0...
	    				$test->category_am = join($temp2, ",");

		    			// return save result
		    			if ($test->save()) {
		    				$result["status"] = 1;
		    				$result['message']= "Success";
		    				$result["id"] = $test->id;
		    			} else {
		    				$result["status"] = 0;
		    				$result['message']= "Fail";
	                    	Yii::app()->user->setFlash('success',Yii::t("admin", "test.register.fail"));
		    			}
		    		}
	    		    if ($form->hasErrors()) {
	    				$result["status"] = 2;
	    				$result['message'] = CHtml::errorSummary($form, '', '', array('class' => 'alert alert-error alert-block hide-message'));
	    			}
	    			break;
	    		case 2: // edit
	    			$form = new TestControlForm();
	    			$form->attributes=$_POST['TestControlForm'];
	    			// echo json_encode($form); exit;
	    			if ($form->validate()) {
		    			$test=RsTest::model()->findByPk($form->id);

						$test->attributes=$_POST['TestControlForm'];

						if ($test->am < $test->point) {
							$result["status"] = 0;
							$result['message']= "Fail";
							Yii::app()->user->setFlash('success',Yii::t("admin", "test.edit.fail"));
							break;
						}
						$temp1 = array();
						$temp2 = explode(",", $test->category_am);

						// if am value is smaller than current category_am string
						// then remove the extra category_am values
						if ($test->am < count($temp2)) {
							array_splice($temp2, $test->am);
						}
						for ($i=1; $i <= $test->am; $i++) {
							array_push($temp1, $i);
							// if am value is bigger than current category_am
							// then add zeros into these positions
							if (!isset($temp2[$i-1])) {
								array_push($temp2, 0);
							}
						}
						$test->category = join($temp1, ",");
						$test->category_am = join($temp2, ",");

		    			if ($test->save()) {
		    				$result["status"] = 1;
		    				$result['message']= "Success";
		    				$result["id"] = $test->id;
		    			} else {
		    				$result["status"] = 0;
		    				$result['message']= "Fail";
	                    	Yii::app()->user->setFlash('success',Yii::t("admin", "test.edit.fail"));
		    			}
		    		}
		    		if ($form->hasErrors()){
		    			$result["status"] = 2;
	    				$result['message'] = CHtml::errorSummary($form, '', '', array('class' => 'alert alert-error alert-block hide-message'));
	    			}
	    			break;
	    		case 3: // delete
	    			$test=RsTest::model()->findByPk($_POST['id']);
	    			if ($test->delete()) {
	    				$result['status'] = 1;
	    				$result['message']= "Success";
                    	Yii::app()->user->setFlash('success',Yii::t("admin", "test.delete.success"));
	    			} else {
	                    Yii::app()->user->setFlash('success',Yii::t("admin", "test.delete.fail"));
	                }
	    			break;
	    		case 4: // update specific category_am
	    			if (isset($_POST['data']['question_amount']) AND is_numeric($_POST['data']['question_amount']) AND $_POST['data']['question_amount'] > 0) {
		    			$test=RsTest::model()->findByPk($_POST['data']['id']);
		    			// convert this value into array
		    			$temp2 = explode(",", $test->category_am);
		    			// then replace the requested position by $_POST value
		    			$temp2[$_POST['data']['category_id']-1] =  $_POST['data']['question_amount'];
		    			$test->category_am = join($temp2, ",");
		    			if ($test->save()) {
		    				$result["status"] = 1;
		    				$result['message']= "Success";
		    				$result["id"] = $test->id;
		    			} else {
		    				$result["status"] = 0;
		    				$result['message']= "Fail";
		    				Yii::app()->user->setFlash('success',Yii::t("admin", "test.update.category.fail"));
		    			}
		    		} else {
		    			// var_dump($_POST); exit;
		    			$result["status"] = 0;
		    			$result['message']= Yii::t("admin", "test.update.category.fail");
		    		}
	    			break;
	    		case 5: //update all categories
	    			$test=RsTest::model()->findByPk($_POST['data']['id']);
	    			// convert this value into array
	    			// data contain only category_am
	    			$validate = true;
	    			foreach ($temp = explode(",", $_POST['data']['category_am']) as $number) {
    					if (!is_numeric($number) OR $number <= 0) {
    						$result["status"] = 0;
    						$result['message']= Yii::t("admin", "test.update.allCategory.fail");
    						$validate = false;
    					}
    				}

    				if ($validate) {
		    			foreach ($_POST['data'] as $key => $value) {
		    				$test->$key = $value;
		    			}

		    			if ($test->save()) {
		    				$result["status"] = 1;
		    				$result['message']= Yii::t("admin", "test.update.allCategory.success");
		    				$result["id"] = $test->id;
		    			} else {
		    				$result["status"] = 0;
		    				$result['message']= Yii::t("admin", "test.update.allCategory.fail");
		    			}
		    		}
	    			break;
	    		case 6:
	    			$test=RsTest::model()->findByPk($_POST['data']['id']);
	    			// convert this value into array
	    			foreach ($_POST['data'] as $key => $value) {
	    				$test->$key = $value;
	    			}

	    			if ($test->save()) {
	    				$result["status"] = 1;
	    				$result['message']= Yii::t("admin", "test.update.remark.success");
	    				$result["id"] = $test->id;
	    			} else {
	    				$result["status"] = 0;
	    				$result['message']= Yii::t("admin", "test.update.remark.success");
	    			}
	    			break;
	    		default:
	    			# code...
	    			break;
	    	}
	    	echo json_encode($result);
	    	exit;
	    }elseif(isset($_POST["import_data"])){
			setlocale(LC_ALL, 'ja_JP.UTF-8');
            $allowed =  array('csv','CSV');
            $filename = $_FILES['import_file']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if(!in_array($ext,$allowed) ) {
                echo 'error';
            }else{
                $storagename = time()."uploaded_file.csv";
                move_uploaded_file($_FILES["import_file"]["tmp_name"], Yii::app()->params["upload_test_temp"] . $storagename);
                $file = fopen(Yii::app()->params["upload_test_temp"] . $storagename,"r");
                $data=array();
                while(! feof($file))
                {
                    $data[]=fgetcsv($file);
                }

                fclose($file);
                $this->import_data($data);
                unlink(Yii::app()->params["upload_test_temp"]  . $storagename);
                Yii::app()->user->setFlash('success',"合格者情報の取り込みは完了しました");
            }
        }

	    // for initiation
    	$criteria=new CDbCriteria;
    	$criteria->order = "date1 DESC, date2 ASC";
		$count=RsTest::model()->count($criteria);
		$pages=new CPagination($count);
		$pages->pageSize=10;
		$pages->applyLimit($criteria);
	    $tests=RsTest::model()->findAll($criteria);

	    $data = array(
	    	'tests' => $tests,
	    	"pages" => $pages,
	    	'model' => $form
	    	);
		$this->render("testList", $data);
	}

	public function actionTestCategoryList() {
		if (!isset($_GET['t_id'])) {
			$this->redirect(array('admin/test/'));
		}

		$data = array();
		$test = RsTest::model()->findByPk($_GET['t_id']);

		if (!($test)) {
			$this->redirect(array('admin/test/'));
		}

		$data['test'] = $test;

		$this->render('testCategoryList', $data);
	}

	public function actionQuestionList() {
		$form = new QuestionForm();
		// database related activity
		if (isset($_POST['action'])) {
			$response = new stdClass();
			switch ($_POST['action']) {
				case 1:
					// print_r($_POST); exit;
					$form = new QuestionForm();
	    			$form->attributes=$_POST['QuestionForm'];
	    			if ($form->validate()) {
						$data = $_POST['QuestionForm'];
						$test = RsTest::model()->findByPk($data['test_id']);
						if ($test) {
							$data['category_no'] = $test->am;
						}
						$criteria = new CDbCriteria;
						$criteria->condition = 'test_id=:test_id';
						$criteria->addCondition('category=:category');
						$criteria->addCondition('no=:no');
						$criteria->params = array(
							":test_id" => $data['test_id'],
							":category" => $data['category'],
							":no" => $data['no']
							);
						$question = RsQuestion::model()->find($criteria) ? RsQuestion::model()->find($criteria) : new RsQuestion;
						foreach ($data as $key => $value) {
							$question->$key = $value;
						}
						if ($question->save()) {
							$response->status = 1;
							$response->id = $question->id;
							$response->message = Yii::t("admin", "question.save.success");
						} else {
							$response->status = 0;
							$response->message = Yii::t("admin", "question.save.fail");
						}
					}
					if ($form->hasErrors()) {
	    				$response->status = 2;
	    				$response->message = CHtml::errorSummary($form, '', '', array('class' => 'alert alert-error alert-block hide-message'));
	    			}
					break;
				case 2:
					// print_r($_POST);
					$response = new stdClass();
					$response->status = 1;
					$response->message = Yii::t("admin", "question.saveAll.success");
					$validate = true;
					foreach ($_POST['data'] as $data) {
						$form = new QuestionForm();
						foreach ($data as $key => $value) {
							$form->$key = $value;
						}
						$form->validate();
						// print_r($form);
						if ($form->hasErrors()) {
							$validate = false;
		    				$response->status = 2;
		    				$response->message = CHtml::errorSummary($form, '', '', array('class' => 'alert alert-error alert-block hide-message'));
		    			}
					}
					if ($validate) {
						foreach ($_POST['data'] as $data) {
							$test = RsTest::model()->findByPk($data['test_id']);
							if ($test) {
								$data['category_no'] = $test->am;
							}
							$criteria = new CDbCriteria;
							$criteria->condition = 'test_id=:test_id';
							$criteria->addCondition('category=:category');
							$criteria->addCondition('no=:no');
							$criteria->params = array(
								":test_id" => $data['test_id'],
								":category" => $data['category'],
								":no" => $data['no']
								);
							$question = RsQuestion::model()->find($criteria) ? RsQuestion::model()->find($criteria) : new RsQuestion;
							foreach ($data as $key => $value) {
								$question->$key = $value;
							}
							if (!$question->save()) {
								$response->status = 0;
								$response->message = Yii::t("admin", "question.saveAll.fail");
							}
						}
					}
					break;
				default:
					# code...
					break;
			}
			echo json_encode($response);
			exit;
		}

		// if not set t_id then return to test list
		if (!isset($_GET['t_id']))
			$this->redirect(array('admin/test/'));
		// if not set c_id then return to category list of t_id
		if (!isset($_GET['c_id']))
			$this->redirect(array('admin/test/'));

		$criteria = new CDbCriteria;
		$criteria->condition = 'test_id=:test_id';
		$criteria->addCondition('category=:category_id');
		$criteria->order = 'no ASC';
		$criteria->params = array(':test_id' => $_GET['t_id'], ':category_id' => $_GET['c_id']);
		$test = RsTest::model()->findByPk($_GET['t_id']); // whether the requested test exist
		if (!$test) {
			$this->redirect(array('admin/test/'));
		}
		$tempCat = explode(',', $test->category_am);
		if (!isset($tempCat[$_GET['c_id']-1])) { // whether user requested c_id bigger than am
			$this->redirect(array('admin/test/'));
		}

		$tempQuestions = RsQuestion::model()->findAll($criteria);

		$questions = array();
		if ($tempQuestions) {
			foreach ($tempQuestions as $q) {
				$questions[$q->no] = $q;
			}
		}

		$data = array();
		$data['numberOfQuestion'] = $tempCat[$_GET['c_id']-1];

		$data['test'] = $test;
		$data['questions'] = $questions;
		$data['category_id'] = $_GET['c_id'];
		$data['model'] = $form;
		$this->render('questionList', $data);
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
    public function import_data($data){
        $flag=array();
        $list_id=array();
        foreach($data as $value){
            try{
                if(in_array($value[0],$flag)){
                    $question=new RsQuestion();
                    $question->no=$value[9];
                    $question->answer=$value[10];
                    $question->exp=$value[11];
                    $question->q=$value[12];
                    $question->category=$value[13];
                    $question->test_id=$list_id[$value[0]];
                    $question->save();
                }else{
                    $flag[]=$value[0];
                    $test=new RsTest();
                    $test->title=$value[1];
                    $date = new DateTime(str_replace("/","-",$value[2]));
                    $test->date1=$date->format('Y-m-d H:i:s');
                    $date = new DateTime(str_replace("/","-",$value[3]));
                    $test->date2=$date->format('Y-m-d H:i:s');
                    $test->remark=$value[4];
                    $test->am=$value[5];
                    $test->point=$value[6];
                    if(empty($value[7])){
                        $value[7]=0;
                    }
                    $test->flag=$value[7];
                    if(isset($value[8]))
                    {
                        $test->category_am=$value[8];
                    }


                    if($test->save()){
                        $list_id[$value[0]]=$test->id;
                        $question=new RsQuestion();
                        $question->no=$value[9];
                        $question->answer=$value[10];
                        $question->exp=$value[11];
                        $question->q=$value[12];
                        $question->category=$value[13];
                        $question->test_id=$list_id[$value[0]];
                        $question->save();
                    }
                }
            }catch (Exception $e){

            }
        }
    }
}
