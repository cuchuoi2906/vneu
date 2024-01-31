<style>
.atclRdSbIn .nwsTit a{font-size:18px}.mg-footer-inner p,.mg-footer h1{font-size:13px!important;line-height:1.4}
/* Begin: 9-11-2019 bo_sung_link_goc_cho_bai_khai_thac_frontend */
p.linkOrigin, p.linkOrigin * {
  font-size: 13px !important;
  color: #757575;
  margin: 10px 0; }
p.linkOrigin * {
    margin: 0; }
p.linkOrigin .icoDrop {
    display: inline-block;
    background: url('//cdn.24h.com.vn/images/icoDrop.png') no-repeat;
    width: 13px;
    height: 11px;
    vertical-align: middle;
    cursor: pointer;
}
/* End: 9-11-2019 bo_sung_link_goc_cho_bai_khai_thac_frontend */

/* Begin 18/8/2020: minhdt toi_uu_magazine */
.magazine_event_date {
    display: flex;
    align-items: center;
    justify-content: space-between;
    max-width: 1000px;
    margin: 20px auto 5px;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    color: #757575;
}
.magazine_event_date div {
    margin: 0;
}
.magazine_event_date .sbNws {
    font-weight: 600;
}
.magazine_event_date h3{
    font-size: 13px;
}
.magazine_event_date .sbNws a {
    font-weight: 300;
    color: #378B36;
}
.mg-author {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin: 0 auto 20px !important;
}
.mg-author .mg-duroi {
    margin: 0 !important;
    padding: 0;
}
.mg-author div {
    margin: 0;
}
/* Minhdt 04/04/2022 Fix-box-nguon-va-tac-gia-bi-lech-bo-cuc */
.mg-author:not(.mg-show) .container-inner{
    width: 100% !important;
    padding: 0;
}
/* End Minhdt 04/04/2022 Fix-box-nguon-va-tac-gia-bi-lech-bo-cuc */
/* End 18/8/2020: minhdt toi_uu_magazine */

/*== Minhdt 11/03/2022 Add-Chon-Font-Cho-Toan-Bo-Trang ==*/
    /*== Custom Font ==*/
      @font-face {
        font-family: 'Roboto-Regular';
        src: url(https://cdn.24h.com.vn/css/fonts/Roboto-Regular.ttf);
      }
      @font-face {
        font-family: 'Roboto-Bold';
        src: url(https://cdn.24h.com.vn/css/fonts/Roboto-Bold.ttf);
      }
      @font-face {
        font-family: 'Roboto-Italic';
        src: url(https://cdn.24h.com.vn/css/fonts/Roboto-Italic.ttf);
      }
      @font-face {
        font-family: 'NotoSerif-Regular';
        font-style: normal;
        font-weight: 400; /* Font Bold để 700-900 ko dùng Bold */
        src:
             url('https://cdn.24h.com.vn/css/fonts/NotoSerif-Regular.woff') format('woff'),/* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
             url('https://cdn.24h.com.vn/css/fonts/NotoSerif-Regular.ttf') format('truetype'); /* Safari, Android, iOS */
        font-display: swap;
      }
    /*== End Custom Font ==*/
    #class_noto *,
    #class_noto p{
        font-family: 'NotoSerif-Regular' !important;
    }
    #class_roboto *,
    #class_roboto p{
        font-family: 'Roboto-Regular' !important;
    }
    /* Minhdt 24/03/2022 Fix-chu-thich-anh-ko-can-giua */
    #magazine_news .width-750-chu-thich {
        width: 100%;
        overflow: hidden;
    }
    #magazine_news .chu_thich_anh_mg{
        max-width: 800px;
        margin: 15px auto;
    }
    #magazine_news .chu_thich_anh_mg *,
    #magazine_news .chu_thich_anh_mg{
        color: #8d8d8d !important;
        font-size: 16px !important;
        line-height: 28px !important;
        text-align: center !important;
        font-family: Arial, Helvetica, sans-serif;
    }
    /* End Minhdt 24/03/2022 Fix-chu-thich-anh-ko-can-giua */
