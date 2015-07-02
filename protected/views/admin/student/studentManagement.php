<?php
$fac_0 = $fac_1 = $fac_2 = $fac_3='';
if(isset($data['search']['s_faculty']))
{
    $fac = explode(',',$data['search']['s_faculty']);
}
if (isset($fac)) {
    $fac_0 = in_array(0, $fac) ? 'checked' : "";
    $fac_1 = in_array(1, $fac) ? 'checked' : "";
    $fac_2 = in_array(2, $fac) ? 'checked' : "";
    $fac_3 = in_array(3, $fac) ? 'checked' : "";
}
?>

<?php $this->pageTitle='受講者管理 - 臨床研究認定管理者サイト' ?>
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
                        <th>名大ID</th><td><input type="text" class="ime-disabled" name="s_student_code" value="<?php if(isset($data['search']['s_student_code']))echo $data['search']['s_student_code']; ?>" /></td>
                        <th>学籍・教員番号</th><td><input type="text" class="ime-disabled" name="s_professor_code" value="<?php if(isset($data['search']['s_professor_code']))echo $data['search']['s_professor_code']; ?>"/></td>
                        <th>メールアドレス</th><td><input type="text" class="ime-disabled" name="s_email" value="<?php if(isset($data['search']['s_email']))echo $data['search']['s_email']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>名前</th><td><input type="text" name="s_name" value="<?php if(isset($data['search']['s_name']))echo $data['search']['s_name']; ?>"/></td>
                        <th>フリガナ</th><td><input type="text" name="s_kana" value="<?php if(isset($data['search']['s_kana']))echo $data['search']['s_kana']; ?>"/></td>
                    </tr>
                    <tr>
                        <th>所属</th>
                        <td colspan="4">
                            <label><input name="s_faculty[]" value="0" type="checkbox" <?php echo $fac_0;?>> 附属病院</label>
                            <label><input name="s_faculty[]" value="1" type="checkbox" <?php echo $fac_1;?>> 医学部</label>
                            <label><input name="s_faculty[]" value="2" type="checkbox" <?php echo $fac_2;?>> 他学部（学内）</label>
                            <label><input name="s_faculty[]" value="3" type="checkbox" <?php echo $fac_3;?>> その他</label>
                        </td>
						<td class="filter-actions2"><button name="search" value="search" type="submit" class="btn btn-green1 span2">検索</button></td>
                    </tr>
                </table>

            </div>
        </form>
    </div>
</div>
    <button class="btn btn-primary" id="new-registration" style="float: right;margin-bottom: 10px;">新規登録</button>

<table class="table table-bordered table-striped td-center">
    <thead>
    <tr>
        <th>名大ID</th>
        <th>名前</th>
        <th>フリガナ</th>
        <th>所属</th>
        <th>学籍・教員番号</th>
        <th>メールアドレス</th>
        <th>管理</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if(isset($data['list_student']))
    {
        foreach($data['list_student'] as $value)
        {
            $faculty = Yii::app()->params['faculty_values'][$value->faculty];
            echo '<tr class="odd gradeX">
                        <td>'.$value->student_code.'</td>
                        <td>'.$value->first_name.'　'.$value->last_name.'</td>
                        <td>'.$value->first_kana.'　'.$value->last_kana.'</td>
                        <td>'.$faculty.'</td>
                        <td>'.$value->professor_code.'</td>
                        <td>'.$value->email.'</td>
                        <td><button name="id_student" value="'.$value->id.'" class="btn btn-success bt_edit">編集</button></td>
                    </tr>';
        }
    }
    ?>
    </tbody>
</table>
<?php $this->widget('customPager', array( 'pages' => $pages, 'searchParams' => $searchParams )) ?>
<div class="modal fade autoModal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
	 aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">アカウント修正</h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
        </div>
    </div>
</div>

<?php
$newSeminarStudentUrl = Yii::app()->createUrl('admin/student/NewStudentManagement');
$registeredStudentsUrl = $this->createUrl('admin/student/editStudentManagement');
$currentUrl = getCurrentUrl(array_merge($_GET,$searchParams?$searchParams:array(),array('page'=>$pages->currentPage+1)));
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '
        $("#new-registration").click(function() {
		    $("#myModalLabel").text("受講者アカウント登録");
            $(".modal-body").load("' . $newSeminarStudentUrl . '", function() {
                $("#myModal").modal("show").width(700).css("margin-left",-350);
                $(".datepicker").datepicker().on("changeDate", function() {$(this).datepicker("hide")});
            });
        });
       function newStudentManager() {
        $("#myModalLabel").text("アカウント登録");
            showLoading();
            var data = $("#form_student").serialize();
               $.ajax({
                    url: "'.$newSeminarStudentUrl.'",
                    type: "post",
                    data: data,
                    success: function(response) {
                        if (response.substring(0, 6) == "[code]"){
                            alert("登録が完了しました。  \n\n臨床研究認定ID： " + response.substring(6));
                            window.location = "' . $currentUrl . '";
                        }else{
                            $(".modal-body").html(response);
                                $("#myModal").modal("show").width(700).css("margin-left",-350)
                        }
                        hideLoading();
                    },
                    error: function(){
                        alert("Error occured.please try again");
                        hideLoading();
                    }
                });
                return false;
        };
        $(".bt_edit").click(function() {
			$("#myModalLabel").text("アカウント修正");
        	showLoading();
            var id_student = $(this).val();
               $.ajax({
                    url: "'.$registeredStudentsUrl.'",
                    type: "post",
                    data: {"id_student" : id_student},
                    success: function(response) {
                    	$(".modal-body").html(response);
							$("#myModal").modal("show").width(700).css("margin-left",-350)
                    	hideLoading();
                    },
                    error: function(){
                    	hideLoading();
                    }
                });
                return false;
        });

        $("button[name=button]").live("click",function() {
            var select = $(this).val();
            if(select=="update"){
            	if (confirm("受講者情報を更新します。\nよろしいですか？")){
            		showLoading();
            		var data = $("#form_student").serialize();
					data += "&action=update";
					$.ajax({
						type: "POST",
								url: "' . $this->createUrl('admin/student/editStudentManagement') . '",
						data:data,
						success:function(data){
							if (data == ""){
								window.location = "' . $currentUrl . '";
							}else{
								$(".modal-body").html(data);
								hideLoading();
							}
						},
						error: function(data) { // if error occured
							hideLoading();
							alert("Error occured.please try again");
						},
							dataType:"html"
					});
				}
            }else if(select=="delete"){
            	if (confirm("受講者情報を削除します。\nよろしいですか？")){
            		showLoading();
            		data = "action=delete&id=" + $("#RegisterForm_id").val();
					$.ajax({
						type: "POST",
								url: "' . $this->createUrl('admin/student/editStudentManagement') . '",
						data:data,
						success:function(data){
							if (data == ""){
								window.location = "' . $currentUrl . '";
							}
							hideLoading();
						},
						error: function(data) { // if error occured
							alert("Error occured.please try again");
							hideLoading();
						},
							dataType:"html"
					});
				}
			}
        });
    ',
    CClientScript::POS_END
);