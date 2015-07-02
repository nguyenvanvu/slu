<?php

/**
 * This is the model class for table "rs_seminar".
 *
 * The followings are the available columns in table 'rs_seminar':
 * @property integer $id
 * @property string $name
 * @property string $from_time
 * @property string $to_time
 * @property string $start_date
 * @property string $location
 * @property integer $holding
 * @property string $lecturer
 * @property string $outline
 * @property string $location_url
 * @property string $apply_from_date

 */
class RsSeminar extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_seminar';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, location, holding', 'required'),
			array('holding', 'numerical', 'integerOnly'=>true),
			array('name, lecturer, location_url', 'length', 'max'=>50),
			array('from_time, to_time', 'length', 'max'=>8),
			//array('start_date', 'length', 'max'=>6),
			array('location', 'length', 'max'=>20),
			array('outline', 'length', 'max'=>400),
			array('apply_from_date, start_date, apply_to_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, from_time, to_time, start_date, location, holding, lecturer, outline, location_url, apply_from_date, apply_to_date', 'safe', 'on'=>'search'),
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
			'seminarStudents' => array(self::HAS_MANY, 'RsStudentSeminar', 'seminar_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'from_time' => 'From Time',
			'to_time' => 'To Time',
			'start_date' => 'Start Date',
			'location' => 'Location',
			'holding' => 'Holding',
			//#8226 141106
			'lecturer' => 'Lecturer',
			'outline' => 'Outline',
			'location_url' => 'Location Url',
			'apply_from_date' => 'Apply From Date',
			'apply_to_date' => 'Apply To Date',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('from_time',$this->from_time,true);
		$criteria->compare('to_time',$this->to_time,true);
		$criteria->compare('start_date',$this->start_date,true);
		$criteria->compare('location',$this->location,true);
		//#8226 141106
		$criteria->compare('holding',$this->holding);
		$criteria->compare('lecturer',$this->lecturer,true);
		$criteria->compare('outline',$this->outline,true);
		$criteria->compare('location_url',$this->location_url,true);
		$criteria->compare('apply_from_date',$this->apply_from_date,true);
		$criteria->compare('apply_to_date',$this->apply_to_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public function getTodaySeminars($per_page) {
		$criteria=new CDbCriteria();
	    $count=RsSeminar::model()->count($criteria);
	    $pages=new CPagination($count);

	    // results per page
	    $pages->pageSize=$per_page;
	    $pages->applyLimit($criteria);
	    return $models=RsSeminar::model()->findAll($criteria);
	}

    public function beforeSave(){
        if(parent::beforeSave()){
            $array_totime = explode(':',$this->to_time);
            $array_fromtime = explode(':',$this->from_time);
            if(!isset($array_totime[1]))
            {
                $this->setAttribute('to_time',$this->to_time.':00');
            }
            if(!isset($array_fromtime[1]))
            {
                $this->setAttribute('from_time',$this->from_time.':00');
            }
            return true;
        }else{
            return false;
        }
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsSeminar the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
