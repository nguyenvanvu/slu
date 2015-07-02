<?php
$this->pageTitle="カテゴリー問題 - 臨床研究認定管理者サイト";
$question_amount = $test->category_am != "" ? explode(",", $test->category_am) : array();
 ?>
<h1><?php echo $test->title ?><span class="question_no">(問.1-問.<?php echo $test->am ?>)全<?php echo $test->am ?>問</span></h1>
<div class="container-fluid">
    <div class="span7 offset2">
        <input type="hidden" id="test-id" value="<?php echo $test->id ?>">
        <div class="row-fluid">
            <textarea name="remark" id="" cols="30" rows="5" class="span12"><?php echo $test->remark ?></textarea>
            <div class="center-actions" style="margin-top: 0"><button class="btn btn-warning action-btn" data-action="6" name="save_remark">備考保存</button></div>

        </div>
        <br>
        <div class="row-fluid">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 25%;">NO</th>
                    <th>カテゴリー問題数</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php for ($i=1; $i <= $test->am; $i++):?>
                    <tr>
                        <td class="col-right">問.<?php echo $i ?></td>
                        <td class="col-center"><input class="span6 ime-disabled question_number" style="ime-mode: disabled;" type="number" id="<?php echo $i ?>" value="<?php echo (isset($question_amount[$i-1])) ?  $question_amount[$i-1] : "" ?>" name="category_am[]"></td>
                        <td class="col-center"><button class="btn btn-success register-question action-btn" name="register_question" data-action="4" data-category-id="<?php echo $i ?>">問題登録</button></td>
                    </tr>
                <?php endfor ?>
                </tbody>
            </table>
            <div class="center-actions" style="margin-bottom: 20px"><button class="btn btn-primary update-all action-btn" data-action="5" name="update_all">一括更新</button></div>
        </div>
    </div>
</div>
<?php
$testList = $this->createUrl('admin/test/');
$editTestList = $this->createUrl('admin/test/testCategoryList');
$questionList = $this->createUrl('admin/test/questionList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'thisScript',
    '
        var datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});

        var addData = function(a, d) {
            return $.ajax({
                url: "'. $testList .'",
                type: "post",
                dataType: "json",
                data: {
                    action: a,
                    data: d,
                },
            })
        }

        $(".action-btn").click(function() {

            var action = $(this).data("action");
            var row = $(this).data("row");
            var data = new Object();
            data.id = $("#test-id").val();
            switch (action*1) {
                case 6: // update remark
                    showLoading();
                    data.remark = $("textarea[name=\"remark\"]").val();
                    addData(action, data)
                    .done(function(response) {
                        hideLoading();
                        switch (response.status) {
                            case 1:
                                showSuccessFlash(response.message);
                                break;
                            case 0:
                                showErrorFlash(response.message);
                                break;
                        }
                    })
                    .always(function() {
                      hideLoading();
                    })
                break;
                case 4: // update specific category_am
                    showLoading();
                    data.category_id = $(this).data("categoryId");
                    data.question_amount = $("#"+data.category_id).val();
                    addData(action, data)
                    .done(function(response) {
                        console.log(response.message);
                        switch (response.status) {
                            case 1:
                                window.location = "'.$questionList.'?t_id=" + data.id + "&c_id=" + data.category_id;
                                break;
                            case 0:
                                showErrorFlash(response.message)
                                break;
                        }
                    })
                    .always(function() {
                      hideLoading();
                    })
                break;
                case 5: // update all category_am
                    var c = confirm("カテゴリー問題数を一括更新します。\nよろしいですか？");
                    if (c) {
                        showLoading();
                        var tempCatAm = new Array();
                        $.each($(".question_number"), function() {
                            tempCatAm.push($(this).val());
                        });
                        data.category_am = tempCatAm.join();
                        addData(action, data)
                        .done(function(response) {
                            switch (response.status) {
                                case 1:
                                    showSuccessFlash(response.message);
                                    break;
                                case 0:
                                    showErrorFlash(response.message);
                            }
                        })
                        .always(function() {
                          hideLoading();
                        })
                    } else {
                        return;
                    }
                break;
                default:

                break;

            }

        });
    ',
    CClientScript::POS_END
);
