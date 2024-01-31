<?php
// thử nghiệm với domain us: dùng adsense
//$_SERVER['SERVER_REGION']="US";

// File cấu hình các thông số liên quan đến quảng cáo video với adsense, dfp

/* Cấu hình chế độ video player 
 * 1: Sử dụng JW Player: nếu có quảng cáo video bằng adsense, dfp thì dùng Jwplayer6, ngược lại thì dùng JWPlayer5
 * 2: Sử dụng FlowPlayer
 * 3: Chế độ linh hoạt - nếu có quảng cáo video bằng adsense, dfp thì dùng FlowPlayer, ngược lại thì dùng JWPlayer5
 * 4: Sử dụng zplayer
 * #: Sử dụng JW Player: nếu có quảng cáo video bằng adsense, dfp thì dùng Jwplayer6, ngược lại thì dùng JWPlayer5
 */
$v_cau_hinh = 4;
if ($_GET['cau_hinh_flowplayer'] && in_array($_GET['cau_hinh_flowplayer'], array('1','2','3'))) {
	$v_cau_hinh = $_GET['cau_hinh_flowplayer'];
}

define('CAU_HINH_VIDEO_PLAYER', $v_cau_hinh); 

/* Cấu hình vùng miền sử dụng với chế độ 2&3 (có thể để nhiều vùng miền cách nhau bởi dấu ,)
 * US: vùng miền nước ngoài 
 * HN: vùng miền Hà nội
 * HCM: vùng miền Hồ Chí Minh 
 */
define('VUNG_MIEN_SU_DUNG_FLOWPLAYER', 'HCM,HN'); 

// thoi gian bat dau hien thi quang cao overlay
define('TIME_START_OVERLAY', 10);
// thoi gian ket thuc hien thi quang cao overlay
define('TIME_END_OVERLAY', 15);
// thoi gian ket thuc hien thi quang cao pre roll
define('TIME_END_PRE_ROLL', 31);
// license key flowplayer
define('LICENSE_KEY_FLOWPLAYER', '#$941a8bea44da05b951f');
// chieu rong flowplayer
define('WIDTH_FLOWPLAYER', 519);
define('HEIGHT_FLOWPLAYER', 292);
// Đường dẫn tới file min.js
define('FLOWPLAYER_MIN_JS_URL', '/js/flowplayer/flowplayer-3.2.13.min.js');
// Đường dẫn tới file giao diện
define('FLOWPLAYER_SWF', '/js/flowplayer/flowplayer.commercial-3.2.18.swf');
// Đường dẫn tới file giao diện controls
define('FLOWPLAYER_CONTROLS_SWF', '/js/flowplayer/flowplayer.controls-3.2.16.swf');
// Đường dẫn tới file plug-in dfp
define('FLOWPLAYER_DFP_SWF', '/js/flowplayer/bigsool.dfp-24h.com.vn-2.9.swf');

// Flash video ngầm định
define('FLASH_VIDEO_DEFAULT', '/js/preview.gif');

// Icon play
define('ICON_PLAY', '/images/iconPlay.png');
// Icon play chiều rộng
define('ICON_PLAY_WIDTH', 60);
// Icon play chiều cao
define('ICON_PLAY_CAO', 60);

// autoPlay
define('AUTOPLAY', true);
// autobuffering
define('AUTOBUFFERING', true);
// autoHide
define('AUTOHIDE', 'never');
// buttonColor
define('BUTTONCOLOR', '#D8D8D8');
// buttonColor
define('BUTTONCOLOR', '#D8D8D8');
// buttonOverColor
define('BUTTONOVERCOLOR', '#000000');
// backgroundColor
define('BACKGROUNDCOLOR', '#000000');
// backgroundGradient
define('BACKGROUNDGRADIENT', '#medium');						
// sliderColor
define('SLIDERCOLOR', '#6E6E6E');						
// sliderBorder
define('SLIDERBORDER', '1px solid #808080');						
// volumeSliderColor
define('VOLUMESLIDERCOLOR', '#6E6E6E');						
// volumeBorder
define('VOLUMEBORDER', '1px solid #808080');						
// timeColor
define('TIMECOLOR', '#A4A4A4');						
// durationColor
define('DURATIONCOLOR', '#6E6E6E');						

