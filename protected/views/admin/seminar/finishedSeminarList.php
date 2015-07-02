<?php $this->pageTitle = "セミナー別受講者一覧 - 臨床研究認定管理者サイト" ?>
<h1>セミナー別受講者一覧</h1>
<p>受講者一覧を表示するセミナーの「表示」ボタンをクリックしてください。</p>
<div class="widget-box">
    <div class="widget-content nopadding">
        <table class="table table-bordered table-striped td1-center td3-center td4-center">
            <thead>
            <tr>
                <th>開催日時</th>
                <th>セミナー名称</th>
                <th>開催場所</th>
                <th>受講者一覧</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($seminars as $s): ?>
            <tr class="odd gradeX">
                <td><?php echo reFormatDate($s->start_date) ?> <br />
                    <?php echo $s->from_time."～".$s->to_time ?></td>
                <td><?php echo $s->name ?></td>
                <td class="col-center"><?php echo $s->location ?></td>
                <td class="col-center"><button class="btn view-st-list" data-s-id="<?php echo $s->id ?>">表示</button></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<!-- pager -->
<?php $this->widget('customPager', array( 'pages' => $pages )); ?>

<div class="modal fade autoModal" id="stListModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="modalLabel">Loading...</h4>
            </div>
            <div class="modal-body">
                Loading...
            </div>
        </div>
    </div>
</div>

<?php
$attendedStudentsUrl = Yii::app()->createUrl('admin/seminar/editFinishedSeminarList');
$getSeminarName = Yii::app()->createUrl('admin/seminar/getSeminarName');
$baseUrl = Yii::app()->baseUrl;
$cs = Yii::app()->getClientScript();
$cs->registerScript(
    'toggleModel',
    '   
        var currentSeminar = 0;
        var getSeminar = function (sid, page) {
            return $.ajax({
                url: "'.$attendedStudentsUrl.'",
                type: "POST",
                dataType: "json",
                data: {
                    s_id: sid,
                    page: page
                },
            });
        }

        function showPage(page){
            getSeminar(currentSeminar, page).done( function(response) {
                $(".modal-body").html(response.content);
            });
        }

        $(".view-st-list").click(function() {
            currentSeminar = $(this).data("sId");
            showLoading();
            getSeminar(currentSeminar, -1)
            .done(function(response) {
                $("#modalLabel").text(response.title + "受講者一覧");
                $(".modal-body").html(response.content);
                $("#stListModal").modal("show").width(700).css("margin-left",-350);
                hideLoading();
            })
            .fail(function() {
                hideLoading();
            })
        });
    ',
    CClientScript::POS_END
);