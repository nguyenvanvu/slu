<div class="widget-box">
	<div class="widget-content nopadding">
		<table class="table table-bordered table-striped">
			<thead>
			<tr>
				<th>実施日</th>
				<th>問題数</th>
				<th>正解数</th>
				<th>合否</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($testHistory as $history): ?>
				<tr class="odd gradeX">
					<td style="text-align: center"><?php echo reFormatDate($history->date) ?></td>
					<td style="text-align: center"><?php echo $history->am ?></td>
					<td style="text-align: center"><?php echo $history->point ?></td>
					<td style="text-align: center"><?php if($history->pof == 0){echo "不合格";}elseif($history->pof == 1){echo "合格";}?></td>
				</tr>
			<?php endforeach ?>
			</tbody>
		</table>
		<div style="float: right">
			<?php $this->widget('customPager', array( 'pages' => $pages, 'jsCallback' => 'showPage' )); ?>
		</div>
	</div>
</div>