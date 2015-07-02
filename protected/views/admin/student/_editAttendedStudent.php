<div id="edit_studens_box" class="content_attended">
    <input type="hidden" id="current_student" value="<?php echo $student->id; ?>">
        <div class="container-fluid">
            <div class="row-fluid control-group">
                <div class="span2">
                    <span>名大ID</span>
                </div>
                <div class="span4">
                    <span><?php echo $student->student_code; ?></span>
                </div>
                <div class="span3">
                    <span>学籍・教員番号</span>
                </div>
                <div class="span3">
                    <span><?php echo $student->professor_code; ?></span>
                </div>
            </div>
            <div class="row-fluid control-group">
                <div class="span2">
                    <span>名前</span>
                </div>
                <div class="span4">
                    <span><?php echo $student->first_name.'　'.$student->last_name; ?></span>
                </div>
                <div class="span3">
                    <span>フリガナ</span>
                </div>
                <div class="span3">
                    <span><?php echo $student->first_kana.'　'.$student->last_kana; ?></span>
                </div>
            </div>
            <div class="row-fluid control-group">
                <div class="span2">
                    <span>所属</span>
                </div>
                <div class="span4">
                    <span>
                        <?php
                        $faculty = Yii::app()->params['faculty_values'][$student->faculty];
                        echo $faculty; ?></span>
                </div>
                <div class="span3">
                    <span>メールアドレス</span>
                </div>
                <div class="span3">
                    <span><?php echo $student->email; ?></span>
                </div>
            </div>
        </div>
        <br />
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
                <?php
                if(isset($list_test))
                {
                    foreach($list_test as $value)
                    {
                        $fof = Yii::app()->params['pof_values'][$value->pof];
                        echo '<tr class="odd gradeX">';
                        echo '<td>'.reFormatDate($value->date).'</td>';
                        echo '<td>'.$value->am.'</td>';
                        echo '<td>'.$value->point.'</td>';
                        echo '<td>'.$fof.'</td>';
                    }
                }
                ?>
                </tbody>
            </table>
    <?php $this->widget('customPager', array( 'pages' => $pages, 'jsCallback' => 'showPage' )) ?>
            <div class="control-group right">
                <button type="button" name="hide" class="btn btn-success right">戻る</button>
            </div>
</div>