<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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
		<title>Tin tức 24h, tin nhanh, bóng đá, thể thao tại 24h <?php echo time().rand(); ?></title>
        <meta name="description" content="Tin tức trong ngày, tin nhanh bóng đá, thể thao cập nhật liên tục 24h trong ngày <?php echo time().rand(); ?>" />
        <meta name="keywords" content="Tin tức, tin tức trong ngày, bóng đá, thể thao, tin nhanh, tin mới" />
		<script type="text/javascript" src="<?php echo str_replace('http:', '', BASE_URL_FOR_PUBLIC); ?>js/jquery.min.js?v=<?php echo JS_CSS_VERSION; ?>"></script>
        <?php
			//begin 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
			echo $v_robot;
			//End 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
			// các trường hợp đặc biệt với các trang thông tin cần biết
			if (!isset($_REQUEST['type_page'])) {
				if ($fwRequestUri != str_replace('box_ban_tin_gia_vang/index', '', $fwRequestUri)) {
					$_REQUEST['type_page'] = 1;
				}
				if ($fwRequestUri != str_replace('box_ban_tin_thoi_tiet/index', '', $fwRequestUri)) {
					$_REQUEST['type_page'] = 2;
				}
				if ($fwRequestUri != str_replace('box_tin_ty_gia_ngoai_te/index', '', $fwRequestUri)) {
					$_REQUEST['type_page'] = 3;
				}
				if ($fwRequestUri != str_replace('box_lich_truyen_hinh/index', '', $fwRequestUri)) {
					$_REQUEST['type_page'] = 4;
				}
				if ($fwRequestUri != str_replace(array('box_tim_kiem_diem_dat_atm/index', 'box_danh_sach_lien_minh_ngan_hang/index', 'box_chi_tiet_ngan_hang/index', 'box_danh_sach_faqs/index'), '', $fwRequestUri)) {
					$_REQUEST['type_page'] = 5;
				}
			}
			
			if (isset($_REQUEST['type_page'])) {
				$v_type_page = intval($_REQUEST['type_page']);
				switch ($v_type_page) {
					case 1: { // trang giá vàng
						?><link type="text/css" rel="stylesheet" href="/css/gia_vang-2014.css?ver=<?php echo JS_CSS_VERSION; ?>" /><?php
						break;
					}
					case 2: { // trang thời tiết
						?><link type="text/css" rel="stylesheet" href="/css/thoi_tiet-2014.css?ver=<?php echo JS_CSS_VERSION; ?>" /><?php
						break;
					}
					case 3: { // trang tỷ giá
                        ?><link type="text/css" rel="stylesheet" href="/css/ty_gia_common_pc.css?ver=<?php echo JS_CSS_VERSION; ?>" /><link type="text/css" rel="stylesheet" href="/css/ty_gia_pc.css?ver=<?php echo JS_CSS_VERSION; ?>" /><?php
						break;
					}
					case 4: { // trang truyền hình
						?><link type="text/css" rel="stylesheet" href="/css/truyen_hinh-2014.css?ver=<?php echo JS_CSS_VERSION; ?>" /><?php
						break;
					}
					case 5: { // trang atm
						?><link type="text/css" rel="stylesheet" href="/css/atm-2014.css?ver=<?php echo JS_CSS_VERSION; ?>" /><?php
						break;
					}
				}
			} else {
				//Begin : 29-09-2015 : Thangnb toi_uu_page_speed
                echo str_replace('http:', '', html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global));
                echo str_replace('http:', '', html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global));
				//End : 29-09-2015 : Thangnb toi_uu_page_speed
			}
		?>
		<?php
			// 25/09/2014 HaiLT: fix ga poll
			if ($fwModuleName == 'poll') {
				$v_echo_ga_poll = false;
				if (strpos($fwRequestUri, '/dsp_vote_result')) {
					$v_echo_ga_poll = true;
				}
				if (strpos($fwRequestUri, '/dsp_security_poll')) {
					$v_string_tmp = substr($fwRequestUri, strpos($fwRequestUri, '/dsp_security_poll') + strlen('/dsp_security_poll') + 1);
					$v_arr_tmp = explode('/', $v_string_tmp);
					$v_tmp_poll_id = intval($v_arr_tmp[0]);
					$v_tmp_answer_id = intval($v_arr_tmp[1]);
					$v_tmp_update_success = intval($_POST['update_success']);
					if ($v_tmp_answer_id <= 0 && $v_tmp_update_success == 1) {
						$v_echo_ga_poll = true;
					}
				}
			}
		
    ?></head>	
	<!--end:header-->
    <?php
} else {
	if ($v_is_box_play_video) {
		$v_arr_tmp = explode('box_player_video_by_url/index/', $fwRequestUri);
		$v_cat_id = intval($v_arr_tmp[1]);
		$v_region_id = get_region_id();
		$v_html_cau_hinh_thu_tu_hien_thi_quang_cao_video = fe_html_cau_hinh_thu_tu_hien_thi_quang_cao_video($v_cat_id, 1, $v_region_id);
		?><head>
            <!--begin:header-->
            <head>		
                <title>Tin tức 24h, tin nhanh, bóng đá, thể thao tại 24h <?php echo time().rand(); ?></title>
                <meta name="description" content="Tin tức trong ngày, tin nhanh bóng đá, thể thao cập nhật liên tục 24h trong ngày <?php echo time().rand(); ?>" />
                <meta name="keywords" content="Tin tức, tin tức trong ngày, bóng đá, thể thao, tin nhanh, tin mới" />
                <?php 
					//begin 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
					echo $v_robot;
					//End 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
                    
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
                        // Dữ liệu xb banner riêng
                        $v_xuat_ban_trang_bai_viet = 1;
                        // Trả về html header: 0 trả về script slot dfp/ 1 trả về html header
                        $v_return   = 0;
                        // Là bài viết có banner in-read
                        $v_la_bai_viet_co_banner_in_read_tvc    = false;
                        // Begin Lucnd 23-06-2017: DFP_24h_xu_ly_quang_cao_dfp_in_image
                        $v_la_bai_viet_co_banner_in_image    = false;
                        // End Lucnd 23-06-2017: DFP_24h_xu_ly_quang_cao_dfp_in_image
                        // Mảng dữ liệu slot dfp
                        $v_row_slot_dfp     = array();
                        $v_get_key_value    = 1;
                        // Lấy js slot dfp
                        $v_script_slot_dfp  = _chen_va_lay_js_slot_dfp('', $v_news_id, $v_cat_id, $v_region_value, $v_xuat_ban_trang_bai_viet, $v_la_bai_viet_co_banner_in_read_tvc, $v_la_bai_viet_co_banner_in_image, $v_return, $v_get_key_value, $v_row_slot_dfp,$v_row_news,$v_co_gan_video_tai_tro);
                        if ($v_script_slot_dfp != '') {
                            if (strtolower($v_region_value) == 'us') {
                                echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp_us());
                            } else {
                                echo str_replace('/*@@SCRIPT_SLOT_DFP@@*/', $v_script_slot_dfp, html_header_script_slot_dfp());
                            }
                        }
                    }
				?>
                
			<script type='text/javascript'>
				v_cat_id = <?php echo $v_cat_id; ?>;
			</script>
            <script type="text/javascript" src="<?php echo str_replace('http:', '', BASE_URL_FOR_PUBLIC); ?>js/jquery.min.js?v=<?php echo JS_CSS_VERSION; ?>"></script>
            <?php 
				// Begin anhnt1 08/5/2015: tối ưu pagespeed
                echo html_load_header_css('<!--@css@-->', 0, MINIFY_JS_CSS,'news_092018', '', $v_device_global);
                echo html_load_header_js('<!--@js@-->', 0, MINIFY_JS_CSS,'','',$v_device_global);
				// End anhnt1 08/5/2015: tối ưu pagespeed
                //Begin 21-2-2018 : Thangnb 24h_player_eva
                if (PLAYER_VIDEO == '24H_PLAYER') {
                    $v_css_video = 	'<style>'._read_file(WEB_ROOT.'css/24hplayer.min.css').'</style>';
                    echo $v_css_video;
                }
			?>
			<?php echo $v_html_cau_hinh_thu_tu_hien_thi_quang_cao_video; ?>
            <?php    
            if($v_is_bai_tuong_thuat){
                $html_js_ads    = '<script>'._read_file(WEB_ROOT.'js/ads_common.min.js').'</script>';
                echo $html_js_ads;
                $_js_quang_cao = _chen_js_quang_cao('<!--js_quang_cao-->', $v_cat_id, $v_region_id, 0);
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
	} else { ?>
        <!--begin:header-->
        <head>		
		<title>Tin tức 24h, tin nhanh, bóng đá, thể thao tại 24h <?php echo time().rand(); ?></title>
        <meta name="description" content="Tin tức trong ngày, tin nhanh bóng đá, thể thao cập nhật liên tục 24h trong ngày <?php echo time().rand(); ?>" />
        <meta name="keywords" content="Tin tức, tin tức trong ngày, bóng đá, thể thao, tin nhanh, tin mới" />
		<?php 
            //begin 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
            echo $v_robot;
            //End 14-03-2017 : Thangnb xu_ly_hien_thi_so_luong_poll_khi_vote_xong
        ?>
        </head>
    <?php 
	}
}
?>
<body>
<?php echo $masterContent; ?>
<!--end-key-->

