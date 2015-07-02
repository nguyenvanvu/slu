<div class="fluid-container">
	<div class="row-fluid">
		<div class="span3">
			<small class="control-label span5">名大ID</small>
			<strong class="span7" name="lbl_p_student_code"><?php echo $student->student_code ?></strong>
		</div>
		<div class="span4">
			<small class="control-label span5">学籍・教員番号</small>
			<strong class="span7" name="lbl_p_professor_code"><?php echo $student->professor_code ?></strong>
		</div>
		<div class="span4">
			<small class="control-label span5">所属</small>
			<strong class="span7" name="lbl_p_faculty"><?php echo Yii::app()->params['faculty_values'][$student->faculty]; ?></strong>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span3">
			<small class="control-label span5">名前</small>
			<strong class="span7" name="lbl_p_name"><?php echo $student->first_name."　".$student->last_name ?></td>
                    </strong>
		</div>
		<div class="span4">
			<small class="control-label span5">フリガナ</small>
			<strong class="span7" name="lbl_p_kana"><?php echo $student->first_kana."　".$student->last_kana ?></strong>
		</div>
		<div class="span4">
			<small class="control-label span5">メールアドレス</small>
			<strong class="span7" name="lbl_p_email"><?php echo $student->email ?></strong>
		</div>
	</div>
</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>開催日時</th>
			<th>セミナー名称</th>
			<th>開催場所</th>
			<th>受講状況</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($seminars as $s): ?>
			<tr>
				<td class="col-center"><?php echo reFormatDate($s->seminar->start_date) ?> <br />
            <?php echo $s->seminar->from_time."～".$s->seminar->to_time ?></td>
				<td><?php echo $s->seminar->name ?></td>
        <td class="col-center"><?php echo $s->seminar->location ?></td>
				<td class="col-center"><?php echo translate_attention_status($s); ?></td>
				
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
<!-- pager -->
<?php $this->widget('customPager', array( 'pages' => $pages, 'jsCallback' => 'showPage' ));