<!-- <h1>生命倫理審査委員会E-ラーニング</h1> -->
<?php $this->pageTitle="合格者情報取り込み - 臨床研究認定管理者サイト" ?>
<h1>生命倫理審査委員会E-ラーニング<span>合格者情報取り込み<span></h1>
<div class="fluid-container">
	<div class="row-fluid">
		<div class="form-horizontal span5" style="margin-left: 30px">
			<table>
				<th>取り込みファイル</th>
				<td>
					<input type="file" accept="*.csv,.csv,text/csv" name="import_file" id="get-csv-file"/>
				</td>
				<td>
					<button class="btn btn-success" name="import" id="import" type="submit">取り込み</button>
				</td>
			</table>
		</div>
	</div>
</div>

<?php
$url = $this->createUrl('');
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'handleCSV',
    '
    	$("input[type=file]").uniform();
    	var csv = "";
    	var separator = ","; // in case client use other csv formats

		var importData = function(d, a) {
			return $.ajax({
				url: "'.$url.'",
				type: "POST",
				dataType: "html",
				data: {
					info: d,
					action: a
				},
			});
		}

    var handleCSVFile = function(evt) {
    	evt.stopPropagation();
    	evt.preventDefault();
      var file = evt.target.files[0]; // FileList object.
      // files is a FileList of File objects. List some properties.
      var r = new FileReader();
      r.onload = function(e) {
      	csv = contents = e.target.result;
      }
      r.readAsText(file);
    }

    document.getElementById("get-csv-file").addEventListener("change", handleCSVFile, false);

    $("#import").on("click", function() {
			showLoading();
    	var data = new Array();
    	try {
  	    temp = csv.trim().split("\r\n").slice(1);
				for (var i = 0; i < temp.length; i++) {
					var st = new Object();
					var codes = temp[i].split(",");
					st.student_code = codes[0];
					st.school_reg_code = codes[1];
					st.staff_code = codes[2];
					data.push(st);
				}
  	    importData(data, 1)
      	.done(function(response) {
      		showSuccessFlash(response);
					autoHide();
      	})
				.fail(function(response) {
	        showErrorFlash("合格者情報の取り込みは失敗しました。");
					autoHide();
				})
				.always(function() {
					hideLoading();
				})
      }
    	catch(err) {
  	    showErrorFlash("合格者情報の取り込みは失敗しました。");
  	    autoHide();
  		}
    });
    ',
    CClientScript::POS_END
);
