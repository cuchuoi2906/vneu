<?php
// begin 03-07-2018 bangnd XLCYCMHENG_28354_xay_dung_chuc_nang_quan_tri_template_bai_magazine
__get_db_functions('db.user');
__get_db_functions('db.general');
__get_db_functions('db.category');
__get_db_functions('db.template_magazine');
fw24h_add_module_function('template_magazine');
include_once WEB_ROOT.'editor/'.EDITOR.'/index.php';
include_once('class/uploader.php');
include_once('class/htmlprocessor.php');

class template_magazine_block extends Fw24H_Block
{
	// khai bao danh sach quyen thao tac
	public $_arr_permision = array (
		'admin' =>ADMIN_OCM_24H,
		'view' =>'VIEW_TEMPLATE_MAGAZINE',
		'update' =>'UPDATE_TEMPLATE_MAGAZINE',
		'delete' =>'DELETE_TEMPLATE_MAGAZINE',
		'publish' =>'PUBLISH_TEMPLATE_MAGAZINE',
	);

	public $_arr_arg = array (
        'hdn_item_id' => array(-100, 'intval')
        ,'sel_user_id' => array(0, 'intval')
        ,'txt_user_id' => array(0, 'intval')
        ,'txt_template_id' => array(0, 'intval')
        ,'txt_template_name' => array('', 'fw24h_replace_bad_char')
        ,'sel_template_status' => array(-1, 'fw24h_replace_bad_char')
        ,'sel_template_use_status' => array(-1, 'fw24h_replace_bad_char')
        ,'page' => array(1, 'page_val')
        ,'number_per_page' => array(_CONST_NUMBER_OF_ROW_PER_LIST, 'intval')
        ,'view_type' => array('update', 'fw24h_replace_bad_char')
	);

    private $template_fileupload_config = array();
    private $template_element_config = array();

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

        $v_template_id      = $this->_GET['txt_template_id'];
        $v_template_name    = $this->_GET['txt_template_name'];
        $v_status           = $this->_GET['sel_template_status'];
        $v_use_status       = $this->_GET['sel_template_use_status'];
        $v_edited_by        = $this->_GET['sel_user_id'];

        $v_templates = be_get_all_magazine_template($v_template_id, $v_template_name, $v_status, $v_use_status, $v_edited_by, $page, $number_per_page);
        $v_record_count = count($v_templates);
        $v_arr_trang_thai = get_list_trang_thai_xuat_ban();
        $v_arr_trang_thai_sd = get_list_trang_thai_su_dung();