<?php
if ($_REQUEST['BLOCK_VIEW_HEAD_TAG'] == 1) { 
    ?>			
	<!-- Start of the Calender //-->
			<table id="calenderTable">
				<tbody id="calenderTableHead">
					<tr>
						<td colspan="4" align="left"><select onchange="showCalenderBody(createCalender(document.getElementById('selectYear').value, this.selectedIndex, false));" id="selectMonth">
							<option value="0">Tháng 1</option><option value="1">Tháng 2</option><option value="2">Tháng 3</option><option value="3">Tháng 4</option><option value="4">Tháng 5</option><option value="5">Tháng 6</option><option value="6">Tháng 7</option><option value="7">Tháng 8</option><option value="8">Tháng 9</option><option value="9">Tháng 10</option><option value="10">Tháng 11</option><option value="11">Tháng 12</option>
						</select></td>
						<td colspan="2" align="center"><select onchange="showCalenderBody(createCalender(this.value, document.getElementById('selectMonth').selectedIndex, false));" id="selectYear"><option value=''>&nbsp;</option></select></td>
						<td align="right"><a href="#" onclick="closeCalender();return false;" title="&amp;#272;&amp;#243;ng">X</a></td>
					</tr>
				</tbody>
				<tbody id="calenderTableDays">
					<tr style="">
						<td>CN</td><td>T2</td><td>T3</td><td>T4</td><td>T5</td><td>T6</td><td>T7</td>
					</tr>
				</tbody>
				<tbody id="calender"><tr><td></td></tr></tbody>
			</table><!-- End of the Calender //-->
    <?php
}

if($v_is_box_play_video && USE_ZPLAYER && PLAYER_VIDEO == 'ZPLAYER') {
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
?></body>
</html>