<?php
__get_db_functions('db.user');
__get_db_functions('db.news');
__get_db_functions('db.category');
__get_db_functions('db.general');

class category_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
    // Tam lay cac quyen tuong duong voi quan tri album anh
	var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
		'view' =>'VIEW_CAT',
		'edit' =>'EDIT_CAT',
		'delete' =>'DELETE_CAT',
	);
	// khai bao danh sach cac bien = _REQUEST
	var $_arr_arg  = array (
		'sel_category_id' => array(-1, 'intval')
		,'txt_category_id' => array('Nhập chuyên mục', 'fw24h_replace_bad_char')
		,'sel_user_id' => array(0, 'intval')
		,'txt_user_id' => array('Người sửa cuối', 'fw24h_replace_bad_char')
        ,'sel_status' => array(-1, 'intval')
		,'txt_status' => array('Trạng thái', 'fw24h_replace_bad_char')
        ,'txt_category_name'=>array('Nhập tên chuyên mục', 'fw24h_replace_bad_char')
		,'chk_dropdown_menu_mobile'=>array(-1, 'intval')
		,'chk_menu_mobile'=>array(-1, 'intval')
		,'chk_trang_danh_ba'=>array(-1, 'intval')
		//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
		,'chk_is_show_on_pc'=>array(-1, 'intval')
		//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
		,'page' => array(1, 'page_val')
		,'number_per_page' => array(10, 'intval')
	);

	/**
	 * Hien thi man hinh danh sach chuyen muc
	 */
	function index()
    {
        if (!$this->getPerm('admin,view')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
        $this->getRequest();
		
		//selected category	
		$v_category_id = $this->_GET['sel_category_id'];
		//selected user
		$v_selected_user = $this->_GET['sel_user_id']; 		
		$v_status = $this->_GET['sel_status'];  
        $v_cat_name = _utf8_to_ascii($this->_GET['txt_category_name']);  
		$v_is_dropdown_menu_mobile = $this->_GET['chk_dropdown_menu_mobile'];
		$v_is_menu_mobile = $this->_GET['chk_menu_mobile'];		 
		$v_is_trang_danh_ba = $this->_GET['chk_trang_danh_ba'];		 
        $page = $this->_GET['page'];
        $number_per_page = $this->_GET['number_per_page']; 
		//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
		$v_is_show_on_pc =  $this->_GET['chk_is_show_on_pc'];
		      
        // lay tat ca cac chuyen muc theo trang thai xuat ban
        $v_arr_items = be_get_all_category(0, $v_selected_user , '', ($v_cat_name=='Nhap ten chuyen muc')?'': $v_cat_name, $v_is_dropdown_menu_mobile, $v_is_menu_mobile, $_SESSION['user_id'], $v_is_trang_danh_ba, $v_is_show_on_pc);  
		//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc    
		
		//tim theo chuyen muc	
		if ( $v_category_id > 0 ) {
			$v_arr_items = $this->search_by_category($v_arr_items, array($v_category_id));
		}
		//Tim kiem theo trang thai
		if ($v_status > -1) {
            $v_arr_items = $this->search_by_status($v_arr_items,array($v_status));
        }
		
        $v_ds_chuyen_muc_cap1 = array();// khoi tao mang luu chuyen muc cap 1 de thuc hien phan trang theo chuyen muc cap 1
		$v_arr_temp = array(); //mang luu danh sach chuyen muc cap 1 va cap 2 de hien thi danh sach sau khi phan trang tren mang chuyen muc cap 1 
        if(count($v_arr_items) > 0) {
            // tach lay chuyen muc cap 1
			$v_ds_chuyen_muc_cap1 = get_sub_array_in_array($v_arr_items, 'Parent', 0, false);			
       		// merge lai 2 mang chuyen muc cap 1 va cap 2			
			if(count($v_ds_chuyen_muc_cap1) > 0) {
				$v_ds_chuyen_muc_cap1 = array_slice($v_ds_chuyen_muc_cap1, ($page-1)*$number_per_page, $number_per_page);			
				if(check_array($v_ds_chuyen_muc_cap1)) {
					foreach($v_ds_chuyen_muc_cap1 as $v_row) {				
						$v_arr_temp[] = $v_row; // insert chuyen muc cap 1
						$v_arr_temp = array_merge($v_arr_temp, get_sub_array_in_array($v_arr_items, 'Parent', $v_row['ID'], false));// insert chuyen muc cap 2
					}			
				}
			} else {// truong hop tim kiem theo chuyen muc cap 2
				$v_arr_temp = array_slice($v_arr_items, ($page-1)*$number_per_page, $number_per_page);
				$v_ds_chuyen_muc_cap1 = $v_arr_temp;
			}	
		}	
		// kiem tra chuyen muc hien thi trong menu ngang trang chu va trang trong
		$v_record_count = count($v_arr_temp);
		// danh sach chuyen muc hien thi trong box tim kiem
		$v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'],-1);		
		// danh sach user
		$v_arr_user = be_get_all_users('',1,2000);  
		// danh sach trang thai xuat ban   
		$v_arr_trang_thai =  get_list_trang_thai_xuat_ban();
		
        // lay ten cac doi tuong loc tim dang selectbox
		$this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
		$this->setParam('v_arr_user', $v_arr_user['data']);			
    	$this->setParam('v_arr_items', $v_arr_temp);
		$this->setParam('v_record_count', $v_record_count);
        $this->setParam('phan_trang', _db_page(count($v_ds_chuyen_muc_cap1), $page, $number_per_page));
		
		$this->setParam('goback', $this->_getCurrentUri());
   		$this->setParam('v_cat_name', $this->_GET['txt_category_name']);
		$this->render($this->thisPath().'view/dsp_all_item.php');
	}
    /**
	* Ham chuyen doi index chua mang thanh id cua cac doi tuong
    * param $p_data array: mang 2 chieu can chuyen doi
            $p_column_id string : ten cot ID cua doi tuong
	*/
    function array_convert_index_to_row_id($p_data, $p_column_id) {
        $rs_return = array();
        if(check_array($p_data)) {
            foreach($p_data as $v_row){
                $rs_return[$v_row[$p_column_id]] = $v_row;
            }
        }
        return $rs_return;
    }
    /**
	* Ham tim kiem theo id chuyen muc
    * param $p_data array: mang chua du lieu nguon
            $p_selected_cat array : mang chua id chuyen muc can tim
	*/
    function search_by_category($p_data, $p_selected_cat) {
        $rs_return = array();
        $p_data = $this->array_convert_index_to_row_id($p_data,'ID');    
        for($i=0;$i < $v_tong_so_chuyen_muc_chon = count($p_selected_cat) ;$i++) {   
            if(array_key_exists ($p_selected_cat[$i],  $p_data)) {
               $rs_return[] = $p_data[$p_selected_cat[$i]];
            }
        }
        return $rs_return;
    }
    /**
	* Ham tim kiem theo nguoi sua cuoi cung
    * param $p_data array: mang du lieu nguon
            $p_selected_user array : mang cac user duoc tich chon de tim
	*/
    function search_by_user($p_data, $p_selected_user) {
        $rs_return = array();
        for($i=0;$i < $v_tong_so_user = count($p_selected_user) ;$i++) {   
            for($j = 0; $j < $v_tong_so_chuyen_muc = count($p_data); $j++){
                if($p_selected_user[$i] == $p_data[$j]['last_editor_id']) {
                   $rs_return[] = $p_data[$j];
                }
            }
        }
        return $rs_return;
    }
    /**
	* Ham  tim kiem theo trang thai xuat ban
    * param $p_data array: mang du lieu nguon
            $p_selected_status array : mang cac trang thai duoc tich chon de tim
	*/
    function search_by_status($p_data, $p_selected_status) {
        $rs_return = array();
        for($i=0;$i < $v_tong_so_status = count($p_selected_status) ;$i++) {   
            for($j = 0; $j < $v_tong_so_chuyen_muc = count($p_data); $j++){
                if($p_selected_status[$i] == $p_data[$j]['Activate']) {
                   $rs_return[] = $p_data[$j];
                }
            }
        }
        return $rs_return;
    }
   
    /**
	 * Hien thi box tim kiem phia tren
	 */
	function dsp_filter_box_top($p_form_name='dsp_filter_box')
	{
		return $this->render($this->thisPath().'view/dsp_filter_box.php');
	}
    /**
	 * Hien thi box cac nut lenh
	 */
	function dsp_form_button()
	{
		return $v_html = $this->render($this->thisPath().'view/dsp_form_button.php');
	}

    /**
	 * Hien thi thong tin chi tiet ve 1 chuyen muc
	 */
	function dsp_single_category($p_category_id = 0)
    {
        if (!$this->getPerm('admin,view,edit')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
        $p_category_id = intval($p_category_id);
        if ($p_category_id > 0) {
            $rs_single_cat = be_get_single_category($p_category_id);
            if (count($rs_single_cat) > 0) {
                $this->setParam('rs_single_cat', $rs_single_cat);                
            }
        }
        $v_arr_ma_mau = array();
		// lay danh sach chuyen muc
        $v_ds_chuyen_muc_cap1 = be_get_all_category_by_select(-1, $_SESSION['user_id'],-1);
        // loc lay chuyen muc cap 1
        $v_ds_chuyen_muc_cap1 = get_sub_array_in_array( $v_ds_chuyen_muc_cap1['data'], 'Parent', 0, false);
        /* begin 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
        $this->setParam('v_arr_ma_mau', $v_arr_ma_mau);
        /* end 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
        $this->setParam('v_ds_chuyen_muc_cap1', $v_ds_chuyen_muc_cap1);        
        $this->setParam('v_arr_trang_thai', get_list_trang_thai_xuat_ban());
		$this->setParam('v_image_dimenssion', _get_module_config('category', 'image_dimension'));
        $this->setParam('v_parent_id', $p_parent_id);
        $this->setParam('v_id',$p_category_id);
        $this->render($this->thisPath().'view/dsp_single_category.php');
    }
    /**
	 * Chuan bi du lieu $_POST de cap nhat
	 */
    function act_prepare_data()
    {
        if ($_POST) {
			  //replace bad char
            $_POST = array_map('fw24h_replace_bad_char2', $_POST);
            foreach ($_POST as $k=>$v) {
                if (is_array($_POST[$k])) {
                    $this->_POST[$k] = $_POST[$k];
                } else {
                    $_POST[$k] = strip_tags($_POST[$k]);
                    $this->_POST[$k] = trim($_POST[$k]);
                }
            }
        }
    }
    
    /**
	 * Kiem tra du lieu hop le de cap nhat
	 */
	function act_check_data()
    {
		if ($this->_POST['txt_cat_name'] == '') {
            js_message('Bạn chưa nhập tên chuyên mục!');
            js_set('parent.document.frm_update_category.txt_cat_name.focus()');
            js_set('parent.document.frm_update_category.txt_cat_name.select()');
            return false;
        }
        if ( $this->_POST['txt_dropdown_position']!='' && !preg_match( '/^\d+$/', $this->_POST['txt_dropdown_position'])) {
            js_message('Thứ tự hiển thị trong dropdownlist mobile phải là số nguyên dương!');
            js_set('parent.document.frm_update_category.txt_dropdown_position.focus()');
            js_set('parent.document.frm_update_category.txt_dropdown_position.select()');           
            return false;
        }
        if ( $this->_POST['txt_position']!='' && !preg_match( '/^\d+$/', $this->_POST['txt_position'])) {
            js_message('Thứ tự hiển thị trên menu ngang mobile phải là số nguyên dương!');
            js_set('parent.document.frm_update_category.txt_position.focus()');
            js_set('parent.document.frm_update_category.txt_position.select()');
            return false;
        }
        if ( !preg_match( '/^\d+$/', $this->_POST['txt_order'])) {
            js_message('Thứ tự hiển thị chuyên mục phải là số nguyên dương!');
            js_set('parent.document.frm_update_category.txt_order.focus()');
            js_set('parent.document.frm_update_category.txt_order.select()');
            return false;
        }   
		//Kiem tra do dai ten menu ngang
		$v_ten_menu_ngang = $this->_POST['txt_mobile_text'];
		$v_ten_menu_ngang = _utf8_to_ascii(strip_tags($v_ten_menu_ngang));
		if (strlen($v_ten_menu_ngang) > 67) {
            js_message('Số lượng kí tự đối đa Menu text trên mobile không được vượt quá 67');
            js_set('parent.document.frm_update_category.txt_mobile_text.focus()');
            js_set('parent.document.frm_update_category.txt_mobile_text.select()');
            return false;
        }    		
        return true;
    }
    
    /**
	 * Chuc nang cap nhat thong tin chuyen muc
	*/
	function act_update_category($p_category_id = 0)
    {
        if (!$this->getPerm('admin,edit')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
        $v_user_id = $_SESSION['user_id'];
        $p_category_id = (int)$p_category_id;
		$this->act_prepare_data();
        if ($this->act_check_data()) {			
            //luu lich su sua doi
            if($p_category_id > 0){
                $this->act_update_category_history($p_category_id);               
            }            
            //pre data
			// upload file
            $v_upload_obj = new upload_image_block();  		
           	$v_anh_trang_danh_ba = '';
			$v_image_dimenssion = _get_module_config('category', 'image_dimension');		
            if ($_FILES['file_anh_trang_danh_ba']['name']!='') {               
                $file_image = $v_upload_obj->act_upload_single_image($_FILES['file_anh_trang_danh_ba'], 0, $v_image_dimenssion);
                if (count($file_url_icon['errors']) > 0) {
                    js_message('Ảnh đại diện:\n'.implode('\n', $file_image['errors']));                  
                } else {
                    $v_anh_trang_danh_ba = $file_image['file_path'];                      
                }
				if ($v_anh_trang_danh_ba =='') {
					js_message('Ảnh chưa đúng kích thước '.$v_image_dimenssion[0].'x'.$v_image_dimenssion[1].'px');                   
				}
            } else {
				 $v_anh_trang_danh_ba  = $this->_POST['hdn_anh_trang_danh_ba'];
			}
			$v_is_trang_danh_ba = ($this->_POST['chk_nha_hang']=='on')? 1 : 0;
			/*if ($v_is_trang_danh_ba && $v_anh_trang_danh_ba =='') {
				js_message('Bạn chưa chọn ảnh trang danh bạ');
				js_set('window.parent.set_enable_link("tr_button")');
				die;
			}*/
			$v_menu_ngang_footer = intval($this->_POST['chk_menu_ngang_footer']);
            // begin 10-04-2017 : trungcq xu_ly_footer_ipad 
            $v_menu_ngang_footer_ipad = intval($this->_POST['chk_menu_ngang_footer_ipad']);
            // end 10-04-2017 : trungcq xu_ly_footer_ipad 
			$v_ten_menu_ngang_footer = $this->_POST['txt_menu_ngang_footer'];
			$v_max_ten_menu_ngang_footer = _get_module_config('category', 'max_ten_menu_ngang_footer');
			if (strlen($v_ten_menu_ngang_footer) > $v_max_ten_menu_ngang_footer) {
				js_message('Text hiển thị trên menu ngang chỉ được tối đa '.$v_max_ten_menu_ngang_footer.' ký tự');
				js_set('window.parent.set_enable_link("tr_button")');
				die;
			}
			//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
			$v_is_show_on_pc = (int)$this->_POST['chk_is_show_on_pc'];
			//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
			
            // begin 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
            $urlHelper = new UrlHelper();$urlHelper->getInstance();
            $urlHelper->_BASE_URL = '';
            $v_urlslug = '';
            if($p_category_id >0){ // truong hop chuyen muc da ton tai thi chi cap nhat urlslug lan cap nhat dau tien
                $v_single_category = be_get_single_category($p_category_id);
                if(check_array($v_single_category)){
                    if($v_single_category['Urlslugs'] != ''){
                        // neu chuyen muc Urlslugs da ton thi se khong cap nhat moi
                        $v_urlslug = $v_single_category['Urlslugs'];
                    }else{
                        $v_urlslug = $urlHelper->preSlug($v_single_category['Name']);
                    }
                }
            }else{ // truong hop them moi thi cap nhat url slug theo ten chuyen muc
                $v_name = fw24h_replace_bad_char($this->_POST['txt_cat_name']);
                $v_urlslug = $urlHelper->preSlug($v_name);
            }
            // end 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
            /* begin 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
            $v_ma_mau = '';
            $v_sel_ma_mau = fw24h_replace_bad_char($this->_POST['sel_ma_mau']);
            if($v_sel_ma_mau == '-- Chọn --'){
                $v_ma_mau = '';
            }else{
                $v_ma_mau = $v_sel_ma_mau;
            }
            /* end 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
			// Begin 26-07-2018 trungcq XLCYCMHENG_32226_bo_sung_anh_chia_se_mxh
            //ảnh đại diện
            $v_anh_dai_dien_cm = '';
            if($this->_POST['hdn_file_anh_dai_dien_cm'] != ''){
                $this->_POST['c_anh_dai_dien'] = $this->_POST['hdn_file_anh_dai_dien_cm'];
                if ($_FILES['file_anh_dai_dien_cm']['name']!='') {
                    $v_arr_anh_dai_dien = _get_module_config('category', 'v_arr_anh_dai_dien');
                    $v_max_size_anh_dai_dien = _get_module_config('category', 'v_max_size_anh_dai_dien');
                    $v_file_uploaded = 	$v_upload_obj->act_upload_single_image($_FILES['file_anh_dai_dien_cm'], $v_max_size_anh_dai_dien, $v_arr_anh_dai_dien,'','',true);
                    if (count($v_file_uploaded['errors']) > 0) {
                        js_message($v_file_uploaded['errors'][0]);
                        js_set('window.parent.set_enable_link("tr_button")');
                        exit();
                    } else {
                        $this->_POST['c_anh_dai_dien'] = $v_file_uploaded['file_path'];
                    }
                }
                $v_anh_dai_dien = $this->_POST['c_anh_dai_dien'];
                $v_anh_dai_dien_cm = $this->_POST['c_anh_dai_dien_cm'];
                if($v_anh_dai_dien_cm == ''){
                    $v_anh_dai_dien_cm = $v_anh_dai_dien;
                }
            }else{
                $v_anh_dai_dien_cm = $this->_POST['hdn_anh_dai_dien'];
            }
            $v_anh_dai_dien = $v_anh_dai_dien_cm;

			//ảnh chia sẻ mạng xã hội
			$v_arr_anh_mxh_lon_nhat = _get_module_config('category', 'v_arr_anh_mxh_lon_nhat');
            $v_size_toi_da = _get_module_config('category', 'v_size_toi_da');
            //upload file
            $v_upload_obj = new upload_image_block();
            $this->_POST['c_anh_mxh'] = $this->_POST['hdn_file_anh_mxh'];
            if ($_FILES['file_anh_mxh']['name']!='') {
                $v_file_uploaded = $v_upload_obj->act_upload_single_image($_FILES['file_anh_mxh'], $v_size_toi_da, $v_arr_anh_mxh_lon_nhat,'','',true);
                if (count($v_file_uploaded['errors']) > 0) {
                    js_message($v_file_uploaded['errors'][0]);
                    js_set('window.parent.set_enable_link("tr_button")');
                    exit();
                } else {
                    $this->_POST['c_anh_mxh'] = $v_file_uploaded['file_path'];
                }
            }
            $v_anh_mxh = $this->_POST['c_anh_mxh'];
            $v_anh_chia_se_mxh = $this->_POST['c_anh_chia_se_mxh'];
            if($v_anh_chia_se_mxh == ''){
                $v_anh_chia_se_mxh = $v_anh_mxh;
            }
            $v_anh_mxh = $v_anh_chia_se_mxh;
			
            $data = array("ID"=>$p_category_id
                ,"Name"=>fw24h_replace_bad_char($this->_POST['txt_cat_name'])
                ,"c_ascii_name"=>_utf8_to_ascii(fw24h_replace_bad_char($this->_POST['txt_cat_name']))
                ,"Link"=>$this->_POST['txt_link']
                ,"LinkType"=>($this->_POST['chk_link_type']=='on')? 1 : 0
                ,"Parent"=>(int)$this->_POST['sel_category']
                ,"Activate"=>(int)$this->_POST['sel_publish']
                ,"Position"=>(int)$this->_POST['txt_order']
                ,"last_edit_id"=>$v_user_id
                ,"footerOption"=>(int)$this->_POST['rad_footer_option']
				,"nhahang"=>($this->_POST['chk_nha_hang']=='on')? 1 : 0
                /* begin 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
				,"nhahang_image"=>$v_ma_mau
                /* end 23/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
				,"c_menu_ngang_footer"=>$v_menu_ngang_footer
				,"c_ten_menu_ngang_footer"=>$v_ten_menu_ngang_footer
				//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
				,"c_is_show_on_pc" => $v_is_show_on_pc
				//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
                // begin 10-04-2017 : trungcq xu_ly_footer_ipad
                ,"c_menu_ngang_footer_ipad"=>$v_menu_ngang_footer_ipad
                // end 10-04-2017 : trungcq xu_ly_footer_ipad
                // begin 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
                ,"Urlslugs" => $v_urlslug
                // end 25/08/2016 TuyenNT fix_loi_doi_ten_chuyen_muc_gay_doi_slug
				,"c_anh_dai_dien" => $v_anh_dai_dien
				,"c_anh_chia_se_mxh" => $v_anh_mxh
            );
            //call update
            $v_result =   be_update_category($data);
			$v_cat_id  =  $v_result['c_cat_id'];
            if ($v_cat_id > 0) {
				if($p_category_id == 0){
                    // luu lich su sua doi
					$this->act_update_category_history($v_cat_id);
                    
                    // Phan quyen chuyen muc cho user
                    global $V_ARR_USERS;
                    if (!is_array($V_ARR_USERS)) {
                        $V_ARR_USERS = be_get_all_users('', 1, 300);
                        $V_ARR_USERS = $V_ARR_USERS['data'];
                    }
					
                    $V_ARR_USERS = get_sub_array_in_array($V_ARR_USERS, 'c_type', 1, false);
                    if (check_array($V_ARR_USERS)) {
                        foreach ($V_ARR_USERS as $v_user) {
                            be_update_user_category($v_user['ID'], $v_cat_id);
                        }
                    }
				}
                js_message('Cập nhật chuyên mục thành công!');
                if ($this->_POST['goback']) {
                    js_redirect(fw24h_base64_url_decode($_POST['goback']));
                } else {
                    js_redirect(BASE_URL.$this->className().'/index');
                }
            } else {
				$v_error = $v_result['RET_ERROR'];
				switch($v_error) {
					case "ERROR_TRUNG_TEN":
						js_message("Tên chuyên mục trùng với tên chuyên mục cùng cấp đã tạo thành công trước đó");
						break;
					case "ERROR_CAP_CHUYEN_MUC":
						js_message("Không chuyển được chuyên mục từ cấp 1 sang cấp 2. Vì chuyên mục cấp 1 đã có các chuyên mục cấp 2 khác!");
						break;
					case 'ERROR_DANH_BA':
						js_message("Chuyên mục có dữ liệu bài viết đang xuất bản không được phép chuyển sang dạng trang danh bạ");
						break;
					default:
						js_message($v_error);
				}
			}
        }
		//hien thi cac nut lenh
        js_set('window.parent.set_enable_link("tr_button")');
        exit();
    }
    
    /**
	 * Thuc thi lenh xoa cac doi tuong duoc chon tren man hinh danh sach
	 */
    function act_delete_category()
    {
		if (!$this->getPerm('admin,delete')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
		$v_rows = intval($_REQUEST["hdn_record_count"]);
		$count = 0;
		$v_user_id = $_SESSION['user_id'];
        $v_error_message = '';
        $rs_cat_checked = array();
        for ($i=0; $i < $v_rows; $i++) {
			$v_id = intval($_REQUEST["chk_item_id".$i]);					
			if ($v_id > 0) {
                $rs_cat_checked[] = $v_id;			
			}
		}
        
        $v_rows = count($rs_cat_checked);
        if ($v_rows == 0) {
			js_message('Chưa có đối tượng nào được chọn!');		
			js_set('window.parent.set_enable_link("tr_button")');
            die;			
		}
        // thuc hien xoa		
        for ($i=0; $i < $v_rows; $i++) {
			$v_id = $rs_cat_checked[$i]; 					
			$rs = be_delete_category($v_id);   
			if($rs['RET_ERROR'] !=''){
				$v_error_message.= 'Không xóa được chuyên mục ID :'.$v_id.' đã xuất bản\n'; 
            } else {
				$count++;
			}
        }
		if ($v_error_message!='') {
			js_message($v_error_message);
			js_set('window.parent.set_enable_link("tr_button")');
            die;			
		}
		if($count > 0) {
			js_message('Đã xoá thành công '.$count.' chuyên mục!');
		}	
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        } 
	}
  
    /**
	 * Cap nhat lich su sua doi chuyen muc
	 */		
	function act_update_category_history($p_category_id)
	{
        $p_category_id = intval($p_category_id);
		if ($p_category_id <= 0) {
			return false;
		}
		$rs = be_get_single_category($p_category_id);
		be_cap_nhat_lich_su($p_category_id, 'category', $rs, $_SESSION['user']);
	}
     /**
	 * Thuc hien cap nhat cac chuyen muc thay doi tren man hinh danh sach
	 */
    function act_update_category_list()
    {
		if (!$this->getPerm('admin,edit')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
		$v_rows = intval($_REQUEST["hdn_record_count"]);
		$count = 0;
		$v_user_id = $_SESSION['user_id'];
        $v_error_message = '';
        $v_txt_order_control = '';
        $rs_cat_checked = array();
        $rs_cat_mobile_checked = array();
        for ($i=0; $i < $v_rows; $i++) {
			$v_id = intval($_REQUEST["chk_item_id".$i]);			
			if ($v_id > 0) {
                $v_parent = intval($_REQUEST["hdn_parent".$i]);	
                $v_position = ($v_parent == 0)? $_REQUEST["txt_order1_".$i]: $_REQUEST["txt_order2_".$i];
                $v_txt_order_control = ($v_parent == 0)? 'txt_order1_'.$i: 'txt_order2_'.$i;
                if ( !preg_match( '/^\d+$/', $v_position)) {
                    $v_error_message.= 'Vị trí hiển thị phải là số nguyên dương';                   
                    break;
                }
				$rs_single_cat = be_get_single_category($v_id);	
				$rs_cat_checked[] = array(
                    "ID"=>$v_id
                    ,"Name"=>$rs_single_cat['Name']
					,"c_ascii_name"=>$rs_single_cat['c_ascii_name'] 
                    ,"Link"=>$rs_single_cat['Link']
                    ,"LinkType"=>intval($rs_single_cat['LinkType'])
                    ,"Parent"=>intval($rs_single_cat['Parent'])
                    ,"Activate"=>intval($_REQUEST["sel_publish".$i])
                    ,"Position"=>$v_position
                    ,"last_edit_id"=>$v_user_id
                    ,"footerOption"=>intval($rs_single_cat['footerOption'])	
					,"nhahang"=>$rs_single_cat['nhahang']
					,"nhahang_image"=>$rs_single_cat['nhahang_image']
					,"c_menu_ngang_footer"=>intval($rs_single_cat['c_menu_ngang_footer'])
					,"c_ten_menu_ngang_footer"=>fw24h_replace_bad_char($rs_single_cat['c_ten_menu_ngang_footer'])
					//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
					,"c_is_show_on_pc" => 0
					//End Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
                    ); 
                }
		}
        if($v_error_message != '') {
            js_message($v_error_message);
            js_set('parent.document.frm_dsp_all_item.'.$v_txt_order_control.'.focus()');
            js_set('parent.document.frm_dsp_all_item.'.$v_txt_order_control.'.select()');
			js_set('window.parent.set_enable_link("tr_button")');            
            die;
        }
        
        $v_rows = count($rs_cat_checked);
        if ($v_rows == 0) {
			js_message('Không có chuyên mục nào thay đổi!');
			js_set('window.parent.set_enable_link("tr_button")');
			die;
		}
        // thuc hien cap nhat 
        for ($i=0; $i < $v_rows; $i++) {
			$data = $rs_cat_checked[$i];
			// cap nhat lich su
            $this->act_update_category_history($data['ID']);
            // cap nhat thay doi		
            $v_new_id = be_update_category($data);
			
			if($v_new_id > 0){
				$count++;
            }
        }
		// END cap nhat cho mobile
        js_message('Đã thực hiện cập nhật thành công '.$count.' chuyên mục!');
		
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        }
	}        
}