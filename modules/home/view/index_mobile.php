<?php 
    echo _hien_thi_quang_cao_tren_trang('ADS_160_15s','bnr clF','',false);
?>
<main>
    <?php
    //30-06-2015 : Thangnb xu ly quang_cao_us
    $region_id = get_region_id();
    $region_value = get_region_value($region_id);
    //End
    $v_is_smart_phone = _get_mobile_type();
   
    // begin 11/08/2016 TuyenNT off_box_breaking_news_mobile_24h
    $v_off_box_breaking_news = _get_module_config('cau_hinh_dung_chung', 'v_on_off_box_breaking_news_mobile_24h');
    if($v_off_box_breaking_news){
        $object = new box_breaking_news_block();
        $object->index(1); // 1: box breaking news
    }

    //31-07-2015: Thangnb bổ sung box tin tổng hợp và đổi vị trí box bóng đá,tin tức
    $object = new box_tin_tong_hop_dau_trang_chu_block();
    //15-09-2015 : Thangnb bo sung
    $object->index(6,SO_LUONG_BAI_VIET_BOX_TIN_TONG_HOP_DAU_TRANG_CHU,$v_is_smart_phone);
    // Hiển thị quảng cáo
    echo _hien_thi_quang_cao_tren_trang('ADS_162_15s','bnr clF',true);
    // box slide menu trang chủ
    $v_key = "mobile_box_slide_menu_trang_chu_092018_".ID_TRANG_CHU."_1_12";
    $v_html_value  = Gnud_Db_read_get_key($v_key, _CACHE_TABLE_BOX_BAI_VIET);
    if ($v_html_value != ""){	
        echo $v_html_value;
    }elseif(_is_test_domain()){
        $object = new box_menu_theo_loai_block();
        $object->setParam('v_device_global', $v_device_global);
        $object->index(1,ID_TRANG_CHU,12);
    }
    // Box tin tức bóng đá
    $object = new box_tin_tuc_trong_ngay_bong_da_the_thao_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index(BOX_DAC_BIET_TIN_BONG_DA);
    
    // Box tin tức trong ngày
    $object->index(BOX_DAC_BIET_TIN_TUC_TRONG_NGAY);	

     /*Begin 14-03-2019 trungcq XLCYCMHENG_34322_code_trang_thong_tin_can_biet*/
    $v_key_thoi_tiet = hien_thi_box_ttcb_theo_chuyen_muc(ID_TRANG_CHU, 1);
    if($v_key_thoi_tiet !=''){
        echo $v_key_thoi_tiet;
    }
    /*End 14-03-2019 trungcq XLCYCMHENG_34322_code_trang_thong_tin_can_biet*/
    
    $object = new box_bai_viet_trang_chu_block();
    $object->setParam('v_device_global', $v_device_global);
    // Box Thế giới
    $object->index(3);
    // begin 12/11/2016 TuyenNT bo_sung_vi_tri_quang_cao_in_feed_native_ads
    
    // hiển thị html banner infeed theo cấu hình
    hien_thi_html_vi_tri_banner_infeed(ID_TRANG_CHU);
    // end 12/11/2016 TuyenNT bo_sung_vi_tri_quang_cao_in_feed_native_ads
    // Box an ninh xã hội
    $object->index(4);
    
    /*Begin 14-03-2019 trungcq XLCYCMHENG_34322_code_trang_thong_tin_can_biet*/
    $v_key_ttcb = hien_thi_box_ttcb_theo_chuyen_muc(ID_TRANG_CHU, 2);
    if($v_key_ttcb !=''){
        echo $v_key_ttcb;
    }
    /*End 14-03-2019 trungcq XLCYCMHENG_34322_code_trang_thong_tin_can_biet*/
    
    // Box thời trang
    $object->index(5);	
    $v_html_vietlott = html_box_data_vietlott();
    if($v_html_vietlott != ''){
        echo $v_html_vietlott;
    }
    // Box làm đẹp
    $object->index(6);	
    // Box tài chính
    $object->index(7);	
    // Box bạn trẻ
    $object->index(8);

    // Box video chọn lọc trang chủ
    $object = new box_video_chon_loc_trong_ngay_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index(5,0,2);	

    $object = new box_bai_viet_trang_chu_block();
    $object->setParam('v_device_global', $v_device_global);
    // Box cười
    $object->index(9);
    // Hiển thị quảng cáo
    echo _hien_thi_quang_cao_tren_trang('ADS_164_15s','bnr clF','',false);
    // Box thể thao
    $object->index(10);
    // Box phim
    $object->index(11);	
    // Box ca nhạc
    $object->index(12);
    // Box sức khỏe
    $object->index(13);
    //box thi truong tieu dung
    $object->index(14);
    // Box thời trang hi-tech
    $object->index(15);
    //Box Công nghệ thông tin
    $object->index(16);
    //Box Ô tô xe máy
    $object->index(17);
    //Box Phi thường kỳ quặc
    $object->index(18);
    // xe máy xe máy điện
    $object->index(19);
    // box am thuc
    $object->index(20);	
    // box du lich
    $object->index(21);
    // Box giao dục du học
    $object->index(22);
    // Bổ sung chuyên mục góc đồ họa
    $object->index(23);
    
    $object = new box_danh_cho_phai_dep_block();
	$object->setParam('v_device_global', $v_device_global);
	$object->index($cat_id,0,4);
    
    // Box thông tin cần biết
    $object = new box_thong_tin_can_biet_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index();
    ?>
</main>
