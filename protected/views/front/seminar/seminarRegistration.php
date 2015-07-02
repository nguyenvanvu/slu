<?php
/* @var $this UserController */

$this->pageTitle=Yii::t('front','seminar.page_title');
?>
<h1>セミナー申し込み</h1>
<div class="widget-box">
	<div class="widget-content nopadding">
		<table class="table table-bordered  td1-center td3-center td4-center">
			<thead>
			<tr>
				<th nowrap="nowrap">開催日時</th>
				<th nowrap="nowrap" style="width:30%">セミナー名称</th>
                <th nowrap="nowrap" style="width:15%">講師</th>
				<th nowrap="nowrap" style="width:22%">開催場所</th>
                <th nowrap="nowrap">詳細</th>
				<th nowrap="nowrap" style="width:13%">申し込み</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($seminars as $seminar): 
				$applyCode = "";
				$attented  = "";
				$fromDate = reFormatDate($seminar->apply_from_date);
				$toDate = reFormatDate($seminar->apply_to_date);
				$dateTime = reFormatDate($seminar->start_date).' '.$seminar->to_time.':00';
				$nowDate =  date("Y/m/d H:i:s");
				if(isset($seminar->seminarStudents[0])){
					$seminarStudent = $seminar->seminarStudents[0];
					$applyCode = $seminarStudent->apply_code;
					$attented  = $seminarStudent->attended;
				}
				$td_html = '<td class = "col-center">&nbsp;</td>';
				$tr_class = 'odd gradeX';
				
				if($fromDate <= $toDate && $toDate <= reFormatDate($dateTime) ){
					if($nowDate < $fromDate){
						$td_html = '<td nowrap="nowrap" class="col-center">
										申し込み期間は</br>'.
										reFormatDate($seminar->apply_from_date).'～'.reFormatDate($seminar->apply_to_date).'です
									</td>';
					}
					else{
						if($applyCode == ""){
							if(	$fromDate <= reFormatDate($nowDate) && 
								reFormatDate($nowDate) <= $toDate && 
								$nowDate <= $dateTime){
								$td_html = '<td nowrap="nowrap" class="col-center">
												<button name="seminar-btn" 
														data-student-id="'.$student_id.'" 
														data-s-id="'.$seminar->id.'" 
														class="btn btn-success seminar-btn">
													申し込み
												</button>
											</td>';
								$tr_class = 'odd gradeX serminar-interms-tr';
							}
							elseif($seminar->apply_to_date < $nowDate)
								$td_html = '<td nowrap="nowrap" class="col-center">未申し込み</td>';
						}
						else {
							if($seminar->apply_from_date <= $nowDate && $nowDate <= $dateTime){
								$td_html = '<td nowrap="nowrap" class="col-center">
												<button name="seminar-btn" 
														data-s-id="'.$seminar->id.'" 
														data-student-id="'.$student_id.'" 
														class="btn btn-danger btn-mini">
													取り消し
												</button>
												<div class="id-issued">受付番号：'.$applyCode.'</div>
											</td>';
							}
							if($dateTime <= $nowDate){
								if($attented == 1){
									$td_html = '<td nowrap="nowrap" class="col-center">受講済み<div class="id-issued">受付番号：'.$applyCode.'</div></td>';
								}
								else $td_html = '<td nowrap="nowrap" class="col-center">未受講</td>';
							}						
						}
					}
				}	
				?>
				<tr <?php echo 'class="'.$tr_class.'"' ?>>
					<td class="col-center" nowrap="nowrap"><?php echo formatDateToJP($seminar->start_date,2); ?> <br /><?php echo $seminar->from_time."～".$seminar->to_time ?></td>
					<td><?php echo $seminar->name?></td>
                    <td><?php echo $seminar->lecturer?></td>
                    <td class="col-center">
						<?php if($seminar->location_url) { ?>
							<a target ="_blank" href="<?php echo  $seminar->location_url?>"><?php echo $seminar->location ?></a>
                        <?php } else { echo $seminar->location;}?> 
                    </td>
					<td nowrap="nowrap"><button name="id_seminar"  class = "btn seminar-detail-btn"  value = "<?php echo  $seminar->id?>" > 表示</button></td>
					<?php echo $td_html ?>	
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
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
$registerSeminarUrl = Yii::app()->createUrl('front/seminar/RegisterSeminar');
$deleteSeminarUrl = Yii::app()->createUrl('front/seminar/DeleteSeminar');
$detailSeminarUrl = Yii::app()->createUrl('front/seminar/SeminarDetail');
$SeminarUrl = Yii::app()->getRequest()->getUrl();
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
	'toggleModel',
	'
		var currentSeminar2 = 0;
		var registerSeminar = function (sid, student ,date) {
			return $.ajax({
				url: "'.$registerSeminarUrl.'",
				type: "POST",
				dataType: "json",
				data: {
					s_id: sid,
					ex_date: date,
					student_id: student
				},
			});
		}
		var deleteSeminar = function (sid, student) {
			return $.ajax({
				url: "'.$deleteSeminarUrl.'",
				type: "POST",
				dataType: "json",
				data: {
					s_id: sid,
					student_id: student
				},
			});
		}
               
        $(".seminar-detail-btn").click(function(){
            showLoading();
            var id_seminar = $(this).val();
            $.ajax({
                url: "'.$detailSeminarUrl.'",
                    type: "post",
                    data: {"id_seminar" : id_seminar},
                    success: function(response) {
                        $("#myModalLabel").text("セミナー情報編集");
                            // $(".datepicker").remove();
                            $("#myModal .modal-body").html(response);
                            // datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
                            $("#myModal").modal("show").width(700).css("margin-left",-350)
                            $("#hide").html("戻る");
								
                            $(".btn.btn-danger.span2").hide()
                            $(".btn.btn-primary.span2").hide()
                            $("#myModal .modal-body").find("input, select, textarea").attr("disabled", true);
                            autoHide();
                            hideLoading();
                         }
            });
            return false;
        });
		$(".seminar-btn").click(function() {
			currentSeminar = $(this).data("sId");
			currentStudent = $(this).data("studentId");
			//$("#sm-rg-cfm").attr("currentSeminar",currentSeminar);
			//$("#sm-rg-cfm").attr("currentStudent",currentStudent);
            //$("#sm-rg-confirmation").modal("show").width(700).css("margin-left",-350);
            //$("#myModal .modal-body").find("input, select, textarea").attr("disabled", false);
			if (confirm("セミナーに申し込みします。\nよろしいですか？")){
        		showLoading();
			  	registerSeminar(currentSeminar,currentStudent, "").done(function(response) {
					hideLoading();
					alert("申し込みを受け付けました。 \n\n受付番号： " + response.title);
					window.location.href ="'.$SeminarUrl.'";

				})
    		}
			

		});
                
		$(".datepicker").datepicker();
		$("#sm-rg-cfm").click(function() {
			showLoading();
			currentSeminar2 = $(this).attr("currentseminar");
			currentStudent2 = $(this).attr("currentstudent");
			extra_date = $("#extra_date").val();

			registerSeminar(currentSeminar2,currentStudent2, extra_date).done(function(response) {
				hideLoading();
                alert("申し込みを受け付けました。 \n\n受付番号： " + response.title);
                window.location.href ="'.$SeminarUrl.'";

            })

		});
		$(".btn-danger").click(function() {
			if (confirm("受講申し込みを取り消します。\nよろしいですか？")){
        		showLoading();
        		seminar = $(this).data("sId");
			  	student = $(this).data("studentId");
			  	deleteSeminar(seminar,student).done(function(response) {
					hideLoading();
					window.location.href="'.$SeminarUrl.'";
           		})
    		}
		});
        $("#hide").live("click",function() {
			$("#myModal").modal("hide")
			$("#myModal .modal-body").html()       
        });
          

	',
	CClientScript::POS_END
);
?>

