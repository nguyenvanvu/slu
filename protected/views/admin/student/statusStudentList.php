<?php
    $this->pageTitle = "受講者管理 - 臨床研究認定管理者サイト";

    $s_student_code = $s_professor_code = $s_professor_code = $s_name = $s_kana = $s_email = $fac_1 = $fac_2 = $fac_3 = $fac_0 = "";
if (isset($post)) {
    $s_student_code = isset($post['s_student_code']) ? $post['s_student_code'] : "";
    $s_professor_code = isset($post['s_professor_code']) ? $post['s_professor_code'] : "";
    $s_apply_code = isset($post['s_apply_code']) ? $post['s_apply_code'] : "";
    $s_name = isset($post['s_name']) ? $post['s_name'] : "";
    $s_kana = isset($post['s_kana']) ? $post['s_kana'] : "";
    $s_email = isset($post['s_email']) ? $post['s_email'] : "";
    if (isset($post['s_faculty'])) {
        $fac_0 = in_array(0, $post['s_faculty']) ? 'checked' : "";
        $fac_1 = in_array(1, $post['s_faculty']) ? 'checked' : "";
        $fac_2 = in_array(2, $post['s_faculty']) ? 'checked' : "";
        $fac_3 = in_array(3, $post['s_faculty']) ? 'checked' : "";
    }
}
?>
<h1>受講者管理</h1>
<div class="widget-box filter-box">
    <div class="widget-title">
        <h5>検索条件</h5>
    </div>
    <div class="widget-content">
        <form action="<?php echo getCurrentUrl(); ?>" method="post" class="form-horizontal">
			<style>
			.filter-table input {width:auto}
			</style>
            <div class="container-fluid">
                <table class="filter-table">
                    <tr>
                        <th>名大ID</th>
                        <td>
                            <input type="text" class="ime-disabled" name="s_student_code" value="<?php echo $s_student_code ?>"placeholder="" />
                        </td>
                        <th>学籍・教員番号</th>
                        <td>
                            <input type="text" class="ime-disabled" name="s_professor_code" value="<?php echo $s_professor_code ?>"placeholder="" />
                        </td>
                        <th>メールアドレス</th>
                        <td>
                            <input type="text" class="ime-disabled" name="s_email" value="<?php echo $s_email ?>"placeholder="" />
                        </td>
                    </tr>
                    <tr>
                        <th>名前</th>
                        <td>
                            <input type="text" name="s_name" value="<?php echo $s_name ?>"placeholder="" />
                        </td>
                        <th>フリガナ</th>
                        <td>
                            <input type="text" name="s_kana" value="<?php echo $s_kana ?>"placeholder="" />
                        </td>
                    </tr>
                    <tr>
                        <th>所属</th>
                        <td colspan="4">
                            <label><input name="s_faculty[]" type="checkbox" <?php echo $fac_0 ?> value="0"> 附属病院</label>
                            <label><input name="s_faculty[]" type="checkbox" <?php echo $fac_1 ?> value="1"> 医学部</label>
                            <label><input name="s_faculty[]" type="checkbox" <?php echo $fac_2 ?> value="2"> 他学部（学内）</label>
                            <label><input name="s_faculty[]" type="checkbox" <?php echo $fac_3 ?> value="3"> その他</label>
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
            <th>メールアドレス</th>
            <th>受講状況</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($students as $st): ?>
            <tr class="odd gradeX">
                <td><?php echo $st->student_code ?></td>
                <td class="col-center"><?php echo $st->first_name." ".$st->last_name ?></td>
                <td class="col-center"><?php echo $st->first_kana." ".$st->last_kana ?></td>
                <td><?php echo $faculty = Yii::app()->params['faculty_values'][$st->faculty]; ?></td>
                <td><?php echo $st->professor_code ?></td>
                <td><?php echo $st->email ?></td>
                <td><button class="btn show-info-modal" data-st-id="<?php echo $st->id ?>">表示</button></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>
<!-- pager -->

<?php $this->widget('customPager', array( 'pages' => $pages, 'searchParams' => $searchParams)); ?>

<div class="modal fade bs-example-modal-lg autoModal" id="edit-student-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">受講者情報</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal">戻る</button>
            </div>
        </div>
    </div>
</div>

<?php
$editStudent = $this->createUrl('admin/student/editStatusStudentList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '
        var currentStudent = -1;
        var getStudentInfo = function (st_id, p) {
            return $.ajax({
                url: "'.$editStudent.'",
                type: "POST",
                dataType: "html",
                data: {
                    st_id: st_id,
                    page: p,
                },
            });
        }
        function showPage(page){
            getStudentInfo(currentStudent, page).done( function(response) {
                $(".modal-body").html(response);
            });
        }
        $(".show-info-modal").click(function() {
            showLoading();
            currentStudent = $(this).data("stId");
            getStudentInfo(currentStudent, -1)
            .done(function(response) {
                $(".modal-body").html(response);
                hideLoading();
                $("#edit-student-modal").modal("show").width(700).css({"margin-left":-350});
            })
            .fail(function() {
                hideLoading();
            })
        });
    ',
    CClientScript::POS_END
);
?>
