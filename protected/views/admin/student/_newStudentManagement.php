<?php  ?>
<div id="edit_studens_box" class="row-fluid">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form_student',
        'enableAjaxValidation'=>false,
        //'focus'=>array($model,'firstName'),
        'htmlOptions'=>array(
            'class' => 'form-horizontal',
            'onsubmit'=>"return false;",/* Disable normal form submit */
        ),
    )); ?>
    <?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
    <?php echo $form->hiddenField($model, 'id'); ?>
    <div class="control-group label_management">
        <?php echo $form->labelEx($model, 'student_code', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'student_code', array('class'=>'span10 ime-disabled')); ?>
        </div>

        <?php echo CHtml::label('名前<span class="required">*</span>', '', array('class'=>'control-label')); ?>
        <div class="controls controls-row">
            <?php echo $form->textField($model, 'first_name', array('class'=>'span5')); ?>
            <?php echo $form->textField($model, 'last_name', array('class'=>'span5')); ?>
        </div>

        <?php echo CHtml::label('フリガナ<span class="required">*</span>', '', array('class'=>'control-label')); ?>
        <div class="controls controls-row">
            <?php echo $form->textField($model, 'first_kana', array('class'=>'span5')); ?>
            <?php echo $form->textField($model, 'last_kana', array('class'=>'span5')); ?>
        </div>

        <?php echo $form->labelEx($model, 'faculty', array('class'=>'control-label')); ?>
        <div class="controls horizontal">
            <label><?php echo $form->radioButton($model, 'faculty', array(
                    'value'=> 0, 'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][0]; ?></label>
            <label><?php echo $form->radioButton($model, 'faculty', array(
                    'value'=> 1, 'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][1]; ?></label>
            <label><?php echo $form->radioButton($model, 'faculty', array(
                    'value'=> 2, 'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][2]; ?></label>
            <label><?php echo $form->radioButton($model, 'faculty', array(
                    'value'=> 3, 'uncheckValue'=>null)); ?><?php echo Yii::app()->params['faculty_values'][3]; ?></label>
        </div>
        <?php echo $form->labelEx($model, 'faculty_name', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList(
                $model, 'faculty_name',
                Yii::app()->params['faculty_name'],
                array('class'=>'')); ?>
        </div>

        <?php echo $form->labelEx($model, 'professor_code', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'professor_code', array('class'=>'span10 ime-disabled')); ?>
        </div>

        <?php echo $form->labelEx($model, 'email', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'email', array('class'=>'span10 ime-disabled')); ?>
        </div>

        <?php echo $form->labelEx($model, 'password', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'password', array('class'=>'span10 ime-disabled','value'=>'')); ?>
        </div>

        <?php echo $form->labelEx($model, 'repeat_password', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->passwordField($model, 'repeat_password', array('class'=>'span10 ime-disabled','value'=>'')); ?>
        </div>

        <div class="center-actions">
            <button type="submit" onclick="newStudentManager();" class="btn span2 btn-primary" id="new-btn">登録</button>
            <button type="button" class="btn btn-success span2" id="cancel-btn" data-dismiss="modal">キャンセル</button>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>