        $this->setParam('sel_template_status', $v_status);
        $this->setParam('sel_template_use_status', $v_use_status);
        $this->setParam('v_arr_trang_thai', $v_arr_trang_thai);
        $this->setParam('v_arr_trang_thai_sd', $v_arr_trang_thai_sd);
        $this->setParam('v_templates', $v_templates);
        $this->setParam('v_record_count', $v_record_count);
        $this->setParam('phan_trang', _db_page($v_record_count, $page, $number_per_page));
        $this->setParam('goback', $this->_getCurrentUri());
        // Hiển thị box danh mục
        $this->render($this->thisPath().'view/dsp_get_all_template_magazine.php');
    }

    public function dsp_filter_box_top($p_form_name='dsp_filter_box')
    {
        $this->getRequest();

        $v_arr_user = be_get_all_users('',1,500);
        $v_user_name = 'Người sửa cuối';
        $this->setParam('v_arr_user', $v_arr_user['data']);
        $this->setParam('v_user_name', $v_user_name);

        $this->setParam('v_form_name', $p_form_name);
        // $this->setParam('goback', $this->_getCurrentUri());
        return $this->render($this->thisPath().'view/dsp_box_fillter_template_magazine.php');
    }

    public function dsp_form_button($p_first=true)
    {
        $this->setParam('p_first', $p_first);
        return $v_html = $this->render($this->thisPath().'view/dsp_form_button.php');
    }

    public function act_update_order_template_magazine()
    {
        if (!$this->getPerm('admin,update,publish')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }
        $v_rows = intval($_REQUEST["hdn_record_count"]);
        $count = 0;
        $v_user_id = $_SESSION['user_id'];
        $v_error_message = '';
        $v_txt_order_control = '';
        $rs_items_checked = array();
        for ($i=0; $i < $v_rows; $i++) {
            $v_id = intval($_REQUEST["chk_item_id".$i]);
            if ($v_id > 0) {
                $v_order = $_REQUEST["txt_order".$i];
                if(!_kiem_tra_trong_so($v_order)) {
                    $v_order = 999;
                }

                $v_status = intval($_REQUEST["sel_publish_status".$i]);
                $v_loai_thay_doi = 0;

                $v_template = be_get_magazine_template($v_id);
                // có sự thay đổi về trạng thái xuất bản
                if($v_template['c_status'] != $v_status) {
                    $v_loai_thay_doi = 1;
                    if (!$this->getPerm('admin,publish')) {
                        js_message('Bạn không có quyền thực hiện chức năng này');
                        exit;
                    }
                }
                if($v_status > 0) {
                    if(!$v_template['c_original_html'] || !$v_template['c_html_template'] || !$v_template['c_html_map']) {
                        js_message('Template chưa có nội dung không thể xuất bản'); exit;
                    }
                }

                $rs_items_checked[] = array(
                    'v_template_id'=> $v_id
                    ,'v_position'=> $v_order
                    ,'v_status' => $v_status
                    ,'v_loai_thay_doi' => $v_loai_thay_doi
                    );
                }
        }
        if($v_error_message != '') {
            js_message($v_error_message);
            js_set('top.document.frm_dsp_all_item.'.$v_txt_order_control.'.focus()');
            js_set('top.document.frm_dsp_all_item.'.$v_txt_order_control.'.select()');
            die;
        }

        $v_rows = count($rs_items_checked);
        if ($v_rows == 0) {
            js_message('Không có template nào thay đổi!');
            die;
        }

        for ($i=0; $i < $v_rows; $i++) {
            $data = $rs_items_checked[$i];

            be_magazine_template_cap_nhat_lich_su($data['v_template_id'], $data['v_loai_thay_doi']);

            // cap nhat thay doi
            be_update_position_magazine_template($data['v_template_id'], $data['v_position'], $v_user_id, $data['v_status']);

            $count++;
        }
        js_message('Đã thực hiện cập nhật thành công '.$count.' template!');
        if ($_POST['goback']) {
            js_redirect(fw24h_base64_url_decode($_POST['goback']));
        }
    }

    /**
     * xóa các template magazine được chọn (màn hình danh sách)
     * @return void
     */
    public function act_remove_magazine_template() {

        if (!$this->getPerm('admin,delete')) {
            js_message('Bạn không có quyền thực hiện chức năng này');
            exit;
        }

        $v_rows = intval($_REQUEST["hdn_record_count"]);
        $count=0;
        for ($i=0; $i<$v_rows; $i++) {

            $v_template_id = intval($_REQUEST["chk_item_id".$i]);
            if ($v_template_id > 0) {
                $v_template = be_get_magazine_template($v_template_id);
                if(check_array($v_template)) {
                    // không cho phép xóa template đã xuất bản
                    if($v_template['c_status'] > 0) {
                        js_message('Template đã xuất bản, không cho phép xóa.');
                        exit;
                    }

                    be_delete_magazine_template($v_template_id); $count++;
                }
            }
        }

        js_message('Xóa thành công '. $count . ' template');
        js_redirect(fw24h_base64_url_decode($_REQUEST['goback']));
    }

    /**
     * tạo mới 1 magazine template
     * @return void
     */
    public function act_create_magazine_template()
    {
        try {
            if (!$this->getPerm('admin,create,update')) {
                mzt_ajax_message('Bạn không có quyền thực hiện chức năng này', true);
            }
            if(!$_POST['c_name']) {
                mzt_ajax_message('Vui lòng nhập tên template', true);
            }
            if(strlen($_POST['c_name']) > 255) {
                mzt_ajax_message('Tên template magazine đã vượt quá 255 ký tự', true);
            }

            $v_template_id = be_create_magazine_template($_POST['c_name'], $_SESSION['user_id']);

            mzt_ajax_message('Tạo template thành công', false, array(
                'template_id' => $v_template_id,
                'redirect_link' => html_link($this->className().'/dsp_magazine_template/' . $v_template_id . '?goback=' . $_POST['goback'] . '&view_type=create', false)
            ));

        } catch (\Exception $ex) {
            mzt_ajax_message($ex->getMessage(), true);
        }
    }

    /**
     * kiểm tra dữ liệu nhập trước khi update template
     * @return void
     */
    public function act_check_template_data() {
        if(!_kiem_tra_trong_so($_POST['c_position'])) {
            $_POST['c_position'] = 999;
        }
        if(empty($_POST['c_name'])) {
            mzt_ajax_message('Bạn chưa nhập tên template magazine', true);
        }
        if(strlen($_POST['c_name']) > 255) {
            mzt_ajax_message('Tên template magazine đã vượt quá 255 ký tự', true);
        }
        if(strlen($_POST['c_description']) > 500) {
            mzt_ajax_message('Ghi chú chỉ được nhập tối đa 500 ký tự', true);
        }
    }

    /**
     * xem trước 1 template
     * @param  int $p_template_id  [khóa chính của template]
     * @return string
     */
    public function act_preview_template_magazine($p_template_id) {

        $v_template = be_get_magazine_template($p_template_id);

        $v_html_template = mzt_restore_bad_char($v_template['c_html_template']);
        $v_template_map = json_decode($v_template['c_html_map'], true);

        if(!$v_html_template || !check_array($v_template_map)) {
            js_message('Template chưa có nội dung');
        }

        $v_html = mzt_rebuild_html($v_html_template, $v_template_map);

        $this->setParam('v_html', $v_html);

        $this->render($this->thisPath().'view/dsp_preview_template_magazine.php');
    }

    /**
     * hiển thị chi tiết 1 magazine template
     * @param  int $p_template_id [khóa chính của template]
     * @return string
     */
    public function dsp_magazine_template($p_template_id)
    {
        $this->getRequest();

        $v_defined_params = $this->template_element_config;
        // thong tin 1 template
        $v_template = be_get_magazine_template($p_template_id);

        $v_template_map = json_decode($v_template['c_html_map'], true);
        // danh sach cac file cua template
        $v_template_fileupload = be_get_all_magazine_template_fileupload($p_template_id);

        $this->setParam('v_template', $v_template);
        $this->setParam('v_template_id', intval($p_template_id));
        $this->setParam('v_template_map', $v_template_map);
        $this->setParam('v_template_fileupload', $v_template_fileupload);
        $this->setParam('v_template_config', $this->template_fileupload_config);
        $this->setParam('v_defined_params', $v_defined_params);
        $this->setParam('goback', $_GET['goback']);
        $this->setParam('v_view_type', $this->_GET['view_type']);

        $this->render($this->thisPath().'view/dsp_magazine_template.php');
    }

    /**
     * Upload các file của template lên server
     * @return void
     */
    public function act_upload_magazine_template_files()
    {
        $arrFileConfig = $this->template_fileupload_config;
        $v_template_id = intval($_POST['template_id']);

        try {
            // TODO: change upload file path to format
            $uploader = new Uploader($_FILES['template_file']);
            $uploader->setConfig($arrFileConfig);
            // set upload path
            $v_upload_path = mzt_get_upload_path($arrFileConfig['upload_path'], $v_template_id, $uploader->getFileType());
            $uploader->setUploadPath($v_upload_path);
            // thực hiện upload file
            if($uploader->upload()) {
                // thông tin sau khi upload
                $upload_info = $uploader->info();
                // cập nhật dữ liệu vào database
                $v_result = mzt_update_template_fileupload($v_template_id, $upload_info);
                                
                echo json_encode([
                    'error' => false,
                    'msg' => '[Tải lên thành công]',
                    'info' => $v_result
                ]);die;
            }
            echo json_encode(['error' => true, 'msg' => $uploader->getError()]);
        } catch (\Exception $ex) {
            echo json_encode(['error' => true, 'msg' => $ex->getMessage()]);
        }
    }

    /**
     * cập nhật thông tin của 1 template
     * @param  integer $p_template_id [khóa chính của template]
     * @return void
     */
    public function act_update_template_magazine($p_template_id) 
    {
        if (!$this->getPerm('admin,update')) {
            mzt_ajax_message('Bạn không có quyền thực hiện chức năng này', true);
        }
        try {
            // kiem tra data gui len server
            $this->act_check_template_data();
            $v_template_id      = intval($p_template_id);
            $v_old_template     = be_get_magazine_template($v_template_id); // lay thong tin cua template truoc khi cap nhat
            $v_name             = $_POST['c_name'];
            $v_status           = $_POST['c_status'];
            $v_original_html    = mzt_restore_bad_char($_POST['c_html']); // noi dung html cua editor
            $v_arr_fileupload   = empty($_POST['arr_fileupload']) ? array() : $_POST['arr_fileupload'];
            $v_description      = $_POST['c_description'];
            $v_thumbnail        = $_POST['c_thumbnail'];
            $v_position         = $_POST['c_position'];
            $v_user_id          = $_SESSION['user_id'];
            $v_goback           = $_POST['goback'];
            // check trùng tên template
            $v_arr_template_has_name = be_get_magazine_template_by_name($v_name, $v_template_id);
            if(check_array($v_arr_template_has_name)) {
                mzt_ajax_message('Tên template đã có trên hệ thống', true);
            }
            // kiểm tra quyền nếu có sự thay đổi về trạng thái xuất bản
            if($v_status > 0 && $v_status != $v_old_template['c_status']) {
                if(!$this->getPerm('admin,publish')) {
                    mzt_ajax_message('Bạn không có quyền thực hiện chức năng này', true);
                }
            }
            // cap nhat lich su sua doi template
            be_magazine_template_cap_nhat_lich_su($v_template_id);

            if (check_array($v_arr_fileupload) ) {
                if(!_kiem_tra_mang_kieu_so($v_arr_fileupload)) {
                    mzt_ajax_message('Mảng id file tải lên không hợp lệ', true);
                }
            } elseif (!trim($v_original_html)) {  // neu khong upload len file nao
                // va nguoi dung khong nhap noi dung trong editor
                mzt_ajax_message('Bạn chưa tải lên file template nào', true);
            }

            be_assign_fileupload_to_template($v_template_id, $v_arr_fileupload);
            // trường hợp không nhập nội dung trong editor
            if(!trim($v_original_html)) {
                // lay noi dung file html moi nhat duoc tai len
                $v_file_html = be_get_all_magazine_template_fileupload($v_template_id, 'html', 'desc', 1, 1);
                if(!check_array($v_file_html)) {
                    mzt_ajax_message('Bạn chưa nhập nội dung template magazine', true);
                }
                $v_original_html = mzt_restore_bad_char($v_file_html[0]['c_orginal_content']);
            }
            // trich xuat du lieu tu html
            $processor = (new HtmlCssProcessor($v_original_html))->process();
            // mang cac file tai len cua template magazine
            $v_arr_file = be_get_all_magazine_template_fileupload($v_template_id);
            // replace cac file upload co ten trung voi ten file trong html
            if (check_array($v_arr_file)) {
                $processor->replaceFilePath($v_arr_file);
            }
            $v_result = $processor->info();

            $v_html             = $v_result['content']; // html sau khi replace
            $v_html_template    = $v_result['template'];
            $v_html_map         = $v_result['map'];
            $v_original_html    = $v_result['content_origin'];
            // cap nhat template magazine
            be_update_magazine_template($v_template_id, $v_name, $v_original_html, $v_html, $v_html_template, $v_html_map, $v_user_id, $v_description, $v_thumbnail, $v_position, $v_status);
            // xu ly cac file css
            if (check_array($v_arr_file)) {
                foreach ($v_arr_file as $v_file) {
                    // neu khong phai file css 
                    if($v_file['c_type'] != 'css') 
                        continue; //bo qua
                    $v_content = mzt_restore_bad_char($v_file['c_content']);

                    // trich xuat cac phan tu trong css va replace cac file upload co ten trung voi ten file trong noi dung css
                    $v_rs = (new HtmlCssProcessor($v_content, 'css'))->process()->replaceFilePath($v_arr_file)->info();
                    $v_file_path = rtrim(ROOT_FOLDER, '/') . '/' . trim($v_file['c_path'], '/') . '/' . $v_file['c_name'];
                    // luu noi dung moi cua file
                    if (file_put_contents($v_file_path, $v_rs['content']) === false) {
                        throw new \Exception("Xảy ra lỗi khi lưu file: ". $v_file['c_name']);
                    }
                    // tao lai file hash
                    $v_hash     = sha1_file($v_file_path);
                    $v_file_id  = $v_file['pk_magazine_template_fileupload'];
                    // cap nhat thong tin file vao db
                    be_update_fileupload_content($v_file_id, $v_template_id, $v_hash, $v_rs['content'], $v_rs['map']);
                }
            }

            echo json_encode([
                'error'         => false,
                'msg'           => 'Cập nhật dữ liệu thành công',
                'redirect_link' => fw24h_base64_url_decode($v_goback)
            ]);
        } catch (\Exception $ex) {
            mzt_ajax_message($ex->getMessage(), true);
        }
    }

    /**
     * hiển thị danh sách lịch sử sửa đổi của 1 template
     * @param  integer  $p_template_id  [khóa chính template]
     * @return string
     */
    public function dsp_all_magazine_template_history($p_template_id)
    {
        $this->getRequest();
        $page = $this->_GET['page'];
        $number_per_page = $this->_GET['number_per_page'];

        $v_template = be_get_magazine_template($p_template_id);

        $v_arr_items = be_get_all_magazine_template_history($p_template_id, $page, $number_per_page);

        $this->setParam('v_template', $v_template);
        $this->setParam('v_arr_items', $v_arr_items['data']); //
        $this->setParam('v_total_items', $v_arr_items['tong_so_dong']);
        $this->setParam('v_record_count', count($v_arr_items['data']));
        $this->setParam('goback', $this->_getCurrentUri());
        $this->setParam('phan_trang', _db_page(count($v_arr_items['data']), $page, $number_per_page));
        $this->render($this->thisPath().'view/dsp_danh_sach_lich_su_thao_tac.php');
    }

    /**
     * hiển thị lịch sử sửa đổi chi tiết
     * @param  integer  $p_template_history_id  [khóa chính của table magazine_template_history]
     * @return string
     */
    public function dsp_single_magazine_template_history($p_template_history_id)
    {
        $v_defined_params = $this->template_element_config;
        // thong tin 1 template
        $v_template = be_get_single_magazine_template_history($p_template_history_id);

        $v_template_map = json_decode($v_template['c_html_map'], true);
        $v_template_map_defined = $v_template_map['defined'];

        $v_template_id = $v_template['fk_magazine_template'];
        // danh sach cac file cua template
        $v_template_fileupload = be_get_all_magazine_template_fileupload($v_template_id);

        $this->setParam('v_template', $v_template);
        $this->setParam('v_template_id', intval($v_template_id));
        $this->setParam('v_template_map_defined', $v_template_map_defined);
        $this->setParam('v_template_fileupload', $v_template_fileupload);
        $this->setParam('v_template_config', $this->template_fileupload_config);
        $this->setParam('v_defined_params', $v_defined_params);
        $this->setParam('goback', $this->_getCurrentUri());

        $this->render($this->thisPath().'view/dsp_chi_tiet_lich_su_thao_tac.php');
    }

}
// end 03-07-2018 bangnd XLCYCMHENG_28354_xay_dung_chuc_nang_quan_tri_template_bai_magazine