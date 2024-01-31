<?php
__get_db_functions('db.user');
__get_db_functions('db.news');
__get_db_functions('db.category');
__get_db_functions('db.general');
__get_db_functions('db.seo');
include_once('class/url_helper.php');									 
//change include path
set_include_path(get_include_path() . PATH_SEPARATOR . WEB_ROOT . 'includes/PHPExcel/Classes/');
/** PHPExcel_IOFactory */
include 'PHPExcel.php';
include 'PHPExcel/IOFactory.php';
class seo_chi_tiet_bai_viet_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
    var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
		'view' =>'VIEW_SEO_CHI_TIET_BAI_VIET',
		'edit' =>'EDIT_SEO_CHI_TIET_BAI_VIET',
		'delete' =>'DELETE_SEO_CHI_TIET_BAI_VIET',
		'publish'=>'PUBLISH_SEO_CHI_TIET_BAI_VIET',
        // begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
        'edit_mxh' =>'SUA_TITLE_DES_MXH_BAI_VIET',
        /* Begin anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
        'import'=>'IMPORT_TITLE_DES_KEYW_BAI_VIET',
        'export'=>'EXPORT_TITLE_DES_KEYW_BAI_VIET',
        /* End anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
	);
	// khai bao danh sach cac bien = _REQUEST
	var $_arr_arg  = array (
		'sel_category_id' => array(0, 'intval')
		,'sel_user_id' => array(0, 'intval')	
        ,'sel_status' => array(-1, 'intval')		
		,'txt_news_name'=>array('Nhập tên bài viết', 'fw24h_replace_bad_char')
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		,'txt_news_name_seo'=>array('Nhập tiêu đề SEO', 'fw24h_replace_bad_char')
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		,'txt_news_id'=>array('0', 'intval')
		,'sel_sorttype'=>array(0, 'intval')
        ,'sel_thiet_bi_filter'=>array(-1, 'intval') // loc theo thiet bi
		,'page' => array(1, 'page_val')
		,'number_per_page' => array(_CONST_NUMBER_OF_ROW_PER_LIST, 'intval')
		/* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
        ,'txt_edit_date_start' => array('', 'strval')
		,'txt_edit_date_end' => array('', 'strval')
        /* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
        ,'chk_add_amp'=>array('0', 'intval')
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
	);
	//Khai bao mang cac loi	
	var $arr_error_msg = array(
		'invalid_access'=>'Bạn không có quyền thực hiện chức năng này'
		,'empty'=>'Chưa có đối tượng nào được chọn'
		,'update_success'=>'Cập nhật thành công'
		,'delete_success'=>'Đã xóa thành công'
		,'no_data_change'=>'Không có bản ghi nào thay đổi'
		,'empty_publish_date'=>'Bạn chưa nhập thời gian xuất bản'
		,'empty_publish_status'=>'Bạn chưa chọn trạng thái xuất bản'
	);
	var $v_doi_tuong_seo ='BAI_VIET';
	/**
	 * Hien thi man hinh danh sach bai viet
	 */
	function index()
    {
		if (!$this->getPerm('admin,view')) {
			js_message($this->arr_error_msg['invalid_access']);
			exit;
		}

        $this->getRequest();                
		//selected category	
		$v_category_id = $this->_GET['sel_category_id'];	
		//selected user
		$v_selected_user = $this->_GET['sel_user_id']; 
		$v_selected_status = $this->_GET['sel_status']; 
		$v_ten_bai_viet = _utf8_to_ascii($this->_GET['txt_news_name']); //Ten bai viet
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$v_tieu_de_seo = _utf8_to_ascii(fw24h_replace_bad_char($this->_GET['txt_news_name_seo'])); // Tieu de SEO bai viet
        $v_tieu_de_seo  = $v_tieu_de_seo == 'Nhap tieu de SEO' ? '' : $v_tieu_de_seo;
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		//End 31-08-2017 : Thangnb toi_uu_tim_kiem_tieu_de_bai_viet 
		$v_id_bai_viet =  $this->_GET['txt_news_id']; //id bai viet
		$v_sort_type = $this->_GET['sel_sorttype']; //kieu sap xep		
        $v_thiet_bi_id = $this->_GET['sel_thiet_bi_filter']; //phuonghv add 18/11/2014
        $v_page = $this->_GET['page'];
        /* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
        $v_edit_date_start = _sql_format_date($this->_GET['txt_edit_date_start']);
		$v_edit_date_end = _sql_format_date($this->_GET['txt_edit_date_end']);
		// Mac dinh lay seo tin bai duoc thay doi trong 1 thang gan nhat
        $v_edit_date_start = ($v_edit_date_start =='') ? date('Y-m-d',strtotime(date('Y-m-d') . ' -1 month')) : $v_edit_date_start;
        $v_edit_date_end = ($v_edit_date_end =='') ? date('Y-m-d') : $v_edit_date_end;
        $v_number_per_page = $this->_GET['number_per_page'];		
        $v_order_by = ' a.c_thoi_gian_sua DESC';		
		$v_ten_bai_viet  = ($v_ten_bai_viet =='Nhap ten bai viet')? '':$v_ten_bai_viet ;
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
        $v_is_off_amp =  $this->_GET['chk_add_amp'];
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
        $v_arr_items  = be_get_all_seo_chi_tiet_bai_viet(
                ($v_category_id ==0)?'':$v_category_id, 
                $v_selected_user, 
                ($v_selected_status ==-1)?'':$v_selected_status, 
                $v_ten_bai_viet, 
                addslashes($v_tieu_de_seo),
                $v_id_bai_viet, 
                $v_order_by, 
                $v_thiet_bi_id, 
                $v_edit_date_start, 
                $v_edit_date_end, 
                $v_page, 
                $v_number_per_page,
                $v_is_off_amp);		
        //var_dump($v_arr_items);die;
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
    	/* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
		$v_tong_so = count($v_arr_items);	
        // lay du lieu thiet bi
        $v_arr_device  = array();	
        // danh sach trang thai xuat ban     
		$v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'],-1);	  		
        $v_arr_user = be_get_all_users('',1,2000);  	
		$v_arr_trang_thai = get_list_trang_thai_xuat_ban();
				
		$v_category_name = 'Nhập chuyên mục';
		$v_user_name = 'Người sửa cuối';
		$v_status_name = 'Trạng thái';
        $this->setParam('v_arr_device', $v_arr_device);        
		// danh sach trang thai xuat ban       		
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
		$this->setParam('v_arr_user', $v_arr_user['data']);		
		$this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->setParam('v_arr_items', $v_arr_items);
		$this->setParam('v_record_count', $v_tong_so);
        $this->setParam('phan_trang', _db_page($v_tong_so, $v_page, $v_number_per_page));
        $this->setParam('goback', $this->_getCurrentUri());        
        $this->setParam('v_id_bai_viet', ($v_id_bai_viet  == 0)? '':$v_id_bai_viet);		
		$this->setParam('v_ten_bai_viet', $this->_GET['txt_news_name']);		
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$this->setParam('v_tieu_de_seo', $this->_GET['txt_news_name_seo']);		
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$this->setParam('v_sort_type',$v_sort_type);
		$this->setParam('v_category_name', $v_category_name);
		$this->setParam('v_user_name', $v_user_name);
		$this->setParam('v_status_name', $v_status_name);
		/* Begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
		$this->setParam('v_edit_date_start', $v_edit_date_start);
		$this->setParam('v_edit_date_end', $v_edit_date_end);
		/* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
		$this->setParam('v_is_off_amp', $v_is_off_amp);
        //Begin 12/5/2020 AnhTT bo_sung_tich_amp
		$this->render($this->thisPath().'view/dsp_all_item.php');
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
	 * Hien thi thong tin chi tiet
	 */
	function dsp_single_seo_chi_tiet($p_seo_chi_tiet_bai_viet_id = 0, $p_news_id = 0)
    {
        if (!$this->getPerm('admin,view,edit')) {
			js_message($this->arr_error_msg['invalid_access']);
			exit;
		}
        /* Begin anhpt1 24/06/2016 fix_loi_bao_mat_sql_injection */
        $p_seo_chi_tiet_bai_viet_id = intval($p_seo_chi_tiet_bai_viet_id);
        /* End anhpt1 24/06/2016 fix_loi_bao_mat_sql_injection */
		$v_goback = $_REQUEST['goback'];
		$v_error_message = '';
		// id bai viet phai la so duong
		if (!preg_match( '/^\d+$/', $p_news_id)) {
			$v_error_message = 'ID bài viết phải là số nguyên dương';            
		} else {
			$p_news_id = intval($p_news_id);
		}
        // danh muc thiet bi
        $v_arr_device  = array();
        /* Begin 08/03/2017 LuanAD XLCYCMHget_list_pr_device()ENG_16889_toi_uu_seo_bai_viet */
        $v_arr_event = array();
        //Mảng lưu thông tin các chuyên mục của 1 tin bài
        $v_arr_category = array();
        /* End 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
        if ($p_news_id > 0 && $v_error_message  == '') {
			$v_arr_news = be_get_single_news($p_news_id);   
			$v_error_message = (count($v_arr_news) == 0)? 'Không tồn tại bài viết có ID: '.$p_news_id: '';
			if($v_error_message == '') {
				$v_content = 	$v_arr_news['Body'];
				$v_news_keyword = $v_arr_news['keywords'];			
				$v_album_id = 	$v_arr_news['Album_trang_anh'];
				//Tim danh sach anh trong bai viet
				$v_arr_images = array();
				$v_category_name = '';
                /* begin 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */
                $v_news_Title = cutBrief($v_arr_news['Title'], 120);
                $v_news_Title = str_replace('...', '', $v_news_Title);
                $v_news_desc = cutBrief($v_arr_news['Summary'], 360);
                $v_news_desc = str_replace('...', '', $v_news_desc);
                $v_seo_title = $rs_single_seo_chi_tiet['c_title'];
                // kiem tra neu title trong phan quan tri chua co thi se hien thi tile mac dinh cua bai viet
                if($v_seo_title == ''){
                    $rs_single_seo_chi_tiet['c_title'] = $v_news_Title;
                }
                // kiem tra neu desc trong phan quan tri chua co thi se hien thi desc mac dinh cua bai viet
                $v_seo_desc = $rs_single_seo_chi_tiet['c_desc'];
                if($v_seo_desc == ''){
                    $rs_single_seo_chi_tiet['c_desc'] = $v_news_desc;
                }
                /* end 11/1/2017 TuyenNT toi_uu_chuc_nang_seo_bai_viet_tu_dong_load_ra_title_desc_bai_viet */
				// kiem tra neu keyword trong phan quan tri chua co thi se hien thi keyword mac dinh cua bai viet
				$v_seo_keyword = $rs_single_seo_chi_tiet['c_keyword'];
				if ($v_seo_keyword =='') {
					$rs_single_seo_chi_tiet['c_keyword'] = $v_news_keyword;
				}
				$v_category_name = '';		
				$rs_single_seo_chi_tiet['c_ten_bai_viet'] = 	strip_tags($v_arr_news['Title']);
				$rs_single_seo_chi_tiet['c_ten_chuyen_muc'] = 	$v_category_name;
				$rs_single_seo_chi_tiet['c_bien_tap_vien'] = 	get_name_of_editor($v_arr_news['EditorID']);
				$rs_single_seo_chi_tiet['c_ngay_xuat_ban'] = 	_dinh_dang_ngay($v_arr_news['DateEdited'], 'd/m/Y H:i:s');           
                //phuonghv add 03/04/2015         
                $rs_single_seo_chi_tiet['c_anh_chia_se_mxh'] = $rs_single_seo_chi_tiet['c_anh_chia_se_mxh']==''?$v_arr_news['c_anh_chia_se_mxh']:$rs_single_seo_chi_tiet['c_anh_chia_se_mxh'];
                //end:phuonghv add 03/04/2015
                /* Begin 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
                //Nếu chưa có giá trị này thì mặc định sẽ để 3 ngày tiếp theo
                if(!$rs_single_seo_chi_tiet['c_ngay_ha_xuat_ban'] || $rs_single_seo_chi_tiet['c_ngay_ha_xuat_ban'] == '0000-00-00'){
                    $rs_single_seo_chi_tiet['c_ngay_ha_xuat_ban'] = date('d-m-Y', strtotime("+3 days"));
                }
                /* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
				$this->setParam('rs_single_seo_chi_tiet', $rs_single_seo_chi_tiet);
			}
        }
        /* Begin 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
        //Lấy bộ keyword theo chủ để bài viết
        $v_arr_keyword = array();
        $this->setParam('v_arr_chu_de_id_list_cau_hinh', $v_arr_chu_de_id_list_cau_hinh);
        $this->setParam('v_chuyen_muc_id_list', $v_chuyen_muc_id_list);
        $this->setParam('v_chu_de_id_list_da_ton_tai', $v_chu_de_id_list_da_ton_tai);
        /*End 16-01-2017 trungcq bo_sung_tich_chon_chu_de_bai_viet_cap_nhat_seo*/
        $this->setParam('v_arr_device', $v_arr_device);	        
		$this->setParam('v_error_message', $v_error_message);		
        $this->setParam('v_arr_trang_thai', get_list_trang_thai_xuat_ban());		
        $this->setParam('v_id',$p_seo_chi_tiet_bai_viet_id);
        $this->setParam('v_news_id',$p_news_id);
		$this->setParam('v_goback', $v_goback);
        $this->setParam('v_arr_images', $v_arr_images);
		$this->setParam('v_album_id', $v_album_id);	
		$this->setParam('v_anh_chia_se_mxh', $v_arr_news['c_anh_chia_se_mxh']);
        /* Begin 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
        $this->setParam('v_arr_event', $v_arr_event);
        $this->setParam('v_main_event', $v_arr_news['EventID']);
        $this->setParam('v_arr_keyword', $v_arr_keyword);
        /* End 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
		$this->render($this->thisPath().'view/dsp_single_seo_chi_tiet.php');
		
    }

    /**
	 * Chuan bi du lieu $_POST de cap nhat
	 */
    function act_prepare_data()
    {
        if ($_POST) {
            foreach ($_POST as $k=>$v) {
                if (is_array($_POST[$k])) {
                    $this->_POST[$k] = $_POST[$k];
                } else {
                    $_POST[$k] = addslashes(strip_tags($_POST[$k]));
                    $this->_POST[$k] = trim($_POST[$k]);
                }
            }
        }
    }
    
    /**
	 * Kiem tra du lieu hop le de cap nhat
	 */
	function act_check_data($p_news = 0)
    {
		$v_news_id = $this->_POST['txt_news_id'];
		if ($v_news_id  == '') {
            js_message('Bạn chưa nhập ID bài viết');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_news_id.focus()');         
            return false;
        }
		// id bai viet phai la so duong
		if (!preg_match( '/^\d+$/', $v_news_id)) {
			js_message('ID bài viết phải là số nguyên dương');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_news_id.focus()');            
			return false;
		} 
		// kiem tra bai viet ton tai
		$rs_single_news = be_get_single_news($v_news_id);
		if(count($rs_single_news) ==0) {
			js_message('ID bài viết không tồn tại');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_news_id.focus()');         
            return false;
		}
		
		if ($this->_POST['txt_title'] == '') {
            js_message('Bạn chưa nhập Title');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_title.focus()');            
            return false;
        }
		if ($this->_POST['txt_desc'] == '') {
            js_message('Bạn chưa nhập Description');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_desc.focus()');            
            return false;
        }
		// kiem tra trang thai xuat ban
		if ($this->_POST['sel_trang_thai_xuat_ban'] =='' && $this->getPerm('admin,publish')) {
			js_message($this->arr_error_msg['empty_publish_status']); 
			return false;
		}
        // kiem tra chon thiet bi
		if ($this->_POST['sel_thiet_bi'] == '') {
            js_message('Bạn chưa chọn thiết bị');
            js_set('parent.document.frm_update_seo_chi_tiet.loc_thiet_bi.focus()');
            return false;
        }
        // Begin TungVN 02-11-2017 - bo_sung_bat_buoc_tich_chon_chu_de_toi_uu_seo_bai_viet
		if ($this->_POST['hdn_tong_so_chu_de'] > 0 && !check_array($this->_POST['chk_chu_de'])) {
            js_message('Bạn chưa chọn chủ đề');
            js_set('parent.document.frm_update_seo_chi_tiet.txt_chu_de.focus()');
            return false;
        }
        // End TungVN 02-11-2017 - bo_sung_bat_buoc_tich_chon_chu_de_toi_uu_seo_bai_viet
        return true;
    }
    
    /**
	* Chuc nang cap nhat thong tin bai viet
	*/
	function act_update_seo_chi_tiet($p_seo_chi_tiet_bai_viet_id = 0)
    {
        if (!$this->getPerm('admin,edit')) {
			js_message($this->arr_error_msg['invalid_access']);
			exit;
		}
        $v_user_id = $_SESSION['user_id'];
        $p_seo_chi_tiet_bai_viet_id = (int)$p_seo_chi_tiet_bai_viet_id;
        $this->act_prepare_data();
        if ($this->act_check_data($p_seo_chi_tiet_bai_viet_id)) {
            //write log
            if($p_seo_chi_tiet_bai_viet_id > 0){
               $this->act_update_seo_chi_tiet_history($p_seo_chi_tiet_bai_viet_id, "Hiệu chỉnh");        
            }
			// lay danh sach alt cua anh
			$v_total_image = (int)$this->_POST["hdn_total_image"];
			$v_arr_alt = array();
			for($i= 0; $i <	$v_total_image; $i++) {
                /* Begin anhpt1 21/4/201 chinh_title_seo_chi_tiet_bai_viet */
				$v_arr_alt[] = $this->_POST["txt_alt_anh".$i];
                /* End anhpt1 21/4/201 chinh_title_seo_chi_tiet_bai_viet */
			}
			$v_danh_sach_alt_anh = '';
			if(check_array($v_arr_alt)) {
				$v_danh_sach_alt_anh = implode("~", $v_arr_alt);
			}
             
            /* begin anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
            $v_on_off_title = _get_module_config('news','v_on_off_title_desc_mxh');
            if(!$v_on_off_title){
                $this->_POST['txt_title_mxh'] = '';
                $this->_POST['txt_des_mxh'] = '';
            }
            /* End anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */	
            
            //pre data
            $data = array(
				"id"=>$p_seo_chi_tiet_bai_viet_id,
                "pk_news"=>(int)$this->_POST["txt_news_id"]
				,"c_sapo"=>$this->_POST["txt_sapo"]
				,"c_title"=>$this->_POST["txt_title"]
				,"c_desc"=>$this->_POST["txt_desc"]
				,"c_keyword"=>$this->_POST["txt_keyword"]
				,"c_slug"=>_url_text($this->_POST["txt_slug"],'')
				,"c_canonical"=>$this->_POST["txt_canonical"]
				,"c_trang_thai_xuat_ban"=>(int)$this->_POST["sel_trang_thai_xuat_ban"]
				,"c_danh_sach_alt_anh"=>$v_danh_sach_alt_anh 
				,"c_tu_khoa_in_nghieng"=>$this->_POST["txt_tu_khoa_in_nghieng"]
				,"c_tu_khoa_in_dam"=>$this->_POST["txt_tu_khoa_in_dam"]
				,"c_tu_khoa_gach_chan"=>$this->_POST["txt_tu_khoa_gach_chan"]
				,"c_nguoi_sua"=> $v_user_id 
                ,'c_thiet_bi'=>intval($this->_POST['sel_thiet_bi'])
                // begin 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
                ,'c_title_mxh'=>$this->_POST['txt_title_mxh']
                ,'c_des_mxh'=>$this->_POST['txt_des_mxh']
                // end 09/03/2016 tuyennt xay_dung_chuc_nang_nhap_title_des_mxh
                /* Begin 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
                ,'c_tu_dong_ha_xuat_ban'=> intval($this->_POST['chk_tu_dong_ha_xuat_ban'])
                ,'c_ngay_ha_xuat_ban'=> _sql_format_date($this->_POST['txt_ngay_ha_xuat_ban'])
                /* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
                //Begin 12/5/2020 AnhTT bo_sung_tich_amp
                ,'c_add_amp'=> intval($this->_POST['chk_add_amp'])
                //Begin 12/5/2020 AnhTT bo_sung_tich_amp
				);
            //Begin 12/5/2020 AnhTT bo_sung_tich_amp
            if(!$data['c_add_amp']){
                $data['c_add_amp'] = '';
            }
            //Begin 12/5/2020 AnhTT bo_sung_tich_amp
            /* Begin 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
            //Nếu bỏ tự động hạ XB thì cũng không lưu ngày
            if(!$data['c_tu_dong_ha_xuat_ban']){
                $data['c_ngay_ha_xuat_ban'] = '';
            }
            /* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
            /* begin anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
            $data['c_anh_chia_se_mxh'] = '';
            if($v_on_off_title){
                if ($_FILES['file_chia_se_mxh']['name']!='') {
                    $v_upload_obj = new upload_image_block();
                    $module_config = _get_module_config('news','anh_chia_se_mxh');
                    //pre($module_config);die;
                    $v_file_uploaded = 	$v_upload_obj->act_upload_single_image($_FILES['file_chia_se_mxh'], $module_config['max_size'], $module_config['kich_thuoc'],'','',true);
                    if (count($v_file_uploaded['errors']) > 0) {
                        js_message($v_file_uploaded['errors'][0]);
                        exit();
                    } else {
                        // VinhLQ thực hiện đóng dấu vào ảnh
                        $v_watermark_image = watermark_image($v_file_uploaded['file_path']);
                        if($v_watermark_image) {
                            $data['c_anh_chia_se_mxh'] = $v_watermark_image;
                        } else {
                            $data['c_anh_chia_se_mxh'] = $v_file_uploaded['file_path'];
                        }
                    }
                } else {
                    $data['c_anh_chia_se_mxh'] = $this->_POST['hdn_anh_chia_se_mxh'];	
                }
            }
            $v_arr_single= array();
            if($data['id'] > 0){
                $v_arr_single = be_get_single_seo_chi_tiet_bai_viet($data['id']);
            }
            /* End anhpt1 19/4/2016 on_off_chuc_nang_title_des_ocm */
            //call update
			$v_result = be_update_seo_chi_tiet_bai_viet(
				$data['id']
                ,$data['pk_news']
				, $data['c_sapo']
				, $data['c_title']
				, addslashes(_utf8_to_ascii($data['c_title']))
				, $data['c_desc']
				, $data['c_keyword']
				, $data['c_slug']
				, $data['c_canonical']
				, $data['c_trang_thai_xuat_ban']
				, $data['c_danh_sach_alt_anh']
				, $data['c_tu_khoa_in_nghieng']
				, $data['c_tu_khoa_in_dam']
				, $data['c_tu_khoa_gach_chan']
				, $data['c_nguoi_sua']
                , $data['c_thiet_bi']
				, $data['c_anh_chia_se_mxh']
                , $data['c_title_mxh']
                , $data['c_des_mxh']
                , $data['c_tu_dong_ha_xuat_ban']
                , $data['c_ngay_ha_xuat_ban']
                , $data['c_add_amp']
				);
			$v_new_id =  $v_result['c_id'];
            if ($v_new_id > 0) {     
                js_message($this->arr_error_msg['update_success']);	
                $v_news_id = intval($data['pk_news']);
                $v_user_id = intval($_SESSION['user_id']);
                $v_canonical_update = false;
                if($data['id'] > 0){
                    if($v_arr_single['c_canonical'] != $data['c_canonical']){
                        $v_canonical_update =true;
                    }
				}			
                if ($this->_POST['goback']) {
                    js_redirect(fw24h_base64_url_decode($_POST['goback']));
                } else {
                    js_redirect(BASE_URL.$this->className().'/index');
                }
            } else {
                $v_message = $v_result['RET_ERROR'] =='ERROR_TRUNG_BAI_VIET_THIET_BI'?'Bài viết và thiết bị cần cập nhật đã tồn tại, thao tác này không thực hiện được.':$rs_result['RET_ERROR'];                             
                js_message($v_message);				
			}
        }
		//hien thi cac nut lenh
        js_set('window.parent.set_enable_link("tr_button")');
        exit();
    }
    
    /**
	 * Thuc thi lenh xoa cac doi tuong duoc chon tren man hinh danh sach
	 */
    function act_delete_seo_chi_tiet()
    {
		if (!$this->getPerm('admin,delete')) {
			js_message($this->arr_error_msg['invalid_access']);
			exit;
		}
		$v_rows = intval($_REQUEST["hdn_record_count"]);
		$count = 0;		
		$v_check_empty = 0;
		$v_error_message = '';
        for ($i=0; $i < $v_rows; $i++) {
			$v_id = intval($_REQUEST["chk_item_id".$i]);					
			if ($v_id > 0) {
				$v_check_empty  = 1;
                //chuan bi du lieu ghi log
				$rs_single_seo_chi_tiet = be_get_single_seo_chi_tiet_bai_viet($v_id);                                              
				$rs_single_seo_chi_tiet['c_loai_hanh_dong'] = "Xóa dữ liệu";				
				$this->act_update_seo_chi_tiet_history($v_id,'Xóa dữ liệu');
				$rs_result = be_delete_seo_chi_tiet_bai_viet($v_id);				
				if($rs_result['OK']=='OK') {
					//ghi log ban ghi vua xoa
					be_cap_nhat_lich_su($v_id, 'seo_chi_tiet_su_kien', $rs_single_seo_chi_tiet, $_SESSION['user']);
					$count++;
				} else {
					$v_error_message.= $rs_result['RET_ERROR'].',';
				} 
			}
		}
		// thong bao loi
		if($v_error_message !='') {
			js_message('Không xóa được bài viết đã xuất bản.');
		}
        if ($v_check_empty == 0) {
			js_message($this->arr_error_msg['empty']);
			die;
		}
		if($count>0) {
			js_message($this->arr_error_msg['delete_success'].$count.' seo title, des, keyword!');
		} else {
			die;
		}
		if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        } 
	}
  
    /**
	 * Cap nhat lich su sua doi text link
	 */		
	function act_update_seo_chi_tiet_history($p_seo_chi_tiet_bai_viet_id, $p_action_type)
	{
        $p_seo_chi_tiet_bai_viet_id = intval($p_seo_chi_tiet_bai_viet_id);
		if ($p_seo_chi_tiet_bai_viet_id <= 0) {
			return false;
		}
		$rs = be_get_single_seo_chi_tiet_bai_viet($p_seo_chi_tiet_bai_viet_id);                 
        $rs['c_loai_hanh_dong'] = $p_action_type;
        //begin: xu_ly_loi_khong_ghi_lich_su_seo_chi_tiet_bai_viet
        // phuonghv add 18/11/2015, thay biến $p_news_id = $p_seo_chi_tiet_bai_viet_id
        be_cap_nhat_lich_su($p_seo_chi_tiet_bai_viet_id, 'seo_chi_tiet_bai_viet', $rs, $_SESSION['user']);
        //end: xu_ly_loi_khong_ghi_lich_su_seo_chi_tiet_bai_viet
	}
	
    /**
	* Thuc hien cap nhat cac bai viet thay doi tren man hinh danh sach
	*/
    function act_update_seo_chi_tiet_list()
    {
		if (!$this->getPerm('admin,publish')) {
			js_message($this->arr_error_msg['invalid_access']);
			exit;
		}
        /* Begin 15/05/2017 LUANAD XLCYCMHENG_20390_bo_sung_nut_ha_xb_nhanh_seo_bai_viet */
        $action_type = $_REQUEST['action_type'] ? fw24h_replace_bad_char($_REQUEST['action_type']) : '';
        /* End 15/05/2017 LUANAD XLCYCMHENG_20390_bo_sung_nut_ha_xb_nhanh_seo_bai_viet */
		$v_rows = intval($_REQUEST["hdn_record_count"]);
		$v_user_id = $_SESSION['user_id'];		
        $v_error_message = '';       
        $rs_seo_chi_tiet_checked = array();
        for ($i=0; $i < $v_rows; $i++) {
			$v_id = intval($_REQUEST["chk_item_id".$i]);					
			if ($v_id > 0) {
                /* Begin 15/05/2017 LUANAD XLCYCMHENG_20390_bo_sung_nut_ha_xb_nhanh_seo_bai_viet */
                $v_trang_thai_xuat_ban = ($action_type == 'quick_unpublish') ? 0 : intval($_REQUEST["sel_publish".$i]);
                /* End 15/05/2017 LUANAD XLCYCMHENG_20390_bo_sung_nut_ha_xb_nhanh_seo_bai_viet */
				$rs_seo_chi_tiet_checked[] = array(
                    "id"=>$v_id
                    ,"c_trang_thai_xuat_ban"=>$v_trang_thai_xuat_ban
					,"c_nguoi_sua"=> $v_user_id
                    );
            }
		}    
		$v_rows = count($rs_seo_chi_tiet_checked);
        if ($v_rows == 0) {
			js_message($this->arr_error_msg['no_data_change']);			
			die;
		}
       
        // thuc hien cap nhat
		$v_count = 0;
        for ($i=0; $i < $v_rows; $i++) {
			$data = $rs_seo_chi_tiet_checked[$i];
            // cap nhat lich su
            $this->act_update_seo_chi_tiet_history($data['id'],'Thay đổi trạng thái');
            // cap nhat thay doi
			$rs_single_seo_chi_tiet = be_get_single_seo_chi_tiet_bai_viet($data['id']);		
            
			$v_result = be_update_trang_thai_seo_chi_tiet_bai_viet(
				$data['id']				
				, $data['c_trang_thai_xuat_ban']				
				, $data['c_nguoi_sua']                
			);
            if($v_result['RET_ERROR'] == ''){
               $v_count++;
            }
        }
		js_message('Lưu thành công '.$v_count.' title,desc,key bài viết');
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        }
	}
    /* Begin anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
    /**
     * Hàm tạo và xuất file excel đánh giá sản phẩm độc giả
    */		
    function export_seo_chi_tiet_bai_viet(){
        $v_goback = fw24h_replace_bad_char($_GET['goback']);
        $this->getRequest();
        $v_published_date = _sql_format_date($this->_GET['txt_published_date']);
		$v_published_to_date = _sql_format_date($this->_GET['txt_published_to_date']);
        $v_category_id = $this->_GET['sel_category_id'];
        $v_so_items_keyword = _get_module_config('news', 'v_so_items_export_seo_chi_tiet_bai_viet');
        html_tao_file_excel_export_seo_chi_tiet_bai_viet($v_so_items_keyword,$this->_GET);
        if ($v_goback != ''){
            js_redirect(fw24h_base64_url_decode($v_goback));
        }
        die;
    }
	/* begin anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
	/**
	 * Hàm tạo và xuất file excel pageview theo tin bài
	*/
	function export_excel_pageview_tin_bai(){
		$this->getRequest();
		$v_goback = fw24h_replace_bad_char($_GET['goback']);
		$v_category_id = fw24h_replace_bad_char($this->_GET['sel_category_id']);
		$v_selected_user = fw24h_replace_bad_char($this->_GET['sel_user_id']); 
		$v_selected_status = fw24h_replace_bad_char($this->_GET['sel_status']); 
		$v_ten_bai_viet = _utf8_to_ascii(fw24h_replace_bad_char($this->_GET['txt_news_name']));
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$v_tieu_de_seo = _utf8_to_ascii(fw24h_replace_bad_char($this->_GET['txt_news_name_seo'])); // Tieu de SEO bai viet
        $v_tieu_de_seo  = $v_tieu_de_seo == 'Nhap tieu de SEO' ? '' : $v_tieu_de_seo;
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$v_id_bai_viet =  fw24h_replace_bad_char($this->_GET['txt_news_id']);
		$v_sort_type = fw24h_replace_bad_char($this->_GET['sel_sorttype']);
		$v_thiet_bi_id = fw24h_replace_bad_char($this->_GET['sel_thiet_bi_filter']);
		$v_page = 0;
		$v_number_per_page = 0;
		$v_number_per_page = fw24h_replace_bad_char($this->_GET['number_per_page']);
		$v_order_by = ' a.c_thoi_gian_sua DESC';
		$v_ten_bai_viet  = ($v_ten_bai_viet =='Nhap ten bai viet')? '':$v_ten_bai_viet ;
		$v_edit_date_start = _sql_format_date(fw24h_replace_bad_char($this->_GET['txt_edit_date_start']));
		$v_edit_date_end = _sql_format_date(fw24h_replace_bad_char($this->_GET['txt_edit_date_end']));
        // Begin TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		$v_arr_items  = be_get_all_seo_chi_tiet_bai_viet(
                ($v_category_id ==0)?'':$v_category_id, 
                $v_selected_user, 
                ($v_selected_status <=0)?'':$v_selected_status, 
                $v_ten_bai_viet, 
                addslashes($v_tieu_de_seo),
                $v_id_bai_viet, 
                $v_order_by, 
                $v_thiet_bi_id, 
                $v_edit_date_start, 
                $v_edit_date_end, 
                $v_page, 
                $v_number_per_page);
        // End TungVN 02-10-2017 - bo_sung_phan_loc_tim_theo_tieu_de_seo_bai_viet
		tao_file_excel_export_pageview_tin_bai($v_arr_items, $v_edit_date_start, $v_edit_date_end);
		 if ($v_goback != ''){
			 js_redirect(fw24h_base64_url_decode($v_goback));
		 }
		die;
	}
	/* End anhpt 07/11/2016 export_seo_chi_tiet_bai_viet */
    /*
    * hien thi man hinh import du lieu tu file excel
    */
    function dsp_import_seo_chi_tiet_bai_viet(){

        if (!$this->getPerm('admin,view,edit')) {
            js_message($this->arr_error_msg['invalid_access']);
            exit;
        }
        $this->act_prepare_data();
        $this->getRequest();
        $v_status = intval($this->_GET['sel_status']);
        $v_page = $this->_GET['page'];
        $v_number_per_page = $this->_GET['number_per_page'];

        $v_seo_config = _get_module_config('news', 'seo_chi_tiet_bai_viet_conf');    
        $v_folder_dich = $v_seo_config['THU_MUC_CHUA_FILE_EXCEL'];
        $v_max_column = $v_seo_config['SO_COT_TOI_DA'];
        $v_file_excel_mau = $v_seo_config['FILE_EXCEL_MAU'];
        $v_arr_items = array();
        if ($_FILES['file_import']['name']!='') {
            $v_filename = $_FILES['file_import']['name'];
            // check file extenstion is .xls, xlsx
            $rs_ext = explode('.', $v_filename);
            $v_ext = $rs_ext[count($rs_ext) -1];
            if(!in_array($v_ext, array('xls','xlsx'))){
                $v_message_error = 'Định dạng file không đúng file excel (.xls,xlsx)';
            }
			$v_time = time();
            if($v_filename!="" && $v_message_error == ''){
                copyFile($_FILES['file_import']['tmp_name'], $v_folder_dich.'/'.$v_time.$v_filename);
                
                $v_file_path = $v_folder_dich.'/'.$v_time.$v_filename;
                if (!file_exists($v_file_path)) {
                    exit("File dữ liệu không tồn tại\n");
                }
            }
        } else if($v_page  == 1){
            if($this->_POST['hdn_file_import'] =='') {
                $v_message_error = 'Bạn chưa chọn file excel import.';
            }
        }
        // doc file excel
        $v_arr_data = array();    
        $v_session_id = isset($_SESSION['session_id'])? $_SESSION['session_id']:time();      
        if ($v_file_path !='') {
            $objReader = ($v_ext =='xls')? PHPExcel_IOFactory::createReader('Excel5'):PHPExcel_IOFactory::createReader('Excel2007');
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($v_file_path);
            $objWorksheet = $objPHPExcel->getSheet(0);
            $highestRow = $objWorksheet->getHighestRow(); // e.g. 10
            $highestColumn = $objWorksheet->getHighestColumn(); // e.g 'F'
            $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); // e.g. 5
            $rs_data_import = array();
            // Lấy số dòng tối đa được import
            $v_so_max_dong_import = _get_module_config('keyword_link','v_so_max_dong_import_keyword_link');
            if($highestColumnIndex  != $v_max_column){
                $v_message_error = 'File excel không đủ các cột quy định';
                unset($_SESSION['session_id']);
            }else if($highestRow > $v_so_max_dong_import){
                $v_message_error = 'File excel vượt quá số dòng cho phép. tối đa '.$v_so_max_dong_import.' dòng';
                unset($_SESSION['session_id']);
            } else {                            
                $v_arr_column = array();                        
                for($i=0 ; $i<$highestColumnIndex; $i++){
                    $v_ten_cot = $objWorksheet->getCellByColumnAndRow($i, 1)->getCalculatedValue();       
                    $v_arr_column[$i]['c_ten_cot'] = $v_ten_cot;
                }
                // tao mang du lieu                        
                $i=0;    
                for ($v_row = 2; $v_row <= $highestRow; ++$v_row) {
                    for($v_col=0 ; $v_col<$highestColumnIndex; $v_col++) {                       
                        $v_value = $objWorksheet->getCellByColumnAndRow($v_col, $v_row)->getCalculatedValue();                                                                       
                        $v_arr_data[$i][$v_arr_column[$v_col]['c_ten_cot']] = trim($v_value);                        
                    }
                    $i++;
                }
            }
            $_SESSION['session_id'] = $v_session_id;
        }
        if (check_array($v_arr_data)) { 
            $v_error = '';
            $v_arr_thiet_bi_check = _get_module_config('news', 'v_arr_thiet_bi');
            // Xóa Trước khi cập nhật lại
            be_delete_seo_chi_tiet_bai_viet_temp($v_session_id);
            foreach($v_arr_data as $v_row) {               
                $v_id_bai_viet = intval($v_row['id_bai_viet']);
                $v_title = fw24h_replace_bad_char($v_row['Title']);
                $v_desc = fw24h_replace_bad_char($v_row['Description']);
                $v_keyword = fw24h_replace_bad_char($v_row['Keyword']);
                $v_slug = fw24h_replace_bad_char($v_row['Slug']);
                $v_ds_id_thiet_bi = fw24h_replace_bad_char($v_row['Thiet_bi']);
                $v_error_message = '';
                $v_trang_thai_du_lieu = ($this->act_valid_data_import($v_row, $v_error_message)? 1: 0);
                $v_error_message = ($v_error_message=='')? '':fw24h_replace_bad_char($v_error_message);
                if($v_ds_id_thiet_bi != ''){
                    $v_arr_thiet_bi = explode(',', $v_ds_id_thiet_bi);
                    if(check_array($v_arr_thiet_bi)){
                        for($i = 0;$i<count($v_arr_thiet_bi);$i++){
                            $v_id_thiet_bi = intval($v_arr_thiet_bi[$i]);
                            if($v_id_thiet_bi == 0 || !in_array($v_id_thiet_bi, $v_arr_thiet_bi_check)){ 
                                $v_trang_thai_du_lieu = 0;
                                $v_error_message = 'Thiết bị bạn nhập không đúng dịnh dạng';
                            }
                            $v_result = be_update_seo_chi_tiet_bai_viet_temp(
                                $v_session_id
                                ,$v_id_bai_viet
                                ,$v_title
                                ,$v_desc
                                ,$v_keyword
                                ,$v_slug
                                ,$v_id_thiet_bi
                                ,$_SESSION['user_id']
                                ,$v_trang_thai_du_lieu
                                ,$v_error_message);
                        }
                    }
                }else{
                    $v_id_thiet_bi = 0;
                    $v_trang_thai_du_lieu = 0;
                    $v_result = be_update_seo_chi_tiet_bai_viet_temp(
                                $v_session_id
                                ,$v_id_bai_viet
                                ,$v_title
                                ,$v_desc
                                ,$v_keyword
                                ,$v_slug
                                ,$v_id_thiet_bi
                                ,$_SESSION['user_id']
                                ,$v_trang_thai_du_lieu
                                ,$v_error_message);
                }    
                
                if ($v_result['RET_ERROR'] != '') { 
                    $v_error .= 'seo chi tiet bài viêt không cập nhật được '.$v_id_bai_viet.' /n';
                }
            }
            if($v_error != ''){
                js_message($v_error);
            }
        }        
        
        $v_session_id = $_SESSION['session_id'];
        $v_arr_items = array();
        if ($v_session_id!='') {
            $v_arr_items = be_get_all_seo_chi_tiet_bai_viet_temp($v_session_id,1,$v_page, $v_number_per_page);          
            $v_tong_so =  count($v_arr_items);
        }
        if ($v_session_id!='') {
            $v_arr_items_khong_hop_le = be_get_all_seo_chi_tiet_bai_viet_temp($v_session_id,0,$v_page, $v_number_per_page);          
            $v_tong_so_khong_hop_le =  count($v_arr_items_khong_hop_le);
        }
        $v_goback = $_REQUEST['goback'];
        $v_error_message = '';
        $this->setParam('v_arr_device', get_list_pr_device());
        $this->setParam('v_arr_trang_thai', get_list_trang_thai_xuat_ban());        
        $this->setParam('v_goback', $v_goback);
        $this->setParam('v_arr_trang_thai_du_lieu', get_list_trang_thai_du_lieu());
        $this->setParam('v_arr_items', $v_arr_items);
        $this->setParam('v_arr_items_khong_hop_le', $v_arr_items_khong_hop_le);
        $this->setParam('v_record_count', $v_tong_so);
        $this->setParam('v_record_count_khong_hop_le', $v_tong_so_khong_hop_le);
        $this->setParam('phan_trang_hop_le', _db_page($v_tong_so, $v_page, $v_number_per_page));
        $this->setParam('phan_trang_khong_hop_le', _db_page($v_tong_so_khong_hop_le, $v_page, $v_number_per_page));
        $this->setParam('v_file_name', $v_file_name);
        $this->setParam('v_message_error', $v_message_error);
        $this->setParam('v_total_rows', intval($v_arr_items[0]['c_tong_so_dong']));
        $this->setParam('v_total_rows_valid', intval($v_arr_items[0]['c_so_dong_hop_le']));
        $this->setParam('v_total_rows_invalid', intval($v_arr_items[0]['c_so_dong_khong_hop_le']));
        $this->setParam('v_file_excel_mau', $v_file_excel_mau);
        $this->render($this->thisPath().'view/dsp_import_seo_chi_tiet_bai_viet.php');
    }
    function act_valid_data_import($p_data, &$v_error_message) {
        // Kiểm tra keyword link
        $v_id_bai_viet = $p_data['id_bai_viet'];
        $v_error_message='';
		if ($v_id_bai_viet == '') {       
            $v_error_message.='Bạn chưa nhập Id bài viết;';
            return false;
        }
        // id bai viet phai la so duong
		if (!preg_match( '/^\d+$/', $v_id_bai_viet)) {
            $v_error_message.='ID bài viết phải là số nguyên dương;';     
			return false;
		} 
        $rs_single_news = be_get_single_news(intval($v_id_bai_viet));
        if(count($rs_single_news) ==0) {
            $v_error_message.='ID bài viết không tồn tại;';
            return false;
        }
        // kiểm tra nhập title
        $v_title = $p_data['Title'];
        if ($v_title == '') {       
            $v_error_message.='bạn chưa nhập title;';
            return false;
        }
        
        if (strlen($v_title) > 90) {       
            $v_error_message.='Title không được nhập quá 90 kí tự;';
            return false;
        }     
        // kiểm tra nhập desc
        $v_desc = $p_data['Description'];
        if ($v_desc == '') {       
            $v_error_message.='bạn chưa nhập Desc;';
            return false;
        }
        // kiểm tra nhập desc
        $v_desc = $p_data['Description'];
        if (strlen($v_desc) > 250) {       
            $v_error_message.='Desc không được nhập quá 250 kí tự;';
            return false;
        }
        $v_slug = $p_data['Slug'];
        if (strlen($v_slug) > 300) {       
            $v_error_message.='Slug không được nhập quá 300 kí tự;';
            return false;
        }
        // kiểm tra thiết bị
        $v_thiet_bi = $p_data['Thiet_bi'];
        if ($v_thiet_bi == '') {       
            $v_error_message.='bạn chưa nhập thiết bị;';
            return false;
        }
        return true;
    }
    function act_update_seo_chi_tiet_bai_viet_import($p_import_tat_ca = 0){
        $v_import_tat_ca = intval($p_import_tat_ca);
        $v_session_id = $_SESSION['session_id'];
        $v_count_items = 0;
        // Nếu là import tất cả
        if($v_import_tat_ca  == 1 ){
            $v_result = be_update_all_seo_chi_tiet_bai_viet_duoc_export();
        }else{
            // lấy số lượng bản ghi được import
            $v_count = intval($_POST['hdn_record_count']);
            for ($i=0; $i < $v_count; $i++) {      
                if (isset($_POST['chk_item_id'.$i]) && intval($_POST['chk_item_id'.$i]) > 0) {                   
                    $v_id_temp = intval($_POST['chk_item_id'.$i]);
                    $v_arr_items = be_get_seo_chi_tiet_bai_viet_tmp_theo_id($v_id_temp);
                    $v_id_news = intval($v_arr_items['c_id_bai_viet']);
                    $v_title = $v_arr_items['c_title'];
                    $v_desc = $v_arr_items['c_desc'];
                    $v_keyword = $v_arr_items['c_keyword'];
                    $v_slug = $v_arr_items['c_slug'];
                    $v_thiet_bi = intval($v_arr_items['c_thiet_bi']);
                    $v_result = be_update_seo_chi_tiet_bai_viet_import($v_id_news,$v_title,$v_desc,$v_keyword,$v_slug,$_SESSION['user_id'],$v_thiet_bi);
                    if ($v_result['RET_ERROR'] =='') { 
                        $v_count_items =  $v_count_items + 1;
                        $v_id_seo_chi_tiet = $v_result['c_id'];
                        $this->act_update_seo_chi_tiet_history($v_id_seo_chi_tiet,'chuc nang import');
                    }
                }    
            }
        }
        if($v_result['RET_ERROR'] ==''){
            js_message('cập nhật thành công seo chi tiet bai viet' );
            be_delete_seo_chi_tiet_bai_viet_temp($_SESSION['session_id']); 
        }
        unset($_SESSION['session_id']);   
        if ($this->_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        } else {
            js_redirect(BASE_URL.$this->className().'/index');
        }
    }
    /**
	 * Hien thi box nut buton
	 */
	function dsp_form_button_import()
	{
		return $this->render($this->thisPath().'view/dsp_form_button_import.php');
	}
    /* End anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
}