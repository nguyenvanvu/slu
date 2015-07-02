<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','status.page_title');
?>   
<div class = "controls-group ">
	<div class="controls controls-row " style = "margin-bottom: 10px;">
		<span class = "span" style = "margin-left:0px"><label>認定試験受講判定</label></span>
		<label class="red "><b class = "span">
				<?php echo $studentPassed ?>
		</b></label>
	</div>		
	<div class="controls controls-row " >
		<span class = "span" style= "margin-top:6px; margin-left:0px" ><label class="control-label" >生命倫理E－ラーニング受講日</label></span>
		<input  type="text" id="date_schedule" data-date-format="yyyy/mm/dd" value="<?php echo empty($schedule_date)?'':date_format(new DateTime($schedule_date), "Y/m/d" )?>" class="datepicker span2">
		<a  data-dismiss="modal" class="btn btn-primary span1"  id="sm-rg-cfm-btn">登録</a>
	</div>
</div>

<div>
	<h1 style="margin-top:20px;">申し込み中セミナー一覧</h1>
	<div class="widget-box" id = "widget-box1" style=" overflow: scroll; overflow-x: hidden;">
		<div class="widget-content nopadding" >
			<table class="table table-bordered  td1-center td3-center td4-center" id = "table1">
				<colgroup>
					<col />
					<col width="30%" />
					<col width="15%" />
					<col width="22%" />
					<col />
					<col width="13%" />
				</colgroup>
				<thead>
				<tr>
					<th nowrap="nowrap">開催日時</th>
					<th nowrap="nowrap">セミナー名称</th>
					<th nowrap="nowrap">講師</th>
					<th nowrap="nowrap">開催場所</th>
					<th nowrap="nowrap">詳細</th>
					<th nowrap="nowrap">申し込み</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($listRegisted as $seminar):
					$fromDate	= reFormatDate($seminar['apply_from_date']);
					$toDate		= reFormatDate($seminar['apply_to_date']);
					$dateTime	= reFormatDate($seminar['start_date']).' '.$seminar['to_time'].':00';
					$nowDate	=  date("Y/m/d H:i:s");
					$applyCode	= $seminar['apply_code'];
					$attented	= $seminar['attended'];	
					$tr_class = 'odd gradeX';
					$td_html = '<td nowrap="nowrap" class="col-center">
									<button name="seminar-btn" 
											data-s-id="'.$seminar['seminar_id'].'" 
											data-student-id="'.$student_id.'" 
											class="btn btn-danger btn-mini">
										取り消し
									</button>
									<div class="id-issued">受付番号：'.$applyCode.'</div>
								</td>';

					?>
					<tr <?php echo 'class="'.$tr_class.'"' ?>>
						<td class="col-center" nowrap="nowrap">
							<?php echo formatDateToJP($seminar['start_date'],2); ?> 
							<br /><?php echo $seminar['from_time']."～".$seminar['to_time'] ?>
						</td>
						<td><?php echo $seminar['name']?></td>
						<td><?php echo $seminar['lecturer']?></td>
						<td class="col-center">
							<?php if($seminar['location_url']) { ?>
								<a target ="_blank" href="<?php echo  $seminar['location_url'] ?>"><?php echo $seminar['location'] ?></a>
							<?php } else { echo $seminar['location'];}?> 
						</td>
						<td nowrap="nowrap">
							<button name="id_seminar"  class = "btn seminar-detail-btn"  value = "<?php echo  $seminar['seminar_id']?>" > 表示</button>
						</td>
						<?php echo $td_html ?>	
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div>
	<h1 style="margin-top:50px;">受講済みセミナー一覧</h1>
	<div class="widget-box" id="widget-box2" style="overflow: auto; overflow-x: hidden;">
		<?php
		if(count($listAttended)==0) echo '<table class="table table-bordered td1-center" style="margin:0"><tr><td>受講済みのセミナーはありません。</td></tr></table>';
		else { 
		?>
		<div class="widget-content nopadding" >
			<table class="table table-bordered  td1-center td3-center td4-center" id="table2">
				<colgroup>
					<col />
					<col width="30%" />
					<col width="15%" />
					<col width="20%" />
					<col />
					<col width="13%" />
				</colgroup>
				<thead>
				<tr>
					<th nowrap="nowrap">開催日時</th>
					<th nowrap="nowrap">セミナー名称</th>
					<th nowrap="nowrap">講師</th>
					<th nowrap="nowrap">開催場所</th>
					<th nowrap="nowrap">詳細</th>
					<th nowrap="nowrap">申し込み</th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach ($listAttended as $seminar):
					$fromDate	= reFormatDate($seminar['apply_from_date']);
					$toDate		= reFormatDate($seminar['apply_to_date']);
					$dateTime	= reFormatDate($seminar['start_date']).' '.$seminar['to_time'].':00';
					$nowDate	=  date("Y/m/d H:i:s");
					$applyCode	= $seminar['apply_code'];
					$attented	= $seminar['attended'];	
					$tr_class = 'odd gradeX';
					$td_html = '<td nowrap="nowrap" class="col-center">受講済み<div class="id-issued">受付番号：'.$applyCode.'</div></td>';
					?>
					<tr <?php echo 'class="'.$tr_class.'"' ?>>
						<td class="col-center" nowrap="nowrap">
							<?php echo formatDateToJP($seminar['start_date'],2); ?> 
							<br /><?php echo $seminar['from_time']."～".$seminar['to_time'] ?>
						</td>
						<td><?php echo $seminar['name']?></td>
						<td><?php echo $seminar['lecturer']?></td>
						<td class="col-center">
							<?php if($seminar['location_url']) { ?>
								<a target ="_blank" href="<?php echo  $seminar['location_url'] ?>"><?php echo $seminar['location'] ?></a>
							<?php } else { echo $seminar['location'];}?> 
						</td>
						<td nowrap="nowrap">
							<button name="id_seminar"  class = "btn seminar-detail-btn"  value = "<?php echo  $seminar['seminar_id']?>" > 表示</button>
						</td>
						<?php echo $td_html ?>	
					</tr>
				<?php endforeach ?>
				</tbody>
			</table>
		</div>
		<?php } ?>
	</div>
