<?php
__get_db_functions('db.user');
__get_db_functions('db.category');
__get_db_functions('db.news');
__get_db_functions('db.general');
/* Begin 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */
__get_db_functions('db.loai_danh_muc');
/* End 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */
/* Begin: 13-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
include_once WEB_ROOT.'gnud/fw24h_elastic_search_json.php';
/* End: 13-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
class category_common_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
		'view' =>'EDIT_BAI',
		'edit' =>'EDIT_BAI',
		'edit_bai' =>'EDIT_BAI',
		'edit_event' =>'EDIT_EVENT',
		'view_cat' =>'VIEW_CAT',
		'view_menu_ngang' =>'VIEW_MENU_NGANG',
	);
    // khai bao danh sach cac bien = _REQUEST
	var $_arr_arg  = array (
		'page' => array(1, 'page_val')
		,'number_per_page' => array(_CONST_NUMBER_OF_ROW_PER_LIST, 'intval')
	);
	
	/**
	 * Hien thi man hinh danh sach chuyen muc
	 */
	function dsp_all_category_by_select()
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		/* begin 13/12/2016 TuyenNT bo_sung_them_quyen_them_chuyen_muc */
        if (!$this->getPerm('admin,view,view_cat')) {
		/* end 13/12/2016 TuyenNT bo_sung_them_quyen_them_chuyen_muc */
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        _setLayout('ajax');
        html_set_title('Chọn chuyên mục xuất bản');
		/* Begin anhpt1 06/07/2016 fix_loi_bao_mat_error_handling */
        $_GET['data'] = !check_array($_GET['data']) ? html_entity_decode($_GET['data']) : '';
        /* End anhpt1 06/07/2016 fix_loi_bao_mat_error_handling */
        $_GET['news_id'] = intval($_GET['news_id']);
        $v_main_cate = intval($_GET['select_main_cate']);
        $v_main_cate_id = intval($_GET['v_main_cate_id']);
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();
        $v_action = html_link('ajax/'.$this->className().'/act_update_category_by_news', false);
		if (isset($_GET['url_action'])) {
			$v_action = html_entity_decode($_GET['url_action']);
			if (!preg_match('#^http://#', $v_action)) {
				$v_action = html_link($v_action, false);
			}
		}
		$v_option = intval($_GET['option']);
		$v_active = -1;
		$v_is_link = 1;
		if($v_option == 1) {// khong lay chuyen muc an va co link
			$v_active = 1; // chuyen muc active
			$v_is_link	= 0; // khong co link
		}
        $v_is_accept_choose_no_category = intval($_GET['is_accept_no']);
		$v_is_no_submit = intval($_GET['is_no_submit']);
        
		if (!preg_match('#option=#', $_SERVER['QUERY_STRING'])) {
			$v_url_reload_default = $_SERVER['QUERY_STRING'].'&option=0';
		    $v_url_reload  = $_SERVER['QUERY_STRING'].'&option=1';
		} else {
			$v_url_reload_default = preg_replace('#option=[0-9]+#', 'option=0', $_SERVER['QUERY_STRING']);
		    $v_url_reload  = preg_replace('#option=[0-9]+#', 'option=1', $_SERVER['QUERY_STRING']);
		}
		// begin 18/10/2016 TuyenNT bo_sung_chuc_nang_cau_hinh_chuyen_muc_nhan_mail_nv_seo
        if(intval($_GET['user_cat']) > 1){
            $v_arr_items = be_get_all_category_by_select($v_active, intval($_GET['user_cat']), $v_is_link);
        } else {
            $v_arr_items = be_get_all_category_by_select($v_active, ($_GET['user_cat']==1?0:$_SESSION['user_id']), $v_is_link);
        }
        // end 18/10/2016 TuyenNT bo_sung_chuc_nang_cau_hinh_chuyen_muc_nhan_mail_nv_seo
        
        $v_arr_tmp = array();
        for ($i=0, $n=count($v_arr_items['data']); $i<$n; $i++) {
            $v_category = $v_arr_items['data'][$i];
            $v_arr_tmp[] = array('id'=>$v_category['ID'], 'name'=>$v_category['Name'], 'ascii_name'=>_utf8_to_ascii($v_category['Name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        $v_arr_url_news = array();
        if(intval($_GET['news_id']) > 0){
            $v_arr_url_news = be_get_news_url(intval($_GET['news_id']));
        }
        $this->setParam('v_is_accept_choose_no_category', $v_is_accept_choose_no_category);
		$this->setParam('v_is_no_submit', $v_is_no_submit);
        $this->setParam('v_main_cate_id', $v_main_cate_id);
        $this->setParam('v_arr_url_news', $v_arr_url_news);
        $this->setParam('v_arr_items', $v_arr_items['data']);
        $this->setParam('v_json_category', $v_json_category);
        $this->setParam('v_record_count', count($v_arr_items['data']));
        $this->setParam('v_arr_category', $v_arr_category);
        $this->setParam('v_action', $v_action);
		$this->setParam('v_option', $v_option);
        $this->setParam('v_url_reload_default', $v_url_reload_default);   
		$this->setParam('v_url_reload', $v_url_reload);
        if($v_main_cate ==1){
            $this->render($this->thisPath().'view/dsp_all_category_by_select_main_cate.php');
        }else{
            $this->render($this->thisPath().'view/dsp_all_category_by_select.php');
        }
	}

    /**
	 * Cap nhat danh sach chuyen muc cho bai viet
	 */
	function act_update_category_by_news()
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,edit,edit_bai,edit_event')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        // kiem tra domain
        global $v_device_global;
        // het kiem tra domain
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if($v_device_global == 'mobile') {
            $d_id = intval($_REQUEST['hdn_news_id']);
        }else {
            $d_id = intval($_POST['hdn_news_id']);
        }
        if ($d_id > 0) {
            if($v_device_global == 'mobile') {
                $v_count = intval($_REQUEST['hdn_record_count']);
                $v_news_id = intval($_REQUEST['hdn_news_id']);
            }else {
                $v_count = intval($_POST['hdn_record_count']);
                $v_news_id = intval($_POST['hdn_news_id']);
            }
			news_block::act_update_news_history($v_news_id, 'category');
            //$v_date = date("Y-m-d");
            $v_arr_check = array();
            if($v_device_global == 'mobile') {
                for ($i=0; $i<$v_count; $i++) {
                    if (isset($_REQUEST['chk_item_id_'.$i]) && intval($_REQUEST['chk_item_id_'.$i]) > 0) {
                        $v_category_id = intval($_REQUEST['chk_item_id_'.$i]);
                        $v_arr_check[] = $v_category_id;
                        be_newscategory_update($v_news_id, $v_category_id, $status=-10, $v_date='',  $_SESSION['user_id']);
                    }
                } 
            }else {
                for ($i=0; $i<$v_count; $i++) {
                    if (isset($_POST['chk_item_id_'.$i]) && intval($_POST['chk_item_id_'.$i]) > 0) {
                        $v_category_id = intval($_POST['chk_item_id_'.$i]);
                        $v_arr_check[] = $v_category_id;
                        be_newscategory_update($v_news_id, $v_category_id, $status=-10, $v_date='',  $_SESSION['user_id']);
                    }
                } 
            }
            // Lấy ID chuyên mục chính
            if($v_device_global == 'mobile') {
                $v_main_cate_id = intval($_REQUEST['chk_item_main_cate_id']);
            }else {
                $v_main_cate_id = intval($_POST['chk_item_main_cate_id']);
            }
            // Tạo canonical, Url gốc cho bài viết
            update_link_origin_for_news($v_news_id,$v_main_cate_id);
            // Xoa cac chuyen muc ko duoc chon
            if (count($v_arr_check) > 0) {
                $v_where = "NewsID = $v_news_id AND (CategoryID != ". implode(' AND CategoryID != ',$v_arr_check) .")";
                be_newscategory_delete_by_where($v_where);
            }
            
            /* Begin: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
            // Lấy cấu hình chế độ đẩy bài viết lên elastic search
            $v_che_do_tim_kiem = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TIM_KIEM_BAI_VIET','ON_OFF_DAY_BAI_ELASTICSEARCH');
            // Kiểm tra chế độ có cho phép đẩy dữ liệu lên elastic search không
            if ($v_che_do_tim_kiem == 'TRUE') {
                $response = day_bai_viet_len_elasticsearch($v_news_id);
                if ($response['errors'] == '' || $response['errors'] == false) {
                    $errorMsg = "+++ Bai viet duoc thay doi CHUYÊN MỤC newscategory : $v_news_id \n";
                    @error_log($errorMsg, 3, WEB_ROOT.'/logs/cron_put_data_to_amazon_elastic_search_api_update.log');
                }
            }
            /* End: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
            
			js_message('Cập nhật thành công.');
		} 
        if($v_device_global == 'mobile') {
            js_redirect(fw24h_base64_url_decode($_REQUEST['goback']));
        }else {
            js_set('top.window.opener.get_thong_tin_nb_khi_thay_doi_chuyen_muc('.(int)$_POST['hdn_news_id'].')');
            js_set('top.window.close()');
        }
	}
	
    /**
	 * Hien thi man hinh danh sach chuyen muc khampha
	 */
	function dsp_all_category_khampha_by_select()
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        _setLayout('ajax');
        $v_text_cm_phu = _get_module_config('cau_hinh_dung_chung', 'v_text_cm_phu');
        html_set_title('Chọn '.$v_text_cm_phu);
        $_GET['data'] = html_entity_decode($_GET['data']);
        $_GET['news_id'] = intval($_GET['news_id']);
        //phuonghv add 30/09/2014
        $v_action = html_link('ajax/'.$this->className().'/act_update_category_khampha_by_news', false);        
        if (isset($_GET['url_action'])) {
			$v_action = html_entity_decode($_GET['url_action']);
			if (!preg_match('#^http://#', $v_action)) {
				$v_action = html_link($v_action, false);
			}
		}
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();
        
		$v_user_id = ($_GET['user_cat']==1) ? 0 : $_SESSION['user_id'];
        $v_arr_items = be_get_all_category_khampha($v_user_id);
        
        $v_arr_tmp = array();
        for ($i=0, $n=count($v_arr_items); $i<$n; $i++) {
            $v_category = $v_arr_items[$i];
            $v_arr_tmp[] = array('id'=>$v_category['ID'], 'name'=>$v_category['Name'], 'ascii_name'=>_utf8_to_ascii($v_category['Name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        
        $this->setParam('v_arr_items', $v_arr_items);
        $this->setParam('v_json_category', $v_json_category);
        $this->setParam('v_record_count', count($v_arr_items));
        $this->setParam('v_arr_category', $v_arr_category);
        $this->setParam('v_action', $v_action);         
        $this->render($this->thisPath().'view/dsp_all_category_khampha_by_select.php');
	}
    
    /**
	 * Cap nhat danh sach chuyen muc khampha cho bai viet
	 */
	function act_update_category_khampha_by_news()
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,edit,edit_bai,edit_event')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        if (intval($_POST['hdn_news_id']) > 0) {
			$v_count = intval($_POST['hdn_record_count']);
            $v_news_id = intval($_POST['hdn_news_id']);
			news_block::act_update_news_history($v_news_id, 'category_khampha');
            
			//Begin 06-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
            $v_arr_category = be_get_all_category_by_one_news($v_news_id);
            $v_is_video = intval($v_arr_category[0]['Video_code']);
            $v_publish_date_khampha = $v_arr_category[0]['PublishedDate2'];
            
            $v_arr_check = array();
			
			$row_news = be_get_single_news($v_news_id);
			$v_time_khampha = _get_module_config('news','v_thoi_gian_delay_day_bai_sang_khampha');
			$v_arr_category_khampha = be_get_all_category_khampha_by_one_news($v_news_id);
			if (check_array($v_arr_category_khampha)) {
				$v_bai_hen_gio = $v_arr_category_khampha[0]['pending_status'];
			} else {
				$v_bai_hen_gio = 0;	
			}
			if ($v_arr_category[0]['pending_status'] > 0) {
				$v_bai_hen_gio = 1;	
			}
			if (strtotime($v_arr_category[0]['PublishedDate2']) >= strtotime('-'.$v_time_khampha.' minutes') && $v_arr_category[0]['Status'] > 0) {
				$v_bai_hen_gio = 1;	
			}
			
            for ($i=0; $i<$v_count; $i++) {
                if (isset($_POST['chk_item_id_'.$i]) && intval($_POST['chk_item_id_'.$i]) > 0) {
                    $v_category_id = intval($_POST['chk_item_id_'.$i]);
                    $v_arr_check[] = $v_category_id;
					if ($row_news['Status'] == 10 && $v_bai_hen_gio <= 0) {
						be_newscategory_khampha_update($v_news_id, $v_category_id, $status= 1, date("Y-m-d H:i:s", strtotime('+'.$v_time_khampha.' minutes',strtotime($v_arr_category[0]['PublishedDate2']))),  $_SESSION['user_id'], $v_is_video);
					} else {
						$v_time_category_khampha = ($v_arr_category[0]['pending_date'] != '0000-00-00 00:00:00') ? $v_arr_category[0]['pending_date'] : $v_arr_category[0]['PublishedDate2'];
                    	be_newscategory_khampha_update($v_news_id, $v_category_id, $status=-10, date("Y-m-d H:i:s", strtotime('+'.$v_time_khampha.' minutes',strtotime($v_time_category_khampha))),  $_SESSION['user_id'], $v_is_video);
					}
                }
            }
            // Xoa cac chuyen muc ko duoc chon
            if (count($v_arr_check) > 0) {
                $v_where = "NewsID = $v_news_id AND (CategoryID != ". implode(' AND CategoryID != ',$v_arr_check) .")";
            } else {
                $v_where = "NewsID = $v_news_id";
            }
            be_newscategory_khampha_delete_by_where($v_where);
            // day loai bai infographic sang kham pha fix_day_bai_infographic_kham_pha
            if($row_news['news_infographic'] == 1){
                news_block::act_push_data_to_khampha($v_news_id, false, true);
            }
            // Day du lieu sang khampha
            if ($row_news['Status'] == 10 && $v_bai_hen_gio <= 0) {
            	news_block::act_push_data_to_khampha($v_news_id, false, true);
			} else {
				$v_news_block = new news_block();
				if ($v_bai_hen_gio > 0) {
					$v_news_block->act_change_status_to_khampha($v_news_id, 2, 0, 0, true);
				} else {
					$v_news_block->act_change_status_to_khampha($v_news_id, 2, 0, 0);
				}
			}
            // Lấy thời gian hẹn giờ xuất bản bài PR
            $v_arr_news_pr = be_get_news_pr_2015_by_news_id($v_news_id);
            $v_thoi_gian_xuat_ban = '';
            if(check_array($v_arr_news_pr)){
                $v_thoi_gian_xuat_ban = $v_arr_news_pr[0]['c_ngay_xuat_ban'];
                $v_count = count($v_arr_news_pr);
                if($v_count>1){ // nếu 1 bài viết được xuất bản nhiều loại bài pr thì mới thực hiện kiểm tra để lấy thời gian xuất bản nhỏ nhất
                    $i=1;
                    foreach($v_arr_news_pr as $news_pr){
                        if($i>1 && $v_thoi_gian_xuat_ban > $news_pr['c_ngay_xuat_ban']){
                            $v_thoi_gian_xuat_ban = $news_pr['c_ngay_xuat_ban'];
                        }
                        $i++;
                    }
                }
            }
            // Nếu đang là bài hẹn giờ xuất bản bài PR thì giữ nguyên thời gian hẹn giờ
            if($v_thoi_gian_xuat_ban != '' && !$v_bai_hen_gio){
                push_news_public_to_khampha($v_news_id,1);
            }
            $v_first_publish_date = ($v_arr_category[0]['pending_date'] != '0000-00-00 00:00:00') ? $v_arr_category[0]['pending_date'] : $v_arr_category[0]['PublishedDate2'];
            $v_status = 0;

            $v_publish_date = date("Y-m-d H:i:s", strtotime('+'.$v_time_khampha.' minutes', strtotime($v_first_publish_date)));
            $v_news_cate = array('NewsID' => $v_news_id,
                            'Status' => $v_status,
                            'PublishedDate' => date('Y-m-d', strtotime($v_publish_date)),
                            'PublishedDate2' => $v_publish_date,
                            'PublishedID' => $_SESSION['user_khampha_id'],
                            'listmenu' => $v_arr_check,
                            );
            $v_news_cate['website'] = _get_module_config('news','v_website_name_for_khampha');
            $v_news_cate['data'] = serialize($v_news_cate);
            $v_return = Gnud_AutoPro_PostCurl($v_news_cate, LINK_PUSH_NEWSCATEGORY_TO_KHAMPHA);
            
            /* Begin: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
            // Lấy cấu hình đẩy elastic search
            $v_che_do_tim_kiem = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TIM_KIEM_BAI_VIET','ON_OFF_DAY_BAI_ELASTICSEARCH');
            // Kiểm tra cấu hình đẩy elastic search
            if ($v_che_do_tim_kiem == 'TRUE') {
                // thực hiện đẩy dữ liệu
                $response = day_bai_viet_len_elasticsearch($v_news_id);
                if ($response['errors'] == '' || $response['errors'] == false) {
                    $errorMsg = "+++ Bai viet duoc thay doi CHUYÊN MỤC newscategory KHAMPHA : $v_news_id \n";
                    @error_log($errorMsg, 3, WEB_ROOT.'/logs/cron_put_data_to_amazon_elastic_search_api_update.log');
                }
            }
            /* End: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
            
			js_message('Cập nhật thành công.');
			//End 06-07-2016 : Thangnb xu_ly_bai_pr_day_sang_khampha
		}
		js_set('top.window.close()');
	}
    /**
    * Hien thi lich su sua doi 1 chuyen muc
    * p_category_id : id chuyen muc
    * $p_from_list : hien thi tu man hinh danh sach
    */
	function dsp_history_change($p_category_id, $p_from_list = 0)
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        $p_category_id = intval($p_category_id);
        $v_rows = intval($_REQUEST["hdn_record_count"]);
        if($p_from_list == 1) {
            // lay dong check dau tien
            for ($i=0; $i < $v_rows; $i++) {
                $v_id = intval($_REQUEST["chk_item_id".$i]);					
                if ($v_id > 0) {
                    $p_category_id =  $v_id;
                    break;
                }
            }
        }
        
        if ($p_category_id > 0) {
            $rs_data = be_danh_sach_lich_su($p_category_id, 'category', 1, 100);
            $this->setParam('rs_data', $rs_data['data']);                            
        }
        $this->setParam('v_arr_trang_thai', array_slice(get_list_trang_thai_xuat_ban(),1,2));
        $this->render($this->thisPath().'view/dsp_history_change.php');
    }
     /**
    * Hien thi lich su sua doi 1 menu ngang
    * p_menu_id : id menu
    */
	function dsp_history_change_menu_ngang($p_menu_id)
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        $this->getRequest();
        $p_menu_id = intval($p_menu_id);
        $page = (int)$this->_GET['page'];
        $page = ($page == 0)? 1: $page;
        $number_per_page = (int)$this->_GET['number_per_page'];
        $number_per_page = ($number_per_page ==0)? 20:$number_per_page;
        if ($p_menu_id > 0) {
            $rs_data = be_danh_sach_lich_su($p_menu_id, 'menu_ngang', $page , $number_per_page);
            $this->setParam('rs_data', $rs_data['data']);                            
        }
         
        $this->setParam('v_arr_trang_thai', array_slice(get_list_trang_thai_xuat_ban(),1,2));
        $this->setParam('phan_trang', _db_page(count($rs_data['data']), $page, $number_per_page));
        
        $this->render($this->thisPath().'view/dsp_history_change_menu_ngang.php');
    }
	/**
	 * Hien thi man hinh danh sach chuyen muc
	 */
	function dsp_all_category_with_date_by_select()
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        _setLayout('ajax');
        html_set_title('Chọn chuyên mục xuất bản');
		
        $_GET['data'] = html_entity_decode($_GET['data']);
        $_GET['news_id'] = intval($_GET['news_id']);
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();
		$v_arr_date = ($_GET['date']!='') ? json_decode($_GET['date'], true) : array();
        $v_action = html_link('ajax/'.$this->className().'/act_update_category_by_news', false);
		if (isset($_GET['url_action'])) {
			$v_action = html_entity_decode($_GET['url_action']);
			if (!preg_match('#^http://#', $v_action)) {
				$v_action = html_link($v_action, false);
			}
		}
		$v_arr_items = be_get_all_category_by_select(-1, $_SESSION['user_id'], 0);
        
        $v_arr_tmp = array();
        for ($i=0, $n=count($v_arr_items['data']); $i<$n; $i++) {
            $v_category = $v_arr_items['data'][$i];
            $v_arr_tmp[] = array('id'=>$v_category['ID'], 'name'=>$v_category['Name'], 'ascii_name'=>_utf8_to_ascii($v_category['Name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        
        $this->setParam('v_arr_items', $v_arr_items['data']);
        $this->setParam('v_json_category', $v_json_category);
        $this->setParam('v_record_count', count($v_arr_items['data']));
        $this->setParam('v_arr_category', $v_arr_category);
        $this->setParam('v_action', $v_action);
        $this->setParam('v_arr_date', $v_arr_date);    
        $this->render($this->thisPath().'view/dsp_all_category_with_date_by_select.php');
	}
    /**
    * Hien thi lich su sua doi 1 menu ngang
    * p_menu_id : id menu
    */
	function dsp_history_change_menu($p_menu_id)
    {
		//Begin 28-11-2016 : Thangnb fix_loi_bao_mat_retest
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		//End 28-11-2016 : Thangnb fix_loi_bao_mat_retest
		
        $this->getRequest();
        $p_menu_id = intval($p_menu_id);
        $page = (int)$this->_GET['page'];
        $page = ($page == 0)? 1: $page;
        $number_per_page = (int)$this->_GET['number_per_page'];
        $number_per_page = ($number_per_page ==0)? 20:$number_per_page;
        if ($p_menu_id > 0) {
            $rs_data = be_danh_sach_lich_su($p_menu_id, 'quan_ly_menu', $page , $number_per_page);
            $this->setParam('rs_data', $rs_data['data']);                            
        }
         
        $this->setParam('v_arr_trang_thai', array_slice(get_list_trang_thai_xuat_ban(),1,2));
        $this->setParam('phan_trang', _db_page(count($rs_data['data']), $page, $number_per_page));
        
        $this->render($this->thisPath().'view/dsp_history_change_menu.php');
    }
    
    /* BEGIn 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */
    /**
	 * Hàm hiện thị màn hình chọn chuyên mục đối tác
     * @author: TuyenNT<tuyennt@24h.com.vn>
     * @date: 6-11-2018
     * @param: Không
	 */
	function dsp_all_category_partners_by_select()
    {
		// Kiểm tra quyền chức năng
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		
        _setLayout('ajax');
        
        // Lấy cấu hình  website đối tác
        $v_cau_hinh_website_doitac = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DANH_SACH_WEB_PHU', strtoupper(fw24h_replace_bad_char($_GET['c_code'])));
        html_set_title('Chọn chuyên mục web '.$v_cau_hinh_website_doitac);
		
        // Lấy Mảng dữ liệu chuyên mục đã được phân quyền cho user
        $_GET['data'] = html_entity_decode($_GET['data']);
        // Lấy ID bài viết
        $_GET['news_id'] = intval($_GET['news_id']);
        // mảng dữ liệu chuyên mục
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();
        // Lấy userid
		$v_user_id = ($_GET['user_cat']==1) ? 0 : $_SESSION['user_id'];
        // mã website đối tác
        $v_code = strtoupper(fw24h_replace_bad_char($_GET['c_code']));
        // mảng dữ kliệu được phân quyền cho user
        $v_arr_items = be_get_all_category_partners($v_user_id, $v_code);
        
        $v_arr_tmp = array();
        for ($i=0, $n=count($v_arr_items); $i<$n; $i++) {
            $v_category = $v_arr_items[$i];
            $v_arr_tmp[] = array('id'=>$v_category['fk_category_id'], 'name'=>$v_category['c_name'], 'ascii_name'=>_utf8_to_ascii($v_category['c_name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        
        // dữ liệu danh sách chuyên mục đối tác
        $this->setParam('v_arr_items', $v_arr_items);
        // Dữ liệu json chuyên mục đối tác đã được phân quyền
        $this->setParam('v_json_category', $v_json_category);
        // Dữ liệu số lượng chuyên mục đối tác
        $this->setParam('v_record_count', count($v_arr_items));
        // Danh sách chuyên mục đối tác đã được phân quyền theo user
        $this->setParam('v_arr_category', $v_arr_category);
        // Mã đối tác
        $this->setParam('v_code', $v_code);
        
        // Điều hướng sang view
        $this->render($this->thisPath().'view/dsp_all_category_partners_by_select.php');
	}
    /* End 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong_xu_ly_phan_quyen_chuyen_muc */
    
    /* Begin: 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong */
    /**
	 * Hien thi man hinh chọn chuyên mục đối tác bài viết
     * @author: TuyenNT<tuyennt@24h.com.vn>
     * @date: 7-11-2018
     * @param: Không
	 */
	function dsp_all_category_partners_for_news_by_select()
    {
        // Kiểm tra quyền chức năng
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
		
        _setLayout('ajax');
        
		// Lấy mảng dữ liệu
        $_GET['data'] = html_entity_decode($_GET['data']);
        // Lấy id bài viết
        $_GET['news_id'] = intval($_GET['news_id']);
        // Lấy id chuyên mục xuất bản chính
        $v_main_cate = intval($_GET['c_main_category_id']);
        // Mảng chuyên mục đã được chọn xuất bản
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();
        // user id đăng nhập
		$v_user_id = ($_GET['user_cat']==1) ? 0 : $_SESSION['user_id'];
        
        // Lấy cấu hình danh mục chuyên mục theo web phu đẩy tin tin bài
        $v_arr_gia_tri_cau_hinh = be_danh_sach_gia_tri_theo_ma_danh_muc('CAU_HINH_CHUYEN_MUC_DAY_BAI_THEO_WEB_PHU');
        
        // Lấy mã đối tác từ ngoài pupup
        $v_sel_code_partners = fw24h_replace_bad_char($_GET['sel_code_partners']);

        // Kiểm tra có được chọn ngoài popup hay không
        if($v_sel_code_partners != ''){
            // Gán mã code bằng mã code lọc ngoài popup
            $v_code = $v_sel_code_partners;
            
        // Trường hợp vào lần đầu
        }else{
            // mã đối tác (mặc định sẽ là Dân việt)
			//Begin 14/5/2020 AnhTT toi_uu_cm_web_phu
            $v_code = 'DANVIET';
			//Begin 14/5/2020 AnhTT toi_uu_cm_web_phu
            // lấy mảng thông tin đẩy tin bài viết đối tác
            $v_arr_partners = be_get_single_24h_partners_by_news($_GET['news_id']);
            // Nếu bài viết đã tồn tại
            if(check_array($v_arr_partners)){
                $v_code = fw24h_replace_bad_char($v_arr_partners['c_code']);
            // Nếu bài viết chưa tồn tại thì mới chọn mặc định theo cấu hình
            }elseif(!check_array($v_arr_partners) && $_GET['news_id'] == 0){
                // Khởi tạo mảng chuyên mục cấu hình mặc định
                $v_arr_cm_cau_hinh = array();
                // Lặp mảng để xác định chuyên mục ngầm định
                foreach($v_arr_gia_tri_cau_hinh as $data){
                    // List chuyên mục cấu hình
                    $v_list_cm = $data['c_ten'];
                    // mảng chuyên mục cấu hình mặc định theo web phụ
                    $v_arr_cm_cau_hinh = explode(',', $v_list_cm);
                    // Kiểm tra chuyên mục xuất bản chính thuộc cấu hình mặc định đẩy sang đối tác nào
                    if(check_array($v_arr_cm_cau_hinh) && in_array($v_main_cate, $v_arr_cm_cau_hinh)){
                        // nếu chuyên mục xuất bản chính thuộc chuyên mục ngầm định thì gán lại mã đối tác bằng mã đối tác theo cấu hình
                        $v_code = $data['c_ma_gia_tri'];
                        // Dừng luôn vòng lặp không cần check cấu hình cm đối tác khác
                        break;
                    }
                }
            }
        }
        
        // Kiểm tra xem mã đối tác thuộc đối tác nào để thực hiện lấy danh sách chuyên mục
        // Nếu là đối tác khám phá
        if(check_website_doi_tac($v_code) == 'khampha'){
            // lấy tất cả chuyên mục đối tác được phân quyền theo user
            $v_arr_items = be_get_all_category_khampha($v_user_id);
        // nếu là đối tác khác thì
        }else {
            // Lấy tất cả chuyên mục đối tác được phân quyền theo user và mã đối tác
            $v_arr_items = be_get_all_category_partners($v_user_id, $v_code);
            // Thực hiện convert các trường thông tin
            $v_arr_items = convert_info_category_partners($v_arr_items);
        }
        
        $v_arr_tmp = array();
        // Lặp mảng tất cả chuyên mục để hiện thị ra ngoài popup
        for ($i=0, $n=count($v_arr_items); $i<$n; $i++) {
            $v_category = $v_arr_items[$i];
            $v_arr_tmp[] = array('id'=>$v_category['ID'], 'name'=>$v_category['Name'], 'ascii_name'=>_utf8_to_ascii($v_category['Name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        
        // Lấy cấu hình danh sách website đối tác
        $v_arr_cau_hinh_web_phu = be_danh_sach_gia_tri_theo_ma_danh_muc('CAU_HINH_DANH_SACH_WEB_PHU');
        // Begin duclt check quyền thao tác web phụ
        if(check_array($v_arr_cau_hinh_web_phu)){
            foreach ($v_arr_cau_hinh_web_phu as $v_cau_hinh){
                $v_arr_cate_bgt = be_get_all_category_partners($v_user_id, $v_cau_hinh['c_ma_gia_tri']);
                if(!check_array($v_arr_cate_bgt)){
                    array_delete_key_value($v_arr_cau_hinh_web_phu, 'c_ma_gia_tri', $v_cau_hinh['c_ma_gia_tri']);
                }
            }
        }
        // End duclt check quyền thao tác web phụ
        $v_arr_cau_hinh_web_phu = _array_convert_index_to_key($v_arr_cau_hinh_web_phu, 'c_ma_gia_tri');

        // set title popup
        html_set_title('Chọn chuyên mục '.$v_arr_cau_hinh_web_phu[$v_code]['c_ten']);
        
        /* Begin 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
        // Lấy chi tiết bài viết
        $v_arr_single_news = be_get_single_news($_GET['news_id']);
        /* End 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
        
        // Truyền dữ liệu sang view
        //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh

		$v_ma_cau_hinh_nguon = 'DANH_SACH_NGUON_KHAI_THAC';
		$v_arr_source = be_danh_sach_gia_tri_theo_ma_danh_muc($v_ma_cau_hinh_nguon);
        $v_arr_source_js = [];
        $v_json_source= '';
		for($i=0,$v_count=count($v_arr_source); $i<$v_count; $i++) {
            if($v_arr_source[$i]['c_ghi_chu'] == $v_code){
                $v_arr_source_js['id'] = intval($v_arr_source[$i]['pk_gia_tri']);
                $v_arr_source_js['name'] = $v_arr_source[$i]['c_ten'];
                $v_json_source = json_encode($v_arr_source_js);
                break;
            }
		}
        $this->setParam('v_json_source', $v_json_source);
        //Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
        $this->setParam('v_arr_items', $v_arr_items);
        $this->setParam('v_json_category', $v_json_category);
        $this->setParam('v_record_count', count($v_arr_items));
        $this->setParam('v_arr_category', $v_arr_category);
        $this->setParam('v_code', $v_code);
        $this->setParam('v_arr_cau_hinh_web_phu', $v_arr_cau_hinh_web_phu);
        $this->setParam('v_ten_doi_tac', $v_arr_cau_hinh_web_phu[$v_code]['c_ten']);
        /* Begin 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
        $this->setParam('v_arr_partners', $v_arr_partners);
        $this->setParam('v_arr_single_news', $v_arr_single_news);
        /* End 21-3-2019 TuyenNT: code_tinh_chinh_ocm_24h_day_bai_baogiaothong */    
        // Điều hướng sang view
        $this->render($this->thisPath().'view/dsp_all_category_partners_for_news_by_select.php');
	}
    
     /**
	 * Cap nhat danh sach chuyen muc đối tác cho bai viet
     * @author: TuyenNT<tuyennt@24h.com.vn>
     * @date: 12-11-2018
     * @param: KHông
	 */
	function act_update_category_partners_by_news()
    {
        // kiem tra domain
        global $v_device_global;
        // het kiem tra domain
		// Kiểm tra quyền thao tác của user
        if (!$this->getPerm('admin,edit,edit_bai,edit_event')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        // Biến check thuộc loại website nào
        $v_hdn_c_code = fw24h_replace_bad_char($_POST['hdn_c_code']);
        if (intval($_POST['hdn_news_id']) > 0) {
            // lấy mảng thông tin đẩy tin bài viết đối tác
            $v_arr_partners = be_get_single_24h_partners_by_news(intval($_POST['hdn_news_id']));
        }
        // Nếu là website khám phá thì thực hiện gọi vào hàm cập nhật chuyên mục khám phá cũ
        if(check_website_doi_tac($v_hdn_c_code) == 'khampha'){
            // goọi hàm cập nhật chuyên mục khám phá cũ
            $this->act_update_category_khampha_by_news();
            
            // Nếu chuyên mục đối tác cũ không phải là khám phá
            if(check_array($v_arr_partners) && check_website_doi_tac($v_arr_partners['c_code']) != 'khampha'){
                // Thực hiện update lại thông đẩy bài viết
                // Trạng thái chưa đẩy bài mặc 0, ID bài viết đối tác chưa đẩy bài => bằng 0
                if($v_device_global == 'mobile') {
                    be_update_24h_partners($v_arr_partners['pk_ID'], intval($_REQUEST['hdn_news_id']), 0, $v_hdn_c_code, 0, $_SESSION['user_id']);
                }else {
                    be_update_24h_partners($v_arr_partners['pk_ID'], intval($_POST['hdn_news_id']), 0, $v_hdn_c_code, 0, $_SESSION['user_id']);
                }
            }
        // nếu không phải là website khám phá
        }else{
            // Nếu bài viết đã tồn tại và không phải website khámphá thì mới thực hiện cập nhật
            if($v_device_global == 'mobile') {
                $so_sanh = intval($_REQUEST['hdn_news_id']);
            }else {
                $so_sanh = intval($_POST['hdn_news_id']);
            }
            if ($so_sanh > 0) {
                // Số lượng chuyên mục
                $v_count = intval($_POST['hdn_record_count']);
                // ID bài viết
                $v_news_id = intval($_POST['hdn_news_id']);
                // Thực hiện ghi log
                $v_his = intval($_POST['hdn_v_his']);
                if($v_his == 1) {
                    $v_content_history = 'Thay đổi chuyên mục phụ (chi tiết)';
                } else {
                    $v_content_history = 'Thay đổi chuyên mục phụ';
                }
                news_block::act_update_news_history($v_news_id, 'news', $v_content_history);

                // Mã đối tác: 
                $v_code = $v_hdn_c_code;
                $v_arr_check = array();
                // Lấy chi tiết bài viết 
                $row_news = be_get_single_news($v_news_id);
                // lấy mảng thông tin đẩy tin bài viết
                $v_arr_partners_news = be_get_single_24h_partners_by_news($v_news_id);
                /* begin: 21-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
                // Nếu là bài pr thì không cho phép đẩy sang baogiaothong
                if(_is_pr_news($row_news) && check_website_doi_tac($v_hdn_c_code) == 'baogiaothong'){
                    // Thông báo không cho phép chọn chuyên mục baogiaothong
                    js_message('Bài PR không được tích chọn web phụ là báo giao thông');
                    js_set("parent.document.getElementById('hdn_c_code_name').value = 'BAOGIAOTHONG'");
                    exit;
                }
                /* End: 21-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
                //Lấy danh sách chuyên mục xuất bản 24h
                $v_arr_category = be_get_all_category_by_one_news($v_news_id);
                // Cấu hình thời gian delay đẩy bài sang đối tác
                $v_time_partners = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DAY_BAI_VIET_24H_SANG_WEB_PHU', 'CAU_HINH_THOI_GIAN_DELAY_DAY_BAI_SANG_'.$v_code);
                /* Begin: 25-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
                $v_time_partners = (intval($v_time_partners) < 0) ? 0 : $v_time_partners;
                /* End: 25-3-2019 TuyenNT code_tinh_chinh_ocm_24h_day_bai_baogiaothong */
                //Lấy danh sách chuyên mục xuất bản đối tác theo bài viết
                $v_arr_category_partners = be_get_all_category_partners_by_one_news($v_news_id, $v_code);

                // Kiểm tra có phải trạng thái hẹn giờ hay không
                if (check_array($v_arr_category_partners)) {
                    $v_bai_hen_gio = $v_arr_category_partners[0]['pending_status'];
                    
                    // nếu bài xuất bản , hoặc hẹn giờ đẩy lại trong khoảng thời gian cấu hình
                    if (strtotime($v_arr_category[0]['PublishedDate2']) >= strtotime('-'.$v_time_partners.' minutes') && $v_arr_category[0]['Status'] > 0) {
                        $v_bai_hen_gio = 1;
                    }
                } else {
                    $v_bai_hen_gio = 0;
                }
                if ($v_arr_category[0]['pending_status'] > 0) {
                    $v_bai_hen_gio = 1;
                }

                // lặp để thực hiện cập nhật chuyên mục
                $v_check_update_t_24h_partners = 0;
                for ($i=0; $i<$v_count; $i++) { 
                    if (isset($_POST['chk_item_id_'.$i]) && intval($_POST['chk_item_id_'.$i]) > 0) {
                        $v_category_id = intval($_POST['chk_item_id_'.$i]);
                        $v_arr_check[] = $v_category_id;
                        // Nếu bài đã xuất bản và trạng thái hẹn giờ bằng 0
                        if ($row_news['Status'] == 10 && $v_bai_hen_gio <= 0) {
                            be_newscategory_partners_update($v_news_id, $v_category_id, $v_code, 1, date("Y-m-d H:i:s", strtotime('+'.$v_time_partners.' minutes',strtotime($v_arr_category[0]['PublishedDate2']))),  $_SESSION['user_id'], 0);
                        // nếu bài đang hẹn giờ
                        } elseif($v_bai_hen_gio == 0) {
                            $v_time_category_khampha = ($v_arr_category[0]['pending_date'] != '0000-00-00 00:00:00') ? $v_arr_category[0]['pending_date'] : $v_arr_category[0]['PublishedDate2'];
                            be_newscategory_partners_update($v_news_id, $v_category_id, $v_code, -10, date("Y-m-d H:i:s", strtotime('+'.$v_time_partners.' minutes',strtotime($v_time_category_khampha))),  $_SESSION['user_id'], 0);

                        // Nếu bài đang soạn, chờ duyệt, chờ xuất bản
                        }else{
                            be_newscategory_partners_update($v_news_id, $v_category_id, $v_code, -10, '', $_SESSION['user_id'], 0);
                        }
                        // gán giá trị cho phép update bảng 24h_partner
                        $v_check_update_t_24h_partners = 1;
                    }
                }  
                // kiểm tra nếu cho phép update bảng t_24h_partners
                $v_code_update = 'KHAMPHA';
                if($v_check_update_t_24h_partners == 1){
                    $v_code_update = $v_code;
                }
                // Cập nhật bảng t_24h_partners
                be_update_24h_partners(intval($v_arr_partners_news['pk_ID']), $v_news_id, intval($v_arr_partners_news['fk_NewsID_partners']), $v_code_update, intval($v_arr_partners_news['c_status']), $_SESSION['user_id'], intval($v_arr_partners_news['c_24h_news_status']));
                
                // Lấy danh sách chuyên mục đối tác xuất bản cho bài viết
                $v_arr_newscategory_partners = be_get_all_category_partners_by_one_news($v_news_id, $v_code);
                // lặp mảng chuyên mục để cập nhật thông tin
                if(check_array($v_arr_newscategory_partners) && ($row_news['Status'] == 10 || $v_arr_category[0]['pending_status'] > 0)){
                    // Nếu share thêm mục bài
                    // nếu là bài đã xuất bản hoặc thêm chuyên mục bài xuất bản trong khoảng thời gian cấu hình
                    if($v_bai_hen_gio == 0 || $row_news['Status'] == 10) {
                        $v_date_time  = date("Y-m-d H:i:s", strtotime('+'.$v_time_partners.' minutes',strtotime($v_arr_category[0]['PublishedDate2'])));
                    // Nếu là bài đang xuất bản
                    }else{
                        $v_date_time  = date("Y-m-d H:i:s", strtotime('+'.$v_time_partners.' minutes',strtotime($v_arr_category[0]['pending_date'])));
                    }
                    
                    foreach ($v_arr_newscategory_partners as $v_arr_partners) {
                        // cập nhật trạng thái và thời gian hẹn giờ xuất bản
                        be_update_news_publication_time_partners(intval($v_arr_partners['pk_id']), 1, date('Y-m-d H:i:s'), $v_date_time, $v_date_time, (int)$_SESSION['user_id']);
                    }
                }

                // Xóa các chuyeuen mục không được chọn
                if (count($v_arr_check) > 0) {
                    $v_where = "fk_NewsID = $v_news_id AND (fk_CategoryID != ". implode(' AND fk_CategoryID != ',$v_arr_check) .")";
                } else {
                    $v_where = "fk_NewsID = $v_news_id";
                }
                be_newscategory_part_ners_delete_by_where($v_where);

                if(check_website_doi_tac($v_code) == 'arttimes'){
                    news_block::act_push_data_to_arttimes($v_news_id); 
                }elseif(check_website_doi_tac($v_code) == 'dulich'){
                    news_block::act_push_data_to_khampha($v_news_id);  
                }else{
                    // Đẩy dữ liệu sang đối tác
                    news_block::act_push_data_to_partners($v_news_id, $v_code);
                }
                
                /* Begin: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
                // Lấy cấu hình đẩy elastic search
                $v_che_do_tim_kiem = get_gia_tri_danh_muc_dung_chung('CAU_HINH_DUNG_CHUNG_TIM_KIEM_BAI_VIET','ON_OFF_DAY_BAI_ELASTICSEARCH');
                // Kiểm tra cấu hình đẩy elastic search
                if ($v_che_do_tim_kiem == 'TRUE') {
                    // thực hiện đẩy dữ liệu
                    $response = day_bai_viet_len_elasticsearch($v_news_id);
                    if ($response['errors'] == '' || $response['errors'] == false) {
                        $errorMsg = "+++ Bai viet duoc thay doi CHUYÊN MỤC newscategory ĐỐI TÁC : $v_news_id \n";
                        @error_log($errorMsg, 3, WEB_ROOT.'/logs/cron_put_data_to_amazon_elastic_search_api_update.log');
                    }
                }
                /* End: 14-5-2019 TuyenNT ocm_24h_tim_kiem_elastic_search */
                // xử lý lưu lại nguồn khi chọn lại web site đẩy mục
                if(intval($v_news_id) > 0){
                    // lấy cấu hình danh sách nguồn
                    $v_arr_source = be_danh_sach_gia_tri_theo_ma_danh_muc('DANH_SACH_NGUON_KHAI_THAC');
                    $v_arr_source = _array_convert_index_to_key($v_arr_source, 'c_ghi_chu');
                    // nếu nguồn có cấu hình thì thực hiện upate nguồn cho bài viết
                    if(check_array($v_arr_source[$v_code])){
                        $v_source_id = $v_arr_source[$v_code]['pk_gia_tri'];
                        // cập nhật nguồn cho bài viết
                        be_news_source_update(intval($v_news_id), $v_source_id);
                    }
                }            
                // Thực hiện 
                js_message('Cập nhật thành công.');
            }
        }
        if($v_device_global == 'mobile') {
            js_redirect(fw24h_base64_url_decode($_REQUEST['goback']));
        }else {
            js_set('top.window.close()');
        }
	}
    /* End: 18-12-2018 TuyenNT code_day_bai_viet_sang_cms_baogiaothong */ 
    /**
     * Hien thi man hinh danh sach chuyen muc lua chon 1
     */
    function dsp_all_category_by_select_radio($p_target_id = '', $p_div_to_put_source_from = '', $p_div_to_insert_ten_box = '', $p_class_check_da_duoc_chon = '')
    {
        if (!$this->getPerm('admin,view,view_cat')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        $v_target_id = fw24h_replace_bad_char($p_target_id);
        _setLayout('ajax');
        html_set_title('Chọn chuyên mục');

        $_GET['data'] = html_entity_decode($_GET['data']);
        $_GET['data'] = !check_array($_GET['data']) ? html_entity_decode($_GET['data']) : '';
        $v_arr_category = ($_GET['data']!='') ? json_decode($_GET['data'], true) : array();

        $v_option = intval($_GET['option']);
        $v_active = -1;
        $v_is_link = 1;
        if($v_option == 1) {// khong lay chuyen muc an va co link
            $v_active = 1; // chuyen muc active
            $v_is_link	= 0; // khong co link
        }
        if (!preg_match('#option=#', $_SERVER['QUERY_STRING'])) {
            $v_url_reload_default = $_SERVER['QUERY_STRING'].'&option=0';
            $v_url_reload  = $_SERVER['QUERY_STRING'].'&option=1';
        } else {
            $v_url_reload_default = preg_replace('#option=[0-9]+#', 'option=0', $_SERVER['QUERY_STRING']);
            $v_url_reload  = preg_replace('#option=[0-9]+#', 'option=1', $_SERVER['QUERY_STRING']);
        }
        if(intval($_GET['user_cat']) > 1){
            $v_arr_items = be_get_all_category_by_select($v_active, intval($_GET['user_cat']), $v_is_link);
        } else {
            $v_arr_items = be_get_all_category_by_select($v_active, ($_GET['user_cat']==1?0:$_SESSION['user_id']), $v_is_link);
        }

        $v_arr_tmp = array();
        for ($i=0, $n=count($v_arr_items['data']); $i<$n; $i++) {
            $v_category = $v_arr_items['data'][$i];
            $v_arr_tmp[] = array('id'=>$v_category['ID'], 'name'=>$v_category['Name'], 'ascii_name'=>_utf8_to_ascii($v_category['Name']));
        }
        $v_json_category = json_encode($v_arr_tmp);
        $this->setParam('v_arr_items', $v_arr_items['data']);
        $this->setParam('v_json_category', $v_json_category);
        $this->setParam('v_record_count', count($v_arr_items['data']));
        $this->setParam('v_arr_category', $v_arr_category);
        $this->setParam('v_option', $v_option);
        $this->setParam('v_url_reload_default', $v_url_reload_default);
        $this->setParam('v_url_reload', $v_url_reload);
        $this->setParam('v_target_id', $v_target_id);
        $this->setParam('p_div_to_put_source_from', $p_div_to_put_source_from);
        $this->setParam('p_div_to_insert_ten_box', $p_div_to_insert_ten_box);
        $this->setParam('p_class_check_da_duoc_chon', $p_class_check_da_duoc_chon);
        $this->render($this->thisPath().'view/dsp_all_category_by_select_radio.php');
    }
}
