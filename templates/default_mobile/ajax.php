<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<?php
$v_is_box_play_video = ($fwRequestUri != str_replace('box_player_video_by_url/index', '', $fwRequestUri) || strpos($fwRequestUri, 'box_xem_video_trang_video') !== false);
if ($v_is_box_play_video) {
	// 08/11/2014 HaiLT set riêng để đảm bảo ajax player_video_by_url luôn đúng
	$_REQUEST['BLOCK_VIEW_HEAD_TAG'] = 0;
}
if ($_REQUEST['BLOCK_VIEW_HEAD_TAG'] == 1) {?>			
	<!--begin:header-->
	<head>		
		<title>Tin tức 24h | tin nhanh bong da | the thao | thoi trang, giai tri vn | bao online</title>
		<meta property="og:description" name="description" content="Tin tuc trong ngay, bóng đá, thể thao, thời trang, giải trí. Update tin nhanh 24/24h. Nhiều đặc sản video tin tức việt nam, thế giới, video bong da anh chỉ có tại 24h.Tổng hợp tintuc vn, BAO CONG AN, an ninh, phap luat." />
		<meta name="keywords" content="tin tuc, tin tức, tin tuc trong ngay, bóng đá, thời trang, cười, tintuc, 24h, tin nhanh , the thao, tin nhanh, thoi trang, thời sự, bong da, bao cong an, bao an ninh, thoi su, giai tri, giải trí, bao" />
		<meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes" name="viewport" />
		<meta name="robots" content="noindex, nofollow"/>
        <script type="text/javascript" src="<?php echo str_replace(array('http://','https//'),'//',BASE_URL_FOR_PUBLIC); ?>js/jquery.min.js?v=<?php echo JS_CSS_VERSION; ?>"></script>
        <?php
        if (_is_test_domain()){
            echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global);
            echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
		} else {
            echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global);
            echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
		}
		?>
    </head>	
	<!--end:header-->
    <?php
}else{
	if ($v_is_box_play_video) {
		$v_arr_tmp = explode('box_player_video_by_url/index/', $fwRequestUri);
		$v_cat_id = intval($v_arr_tmp[1]);
		$v_region_id = get_region_id();
		$v_html_cau_hinh_thu_tu_hien_thi_quang_cao_video = fe_html_cau_hinh_thu_tu_hien_thi_quang_cao_video($v_cat_id, 2, $v_region_id);
		?>
		<head>
            <?php
            /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
            // ID bài viết
            /* Begin - Tytv 14/12/2017 - xay_dung_banner_tai_tro_video*/
            // ID bài viết
            $v_news_id  = 0;
            $v_is_bai_tuong_thuat = 0;
            preg_match('#p_is_bai_tuong_thuat=([^\&\?]+)#', $fwRequestUri, $matches_bai_tt);
            $v_is_bai_tuong_thuat = intval($matches_bai_tt[1]);
                // thực hiện kiểm tra xem có phải là video bài tường thuật ko?
            if($v_is_bai_tuong_thuat){

                preg_match('#p_url_now=([^\&\?]+)#', $fwRequestUri, $matches_url_news);
                $v_url_news = $matches_url_news[1];
                $v_arr_param_by_url_news = _lay_thong_so_theo_url_bai_viet($v_url_news);
                $v_news_id = intval($v_arr_param_by_url_news['v_news_id']);
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
                // Begin Trungcq 28-02-2019: XLCYCMHENG_34237_xu_ly_quang_cao_dfp_in_image_mobile
                $v_la_bai_viet_co_banner_in_image = false;
                // Lấy js slot dfp
                $v_script_slot_dfp  = _chen_va_lay_js_slot_dfp('', $v_cat_id, $v_region_value, $v_xuat_ban_trang_bai_viet, $v_la_bai_viet_co_banner_on_page, $v_la_bai_viet_co_banner_in_image, $v_return, $v_get_key_value, $v_row_slot_dfp,$v_row_news,$v_co_gan_video_tai_tro);                
                // End Trungcq 28-02-2019: XLCYCMHENG_34237_xu_ly_quang_cao_dfp_in_image_mobile
                if ($v_script_slot_dfp != '') {
                    if (strtolower($v_region_value) == 'us') {
                        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp_us());
                    } else {
                        echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp());
                    }
                }
            }
            ?>
            <script type="text/javascript" src="<?php echo str_replace(array('http://','https//'),'//',BASE_URL_FOR_PUBLIC); ?>js/jquery.min.js?v=<?php echo JS_CSS_VERSION; ?>"></script>
			<script type='text/javascript'>
				v_cat_id = <?php echo $v_cat_id; ?>;
			</script><?php
			if (_is_test_domain()){
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			} else {
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
			}
            // Chèn css cho 24h player
			if (PLAYER_VIDEO == '24H_PLAYER') {
                $v_css_video = 	'<style>'._read_file(WEB_ROOT.'css/24hplayer.min.css').'</style>';
                echo $v_css_video;
            }
			echo $v_html_cau_hinh_thu_tu_hien_thi_quang_cao_video;
			if (!USE_JWPLAYER_JS_WHEN_RUN && !USE_ZPLAYER){?>
				<script type="text/javascript" src="<?php echo JWPLAYER6_JS_URL; ?>?ver=20140526"></script>
				<?php
			}?>
            <?php    
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
?><body><?php 
	echo $masterContent;
	if ($v_is_box_play_video && USE_ZPLAYER && PLAYER_VIDEO == 'ZPLAYER') {
		// Begin: Tytv - 23/05/2017 - chuyen_su_dung_zplayer_html5
        echo '<script type="text/javascript" src="'.ZPLAYER_HTML5_POLYFILL_JS_URL.'"></script>';
        echo '<script type="text/javascript" src="'.ZPLAYER_HTML5_JS_URL.'"></script>';
        echo '<script type="text/javascript" src="'.ZPLAYER_LEGACY_JS_URL.'"></script>';
        // End: Tytv - 23/05/2017 - chuyen_su_dung_zplayer_html5
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
?><!--end-key-->
</body>
</html>