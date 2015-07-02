<?php
class SeminarForm extends CFormModel {
    public $id;
    public $name;
    public $from_time;
    public $to_time;
    public $start_date;
    public $location;
    public $holding;
	//#8226	141106
	public $lecturer;
	public $outline;
	public $location_url;
	public $apply_from_date;
	public $apply_to_date;
	
    //#8226	141106
	public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('name,lecturer,outline,start_date,from_time,to_time,location,apply_from_date,apply_to_date,holding', 'required'), 
			array('name,lecturer', 'required'),
            array('name, lecturer, location_url', 'length', 'max'=>50),
			array('outline', 'required'),
			array('outline', 'length', 'max'=>400),
			array('start_date', 'required'),
            array('start_date', 'type', 'type' => 'date', 'message' => '開催日'.Yii::t('admin','seminar.formatdate'),'dateFormat' => 'yyyy/MM/dd'),
			array('from_time,to_time', 'required'),
            array('from_time, to_time', 'length', 'max'=>8),
			array('to_time, from_time', 'validateTime', 'message' => Yii::t('admin','seminar.formatdate')),
            array('to_time', 'compareFromTime', 'from_time' => $this->from_time, 'message' => Yii::t('admin','er_comparetotime_semr')),	
			array('location', 'required'),
            array('location, holding', 'length', 'max'=>20),
			array('location_url','url'),
			array('apply_from_date,apply_to_date,holding', 'required'), 
            array('apply_from_date', 'date', 'message' => '{attribute}'.Yii::t('admin','seminar.formatdate'),'format' => 'yyyy/MM/dd'),
			array('apply_from_date', 'compare', 'operator'=>'<=','compareAttribute'=>'start_date','allowEmpty'=>true),
			array('apply_to_date', 'date', 'message' => '{attribute}'.Yii::t('admin','seminar.formatdate'),'format' => 'yyyy/MM/dd'),
			array('apply_to_date', 'compare', 'operator'=>'<=','compareAttribute'=>'start_date','allowEmpty'=>true),
			array('apply_to_date', 'compare', 'operator'=>'>=','compareAttribute'=>'apply_from_date','allowEmpty'=>true),
			array('flag, id', 'safe'),
			array('name,lecturer,outline,start_date,from_time,to_time,location,location_url,apply_from_date,apply_to_date,holding', 'safe'), 
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, name, from_time, to_time, start_date, location, holding', 'safe', 'on'=>'search'),
            //array(' name, from_time, to_time, start_date, location , holding', 'required')
        );
    }
    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'name' => 'セミナー名称',
            'from_time' => '開催時間（From）',
            'to_time' => '開催時間（To）',
            'start_date' => '開催日',
            'location' => '開催場所',
            'holding' => '開催機関',
            'date'=>'開催時間 <span class="required">*</span>',
			//#8226 141106
			'lecturer' => '講師',
			'outline' => '概要',
			'location_url' => '開催場所URL',
			'apply_from_date' => '申し込み期間（From）',
			'apply_to_date' => '申し込み期間（To）',
        );
    }
	public function  generateCodeID($id){

		var_dump($id);exit;

		$reg_code = '';
		$code = generateRandomString(8).$id;
		$reg_code = substr($code, 2, 8);
		return $reg_code;
	}

    public function compareFromTime($attribute,$params)
    {
        if($this->$attribute !='')
        {
            $array_totime = explode(':',$this->$attribute);
            $array_fromtime = explode(':',$params['from_time']);
            if(isset($array_totime[1]))
            {
                $to_time = strtotime($this->to_time);
            }else
            {
                $to_time = strtotime($this->to_time.':00');
            }
            if(isset($array_fromtime[1]))
            {
                $from_time = strtotime($this->from_time);
            }else
            {
                $from_time = strtotime($this->from_time.':00');
            }
            if($to_time < $from_time)
            {
                $this->addError($attribute, $params['message']);
            }
        }
    }
    public function validateTime($attribute,$params)
    {
        if($this->$attribute !='')
        {
            $array_time = explode(':',$this->$attribute);
            if(isset($array_time[0]))
            {
                if(!preg_match("/^[0-9]+$/", $array_time[0]))
                {
                    $this->addError($attribute, $this->getAttributeLabel($attribute).' '.$params['message']);
                }else
                {
                    if((int)$array_time[0] < 0 | (int)$array_time[0] > 23)
                    {
                        $this->addError($attribute, $this->getAttributeLabel($attribute).' '.$params['message']);
                    }
                }
            }
            if(isset($array_time[1]))
            {
                if(!preg_match("/^[0-9]+$/", $array_time[1]))
                {
                    $this->addError($attribute, $this->getAttributeLabel($attribute).' '.$params['message']);
                }else
                {
                    if((int)$array_time[1] < 0 | (int)$array_time[1] > 59)
                    {
                        $this->addError($attribute, $this->getAttributeLabel($attribute).' '.$params['message']);
                    }
                }
            }
        }
    }
} 