/*== End Minhdt 11/03/2022 Add-Chon-Font-Cho-Toan-Bo-Trang ==*/
</style>

<?php
if($v_arr_magazine_content['c_font'] != ''){
    echo '<div id="'.$v_arr_magazine_content['c_font'].'">';
}
echo '<div id="magazine_news">';
	// begin 23-8-2018 BangND XLCYCMHENG_25639_xd_giao_dien_FE_magazine
	if (check_array($v_arr_magazine_content)) {
		$row_news['Body'] = html_entity_decode(hien_thi_noi_dung_magazine($row_news['Body'], $v_arr_magazine_content));
	}
	// end 23-8-2018 BangND XLCYCMHENG_25639_xd_giao_dien_FE_magazine
	$row_news = replace_alt_images_magazine($row_news, $row_seo_news, $row_cat, $v_url_news);
    if(strpos($row_news['Body'],'videoUpload24h') !== false){
        preg_match_all("|<video[^>]+>(.*)<\/video>|U",$row_news['Body'],$match);
        if(check_array($match[0])){
            // lấy mảng ảnh đại diện video
            preg_match_all("#<img[^>]+class=\"img_poster_video\"[^>]+src=[\"\']([^\"\']+)[\"\'][^>]*>#ism", $row_news['Body'], $v_arr_img_poster);
            foreach($match[0] as $key=>$video){
                preg_match_all('/src="([^"]*)"/msi', $video,$matchSrc);
                preg_match('/width="([0-9]+)"/msi', $video,$matchWidth);
                preg_match('/height="([0-9]+)"/msi', $video,$matchHeight);
                if(check_array($matchSrc[1])){
                    $v_video_file = implode(',', $matchSrc[1]);
                    $v_arr_configs_bai_viet = _lay_thong_so_cau_hinh_bai_viet($row_news,$row_cat);
                    $v_param_extension_video = $v_arr_configs_bai_viet;
                    // bổ xung thêm các tham số cấu hình video vào mảng theo dữ liệu truyền vào
                    $v_param_extension_video['v_is_trang_bai_viet'] = false;
                    $v_param_extension_video['v_is_box_video_chon_loc'] = true;
                    $v_param_extension_video['v_width_video']   = intval($matchWidth[1]) > 0 ? intval($matchWidth[1]) : 800;
                    $v_param_extension_video['v_height_video']  = intval($matchHeight[1]) > 0 ? intval($matchHeight[1]) : 450;
                    $v_param_extension_video['v_anh_dai_dien_video'] = $row_news['SummaryImg_chu_nhat'];
                    $v_param_extension_video['v_type_video']    = 'flashWrite';
                    $v_param_extension_video['v_type_quang_cao']    = TYPE_ADS_DEFAULT;
                    $v_param_extension_video['v_ga_file']       = LINK_GA_VIDEO_MAC_DINH;
                    $v_file_poster = ($v_arr_img_poster[1][$key] != '') ? $v_arr_img_poster[1][$key] : '';
                    // gắn file poster magazine vào trong mảng bài viết
                    $row_news['v_file_poster_video_magazine'] = $v_file_poster;
                    $v_html_player = _vd_xu_ly_tao_player_theo_url_video($v_video_file,$row_news, $row_cat,$v_param_extension_video);
                    $row_news['Body'] = str_replace($video, $v_html_player, $row_news['Body']);
                }
            }
        }
    }
    // begin 31-03-2022 Quyvd XLCYCMHENG-38869 fix_loi_gan_tracking_CLICK 
    // end 31-03-2022 Quyvd XLCYCMHENG-38869 fix_loi_gan_tracking_CLICK 
    echo fw24h_restore_bad_char($row_news['Body']);
echo '</div>';
if($v_arr_magazine_content['c_font'] != ''){
    echo '</div>';
}
?>