// Mang dau hieu nhan biet quang cao adsense & dfp
$arr_dau_hieu_qc_adsense_dfp = array('googleads', 'doubleclick', 'pubads.g');
								
if (count($arr_dau_hieu_qc_adsense_dfp) > 0) {
	define('ARR_DAU_HIEU_QC_ADSENSE_DFP', serialize($arr_dau_hieu_qc_adsense_dfp));	
}

// license key flowplayer
define('LICENSE_KEY_JWPLAYER6', 'h9Hk94XPyRVLvp3o2cVctXdh/OSReDO/NMi60A==');
// Đường dẫn tới file js player
define('JWPLAYER6_JS_URL', '/js/jwplayer6_12/jwplayer.js?v=20150307');
define('IPAD_JWPLAYER6_JS_URL', '/jwplayer6_12/jwplayer.js?v=20150313');
// Ten skin
define('JWPLAYER6_SKIN', 'glow');
// chieu rong jwplayer6
define('WIDTH_JWPLAYER6', 519);
define('HEIGHT_JWPLAYER6', 292);

// Xác định xem có tham chiếu đến thư viện javascript của player cho mỗi lời gọi Player hay ko
define('USE_JWPLAYER_JS_WHEN_RUN', true);

// Xác định xem có gắn mã GA vào video chân bài viết không
define('GAN_GA_VAO_VIDEO_CHAN_BAI_VIET', true);

// 20/10/2014 HaiLT: xác định có thực hiện tự động off quảng cáo prerol không? số giây sẽ tự off
define('MAX_TIME_ADS_PREROLL', 0); // để <=0 để chạy ads preroll bình thường
define('DEFAULT_VIDEO_PLAYER_ADS_PREROLL', DEFAULT_IMAGE_VIDEO.'/upload/4-2014/videoclip/2014-10-21/ads_video.mp4'); // Đường dẫn file video chạy trên player chạy quảng cáo preroll trước khi bị tắt


