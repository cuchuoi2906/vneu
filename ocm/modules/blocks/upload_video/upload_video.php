<?php
__get_db_functions('db.user');
__get_db_functions('db.category');
_setLayout('ajax');

class upload_video_block extends Fw24H_Block
{
    // khai bao danh sach quyen thao tac
    var $_arr_permision = array (
        'admin' =>'ADMIN_OCM_24H',
        'view' =>'VIEW_BAI',
        'edit' =>'EDIT_BAI',
        'upload'=>'UPLOAD_VIDEO', //phuonghv add 06/10/2015 bổ sung quyền upload video làm banner
    );
    
    /**
     * Hien thi form upload video
     */
    function index()
    {
        html_set_title('Upload video');          
        $v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'], 1);
        //phuonghv add 01/10/2015 lây cấu hình danh sách loại video           
        $this->setParam('rs_upload_video_conf', _get_module_config('upload_video','upload_video_conf'));
        $this->setParam('rs_loai_video', _get_module_config('upload_video','v_arr_loai_video'));
        //end 01/10/2015
        $this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->render($this->thisPath().'view/dsp_upload_video_form.php');
    }
    
    /**
     * Upload video len server
     */
    function act_upload()
    {
        // pre($_POST);
        $v_category_id = intval($_POST['rad_category']);
        $v_uploaded_file = array();
        $v_files = $_FILES['file_video'];
        $v_count = 0;
        $v_number_files = count($v_files['name']);
        for ($i=0; $i<$v_number_files; $i++) {
            if ($v_files['name'][$i]=='') {
                continue;
            }
            $v_count++;
        }
        $upload_video_conf = _get_module_config('upload_video', 'upload_video_conf');
        //phuonghv add 01/10/2015 lây cấu hình danh sách loại video
        $rs_loai_video = _get_module_config('upload_video','v_arr_loai_video');
        $v_loai_video  = $_POST['sel_loai_video'];
        if (!isset($v_loai_video) || $v_loai_video == '' ) {
            $v_loai_video  = _get_module_config('upload_video','VIDEO_THUONG');
        }       
        $v_template_video_code = '';
        
        if($v_loai_video!=''){
            $v_arr_loai_video = get_sub_array_in_array($rs_loai_video, 'c_code', $v_loai_video, true);
            $v_template_video_code = $v_arr_loai_video[0]['c_template_video_code'];
        }
        //end 01/10/2015
        
        if ($v_count == 0) {
            js_message('Bạn chưa chọn video nào!');
            js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
            exit;
        } else if ($v_count > $upload_video_conf['max_file_upload']) {
            js_message('Số file upload không được quá '.$upload_video_conf['max_file_upload'].' file!');
            js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
            exit;
        } else {
            $v_upload_path = $this->act_create_upload_folder();
            $v_max_video_size = intval($upload_video_conf['max_video_size'][$v_category_id]);
            $v_max_video_size = ($v_max_video_size > 0) ? $v_max_video_size : MAX_VIDEO_SIZE;
            for ($i=0; $i<$v_number_files; $i++) {
                $v_file_name = $v_files['name'][$i];
                $v_file_size = $v_files['size'][$i];
                $v_file_type = $v_files['type'][$i];
               
                if ($v_file_name=='') {
                    continue;
                }
                
                $v_file_name = _xu_ly_ten_file($v_file_name);
                $v_file_name = time().'-'.$v_file_name;

                /* Begin anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
                // Nếu là loại video nivea thì thêm tiền tố vào đuôi video
                if($v_loai_video != 'flashWrite'){
                    if(!isset($_POST['sel_loai_giai_dau']) || fw24h_replace_bad_char($_POST['sel_loai_giai_dau']) == ''){
                        js_message('bạn chưa chọn giải đấu');
                        js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
                        exit;
                    }
                    $v_ten_giai_dau = fw24h_replace_bad_char($_POST['sel_loai_giai_dau']);
                    $v_file_name = $v_ten_giai_dau.'-'.$v_file_name;
                }
                /* End anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
                if ($v_file_size > $v_max_video_size) {
                    js_message('File '.($i+1).': '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$v_max_video_size);
                    js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
                    exit;
                }

                //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
                if(!_valid_file_extension($v_file_name, VIDEO_EXTENSION_ALLOW)) {
                    js_message('File '.($i+1).': '.$v_file_name.' không phải định dạng video');
                    _write_log_bad_upload_file($v_file_name); // Ghi log 
                    js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
                    exit;
                }
                //end: phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
                
                $v_file_path = $v_upload_path.$v_file_name;
                /* end: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
                if (!copy($v_files['tmp_name'][$i], $v_file_path)) {
                    js_message('Lỗi upload file '.$v_file_path);
                    js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
                    exit;
                } else {
                    _chmode_777( $v_file_path);
                    $v_uploaded_file[] = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
                }
            }
            if (count($v_uploaded_file) > 0) {
                $v_str_uploaded = implode( ',', $v_uploaded_file);
                //phuonghv edit 01/10/2015
                $v_template_video_code = str_replace('{video_url}', $v_str_uploaded, $v_template_video_code);
                $v_code = htmlspecialchars('<div align="center">'.$v_template_video_code.'</div>');
                //end 01/10/2015
                /* Begin anhpt1 24/5/2016 chuc_nang_upload_video */
                $v_html = '<div style="padding:10px;margin-left: 60px;"><b>Copy toàn bộ code này cho vào bài viết:</b><br><textarea name="txt_video_code" style="width:500px; height:200px" onfocus="this.select();">'.$v_code.'</textarea></div>';
                $v_html_image = '';
                $v_html_image_gif = '';
                $j = 1;
                $v_arr_link_video = array();
            
                $v_txt_uploaded_file = '';
                foreach ($v_uploaded_file as $v_file) {
                    if($j == 1){
                        //video dir
                        $v_arr_link_video[] = $v_file;
                        //$this->act_create_upload_image_folder();
                        //$v_image_file_gif = lay_anh_dai_dien_gif_tu_file_video($v_file);
                        $v_exits_gif_video = $v_image_file_gif != '' ? 1 : 0;
                        //$v_image_file =  lay_anh_dai_dien_tu_file_video($v_file,10,$v_exits_gif_video);
                    }
                    $j++;/* End anhpt1 24/5/2016 chuc_nang_upload_video */
                    $v_txt_uploaded_file .= '<div style="border:1px solid #cccccc;background-color:#ffffcc;padding:10px;margin-bottom:5px;">'.$v_file.'</div>';
                }
                $v_html_link_video = '<input type="hidden" value="'.implode(',', $v_arr_link_video).'" name="txt_link_video" />';
                /* Begin: Tytv 17/02/2016 - bo_xung_tinh_thoi_luong_video_upload */
                //$v_tmp_data_video  = _cap_nhat_du_lieu_file_video($v_uploaded_file);
                $v_tong_thoi_luong = 0;
                //$v_html_image .= html_thoi_luong_video_upload($v_tmp_data_video['v_data']);
                /* End: Tytv 17/02/2016 - bo_xung_tinh_thoi_luong_video_upload */
                call_js('js/jquery-1.7.2.min.js');
                //phuonghv add 01/10/2015 set selected cho select box loai video
                js_set('$("#sel_loai_video", top.document).val(\''.$v_loai_video.'\');');
                js_set('$("#upload_result", top.document).html(\''.$v_html.'\');');
                js_set('$("#upload_result_2", top.document).html(\''.$v_txt_uploaded_file.'\');');
                js_set('$("input:file", top.document).val("");');
                js_set('$("#sel_loai_giai_dau", top.document).val(\''.$v_ten_giai_dau.'\');');
                js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
            }
        }
        js_set('if(parent.document.getElementById("loadding_page")){var element = parent.document.getElementById("loadding_page");element.classList.add("none_load_page");element.classList.remove("show_load_page");}');
        exit;
    }
    
