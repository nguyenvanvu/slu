<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<!-- SmartNet CSS -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/maruti-style.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/maruti-media.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/uniform.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/custom.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/datepicker.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.dataTables.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style_ex.css" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<!--Header-part-->
<div id="header">
	<h1><a href="dashboard.html">Maruti Admin</a></h1>
</div>
<!--close-Header-part-->

<!--top-Header-messaages-->
<div class="btn-group rightzero"><a class="top_message tip-left" title="Manage Files"><i class="icon-file"></i></a> <a
		class="top_message tip-bottom" title="Manage Users"><i class="icon-user"></i></a> <a
		class="top_message tip-bottom" title="Manage Comments"><i class="icon-comment"></i><span
			class="label label-important">5</span></a> <a class="top_message tip-bottom" title="Manage Orders"><i
			class="icon-shopping-cart"></i></a></div>
<!--close-top-Header-messaages-->

<!--top-Header-menu-->
<div id="user-nav" class="navbar navbar-inverse">
	<ul class="nav">
		<li class="" ><div class="padding-text"><i class="icon icon-user"></i> <span class="text"><?php echo Yii::app()->user->id; ?></span></div></li>
		<li class=""><a title="" href="<?php echo Yii::app()->createUrl('admin/logout'); ?>"><i class="icon icon-share-alt"></i> <span class="text">ログアウト</span></a></li>
	</ul>
</div>
<!--close-top-Header-menu-->

<div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> Dashboard</a>
	<ul>
		<li class="active submenu"><a href="<?php echo Yii::app()->createUrl('admin/student/studentManagement');
			?>"><span>受講者管理</span></a>
			<ul>
				<li><a href="<?php echo Yii::app()->createUrl('admin/student/studentManagement');?>">受講者管理</a></li>
				<li><a href="<?php echo Yii::app()->createUrl('admin/seminar/registeredSeminarList');?>">受講予定者一覧</a></li>
			</ul>
		</li>
		<li class="submenu"><a href="<?php echo Yii::app()->createUrl('admin/seminar/todaySeminarList');
			?>"><span>受付処理</span></a>
			<ul>
				<li><a href="<?php echo Yii::app()->createUrl('admin/seminar/todaySeminarList');
			?>">受講者受付処理</a></li>
			</ul>
		</li>
		<li class="submenu"><a href="<?php echo Yii::app()->createUrl('admin/seminar/finishedSeminarList');?>"><span>受講状況管理</span></a>
			<ul>
				<li><a href="<?php echo Yii::app()->createUrl('admin/seminar/finishedSeminarList');?>">セミナー別受講者一覧</a></li>
				<li><a href="<?php echo Yii::app()->createUrl('admin/student/statusStudentList'); ?>">受講者管理</a></li>
			</ul>
		</li>
		<li><a href="<?php echo Yii::app()->createUrl('admin/seminar/seminarManagement');?>"><span>セミナー管理</span></a></li>
		<li class="submenu"><a href="<?php echo Yii::app()->createUrl('admin/student/attendedStudent');?>"><span>認定者管理</span></a>
			<ul>
				<li><a href="<?php echo Yii::app()->createUrl('admin/student/attendedStudent');?>">認定者管理</a></li>
<!--				<li><a href="#">認定結果一覧</a></li>-->
				<li><a href="<?php echo Yii::app()->createUrl('admin/test/testList');?>">認定試験管理</a></li>
			</ul>
		</li>
		<li><a href="<?php echo Yii::app()->createUrl('admin/student/importData');?>"><span>合格者情報取り込み</span></a></li>
	</ul>
</div>
<div id="content">
	<div class="container-fluid">
		<?php echo $content; ?>
	</div>
</div>
</div>
</div>
<div class="row-fluid">
	<div id="footer" class="span12">2014 &copy; Rinshou Kenkyu.</div>
</div>
<?php if(Yii::app()->user->hasFlash('success')):?>
	<div class="alert alert-success flash-success alert-block hide-message">
		<?php echo Yii::app()->user->getFlash('success'); ?>
	</div>
<?php endif; ?>
<?php if(Yii::app()->user->hasFlash('error')):?>
	<div class="alert alert-error flash-error alert-block hide-message">
		<?php echo Yii::app()->user->getFlash('error'); ?>
	</div>
<?php endif; ?>
<div class="alert alert-success flash-success  alert-block hide-message hide"></div>
<div class="alert alert-error flash-error alert-block hide-message hide"></div>
<div id="ajax-load"><img src="<?php echo Yii::app()->request->baseUrl; ?>/img/ajax-loader.gif"></div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.ui.custom.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.dataTables.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/maruti.tables.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap-datepicker.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/custom.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery.uniform.js"></script>



<script type="text/javascript">
	// This function is called from the pop-up menus to transfer to
	// a different page. Ignore if the value returned is a null string:
	function goPage(newURL) {

		// if url is empty, skip the menu dividers and reset the menu selection to default
		if (newURL != "") {

			// if url is "-", it is this page -- reset the menu:
			if (newURL == "-") {
				resetMenu();
			}
			// else, send page to designated URL
			else {
				document.location.href = newURL;
			}
		}
	}

	// resets the menu selection upon entry to this page:
	function resetMenu() {
		document.gomenu.selector.selectedIndex = 2;
	}
</script>
</body>
</html>