// xác định xem có sử dụng zplayer
define('USE_ZPLAYER', true);
// đường dẫn tới file js zplayer
if (isset($_SERVER['SERVER_REGION']) && strtoupper($_SERVER['SERVER_REGION']) == 'US') {
    define('ZPLAYER_JS_URL', DEFAULT_IMAGE_STATIC.'zplayer/zPlayer.js?ver='.JS_CSS_VERSION);
    /* Begin 17-02-2017 : Thangnb co_che_dem_nguoc_video */
    define('ZPLAYER_LEGACY_JS_URL', DEFAULT_IMAGE_STATIC.'zplayer/legacy.min.js?ver='.JS_CSS_VERSION);
    /* End 17-02-2017 : Thangnb co_che_dem_nguoc_video */
    // đường dẫn tới file js zplayer html
    define('ZPLAYER_HTML5_POLYFILL_JS_URL', DEFAULT_IMAGE_STATIC.'zplayer/polyfill.min.js?ver='.JS_CSS_VERSION);
    define('ZPLAYER_HTML5_JS_URL', DEFAULT_IMAGE_STATIC.'zplayer/zPlayerHtml5.min.js?ver='.JS_CSS_VERSION);
}else{
    define('ZPLAYER_JS_URL', '/zplayer/zPlayer.js?ver='.JS_CSS_VERSION);
    /* Begin 17-02-2017 : Thangnb co_che_dem_nguoc_video */
    define('ZPLAYER_LEGACY_JS_URL', '/zplayer/legacy.min.js?ver='.JS_CSS_VERSION);
    /* End 17-02-2017 : Thangnb co_che_dem_nguoc_video */
    // đường dẫn tới file js zplayer html
    define('ZPLAYER_HTML5_POLYFILL_JS_URL', '/zplayer/polyfill.min.js?ver='.JS_CSS_VERSION);
    define('ZPLAYER_HTML5_JS_URL', '/zplayer/zPlayerHtml5.min.js?ver='.JS_CSS_VERSION);
}
// define('ZPLAYER_JS_URL', 'https://player.24h.com.vn/zplayer/zPlayer.js?v=2016092114');
// xác định sử dụng auto play
define('USE_AUTO_PLAY', false);
// xác định sử dụng quảng cáo pre_roll
define('USE_PRE_ROLL', true);
// xác định quảng cáo overlay
define('USE_OVERLAY', true);
// xác định quảng cáo post roll
define('USE_POST_ROLL', true);
define('POST_ROLL_ONLY', '');
define('TIME_END_POST_ROLL', 30);
// Hiện thị skip button
define('USE_SKIP_AD', true);
// xác định xử dụng logo
define('USE_LOGO_ON_VIDEO', false);
// đường dẫn logo xử dụng cho zplayer
define('LOGO_ON_VIDEO', IMAGE_STATIC.'images/2014/blank.png');
// vị trí logo trên zplayer
define('LOGO_POSITION','top-right'); //-- top-right : Trên & phải- bottom-right : Dưới & phải- top-left : Trên & trái- bottom-left : Dưới & trái
define('PLAYER_SOURCE', '/zplayer/');
define('TIME_SKIP_BUTTON', 5);
define('LOGO_URL', BASE_URL_FOR_PUBLIC);
define('SKIP_BUTTON_AD', 'PREROLL,POSTROLL');/* add: Tytv - 23/05/2017 - chuyen_doi_video_flash_sang_html5 */
// bg hiển thị trươc và sau khi play
define('PLAYER_BG_IMAGE', '/js/preview.gif');
// ẩn hiện thanh điều khiển trên player
define('HIDE_EXTRA_BUTTON', true);
define('ZPLAYER_WIDTH_OUTSIDE_OF_NEW', '480');
define('ZPLAYER_HEIGHT_OUTSIDE_OF_NEW', '340');
// Chiều rộng, chiều cao của zplayer
/* Begin: Tytv - 10/05/2017 - toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
define('WIDTH_ZPLAYER', 660);
define('HEIGHT_ZPLAYER', 446);
/* End: Tytv - 10/05/2017 - toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
define('SSL_DOMAIN_ZPLAYER_SWF', BASE_URL_FOR_PUBLIC.'zplayer/zPlayerView.swf?ver='.JS_CSS_VERSION);
/* Begin 12-07-2017 : Tytv thu_nghiem_autoplay_video_1_so_chuyen_muc */
define('VOLUME_VIDEO', 40);
define('AD_VOLUME_VIDEO', 40);
/* Begin 12-07-2017 : Tytv thu_nghiem_autoplay_video_1_so_chuyen_muc */

// 2016/03/17 ducnq Begin bo_dau_hieu_nhan_biet_delivery
//$arr_dau_hieu_nhan_biet_quang_cao_ambient = array('ambient','delivery');
$arr_dau_hieu_nhan_biet_quang_cao_ambient = array('ambient');
// 2016/03/17 ducnq End bo_dau_hieu_nhan_biet_delivery
if (count($arr_dau_hieu_nhan_biet_quang_cao_ambient) > 0) {
	define('ARR_DAU_HIEU_NHAN_BIET_QUANG_CAO_AMBIENT', serialize($arr_dau_hieu_nhan_biet_quang_cao_ambient));
}
//14-05-2015: Thangnb add
//define('LINK_GA_VIDEO_MAC_DINH','/ajax/video_player_ga.php');
define('LINK_GA_VIDEO_MAC_DINH',''); // 28/12 off mã ga playe



