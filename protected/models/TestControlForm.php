<?php
class TestControlForm extends CFormModel {
	public $id;
	public $title;
	public $date1;
	public $date2;
	public $remark;
	public $am;
	public $point;
	public $period;
	public $flag;
	public $category;
	public $category_am;

	public function rules()
	{
		return array(
			array('title, date1, date2, am, point', 'required'),
			array('am, point, period', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>30),
			array('flag', 'length', 'max'=>3),
			array('id, remark, category, category_am', 'safe'),
			array('id, title, date1, date2, remark, am, point, period, flag, category, category_am', 'safe', 'on'=>'search'),
			// array('title, date1, date2, am, point', 'boolean', 'allowEmpty'=>false),
			array('am, point', 'numerical', 'min'=>1),
			array('date2', 'compareTestDates'),
			array('point', 'compareTestPoints'),
			// array('point', 'compare', 'compareAttribute'=>'am', 'operator' => '<='),
			// array('date2', 'compare', 'compareAttribute'=>'date1', 'operator' => '>'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'title' => '表題',
			'date1' => '試験開始',
			'date2' => '試験終了',
			'remark' => '備考',
			'am' => '問題数',
			'point' => '合格ライン',
			'period' => 'Period',
			'flag' => '表示',
			'category' => 'Category',
			'category_am' => 'Category Am',
		);
	}

	public function compareTestDates($attribute,$params) {
		if ( $this->date1 AND $this->date2 AND date($this->date1) > date($this->date2) ) {
			$this->addError('compareDates',Yii::t("admin","test.compare.dates"));
		}
	}
	public function compareTestPoints($attribute,$params) {
		if ( $this->am AND $this->point AND $this->am < $this->point ) {
			$this->addError('comparePoints',Yii::t("admin","test.compare.points"));
		}
	}
}
