<?php
__get_db_functions('db.user');
__get_db_functions('db.news');
__get_db_functions('db.category');
__get_db_functions('db.general');
include_once WEB_ROOT.'editor/ckeditor/index.php';
fw24h_add_module_function('news');
class news_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
	);
	// khai bao danh sach cac bien = _REQUEST
	var $_arr_arg  = array (
		'sel_category_id' => array(-1, 'intval')
		,'txt_category_id' => array('Nhập chuyên mục', 'fw24h_replace_bad_char')
		,'txt_news_id' => array(-1, 'intval')
		,'sel_user_id' => array(0, 'intval')
		,'txt_user_id' => array('', 'fw24h_replace_bad_char')
        ,'sel_status' => array(-1, 'intval')
		,'txt_status' => array('', 'fw24h_replace_bad_char')
        ,'txt_news_name'=>array('', 'fw24h_replace_bad_char')
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
	 * Hien thi man hinh soan thao tin bai
	 */
	function index()
    {
        if (!$this->getPerm('admin')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			js_set('top.history.back()');
			exit;
		}
        $this->getRequest();
        
		//selected category	
		$v_category_id = $this->_GET['sel_category_id'];
		//selected user
		$v_selected_user = $this->_GET['sel_user_id']; 		
		$v_status = $this->_GET['sel_status'];  
        $v_news_name = _utf8_to_ascii($this->_GET['txt_news_name']);  
        $v_news_id = $this->_GET['txt_news_id'];  
		$v_is_dropdown_menu_mobile = $this->_GET['chk_dropdown_menu_mobile'];
		$v_is_menu_mobile = $this->_GET['chk_menu_mobile'];		 
		$v_is_trang_danh_ba = $this->_GET['chk_trang_danh_ba'];		 
        $page = $this->_GET['page'];
        $number_per_page = $this->_GET['number_per_page']; 
		//Begin Thangnb 02-03-2016 : bo_sung_tich_chon_hien_thi_chuyen_muc
		$v_is_show_on_pc =  $this->_GET['chk_is_show_on_pc'];
        
        $v_arr_items = be_get_all_news(
            $v_news_id
            ,$v_category_id
            ,$v_selected_user
            ,$v_status
            ,$v_news_name
        );
        //pre($v_arr_items);
        
        
        $v_arr_trang_thai =  get_list_trang_thai_xuat_ban();
		$rs_chuyen_muc_xb = be_get_all_category_by_select(-1, -1, -1);
        $this->setParam('v_arr_category_by_select',$rs_chuyen_muc_xb['data']);
		$v_news = array();
		$this->setParam('v_news_id', $v_news_id);
		$this->setParam('v_news_name', $v_news_name);
		$this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
        
        $this->setParam('v_arr_items', $v_arr_items);
		$this->setParam('v_record_count', count($v_arr_items));
        $this->setParam('phan_trang', _db_page(count($v_arr_items), $page, $number_per_page));
		
		$this->setParam('goback', $this->_getCurrentUri());
        
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
    
    function dsp_single_item($p_news_id = 0)
    {
        if (!$this->getPerm('admin')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			js_set('top.history.back()');
			exit;
		}
        $v_news = array();
        $p_news_id = intval($p_news_id);
        $v_news_type = isset($_GET['type']) ? fw24h_replace_bad_char($_GET['type']) : 'news';
        if($p_news_id > 0){
            $v_news = be_get_single_news($p_news_id);
            $v_news_type = intval($v_news['Type']) == 2 ? 'magazine' : '';
        }
        $v_arr_trang_thai =  get_list_trang_thai_xuat_ban();
		$rs_chuyen_muc_xb = be_get_all_category_by_select(-1, -1, -1);
        $this->setParam('rs_chuyen_muc_xb',$rs_chuyen_muc_xb['data']);
		$this->setParam('v_news', $v_news);
		$this->setParam('v_news_type', $v_news_type);
		$this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
		
		$this->render($this->thisPath().'view/dsp_single_item.php');
	}
	function act_update_news($p_action='SAVE', $p_news_id=0)
    {
		if ($_POST) {
			$this->act_prepare_news_data();
            $v_upload_obj = new upload_image_block();
			$anh_dai_dien_config = _get_module_config('news','anh_dai_dien');
			if ($_FILES['file_summary_image_chu_nhat']['name']!='') {
				//  12-01-2021 DanNC begin cắt tên file anh quá dài
				$_FILES['file_summary_image_chu_nhat']['name'] = substr($_FILES['file_summary_image_chu_nhat']['name'], -50,50);
				//  12-01-2021 DanNC end cắt tên file anh quá dài
				$v_size_anh_dai_dien_news = lay_dung_luong_anh_dai_dien_bai_viet_dang_gif($_FILES['file_summary_image_chu_nhat']['name']);
				//phuonghv sua ngay 26/03/2015
				$file_summary_image_chu_nhat = $v_upload_obj->act_upload_single_image($_FILES['file_summary_image_chu_nhat'], $v_size_anh_dai_dien_news, $anh_dai_dien_config['kich_thuoc']);
                // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
				if(check_anh_dang_gif_theo_duong_dan($file_summary_image_chu_nhat['file_path'])){
					$v_summary_image_chu_nhat_gif = $file_summary_image_chu_nhat['file_path'];
					$file_summary_image_chu_nhat['file_path'] = convert_image_gif_to_jpg($file_summary_image_chu_nhat['file_path']);
					// Tao anh thumbnail cho ảnh GIF
					if(!check_array($v_thumbnail_resize_chu_nhat)){
						$v_thumbnail_resize_chu_nhat = _get_module_config('news', 'thumnail_resize_chu_nhat');
					}
					// lấy cấu hình on/off resize gif
					$v_off_gif_resize_images = get_gia_tri_danh_muc_dung_chung('CONFIG_OPTIMIZER_IMAGES_UPLOAD', 'OFF_GIF_RESIZE_IMAGES_UPLOAD');
					if(check_array($v_thumbnail_resize_chu_nhat) && strtolower(trim($v_off_gif_resize_images))== 'false'){
					foreach ($v_thumbnail_resize_chu_nhat as $v_thumbnail => $v_arr_thumbnail_info) {
						$v_thumnail_gif = $v_upload_obj->act_create_thumbnail_gif($v_summary_image_chu_nhat_gif, $v_arr_thumbnail_info);
						if (count($v_thumnail_gif['errors']) > 0) {
							js_message('Ảnh đại diện:\n'.implode('\n', $v_thumnail_gif['errors']));
							// hien thi cac nut lenh
							js_set('window.parent.set_enable_link("tr_button")');
							exit();
						}
					}
					}
				}
				// End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
				if (count($file_summary_image_chu_nhat['errors']) > 0) {
					js_message('Ảnh đại diện:\n'.implode('\n', $file_summary_image_chu_nhat['errors']));
					// hien thi cac nut lenh
					js_set('window.parent.set_enable_link("tr_button")');
					exit();
				} else {
					$this->_POST['txt_summary_image_chu_nhat'] = $file_summary_image_chu_nhat['file_path'];
				}
			}
			//ảnh đại diện
            $v_anh_dai_dien_cm = '';
            if($this->_POST['file_background_news'] != ''){
                $this->_POST['txt_summary_image_chu_nhat_3_2'] = $this->_POST['file_background_news'];
                if ($_FILES['file_background_news']['name']!='') {
                    $v_arr_anh_dai_dien = _get_module_config('news', 'v_arr_background_news');
                    $v_max_size_anh_dai_dien = _get_module_config('news', 'v_max_size_arr_background_news');
                    $v_file_uploaded = 	$v_upload_obj->act_upload_single_image($_FILES['file_background_news'], $v_max_size_anh_dai_dien, $v_arr_anh_dai_dien,'','',true);
                    if (count($v_file_uploaded['errors']) > 0) {
                        js_message($v_file_uploaded['errors'][0]);
                        js_set('window.parent.set_enable_link("tr_button")');
                        exit();
                    } else {
                        $this->_POST['txt_summary_image_chu_nhat_3_2'] = $v_file_uploaded['file_path'];
                    }
                }
                $v_anh_dai_dien_cm = $this->_POST['c_background_news'];
                if($v_anh_dai_dien_cm == ''){
                    $this->_POST['txt_summary_image_chu_nhat_3_2'] = $v_anh_dai_dien_cm;
                }
            }else{
				$v_anh_dai_dien_cm = $this->_POST['c_background_news'];
                if($v_anh_dai_dien_cm != ''){
                    $this->_POST['txt_summary_image_chu_nhat_3_2'] = $v_anh_dai_dien_cm;
                }else{
					$this->_POST['txt_summary_image_chu_nhat_3_2'] = $this->_POST['hdn_background_news'];
				}
            }
			
			if(($p_action != 'SAVE') && $this->_POST['txt_summary_image_chu_nhat']=='' && $this->_POST['hdn_summary_image_chu_nhat_preview']=='') {
				js_message('Bạn chưa nhập ảnh đại diện '.$anh_dai_dien_config['kich_thuoc'][0].'x'.$anh_dai_dien_config['kich_thuoc'][1].' cho bài viết');
				exit();
			}
			if ($this->_POST['txt_title']=='') {
				js_message('Bạn chưa nhập tiêu đề bài viết!');
				exit();
			}
			if ($this->_POST['txt_body']=='') {
				js_message('Bạn chưa nhập nội dung bài viết!');
				exit();
			}
			$v_id_cm_banner_layout = intval($this->_POST['sel_chuyen_muc']);
			if (!isset($this->_POST['sel_chuyen_muc'])) {
				js_message('Bạn phải chọn chuyên mục bài viết!');
				exit();
			}
            $v_status = intval($this->_POST['sel_publish']);
			$v_news_id = be_update_news($this->_POST, $p_news_id, $_SESSION['user_id'], $v_status);
            if ($v_news_id > 0) {
                js_message('Cập nhật bài viết thành công!');
                if ($this->_POST['goback']) {
                    js_redirect(fw24h_base64_url_decode($_POST['goback']));
                } else {
                    js_redirect(BASE_URL.$this->className().'/index');
                }
            }
		}
	}
	/**
	 * Chuan bi du lieu $_POST de cap nhat
	 */
    function act_prepare_news_data()
    {
        if ($_POST) {
            $_POST['sel_dang_hien_thi'] = isset($_POST['sel_dang_hien_thi']) ? fw24h_replace_bad_char($_POST['sel_dang_hien_thi']) : '';
            foreach ($_POST as $k=>$v) {
                if (is_array($_POST[$k])) {
                    $this->_POST[$k] = $_POST[$k];
                } else {
                    $this->_POST[$k] = trim($_POST[$k]);
                }
            }
        }
		if(check_array($_FILES)){
			foreach ($_FILES as $k=>$v) {
				if($_FILES[$k]['name'] != ''){
					$_FILES[$k]['name'] = strtolower($_FILES[$k]['name']);
				}
			}
		}
    }
    /**
	 * Thuc thi lenh xoa cac doi tuong duoc chon tren man hinh danh sach
	 */
    function act_delete_news()
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
			$rs = be_delete_news_single($v_id);   
			if($rs['RET_ERROR'] !=''){
				$v_error_message.= 'Không xóa được bài viết ID :'.$v_id.' đã xuất bản\n'; 
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
			js_message('Đã xoá thành công '.$count.' bài viết!');
		}	
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        } 
	}
    function dsp_news_url($p_news_id = 0, $p_category_id = 0) {
        $p_news_id = intval($p_news_id);
        $p_category_id = intval($p_category_id);
        /* End anhpt1 24/06/2016 fix_loi_bao_mat_sql_injection */
		$arr_single_news = be_get_single_news($p_news_id);
		$arr_single_cat = be_get_single_category($arr_single_news['CategoryID']);

		$v_news_title = (trim($arr_single_news['SlugTitle'])!='') ? $arr_single_news['SlugTitle'] : $arr_single_news['Title'];
		$v_urlslugs = (trim($arr_single_cat['Urlslugs'])!='') ? $arr_single_cat['Urlslugs'] : $arr_single_cat['Name'];
        $urlHelper = new UrlHelper();$urlHelper->getInstance();
        $urlHelper->_BASE_URL = '';
        $v_url = SEO_DOMAIN.$urlHelper->url_news(array('ID'=>$p_news_id, 'cID'=>$p_category_id, 'slug'=>$v_urlslugs.'/'.$v_news_title));
        
		$this->setParam('arr_single_news', $arr_single_news);
		$this->setParam('v_url', $v_url);
        
		$this->render($this->thisPath().'view/dsp_news_url.php');
		$v_html = $this->blockContent;
		$v_html = str_replace(array("\n","\n\r","\r"),'',$v_html);
		$v_html = str_replace(array("'"),'&apos;',$v_html);
        $v_height = 300;
        if($v_url_preview_pr != ''){
            $v_height = 350;
        }
		js_set('top.show_box_popup(\''.$v_html.'\', 650,'.$v_height.')');
		die;
	}
}
