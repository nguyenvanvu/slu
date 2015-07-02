<?php $this->pageTitle = "更新問題管理 - 臨床研究認定管理者サイト" ?>
<h1>更新問題管理<span class="question_no">問.<?php echo $category_id ?>(NO.1-NO.<?php echo $numberOfQuestion ?>)全<?php echo $numberOfQuestion ?>問</span></h1>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id'=>'question_form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=>array(
        'class' => 'form-horizontal',
        'onsubmit'=>"return false;",/* Disable normal form submit */
    ),
)); ?>
<?php $this->endWidget(); ?>
<div class="container-fluid b-64 main">
    <div class="row-fluid">
        <div class="span12">
            <div id="question-list-action-bar">
                <button class="btn btn-inverse back">戻る</button>
                <button class="btn btn-info previous-cat" href="" <?php echo $category_id == 1 ? "disabled" : "" ?>>&lt;&lt;前の問題</button>
                <button class="btn btn-info next-cat" <?php echo $category_id == $test->am ? "disabled" : "" ?>>次の問題&gt;&gt;</button>
                <input type="hidden" id="t-data" data-t-id="<?php echo $test->id ?>" data-c-id="<?php echo $category_id ?>" data-question-no="<?php echo $numberOfQuestion ?>">
            </div>
            <div class="widget-box">
                <div class="widget-content nopadding">
                    <table id="question-list" class="table table-bordered table-striped td1-center td3-center td5-center">
                        <thead>
                        <tr>
                            <th style="width: 5%;">No</th>
                            <th>問題</th>
                            <th style="width: 8%;">正解No</th>
                            <th>解説</th>
                            <th style="width: 8%;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for ($i=1; $i <= $numberOfQuestion; $i++): ?>
                        <tr id="<?php echo $i ?>">
                            <td>
                                <?php echo $form->hiddenField($model, 'no', array('value' => $i)); ?>
                                <?php echo $form->hiddenField($model, 'test_id', array('value' => $test->id)); ?>
                                <?php echo $form->hiddenField($model, 'category', array('value' => $category_id)); ?>
                                No.<?php echo $i ?>
                            </td>
                            <td>
                                <?php echo $form->textArea($model, 'q', array('class' => 'focus-receiver full-width', 'value' => isset($questions[$i]) ? $questions[$i]->q : "", 'rows' => 7 )); ?>
                            </td>
                            <td>
                                <?php echo $form->numberField($model, 'answer', array( 'class'=>'focus-receiver ime-disabled span12', 'value' => isset($questions[$i]) ? $questions[$i]->answer : "" )); ?>
                            </td>
                            <td>
                                <?php echo $form->textArea($model, 'exp', array('class' => 'focus-receiver full-width', 'value' => isset($questions[$i]) ? $questions[$i]->exp : "", 'rows' => 7 )); ?>
                            </td>
                            <td><button class="focus-receiver btn btn-success action-btn" name="update" data-action="1" data-row="<?php echo $i ?>">行更新</button></td>
                        </tr>
                        <?php endfor ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="question-list-action-bar-footer">
    <button class="btn btn-primary action-btn" name="update_all" data-action="2">一括更新</button>
    <button class="btn btn-warning action-btn" id="btn-preview"  data-action="3" name="preview" style="margin-left: 20px">プレビュー</button>
</div>

<div class="modal fade autoModal" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog b-64-modal">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">更新問題プレビュー</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>

<?php
$questionList = Yii::app()->createUrl('admin/test/questionList');
$backUrl = Yii::app()->createUrl('admin/test/testCategoryList', array('t_id'=>$test->id));
$nextUrl = Yii::app()->createUrl('admin/test/questionList', array('t_id' =>  $test->id, 'c_id' => $category_id+1 ) );
$previousUrl = Yii::app()->createUrl('admin/test/questionList', array('t_id' => $test->id, 'c_id' => $category_id-1 ) );
$previewQuestionListUrl = Yii::app()->createUrl('admin/test/previewQuestionList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'thisScript',
    '
        $(".back").on("click", function() {
            window.location = "'.$backUrl.'";
        });
        $(".previous-cat").on("click", function() {
            window.location = "'.$previousUrl.'";
        })
        $(".next-cat").on("click", function() {
            window.location = "'.$nextUrl.'";
        })
        var addData = function(a, d) {
            switch (a) {
                case 1:
                    return $.ajax({
                        url: "'. $questionList .'",
                        type: "post",
                        dataType: "json",
                        data: d,
                    })
                    break;
                case 2:
                    return $.ajax({
                        url: "'. $questionList .'",
                        type: "post",
                        dataType: "json",
                        data: {
                            data: d,
                            action: a
                        }
                    })
                    break;
                default:
                    break;
            }
        }

        $(".action-btn").click(function() {
            var action = $(this).data("action");
            var data = new Object();
            var confirm_message = "";
            var c = true;
            switch (action*1) {
                case 1:
                    showLoading();
                    // insert/update specific question
                    var row = $(this).data("row");
                    data = $("#" + row + " :input").serialize() + "&action=1";
                    console.log(data);
                    break;
                case 2:
                    c = confirm("全問題情報を更新します。\nよろしいですか？");
                    data = new Array();
                    if (c) {
                        showLoading();
                        for (var i=1; i <= $("#t-data").data("questionNo"); i++) {
                            var rec = new Object();
                            rec.no = i;
                            rec.test_id = $("#t-data").data("tId");
                            rec.category = $("#t-data").data("cId");
                            rec.q = $("#" + i + " textarea[name=\"QuestionForm[q]\"]").val();
                            rec.answer = $("#" + i + " input[name=\"QuestionForm[answer]\"]").val();
                            rec.exp = $("#" + i + " textarea[name=\"QuestionForm[exp]\"]").val();
                            data.push(rec);
                        }
                        console.log(data);
                    }
                    // process
                    break;
                case 3:
                    var table = "<div class=\"widget-box\"><div class=\"widget-content nopadding\"><table class=\"table table-bordered\"><thead><tr><th style=\"width: 10%;\">No</th><th style=\"width: 40%;\">問題</th><th style=\"width: 10%;\">正解</th><th style=\"width: 40%;\">解説</th></tr></thead><tbody>";
                    for (var i=1; i <= $("#t-data").data("questionNo"); i++) {
                        var rec = new Object();
                        rec.no = i;
                        rec.test_id = $("#t-data").data("tId");
                        rec.category = $("#t-data").data("cId");
                        rec.q = $("#" + i + " textarea[name=\"QuestionForm[q]\"]").val().replace(/\r?\n/g, "<br />");
                        rec.answer = $("#" + i + " input[name=\"QuestionForm[answer]\"]").val();
                        rec.exp = $("#" + i + " textarea[name=\"QuestionForm[exp]\"]").val().replace(/\r?\n/g, "<br />");
                        var tr = "<tr><td class=\"col-center\">No."+rec.no+"</td><td>"+rec.q+"</td><td class=\"col-center\">"+rec.answer+"</td><td>"+rec.exp+"</td></tr>";
                        table += tr;
                    }
                    table += "</tbody></table></div></div>";
                    $(".modal-body").html(table);
                    $("#previewModal").modal("show").width(800).css({"margin-left":-400})
                    return;
                    break;
                default:
                    break;
            }

            if (c) {
                addData(action, data)
                .done(function(response) {
                    console.log(response.message);
                    switch (response.status) {
                        // Success
                        case 1:
                            showSuccessFlash(response.message);
                            break;
                        // Fail when trying to interact with database
                        case 0:
                            showErrorFlash(response.message);
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
