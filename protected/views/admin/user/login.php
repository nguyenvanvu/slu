<?php
/* @var $this UserController */
$this->pageTitle = Yii::t('admin','login.page_title');
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

		<div class="control-group normal_text"> <h3>臨床研究認定管理者サイトログイン</h3></div>
		<?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
		<div class="control-group">
			<div class="controls">
				<div class="main_input_box">
					<span class="label-input">管理者ID</span><?php echo $form->textField($model,'username',array('id'=>'username','style'=>'ime-mode: disabled')); ?>
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
			<span class="pull-center">
					<?php echo CHtml::submitButton('ログイン',array('id'=>'login','name'=>'login','class'=>"btn btn-success" )); ?>
			</span>
		</div>
	<?php $this->endWidget(); ?>


</div>

<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/maruti.login.js"></script>
</body>

</html>

