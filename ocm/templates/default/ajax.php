<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link href="<?php html_css('css/style.css') ;?>" rel="stylesheet" type="text/css" />
<link type="text/css" href="<?php html_css('css/ui-lightness/jquery-ui-1.8.22.custom.css'); ?>" rel="stylesheet" />
<script type="text/javascript" src="<?php html_js('js/jquery-1.7.2.min.js'); ?>"></script>
<script type="text/javascript" src="<?php html_js('js/ocm24h.js?v=20170906'.time()); ?>"></script>
<script type="text/javascript" src="<?php html_js('js/jquery-ui-1.8.22.custom.min.js'); ?>"></script>	
<script type="text/javascript" src="<?php html_js('js/jwplayer.js'); ?>"></script>
<script type="text/javascript" language="javascript">
var CONFIG = {"BASE_DOMAIN":"<?php echo BASE_DOMAIN?>","BASE_URL":"<?php echo BASE_URL?>","BASE_URL_FRONT_END":"<?php echo FRONTEND_DOMAIN?>"};
</script>
<title><?php echo html_get_title();?></title>
<script type="text/javascript">
$(document).ready(function() {
    $("body").keypress(function(e){
        if (e.keyCode == 27) { //Esc keycode
            window.close();
        }
    });
});
</script>
</head>

<body>
<div class="header-popup"><?php echo html_get_title();?></div>
<div class="popupContent">
<?php echo $__MASTER_CONTENT__; ?>
</div>
</body>
</html>