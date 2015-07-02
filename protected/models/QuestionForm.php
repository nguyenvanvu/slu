<?php
class QuestionForm extends CFormModel {
	public $id;
	public $test_id;
	public $no;
	public $answer;
	public $category;
	public $category_no;
	public $exp;
	public $q;

	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_id, no, answer, category, category_no', 'numerical', 'integerOnly'=>true),
			array('exp, q', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, test_id, no, answer, exp, q, category, category_no', 'safe', 'on'=>'search'),
			array('q, exp, answer', 'required'),
			array('answer', 'numerical', 'min'=>1),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'test_id' => 'Test',
			'no' => 'No',
			'answer' => '正解No',
			'exp' => '解説',
			'q' => '問題',
			'category' => 'Category',
			'category_no' => 'Category No',
		);
	}

}
