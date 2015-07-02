<style>
.controls textarea.error {
	border-color:#b94a48;
}
</style>
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
                    'data-date-format'=>'yyyy/mm/dd'
                )); ?>
                <span class="help-block help-block-inline">（YYYY/MM/DD)</span>
            </div>

            <?php echo $form->labelEx($model, 'date', array('class'=>'control-label sm-popup-label required')); ?>
            <div class="controls controls-row">
                <?php 
				$aTimes = array();
				for($h=0; $h<=23; $h++){
					for($m=0; $m<60; $m+=15){
						$time = str_pad($h,2,"0",STR_PAD_LEFT).":".str_pad($m,2,"0",STR_PAD_LEFT);
						$aTimes[$time] = $time;
					}
				}
				echo $form->dropDownList($model, 'from_time',$aTimes,array('class'=>'span4','onchange'=>'chanegFrTime()'));
				echo '<span class="span2 text-center"> ～ </span>';
				echo $form->dropDownList($model, 'to_time',$aTimes,array('class'=>'span4'));
				?>
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
                    'data-date-format'=>'yyyy/mm/dd',
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
                <button type="submit" class="btn span2 btn-primary" id="new-btn">登録</button>
                <button type="button" class="btn span2" id="cancel-btn" data-dismiss="modal">キャンセル</button>
            </div>
        </div>
    <?php $this->endWidget(); ?>
</div>