<?php
/*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/
$upload_video_conf['max_file_upload'] = 5;
/*End 14-06-2014 trungcq tang_so_luong_upload_video*/
// $upload_video_conf['max_video_size'][62] = 15728640;
// $upload_video_conf['max_video_mobile_size'][62] = 20971520;

//phuonghv add 03/10/2015: thêm các biến nhận biết các loại video, giá trị các biến này chính là tên các function javascript xử lý hiển thị video.
$VIDEO_THUONG ='flashWrite';// loại video 24h
$VIDEO_VTV = 'vtvWrite';// loại video vtv
$VIDEO_BALL_BALL_7 = 'ballballWrite7';// loại video ball ball giới bạn 7 ngày
$VIDEO_BALL_BALL = 'ballballWriteAll';// loại video ball ball không giới hạn ngày.
$VIDEO_QUANG_CAO_NEVIA = 'quangcaoWrite';// loại video ball ball không giới hạn ngày.
$VIDEO_QUANG_CAO_HEINEKEN = 'heinekenWrite';// loại video quảng cáo heineken.
/* begin 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
$VIDEO_EMOBI = 'emobi_write';// loại video vtv 
/* end 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */

$upload_video_conf['loai_video_mac_dinh_duoc_chon'] = $VIDEO_THUONG;
$v_arr_loai_video = array();
$v_arr_loai_video[] = array('c_code'=>$VIDEO_THUONG, 'c_name'=>'Video thường', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_THUONG.'("/images/24hvideo_player.swf?file={video_url}",418,314);</script>');
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_VTV,'c_name'=>'Video VTV', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_VTV.'("{video_url}");</script>');
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_QUANG_CAO_NEVIA,'c_name'=>'Video quảng cáo nivea', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_QUANG_CAO_NEVIA.'("/images/24hvideo_player.swf?file={video_url}",418,314);</script>');
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_QUANG_CAO_HEINEKEN,'c_name'=>'Video quảng cáo heineken', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_QUANG_CAO_HEINEKEN.'("/images/24hvideo_player.swf?file={video_url}",418,314);</script>');
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_BALL_BALL_7,'c_name'=>'Video Ballball 7 ngày', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_BALL_BALL_7.'("{video_url}");</script>');
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_BALL_BALL,'c_name'=>'Video Ballball không giới hạn ngày', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_BALL_BALL.'("{video_url}");</script>');
/* begin 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
//$v_arr_loai_video[] = array('c_code'=>$VIDEO_EMOBI,'c_name'=>'Video EMOBI', 'c_template_video_code'=>'<script type="text/javascript">'.$VIDEO_EMOBI.'("{video_url}");</script>');
/* end 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */

//phuonghv add 06/10/2015 thêm các cấu hình cho việc upload video làm banner
//Begin 08-06-2016 : Thangnb xu_ly_thay_the_domain_static
$upload_video_conf['domain_video'] = 'http://video.24h.com.vn';
//Begin 08-06-2016 : Thangnb xu_ly_thay_the_domain_static
$upload_video_conf['max_video_size_for_banner'] =20*1024*1024; // giới hạn dung lượng video tối đa được phép upload là 20M
$upload_video_conf['extension_video_for_banner'] = 'mp4,flv,3gp';
$upload_video_conf['upload_uutien'] = ROOT_FOLDER.'upload_uutien/';
/* Begin anhpt1 24/5/2016 chuc_nang_upload_video */
$v_kich_thuoc_anh_dai_dien_video = '660x370'; /* edit: Tytv - 10/05/2017 - toi_uu_kich_thuoc_video_upload */
$v_duoi_anh_dai_dien_video = '.jpg';
/* End anhpt1 24/5/2016 chuc_nang_upload_video */
$v_arr_loai_giai_dau_nivea = array(
    0=>array('c_code' => 'cd_nivea_nha','c_name' => 'Giải ngoại hạng anh'),
    1=>array('c_code' => 'cd_nivea_c1','c_name' => 'Giải khác có real'),
    2=>array('c_code' => 'cd_nivea_tbn','c_name' => 'Giải LALIGA có real'),
    3=>array('c_code' => 'cd_nivea_affcup','c_name' => 'Giải Seagame'),
    //4=>array('c_code' => 'cd_nivea_fifa','c_name' => 'Giải FIFA club world cup'),
);