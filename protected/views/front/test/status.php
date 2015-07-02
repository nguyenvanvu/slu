<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','status.page_title');
?>
<h1>認定試験</h1>
<div class="span5">
	<table class="attending_test">
		<tr>
			<td>
				<label>認定試験受講判定</label>
			</td>
			<td >
				<label class="red"><b>
						<?php echo $studentPassed ?>
				</b></label>
			</td>
		</tr>
		<tr>
			<td></td>
			<td>
				<?php if($hideButton == false): ?>
					<button type="submit" disabled class="btn btn-primary" data-student-id="<?php echo $student_id  ?>"  id="check-certification" >試験実施</button>
				<?php else: ?>
					<button type="submit" class="btn btn-primary" data-student-id="<?php echo $student_id  ?>" id="check-certification">試験実施</button>
				<?php endif; ?>
				<button type="submit" class="btn btn-success">履歴</button>
			</td>
		</tr>
	</table>
</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">認定試験受講履歴</h4>
			</div>
			<div class="modal-body">
				Loading...
			</div>
		</div>
	</div>
</div>

<?php
$registeredStudentsUrl = Yii::app()->createUrl('front/test/History');
$createTestUrl = Yii::app()->createUrl('front/test/CreateTest');
$testUrl = Yii::app()->createUrl('front/test/test');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
	'toggleModel',
	'
	 var createTest = function (student) {
            return $.ajax({
                url: "'.$createTestUrl.'",
                type: "POST",
                dataType: "html",
                data: {
                   student_id: student
                },
            });
        }
		var currentStudent = -1;
        var getStudentInfo = function (p) {
            return $.ajax({
                url: "'.$registeredStudentsUrl.'",
                type: "POST",
                dataType: "html",
                data: {
                    page: p,
                },
            });
        }
        function showPage(page){
            getStudentInfo(page).done( function(response) {
                $(".modal-body").html(response);

            });
        }
		$(".btn-success").click(function() {
				showLoading();
				getStudentInfo(-1).done(function(response) {

                $(".modal-body").html(response);
                $("#myModal").modal("show").width(700).css({"margin-left":-350});
                hideLoading();
            })
        });

        $("#check-certification").click(function() {
				showLoading();
				currentStudent = $(this).data("studentId");
				createTest(currentStudent).done(function(response) {

					if(JSON.parse(response).status == 1){
						 window.location.href="'.$testUrl.'";
					}else{
						alert("有効な試験情報はありません。");
					}

               	 hideLoading();
            })
        });

    ',
	CClientScript::POS_END
);
?>