<?php

/**
 * This is the model class for table "rs_question".
 *
 * The followings are the available columns in table 'rs_question':
 * @property integer $id
 * @property integer $test_id
 * @property integer $no
 * @property integer $answer
 * @property string $exp
 * @property string $q
 * @property integer $category
 * @property integer $category_no
 */
class RsQuestion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_question';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
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
			'no' => 'No',
			'answer' => 'Answer',
			'exp' => 'Exp',
			'q' => 'Q',
			'category' => 'Category',
			'category_no' => 'Category No',
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
		$criteria->compare('no',$this->no);
		$criteria->compare('answer',$this->answer);
		$criteria->compare('exp',$this->exp,true);
		$criteria->compare('q',$this->q,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('category_no',$this->category_no);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsQuestion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
