<?php
// begin 23-07-2018 bangnd XLCYCMHENG_28355_xay_dung_chuc_nang_quan_tri_noi_dung_bai_magazine
__get_db_functions('db.user');
__get_db_functions('db.general');
__get_db_functions('db.category');
__get_db_functions('db.magazine');
__get_db_functions('db.template_magazine');
fw24h_add_module_function('template_magazine');
include_once WEB_ROOT.'editor/'.EDITOR.'/index.php';

class magazine_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	public $_arr_permision = array (
		'admin' =>ADMIN_OCM_24H,
		'view' =>'VIEW_NOI_DUNG_MAGAZINE',
        'update' =>'UPDATE_NOI_DUNG_MAGAZINE',
        'delete' =>'DELETE_NOI_DUNG_MAGAZINE',
        'publish' =>'PUBLISH_NOI_DUNG_MAGAZINE',
        'add_bai_tham_khao' =>'ADD_BAI_THAM_KHAO_MAGAZINE',
        'view_bai_tham_khao' =>'VIEW_BAI_THAM_KHAO_MAGAZINE',
	);
       
	public $_arr_arg = array (
        'hdn_item_id' => array(-100, 'intval')
        ,'sel_user_id' => array(0, 'intval')
        ,'txt_user_id' => array(0, 'intval')
        ,'txt_template_id' => array(0, 'intval')
        ,'txt_template_name' => array('', 'fw24h_replace_bad_char')
        ,'txt_magazine_name' => array('', 'fw24h_replace_bad_char')
        ,'sel_template_status' => array(-1, 'fw24h_replace_bad_char')
        ,'sel_template_use_status' => array(-1, 'fw24h_replace_bad_char')
        ,'page' => array(1, 'page_val')
        ,'number_per_page' => array(20, 'intval')
        ,'sel_category_id' => array(0, 'intval')
        ,'txt_category_id' => array('Nhập chuyên mục', 'fw24h_replace_bad_char')
        ,'txt_news_id' => array(0, 'intval')
        ,'stt' => array(0, 'intval')
        ,'code' => array('', 'fw24h_replace_bad_char')
        ,'target' => array('', 'fw24h_replace_bad_char')
	);

    public $ten_module ='template_magazine';
    private $template_fileupload_config = array();
    private $template_element_config  = array();
    
    function __construct() {
        parent::__construct();
        $this->template_fileupload_config       = _get_module_config('template_magazine', 'template_fileupload_config');
        $this->template_element_config          = _get_module_config('template_magazine', 'template_element_config');
    }
    
    public function index()
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $this->getRequest();
        // các tiêu thức lọc
        $page = (int) $this->_GET['page'];

        $number_per_page = (int)$this->_GET['number_per_page'];

        $v_magazine_name    = $this->_GET['txt_magazine_name'];
        $v_status           = $this->_GET['sel_template_status'];
        $v_edited_by        = $this->_GET['sel_user_id'];
        $v_category_id      = $this->_GET['sel_category_id'];
        $v_news_id          = $this->_GET['txt_news_id'];

        $v_magazines = be_get_all_magazine($v_magazine_name, $v_category_id, $v_edited_by, -1, $v_news_id, $page, $number_per_page);
//        tuannt bo sung thong tin su dung template magazine
        $v_magazines = $this->add_info_template_used($v_magazines);
        
