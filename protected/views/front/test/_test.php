<div class="row-fluid control-group">
<p>
    <span class="span2">正解数</span>
    <span class="red"><b><?php echo $correct ?>問</b></span>
</p>
<p>
    <span class="span2">合否判定</span>
    <span class="red"><b><?php echo $pass ? "合格" : "不合格" ?></b></span>
    <a href="<?php echo Yii::app()->createUrl('front/test/status');?>" class="btn btn-primary" id="check-certification" style="float: right">戻る</a>
</p>
</div>
<div id="test" class="widget-box">
    <div class="widget-content nopadding">
        <table class="table table-bordered table-striped">
            <thead>
            <tr>
                <th style="width: 10%">問題No</th>
                <th style="width: 30%">問題</th>
                <th style="width: 10%">解答</th>
                <th style="width: 10%">正解</th>
                <th style="width: 10%">判定</th>
                <th style="width: 30%">解説</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($questions as $no => $content): ?>
            <tr class="odd gradeX">
                <td>問.<?php echo $no+1 ?></td>
                <td class="text_left"><?php echo nl2br($content['q']) ?></td>
                <td><?php echo $answer[$no] ?></td>
                <td><?php echo $content['answer'] ?></td>
                <td><?php echo $content['answer'] == $answer[$no] ? "O" : "X" ?></td>
                <td class="text_left"><?php echo nl2br($content['exp']) ?></td>
            </tr>
            <?php endforeach ?>

            </tbody>
        </table>
    </div>
</div>
