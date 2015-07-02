<?php
/* @var $this UserController */
$this->pageTitle=Yii::t('front','test.page_title');
?>
<h1>認定試験受講</h1>

<div id="test" class="widget-box">
	<div class="widget-content nopadding">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<th style="width: 10%">問題No</th>
				<th>問題</th>
				<th style="width: 10%">解答</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($questions as $no => $content): ?>
			<tr id="q<?php echo $no+1 ?>">
				<td>問.<?php echo $no+1 ?></td>
				<td style="text-align: left">
					<?php echo nl2br($content['q']) ?>
				</td>
				<td>
					<ol class="radio_answer" style="list-style-type: none">
						<li><label>1 <input type="radio" class="a<?php echo $no+1 ?>" name="a<?php echo $no+1 ?>" value="1"></label></li>
						<li><label>2 <input type="radio" class="a<?php echo $no+1 ?>" name="a<?php echo $no+1 ?>" value="2"></label></li>
						<li><label>3 <input type="radio" class="a<?php echo $no+1 ?>" name="a<?php echo $no+1 ?>" value="3"></label></li>
						<li><label>4 <input type="radio" class="a<?php echo $no+1 ?>" name="a<?php echo $no+1 ?>" value="4"></label></li>
						<li><label>5 <input type="radio" class="a<?php echo $no+1 ?>" name="a<?php echo $no+1 ?>" value="5"></label></li>
					</ol>
				</td>
			</tr>
			<?php endforeach ?>
			</tbody>
		</table>
	</div>
</div>
<div class="filter-actions">
	<button type="submit" id="answer" name="answer" class="btn btn-success span2">解答</button>
</div>

<div class="modal fade autoModal" id="testResult" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">認定試験結果</h4>
			</div>
			<div class="modal-body">
				 読み込み中...
			</div>
		</div>
	</div>
</div>

<?php
$answerUrl = Yii::app()->createUrl('test');
$statusUrl = Yii::app()->createUrl('status');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
	'thisScript',
	'
		var submitAnswer = function(a) {
			return $.ajax({
				url: "'.$answerUrl.'",
				type: "post",
				dataType: "html",
				data: {
					answer: a
				},
			})
		}
		$("#answer").on("click", function() {
			var answers = new Array();
			var gaveUp = new Array();
			var error = true;
			for (i=1; i <='.count($questions).' ; i++) {
				if (!$(".a"+i+":checked").val()) {
					gaveUp.push(i);
					error = true;
				} else {
					answers.push($(".a"+i+":checked").val());
					error = false;
				}
			}
			if (error) {
				gaveUp.join("、");
				$("<div class=\"alert alert-error alert-block hide-message\">問."+gaveUp+"は解答されていません。</div>").insertBefore("#test");
				autoHide();
			} else {
				showLoading();
				submitAnswer(answers).done(function(response) {
					hideLoading();
					$("#testResult .modal-body").html(response)
					$("#testResult").modal("show").width(900).css({"margin-left":-450})
				})
			}
		});
		$("#testResult").on("hide.bs.modal", function() {
			window.location = "'.$statusUrl.'";
		})
    ',
	CClientScript::POS_END
);
?>
