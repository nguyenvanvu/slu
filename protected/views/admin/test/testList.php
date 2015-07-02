<?php $this->pageTitle ="認定試験管理 - 臨床研究認定管理者サイト" ?>
<h1>認定試験管理</h1>
<?php $form = $this->beginWidget('CActiveForm', array(
	'id'=>'test_form',
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array(
		'class' => 'form-horizontal',
		'onsubmit'=>"return false;",/* Disable normal form submit */
	),
)); ?>
<?php $this->endWidget(); ?>
<div class="container-fluid b-63 main">
    <div class="row-fluid">
        <div class="form-horizontal" style="float: right;">
            <table>
                <tr style="background-color: transparent;">
                    <th>取り込みファイル</th>
                    <td>
                        <form id="import_csv_test" enctype="multipart/form-data" method="post">
                            <input type="file" accept="*.csv,.csv,text/csv" name="import_file" id="get-csv-file"/>
                            <input type="hidden" name="import_data" value="import_data">
                        </form>

                    </td>
                    <td>
                        <button class="btn btn-success" name="import" id="import" type="submit">取り込み</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
	<div class="row-fluid">
		<div class="span12">
					<table class="table table-bordered table-responsive table-condensed">
						<thead>
							<tr>
								<th style="width: 16%;">表題</th>
								<th style="width: 12%;">試験開始</th>
								<th style="width: 12%;">試験終了</th>
								<th style="width: 9%;">問題数</th>
								<th style="width: 9%;">合格ライン</th>
								<th style="width: 5%;">表示</th>
								<th>備考</th>
								<th style="width: 14%;">管理</th>
							</tr>
						</thead>
						<tbody>
							<tr id="register-row">
									<td>
										<?php echo $form->textField($model, 'title', array('value' => '')); ?>
									</td>
									<td>
										<?php echo $form->textField($model, 'date1', array('class'=>'ime-disabled datepicker span12', 'data-date-format' => 'yyyy/mm/dd')); ?>
									</td>
									<td>
										<?php echo $form->textField($model, 'date2', array('class'=>'ime-disabled datepicker span12', 'data-date-format' => 'yyyy/mm/dd')); ?>
									</td>
									<td>
										<?php echo $form->numberField($model, 'am', array( 'class'=>'ime-disabled span12' )); ?>
									</td>
									<td>
										<?php echo $form->numberField($model, 'point', array( 'class'=>'ime-disabled span12' )); ?>
									</td>
									<td class="col-center">
										<?php echo $form->checkBox($model, 'flag', array('value' => 1, 'uncheckValue' => 0)); ?>
									</td>
									<td>
									</td>
									<td class="col-center">
										<button class="btn action-btn btn-primary" data-action="1" data-row="register-row" name="register">新規登録</button>
								</td>
							</tr>
							<?php foreach ($tests as $test): ?>
							<tr id="<?php echo $test->id ?>">
								<td>
									<?php echo $form->hiddenField($model, 'id', array('value' => $test->id)); ?>
									<?php echo $form->textField($model, 'title', array('value' => $test->title)); ?>
								</td>
								<td>
									<?php echo $form->textField($model, 'date1', array('class'=>'ime-disabled datepicker span12', 'data-date' => '2015/01/01', 'data-date-format' => 'yyyy/mm/dd', 'value' => reFormatDate($test->date1))); ?>
								</td>
								<td>
									<?php echo $form->textField($model, 'date2', array('class'=>'ime-disabled datepicker span12', 'data-date' => '2015/01/01', 'data-date-format' => 'yyyy/mm/dd', 'value' => reFormatDate($test->date2))); ?>
								</td>
								<td>
									<?php echo $form->numberField($model, 'am', array( 'class'=>'ime-disabled span12', 'value' => $test->am )); ?>
								</td>
								<td>
									<?php echo $form->numberField($model, 'point', array( 'class'=>'ime-disabled span12', 'value' => $test->point )); ?>
								</td>
								<td class="col-center">
									<?php
									$checked = $test->flag == "1" ? "checked" : null;
									echo $form->checkBox($model, 'flag', array('value' => 1,'uncheckValue' => 0, $checked => $checked)); ?>
								</td>
								<td name="remark">
									<?php echo $test->remark ?>
								</td>
								<td class="col-center">
									<button name="edit" class="btn action-btn span6 btn-success" data-action="2" data-row="<?php echo $test->id ?>" data-action="2">修正</button>
									<button name="delete" class="btn action-btn span6 btn-danger" data-action="3" data-row="<?php echo $test->id ?>">削除</button>
								</td>
							</tr>
							<?php endforeach ?>
						</tbody>
					</table>
                	<!-- Pager -->
					<?php $this->widget('customPager', array( 'pages' => $pages )) ?>
		</div>
	</div>
</div>


<div class="modal fade bs-example-modal-lg autoModal" id="edit-test-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg b-63-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">第９回更新試験<span class="question_no">(問.1-問.25)全25問</span></h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>
<?php

$testList = $this->createUrl('admin/test/testList');
$editTestList = $this->createUrl('admin/test/testCategoryList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'thisScript',
    '

    $("input[type=file]").uniform();

    $("#import").on("click", function() {
			showLoading();
			$("#import_csv_test").submit();
    });

        var datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
		$("#register-row .datepicker").datepicker();
		var addData = function(d) {
    		return $.ajax({
				url: "'. $testList .'",
				type: "post",
				dataType: "json",
				data: d
			});
		}

        $(".action-btn").click(function() {
        	var action = $(this).data("action");
        	var row = $(this).data("row");
			var data = new Object();
			var confirm_message = "";
			switch (action*1) {
				case 1:
					// insert
				 	confirm_message= "試験情報を登録します。\nよろしいですか？";
					data = $("#register-row :input").serialize() + "&action=1";
				break;
				case 2:
					// update
					confirm_message = "試験情報を更新します。\nよろしいですか？";
					data = $("#" + row +" :input").serialize() + "&action=2";
				break;
				case 3:
					// Delete
					confirm_message = "試験情報を削除します。\nよろしいですか？";
					data = "id="+row+"&action=3";
				break;
				default:

				break;

			}
			var c = confirm(confirm_message);
			if (c) {
				showLoading();
				addData(data)
				.done(function(response) {
					switch (response.status) {
						// Success
						case 1:
							if (response.id) {
								window.location = "'.$editTestList.'?t_id="+response.id
							} else {
								location.reload();
							}
							break;
						// Fail when trying to interact with database
						case 0:
							location.reload();
							break;
						// Validate fail
						case 2:
							console.log(response.message);
							$(response.message).insertBefore(".main");
							autoHide();
							break;
						default:
							break;

					}
				})
				.always(function() {
					hideLoading();
				})
			}
        });

    ',
    CClientScript::POS_END
);
?>



