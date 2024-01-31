<!DOCTYPE html>
<html>
<head>
	<title></title>
	<?php mz_echo_magazine_head_files($v_arr_head_files); ?>
</head>
<?php
$v_device = 'pc';
$v_url_preview = BASE_DOMAIN.'ocm/magazine/act_preview_magazine/'.$v_magazine_id;
$v_device = ($_GET['v_device'] != '') ? $_GET['v_device'] : $v_device;
$v_is_iframe = intval($_GET['v_is_iframe']);
?>
<body>
    <?php 
    if($v_is_iframe == 0){
        ?>
        <div class="error_box cap1 padBot">
            <center>
                <label style="cursor:pointer">
                    <input type="radio" name="rad_option"  id = "rad_option1" value="1"  <?php echo ($v_device == 'pc') ? 'checked' : ''; ?> onclick="window.location.href='<?php echo $v_url_preview.'?v_device=pc' ?>'" />
                    Phiên bản web
                </label>
                <label style="cursor:pointer">
                    <input type="radio" name="rad_option"  id = "rad_option2" value="1" <?php echo ($v_device == 'tablet') ? 'checked' : ''; ?>  onclick="window.location.href='<?php echo $v_url_preview.'?v_device=tablet' ?>'" />
                    Phiên bản ipad
                </label>
                <label style="cursor:pointer">
                    <input type="radio" name="rad_option"  id = "rad_option3" value="1" <?php echo ($v_device == 'mobile') ? 'checked' : ''; ?> onclick="window.location.href='<?php echo $v_url_preview.'?v_device=mobile' ?>'"  />
                    Phiên bản mobile
                </label>
            </center>
        </div>
        <?php 
    }
    if($v_device != 'tablet' && $v_device != 'mobile'){
        echo $v_magazine_body_html; 
    }elseif($v_device == 'tablet'){
        ?>
        <iframe src="<?php echo $v_url_preview.'?v_is_iframe=1'; ?>" style="position: absolute;left: 50%;transform: translateX(-50%);" id="ifm_url_preview_mobile" width="1024" height="768" frameborder="0"></iframe>    
        <?php
    }elseif($v_device == 'mobile'){
        ?>
        <iframe src="<?php echo $v_url_preview.'?v_is_iframe=1'; ?>" style="position: absolute;left: 50%;transform: translateX(-50%);" id="ifm_url_preview_mobile" width="400" height="632" frameborder="0"></iframe>    
        <?php
    }
    
    ?>
</body>
</html>