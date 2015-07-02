<?php

/**
 * This is the model class for table "rs_student".
 *
 * The followings are the available columns in table 'rs_student':
 * @property integer $id
 * @property string $reg_code
 * @property string $student_code
 * @property string $first_name
 * @property string $last_name
 * @property string $first_kana
 * @property string $last_kana
 * @property integer $faculty
 * @property string $professor_code
 * @property string $email
 * @property string $password
 */
class RsStudent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rs_student';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('student_code, first_name, last_name, professor_code, email, password, faculty_name', 'required'),
			array('faculty', 'numerical', 'integerOnly'=>true),
			array('reg_code, student_code, first_name, last_name, first_kana, last_kana, professor_code, ', 'length', 'max'=>20),
			array('email', 'length', 'max'=>50),
			array('password', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, reg_code, student_code, first_name, last_name, first_kana, last_kana, faculty, professor_code, email, password', 'safe', 'on'=>'search'),
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
            'studentSeminars' => array(self::HAS_MANY, 'RsStudentSeminar', 'student_id'),
            'studentTestResult' => array(self::HAS_MANY,'RsTestResult','student_id')
        );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'reg_code' => 'Reg Code',
			'student_code' => 'Student Code',
			'first_name' => 'First Name',
			'last_name' => 'Last Name',
			'first_kana' => 'First Kana',
			'last_kana' => 'Last Kana',
			'faculty' => 'Faculty',
			'professor_code' => 'Professor Code',
			'email' => 'Email',
			'password' => 'Password',
            'faculty_name'=>'Faculty Name'
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
		$criteria->compare('reg_code',$this->reg_code,true);
		$criteria->compare('student_code',$this->student_code,true);
		$criteria->compare('first_name',$this->first_name,true);
		$criteria->compare('last_name',$this->last_name,true);
		$criteria->compare('first_kana',$this->first_kana,true);
		$criteria->compare('last_kana',$this->last_kana,true);
		$criteria->compare('faculty',$this->faculty);
		$criteria->compare('professor_code',$this->professor_code,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function sql_status($aCond=array()){
        //#8227	141011
		$sql_pof = 		'select rs_student.*, 0 as status, passed_date :: VARCHAR, 1 as status_rank   
						from rs_student inner join (SELECT student_id, pof, MIN ("date") AS passed_date
													FROM rs_test_result 
													'.(empty($aCond['date'])?'':("WHERE (".$aCond['date'].")")).'
													GROUP BY student_id, pof
													) AS rs_test_result on rs_test_result.student_id = rs_student.id and pof = 1';
        $sql_not_pof = 	'select rs_student.*, 2 as status, NULL AS passed_date, 2 as status_rank
						from rs_student inner join rs_test_result on rs_test_result.student_id = rs_student.id and pof = 0 '.
						(empty($aCond['date'])?'':("AND (".$aCond['date'].")"));
        $sql_eligible = 'select rs_student.*, 1 as status, NULL AS passed_date, 3 as status_rank
						from 
							rs_student
							inner join rs_student_seminar on rs_student_seminar.student_id = rs_student.id and rs_student_seminar.attended = 1
							inner join rs_seminar on rs_seminar.id = rs_student_seminar.seminar_id
						group by 
							rs_student.id, rs_seminar.holding, rs_student.reg_code, rs_student.student_code, 
							rs_student.first_name, rs_student.last_name, rs_student.first_kana, rs_student.last_kana, 
							rs_student.faculty, rs_student.professor_code, rs_student.email, rs_student.password,
							rs_student.faculty_name, rs_student.schedule_date
						having (holding = 0 and count(*) >= 2)  or (holding = 1 and count(*) >= 1)
						union
						select rs_student.*, 1 as status, NULL AS passed_date, 3 as status_rank 
						from 
							rs_student
							inner join rs_student_passed on rs_student_passed.student_code = rs_student.student_code';
        $sql_status = array();
        $sql_status['sql_pof'] = $sql_pof;
        $sql_status['sql_not_pof'] = $sql_not_pof;
        $sql_status['sql_eligible'] = $sql_eligible;
        //$sql = 'select DISTINCT ON (id) * from (('.$sql_pof.') union ('.$sql_eligible.') union ('.$sql_not_pof.')) as tbl order by id,status';
		$sql = 'select DISTINCT ON (id) * from (('.$sql_pof.') union ('.$sql_eligible.') union ('.$sql_not_pof.')) as tbl order by id,status_rank';
		$sql_status['sql'] = $sql;
        return $sql_status;
    }
    public function data_query($sql)
    {
        $Command = Yii::app()->db->createCommand($sql);
        $list_student = $Command->queryAll();
        return $list_student;
    }


	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RsStudent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