    /**
     * Tao thu muc video
     */
    function act_create_upload_folder($p_upload_path='')
    {
        $p_upload_path = ($p_upload_path != '') ? $p_upload_path : UPLOAD_FOLDER;
        $quarterByDate = quarterByDate(date('m'));
        $path = $p_upload_path.$quarterByDate.'-'.date('Y');
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        _chmode_777($path);
        // tao thu muc
        $path .= '/videoclip/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        _chmode_777($path);
        // tao theo ngay
        $path .= date('Y-m-d').'/';
        if (!is_dir($path)) {
            mkdir($path, 0777);    
        }
        _chmode_777($path);
        return $path;
    }
    
    /**
     * Hien thi form upload video cho mobile
     */
    function dsp_upload_video_mobile_form()
    {
        html_set_title('Upload video cho mobile');
        $v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'], 1);
        $this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->render($this->thisPath().'view/dsp_upload_video_mobile_form.php');
    }
    
    /**
     * Tao thu muc upload video mobile
     */
    function act_create_mobile_upload_folder($p_upload_path='')
    {
        $p_upload_path = ($p_upload_path != '') ? $p_upload_path : UPLOAD_MOBILE_FOLDER;
        $quarterByDate = quarterByDate(date('m'));
        $path = $p_upload_path.$quarterByDate.'-'.date('Y');
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        _chmode_777($path);
        // tao thu muc
        $path .= '/videoclip_hd/';
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }
        _chmode_777($path);
        // tao theo ngay
        $path .= date('Y-m-d').'/';
        if (!is_dir($path)) {
            mkdir($path, 0777);    
        }
        _chmode_777($path);
        return $path;
    }
    
    /**
     * Upload 1 video len server
     */
    /* Begin: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
    function act_upload_single_video($p_file, $p_file_type='', $p_max_size=0, $p_extension_allow=VIDEO_EXTENSION_ALLOW, $p_upload_path='',$p_loai_video_nivea = '',$p_max_width_video=MAX_WIDTH_VIDEO,$p_max_height_video=MAX_HEIGHT_VIDEO)
    {
        if ($p_file_type == 'mobile') {
            $v_upload_path = $this->act_create_mobile_upload_folder($p_upload_path);
        } else {
            $v_upload_path = $this->act_create_upload_folder($p_upload_path);
        }
        
        $v_file_name = $p_file['name'];
        $v_file_size = $p_file['size'];
        $v_file_type = $p_file['type'];
        
        $v_error = array();
        $v_uploaded_file = '';

        if($v_file_name!='') {
            $v_file_name = _xu_ly_ten_file($v_file_name);
            $v_file_name = time().'-'.$v_file_name;

            /* Begin anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
            if($p_loai_video_nivea != ''){
                $v_file_name = $p_loai_video_nivea.$v_file_name;
            }
            /* End anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
            if ($p_max_size > 0 && $v_file_size>$p_max_size) {
                $v_error[] = 'File '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$p_max_size;
            }

            //phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
            if(!_valid_file_extension($v_file_name, $p_extension_allow)) {
                $v_error[] = 'File '.$v_file_name.' không phải định dạng: '.$p_extension_allow; 
                _write_log_bad_upload_file($v_file_name); // Ghi log 
            }
            //end: phuonghv add 31/03/2015 - kiểm tra phần tính hợp lệ của phần mở rộng file.
            /* Begin: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
            if(intval($p_max_height_video) > 0 && intval($p_max_width_video)>0) {
                $height = 1;
                $width = 1;
                try {
                    $cmd = '/usr/local/bin/ffprobe -v error -select_streams v:0 -show_entries stream=width,height -of json '.$p_file['tmp_name'];
                    exec($cmd, $output);
                    $list = array("width"=>intval(explode(":",$output[6])[1]),"height"=>intval(explode(":",$output[7])[1]));
                    $height = intval($list['height']); 
                    $width = intval($list['width']);
                } catch (Exception $exc) {
                    js_message("Có lỗi upload video.");
                    $v_log_file = WEB_ROOT . 'logs/loi_upload_video_log.log';
                    $v_log = date('Y:m:d H:i:s') . " lỗi upload: " . $exc->getMessage();
                    @error_log($v_log, 3, $v_log_file);
                    exit;
                }
                $v_ti_le = $width / $height;
                    if ($height > MAX_HEIGHT_VIDEO || $width > MAX_WIDTH_VIDEO) {
                        js_message('File '.($i+1).': '.$v_file_name.' không đúng kích thước: '.MAX_WIDTH_VIDEO.' x '.MAX_HEIGHT_VIDEO);
                        exit;
                    }
                 echo "Chiều dài: $height , chiều rộng $width , tỉ lệ hiện tại:  $v_ti_le";
                    if ($v_ti_le <  1.75 || $v_ti_le > 1.8) {
                        js_message("File ".($i+1).": ".$v_file_name." không đúng tỉ lệ: 16:9. Vui lòng upload video đúng tỷ lệ 16:9 (~ từ 1.75 - 1.8)");
                        echo "Vui lòng upload video đúng tỷ lệ 16:9";
                        exit;
                    }
            }
            /* End: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
            
            $v_file_path = $v_upload_path.$v_file_name;
            if (!copy($p_file['tmp_name'], $v_file_path)) {
                $v_error[] = 'Lỗi upload file '.$v_file_path; 
            } else {
                _chmode_777($v_file_path);
                $v_uploaded_file = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
                
                # 20150528_hailt_conver_flv__move_metadata
                # add vào ds video đợi fix lại metadata
                $sql = "CALL be_cap_nhat_queue_video_fix_metadata('".fw24h_replace_bad_char($v_file_path)."')";
                $rs = Gnud_Db_write_query($sql);
                # end 20150528_hailt_conver_flv__move_metadata

                // Cập nhật video vào hàng đợi convert m3u8
                be_cap_nhat_queue_video_upload(fw24h_replace_bad_char($v_file_path));
            }  
        }
        $v_return = array('errors' => $v_error, 'file_path' => $v_uploaded_file);
        return $v_return;
    }
    /* End: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
    
    /**
     * Upload video mobile len server
     */
    function act_upload_for_mobile()
    {
        $v_category_id = intval($_POST['rad_category']);
        $upload_video_conf = _get_module_config('upload_video', 'upload_video_conf');
        $v_max_video_size = intval($upload_video_conf['max_video_size'][$v_category_id]);
        $v_max_video_size = ($v_max_video_size > 0) ? $v_max_video_size : MAX_VIDEO_MOBILE_SIZE;
        /* Begin: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
        $v_video_mobile_uploaded = $this->act_upload_single_video($_FILES['file_3gp'], 'mobile', $v_max_video_size,VIDEO_EXTENSION_ALLOW,'','',MAX_WIDTH_VIDEO_MOBILE_THUONG,MAX_HEIGHT_VIDEO_MOBILE_THUONG);
        
        /* End: Tytv - 18/05/2017 - toi_uu_kich_thuoc_video_upload */
        $v_video_mobile_hd_uploaded = $this->act_upload_single_video($_FILES['file_3gp_hd'], 'mobile', $v_max_video_size);
        
        $v_video_mobile_url = '';
        $v_video_mobile_hd_url = '';
        if ($v_video_mobile_uploaded) {
            $v_error = $v_video_mobile_uploaded['errors'];
            if (count($v_error) > 0) {
                js_message('Lỗi upload file thường:\n- '.implode('\n- ', $v_error));
                exit;
            }
            $v_video_mobile_url = $v_video_mobile_uploaded['file_path'];
        }
        if ($v_video_mobile_hd_uploaded) {
            $v_error = $v_video_mobile_hd_uploaded['errors'];
            if (count($v_error) > 0) {
                js_message('Lỗi upload file chất lượng cao:\n- '.implode('\n- ', $v_error));
                exit;
            }
            $v_video_mobile_hd_url = $v_video_mobile_hd_uploaded['file_path'];
        }
        $v_html = '';
        if ($v_video_mobile_url) {
            $v_html .= '<div style="padding:5px;"><b>Link file thường:</b><br><input type="text" style="width:400px;" value="'.$v_video_mobile_url.'" onfocus="this.select();"></div>';
        }
        if ($v_video_mobile_hd_url) {
            $v_html .= '<div style="padding:5px;"><b>Link file chất lượng cao:</b><br><input type="text" style="width:400px;" value="'.$v_video_mobile_hd_url.'" onfocus="this.select();"></div>';
        }
        call_js('js/jquery-1.7.2.min.js');
        js_set('$("#upload_result", top.document).html(\''.$v_html.'\');');
        js_set('$("input:file", top.document).val("");');
        exit;
    }
    
    /**
     * Hien thi trang tao code video
     */
    function dsp_form_gen_code()
    {
        html_set_title('Sinh mã video');
        $this->render($this->thisPath().'view/dsp_form_gen_code.php');
    }
    /**
     * Hien thi form upload video cho chuc nang quan tri banner
     */
    function dsp_form_upload_video_for_banner()
    {
        html_set_title('Upload video');                
        $this->setParam('upload_video_conf', _get_module_config('upload_video', 'upload_video_conf'));        
        $this->render($this->thisPath().'view/dsp_form_upload_video_for_banner.php');
    }
    /**
     * phuonghv add 06/10/2015 bổ sung chức năng upload video làm banner lên server  
     */
    function act_upload_video_for_banner($p_is_copy_file_to_upload_uu_tien=1)
    {
        if (!$this->getPerm('admin,upload')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        $p_is_copy_file_to_upload_uu_tien = intval($p_is_copy_file_to_upload_uu_tien);
        $v_uploaded_file = array();
        $v_files = $_FILES['file_video'];
        $v_count = 0;
        $v_number_files = count($v_files['name']);
        for ($i=0; $i<$v_number_files; $i++) {
            if ($v_files['name'][$i]=='') {
                continue;
            }
            $v_count++;
        }
        $upload_video_conf = _get_module_config('upload_video', 'upload_video_conf');              
        if ($v_count == 0) {
            js_message('Bạn chưa chọn video nào!');
            exit;
        } else {
            $v_upload_path = $this->act_create_upload_folder();
            $v_upload_path_uu_tien='';
            if($upload_video_conf['upload_uutien']!='') {
                $v_upload_path_uu_tien = $this->act_create_upload_folder($upload_video_conf['upload_uutien']);
            }
            $v_max_video_size = intval($upload_video_conf['max_video_size_for_banner']);
            $v_max_video_size = ($v_max_video_size > 0) ? $v_max_video_size : MAX_VIDEO_SIZE;
            for ($i=0; $i<$v_number_files; $i++) {
                $v_file_name = $v_files['name'][$i];
                $v_file_size = $v_files['size'][$i];
                $v_file_type = $v_files['type'][$i];
               
                if ($v_file_name=='') {
                    continue;
                }                
                $v_file_name = _xu_ly_ten_file($v_file_name);
                $v_file_name = time().'-'.$v_file_name;
                
                if ($v_file_size > $v_max_video_size) {
                    js_message('File '.($i+1).': '.$v_file_name.' vượt quá dung lượng cho phép: '.$v_file_size.' > '.$v_max_video_size);
                    exit;
                }
                $v_extension_allow = $upload_video_conf['extension_video_for_banner'];
                $v_extension_allow = $v_extension_allow!=''?$v_extension_allow:VIDEO_EXTENSION_ALLOW;
                if(!_valid_file_extension($v_file_name, $v_extension_allow)) {
                    js_message('File '.($i+1).': '.$v_file_name.' không phải định dạng video');
                    _write_log_bad_upload_file($v_file_name); // Ghi log 
                    exit;
                }
                $v_file_path = $v_upload_path.$v_file_name;
                $v_file_path_upload_uu_tien = $v_upload_path_uu_tien.$v_file_name;
                if (!copy($v_files['tmp_name'][$i], $v_file_path)) {
                    js_message('Lỗi upload file '.$v_file_path);
                    exit;
                } else {                    
                    _chmode_777( $v_file_path);
                    $v_uploaded_file[] = '/'.str_replace(ROOT_FOLDER, '', $v_file_path);
                    
                    # 20150528_hailt_conver_flv__move_metadata
                    # add vào ds video đợi fix lại metadata
                    $sql = "CALL be_cap_nhat_queue_video_fix_metadata('".fw24h_replace_bad_char($v_file_path)."')";
                    $rs = Gnud_Db_write_query($sql);
                    # end 20150528_hailt_conver_flv__move_metadata
                    //Xử lý copy file sang thư mục upload_uutien
                    if($p_is_copy_file_to_upload_uu_tien && $v_upload_path_uu_tien!='') {
                        copy($v_file_path, $v_file_path_upload_uu_tien);
                    }
                }
            }
            $v_domain_video = $upload_video_conf['domain_video'];
            if (count($v_uploaded_file) > 0) {
                $v_str_uploaded = implode( ',', $v_uploaded_file);                               
                $v_txt_uploaded_file = '';
                foreach ($v_uploaded_file as $v_file) {
                    $v_txt_uploaded_file .= '<div style="border:1px solid #cccccc;background-color:#ffffcc;padding:10px;margin-bottom:5px;">'.$v_domain_video.$v_file.'</div>';
                }                
                call_js('js/jquery-1.7.2.min.js');                                
                js_set('$("#upload_result_2", top.document).html(\''.$v_txt_uploaded_file.'\');');
                js_set('$("input:file", top.document).val("");');
            }
        }
        exit;
    }
    /**
     * lấy ảnh đại diện video
     */
    function act_get_image_video($p_thu_tu_video){
        // Thứ tự video
        $v_thu_tu_video = intval($p_thu_tu_video);
        if($v_thu_tu_video <= 0){return;}
        $v_link_video = $_REQUEST['txt_link_video'];
        $v_so_giay_video_nhap_moi = intval($_REQUEST['txt_so_giay_anh_dai_dien_'.$p_thu_tu_video]);
        if($v_so_giay_video_nhap_moi <= 0 ){
            js_message('Số giây bạn chọn không được nhỏ hơn 0');
            exit;
        }
        if($v_link_video != ''){
            $v_arr_link_video = explode(',', $v_link_video);
        }
        $v_file_video = ROOT_FOLDER.$v_arr_link_video[$v_thu_tu_video - 1];
        passthru("/usr/local/bin/ffmpeg -i ".$v_file_video." 2>&1");
        $duration = ob_get_contents();
        ob_end_clean();

        $search='/Duration: (.*?)[.]/';
        $duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE);
        $duration = $matches[1][0];
        $v_so_giay = date('s',strtotime($duration));
        $v_so_phut = date('i',strtotime($duration));
        $v_so_gio = date('H',strtotime($duration));
        $v_tong_so_giay = $v_so_giay + $v_so_phut* 60 + $v_so_gio * 60 *60;
        if($v_so_giay_video_nhap_moi > $v_tong_so_giay){
            js_message('Số giây bạn chọn không được vượt quá thời lượng video');
            exit;
        }
        $v_image_news = lay_anh_dai_dien_tu_file_video($v_arr_link_video[$v_thu_tu_video - 1],$v_so_giay_video_nhap_moi).'?'.time();
        call_js('js/jquery-1.7.2.min.js');
        js_set('$("#img_anh_video'.$v_thu_tu_video.'", top.document).attr("src",\''.$v_image_news.'\');');
        exit;
    }    
    /**
     * Tao thu muc anh
     */
    function act_create_upload_image_folder($p_upload_path='')
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
     * lấy ảnh đại diện video
     */
    function act_get_image_gif_video($p_thu_tu_video){
        // Thứ tự video
        $v_thu_tu_video = intval($p_thu_tu_video);
        if($v_thu_tu_video <= 0){return;}
        $v_link_video = $_REQUEST['txt_link_video'];
        $v_so_giay_video_nhap_moi = intval($_REQUEST['txt_so_giay_anh_gif_dai_dien_'.$p_thu_tu_video]);
        $v_so_giay_video_nhap_moi_den = intval($_REQUEST['txt_so_giay_anh_gif_dai_dien_den_'.$p_thu_tu_video]);
        if($v_so_giay_video_nhap_moi <= 0 ){
            call_js('js/jquery-1.7.2.min.js');
            js_message('Số giây bạn chọn không được nhỏ hơn 0');
            js_set('$("#loadding_page", top.document).removeClass("show_load_page");$("#loadding_page", top.document).addClass("none_load_page");');
            exit;
        }
        if($v_so_giay_video_nhap_moi_den <= 0 ){
            call_js('js/jquery-1.7.2.min.js');
            js_message('Số giây đến không được nhỏ hơn 0');
            js_set('$("#loadding_page", top.document).removeClass("show_load_page");$("#loadding_page", top.document).addClass("none_load_page");');
            exit;
        }
        if($v_so_giay_video_nhap_moi >= $v_so_giay_video_nhap_moi_den ){
            call_js('js/jquery-1.7.2.min.js');
            js_message('Số giây đến phải lớn hơn số giây từ');
            js_set('$("#loadding_page", top.document).removeClass("show_load_page");$("#loadding_page", top.document).addClass("none_load_page");');
            exit;
        }
        if($v_link_video != ''){
            $v_arr_link_video = explode(',', $v_link_video);
        }
        $v_file_video = ROOT_FOLDER.$v_arr_link_video[$v_thu_tu_video - 1];
        passthru("/usr/local/bin/ffmpeg -i ".$v_file_video." 2>&1");
        $duration = ob_get_contents();
        ob_end_clean();

        $search='/Duration: (.*?)[.]/';
        $duration=preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE);
        $duration = $matches[1][0];
        $v_so_giay = date('s',strtotime($duration));
        $v_so_phut = date('i',strtotime($duration));
        $v_so_gio = date('H',strtotime($duration));
        $v_tong_so_giay = $v_so_giay + $v_so_phut* 60 + $v_so_gio * 60 *60;
        if($v_so_giay_video_nhap_moi > $v_tong_so_giay){
            call_js('js/jquery-1.7.2.min.js');
            js_message('Số giây bạn chọn không được vượt quá thời lượng video');
            js_set('$("#loadding_page", top.document).removeClass("show_load_page");$("#loadding_page", top.document).addClass("none_load_page");');
            exit;
        }
        $v_image_news = lay_anh_dai_dien_gif_tu_file_video($v_arr_link_video[$v_thu_tu_video - 1],$v_so_giay_video_nhap_moi,$v_so_giay_video_nhap_moi_den).'?'.time();
        call_js('js/jquery-1.7.2.min.js');
        js_set('$("#img_anh_gif_video'.$v_thu_tu_video.'", top.document).attr("src",\''.$v_image_news.'\');');
        js_set('$("#loadding_page", top.document).removeClass("show_load_page");$("#loadding_page", top.document).addClass("none_load_page");');
        exit;
    }
} 
