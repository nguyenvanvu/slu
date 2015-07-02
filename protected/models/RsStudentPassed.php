<?php

/**
 * This is the model class for table "rs_student_passed".
 *
 * The followings are the available columns in table 'rs_student_passed':
 * @property integer $id
 * @property string $student_code
 * @property string $school_reg_code
 * @property string $staff_code
 */
class RsStudentPassed extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_student_passed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_code, school_reg_code, staff_code', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, student_code, school_reg_code, staff_code', 'safe', 'on'=>'search'),
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
			'student_code' => 'Student Code',
			'school_reg_code' => 'School Reg Code',
			'staff_code' => 'Staff Code',
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
		$criteria->compare('student_code',$this->student_code,true);
		$criteria->compare('school_reg_code',$this->school_reg_code,true);
		$criteria->compare('staff_code',$this->staff_code,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsStudentPassed the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**get student can test**/
	public function studentCanTest($student_id,$student_code){
		$sql = "
				(select distinct 1 from rs_student_seminar
					 inner join rs_seminar
					  on rs_seminar.id = rs_student_seminar.seminar_id
					  and rs_student_seminar.student_id = ".$student_id."
					  and rs_student_seminar.attended = 1
					 group by rs_student_seminar.student_id, holding
					 having (holding = 0 and count(*) >= 2)  or (holding = 1 and count(*) >= 1)
					)
					union
					(
					select distinct 1 from rs_student_passed
					 where rs_student_passed.student_code = '$student_code'
					)
		";

		return $sql;
	}
}
