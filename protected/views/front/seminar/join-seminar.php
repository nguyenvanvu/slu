<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>開催スケジュール一覧の</title>
<style type="text/css">
body {
	color: #5a554e;
	font: 14px "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS PGothic", sans-serif;
}
#page a {
	color: #516c00;
	font: 14px "ヒラギノ角ゴ Pro W3", "Hiragino Kaku Gothic Pro", "メイリオ", Meiryo, Osaka, "ＭＳ Ｐゴシック", "MS PGothic", sans-serif;
	text-decoration: underline;
}
#page a:hover {
    color: #516c00;
    font: 14px "ヒラギノ角ゴ Pro W3","Hiragino Kaku Gothic Pro","メイリオ",Meiryo,Osaka,"ＭＳ Ｐゴシック","MS PGothic",sans-serif;
    text-decoration: none;
}
</style>
</head>
<body>
<div id="page">
  <table cellspacing="0" cellpadding="5" border="1" style="width:600px">
    <tbody>
      <tr>
        <td bgcolor="#E0FFFF" width="90" style="text-align: center;">開催日時</td>
        <td bgcolor="#E0FFFF" width="220" style="text-align: center;">テーマ</td>
        <td bgcolor="#E0FFFF" style="text-align: center;">講師</td>
        <td bgcolor="#E0FFFF" width="70" style="text-align: center;">場所</td>
        <td bgcolor="#E0FFFF" width="45" style="text-align: center;">申込み</td>
      </tr>
      <?php foreach ($arrResults as $key => $row): ?>
      <tr<?php echo ($row['disable_apply'] == 0) ? ' bgcolor="#F0E68C"' : ''; ?>>
        <td><?php echo date("m",strtotime($row['start_date'])); ?>月<?php echo date("d",strtotime($row['start_date'])); ?>日(<?php echo formatDateToJP($row['start_date'], 3); ?>)<br>
          <?php echo $row['from_time']; ?>-<?php echo $row['to_time']; ?></td>
        <td><?php echo $row['name']; ?></td>
        <td style="text-align: center;"><?php echo $row['lecturer']; ?></td>
        <td style="text-align: center;">
        	<?php if ( trim($row['location_url']) != "" ): ?>
        		<a href="<?php echo $row['location_url']; ?>" target="_blank" title=""><?php echo $row['location']; ?></a>
            <?php else: ?>
            	<?php echo $row['location']; ?>
            <?php endif; ?>
        </td>
        <td style="text-align: center;">
        	<?php if ( $row['disable_apply'] == 1 ): ?>
            	<span style="color: #888888;">受付前</span>
            <?php elseif ( $row['disable_apply'] == 2 ): ?>
            	<span style="color: #888888;">受付終了</span>
            <?php elseif ( $row['disable_apply'] == 0 ): ?>
            	<a href="http://<?php echo $_SERVER['HTTP_HOST']; ?>" target="_blank" style="color:#FF0000;">受付中</a>
            <?php endif; ?>
         </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>
