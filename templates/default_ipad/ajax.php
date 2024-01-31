<!DOCTYPE html>
<html>
<?php
$v_is_box_play_video = ($fwRequestUri != str_replace('box_player_video_by_url/index', '', $fwRequestUri));
if ($v_is_box_play_video) {
	// 08/11/2014 HaiLT set riêng để đảm bảo ajax player_video_by_url luôn đúng
	$_REQUEST['BLOCK_VIEW_HEAD_TAG'] = 0;
}

/* begin: Tytv - 27/11/2017 - bo_index_follow_cho_box_chay_qua_ajax */
$v_robot = '<meta name="robots" content="noindex, nofollow"/>';
/* End: Tytv - 27/11/2017 - bo_index_follow_cho_box_chay_qua_ajax */

if ($_REQUEST['BLOCK_VIEW_HEAD_TAG'] == 1) { 
    ?>			
	<!--begin:header-->
	<head>		
		<title>Tin tức 24h | tin nhanh bong da | the thao | thoi trang, giai tri vn | bao online</title>	
        <?php 
		//begin 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
		echo $v_robot;
		//End 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
		?>
		<script type="text/javascript" src="/js/jquery.min.js"></script><?php
			if (_is_test_domain()){
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			} else {
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			}
		?><script type="text/javascript" src="/js/swipe.js"></script>	
		<script type="text/javascript" src="/js/dhtml-menu.js"></script>	
		<script type="text/javascript" src="/js/vietUni.js"></script>	
		<?php 
		if (!USE_ZPLAYER) { ?>
			<script type="text/javascript" src="<?php echo JWPLAYER6_JS_URL; ?>"></script>
		<?php 
		} ?>
    </head>	
	<!--end:header-->
    <?php
} else {
	if ($v_is_box_play_video) {
		?><head>
			<?php 
			$v_arr_tmp = explode('box_player_video_by_url/index/', $fwRequestUri);
            $v_cat_id = intval($v_arr_tmp[1]);
            $v_region_id = get_region_id();
            /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
            // ID bài viết
            /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
            // ID bài viết
            $v_news_id  = 0;
            $v_is_bai_tuong_thuat = 0; 
            preg_match('#p_is_bai_tuong_thuat=([^\&\?]+)#', $_SERVER['REQUEST_URI'], $matches_bai_tt);
            $v_is_bai_tuong_thuat = intval($matches_bai_tt[1]);
                // thực hiện kiểm tra xem có phải là video bài tường thuật ko?
            if($v_is_bai_tuong_thuat){

                preg_match('#p_url_now=([^\&\?]+)#', $_SERVER['REQUEST_URI'], $matches_url_news);
                $v_url_news = $matches_url_news[1];
                $v_arr_param_by_url_news = _lay_thong_so_theo_url_bai_viet($v_url_news);
                $v_news_id = intval($v_arr_param_by_url_news['v_news_id']);
                $v_cat_id = intval($v_arr_param_by_url_news['v_cat_id']);
                $v_row_news = fe_bai_viet_theo_id($v_news_id);
                $v_co_gan_video_tai_tro = (!empty($v_row_news['c_list_ma_content']))?true:false;
                // $v_row_cat = fe_chuyen_muc_theo_id($v_cat_id);
                $v_region_value = get_region_value($v_region_id);
                
                // Trả về html header: 0 trả về script slot dfp/ 1 trả về html header
                $v_return   = 0;
                
                $v_xuat_ban_trang_bai_viet    = true;
                $v_la_bai_viet_co_banner_on_page    = false;
                // End Lucnd 23-06-2017: DFP_24h_xu_ly_quang_cao_dfp_in_image
                
                // Mảng dữ liệu slot dfp
                $v_row_slot_dfp     = array();
                $v_get_key_value    = 1;
                // Lấy js slot dfp
                $v_script_slot_dfp  = _chen_va_lay_js_slot_dfp('', $v_cat_id, $v_region_value, $v_xuat_ban_trang_bai_viet, $v_la_bai_viet_co_banner_on_page, $v_return, $v_get_key_value, $v_row_slot_dfp,$v_row_news, $v_co_gan_video_tai_tro);
                
                if ($v_script_slot_dfp != '') {
                    if (strtolower($v_region_value) == 'us') {
                        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp_us());
                    } else {
                        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp());
                    }
                }
                echo '<script type=\'text/javascript\'>
                    v_cat_id = '.$v_cat_id.';
                        adsData     = [];
                </script>';
            }
            ?>
            <script type="text/javascript" src="<?php echo str_replace(array('http://','https//'),'//',BASE_URL_FOR_PUBLIC); ?>js/jquery.min.js?v=<?php echo JS_CSS_VERSION; ?>"></script>
            <?php
            echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS);
			if (_is_test_domain()){
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			} else {
				echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			}
			
            // Chèn css cho 24h player
			if (PLAYER_VIDEO == '24H_PLAYER') {
                $v_css_video = 	'<style>'._read_file(WEB_ROOT.'css/24hplayer.min.css').'</style>';
                echo $v_css_video;
            }
            
			if (!USE_ZPLAYER) {
				?><script type="text/javascript" src="<?php echo JWPLAYER6_JS_URL; ?>"></script><?php
			} 
            if($v_is_bai_tuong_thuat){
                $html_js_ads    = '<script>'._read_file(WEB_ROOT.'js/ads_common.min.js').'</script>';
                echo $html_js_ads;
                $_js_quang_cao = _chen_js_quang_cao('<!--js_quang_cao-->', $v_cat_id, $v_region_id);
            ?>
                <script type='text/javascript'>
                //<![CDATA[
                    <?php echo $_js_quang_cao; ?>;
                //]]>
                </script>
            <?php 
            }
            /* End - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/ ?>
		</head><?php
	}
}
?>
<body>
<?php echo $masterContent; ?>
<!--end-key-->
<?php
if($v_is_box_play_video && USE_ZPLAYER && PLAYER_VIDEO == 'ZPLAYER') {
	// Begin: Tytv - 10/08/2017 - chuyen_su_dung_zplayer_html5
	echo '<script type="text/javascript" src="'.ZPLAYER_HTML5_POLYFILL_JS_URL.'"></script>';
	echo '<script type="text/javascript" src="'.ZPLAYER_HTML5_JS_URL.'"></script>';
	echo '<script type="text/javascript" src="'.ZPLAYER_LEGACY_JS_URL.'"></script>';
	// End: Tytv - 10/08/2017 - chuyen_su_dung_zplayer_html5
}else if (PLAYER_VIDEO == '24H_PLAYER') {
    // Khai bai script cần load 
    $v_html_load_player_video = '<!--[if lt IE 9]>
        <script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <!-- If you\'d like to support IE8 -->
        <script async src="//imasdk.googleapis.com/js/sdkloader/ima3.js?v='.JS_CSS_VERSION.'"></script>
        <script async src="'.IMAGE_STATIC.'js/videojs-ie8.min.js?v='.JS_CSS_VERSION.'"></script>';
    $v_html_load_player_video .= html_load_js_24hplayer();
    echo $v_html_load_player_video;
}
?>
</body>
</html>