//        tuannt bo sung thong tin su dung template magazine
        $v_record_count = count($v_magazines['data']);
        $v_arr_trang_thai = get_list_trang_thai_xuat_ban();
        $v_arr_trang_thai_sd = get_list_trang_thai_su_dung();
        $v_arr_trang_thai_bai_tham_khao = get_list_bai_tham_khao_magazine();

        $v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'],-1);

        $this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->setParam('sel_template_status', $v_status);
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
        $this->setParam('v_arr_trang_thai_bai_tham_khao', $v_arr_trang_thai_bai_tham_khao);
        $this->setParam('v_arr_trang_thai_sd', $v_arr_trang_thai_sd);
        $this->setParam('v_magazines', $v_magazines['data']);
        $this->setParam('v_record_count', $v_record_count);
        $this->setParam('phan_trang', _db_page($v_record_count, $page, $number_per_page));
        $this->setParam('goback', $this->_getCurrentUri());
        // Hiển thị box danh mục
        $this->render($this->thisPath().'view/dsp_get_all_magazine.php');
    }
	/**
     * Lấy thông tin các template được sử dụng trong magazine
     * @param array $v_magazines
     * @return string
     */
    public function add_info_template_used($v_magazines){ 
         for($i = 0;$i < count($v_magazines['data']); $i++){
            $v_info_mgz_used = "";
            // lấy id magazine
            $v_fk_mgz = $v_magazines['data'][$i]['pk_magazine'];
            // lấy chi tiết magazine theo id
            $v_arr_magazine_content = be_get_all_magazine_content($v_fk_mgz);
            foreach ($v_arr_magazine_content as $k => $mzc) {                
                // lấy id template
				$info_mgz = be_get_magazine_template($mzc['fk_magazine_template']);
                $v_name_mgz = $info_mgz['c_name'];
                if($v_info_mgz_used == ""){
                    $v_info_mgz_used = '<span title="'.$v_name_mgz.'" style="cursor: pointer;">'.$mzc['fk_magazine_template'].'</span>';
                }
                else{
                    $v_info_mgz_used = $v_info_mgz_used." - <span title='".$v_name_mgz."' style='cursor: pointer;'>".$mzc['fk_magazine_template']."</span>";
                }                
            }
            $v_magazines['data'][$i]['info_mgz_used'] = $v_info_mgz_used;
         }
            return $v_magazines;
    }

    /**
     * hiển thị box lọc tìm màn hình danh sách
     * @param string $p_form_name
     * @return string
     */
    public function dsp_filter_box_top($p_form_name='dsp_filter_box')
    {
        $this->getRequest();

        $v_arr_user = be_get_all_users('',1,500);
        $v_user_name = 'Người sửa cuối';
        $this->setParam('v_arr_user', $v_arr_user['data']);
        $this->setParam('v_user_name', $v_user_name);

        $this->setParam('v_form_name', $p_form_name);
        // $this->setParam('goback', $this->_getCurrentUri());
        return $this->render($this->thisPath().'view/dsp_box_fillter_top.php');
    }

    /**
     * hiển thị các nút thao tác màn hình danh sách
     * @param bool $p_first
     * @return string
     */
    public function dsp_form_button($p_first=true)
    {
        $this->setParam('p_first', $p_first);
        return $v_html = $this->render($this->thisPath().'view/dsp_form_button.php');
    }

    /**
     * hiển thị màn hình chi tiết 1 magazine
     * @param int $p_magazine_id
     * @return string
     */
    public function dsp_single_magazine($p_magazine_id = 0)
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $this->getRequest();

        $v_magazine_id = intval($p_magazine_id);
        $v_magazine = array();
        $v_arr_magazine_content = array();
        $v_magazine_id_new = intval($_GET['id_magazine']);

        if ($v_magazine_id > 0 || $v_magazine_id_new > 0) {
            if($v_magazine_id_new > 0){
                $v_magazine = be_get_single_magazine($v_magazine_id_new);
            }else{
                $v_magazine = be_get_single_magazine($v_magazine_id);
            }
            if (!check_array($v_magazine)) {
                js_message('Magazine không tồn tại'); exit;
            }
            if($v_magazine_id_new > 0){
                $v_arr_magazine_content = be_get_all_magazine_content($v_magazine_id_new);
            }else{
                $v_arr_magazine_content = be_get_all_magazine_content($v_magazine_id);
            }
        }
        
        $v_text_copy = '';
        if($v_magazine_id_new > 0){
            $v_text_copy = ' - copy';
        }

        $count = count($v_arr_magazine_content);

        $v_next_stt = $count > 0 ? $count : 1;

        $v_arr_magazine_template = be_get_all_magazine_template_for_select();

        $v_arr_trang_thai = get_list_trang_thai_xuat_ban();
        $v_list_id_temp_slide = array();

        $this->setParam('v_next_stt', $v_next_stt);
        $this->setParam('v_text_copy', $v_text_copy);
        $this->setParam('v_magazine_id_new', $v_magazine_id_new);
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
        $this->setParam('v_arr_magazine_template', $v_arr_magazine_template);
        $this->setParam('v_magazine_id', $v_magazine_id);
        $this->setParam('v_magazine', $v_magazine);
        $this->setParam('v_arr_magazine_content', $v_arr_magazine_content);
        $this->setParam('v_list_id_temp_slide', $v_list_id_temp_slide);
        $this->setParam('v_goback', $_GET['goback']);

    return $this->render($this->thisPath().'view/dsp_single_magazine.php');
    }

    /**
     * cập nhật thông tin 1 magazine
     * @param int $p_magazine_id
     * @throws Exception
     */
    public function act_update_magazine($p_magazine_id = 0)
    {
        if (!$this->getPerm('admin,update')) {
            echo json_encode([
                'error' => true, 
                'msg' => 'Bạn không có quyền thực hiện chức năng này', 
            ]);
            die;
        }

        $v_magazine_id = intval($p_magazine_id);
        $v_magazine_name    = $_POST['magazine_name'];
        $v_magazine_status  = $_POST['magazine_status'];
        $v_user_id          = $_SESSION['user_id'];
        $v_goback           = $_POST['goback'];
        $v_chk_bai_tham_khao  = intval($_POST['chk_bai_tham_khao']);
        $v_magazine_font  = fw24h_replace_bad_char($_POST['magazine_font']);
        // gather magazine content data
        $v_arr_magazine_content = $_POST['magazine_content'];
        if (empty($v_magazine_name)) {
            echo json_encode([
                'error' => true, 
                'msg' => 'Chưa nhập tên magazine', 
            ]);
            exit;
        }
        if (strlen($v_magazine_name) > 300) {
            echo json_encode([
                'error' => true, 
                'msg' => 'Tên magazine đã vượt quá 300 ký tự', 
            ]);
            exit;
        }
        if (!check_array($v_arr_magazine_content)) {
            echo json_encode([
                'error' => true, 
                'msg' => 'Bài không có nội dung. Không thể tạo bài', 
            ]);
            exit;
        }
        // gom cac thong tin gui len
        $v_new_data = array('data' => $_POST, 'file' => $_FILES);
        $v_arr_data = mz_gather_magazine_content_data($v_arr_magazine_content);
        // kiểm tra thêm nội dung HTML
        if (!check_array($v_arr_data) || $v_arr_data[0]['v_html_template'] == '') {
            echo json_encode([
                'error' => true, 
                'msg' => 'Bài không có nội dung. Không thể tạo bài', 
            ]);
            exit;
        }
        // kiểm tra xem đồng thời có chọn cả template slide và template khác hay không
        $v_list_id_temp_slide = '227,228';
        $v_arr_id_slide = explode(',', $v_list_id_temp_slide);
        $v_is_template_slide = 0;
        $v_no_template_slide = 0;
        foreach($v_arr_data as $data){
            if(in_array($data['v_template_id'], $v_arr_id_slide)){
                $v_is_template_slide = 1;
            }
            if(!in_array($data['v_template_id'], $v_arr_id_slide)){
                $v_no_template_slide = 1;
            }
        }
        if($v_is_template_slide == 1 && $v_no_template_slide == 1){
            echo json_encode([
                'error' => true, 
                'msg' => 'Không được phép chọn template slide story và template thường!', 
            ]);
            exit;
        }
        // Lấy cấu hình list template cho phép chọn hiệu ứng
        $v_list_id_temp = '140,172,164,163,205,167,141,198,185,208,209,195,136,135,179,182,187,188,189,190,191,208,209';
        $v_arr_temp_id = array();
        if($v_list_id_temp != ''){
            $v_arr_temp_id = explode(',', $v_list_id_temp);
        }
        $errors = array();
        $v_list_id_temp = 227;
        foreach ($v_arr_data as $k => $data) {
            $v_html_map = $data['v_html_map'];
            $v_stt      = $data['v_stt'];
            if (!empty($v_html_map['defined'])) {
                // kiểm tra nếu là slide story 1
                if(strpos($data['v_html_template'],'slide_story_1')!==false){
                    // convert dữ liệu từ post
                    $v_new_data = mz_convert_data_template_slide_story($v_new_data,$k);
                }
                // update cac thong tin moi vao json map (lưu map của các phần tử được bóc tách)
                mz_update_magazine_template_with_new_data($v_html_map, $v_new_data, $v_stt, $errors);
            }
            $v_arr_data[$k]['v_html_map'] = $v_html_map;
            
            if(check_array($v_arr_temp_id) && in_array($data['v_template_id'], $v_arr_temp_id)){
                $v_template_id = $data['v_template_id'];
                $v_html_temp = $data['v_html_template'];
                $match_img_temp = array();
                preg_match_all('/<img[^>]+>/i', $v_html_temp, $match_img_temp);
                if(check_array($match_img_temp[0])){
                    $v_html_temp_new = '';
                    foreach($match_img_temp[0] as $key=>$v_img){
                        $v_html_temp_new = $v_html_map['defined']['image'][$key]['html_template'];
                        if($v_html_temp_new != ''){
                            $match_class_template_new = array();
                            $match_class_template = array();
                            preg_match('#class=\s*"([^\"]*)"#ism', $v_html_temp_new, $match_class_template_new);
                            preg_match('#class=\s*"([^\"]*)"#ism', $v_img, $match_class_template);
                            $v_img_temp = '';
                            if(check_array($match_class_template) && check_array($match_class_template_new)){
                                $v_img_temp = str_replace($match_class_template[1], $match_class_template_new[1], $v_img);
                                $v_html_temp = str_replace($v_img, $v_img_temp, $v_html_temp);
                            }
                        }
                    }
                }
                $v_arr_data[$k]['v_html_template'] = $v_html_temp;
            }
            // xử lý vị trí chú thích ảnh
            if($v_list_id_temp != ''){
                $v_arr_list_id_temp = explode(',', $v_list_id_temp);
                if(check_array($v_arr_list_id_temp) && in_array($v_arr_data[$k]['v_template_id'], $v_arr_list_id_temp)){
                    // xử lý chọn vị trí
                    $v_slect_text = 'effect_text_note_'.$k;
                    if($_POST[$v_slect_text] != ''){
                        // add class
                        preg_match_all('/<div class=\"item-slide-text(.*)\"/msU', $v_arr_data[$k]['v_html_template'], $v_html_class);
                        if(check_array($v_html_class[1]) && $v_html_class[1][0] != ''){
                            $v_arr_data[$k]['v_html_template'] = str_replace($v_html_class[1][0], ' '.$_POST[$v_slect_text], $v_arr_data[$k]['v_html_template']);
                        }
                        $v_color_text_0 = 'txt_effect_text_note_0_'.$k;
                        $v_color_text_1 = 'txt_effect_text_note_1_'.$k;
                        preg_match_all('/<div class=\"style_color\">(.*)<\/div>/msU', $v_arr_data[$k]['v_html_template'], $v_arr_color);
                        if(check_array($v_arr_color[0]) && $v_arr_color[0][0] != ''){
                            $v_string_color = '<div class="style_color">'.$_POST[$v_color_text_0].'@@'.$_POST[$v_color_text_1].'</div>';
                            $v_arr_data[$k]['v_html_template'] = str_replace($v_arr_color[0][0], $v_string_color, $v_arr_data[$k]['v_html_template']);
                        }
                    }
                }
            }
        }
        if (check_array($errors)) {
            echo json_encode([
                'error' => true, 
                'msg' => 'Có lỗi xảy ra. Bạn vui lòng kiểm tra lại các dữ liệu đã nhập', 
                'errors' => $errors,
            ]); 
            exit;
        }

        if(!$v_magazine_id) {
            // them moi magazine
            $v_result = be_create_magazine($v_magazine_name, $v_magazine_status, $v_user_id, $v_chk_bai_tham_khao, $v_magazine_font);
        } else {
            $v_action_type = 0;
            $v_old_magazine = be_get_single_magazine($v_magazine_id);
            // neu co su thay doi ve trang thai xuat ban
            if ($v_old_magazine['c_status'] != $v_magazine_status) {
                if (!$this->getPerm('admin,publish')) {
                    echo json_encode([
                        'error' => true, 
                        'msg' => 'Bạn không có quyền thực hiện chức năng này', 
                    ]);
                    die;
                }
                $v_action_type = 1;
            }
            // luu lich su sua doi noi dung magazine
            be_update_magazine_history($v_magazine_id, $v_action_type);
            // luu thong tin magazine
            $v_result = be_update_magazine($v_magazine_id, $v_magazine_name, $v_magazine_status, $v_user_id, '', array(), $v_chk_bai_tham_khao, $v_magazine_font);
        }

        if (!empty($v_result['RET_ERROR'])) {
            if ($v_result['RET_ERROR'] == "ERROR_TRUNG_TEN") {
                echo json_encode([
                    'error' => true, 
                    'msg' => 'Tên magazine đã có trên hệ thống', 
                ]);
            } else {
                echo json_encode([
                    'error' => true, 
                    'msg' => 'Có lỗi xảy ra trong quá trình cập nhật dữ liệu', 
                ]);
            }
            exit;
        }

        $v_magazine_id = intval($v_result['magazine_id']);
        // xoa cac noi dung cu truoc khi cap nhat noi dung moi
        be_delete_magazine_content($v_magazine_id);
        // cap nhat danh sach noi dung magazine
        foreach ($v_arr_data as $data) {
            // cap nhat noi dung magazine
            be_update_magazine_content($v_magazine_id, $data['v_template_id'], $data['v_html_template'], $data['v_html_map'], $data['v_position'], $v_user_id);
        }

        $v_arr_magazine_content = be_get_all_magazine_content($v_magazine_id);

        $v_magazine_body_html = '';
        $v_arr_head_files = array();

        foreach ($v_arr_magazine_content as $key => $magazine_content) {
            $v_html_template    = mzt_restore_bad_char($magazine_content['c_html_template']);
            $v_html_map         = json_decode($magazine_content['c_html_map'], true);
            $v_html             = mzt_rebuild_html($v_html_template, check_array($v_html_map) ? $v_html_map : array());
            // toan bo html cua magazine
            // xử lý vị trí chú thích ảnh
            if($v_list_id_temp != ''){
                $v_arr_list_id_temp = explode(',', $v_list_id_temp);
                if(in_array($magazine_content['fk_magazine_template'], $v_arr_list_id_temp)){
                    $v_html_restore = fw24h_restore_bad_char($v_html);
                    // cắt lấy text chú thích
                    preg_match_all('/<div class="width-750-chu-thich"[^>]+>(.*)<\/div>/msU', $v_html_restore, $v_arr_chu_thich);
                    if(check_array($v_arr_chu_thich) && $v_arr_chu_thich[1][0] != ''){
                        // xử lý màu nền text
                        $v_color_text_0 = 'txt_effect_text_note_0_'.$key;
                        $v_color_text_1 = 'txt_effect_text_note_1_'.$key;
                        $v_style_bg = 'style="background: linear-gradient(45deg, #'.$_POST[$v_color_text_0].', #'.$_POST[$v_color_text_1].');"';
                        preg_match_all('/<p[^>]+>(.*)<\/p>/msU', $v_arr_chu_thich[1][0], $v_arr_p);
                        if($v_arr_p[1][0] !=''){
                            if(strlen($v_arr_p[1][0]) > 4){
                                $v_arr_chu_thich[1][0] = '<p '.$v_style_bg.'>'.$v_arr_p[1][0].'</p>';
                            }else{
                                $v_arr_chu_thich[1][0] = '';
                            }
                        }else{
                            $v_arr_chu_thich[1][0] = '<p '.$v_style_bg.'>'.$v_arr_chu_thich[1][0].'</p>';
                        }
                        $v_arr_chu_thich[1][0] = str_replace('<p>', '<p '.$v_style_bg.'>', $v_arr_chu_thich[1][0]);
                        $v_html = str_replace('<!--textghichu-->', $v_arr_chu_thich[1][0], $v_html);
                    }
                }
            }
            $v_magazine_body_html .= mzt_get_body_inner_html($v_html);
            // file tren dau
            mz_get_head_files($v_html_map, $v_arr_head_files);
        }
        mz_minify_head_files($v_arr_head_files, $v_magazine_id);
        $v_magazine_body_html = _replace_xml_special_char($v_magazine_body_html);
        be_update_magazine_full_html($v_magazine_id, $v_magazine_body_html, $v_arr_head_files);

        // cập nhật redis data
        be_gen_magazine_data($v_magazine_id);

        $arr_news = be_get_all_news_by_magazine_id($v_magazine_id);
        if(check_array($arr_news)){
            foreach ($arr_news as $items){
                $v_news_id = intval($items['fk_news']);
            }
        }
        echo json_encode([
            'error' => false, 
            'msg' => 'Cập nhật thành công', 
            'goback' => fw24h_base64_url_decode($v_goback),
        ]);
    }

    /**
     * hiển thị thông tin 1 slot nội dung của magazine
     * @author bangnd <bangnd@24h.com.vn>
     * @param  integer $p_stt              [Số thứ tự của slot]
     * @param  integer $p_template_id      [khóa chính của magazine template]
     * @param  array   $p_magazine_content [bản ghi 1 magazine_content]
     * @return string
     */
    public function dsp_single_magazine_content($p_stt = 0, $p_template_id = 0, array $p_magazine_content = array(),$v_count = 0)
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $v_template = array();
        $v_stt = intval($p_stt);
        $v_template_id = intval($p_template_id);

        $v_arr_magazine_template = be_get_all_magazine_template_for_select();
        $v_is_update = 0; // danh dau man hinh cap nhat
        if (!empty($p_magazine_content)) {
            $v_is_update = 1;
            $v_template_id = $p_magazine_content['fk_magazine_template'];
            $v_template = be_get_magazine_template($v_template_id);

            $v_magazine_id = $p_magazine_content['fk_magazine'];

            $v_html_map = json_decode($p_magazine_content['c_html_map'], true);
        } else {
            $v_template = be_get_magazine_template($v_template_id);
            $v_html_map = json_decode($v_template['c_html_map'], true);
        }
        // lấy chi tiết content
        $v_magazine_content = be_get_single_magazine_content($p_magazine_content['pk_magazine_content']);
        $v_html_template    = mzt_restore_bad_char($v_magazine_content['c_html_template']);
        $v_html_body = mzt_get_body_inner_html($v_html_template);
        
        $v_file_conf = $this->template_element_config;

        $this->setParam('v_is_update', $v_is_update);
        $this->setParam('v_stt', $v_stt);
        $this->setParam('v_count', $v_count);
        $this->setParam('v_file_conf', $v_file_conf);
        $this->setParam('v_magazine_content', $p_magazine_content);
        $this->setParam('v_template_id', $v_template_id);
        $this->setParam('v_template', $v_template);
        $this->setParam('v_html_body', $v_html_body);
        $this->setParam('v_arr_magazine_template', $v_arr_magazine_template);
        $this->setParam('v_html_map', $v_html_map);

        return $this->render($this->thisPath().'view/dsp_single_magazine_content.php');
    }

    /**
     * xem trước 1 nội dung trong magazine
     * @param $p_template_id
     * @param int $p_magazine_content_id
     * @return string
     */
    public function dsp_preview_magazine_content($p_template_id, $p_magazine_content_id = 0)
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $v_template_id = intval($p_template_id);
        $v_magazine_content_id = intval($p_magazine_content_id);

        if ($v_magazine_content_id > 0) {
            $v_magazine_content = be_get_single_magazine_content($v_magazine_content_id);

            $v_template_id = $v_magazine_content['fk_magazine_template'];

            $v_html_template    = mzt_restore_bad_char($v_magazine_content['c_html_template']);
            $v_html_map         = json_decode($v_magazine_content['c_html_map'], true);
        } else {
            $v_template         = be_get_magazine_template($v_template_id);
            $v_html_template    = mzt_restore_bad_char($v_template['c_html_template']);
            $v_html_map         = json_decode($v_template['c_html_map'], true);
        }

        $v_html = html_entity_decode(mzt_rebuild_html($v_html_template, check_array($v_html_map) ? $v_html_map : array()));
        // chi lay html trong <body></body>
        $v_html_body = mzt_get_body_inner_html($v_html_template);

        $this->setParam('v_template_id', $v_template_id);
        $this->setParam('v_template', $v_template);
        $this->setParam('v_html_map', $v_html_map);
        $this->setParam('v_html_body', $v_html_body);
        $this->setParam('v_html', $v_html);

        return $this->render($this->thisPath().'view/dsp_preview_magazine_content.php');
    }

    /**
     * cập nhật trạng thái xuất bản của 1 magazine
     */
    public function act_update_status_magazine()
    {
        if (!$this->getPerm('admin,publish')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        $v_rows = intval($_REQUEST["hdn_record_count"]);
        $count = 0;
        $v_user_id = $_SESSION['user_id'];
        $v_error_message = '';
        $rs_items_checked = array();
        for ($i=0; $i < $v_rows; $i++) {
            $v_magazine_id = intval($_REQUEST["chk_item_id".$i]);                    
            if ($v_magazine_id > 0) {
                $v_status = intval($_REQUEST["sel_publish_status".$i]);
                $v_bai_tham_khao = intval($_REQUEST["sel_bai_tham_khao".$i]);

                $v_magazine = be_get_single_magazine($v_magazine_id);
                // có sự thay đổi về trạng thái xuất bản
                if($v_magazine['c_status'] != $v_status) {
                    if (!$this->getPerm('admin,publish')) {
                        js_message('Bạn không có quyền thực hiện chức năng này');
                        exit;
                    }
                }
                $v_head_files = json_decode($v_magazine['c_head_files'], true);
                $v_html = fw24h_restore_bad_char($v_magazine['c_html']);
                $rs_items_checked[] = array(
                    'v_magazine_id'=> $v_magazine_id
                    ,'v_status'     => $v_status
                    ,'v_bai_tham_khao'     => $v_bai_tham_khao
                    ,'v_head_files'     => $v_head_files
                    ,'c_html'     => $v_html
                );
            }
        }
        if($v_error_message != '') {
            js_message($v_error_message);
            js_set('top.document.frm_dsp_all_item.'.$v_txt_status_control.'.select()');
            die;
        }
        
        $v_rows = count($rs_items_checked);
        if ($v_rows == 0) {
            js_message('Không có magazine nào thay đổi!');
            die;
        }
       
        for ($i=0; $i < $v_rows; $i++) {
            $data = $rs_items_checked[$i];
            // luu lich su sua doi noi dung magazine
            be_update_magazine_history($data['v_magazine_id'], 1);
            // cap nhat thay doi
            be_update_status_magazine($data['v_magazine_id'], $data['v_status'], $v_user_id, $data['c_html'], $data['v_head_files'], $data['v_bai_tham_khao']);
            
            $count++;
        }
        js_message('Đã thực hiện cập nhật thành công '.$count.' magazine!');
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        }
    }

    /**
     * xóa 1 magazine
     */
    public function act_delete_magazine() {

        if (!$this->getPerm('admin,delete')) {
            js_message('Bạn không có quyền thực hiện chức năng này');exit;
        }

        $v_rows = intval($_REQUEST["hdn_record_count"]);
        $count=0;
        for ($i=0; $i<$v_rows; $i++) {
            $v_magazine_id = intval($_REQUEST["chk_item_id".$i]);
            if ($v_magazine_id > 0) {
                $v_magazine = be_get_single_magazine($v_magazine_id);
                if(check_array($v_magazine)) {
                    // không cho phép xóa magazine đã xuất bản
                    if($v_magazine['c_status'] > 0) {
                        js_message('Magazine đã xuất bản, không cho phép xóa.'); 
                        js_set('top.window.location.reload()'); exit;
                    }

                    $total = be_count_news_has_magazine($v_magazine_id);
                    // không cho phép xóa magazine đã được sử dụng trong bài viết
                    if($total > 0)  {
                        js_message('Magazine đã được sử dụng trong bài viết, không cho phép xóa.'); 
                        js_set('top.window.location.reload()'); exit;
                    }

                    $rs_items_checked[] = array(
                        'v_magazine_id'=> $v_magazine_id
                    );
                }
            }
        }

        $v_row_count = isset($rs_items_checked) ? count($rs_items_checked) : 0;
        if ($v_row_count == 0) {
            js_message('Không có magazine nào thay đổi!');
            die;
        }

        if (isset($rs_items_checked) && check_array($rs_items_checked)) {
            for ($i=0; $i < $v_row_count; $i++) {
                $data = $rs_items_checked[$i];
                be_delete_magazine($data['v_magazine_id']);
                $count++;
            }
        }

        
        js_message('Xóa thành công '. $count . ' magazine'); 
        js_redirect(fw24h_base64_url_decode($_REQUEST['goback']));
    }

    /**
     * màn hình danh sách lịch sử sửa đổi của 1 magazine
     * @param $p_magazine_id
     */
    public function dsp_all_magazine_history($p_magazine_id)
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $this->getRequest();

        $v_magazine_id      = intval($p_magazine_id);

        $v_magazine = be_get_single_magazine($v_magazine_id);
        
        $page               = $this->_GET['page'];
        $number_per_page    = $this->_GET['number_per_page'];
        
        $v_arr_items = be_get_all_magazine_history($v_magazine_id, $page, $number_per_page);

        $this->setParam('v_magazine', $v_magazine);
        $this->setParam('v_arr_items', $v_arr_items['data']); // 
        $this->setParam('v_total_items', $v_arr_items['tong_so_dong']);
        $this->setParam('v_record_count', count($v_arr_items['data']));
        $this->setParam('goback', $this->_getCurrentUri());
        $this->setParam('phan_trang', _db_page(count($v_arr_items['data']), $page, $number_per_page));
        $this->render($this->thisPath().'view/dsp_all_magazine_history.php');
    }

    public function dsp_single_magazine_history($p_history_id)
    {
        if (!$this->getPerm('admin,view')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }
        
        $v_history_id = intval($p_history_id);
        // thong tin 1 template
        $v_history = be_get_single_magazine_history($v_history_id);
        $v_arr_magazine_content = json_decode($v_history['c_content_detail'], true);

        $this->setParam('v_history', $v_history);
        $this->setParam('v_arr_magazine_content', $v_arr_magazine_content);
        $this->setParam('goback', $this->_getCurrentUri());

        $this->render($this->thisPath().'view/dsp_single_magazine_history.php');
    }

    public function dsp_choose_template_magazine()
    {
        _setLayout('ajax');
        html_set_title('Chọn template');

        $this->getRequest();
        $page = (int) $this->_GET['page'];
        $number_per_page = (int)$this->_GET['number_per_page'];
        $v_stt = (int)$this->_GET['stt'];

        $v_template_name    = $this->_GET['txt_template_name'];
        $v_templates = be_get_all_magazine_template(0, $v_template_name, 1, -1, 0, $page, $number_per_page);
        $v_record_count = count($v_templates);

        $this->setParam('v_stt', $v_stt);
        $this->setParam('v_templates', $v_templates);
        $this->setParam('v_record_count', $v_record_count);
        $this->setParam('v_paging', _db_page($v_record_count, $page, $number_per_page));
        return $this->render($this->thisPath().'view/dsp_choose_template_magazine.php');
    }

    public function dsp_choose_magazine()
    {
        _setLayout('ajax');
        html_set_title('Chọn nội dung bài magazine');

        $this->getRequest();

        $v_magazine_name    = $this->_GET['txt_magazine_name'];
        $page = (int) $this->_GET['page'];
        $number_per_page = (int)$this->_GET['number_per_page'];

        $v_magazines = be_get_all_magazine($v_magazine_name, 0, 0, 1, 0, $page, $number_per_page);
        $v_record_count = count($v_magazines['data']);

        $this->setParam('v_magazines', $v_magazines['data']);
        $this->setParam('v_record_count', $v_record_count);
        $this->setParam('v_paging', _db_page($v_record_count, $page, $number_per_page));
        return $this->render($this->thisPath().'view/dsp_choose_magazine.php');
    }

    public function act_preview_magazine($p_magazine_id)
    {
        _setLayout('content_only');
        $v_magazine_id = intval($p_magazine_id);
        $v_magazine = be_get_single_magazine($v_magazine_id);
        if (!check_array($v_magazine)) {
            js_message('Magazine không tồn tại'); js_set('window.close();'); exit;
        }
        $v_arr_magazine_content = be_get_all_magazine_content($v_magazine_id);
        if (!check_array($v_arr_magazine_content)) {
            js_message('Magazine không có nội dung'); js_set('window.close();'); exit;
        }

        $v_magazine_body_html = mzt_restore_bad_char($v_magazine['c_html']);
		$v_magazine_body_html = xu_ly_background_bai_magazine($v_magazine_body_html);
        $v_arr_head_files = json_decode($v_magazine['c_head_files'], true);

        $this->setParam('v_magazine_body_html', $v_magazine_body_html);
        $this->setParam('v_arr_head_files', $v_arr_head_files);
        $this->setParam('v_magazine', $v_magazine);
        $this->setParam('v_magazine_id', $v_magazine_id);
        unset($v_arr_magazine_content);
        return $this->render($this->thisPath().'view/dsp_preview_magazine.php');
    }

    public function dsp_crop_image()
    {
        _setLayout('ajax');
        html_set_title('Cắt ảnh');

        $this->getRequest();

        $v_stt  = (int) $this->_GET['stt'];
        $v_code = $this->_GET['code'];
        $v_target = $this->_GET['target'];

        $this->setParam('v_stt', $v_stt);
        $this->setParam('v_code', $v_code);
        $this->setParam('v_target', $v_target);

        return $this->render($this->thisPath().'view/dsp_crop_image.php');
    }

    function dsp_danh_sach_bai_tham_khao()
    {
        if (!$this->getPerm('admin,view_bai_tham_khao')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            die;
        }

        $this->getRequest();
        // các tiêu thức lọc
        $page = (int) $this->_GET['page'];

        $number_per_page = (int)$this->_GET['number_per_page'];

        $v_magazine_name    = $this->_GET['txt_magazine_name'];
        $v_status           = $this->_GET['sel_template_status'];
        $v_edited_by        = $this->_GET['sel_user_id'];
        $v_category_id      = $this->_GET['sel_category_id'];
        $v_news_id          = $this->_GET['txt_news_id'];
        
        // Lấy danh sách bài tham khảo
        $v_magazines = be_get_all_magazine($v_magazine_name, $v_category_id, $v_edited_by, -1, $v_news_id, $page, $number_per_page, 1);
       
        // tuannt bo sung thong tin su dung template magazine
        $v_magazines = $this->add_info_template_used($v_magazines);
        $v_record_count = count($v_magazines['data']);
        $v_arr_trang_thai = get_list_trang_thai_xuat_ban();
        $v_arr_trang_thai_sd = get_list_trang_thai_su_dung();
        $v_arr_trang_thai_bai_tham_khao = get_list_bai_tham_khao_magazine();

        $v_arr_category_by_select =  be_get_all_category_by_select(-1, $_SESSION['user_id'],-1);
        $this->setParam('v_arr_category_by_select', $v_arr_category_by_select['data']);
        $this->setParam('sel_template_status', $v_status);
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
        $this->setParam('v_arr_trang_thai_bai_tham_khao', $v_arr_trang_thai_bai_tham_khao);
        $this->setParam('v_arr_trang_thai_sd', $v_arr_trang_thai_sd);
        $this->setParam('v_magazines', $v_magazines['data']);
        $this->setParam('v_record_count', $v_record_count);
        $this->setParam('phan_trang', _db_page($v_record_count, $page, $number_per_page));
        $this->setParam('goback', $this->_getCurrentUri());
        // Hiển thị box danh mục
        $this->render($this->thisPath().'view/dsp_danh_sach_bai_tham_khao.php'); 
    }
}
// end 23-07-2018 bangnd XLCYCMHENG_28355_xay_dung_chuc_nang_quan_tri_noi_dung_bai_magazine