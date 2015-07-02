<?php $this->pageTitle='受講予定者一覧 - 臨床研究認定管理者サイト'; ?>
<h1>受講予定者一覧</h1>
<p>受講予定者一覧を表示するセミナーの「表示」ボタンをクリックしてください。</p>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>開催日時</th>
            <th>セミナー名称</th>
            <th>開催場所</th>
            <th>受講予定者一覧</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if(isset($seminars)){
            foreach ($seminars as $seminar): ?>
            <tr class="odd gradeX">
                <td class="col-center"><?php echo reFormatDate($seminar->start_date); ?> <br />
                    <?php echo $seminar->from_time."～".$seminar->to_time ?></td>
                <td><?php echo $seminar->name ?></td>
                <td class="col-center"><?php echo $seminar->location ?></td>
                <td class="col-center"><button name="seminar-btn" value="<?php echo $seminar->id.'|'.$seminar->name ?>" class="btn btn-success seminar-btn">表示</button></td>
            </tr>
        <?php endforeach; }?>
    </tbody>
</table>
<?php $this->widget('customPager', array( 'pages' => $pages )) ?>
<div class="modal fade autoModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
        </div>
    </div>
</div>

<?php
$registeredStudentsUrl = Yii::app()->createUrl('admin/seminar/editRegisteredSeminarList');
$registeredExportUrl = Yii::app()->createUrl('admin/seminar/exportRegisteredSeminarList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '
        $(".seminar-btn").click(function() {
        	showLoading();
            var seminar = $(this).val();
            var array_seminar = seminar.split("|", 2);
            var id_seminar = array_seminar[0];
               $.ajax({
                    url: "'.$registeredStudentsUrl.'",
                    type: "post",
                    data: {"id_seminar" : id_seminar},
                    success: function(response) {
                        $("#myModalLabel").text(array_seminar[1]+"受講予定者一覧");
                        $(".modal-body").html(response);
                        $("#myModal").modal("show").width(700).css("margin-left",-350)
                        hideLoading();
                    }
                });
                return false;
        });
        function showPage(page){
        	showLoading();
        	$.ajax({
				url: "'.$registeredStudentsUrl.'",
				type: "post",
				data: {"id_seminar" : $("#current_seminar").val(), "page" : page},
				success: function(response) {
					$(".modal-body").html(response);
					hideLoading();
				}
			});
        }
    ',
    CClientScript::POS_END
);