</div>

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
$scheduleDateSeminarUrl = Yii::app()->createUrl('front/seminar/SeminarIndex');
$SeminarUrl = Yii::app()->getRequest()->getUrl();
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
	'toggleModel',
	'
		var height1 = 0;
		$.each([0,1,2,3] , function(index,val){
			height1 += $($("#table1").find("tr")[val]).height();
		});
		$("#widget-box1").height(height1);
		
		var height2 = 0;
		$.each([0,1,2,3] , function(index,val){
			height2 += $($("#table2").find("tr")[val]).height();
		});
		if(height2) $("#widget-box2").height(height2);
		
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
		var updateSeminarScheduleDate = function (date) {
			return $.ajax({
				url: "'.$scheduleDateSeminarUrl.'",
				type: "POST",
				dataType: "json",
				data: {
					schedule_date: date,
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
			 $("#sm-rg-cfm").attr("currentSeminar",currentSeminar);
			 $("#sm-rg-cfm").attr("currentStudent",currentStudent);
                        $("#sm-rg-confirmation").modal("show").width(700).css("margin-left",-350);
                        $("#myModal .modal-body").find("input, select, textarea").attr("disabled", false);

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
		$("#sm-rg-cfm-btn").click(function() {
			showLoading();
			date_schedule = $("#date_schedule").val();
			updateSeminarScheduleDate(date_schedule).done(function(response) {
				hideLoading();
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
		<a data-dismiss="modal" class="btn btn-primary " id = "sm-rg-cfm"  >登録</a>
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
