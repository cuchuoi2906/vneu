<?php
__get_db_functions('db.user');
_setLayout('ajax');
html_set_title('Cắt ảnh đại diện');

class crop_image_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
		'view' =>'EDIT_BAI',
		'edit' =>'EDIT_BAI',
	);

	/**
	 * Hien thi form
	 */
	function index($p_field_name, $p_txt_field_name, $p_field_hdn_name_preview, $p_width, $p_height, $p_image='')
    {
		ini_set('memory_limit', '-1');
        $v_image = $p_image;
        /* begin 16/5/2017 TuyenNT fix_loi_crop_anh_video_trang_chu_tren_ocm_eva */
        if($p_txt_field_name == 'txt_video_homepage_image' || $p_txt_field_name == 'txt_summary_image_chu_nhat'){ // chỉ xử lý đối với bài nhập ảnh đại diện video trang chủ
            header('X-XSS-Protection: 0');
        }
        /* end 16/5/2017 TuyenNT fix_loi_crop_anh_video_trang_chu_tren_ocm_eva */
        $v_rq_max_width = intval($_REQUEST['max_width']);
        if ($_REQUEST[$p_field_hdn_name_preview] != '' && preg_match('#^data:image#', $_REQUEST[$p_field_hdn_name_preview])) {
            $v_image = $_REQUEST[$p_field_hdn_name_preview];
        } else if ($_FILES[$p_field_name]['name']!='') {
            $v_upload_obj = new upload_image_block();
            $file_image = $v_upload_obj->act_upload_single_image($_FILES[$p_field_name]);
            if (count($file_image['errors']) == 0) {
                $v_image = substr(BASE_DOMAIN, 0, -1).$file_image['file_path'];
            } else {
                exit(implode('<br />', $file_image['errors']));
            }
        // nếu là upload album
        }elseif(intval($_GET['crop_image_album'] == 1)){
            $v_stt = str_replace('image_album_', '', $p_field_name);
            $v_arr_file['name'] = $_FILES['image_album']['name'][$v_stt];
            $v_arr_file['type'] = $_FILES['image_album']['type'][$v_stt];
            $v_arr_file['tmp_name'] = $_FILES['image_album']['tmp_name'][$v_stt];
            $v_arr_file['error'] = $_FILES['image_album']['error'][$v_stt];
            $v_arr_file['size'] = $_FILES['image_album']['size'][$v_stt];
            $v_upload_obj = new upload_image_block();
            $file_image = $v_upload_obj->act_upload_single_image($v_arr_file);
            if (count($file_image['errors']) == 0) {
                $v_image = substr(BASE_DOMAIN, 0, -1).$file_image['file_path'];
            } else {
                exit(implode('<br />', $file_image['errors']));
            }
        }
        if(intval($_GET['crop_image_album'] == 1)){
            $v_img_size = getimagesize($v_image);
            $v_rq_max_width = $v_img_size[0];
        }
        if ($v_image == '') {
            exit('Chưa có ảnh nào được chọn');
        }
		//Begin 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
		$free_size = (int)$_REQUEST['free_size'];
		//End 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh

        $this->setParam('v_image', $v_image);
        $this->setParam('v_txt_field_name', $p_txt_field_name);
		$this->setParam('v_field_name', $p_field_name);
        $this->setParam('v_width', $p_width);
        $this->setParam('v_height', $p_height);
        $this->setParam('v_rq_max_width', $v_rq_max_width);# 20220105 hỗ trợ max width từ param
		//Begin 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
		$this->setParam('free_size',$free_size);
		$v_block = fw24h_replace_bad_char($_REQUEST['block']);
		$v_type = fw24h_replace_bad_char($_REQUEST['type']);
		$v_extend = fw24h_replace_bad_char($_REQUEST['extend']);
		$this->setParam('v_block',$v_block);
		$this->setParam('v_type',$v_type);
		$this->setParam('v_extend',$v_extend);
		if ($free_size == 1) {
        	$this->render($this->thisPath().'view/dsp_crop_form_free_size.php');
		} else {
			$this->render($this->thisPath().'view/dsp_crop_form.php');
		}
		//End 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
	}

    /**
	 * Cat anh
	 */
	function act_crop_image($p_field_name, $p_txt_field_name, $v_thumb_w, $v_thumb_h)
    {
		ini_set('memory_limit', '-1');
        $v_image = $_POST['hdn_image'];
        $v_x = intval($_POST['x']);
        $v_y = intval($_POST['y']);
        $v_width = intval($_POST['w']);
        $v_height = intval($_POST['h']);

        // begin 18/03/2016 TuyenNT bo_sung_kich_co_toi_thieu_khi_cat_anh_dai_dien_ghi_log_file_anh_crop
        // neu cat anh dai dien bai viet thi kiem tra
        if($p_field_name == 'file_summary_image_chu_nhat'){
            if($v_width < 180 || $v_height < 135){
                js_message('Ảnh đại diện được cắt có kích thước tối thiểu 180x135');
                exit();
            }
        }
        // end 18/03/2016 TuyenNT bo_sung_kich_co_toi_thieu_khi_cat_anh_dai_dien_ghi_log_file_anh_crop
		// Cảnh báo kích thước tối thiểu ảnh cắt bài quiz
        $v_block = fw24h_replace_bad_char($_REQUEST['hdn_block']);
        $v_type = fw24h_replace_bad_char($_REQUEST['hdn_type']);
        $v_extend = fw24h_replace_bad_char($_REQUEST['hdn_extend']);
        if($v_block=='quiz'){
			$v_arr_kich_thuoc_anh_cat_toi_thieu = _get_module_config('cau_hinh_dung_chung', 'v_arr_kich_thuoc_anh_cat_toi_thieu');
			$v_arr_anh_quiz = array();
			if(check_array($v_arr_kich_thuoc_anh_cat_toi_thieu)){
				$v_arr_anh_quiz = $v_arr_kich_thuoc_anh_cat_toi_thieu[$v_block][$v_type][$v_extend];
			}
			$v_width_config = intval($v_arr_anh_quiz['width']);
			$v_height_config = intval($v_arr_anh_quiz['height']);
			if($v_width_config > 0 && $v_height_config > 0 && $v_width < $v_width_config || $v_height < $v_height_config){
				$v_messsage = 'Kích thước ảnh đã cắt ['.$v_width.'x'.$v_height.'] nhỏ hơn kích thước hiển thị ngoài frontend ['.$v_width_config.'x'.$v_height_config.']. Ảnh hiển thị có thể sẽ bị mờ';
                js_message($v_messsage);
            }
		}
        $v_upload_obj = new upload_image_block();
        $v_upload_path = $v_upload_obj->act_create_upload_folder();
        if (preg_match('#^data:image#', $v_image)) {
            $v_file_name = $v_upload_obj->validate_file_name('thumbnail');
            $v_file_name    = $v_file_name.'-'.'width'.$v_thumb_w.'height'.$v_thumb_h.'.jpg';
            $v_file_path = $v_upload_path.$v_file_name;

            $v_arr_image_data = explode(',', $v_image);
            $v_image_string = $v_arr_image_data[1];
            if ($v_image_string == '') {
                js_message('Lỗi cắt ảnh!');
                exit();
            }
            $v_img = imagecreatefromstring(base64_decode($v_image_string));
        } else {
            $v_file_path = ROOT_FOLDER.substr($v_image, strpos($v_image, '/upload/')+1);
            $v_img = imagecreatefromjpeg($v_file_path);
        }
        $v_thumb_img = imagecreatetruecolor($v_thumb_w, $v_thumb_h);
        imagecopyresampled($v_thumb_img, $v_img, 0, 0, $v_x, $v_y, $v_thumb_w, $v_thumb_h, $v_width, $v_height);
        $result = imagejpeg($v_thumb_img, $v_file_path);
        if ($result !== false) {
            $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);

            // begin 18/03/2016 TuyenNT bo_sung_kich_co_toi_thieu_khi_cat_anh_dai_dien_ghi_log_file_anh_crop
            if($p_field_name == 'file_summary_image_chu_nhat'){
                $errorMsg = date('Y-m-d H:i:s '). "File ảnh đại diện khi cắt :".MST_DOMAIN.$v_uploaded_file." \n";
                @error_log($errorMsg, 3, WEB_ROOT.'/logs/log_file_anh_dai_dien_duoc_crop.log');
            }
            // end 18/03/2016 TuyenNT bo_sung_kich_co_toi_thieu_khi_cat_anh_dai_dien_ghi_log_file_anh_crop

            if (count($file_image['errors']) == 0) {
                js_set('top.opener.document.getElementById("'.$p_txt_field_name.'").value="'.$v_uploaded_file.'"; top.opener.document.getElementById("'.$p_field_name.'").outerHTML = top.opener.document.getElementById("'.$p_field_name.'").outerHTML; top.close()');
            }
        } else {
            js_message('Lỗi cắt ảnh!');
            exit();
        }
    }

	//Begin 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
	function act_crop_image_free_size($p_field_name, $p_txt_field_name)
    {
		ini_set('memory_limit', '-1');
        $v_image = $_POST['hdn_image'];
        $v_x = intval($_POST['x']);
        $v_y = intval($_POST['y']);
        $v_width = intval($_POST['w']);
        $v_height = intval($_POST['h']);
        // Cảnh báo kích thước tối thiểu ảnh cắt bài quiz
        $v_block = fw24h_replace_bad_char($_REQUEST['hdn_block']);
        $v_type = fw24h_replace_bad_char($_REQUEST['hdn_type']);
        $v_extend = fw24h_replace_bad_char($_REQUEST['hdn_extend']);
        if($v_block=='quiz'){
			$v_arr_kich_thuoc_anh_cat_toi_thieu = _get_module_config('cau_hinh_dung_chung', 'v_arr_kich_thuoc_anh_cat_toi_thieu');
			$v_arr_anh_quiz = array();
			if(check_array($v_arr_kich_thuoc_anh_cat_toi_thieu)){
				$v_arr_anh_quiz = $v_arr_kich_thuoc_anh_cat_toi_thieu[$v_block][$v_type][$v_extend];
			}
			$v_width_config = intval($v_arr_anh_quiz['width']);
			$v_height_config = intval($v_arr_anh_quiz['height']);
			if($v_width_config > 0 && $v_height_config > 0 && $v_width < $v_width_config || $v_height < $v_height_config){
				$v_messsage = 'Kích thước ảnh đã cắt ['.$v_width.'x'.$v_height.'] nhỏ hơn kích thước hiển thị ngoài frontend ['.$v_width_config.'x'.$v_height_config.']. Ảnh hiển thị có thể sẽ bị mờ';
                js_message($v_messsage);
            }
		}
        $v_upload_obj = new upload_image_block();
        $v_upload_path = $v_upload_obj->act_create_upload_folder();

        if (preg_match('#^data:image#', $v_image)) {
            $v_file_name = $v_upload_obj->validate_file_name('-'.'width'.$v_width.'height'.$v_height).'.jpg';
            $v_file_path = $v_upload_path.$v_file_name;

            $v_arr_image_data = explode(',', $v_image);
            $v_image_string = $v_arr_image_data[1];
            if ($v_image_string == '') {
                js_message('Lỗi cắt ảnh!');
                exit();
            }
            $v_img = imagecreatefromstring(base64_decode($v_image_string));
        } else {
            $v_file_path = ROOT_FOLDER.substr($v_image, strpos($v_image, '/upload/')+1);
            $v_img = imagecreatefromjpeg($v_file_path);
        }
        $v_thumb_img = imagecreatetruecolor($v_width, $v_height);
        imagecopyresampled($v_thumb_img, $v_img, 0, 0, $v_x, $v_y, $v_width, $v_height, $v_width, $v_height);
        $result = imagejpeg($v_thumb_img, $v_file_path);
        if ($result !== false) {
            $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);

            if (count($file_image['errors']) == 0) {
                js_set('top.opener.document.getElementById("'.$p_txt_field_name.'").value="'.$v_uploaded_file.'"; top.opener.document.getElementById("'.$p_field_name.'").outerHTML = top.opener.document.getElementById("'.$p_field_name.'").outerHTML; top.close()');
            }
        } else {
            js_message('Lỗi cắt ảnh!');
            exit();
        }
    }
	//End 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
}