<div id="sm-rg-confirmation" class="modal hide">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" type="button">×</button>
		<h4>申し込み確認</h4>
	</div>
	<div class="modal-body">
		<p>生命倫理E－ラーニングの受講予定がある場合は受講日を入力して下さい。</p>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label">生命倫理E－ラーニング受講日</label>
				<div class="controls controls-row">
					<input type="text" id="extra_date" data-date="2013/02/01" data-date-format="yyyy/mm/dd" value="" class="datepicker span2">
					<span class="help-block help-block-inline">（YYYY/MM/DD)</span>
				</div>
			</div>
		</form>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" class="btn btn-primary"  id="sm-rg-cfm">登録</a>
		<a data-dismiss="modal" class="btn" href="#">キャンセル</a>
	</div>
</div>

<div class="modal fade autoModal" id="sm-rg-cfm-alert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">&nbsp;</h4>
			</div>
			<div class="modal-body">
				<p>申し込みを受け付けました。</p>
				<p id="content-apply"></p>
				<div class="modal-footer">
					<a data-dismiss="modal" class="btn btn-primary" href="#">OK</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="sm-cc-confirmation" class="modal hide">
	<div class="modal-header">
		<button data-dismiss="modal" class="close" type="button">×</button>
		<h4>&nbsp;</h4>
	</div>
	<div class="modal-body">
		<p>受講申し込みを取り消します。<br/>
			よろしいですか？</p>
	</div>
	<div class="modal-footer">
		<a data-dismiss="modal" class="btn btn-primary" id="delete-apply" href="#">OK</a>
		<a data-dismiss="modal" class="btn" href="#">キャンセル</a>
	</div>
</div>