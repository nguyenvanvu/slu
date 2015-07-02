<?php

/**
 * This is the model class for table "rs_test".
 *
 * The followings are the available columns in table 'rs_test':
 * @property integer $id
 * @property string $title
 * @property string $date1
 * @property string $date2
 * @property string $remark
 * @property integer $am
 * @property integer $point
 * @property integer $period
 * @property string $flag
 * @property string $category
 * @property string $category_am
 */
class RsTest extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_test';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, date1, date2, am, point', 'required'),
			array('am, point, period', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>30),
			array('flag', 'length', 'max'=>3),
			array('remark, category, category_am', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, title, date1, date2, remark, am, point, period, flag, category, category_am', 'safe', 'on'=>'search'),
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
			'title' => 'Title',
			'date1' => 'Date1',
			'date2' => 'Date2',
			'remark' => 'Remark',
			'am' => 'Am',
			'point' => 'Point',
			'period' => 'Period',
			'flag' => 'Flag',
			'category' => 'Category',
			'category_am' => 'Category Am',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('date1',$this->date1,true);
		$criteria->compare('date2',$this->date2,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('am',$this->am);
		$criteria->compare('point',$this->point);
		$criteria->compare('period',$this->period);
		$criteria->compare('flag',$this->flag,true);
		$criteria->compare('category',$this->category,true);
		$criteria->compare('category_am',$this->category_am,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsTest the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
