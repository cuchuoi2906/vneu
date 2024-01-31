<?php
__get_db_functions('db.dang_ky_thanh_vien');
class dang_ky_thanh_vien_block extends Fw24H_Block
{	
   /**
	* $Author: tuanna
	* $Date: 2012-07-13 08:50:00 +0700 (Fri,Jul 13 2012) $
	* index	
	* @param	
	* @return string
	*/	
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
        ,'txt_category_name'=>array('Nhập tên chuyên mục', 'fw24h_replace_bad_char')
		,'page' => array(1, 'page_val')
		,'number_per_page' => array(10, 'intval')
	);

    function index()
    {				
		if (!$this->getPerm('admin,view')) {
			js_message('Bạn không có quyền thực hiện chức năng này');
			exit;
		}
		$this->getRequest();
		$v_page = $this->_GET['page'];
		$v_number_per_page = $this->_GET['number_per_page'];
		$v_arr_items  = be_danh_sach_doc_gia(
				'',
                $v_page, 
                $v_number_per_page);
				
		$v_tong_so = count($v_arr_items);
		
		$this->setParam('v_arr_items', $v_arr_items);
		$this->setParam('v_record_count', $v_tong_so);
        $this->setParam('phan_trang', _db_page($v_tong_so, $v_page, $v_number_per_page));
        $this->setParam('goback', $this->_getCurrentUri());  
		$this->render($this->thisPath().'view/dsp_all_item.php');
	}
}

