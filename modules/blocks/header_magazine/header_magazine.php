<?php
__get_db_functions('db.general');
class header_magazine_block extends Fw24H_Block
{
	var $_cache_key = 'header_magazine_responsive';
    function index($cat_id = ID_TRANG_CHU, $region_id= '0',$v_device_global= 'pc', $view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
		$this->generate();
	}
		
    function doAction()
    {
        $v_device_global = fw24h_replace_bad_char($this->getParam('v_device_global'));
        if($v_device_global == ''){
            global $v_device_global;
        }
        // Kiểm tra thiết bị PC
        if(_is_thiet_bi_pc($v_device_global)){
            $this->dsp_header_magazine_pc();
        }
        // Kiểm tra thiết bị mobile
        if(_is_thiet_bi_mobile($v_device_global)){
            $this->dsp_header_magazine_mobile();
        }
		// Kiểm tra thiết bị mobile
        if(_is_thiet_bi_ipad($v_device_global)){
            $this->dsp_header_magazine_ipad();
        }
    }
    /*
     * Hiển thị giao diện phiên bản PC
    */
    function dsp_header_magazine_ipad(){
        // Lay gia tri tham so
		$cat_id = $this->getParam('cat_id');
		$v_region_id = $this->getParam('region_id');
	
		if ($v_region_id != '' && $v_region_id != 0) {
			$v_region_id_list = '"'.$v_region_id.'"'.',"0"';
		} else {
			$v_region_id_list = '"0"';
		}
		$rs_header = fe_header_ipad($cat_id,$v_region_id_list);		
		// Lay key value js quang cao	 	
		$row_cat = fe_chuyen_muc_theo_id($cat_id);
		//phuong add 08/05/2014 them tham so lay header script theo vung mien
        $v_region_value = get_region_value($v_region_id);
		// thiet bi = 2 : ipad; 0 - Lay script xuat ban tren chuyen muc cua bai viet      
        $row_header_script = fe_header_script($cat_id, $v_thiet_bi = 2, $v_region_value, 0); 
		// Lay them script tren trang bai viet 
		if ($v_xuat_ban_trang_bai_viet>0) {
			$row_header_script_trang_bai_viet = fe_header_script($cat_id, $v_thiet_bi = 2, $v_region_value, $v_xuat_ban_trang_bai_viet);
			if (check_array($row_header_script_trang_bai_viet)){
				$row_header_script = array_merge($row_header_script,$row_header_script_trang_bai_viet);
				array_unique_key($row_header_script, 'pk_script');
			}	
		}	
		/*Begin 07-06-2018 trungcq XLCYCMHENG_31660_xu_ly_code_script_quang_cao_amazone*/
		$v_arr_header_script = array();
		if(check_array($row_header_script)){
			$v_arr_header_script = xu_ly_header_script_theo_loai($row_header_script);
		}
		/*End 07-06-2018 trungcq XLCYCMHENG_31660_xu_ly_code_script_quang_cao_amazone*/
		// 13/12/2014 HaiLT xử lý GA theo chuyên mục
		$v_arr_ga = fe_danh_sach_ga(2, $cat_id, -1, -1, -1);
		$v_ds_ga = '';
		for ($i = 0, $s = sizeof($v_arr_ga); $i < $s; ++$i){
			$v_thiet_bi = intval($v_arr_ga[$i]['c_thiet_bi']);
			if ($v_thiet_bi == 0 || $v_thiet_bi == 2){
				// ga tổng chuyên mục hoặc ga cho ipad
				$v_ds_ga .= ','.$v_arr_ga[$i]['c_list_ga'];
			}
		}
		$this->setParam('v_ds_ga', $v_ds_ga);
		
		//Thangnb 29/01/2015 lay script marketing theo chuyen muc
		if($row_cat['Parent'] > 0){
			$row_seo_cat = fe_seo_chi_tiet_chuyen_muc_theo_id($row_cat['Parent'], ID_THIET_BI_IPAD);
		} else {
			$row_seo_cat = fe_seo_chi_tiet_chuyen_muc_theo_id($cat_id, ID_THIET_BI_IPAD);
		}
        $this->setParam('cat_id', $cat_id);	 /* Tytv 26/09/2016 on_off_text_link_logo_header_footer */
		$this->setParam('row_seo_cat', $row_seo_cat);		
		$this->setParam('row_cat', $row_cat);		
		$this->setParam('rs_header', $arr_headeroption);		
        $this->setParam('row_header_script', $v_arr_header_script);
        $this->render($this->thisPath().'view/header_magazine_ipad.php');
    }
    /*
     * Hiển thị giao diện phiên bản PC
    */
    function dsp_header_magazine_mobile(){
        // Lay gia tri tham so
		$cat_id = $this->getParam('cat_id');
		$v_region_id = $this->getParam('region_id');      
        $v_xuat_ban_trang_bai_viet = intval($this->getParam('trang_bai_viet'));
		
		if ($v_region_id != '' && $v_region_id != 0) {
			$v_region_id_list = ''.$v_region_id.''.',0';
		} else {
			$v_region_id_list = '0';		
		}
		
		// Header tất cả các trang là như nhau
		$rs_header = fe_header_trang_chu($cat_id, $v_region_id_list);                      
		
		$row_cat = fe_chuyen_muc_theo_id($cat_id);		
		if($row_cat['Parent'] > 0){
			$row_seo_cat = fe_seo_chi_tiet_chuyen_muc_theo_id($row_cat['Parent'], ID_THIET_BI_MOBILE);			
		} else {
			$row_seo_cat = fe_seo_chi_tiet_chuyen_muc_theo_id($cat_id, ID_THIET_BI_MOBILE);
		}
		//phuong add 08/05/2014 them tham so lay header script theo vung mien
        $v_region_value = get_region_value($v_region_id);
		// thiet bi = 4 : mobile 2014; 0 - Lay script xuat ban tren chuyen muc cua bai viet      
        $row_header_script = fe_header_script($cat_id, 4, $v_region_value, 0); 
		// Lay them script tren trang bai viet 
		if ($v_xuat_ban_trang_bai_viet>0) {
			$row_header_script_trang_bai_viet = fe_header_script($cat_id, 4, $v_region_value, 1);
			if (check_array($row_header_script_trang_bai_viet)){
				$row_header_script = array_merge($row_header_script,$row_header_script_trang_bai_viet);
				array_unique_key($row_header_script, 'pk_script');
			}	
		}	
		/*Begin 07-06-2018 trungcq XLCYCMHENG_31660_xu_ly_code_script_quang_cao_amazone*/
		$v_arr_header_script = array();
		if(check_array($row_header_script)){
			$v_arr_header_script = xu_ly_header_script_theo_loai($row_header_script);
		}
		/*End 07-06-2018 trungcq XLCYCMHENG_31660_xu_ly_code_script_quang_cao_amazone*/
		// 13/12/2014 HaiLT xử lý GA theo chuyên mục
		$v_arr_ga = fe_danh_sach_ga(2, $cat_id, -1, -1, -1);
		$v_ds_ga = '';
		for ($i = 0, $s = sizeof($v_arr_ga); $i < $s; ++$i){
			$v_thiet_bi = intval($v_arr_ga[$i]['c_thiet_bi']);
			if ($v_thiet_bi == 0 || $v_thiet_bi == 3){
				// ga tổng chuyên mục hoặc ga cho mobile
				$v_ds_ga .= ','.$v_arr_ga[$i]['c_list_ga'];
			}
		}
		$this->setParam('v_ds_ga', $v_ds_ga);
		
       	$this->setParam('row_cat', $row_cat);		
		$this->setParam('row_seo_cat', $row_seo_cat);		
		$this->setParam('rs_header', $rs_header);		
		$this->setParam('row_header_script', $v_arr_header_script);
		$this->setParam('v_region_id', $v_region_id);
		$this->render($this->thisPath().'view/header_magazine_mobile.php');
    }
    /*
     * Hiển thị giao diện phiên bản PC
     */
    function dsp_header_magazine_pc(){
        // Lay gia tri tham so
		$cat_id = $this->getParam('cat_id');
		$v_region_id = $this->getParam('region_id');      
		
		if ($v_region_id != '' && $v_region_id != 0) {
			$v_region_id_list = ''.$v_region_id.''.',0';
		} else {
			$v_region_id_list = '0';		
		}
		
		$rs_header = array(); 
		                     
		$row_cat = fe_chuyen_muc_theo_id($cat_id);
		$v_arr_header_script = array();
		$v_ds_ga = '';
		
		$this->setParam('v_ds_ga', $v_ds_ga);
		// begin 23/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h
        $this->setParam('cat_id', $cat_id);
        // end 23/05/2016 TuyenNT fix_loi_va_bo_sung_snippet_ipad_mobile_24h
       	$this->setParam('row_cat', $row_cat);
		$this->setParam('rs_header', $rs_header);		
		$this->setParam('row_header_script', $v_arr_header_script);
		//02-07-2015: Thangnb set biến vùng miền để check banner_us
		$this->setParam('v_region_value',$v_region_value);
        if(isset($_SERVER['is_mobile']) && $_SERVER['is_mobile']){
            $this->render($this->thisPath().'view/header_magazine_mobile.php');
        }else{
            $this->render($this->thisPath().'view/header_magazine_pc.php');
        }
    }
}

