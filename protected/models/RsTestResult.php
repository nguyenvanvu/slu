<?php

/**
 * This is the model class for table "rs_test_result".
 *
 * The followings are the available columns in table 'rs_test_result':
 * @property integer $id
 * @property integer $test_id
 * @property integer $student_id
 * @property string $date
 * @property integer $pof
 * @property integer $point
 * @property integer $am
 * @property string $answer_list
 * @property string $test_title
 * @property integer $test_period
 * @property string $student_name
 * @property string $category
 * @property string $category_no
 * @property string $mark
 */
class RsTestResult extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_test_result';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('test_id, student_id, pof, point, am, test_period', 'numerical', 'integerOnly'=>true),
			array('test_title, student_name', 'length', 'max'=>30),
			array('date, answer_list, category, category_no, mark', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, test_id, student_id, date, pof, point, am, answer_list, test_title, test_period, student_name, category, category_no, mark', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'test_id' => 'Test',
			'student_id' => 'Student',
			'date' => 'Date',
			'pof' => 'Pof',
			'point' => 'Point',
			'am' => 'Am',
			'answer_list' => 'Answer List',
			'test_title' => 'Test Title',
			'test_period' => 'Test Period',
			'student_name' => 'Student Name',
			'category' => 'Category',
			'category_no' => 'Category No',
			'mark' => 'Mark',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('test_id',$this->test_id);
		$criteria->compare('student_id',$this->student_id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('pof',$this->pof);
		$criteria->compare('point',$this->point);
		$criteria->compare('am',$this->am);
		$criteria->compare('answer_list',$this->answer_list,true);
		$criteria->compare('test_title',$this->test_title,true);
		$criteria->compare('test_period',$this->test_period);
		$criteria->compare('student_name',$this->student_name,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('category_no',$this->category_no,true);
		$criteria->compare('mark',$this->mark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsTestResult the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