# 22/12/2014: HaiLT tham số set iframe cho video player của ipad
define('IFRAME_VIDEO_PLAYER_IPAD', false);
// xác định xem có sử dụng zplayer cho ipad không
define('USE_ZPLAYER_IPAD', false);
// đường dẫn tới file js zplayer cho ipad
//28052015:Thangnb bo_sung_zplayer
define('ZPLAYER_JS_URL_IPAD', BASE_URL_FOR_PUBLIC.'zplayer/zPlayer.js?ver='.JS_CSS_VERSION);
//16-05-2015: Thangnb bo sung 
// ten key chua quang cao video
define('TEN_KEY_QUANG_CAO_VIDEO', 'video_ads_201403_');
/* Begin Lucnd 31-03-2017: DFP_24h_xu_ly_quang_cao_dfp */
//define('TEN_KEY_QUANG_CAO_VIDEO_CHON_LOC', 'video_chon_loc_ads_201409_');
define('TEN_KEY_QUANG_CAO_VIDEO_CHON_LOC', 'video_ads_201403_');
/* End Lucnd 31-03-2017: DFP_24h_xu_ly_quang_cao_dfp */
//Begin : 05-10-2015 Thangnb xu ly zplayer_ball_ball
define('MA_DOI_TAC_BALLBALL',4);
//End : 05-10-2015 Thangnb xu ly zplayer_ball_ball

//Begin : 09-10-2015 ducnq thời gian giới hạn hiển thị video ball ball 
define('TIMEOUT_VIDEO_BALLBALL',"-1 week"); // 1 tuần (test để tạm 1 giờ)

//Begin : 21-01-2016: Thangnb xu_ly_nguon_qc_video_ants
define('ANTS_SOURCE_VIDEO_24H',"24h"); 
define('ANTS_SOURCE_VIDEO_BALLBALL',"ballball"); 
//End : 21-01-2016: Thangnb xu_ly_nguon_qc_video_ants

//Begin : 09-10-2015 ducnq cấu hình source_id video ball ball (cấu hình cho quảng cáo của ANTS)
define('HTML_LOGO_BALLBALL','<div class="video-ballball"><a href="http://www.ballball.com/vi-vn" target="_blank"><img src="{{LOGO_BALLBALL}}" style="max-width:519px;width:519px" /></a></div>'); 
define('LOGO_BALLBALL','images/logo-ballball.jpg'); 

/* Begin: Tytv - 26/09/2017 - toi_uu_code_xu_ly_video */
define('SOURCE_VIDEO_24H',"24H"); 
define('ID_PLAYER_VIDEO','{zplayer-id}');
define('WIDTH_ZPLAYER_BOX_VIDEO_CHON_LOC',400);  // chiều rộng khung video box video chon loc
define('HEIGHT_ZPLAYER_BOX_VIDEO_CHON_LOC',300); // chiều cao khung video box video chon loc
define('TYPE_ADS_DEFAULT', 9999);

// thông số cho AD_SKIP
define('AD_SKIP_TEXT', 'Bỏ qua quảng cáo'); //ad_skipText = nội dung nút skip (default Bỏ qua)
define('AD_SKIP_WIDTH', 140);  // độ dài nút skip (default 150)
define('AD_SKIP_HEIGHT', 30); // độ cao nút skip (default 30)
define('AD_SKIP_TOP', 392); //vị trí TOP Y nút skip (default 10)
define('AD_SKIP_RIGHT', 360); //vị trí RIGHT X nút skip (default 70)
// thông số cho AD_CLOCK
define('AD_CLOCK_TEXT', 'Quảng cáo '); // (default Quảng cáo)
define('AD_CLOCK_LEFT', -5); // (default 8)
define('AD_CLOCK_BOTTOM', 25); // (default 40)
define('AD_CLOCK_WIDTH', 115); // (default 115)
define('AD_CLOCK_HEIGHT', 20); // (default 20)
// thông số cho AD_MUTE
define('AD_MUTE_WIDTH', 40); // (default 40)
define('AD_MUTE_HEIGHT', 30); // (default 30)
define('AD_MUTE_TOP', 392); // (default 10)
define('AD_MUTE_RIGHT', 510); // (default 20)

