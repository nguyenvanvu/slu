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
    <?php foreach ($students as $st): ?>
        <tr class="odd gradeX">
            <td><?php echo $st->reg_code ?></td>
            <td><?php echo $st->student_code ?></td>
            <td class="col-center"><?php echo $st->first_name." ".$st->last_name ?></td>
            <td class="col-center"><?php echo $st->first_kana." ".$st->last_kana ?></td>
            <td><?php echo $faculty = Yii::app()->params['faculty_values'][$st->faculty]; ?></td>
            <td><?php echo $st->professor_code ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<!-- pager -->
<?php $this->widget('customPager', array( 'pages' => $pages, 'jsCallback' => 'showPage' ));