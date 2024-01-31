<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<link href="<?php html_css('css/style.css?v=20170927'); ?>" rel="stylesheet" type="text/css" />
		<link type="text/css" href="<?php html_css('css/ui-lightness/jquery-ui-1.8.22.custom.css'); ?>" rel="stylesheet" />
        <link type="text/css" href="<?php html_css('css/lightbox.css'); ?>" rel="stylesheet" />
		<script type="text/javascript" src="<?php html_js('js/jquery-1.7.2.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php html_js('js/jquery-ui-1.8.22.custom.min.js'); ?>"></script>
		<script type="text/javascript" src="<?php html_js('js/jquery-ui.datepicker-vi.js'); ?>"></script>
        <script type="text/javascript" src="<?php html_js('js/lightbox.js'); ?>"></script>

		<script type="text/javascript" language="javascript">
		var CONFIG = {"BASE_DOMAIN":"<?php echo BASE_DOMAIN; ?>","BASE_URL":"<?php echo BASE_URL; ?>","BASE_URL_FRONT_END":"<?php echo FRONTEND_DOMAIN; ?>"};
		</script>

		<script type="text/javascript" src="<?php html_js('js/ocm24h.js?v=20171128'.time()); ?>"></script>
		<script type="text/javascript" src="<?php html_js('js/tooltip.js'); ?>"></script>
      
        <script type="text/javascript" src="<?php html_js('js/jquery.tokeninput.js'); ?>"></script>

        <link rel="stylesheet" href="<?php html_css('css/token-input.css');?>" type="text/css" />
        <link rel="stylesheet" href="<?php html_css('css/token-input-facebook.css'); ?>" type="text/css" />
		<title><?php echo html_get_title();?></title>
        <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-6528669-5', '24h.com.vn');
        ga('send', 'pageview');
        </script>
        <?php /* Begin anhpt1 07/07/2016 fix_loi_bao_mat_clickjaking */ 
        header('X-Frame-Options: DENY'); 
        /* End anhpt1 07/07/2016 fix_loi_bao_mat_clickjaking */ ?>
	</head>
<body>
	<div class="header">
		<!--<img src="<?php html_image('images/header.jpg'); ?>" alt="" width="612"/> -->
		<div class="logged">Xin chào <span class="user"><?php echo $_SESSION['user'] ?></span> | <a href="<?php html_link('ajax/user/logout.php'); ?>">Thoát</a></div>
	</div>
