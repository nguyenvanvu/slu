<?php
    $this->pageTitle = "受講者出席登録 - 臨床研究認定管理者サイト";


    $s_student_code = $s_professor_code = $s_professor_code = $s_name = $s_kana = $s_today_register = $fac_1 = $fac_2 = $fac_3 = $fac_0 = "";
if (isset($post)) {
    $s_student_code = isset($post['s_student_code']) ? $post['s_student_code'] : "";
    $s_professor_code = isset($post['s_professor_code']) ? $post['s_professor_code'] : "";
    $s_apply_code = isset($post['s_apply_code']) ? $post['s_apply_code'] : "";
    $s_name = isset($post['s_name']) ? $post['s_name'] : "";
    $s_kana = isset($post['s_kana']) ? $post['s_kana'] : "";
    $s_today_register = isset($post['s_today_register']) ? "checked" : "";
    if (isset($post['s_faculty'])) {
        $fac_0 = in_array(0, $post['s_faculty']) ? 'checked' : "";
        $fac_1 = in_array(1, $post['s_faculty']) ? 'checked' : "";
        $fac_2 = in_array(2, $post['s_faculty']) ? 'checked' : "";
        $fac_3 = in_array(3, $post['s_faculty']) ? 'checked' : "";

    }
}
?>

        <h1><?php print_r( $seminar_name )?> 受講者出席登録</h1>

        <div class="widget-box filter-box">
            <div class="widget-title">
                <h5>検索条件</h5>
            </div>
            <div class="widget-content">
                <form action="<?php echo getCurrentUrl(array('s_id'=>$s_id)); ?>" method="post" class="form-horizontal">
                    <input type="hidden" name="s_id" value="<?php echo $s_id ?>">
                    <div class="container-fluid">
                        <table class="filter-table">
                            <tr>
                                <th>名大ID</th>
                                <td><input type="text" name="s_student_code" class="ime-disabled" value="<?php echo $s_student_code ?>" placeholder="" /></td>
                                <th>学籍・教員番号</th>
                                <td><input name="s_professor_code" class="ime-disabled" value="<?php echo $s_professor_code ?>" type="text" placeholder="" /></td>
                                <th>受付番号</th>
                                <td><input name="s_apply_code" class="ime-disabled" type="text" value="<?php echo $s_apply_code ?>" <?php echo $s_today_register ? "disabled" : "" ?> placeholder="" /></td>
                            </tr>
                            <tr>
                                <th>名前</th><td><input type="text" name="s_name" value="<?php echo $s_name ?>" placeholder="" /></td>
                                <th>フリガナ</th><td><input type="text" name="s_kana" value="<?php echo $s_kana ?>" placeholder="" /></td>
                                <th>受付</th><td><label><input type="checkbox" name="s_today_register" value="1" placeholder="" <?php echo $s_today_register ?>/> 当日受付</label></td>
                            </tr>
                            <tr>
                                <th>所属</th>
                                <td colspan="4">
                                    <label><input type="checkbox" name="s_faculty[]" value="0" <?php echo $fac_0 ?>> 附属病院</label>
                                    <label><input type="checkbox" name="s_faculty[]" value="1" <?php echo $fac_1 ?>> 医学部</label>
                                    <label><input type="checkbox" name="s_faculty[]" value="2" <?php echo $fac_2 ?>> 他学部（学内）</label>
                                    <label><input type="checkbox" name="s_faculty[]" value="3" <?php echo $fac_3 ?>> その他</label>
                                </td>
								<td class="filter-actions2"><button type="submit" class="btn btn-green1 span2">検索</button></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>

        <table class="table table-bordered table-striped td-center">
            <thead>
                <tr>
                    <th>名大ID</th>
                    <th>名前</th>
                    <th>フリガナ</th>
                    <th>所属</th>
                    <th>学籍・教員番号</th>
                    <th>受付番号</th>
                    <th>出席</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                <tr class="odd gradeX">
                    <td><?php echo $student->student_code ?></td>
                    <td><?php echo $student->first_name."　".$student->last_name ?></td>
                    <td><?php echo $student->first_kana."　".$student->last_kana ?></td>
                    <td><?php echo $faculty = Yii::app()->params['faculty_values'][$student->faculty]; ?></td>
                    <td><?php echo $student->professor_code ?></td>
                    <td><?php echo isset($student->studentSeminars[0]) ? $student->studentSeminars[0]->apply_code : "" ?></td>
                    <td><button data-student-id="<?php echo $student->id ?>" class="btn
                        <?php if (isset($student->studentSeminars[0]) AND $student->studentSeminars[0]->attended == 0 AND $student->studentSeminars[0]->apply_code != "") :?>
                        btn-success btn-confirm attended" data-action="1">登録
                        <?php elseif ( isset($student->studentSeminars[0]) AND $student->studentSeminars[0]->apply_code == "" AND $student->studentSeminars[0]->attended == 0 ): ?>
                        btn-success btn-confirm attended" data-action="1">当日登録
                        <?php elseif(!isset($student->studentSeminars[0])): ?>
                        btn-success btn-confirm register" data-action="2">当日登録
                        <?php else: ?>
                        btn-confirm cancel btn-danger" data-action="3">取り消し
                        <?php endif ?>
                    </button></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
        <input type="hidden" class="current-url" data-url="<?php echo $this->createUrl("",array_merge($_GET,$search_params)) ?>">
<?php $this->widget('customPager', array( 'pages' => $pages, 'searchParams' => $search_params));

$processor = Yii::app()->createUrl('admin/seminar/ConfirmAttended');
$currentPage = getCurrentUrl(array_merge($_GET,$search_params));
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'attendedConfimation',
    '
        $("[name=\"s_today_register\"]").on("change", function() {
            $("[name=\"s_apply_code\"]").prop("disabled", $(this).prop("checked"));
        })

        var register = function(a, st) {
            return $.ajax({
                url: "'.$processor.'",
                type: "POST",
                dataType: "json",
                data: {
                    action: a,
                    st_id: st,
                    s_id: '.$s_id.'
                },
            })
        }

        $(".btn-confirm").click(function() {
            var actionCode = $(this).data("action");
            /*
            1 = confirm registered student
            2 = confirm non registered student
            3 = cancel confirmation
            */


            var confirm_message = "";
            switch (actionCode) {
                case 1:
                    confirm_message = "出席登録します。\nよろしいですか？";
                    break;
                case 2:
                    confirm_message = "出席登録します。\nよろしいですか？";
                    break;
                case 3:
                    confirm_message = "出席登録を取り消します。\nよろしいですか？";
                    break;
                default:
                    break;
            }

            var c = confirm(confirm_message);
            if (c) {
                showLoading();
                register(actionCode, $(this).data("studentId"))
                    .done(function() {
                        window.location = "'.$currentPage.'";
                    })
                    .fail(function() {
                        hideLoading();
                    })
            } else {
                return;
            }
        });
    ',
    CClientScript::POS_END
);

