<?php
__get_db_functions('db.user');
_setLayout('ajax');

class upload_image_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	var $_arr_permision = array (
		'admin' =>'ADMIN_OCM_24H',
		'view' =>'EDIT_BAI',
		'edit' =>'EDIT_BAI',
	);

	/**
	 * Hien thi form upload
	 */
	function index($p_browser='')
    {
		//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
		$v_image_type = fw24h_replace_bad_char($_REQUEST['image_type']);
		if ($v_image_type == 'gif') {
			html_set_title('Upload ảnh GIF cho bài viết');
        /* Begin - Tytv 19/01/2017 - tinh_chinh_chat_luon_nen_anh_cho_bai_pr */
        } else if(strtolower($v_image_type) == 'pr'){
            html_set_title('Upload ảnh thường cho bài viết PR');
        /* end - Tytv 19/01/2017 - tinh_chinh_chat_luon_nen_anh_cho_bai_pr */
		} else {
			html_set_title('Upload ảnh cho bài viết');	
		}
        $v_view = ($p_browser == 'ie') ? 'dsp_upload_form_ie' : 'dsp_upload_form';
		$this->setParam('v_image_type', $v_image_type);
		//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        $this->render($this->thisPath().'view/'.$v_view.'.php');
	}

    /**
	 * Upload anh len server
	 */
    function act_upload()
    {
		//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
		$v_image_type = fw24h_replace_bad_char($_REQUEST['image_type']);
		if ($v_image_type == 'gif') {
			html_set_title('Upload ảnh GIF cho bài viết');
        /* Begin - Tytv 19/01/2017 - tinh_chinh_chat_luon_nen_anh_cho_bai_pr */
        } else if(strtolower($v_image_type) == 'pr'){
            html_set_title('Upload ảnh thường cho bài viết PR');
		} else {
			html_set_title('Upload ảnh cho bài viết');	
		}
        $v_quality = null;
        $v_file_uploaded = $this->act_upload_multi_image($_FILES['file_image'],0,array(0,0),0,$v_quality);
        /* end - Tytv 19/01/2017 - tinh_chinh_chat_luon_nen_anh_cho_bai_pr */
        $this->setParam('v_arr_error', $v_file_uploaded['errors']);
        $this->setParam('v_uploaded_file', $v_file_uploaded['file_path']);
		//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
		$this->setParam('v_arr_image_type', $v_file_uploaded['image_type']);
		//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        $this->render($this->thisPath().'view/dsp_upload_result.php');
    }

    /**
	 * Tao thu muc anh
	 */
    function act_create_upload_folder($p_upload_path='')
    {
        $p_upload_path = ($p_upload_path != '') ? $p_upload_path : UPLOAD_FOLDER;
        $quarterByDate = quarterByDate(date('m'));
        $path = $p_upload_path.$quarterByDate.'-'.date('Y');
        if (!is_dir($path)) {
            mkdir($path, 0777);
            _chmode_777($path);
        }
        // tao thu muc
        $path .= '/images/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
            _chmode_777($path);
        }
        // tao theo ngay
        $path .= date('Y-m-d').'/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
            _chmode_777($path);
        }
        return $path;
    }

    /**
	* Upload 1 anh
	* 20-01-2015 : Thangnb them bien $p_check_ty_le de them truong hop kiem tra ty le cua anh
	*/
    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    function act_upload_single_image($p_file, $p_max_size=0, $p_arr_length=array(0,0), $p_upload_path='', $p_extension_allow='',$p_check_ty_le = false, $p_max_size_2 = 0, $p_quality = null, $p_bkSubfix = null, $p_staus_limit = false)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        if ($p_upload_path != '') {
			$v_upload_path = $this->act_create_upload_folder($p_upload_path);
        } else {
			$v_upload_path = $this->act_create_upload_folder();
		}
		$v_extension_allow = $p_extension_allow;
        $p_extension_allow = ($p_extension_allow != '') ? $p_extension_allow : IMAGE_EXTENSION_ALLOW;
        $v_file_name = $p_file['name'];
        $v_file_size = $p_file['size'];
        $v_file_type = $p_file['type'];

        $p_max_size = intval($p_max_size);
        
        // Giới hạn dung lượng tối đa được upload
        $p_max_size = ($p_max_size > 0 && $p_max_size <= MAX_IMAGE_SIZE) ? $p_max_size : MAX_IMAGE_SIZE;     
        //04-05-2015 : Thangnb them max size anh
		if (intval($p_max_size_2) > 0) {
			$p_max_size = $p_max_size_2;
		}
        $v_error = array();
        /*Begin 24-11-2017 trungcq XLCYCMHENG_27976_bo_sung_width_height_ten_anh*/
        $v_file_name = $this->change_image_name($p_file['tmp_name'],$v_file_name);
	/*End 24-11-2017 trungcq XLCYCMHENG_27976_bo_sung_width_height_ten_anh*/
		
        //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
        if(!$this->valid_file_extension($v_file_name, $p_extension_allow)) {
	        $v_error[] = 'Phần mở rộng file '.$v_file_name.' không hợp lệ';            
            $this->write_log($v_file_name); // Ghi log 
			$v_return = array('errors' => $v_error, 'file_path' => $v_file_name);
			return $v_return;
        }
        //end: phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
		// Nếu là ảnh gif thì tăng giới hạn dung lượng upload
        if(check_anh_dang_gif_theo_duong_dan($p_file['name'])){
            // Gan lại kích cỡ size ảnh gif
            $v_max_size_anh_dai_dien_bai_dang_gif = _get_module_config('news','v_max_size_anh_dai_dien_bai_dang_gif'); 
            $p_max_size = $v_max_size_anh_dai_dien_bai_dang_gif;
            // Gan thêm dấu hiệu vào tên file để nhận dạng ảnh gif
            $v_ten_dau_hieu_nhan_dang_anh_gif = _get_module_config('upload_image','v_ten_dau_hieu_nhan_dang_anh_gif');
            $v_file_name = $v_ten_dau_hieu_nhan_dang_anh_gif.$v_file_name;
        }
        if ($v_file_size > $p_max_size) {
			$v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.round($v_file_size/1024).' KB'.' > '.round($p_max_size/1024).' KB';
        }

        $v_file_path = $v_upload_path.$v_file_name;
        $v_uploaded_file = '';
        if (count($v_error) == 0) {
            if ($p_file['tmp_name'] == '' || !copy($p_file['tmp_name'], $v_file_path)) {
                $v_error[] = 'Lỗi upload file '.$v_file_name;
            } else {
				$v_img_size = getimagesize($v_file_path);
				if ($p_staus_limit == false) {
                    if ($p_check_ty_le === false) {
                        if ($p_arr_length[0] > 0 && $v_img_size[0] != $p_arr_length[0]) {
                            $v_error[] = 'Chiều rộng file '.$v_file_name.' khác '.$p_arr_length[0];
                        }
                        if ($p_arr_length[1] > 0 && $v_img_size[1] != $p_arr_length[1]) {
                            $v_error[] = 'Chiều cao file '.$v_file_name.' khác '.$p_arr_length[1];
                        }
                    } else {
                        if (check_array($p_arr_length) && $p_arr_length[1] != '') {
                            $v_ty_le = round($p_arr_length[0]/$p_arr_length[1]);
                        }
                        if ($v_img_size[1] != 0 && round($v_img_size[0]/$v_img_size[1]) != $v_ty_le) {
                            $v_error[] = "Bạn phải nhập ảnh có kích thước ".$p_arr_length[0]."x".$p_arr_length[1]." px hoặc ảnh nhỏ hơn nhưng có cùng tỷ lệ";
                        }
                    }
                } else {
                    if ($p_check_ty_le === false) {
                        if ($p_arr_length[0] > 0 && $v_img_size[0] > $p_arr_length[0]) {
                            $v_error[] = 'Chiều rộng file '.$v_file_name.' lớn hơn '.$p_arr_length[0];
                        }
                        if ($p_arr_length[1] > 0 && $v_img_size[1] > $p_arr_length[1]) {
                            $v_error[] = 'Chiều cao file '.$v_file_name.' lớn hơn '.$p_arr_length[1];
                        }
                    } else {
                        if (check_array($p_arr_length) && $p_arr_length[1] != '') {
                            $v_ty_le = round($p_arr_length[0]/$p_arr_length[1]);
                        }
                        if ($v_img_size[1] != 0 && round($v_img_size[0]/$v_img_size[1]) != $v_ty_le) {
                            $v_error[] = "Bạn phải nhập ảnh có kích thước ".$p_arr_length[0]."x".$p_arr_length[1]." px hoặc ảnh nhỏ hơn nhưng có cùng tỷ lệ";
                        }
                    }
                }
                if (count($v_error) == 0) {
                    _chmode_777( $v_file_path);
                    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                    //optimize_images($v_file_path,$p_quality,$p_bkSubfix);
                    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                    $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
                } else {
                    unlink($v_file_path);
                    $v_uploaded_file = '';
                }
            }
        }
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file);
        return $v_return;
    }

    /**
	 * Upload nhieu anh
	 */
    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    function act_upload_multi_image($p_file, $p_max_size=0, $p_arr_length=array(0,0), $p_max_size_2 = 0, $p_quality = null, $p_bkSubfix = null, $p_limit_size = false)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
		//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
		$v_image_type = '';
		$v_image_type = fw24h_replace_bad_char($_REQUEST['image_type']);
		$v_folder_resize_anh_gif = _get_module_config('upload_image','v_folder_resize_anh_gif');
		$v_max_image_gif_size = _get_module_config('upload_image','v_max_image_gif_size');
		$v_arr_image_type = array();
		//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        $v_upload_path = $this->act_create_upload_folder();
        $v_error = array();
        $v_uploaded_file = array();

        $p_max_size = intval($p_max_size);            
        // Giới hạn dung lượng tối đa được upload
        $p_max_size = ($p_max_size > 0 && $p_max_size <= MAX_IMAGE_SIZE) ? $p_max_size : MAX_IMAGE_SIZE;   
		
		//04-05-2015 : Thangnb them 1 truong hop thay doi max size anh
		if (intval($p_max_size_2) > 0) {
			$p_max_size = $p_max_size_2;
		}
        for ($i=0, $n=count($p_file['name']); $i<$n; $i++) {
            $v_file_name = $p_file['name'][$i];
            $v_file_size = $p_file['size'][$i];
            $v_file_type = $p_file['type'][$i];
			//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
			if ($v_image_type == 'gif') {
				if ($v_file_type != 'image/gif') {
					$v_error[] = 'Bạn phải upload ảnh GIF';            
					$this->write_log($v_file_name); // Ghi log 
					continue;
				} else {
					$p_max_size = $v_max_image_gif_size;	
				}
			}
			//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
            if($v_file_name=='') {
                continue;
            }

            //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
            if(!$this->valid_file_extension($v_file_name, IMAGE_EXTENSION_ALLOW)) {       
				$v_error[] = 'Phần mở rộng file '.$v_file_name.' không hợp lệ';            
                $this->write_log($v_file_name); // Ghi log 
				continue;
            }
            //end: phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
            
            $v_file_name = $this->change_image_name($p_file['tmp_name'][$i], $v_file_name);
            if ($v_file_size>$p_max_size) {
                $v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$p_max_size;
                continue;
            }

            $v_file_path = $v_upload_path.$v_file_name;
            if ($p_file['tmp_name'] == '' || !copy($p_file['tmp_name'][$i], $v_file_path)) {
                $v_error[] = 'Lỗi upload file '.$v_file_name;
                continue;
            } else {
                $v_img_size = getimagesize($v_file_path);
                $v_err_length = false;
                if ($p_limit_size == false) {
                    if ($p_arr_length[0] > 0 && $v_img_size[0] != $p_arr_length[0]) {
                        $v_error[] = 'Chiều rộng file '.$v_file_name.' khác '.$p_arr_length[0];
                        $v_err_length = true;
                    }
                    if ($p_arr_length[1] > 0 && $v_img_size[1] != $p_arr_length[1]) {
                        $v_error[] = 'Chiều cao file '.$v_file_name.' khác '.$p_arr_length[1];
                        $v_err_length = true;
                    }
                } else {
                    if ($p_arr_length[0] > 0 && $v_img_size[0] > $p_arr_length[0]) {
                        $v_error[] = 'Chiều rộng file '.$v_file_name.' lớn hơn '.$p_arr_length[0];
                        $v_err_length = true;
                    }
                    if ($p_arr_length[1] > 0 && $v_img_size[1] > $p_arr_length[1]) {
                        $v_error[] = 'Chiều cao file '.$v_file_name.' lớn hơn '.$p_arr_length[1];
                        $v_err_length = true;
                    }
                }
                if (!$v_err_length) {
                    _chmode_777( $v_file_path);
                    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                    //optimize_images($v_file_path,$p_quality,$p_bkSubfix);
                    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
					$v_uploaded_file[] = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
					//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
					if ($v_image_type == 'gif') {
						$v_arr_image_type[] = 'gif';
						$this->act_create_thumbnail('/'.str_replace(ROOT_FOLDER, '', $v_file_path), $v_img_size[0], $v_img_size[1], $v_folder_resize_anh_gif);	
					} else {
						$v_arr_image_type[] = '';
					}
                } else {
                    unlink($v_file_path);
                }
            }
        }
        // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file, 'image_type' => $v_arr_image_type);
		//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        return $v_return;
    }

    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    /**
	 * Tao anh thumbnail
	 */
    function act_create_thumbnail($p_file_path, $p_max_width, $p_max_height, $p_name_ext, $p_name_type='', $p_crop=false, $p_quality = null, $p_bkSubfix = null,$p_resize_config =false)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        $p_max_width = ($p_max_width==0) ? 2000 : $p_max_width;
        $p_max_height = ($p_max_height==0) ? 2000 : $p_max_height;
        $v_image_path = substr(ROOT_FOLDER, 0, -1).$p_file_path;
        list($v_width, $v_height, $v_type) = getimagesize($v_image_path);
		if(intval($v_height) == 0){
			$errorMsg = date('Y-m-d H:i:s ')." url: ".$v_image_path." \n";
            @error_log($errorMsg, 3, WEB_ROOT.'/logs/act_create_thumbnail_err.log');
			return;
		}

        $v_gold_percent = $p_max_width/$p_max_height;
        $v_current_percent = $v_width/$v_height;

        $x_ratio = $p_max_width/$v_width;
        $y_ratio = $p_max_height/$v_height;
        if ($v_current_percent < $v_gold_percent) { // anh doc
            $tn_height = $p_max_height;
            $tn_width = ceil($y_ratio * $v_width);
            $src_width = $v_width;
            $src_height = ceil($src_width/$v_gold_percent);
            $x = 0;
            $y = 0;
        } else { // anh ngang
            $tn_width = $p_max_width;
            $tn_height = ceil($x_ratio * $v_height);
            $src_height = $v_height;
            $src_width = $v_gold_percent*$src_height;
            $x = ceil(($v_width-$src_width)/2);
            $y = 0;
        }
        // resize đúng kích thước config
        if($p_resize_config){
            $tn_width = $p_max_width;
            $tn_height = $p_max_height;
            $src_height = $v_height;
            $src_width = $v_width;
            $x = 0;
            $y = 0;
        }		 

        $v_image_name = substr($p_file_path, strrpos($p_file_path, '/')+1);
        $v_image_name_without_ext = substr($v_image_name, 0, strrpos($v_image_name, '.'));
        $v_image_ext = str_replace($v_image_name_without_ext, '', $v_image_name);
        switch ($p_name_type) {
            case 'prefix':
                $v_output_name = str_replace($v_image_name, $p_name_ext.'_'.$v_image_name, $p_file_path);
                break;
            case 'subfix':
                $v_output_name = str_replace($v_image_name, $v_image_name_without_ext.'_'.$p_name_ext.$v_image_ext, $p_file_path);
                break;
            case 'folder':
            default:
                // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                $v_thumbnail_path = str_replace($v_image_name, '', $p_file_path).$p_name_ext.'/';
                if (strpos($v_thumbnail_path, ROOT_FOLDER) === false) {
                    $v_thumbnail_path = substr(ROOT_FOLDER, 0, -1) . $v_thumbnail_path;
                }
                // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                if (!is_dir($v_thumbnail_path)) {
                    mkdir($v_thumbnail_path, 0777);
                }
                _chmode_777($v_thumbnail_path);
                // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                //optimize_images($v_thumbnail_path,$p_quality,$p_bkSubfix);
                // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                $v_output_name = '/'.str_replace(ROOT_FOLDER, '', $v_thumbnail_path).$v_image_name;
                break;
        }

        switch ($v_type) {
            case 1: $v_image_create = imagecreatefromgif($v_image_path); break;
            case 2: $v_image_create = imagecreatefromjpeg($v_image_path); break;
            case 3: $v_image_create = imagecreatefrompng($v_image_path); break;
            default: return ''; break;
        }

        if ($p_crop) {
            $v_new_image = imagecreatetruecolor($p_max_width, $p_max_height);
            imagecopyresampled($v_new_image, $v_image_create, 0, 0, $x, $y, $p_max_width, $p_max_height, $src_width, $src_height);
        } else {
            $v_new_image = imagecreatetruecolor($tn_width, $tn_height);
            imagecopyresampled($v_new_image, $v_image_create, 0, 0, 0, 0, $tn_width, $tn_height, $v_width, $v_height);
        }

        // Generate the file, and rename it
        $v_output_path = substr(ROOT_FOLDER, 0, -1).$v_output_name;
        switch ($v_type) {
            case 1: imagegif($v_new_image, $v_output_path); break;
            case 2: imagejpeg($v_new_image, $v_output_path, 90); break;
            case 3: imagepng($v_new_image, $v_output_path); break;
            default: return ''; break;
        }
        _chmode_777($v_output_path);

        // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        //optimize_images($v_output_path,$p_quality,$p_bkSubfix);
        // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        return $v_output_name;
    }

    /**
     * Upload anh tu url
     */
	 // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    function act_upload_image_from_url($p_url, $p_max_size=0, $p_arr_length=array(0,0), $p_upload_path='', $is_magazine_image = false, $p_quality = null, $p_bkSubfix = null)
    {
	// End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        if ($p_upload_path != '') {
            $v_upload_path = $this->act_create_upload_folder($p_upload_path);
        } else {
            $v_upload_path = $this->act_create_upload_folder();
        }
        $v_file_name = basename($p_url);
		
        //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
		$v_error = array();
        if(!$this->valid_file_extension($v_file_name, IMAGE_EXTENSION_ALLOW)) {       
			$v_error[] = 'Phần mở rộng file '.$v_file_name.' không hợp lệ';            
			$this->write_log($v_file_name); // Ghi log 
			$v_return = array('errors' => $v_error, 'file_path' => $v_file_name);
			return $v_return;
        }
        
        // phuonghv add 01/04/2015 Giới hạn dung lượng tối đa được upload
		//Begin 07-12-2016 : Thannb xu_ly_bai_magazine backend
		if ($is_magazine_image == false) {
        $p_max_size = ($p_max_size > 0 && $p_max_size <= MAX_IMAGE_SIZE) ? $p_max_size : MAX_IMAGE_SIZE;   
		}
		//End 07-12-2016 : Thannb xu_ly_bai_magazine backend
            
        $v_file_name = $this->validate_file_name($v_file_name);
        /* begin 18/10/2017 TuyenNT bo_sung_xu_ly_hien_thi_bai_magazin_tren_ban_amp_24h */
        // Lấy đuôi mở rộng của file
        $ext = strtolower(pathinfo($v_file_name, PATHINFO_EXTENSION));
        // Lấy thông tin kích cỡ ảnh
        $v_img_size_tmp = '';
		if ($v_file_name != '' && !empty($v_file_name)) {
        	$v_img_size_tmp = getimagesize($p_url);
		}
        // Đổi chữ hoa thành chữ thường
        $v_file_name = strtolower($v_file_name);
        // Lấy tên file ảnh không bao gồm đuôi mở rộng
        $v_file_name    = str_replace(".$ext", '', $v_file_name);
        // Gắn thêm width[chiều rộng] height[chiều cao] vào tên ảnh
        $v_file_name    = $v_file_name.'-'.time().'-'.'width'.$v_img_size_tmp[0].'height'.$v_img_size_tmp[1].".$ext";
        /* end 18/10/2017 TuyenNT bo_sung_xu_ly_hien_thi_bai_magazin_tren_ban_amp_24h */
        $v_file_path = $v_upload_path.$v_file_name;

        $v_file_content = file_get_contents_curl($p_url);
        if (preg_match('#404 Not Found#i', $v_file_content)) {
            $v_error[] = 'File '.$v_file_name.' không tồn tại';
        } else {
            $result = file_put_contents($v_file_path, $v_file_content);

            $v_uploaded_file = '';
            if ($result === false) {
                $v_error[] = 'Lỗi upload file '.$v_file_name.'!';
            } else {
                $p_max_size = intval($p_max_size);
                $v_file_size = filesize($v_file_path);
                if ($p_max_size > 0 && $v_file_size > $p_max_size) {
                    $v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$p_max_size;
                }

                $v_img_size = getimagesize($v_file_path);
                if ($p_arr_length[0] > 0 && $v_img_size[0] != $p_arr_length[0]) {
                    $v_error[] = 'Chiều rộng file '.$v_file_name.' khác '.$p_arr_length[0];
                }
                if ($p_arr_length[1] > 0 && $v_img_size[1] != $p_arr_length[1]) {
                    $v_error[] = 'Chiều cao file '.$v_file_name.' khác '.$p_arr_length[1];
                }
            }
            if (count($v_error) == 0) {
                _chmode_777( $v_file_path);
                // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                //optimize_images($v_file_path,$p_quality,$p_bkSubfix);
                // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
            } else {
                unlink($v_file_path);
            }
        }
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file);
        return $v_return;
    }

    /**
     * Upload anh tu url
     */
    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    function act_upload_image_from_string($p_string, $p_max_size=0, $p_arr_length=array(0,0), $p_upload_path='', $p_quality = null, $p_bkSubfix = null)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        $v_arr_image_data = explode(',', $p_string);
        $v_image_string = $v_arr_image_data[1];
        $v_image_type = str_replace(array('data:image/', ';base64'), '', $v_arr_image_data[0]);
        $v_img = imagecreatefromstring(base64_decode($v_image_string));
        // Giới hạn dung lượng tối đa được upload
        $p_max_size = ($p_max_size > 0 && $p_max_size <= MAX_IMAGE_SIZE) ? $p_max_size : MAX_IMAGE_SIZE; 
        
        $v_error = array();
        $v_uploaded_file = '';
        if($v_img != false) {
            if ($p_upload_path != '') {
                $v_upload_path = $this->act_create_upload_folder($p_upload_path);
            } else {
                $v_upload_path = $this->act_create_upload_folder();
            }
            $v_file_name = $this->validate_file_name('local');
            $v_file_path = $v_upload_path.$v_file_name;
            switch ($v_image_type) {
                case 'jpeg':
                    $v_file_path = $v_file_path.'.jpg';
                    $result = imagejpeg($v_img, $v_file_path);
                    break;
                case 'gif':
                    $v_file_path = $v_file_path.'.gif';
                    $result = imagegif($v_img, $v_file_path);
                    break;
                case 'png':
                    $v_file_path = $v_file_path.'.png';
                    $result = imagepng($v_img, $v_file_path);
                    break;
            }
            if ($result !== false) {
                imagedestroy($v_img);

                $p_max_size = intval($p_max_size);
                $v_file_size = filesize($v_file_path);
                if ($p_max_size > 0 && $v_file_size > $p_max_size) {
                    $v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$p_max_size;
                }

                $v_img_size = getimagesize($v_file_path);
                if ($p_arr_length[0] > 0 && $v_img_size[0] != $p_arr_length[0]) {
                    $v_error[] = 'Chiều rộng file '.$v_file_name.' khác '.$p_arr_length[0];
                }
                if ($p_arr_length[1] > 0 && $v_img_size[1] != $p_arr_length[1]) {
                    $v_error[] = 'Chiều cao file '.$v_file_name.' khác '.$p_arr_length[1];
                }
            }
            if (count($v_error) == 0) {
                _chmode_777( $v_file_path);
                // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                //optimize_images($v_file_path,$p_quality,$p_bkSubfix);
                // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
            } else {
                unlink($v_file_path);
            }
        }
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file);
        return $v_return;
    }

    /**
     * Hien thi bang quy dinh dung luong/so luong anh
     */
    function dsp_regulation_table()
    {
        $this->render($this->thisPath().'view/dsp_regulation_table.php');
        return $this->blockContent;
    }

    function validate_file_name($p_file_name)
    {
        $v_file_name = _xu_ly_ten_file($p_file_name);
        // Begin TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
        $v_file_name = time().'-'.rand(1, 999).'-'.$v_file_name;
        // End TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
        return $v_file_name;
    }
    
    /*
    * Ham kiem tra phan mo rong cua file
    * phuonghv add 31/03/2015
    * @param $p_file_name tên file cần kiểm tra
    * @param $p_extension_allow Phần mở rộng của file hợp lệ
    * @return boolean
    */
	//Begin 13-06-2016 : Thangnb upload_anh_so_sanh
    function valid_file_extension($p_file_name, $p_extension_allow=''){
        return _valid_file_extension($p_file_name, $p_extension_allow);
    }
    //End 13-06-2016 : Thangnb upload_anh_so_sanh
    /*
    * function ghi log file upload bi loi
    * phuonghv add 31/03/2015
    * @param $p_file_name tên file cần kiểm tra    
    * @return array
    */
    function write_log($p_file_name) {
		_write_log_bad_upload_file($p_file_name);
        return;
    }
	
	/* 
	//Begin 13-06-2016 : Thangnb upload_anh_so_sanh
	*/
    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    function act_upload_single_image_anh_so_sanh($p_file, $p_quality = null, $p_bkSubfix = null)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
		$v_upload_path = $this->act_create_upload_folder();
		$v_max_size_anh_so_sanh = _get_module_config('upload_image','v_max_size_anh_so_sanh');
		$v_extension_allow_anh_so_sanh = _get_module_config('upload_image','v_extension_allow_anh_so_sanh');
		$v_max_width_anh_so_sanh = _get_module_config('upload_image','v_max_width_anh_so_sanh');
		
        $v_file_name = $p_file['name'];
        $v_file_size = $p_file['size'];
        $v_file_type = $p_file['type'];

        $v_error = array();
        // Lấy tên ảnh nội dung bài theo định dạng mới
        $v_file_name = $this->change_image_name($p_file['tmp_name'], $v_file_name);
		
        //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
        if(!$this->valid_file_extension($v_file_name, $v_extension_allow_anh_so_sanh)) {
	        $v_error[] = 'Ảnh upload '.$v_file_name.' không đúng định dạng '.$v_extension_allow_anh_so_sanh;
			$v_return = array('errors' => $v_error, 'file_path' => $v_file_name);
			return $v_return;
        }
        //end: phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.

        if ($v_file_size > $v_max_size_anh_so_sanh) {
			$v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.round($v_file_size/1024).' KB'.' > '.round($v_max_size_anh_so_sanh/1024).' KB';
        }
        $v_file_path = $v_upload_path.$v_file_name;
        $v_uploaded_file = '';
        if (count($v_error) == 0) {
            if ($p_file['tmp_name'] == '' || !copy($p_file['tmp_name'], $v_file_path)) {
                $v_error[] = 'Lỗi upload file '.$v_file_name;
            } else {
				$v_img_size = getimagesize($v_file_path);
				
				if ($v_img_size[0] > $v_max_width_anh_so_sanh) {
					$v_error[] = 'Chiều rộng file '.$v_file_name.' lớn hơn '.$v_max_width_anh_so_sanh.'px';
				}

                if (count($v_error) == 0) {
                    _chmode_777( $v_file_path);
                    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                    //optimize_images($v_file_path,$p_quality,$p_bkSubfix);
                    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
                    $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
                } else {
                    unlink($v_file_path);
                    $v_uploaded_file = '';
                }
            }
        }
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file, 'file_path_to_delete' => $v_file_path, 'v_width' => $v_img_size[0], 'v_height' => $v_img_size[1]);
        return $v_return;
    }
	//Begin 27-12-2016 : Thangnb toi_uu_day_bai_infographic
    function act_upload_file_font_from_url($p_url, $p_upload_path='')
    {
        if ($p_upload_path != '') {
            $v_upload_path = $this->act_create_upload_folder_font($p_upload_path);
        } else {
            $v_upload_path = $this->act_create_upload_folder_font();
        }
        $v_file_name = basename($p_url);
        //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
		$v_error = array();
        if(!$this->valid_file_extension($v_file_name, FONT_EXTENSION_ALLOW)) {       
			$v_error[] = 'Phần mở rộng file '.$v_file_name.' không hợp lệ';            
			$this->write_log($v_file_name); // Ghi log 
			$v_return = array('errors' => $v_error, 'file_path' => $v_file_name);
			return $v_return;
        }
        
        $v_file_path = $v_upload_path.$v_file_name;

		if (file_exists($v_file_path)) {
			$v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
		} else {
			$v_file_content = file_get_contents_curl($p_url);
			if (preg_match('#404 Not Found#i', $v_file_content)) {
				$v_error[] = 'File '.$v_file_name.' không tồn tại';
			} else {
				$result = file_put_contents($v_file_path, $v_file_content);

				$v_uploaded_file = '';
				if ($result === false) {
					$v_error[] = 'Lỗi upload file '.$v_file_name.'!';
				}
				if (count($v_error) == 0) {
					_chmode_777($v_file_path);
					$v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
				} else {
					unlink($v_file_path);
				}
			}
		}
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file);
        return $v_return;
    }
	
    /**
	 * Thangnb - Tao thu muc font
	 */
    function act_create_upload_folder_font($p_upload_path='')
    {
        $p_upload_path = ($p_upload_path != '') ? $p_upload_path : UPLOAD_FOLDER;
		
        $path = rtrim($p_upload_path,'/').'/font/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
            _chmode_777($path);;
        }
        return $path;
    }
	//End 27-12-2016 : Thangnb toi_uu_day_bai_infographic
    /* Begin 
     * @desc    Hàm xử lý đổi tên ảnh
     * @author  TuyenNT 
     * @date    30/11/2016
     * @params:
     *      $p_file_tmp     đường dẫn tạm lưu file ảnh trước khi upload
     *      $p_file_name    tên file ảnh
     *      $p_news_title   tên tiêu đề bài viết
     */
    function change_image_name($p_file_tmp, $p_file_name = '', $p_news_title = ''){
        // Lấy tên bài viết
        $v_news_title   = ($p_news_title != '') ? $p_news_title : fw24h_replace_bad_char($_REQUEST['news_title']);
        // Đổi title bài viết về dạng không dấu
        $v_news_title   = fw24h_iso_ascii($v_news_title, '');
        // Xóa bỏ các kí tự đặc biệt
        if($v_news_title != ''){
            $v_news_title = preg_replace('#[^\-a-zA-Z0-9_\.]#', '-', $v_news_title);
        }
        // Lấy đuôi mở rộng của file
        $ext = strtolower(pathinfo($p_file_name, PATHINFO_EXTENSION));
        // Lấy thông tin kích cỡ ảnh
        $v_img_size_tmp = getimagesize($p_file_tmp);
        // Thay thế các chuỗi, ký tự đặc biệt không được validate trong tên ảnh
        $v_file_name = preg_replace('#[^\-a-zA-Z0-9_\.]#', '-', $p_file_name);
        // Đổi chữ hoa thành chữ thường
        $v_file_name = strtolower($v_file_name);
        // Lấy tên file ảnh không bao gồm đuôi mở rộng
        $v_file_name    = str_replace(".$ext", '', $v_file_name);
        
        // Kếu có tồn tại tiêu đề bài viết
        // Gắn thêm width[chiều rộng] height[chiều cao] vào tên ảnh
        if($v_news_title != ''){
            if (strlen($v_news_title) > 100) {
                $v_news_title = substr($v_news_title, 0, 100);	
            }
			if (strlen($v_file_name) > 50) {
				$v_file_name = substr($v_file_name, 0, 50);	
			}
            // Begin TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
            $v_file_name    = $v_news_title.'-'.$v_file_name.'-'.time().'-'.rand(1, 999).'-'.'width'.$v_img_size_tmp[0].'height'.$v_img_size_tmp[1].".$ext";
            // End TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
        } else {
            if (strlen($v_file_name) > 150) {
				$v_file_name = substr($v_file_name, 0, 150);	
			}
            // Begin TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
            $v_file_name = $v_file_name.'-'.time().'-'.rand(1, 999).'-'.'width'.$v_img_size_tmp[0].'height'.$v_img_size_tmp[1].".$ext";
            // End TungVN 03-11-2017 - toi_uu_xu_ly_ten_anh_khi_upload
        }
        
        return $v_file_name;
    }
    // Begin Tytv - 16/01/2018 - toi_uu_nen_anh_24h
    /**
	 * Tao anh thumbnail gif
	 */
    function act_create_thumbnail_gif($p_file_path, $p_arr_thumbnail_info, $p_quality = null)
    {
    // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        $v_image_path = substr(ROOT_FOLDER, 0, -1).$p_file_path;
        $p_name_ext = $p_arr_thumbnail_info['folder'];

        $v_image_name = substr($p_file_path, strrpos($p_file_path, '/')+1);
        $v_image_name_without_ext = substr($v_image_name, 0, strrpos($v_image_name, '.'));
        $v_image_ext = str_replace($v_image_name_without_ext, '', $v_image_name);
        
        $v_thumbnail_path = str_replace($v_image_name, '', $p_file_path).$p_name_ext.'/';
        if (strpos($v_thumbnail_path, ROOT_FOLDER) === false) {
            $v_thumbnail_path = substr(ROOT_FOLDER, 0, -1) . $v_thumbnail_path;
        }
        // End Tytv - 16/01/2018 - toi_uu_nen_anh_24h
        if (!is_dir($v_thumbnail_path)) {
            mkdir($v_thumbnail_path, 0777);
        }
        $v_output_name = $v_thumbnail_path.$v_image_name; 
                 
        $v_resize_gif_image = resize_gif_image($v_image_path, $v_output_name, $p_arr_thumbnail_info, $p_quality);
        return $v_resize_gif_image;
    }
}
