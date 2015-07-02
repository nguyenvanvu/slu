<?php $this->pageTitle= '認定者管理 - 臨床研究認定管理者サイトログイン' ?>
<h1>認定者管理</h1>
<div id="attended" class="widget-box filter-box">
    <div class="widget-title">
        <h5>検索条件</h5>
    </div>
    <div class="widget-content content_attended">
        <form action="" method="post">
            <div class="control-group ">
                <div class="controls controls-row">
                    <span class="span1">認定状況</span>
                    <label class="span1 label-radio"><input type="radio" name="s_status" value=0 <?php if(isset($searchParams['s_status'])) if($searchParams['s_status']==0) echo 'checked' ?>/> 認定者</label>
                    <label class="span2 label-radio"><input type="radio" name="s_status" value=1 <?php if(isset($searchParams['s_status'])) if($searchParams['s_status']==1) echo 'checked' ?>/> 認定試験受講可能者</label>
                    <label class="span1 label-radio"><input type="radio" name="s_status" value=2 <?php if(isset($searchParams['s_status'])) if($searchParams['s_status']==2) echo 'checked' ?>/> すべて</label>
                </div>
                <div class="controls controls-row">
                    <span class="span1 centert">認定日</span>
                    <input type="text" id="start_date" name="start_date" <?php if(isset($searchParams['s_status'])) if($searchParams['s_status']==1) echo 'disabled' ?>  class="ime-disabled align_left datepicker start-date span2" data-date-format="yyyy/mm/dd" value="<?php if(isset($searchParams['start_date'])) echo $searchParams['start_date']?>">
                    <span class="span1 text-center"> ～ </span>
                    <input type="text" id="end_date" name="end_date" <?php if(isset($searchParams['s_status'])) if($searchParams['s_status']==1) echo 'disabled' ?> class="ime-disabled align_left datepicker end-date span2" data-date-format="yyyy/mm/dd" value="<?php if(isset($searchParams['end_date'])) echo $searchParams['end_date']?>">
					<button type="submit" class="btn span2 btn-green1">検索</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="content_attended">
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>名大ID</th>
            <th>名前</th>
            <th>フリガナ</th>
            <th>所属</th>
            <th>学籍・教員番号</th>
			<th>認定日</th>
            <th>状況</th>
            <th>履歴</th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($list_student))
        {
            foreach($list_student as $values)
            {
                $faculty = Yii::app()->params['faculty_values'][$values["faculty"]];
                $status = Yii::app()->params['status'][$values["status"]];
                echo '<tr class="odd gradeX">';
                echo '<td>'.$values["student_code"].'</td>';
                echo '<td>'.$values["first_name"].'　'.$values["last_name"].'</td>';
                echo '<td>'.$values["first_kana"].'　'.$values["last_kana"].'</td>';
                echo '<td>'.$faculty.'</td>';
                echo '<td>'.$values["professor_code"].'</td>';
				echo '<td>'.(empty($values["passed_date"])?'&nbsp;':formatDateToJP($values["passed_date"])).'</td>';
				//echo '<td></td>';
                echo '<td>'.$status.'</td>';
                echo '<td class="center btn_show"><button name="edit" value="'.$values["id"].'" class="btn btn-success bt_edit">表示</button></td>';
            }
        }
        ?>
        </tbody>
    </table>
    <?php $this->widget('customPager', array( 'pages' => $pages, 'searchParams' => $searchParams)); ?>
    <div class="control-group right">
        <form action="<?php echo Yii::app()->createUrl('admin/student/ExportAttendedStudent'); ?>" method="post">

            <button type="submit" class="btn btn-warning right">CSV出力</button>
        </form>
    </div>
</div>
<div class="modal fade autoModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">受講者履歴情報</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
    </div>
</div>

<?php
$registeredStudentsUrl = $this->createUrl('admin/student/editAttendedStudent');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'initDatePicker',
    '
        $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
    ',
    CClientScript::POS_END
);
$cs->registerScript(
    'toggleModel',
    '
        $(".bt_edit").click(function() {
            showLoading();
            var student_id = $(this).val();
               $.ajax({
                    url: "'.$registeredStudentsUrl.'",
                    type: "post",
                    data: {"student_id" : student_id},
                    success: function(response) {
                        $(".modal-body").html(response);
                        $("#myModal").modal("show").width(700).css("margin-left",-350)
                        hideLoading();
                    }
                });
                return false;
        });
        function showPage(page){
        	$.ajax({
				url: "'.$registeredStudentsUrl.'",
				type: "post",
				data: {"student_id" : $("#current_student").val(), "page" : page},
				success: function(response) {
					$(".modal-body").html(response);
				}
			});
        }
        $("button[name=hide]").live("click",function() {
            $("#myModal").modal("hide")
        });

        $("input[name=s_status]").on("click",function() {
            var status = $(this).val();
            if(status==1)
            {
                document.getElementById("start_date").setAttribute("disabled", "disabled");
                document.getElementById("end_date").setAttribute("disabled", "disabled");
            }else
            {
                $("#start_date").removeAttr("disabled");
                $("#end_date").removeAttr("disabled");
            }
        });
    ',
    CClientScript::POS_END
);
?>