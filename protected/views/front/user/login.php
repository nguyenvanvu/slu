<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','login.page_title');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title><meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/maruti-login.css" />
</head>
<body>
<div id="loginbox">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'box-login',
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions'=>array(
			'class'=>'form-vertical',
		),
	)); ?>
	<div class="control-group normal_text"> <h3>臨床研究認定ポータルサイトログイン</h3></div>
	<?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
	<div class="control-group">
		<div class="controls">
			<div class="main_input_box">
				<span class="label-input">臨床研究認定ID</span><?php echo $form->textField($model,'reg_code',array('id'=>'reg_code','style'=>'ime-mode: disabled')); ?>
			</div>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<div class="main_input_box">
				<span class="label-input" >パスワード</span><?php echo $form->passwordField($model,'password',array('id'=>'password','style'=>'ime-mode: disabled')); ?>
			</div>
		</div>
	</div>
	<div class="form-actions">
		<span	>
			<span class="pull-center"><input type="button" name="register" class="flip-link btn btn-primary span2" value="新規登録" onclick="javascript:location.href='<?php echo Yii::app()->createUrl('front/user/register'); ?>'"/></span>
			<?php echo CHtml::submitButton('ログイン',array('id'=>'login','name'=>'login','class'=>"btn btn-success span2" )); ?>
		</span>
	</div>
	<?php $this->endWidget(); ?>


</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>

</body>

</html>

