<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','register.page_title');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title><meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/maruti-register.css" />
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/uniform.css" />
</head>
<body>

<div id="registbox">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'box-register',
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
		'htmlOptions'=>array(
			'class'=>'form-vertical',
		),
	)); ?>

	<div class="control-group normal_text"> <h3>アカウント修正</h3></div>
	<?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
	<?php if(Yii::app()->user->hasFlash('save_error')):?>
		<div class="alert alert-error alert-block hide-message">
			<?php echo Yii::app()->user->getFlash('save_error'); ?>
		</div>

	<?php endif; ?>
		<div class="control-group">
			<div class="col">
					<?php echo $form->labelEx($model, 'student_code', array('class'=>'control-label align-left-label')); ?>
					<div class="controls">
						<?php echo $form->textField($model,'student_code',array('id'=>'student_code','class'=>'span10', 'style'=>'ime-mode: disabled')); ?>
					</div>
			</div>
			<div class="col">
				<label class="control-label align-left-label required" for="RegisterForm_student_code">
					名前
					<span class="required">*</span>
				</label>
				<div class="controls controls-row">
					<?php echo $form->textField($model,'first_name',array('id'=>'first_name','class'=>'span_input first span5')); ?>
					<?php echo $form->textField($model,'last_name',array('id'=>'last_name','class'=>'span_input span5')); ?>
				</div>

			</div>
			<div class="col">
				<label class="control-label align-left-label required" for="RegisterForm_student_code">
					フリガナ
					<span class="required">*</span>
				</label>
				<div class="controls controls-row">
					<?php echo $form->textField($model,'first_kana',array('id'=>'first_kana','class'=>'span_input first span5')); ?>
					<?php echo $form->textField($model,'last_kana',array('id'=>'last_kana','class'=>'span_input span5')); ?>
				</div>
			</div>
			<div class="col clearfix">
				<?php echo $form->labelEx($model, 'faculty', array('class'=>'control-label align-left-label')); ?>
				<div class="controls horizontal">
					<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'0','uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][0]; ?></label>
					<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'1' ,'uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][1]; ?></label>
					<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'2' ,'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][2]; ?></label>
					<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'3' ,'uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][3]; ?></label>
				</div>
			</div>
            <div class="col">
                <?php echo $form->labelEx($model, 'faculty_name', array('class'=>'control-label')); ?>
                <div class="controls controls-row">
                    <?php echo $form->dropDownList(
                        $model, 'faculty_name',
                        Yii::app()->params['faculty_name'],
                        array('class'=>'')); ?>
                </div>
            </div>
			<div class="col">
				<?php echo $form->labelEx($model, 'professor_code', array('class'=>'control-label align-left-label')); ?>
				<div class="controls controls-row">
					<?php echo $form->textField($model,'professor_code',array('id'=>'professor_code','class'=>'span10' ,'style'=>'ime-mode: disabled')); ?>
				</div>
			</div>
			<div class="col">
				<?php echo $form->labelEx($model, 'email', array('class'=>'control-label align-left-label')); ?>
				<div class="controls controls-row">
					<?php echo $form->textField($model,'email',array('id'=>'email','class'=>'span10' ,'style'=>'ime-mode: disabled')); ?>
				</div>
			</div>
			<div class="col">
				<?php echo $form->labelEx($model, 'password', array('class'=>'control-label align-left-label')); ?>
				<div class="controls controls-row">
					<?php echo $form->passwordField($model,'password',array('id'=>'password','class'=>'span10' ,'style'=>'ime-mode: disabled')); ?>
				</div>
			</div>
			<div class="col">
				<?php echo $form->labelEx($model, 'repeat_password', array('class'=>'control-label align-left-label')); ?>
				<div class="controls controls-row">
					<?php echo $form->passwordField($model,'repeat_password',array('id'=>'repeat_password','class'=>'span10' ,'style'=>'ime-mode: disabled')); ?>
				</div>
			</div>

		</div>
		<div class="form-actions">
			<span class="pull-center">
				<?php echo CHtml::submitButton('登録',array('id'=>'register','class'=>"btn btn-success span2" )); ?>
				<input type="button" name="login" class="flip-link btn btn-primary span2" value="戻る" onClick="javascript:location.href='<?php echo Yii::app()->createUrl('front/user/login'); ?>'"/>
			</span>
		</div>

	<?php $this->endWidget(); ?>
	<?php
		foreach(Yii::app()->user->getFlashes() as $key => $message) {
			echo '<div class="flash-' . $key . '">' . $message . "</div>\n";
		}
	?>
</div>


<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/maruti.login.js"></script>
<script>
	$(document).ready (function(){

		$('#box-register').submit(function(){
			$('input[type=submit]', this).attr('disabled', 'disabled');
		});

		if($('.flash-showregcode').length > 0){

			var message = $('.flash-showregcode').text();
			alert("登録が完了しました。  \n\n臨床研究認定ID： " + message);
			window.location.href="<?php echo Yii::app()->createUrl('apply'); ?>";
		}


	});
</script>


</body>

</html>



