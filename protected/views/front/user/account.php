<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','account.page_title');
?>
<h1 class="">アカウント修正</h1>
<div class="span6 offset4">
	<div class="row-fluid">

		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'box-account',
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
			'htmlOptions'=>array(
				'class'=>'form-horizontal',
			),
		)); ?>
		<?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
	<div class="box-border">
		<div class="control-group">
			<?php echo $form->labelEx($model, 'student_code', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->textField($model,'student_code',array('id'=>'student_code','class'=>'span15 ime-disabled')); ?>
			</div>

			<label class="control-label align-left-label required" for="RegisterForm_student_code">
				名前
				<span class="required">*</span>
			</label>
			<div class="controls controls-row">
				<?php echo $form->textField($model,'first_name',array('id'=>'first_name','class'=>'span6')); ?>
				<?php echo $form->textField($model,'last_name',array('id'=>'last_name','class'=>'span6')); ?>

			</div>

			<label class="control-label align-left-label required" for="RegisterForm_student_code">
				フリガナ
				<span class="required">*</span>
			</label>
			<div class="controls controls-row">
				<?php echo $form->textField($model,'first_kana',array('id'=>'first_kana','class'=>'span6')); ?>
				<?php echo $form->textField($model,'last_kana',array('id'=>'last_kana','class'=>'span6')); ?>
			</div>

			<?php echo $form->labelEx($model, 'faculty', array('class'=>'control-label align-left-label')); ?>
			<div class="controls horizontal">
				<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'0','uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][0]; ?></label>
				<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'1' ,'uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][1]; ?></label>
				<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'2' ,'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][2]; ?></label>
				<label class=""><?php echo $form->radioButton($model,'faculty',array('value'=>'3' ,'uncheckValue'=>null)); ?> <?php echo Yii::app()->params['faculty_values'][3]; ?></label>
			</div>
			
			<?php //#8230	141117
			echo $form->labelEx($model, 'faculty_name', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->dropDownList($model, 'faculty_name',Yii::app()->params['faculty_name'],array('class'=>'')); ?>
			</div>

			<?php echo $form->labelEx($model, 'professor_code', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->textField($model,'professor_code',array('id'=>'professor_code','class'=>'span15 ime-disabled')); ?>

			</div>

			<?php echo $form->labelEx($model, 'email', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->textField($model,'email',array('id'=>'email','class'=>'span15 ime-disabled')); ?>

			</div>

			<?php echo $form->labelEx($model, 'password', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->passwordField($model,'password',array('id'=>'password','class'=>'span15 ime-disabled')); ?>
			</div>

			<?php echo $form->labelEx($model, 'repeat_password', array('class'=>'control-label align-left-label')); ?>
			<div class="controls">
				<?php echo $form->passwordField($model,'repeat_password',array('id'=>'repeat_password','class'=>'span15 ime-disabled')); ?>
			</div>
			<div class="center-actions">
				<a class="btn span4 btn-danger delete-promp" href="javascript:DeleteMessageDialog(<?php echo $info->id; ?>);">削除</a>
				<?php echo CHtml::submitButton('更新',array('id'=>'update','class'=>"btn span4 btn-primary change-promp" ,'confirm'=> "受講者情報を更新します。\nよろしいですか？")); ?>
			</div>
		</div>
	</div>

		<?php $this->endWidget(); ?>
	</div>
</div>

<script language="javascript">

	function DeleteMessageDialog(id)
	{
		if (confirm("受講者情報を削除します。\nよろしいですか？")){
			$.ajax({
				type: "POST",
				data: {id: id},
				url: "<?php echo Yii::app()->createUrl('front/user/DeleteAccount'); ?>",
				success:function(html){
					if (typeof html.status != 'undefined')
					{
						if (html.status>0)
						{

							window.location.href="<?php echo Yii::app()->createUrl('/login'); ?>";
						}
					}
				}
			});
		}
	}

</script>