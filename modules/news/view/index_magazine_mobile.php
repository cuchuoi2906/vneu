<style>
.mg-footer-inner p{font-size:14px}.atclRdSbIn .nwsTit .bld{font-family:Arial,Helvetica,sans-serif}
/* Begin: 9-11-2019 bo_sung_link_goc_cho_bai_khai_thac_frontend */
p.linkOrigin, p.linkOrigin * {
    font-size: 14px;
    color: #757575;
    margin: 10px;
}
p.linkOrigin * {
    margin: 0;
}
p.linkOrigin .icoDrop {
  display: inline-block;
  background: url("//cdn.24h.com.vn/images/icoDrop.png") no-repeat;
  width: 13px;
  height: 11px;
  vertical-align: middle;
  cursor: pointer;
}
@media screen and (max-width: 320px) {
    p.linkOrigin, p.linkOrigin * {
        font-size: 11px;
    }
}
@media screen and (min-width: 414px) {
    p.linkOrigin, p.linkOrigin * {
        font-size: 15px;
    }
}
@media screen and (min-width: 480px) {
    p.linkOrigin, p.linkOrigin * {
        font-size: 13px;
    }
}
@media screen and (min-width: 640px) {
    p.linkOrigin, p.linkOrigin * {
        font-size: 15px;
    }
}
@media screen and (min-width: 736px) {
    p.linkOrigin, p.linkOrigin * {
        font-size: 17px;
}   }
/* End: 9-11-2019 bo_sung_link_goc_cho_bai_khai_thac_frontend */

/* Begin 19/8/2020: minhdt toi_uu_magazine */
.magazine_event_date {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 13px;
    color: #757575;
    background: #F7F7F7;
    border-radius: 10px;
    padding: 10px 12px;
    margin: 20px 10px;
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
.magazine_event_date a {
    font-weight: 300;
    color: #378B36;
}
.magazine_event_date .eventList {
    display: inline-block;
}
.magazine_event_date .tmPst {
    margin: 8px 0 0;
}
@media screen and (max-width: 736px) {
    .magazine_event_date,.magazine_event_date h3{
        font-size: 17px;
    }
}
@media screen and (max-width: 640px) {
    .magazine_event_date,.magazine_event_date h3{
        font-size: 15px;
    }
}
@media screen and (max-width: 568px){
    .magazine_event_date,.magazine_event_date h3{
        font-size: 13px;
    }
}
@media screen and (max-width: 414px) {
    .magazine_event_date,.magazine_event_date h3{
        font-size: 15px;
    }
}
@media screen and (max-width: 375px) {
    .magazine_event_date,.magazine_event_date h3{
        font-size: 13px;
    }
}
@media screen and (max-width: 320px) {
    .magazine_event_date,.magazine_event_date h3{
        font-size: 11px;
    }
}
/* End 19/8/2020: minhdt toi_uu_magazine */

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
             url('https://cdn.24h.com.vn/css/fonts/NotoSerif-Regular.woff') format('woff'),
             url('https://cdn.24h.com.vn/css/fonts/NotoSerif-Regular.ttf') format('truetype');
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
    #magazine_news .chu_thich_anh_mg{
        text-align: center !important;
        color: #8d8d8d !important;
        font-size: 15px !important;
        line-height: 26px !important;
    }
/*== End Minhdt 11/03/2022 Add-Chon-Font-Cho-Toan-Bo-Trang ==*/
</style>
<script>
function show_hide_url_origin(){
    if(document.getElementById('url_origin_cut')){
        document.getElementById('url_origin_cut').style.display = 'none';
    }
    if(document.getElementById('url_origin_full')){
        document.getElementById('url_origin_full').style.display = 'block';
    }
    if(document.getElementById('icoDrop')){
        document.getElementById('icoDrop').style.display = 'none';
    }
}
</script>

<?php
if($v_arr_magazine_content['c_font'] != ''){
    echo '<div id="'.$v_arr_magazine_content['c_font'].'">';
}
echo '<div id="magazine_news">';
// begin 23-8-2018 BangND XLCYCMHENG_25639_xd_giao_dien_FE_magazine
if (check_array($v_arr_magazine_content)) {
	$row_news['Body'] = html_entity_decode(hien_thi_noi_dung_magazine($row_news['Body'], $v_arr_magazine_content));
	$row_news = replace_alt_images_magazine($row_news, $row_seo_news, $row_cat, $v_url_news);
}
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
// end 23-8-2018 BangND XLCYCMHENG_25639_xd_giao_dien_FE_magazine
echo fw24h_restore_bad_char($row_news['Body']);
echo '</div>';
if($v_arr_magazine_content['c_font'] != ''){
    echo '</div>';
}
?>
<style>
.heading-site a img{
	width: 168px;
    height: 30px;
	position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -15px;
    margin-left: -84px;
}
</style>