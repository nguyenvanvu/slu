<div class="widget-box">
    <div class="widget-content nopadding">
		<input type="hidden" id="current_seminar" value="<?php echo $seminar_id; ?>">
        <table class="table table-bordered table-striped td-center">
            <thead>
            <tr>
                <th>受付番号</th>
                <th>名大ID</th>
                <th>名前</th>
                <th>フリガナ</th>
                <th>所属</th>
                <th>学籍・教員番号</th>
            </tr>
            </thead>
            <tbody>
            <?php
            if(isset($student_seminar))
            {
                foreach($student_seminar as $value)
                {
					$faculty = Yii::app()->params['faculty_values'][$value->faculty];
                    $apply_code ='';
                    if(isset($value->studentSeminars[0]))
                        $apply_code = $value->studentSeminars[0]->apply_code;
                    echo '<tr class="odd gradeX">
                        <td>'.$apply_code.'</td>
                        <td>'.$value->student_code.'</td>
                        <td>'.$value->first_name.'　'.$value->last_name.'</td>
                        <td>'.$value->first_kana.'　'.$value->last_kana.'</td>
                        <td>'.$faculty.'</td>
                        <td>'.$value->professor_code.'</td>
                    </tr>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php $this->widget('customPager', array( 'pages' => $pages, 'jsCallback' => 'showPage' )) ?>
<?php $registeredExportUrl = Yii::app()->createUrl('admin/seminar/exportRegisteredSeminarList'); ?>
<form action="<?php echo $registeredExportUrl; ?>" method="post">
    <input type="hidden" name="id_seminar" value="<?php if(isset($seminar_id)) echo $seminar_id; ?>">
<button type="submit" class="btn btn-success" id="export" value="<?php if(isset($seminar_id)) echo $seminar_id; ?>" style="float: right;">名簿印刷</button>
</form>