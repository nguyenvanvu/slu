<?php
/* @var $this UserController */

$this->pageTitle=Yii::t('front','seminar.page_title');
?>
<h1>過去セミナー申告</h1>
<div class="widget-box">
	<div class="widget-content nopadding">
		<table class="table table-bordered  td1-center td3-center td4-center">
			<thead>
			<tr>
				<th  nowrap="nowrap">開催日時</th>
				<th  nowrap="nowrap" style = "width : 30%">セミナー名称</th>
                <th  nowrap="nowrap" style = "width : 15%">講師</th>
				<th  nowrap="nowrap"style = "width : 25%">開催場所</th>
                <th  nowrap="nowrap">詳細</th>
				<th  nowrap="nowrap">申告</th>
			</tr>
			</thead>
			<tbody>
			<?php
			foreach ($seminars as $seminar): ?>
                                <!-- Seminar Apply -->
				<?php
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
				?>
                <tr  class="odd gradeX "  >
					<td class="col-center" nowrap="nowrap"><?php echo formatDateToJP($seminar->start_date,2); ?> <br /><?php echo $seminar->from_time."～".$seminar->to_time ?></td>
					<td><?php echo $seminar->name ?></td>
                    <td><?php echo $seminar->lecturer?></td>
                    <td class="col-center">
						<?php if($seminar->location_url) { ?>
							<a target ="_blank" href="<?php echo  $seminar->location_url?>"><?php echo $seminar->location ?></a>
                        <?php } else { echo $seminar->location;}?> 
                    </td>
                     <td nowrap="nowrap">
						<button name="id_seminar"  class = "btn seminar-detail-btn"  value = "<?php echo  $seminar->id?>" > 表示</button>
					</td>
					<td class="col-center" nowrap="nowrap">
						<button data-student-id="<?php echo $student_id  ?>" data-s-id="<?php echo $seminar->id ?>" class="btn btn-success btnUpdate">申告</button>
					</td>	
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
$updateSeminarUrl = Yii::app()->createUrl('front/seminar/UpdateSeminarHistory');
$deleteSeminarUrl = Yii::app()->createUrl('front/seminar/DeleteSeminar');
$detailSeminarUrl = Yii::app()->createUrl('front/seminar/SeminarDetail');
$SeminarUrl = Yii::app()->getRequest()->getUrl();
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
	'toggleModel',
	'   
		var currentSeminar2 = 0;
		var updateSeminar = function (sid, student ) {
			return $.ajax({
				url: "'.$updateSeminarUrl.'",
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
		$(".btnUpdate").click(function() {
			if (confirm("出席登録します。よろしですか？")){
        		showLoading();
				currentSeminar2 = $(this).attr("data-s-id");
				currentStudent2 = $(this).attr("data-student-id");
				
				//alert(currentSeminar2);
				updateSeminar(currentSeminar2,currentStudent2).done(function(response) {
					hideLoading();
					//alert("申し込みを受け付けました。 \n\n受付番号： " + response.title);
					window.location.href ="'.$SeminarUrl.'";

				})
				hideLoading();
			  
    		}
		});
                
		$(".datepicker").datepicker();
	
		
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