// THÔNG SỐ VIDEO CHỌN LỌC
define('AD_SKIP_TEXT_VIDEO_CHON_LOC', 'Bỏ qua '); //ad_skipText = nội dung nút skip (default Bỏ qua)
define('AD_SKIP_WIDTH_VIDEO_CHON_LOC', 60);  // độ dài nút skip (default 150)
define('AD_SKIP_HEIGHT_VIDEO_CHON_LOC', 30); // độ cao nút skip (default 30)
define('AD_SKIP_TOP_VIDEO_CHON_LOC', 260); //vị trí TOP Y nút skip (default 10)
define('AD_SKIP_RIGHT_VIDEO_CHON_LOC', 205); //vị trí RIGHT X nút skip (default 70)
// thông số cho AD_CLOCK
define('AD_CLOCK_TEXT_VIDEO_CHON_LOC', 'Ad '); // (default Quảng cáo)
define('AD_CLOCK_LEFT_VIDEO_CHON_LOC', -5); // (default 8)
define('AD_CLOCK_BOTTOM_VIDEO_CHON_LOC', 10); // (default 40)
define('AD_CLOCK_WIDTH_VIDEO_CHON_LOC', 65); // (default 115)
define('AD_CLOCK_HEIGHT_VIDEO_CHON_LOC', 20); // (default 20)
// thông số cho AD_MUTE
define('AD_MUTE_WIDTH_VIDEO_CHON_LOC', 40); // (default 40)
define('AD_MUTE_HEIGHT_VIDEO_CHON_LOC', 30); // (default 30)
define('AD_MUTE_TOP_VIDEO_CHON_LOC', 260); // (default 10)
define('AD_MUTE_RIGHT_VIDEO_CHON_LOC', 155); // (default 20)
define('ON_OFF_MUTE', 0); // 0: mặc định off tắt tiếng, 1: cấu hình luôn luôn bật tiếng
define('ON_OFF_VOLUMN_M_TO_N', false); // false: mặc định off volumn từ m đến n, true: bật chế độ xử lý volumn
//Begin 21-2-2018 : Thangnb 24h_player
define('SKIP_TEXT', 'Skip Ad');
define('SHOW_AD_VOLUME', true);
define('AD_VOLUME_INCREASE', '0.4');
define('TEXT_REPLAY_VIDEO', 'Xem lại');
define('MID_TIME_ADS', 10);
define('VIDEO_VOL_24H', '0.4');
define('ADS_LOAD_TIME_OUT', 8000); 
define('SKIP_BUTTON_POSITION', 'bottom-left');
define('USE_TOOLTIP_HIGHLIGHT', false); // False: sử dụng tool mặc định trìn duyệt, True: sử dụng HTML thiết kế
//End 21-2-2018 : Thangnb 24h_player
define('ANH_DAI_DIEN_FIFA_3S', '//static.24h.com.vn/images/fifa-3s.gif?v=2018');
define('ON_OFF_LAY_ANH_DAI_DIEN_FIFA', false); // true: Lấy ảnh dại diện fifa, False: lấy theo cơ chế cắt ảnh trong OCM
define('USE_NGINX_M3U8', true); // true: Có sử dụng thì file video wc2018 có bản quyền sẽ được đổi phần mở rộng từ mp4 thành m3u8
define('USE_WOWZA_M3U8', false); // true: Có sử dụng thì file video wc2018 có bản quyền sẽ được đổi domain từ video.24h.com.vn thành video-hls.24h.com.vn
define('DOMAIN_VIDEO_HLS_2018', 'https://video-hls.24h.com.vn/'); // Cấu hình domain hls nếu bật chế độ sử dụng wowza
define('LINK_VIDEO_HLS_2018',DOMAIN_VIDEO_HLS_2018.'24h/mp4:_definst_/{link-video}/playlist.m3u8');
define('USE_ON_OFF_FALLBACK', false); // True: bật cấu hình fall back lại khi link m3u8 lỗi
define('KEY_SYSTEMS_HLS_DRM', "
    'com.apple.fps.1_0': {
        certificateUri: '<CERTIFICATE URI>',
        licenseUri: '<LICENSE URI>'
    }"
); // Chuỗi key hls drm cấu hình
define('KEY_SYSTEMS_DASH_DRM', "
    {
        'name': 'com.widevine.alpha',
        'options': {
          'serverURL': 'https://license.pallycon.com/ri/licenseManager.do',
          'httpRequestHeaders': {
            'pallycon-customdata-v2': 'eyJkYXRhIjoicVYreG9DV3d2Q0lvaTZJQi9TK3JHY29TdElLbzBDOFg4ZHEyZ1EvZFZZcEpHc01ZMzdYNnRSalJSZzcyRFNWayIsInNpdGVfaWQiOiJZUUI1IiwiZHJtX3R5cGUiOiJXaWRldmluZSJ9',
          }
        }
    },
    {
        'name': 'com.microsoft.playready',
        'options': {
            'serverURL': 'https://license.pallycon.com/ri/licenseManager.do',
            'httpRequestHeaders': {
                'pallycon-customdata-v2': 'eyJkYXRhIjoicVYreG9DV3d2Q0lvaTZJQi9TK3JHY29TdElLbzBDOFg4ZHEyZ1EvZFZZcEpHc01ZMzdYNnRSalJSZzcyRFNWayIsInNpdGVfaWQiOiJZUUI1IiwiZHJtX3R5cGUiOiJQbGF5UmVhZHkifQ==',
            }
        }
    }
");
define('PERCENTAGE_SHOW_MINI_PLAYER', 20); // thu nhỏ video theo % che player .vd offsetWatchPercent: 10 => che 10% thì thu nhỏ
define('ON_OFF_GHI_LOG_ERR_VIDEO_GA', 1); // cấu hình có ghi log lỗi video vào GA không , True; Có script ghi log, 0: không ghi
define('LINK_GA_ERR_VIDEO_MAC_DINH','/ajax/ga_tong_tai_nguyen_loi_load_video.php');
define('ON_OFF_GHI_LOG_MEDIA_SLOW', 1); // cấu hình có ghi log ghi nhận số lượt video chậm
define('SECOND_TO_LOAD_MEDIA',3000);
define('SECOND_TO_LOAD_META',3000);
define('LINK_GA_MEDIA_SLOW_VIDEO_MAC_DINH','/ajax/ga_tong_tai_nguyen_loi_media_slow.php');
define('LINK_GA_METRIC_DURATION_VIEWVD','/ajax/ga_tong_tai_nguyen_video_viewership.php');
define('ON_OFF_GHI_LOG_VIEWERSHIP', true); // cấu hình có ghi log ghi nhận số lượt video chậm
define('CONFIG_SCRIPT_HLS', 
        '"hlsTempUrl": "'.BASE_URL_FOR_PUBLIC.'",
        "isHls": true,
        /*random-hls-prefix*/
        "cdnDomain":"<!--folder_video_hls-->",'); // cấu hình script video hls
define('CONFIG_SCRIPT_HLS_PLAYLIST', 
        'hlsTempUrl: "'.BASE_URL_FOR_PUBLIC.'",
        /*random-hls-prefix*/
        isHls: true,
        cdnDomain:"<!--folder_video_hls-->",'); // cấu hình script video hls playlist
define('ON_OFF_CONFIG_SCRIPT_HLS', true); // Cấu hình on/off script hls
define('SECOND_TO_FASTSEEK', 5); // Cấu hình on/off script hls
define('VIDEO_HEIGHT_THEATER_MOD', 580); // Cấu hình on/off script hls
define('ON_OFF_VOLINCREASEMENT', true); // Cấu hình on/off script hls
define('WIDTH_ZPLAYER_2021', 730);
define('HEIGHT_ZPLAYER_2021', 429);
define('WIDTH_ZPLAYER_LIVE_2021', 653);								  
define('LINK_GA_SLOW_PLAYLIST_NEXT_VIDEO','/ajax/ga_tong_tai_nguyen_video_slow_playlist_next.php');
define('LINK_GA_CLICK_PREROLL','/ajax/ga_tong_tai_nguyen_click_preroll.php');
define('ID_BAI_HIEN_THI_ANH_DAI_DIEN_TU_DONG', 1312748); // Các ID > ID cấu hình sẽ hiển thị ảnh tự động																																						