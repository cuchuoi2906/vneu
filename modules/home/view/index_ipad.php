<main>
    <?php
    $object = new box_breaking_news_block();
    $object->index();
	/*Begin 14-05-2018 trungcq XLCYCMHENG_31384_bo_sung_box_dac_biet_trang_chu*/
	$v_hien_thi_su_kien_dac_biet = get_gia_tri_danh_muc_dung_chung('CAU_HINH_BOX_DAC_BIET_TRANG_CHU','HIEN_THI_BOX_SU_KIEN_DAC_BIET_IPAD');
	if(strtoupper($v_hien_thi_su_kien_dac_biet)=='TRUE'){
		$v_key_box_su_kien_dac_biet = "ipad_box_su_kien_dac_biet_trang_chu_responsive";
		$v_html_box_su_kien_dac_biet  = Gnud_Db_read_get_key($v_key_box_su_kien_dac_biet, _CACHE_TABLE_BOX_BAI_VIET);
		if ($v_html_box_su_kien_dac_biet != ""){	
			echo $v_html_box_su_kien_dac_biet;
		}
	}
	/*End 14-05-2018 trungcq XLCYCMHENG_31384_bo_sung_box_dac_biet_trang_chu*/
    // Box tin tưc trong ngày
    $object = new box_tin_tuc_trong_ngay_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index(BOX_DAC_BIET_TIN_TUC_TRONG_NGAY);
    $object->index(BOX_DAC_BIET_TIN_BONG_DA);
    ?> 
    <div class="divBnrHm mrB10">
        <?php
        // Xử lý hiển thị quảng cáo
        echo html_text_phan_biet_quang_cao();
        echo _hien_thi_quang_cao_tren_trang('ADS_198_15s',0,'bnrHmL','');
    
        $object = new box_bai_viet_canh_banner_hot_block();
        $object->setParam('v_device_global', $v_device_global);
        $object->index(ID_CHUYEN_MUC_CANH_BANNER_HOT);
        ?>
    </div>
    <?php
    // Hiển thị box bài viết trang chủ
    $object_bai_viet_trang_chu = new box_bai_viet_trang_chu_block();
    $object_bai_viet_trang_chu->setParam('v_device_global', $v_device_global);
    
    $object_bai_viet_trang_chu->index($position=3);
    $object_bai_viet_trang_chu->index($position=4);
    $object_bai_viet_trang_chu->index($position=5);
    $object_bai_viet_trang_chu->index($position=6);
    $object_bai_viet_trang_chu->index($position=7);
    ?>
    <div class="clF pdB5"></div>
    <?php
    $object = new box_video_clip_dac_sac_block();
    $object->setParam('v_device_global', $v_device_global);
    $object->index();
    
    /* begin 18/5/2017 TuyenNT nang_cap_ipad_2017_ghep_trang_chu_ipad */
    $v_key = "tablet_box_slide_menu_trang_chu_responsive".ID_TRANG_CHU."_1_8";
    $v_html_value  = Gnud_Db_read_get_key($v_key, _CACHE_TABLE_BOX_BAI_VIET);
    if ($v_html_value != ""){	
        echo $v_html_value;
    }
    /* end 18/5/2017 TuyenNT nang_cap_ipad_2017_ghep_trang_chu_ipad */
    $v_count = count($home_index_box);
    for ($i=8; $i<=$v_count; $i++) {
        $object_bai_viet_trang_chu->index($home_index_box[$i]['box_number']);
    }
    ?>
</main>