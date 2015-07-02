<?php $this->pageTitle= 'セミナー管理 - 臨床研究認定管理者サイトログイン'; ?>
<h1>セミナー管理</h1>
<button class="btn btn-primary" id="new-registration" style="float: right">新規登録</button>
<br />
<div class="widget-box">
    <div class="widget-content nopadding">
        <table class="table table-bordered table-striped td1-center td3-center td4-center td5-center">
            <thead>
            <tr>
               	<th nowrap="nowrap">開催日時</th>
                <th nowrap="nowrap" style="width:30%">セミナー名称</th>
                <th nowrap="nowrap" style="width:15%">講師</th>
                <th nowrap="nowrap" style="width:20%">開催場所</th>
                <th nowrap="nowrap" style="width:20%">開催機関</th>
                <th nowrap="nowrap">詳細</th>
                <th nowrap="nowrap">編集</th>
            </tr>
            </thead>
            <tbody>
            <?php
			
			
            if(!empty($list_seminar))
            {
                foreach($list_seminar as $value)
                {
                    $holding = Yii::app()->params['holding_values'][$value->holding];
                    if($value->location_url)
                        $location = '<a target = "_blank" href ='.($value->location_url?$value->location_url:"#").'/>' .$value->location. '</a>';
                    else $location = $value->location;
                    $row_html = '<tr class="odd gradeX">
                                    <td nowrap="nowrap">' 
                                        .  formatDateToJP($value->start_date,2) . '<br />'.
                                        $value->from_time.'～'.$value->to_time. 
                                   
                                    '</td>
                                    <td>'.$value->name.'</td>
                                    <td>'.$value->lecturer.'</td>    
                                    <td>'. $location .'</td>
                                    <td>'.$holding.'</td>
                                    <td nowrap="nowrap"><button class = "btn seminar-detail-btn"  value = '.$value->id .'>表示</button></td>
                                    <td nowrap="nowrap">';
					$tmp = explode(' ',$value->start_date);
                    $str_date = $tmp[0];
                    $str_date .= ' '.$value->to_time;
                    $str_date_now = date('Y-m-d H:i:s');
                    if((strtotime($str_date))>=(strtotime($str_date_now)))
                    {
                        $row_html .= '<button name="id_seminar" value="'.$value->id.'" class="btn seminar-edit-btn">編集</button>';
                    }
                    echo $row_html;
                }
            }
            ?>
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
$newSeminarManagementUrl = Yii::app()->createUrl('admin/Seminar/NewSeminarManagement');
$editSeminarManagementUrl = Yii::app()->createUrl('admin/Seminar/EditSeminarManagement');
$currentUrl = getCurrentUrl(array_merge($_GET,array('page'=>$pages->currentPage+1)));
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '
		$("#new-registration").click(function() {
		    $("#myModalLabel").text("セミナー情報登録");
            $(".modal-body").load("' . $newSeminarManagementUrl . '", function() {
                $("#myModal").modal("show").width(700).css("margin-left",-350);
                $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
            });
        });

        $("#new-btn").live("click",function() {
            if (confirm("セミナー情報を登録します。\nよろしいですか？")){
                showLoading();
				var data = $("#form_seminar").serialize();
				$.ajax({
					type: "POST",
							url: "' . $editSeminarManagementUrl. '",
					data:data,
					success:function(data){
						if (data == ""){
							window.location = "' . $currentUrl . '";
						}else{
					        $("#myModalLabel").text("セミナー情報登録");
					        $(".datepicker").remove();
							$(".modal-body").html(data);
							datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
							autoHide();
							hideLoading();
						}
					},
					error: function(data) { // if error occured
						alert("Error occured.please try again");
						hideLoading();
					},
						dataType:"html"
				});
			}
        });

        $(".seminar-edit-btn").click(function() {
            showLoading();
            var id_seminar = $(this).val();
               $.ajax({
                    url: "'.$editSeminarManagementUrl.'",
                    type: "post",
                    data: {"id_seminar" : id_seminar},
                    success: function(response) {
                        $("#myModalLabel").text("セミナー情報編集");
                        $(".datepicker").remove();
                        $(".modal-body").html(response);
                        datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
                        $("#myModal").modal("show").width(700).css("margin-left",-350)
                        //$(".modal-body textarea" ).attr("disabled", true);
                        autoHide();
                        hideLoading();
                    }
                });
                return false;
        });
        
        $(".seminar-detail-btn").click(function(){
             showLoading();
            var id_seminar = $(this).val();
               $.ajax({
                    url: "'.$editSeminarManagementUrl.'",
                    type: "post",
                    data: {"id_seminar" : id_seminar},
                    success: function(response) {
                        $("#myModalLabel").text("セミナー情報編集");
                        $(".datepicker").remove();
                        $(".modal-body").html(response);
                        datepicker = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
                        $("#myModal").modal("show").width(700).css("margin-left",-350)
                        $("#hide").html("戻る");
                        $(".btn.btn-danger.span2").hide()
                        $(".btn.btn-primary.span2").hide()
                        $(".modal-body").find("input, select, textarea").attr("disabled", true);
                        autoHide();
                        hideLoading();
                    }
                });
                return false;
        });
        
        $("button[name=button]").live("click",function() {
            var select = $(this).val();
            var mesbox = "";
            if(select=="update"){
            	if (confirm("セミナー情報を登録します。\nよろしいですか？")){
            	    showLoading();
            		var data = $("#form_seminar").serialize();
					data += "&action=update";
					$.ajax({
						type: "POST",
								url: "' . $editSeminarManagementUrl . '",
						data:data,
						success:function(data){
							if (data == ""){
								window.location = "' . $currentUrl . '";
							}else{
					            $("#myModalLabel").text("セミナー情報登録");
					            $(".datepicker").remove();
								$(".modal-body").html(data);
								datepicker4 = $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
								autoHide();
							}
							hideLoading();
						},
						error: function(data) { // if error occured
							alert("Error occured.please try again");
							alert(data);
							hideLoading();
						},
							dataType:"html"
					});
				}
            }else if(select=="delete"){
            	if (confirm("受講者情報を削除します。\nよろしいですか？")){
            	    showLoading();
            		data = "action=delete&id=" + $("#SeminarForm_id").val();
					$.ajax({
						type: "POST",
								url: "' . $editSeminarManagementUrl . '",
						data:data,
						success:function(data){
							if (data == ""){
								window.location = "' . $currentUrl . '";
							}
							hideLoading();
						},
						error: function(data) { // if error occured
							alert("Error occured.please try again");
							alert(data);
							hideLoading();
						},
							dataType:"html"
					});
				}
			}
        });

        $("#hide").live("click",function() {
            $("#myModal").modal("hide")
        });

    ',
    CClientScript::POS_END
);
?>
<script type="text/javascript">
function chanegFrTime(){
	var time = new Date();
	if($('#SeminarForm_from_time').val().match(/^([0-9]|0[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$/)){
		var aTime = $('#SeminarForm_from_time').val().split(':');
		var date_st = time.getDate();
		time.setHours(aTime[0]);
		time.setMinutes(aTime[1]);
		time.setMinutes(time.getMinutes() + 90);
		if(date_st!=time.getDate()) $('#SeminarForm_to_time').val('23:59');
		else $('#SeminarForm_to_time').val(	(time.getHours().toString().length<2?('0'+time.getHours()):time.getHours())
											+':'+
											(time.getMinutes().toString().length<2?('0'+time.getMinutes()):time.getMinutes())
										);
	}
}
</script>