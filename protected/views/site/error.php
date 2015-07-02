<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
?>

<h2>Error <?php echo $code; ?></h2>

<div class="error">
<?php if ($code == 404): ?>
<?php echo CHtml::encode($message); ?>
<?php else: ?>
<?php echo "サーバー内部エラー" ?>
<?php endif; ?>
</div>