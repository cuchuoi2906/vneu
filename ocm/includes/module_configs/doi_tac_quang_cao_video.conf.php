<?php 
	// Thangnb video_kieu_moi_doi_tac_21_05_2015
	$danh_sach_doi_tac = array(
		array('c_code' => 1, 'c_name' => 'ANTV', 'c_dau_hieu_nhan_biet' => 'antvWrite'),
		// 21-05-2015 : Thangnb them doi_tac_vtv
		array('c_code' => 3, 'c_name' => 'VTV', 'c_dau_hieu_nhan_biet' => 'vtvWrite'),
        // 03-10-2015 : phuonghv thêm đối tác ballball
		array('c_code' => 4, 'c_name' => 'ballball', 'c_dau_hieu_nhan_biet' => 'ballballWrite'),
        // anhpt1 thêm cấu hình video giành riêng cho quảng cáo
        array('c_code' => 5, 'c_name' => 'quangcao-nivea', 'c_dau_hieu_nhan_biet' => 'quangcaoWrite'),
        // chiến dịch quảng cáo heineken
        array('c_code' => 6, 'c_name' => 'quangcao-heineken', 'c_dau_hieu_nhan_biet' => 'heinekenWrite'),
        /* begin 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
        array('c_code' => 7, 'c_name' => 'EMOBI', 'c_dau_hieu_nhan_biet' => 'emobi_write')
        /* end 18/9/2017 TuyenNT xu_ly_ocm_ho_tro_video_emobi */
	);
    /* Begin anhpt1 26/09/2016  quang_cao_chien_dich_heniken */
    $v_arr_ghi_chu_quang_cao_theo_loai = array(
        array('c_code'=> 0,'c_name'=>'--Chọn đối tac quảng cáo--'),
        array('c_code'=> 1,'c_name'=>'Quảng cáo NIVEA','c_dau_hieu' =>'quangcaoWrite','c_ghi_chu'=>'Bài video highlight bàn thắng của giải Ngoại hạng anh, Toàn bộ bài video có liên quan đội Real Madrid Ngoài giải LALIGA, Toàn bộ bài video có liên quan đội Real Madrid trong cúp LALIGA, Toàn bộ bài video có liên quan giải seagame, Toàn bộ bài video có liên quan giải FIFA club world cup','c_dau_hieu_bai_tuong_thuat' =>'quang_cao_Write'),
        array('c_code'=> 2,'c_name'=>'Quảng cáo HEINEKEN','c_dau_hieu'=>'heinekenWrite','c_ghi_chu'=>'Bài video của giải C1 loại trừ các trận liên quan real','c_dau_hieu_bai_tuong_thuat' =>'heineken_Write'),
    );
    /* End anhpt1 26/09/2016  quang_cao_chien_dich_heniken */
?>