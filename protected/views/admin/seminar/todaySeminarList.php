<?php $this->pageTitle = "受講者受付処理 - 臨床研究認定管理者サイト" ?>
<h1>受講者受付処理</h1>
<p>受講者受付を行うセミナーの「受付」ボタンをクリックしてください。</p>

<table class="table table-bordered table-striped">
    <thead>
    <tr>
        <th class="col-center">開催日時</th>
        <th class="col-center">セミナー名称</th>
        <th class="col-center">開催場所</th>
        <th class="col-center">受付処理</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($seminars as $seminar): ?>
    <tr class="odd gradeX">
        <td class="col-center"><?php echo reFormatDate($seminar->start_date) ?> <br />
            <?php echo $seminar->from_time."～".$seminar->to_time ?></td>
        <td><?php echo $seminar->name ?></td>
        <td class="col-center"><?php echo $seminar->location ?></td>
        <td class="col-center"><a href="<?php echo Yii::app()->createUrl('admin/seminar/editTodaySeminarList', array('s_id' => $seminar->id))?>" class="btn btn-success">受付</a></td>
    </tr>
    <?php endforeach ?>
    </tbody>
</table>
<?php $this->widget('customPager', array( 'pages' => $pages,)) ?>
<div class="modal fade bs-example-modal-lg" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<?php
$manageTodaySeminarUrl = $this->createUrl('html/editTodaySeminarList');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '
        $(".modal-btn").click(function() {
            $("#myModal").modal({
                remote: "' . $manageTodaySeminarUrl . '"
            }).width(700).css("margin-left",-350);
        });
    ',
    CClientScript::POS_END
);