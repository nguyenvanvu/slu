<div class="row-fluid">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id'=>'form_seminar',
        'enableAjaxValidation'=>false,
        //'focus'=>array($model,'firstName'),
        'htmlOptions'=>array(
            'class' => 'form-horizontal',
            'onsubmit'=>"return false;",/* Disable normal form submit */
        ),
    )); ?>
    <?php echo CHtml::errorSummary($model, '', '', array('class' => 'alert alert-error alert-block hide-message')); ?>
    <?php echo $form->hiddenField($model, 'id'); ?>
    <div class="control-group">
        <?php echo $form->labelEx($model, 'name', array('class'=>'control-label sm-popup-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'name', array('class'=>'span10')); ?>
        </div>
		
		<?php echo $form->labelEx($model, 'lecturer', array('class'=>'control-label sm-popup-label required')); ?>
		<div class="controls">
			<?php echo $form->textField($model, 'lecturer', array('class'=>'span10')); ?>
		</div>
		<?php echo $form->labelEx($model, 'outline', array('class'=>'control-label sm-popup-label required')); ?>
		<div class="controls">
			<?php echo $form->textArea($model, 'outline', array('class'=>'span10','rows' => 7)); ?>
		</div>
		
        <?php echo $form->labelEx($model, 'start_date', array('class'=>'control-label sm-popup-label')); ?>
        <div class="controls controls-row">
            <?php echo $form->textField($model, 'start_date', array(
                'class'=>'datepicker span4 ime-disabled',
                'data-date-format'=>'yyyy/mm/dd',
            )); ?>
            <span class="help-block help-block-inline">（YYYY/MM/DD)</span>
        </div>

        <?php echo $form->labelEx($model, 'date', array('class'=>'control-label sm-popup-label required')); ?>
        <div class="controls controls-row">
            <?php echo $form->textField($model, 'from_time', array('class'=>'span4 ime-disabled','onchange'=>'chanegFrTime()')); ?>
            <span class="span2 text-center"> ～ </span>
            <?php echo $form->textField($model, 'to_time', array('class'=>'span4 ime-disabled')); ?>
        </div>

        <?php echo $form->labelEx($model, 'location', array('class'=>'control-label sm-popup-label')); ?>
        <div class="controls horizontal">
            <?php echo $form->textField($model, 'location', array('class'=>'span10')); ?>
        </div>
		
		<?php echo $form->labelEx($model, 'location_url', array('class'=>'control-label sm-popup-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model, 'location_url', array('class'=>'span10')); ?>
		</div>
		
		<label class="control-label sm-popup-label required">申し込み期間 <span class="required">*</span></label>
		<div class="controls controls-row">
			<?php echo $form->textField($model, 'apply_from_date', array(
				'class'=>'datepicker span4 ime-disabled',
				'data-date-format'=>'yyyy/mm/dd'
			)); ?>
			<span class="span2 text-center"> ～ </span>
			<?php echo $form->textField($model, 'apply_to_date', array(
				'class'=>'datepicker span4 ime-disabled',
				'data-date-format'=>'yyyy/mm/dd'
			)); ?>
		</div>
		
		
        <?php echo $form->labelEx($model, 'holding', array('class'=>'control-label sm-popup-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList(
                $model, 'holding',
                Yii::app()->params['holding_values'],
                array('class'=>'')); ?>
        </div>
        <div class="center-actions">
            <button type="submit" name="button" class="btn btn-danger span2" value="delete">削除</button>
            <button type="submit" name="button" class="btn btn-primary span2" value="update">登録</button>
            <button type="button" id="hide" class="btn btn-success span2">キャンセル</button>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>