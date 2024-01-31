<?php
/**
 * $Author: tuanna
 * $Revision: 2055 $
 * $Date: 2012-01-18 08:50:00 +0700 (Thu, 24 Nov 2011) $
 */
__get_db_functions('db.general');
class footer_magazine_block extends Fw24H_Block
{
    var $_cache_key = 'footer_magazine_responsive';
    function index($cat_id = ID_TRANG_CHU,$v_device_global ='pc', $view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
        $this->generate();
    }

    function doAction() {
        $v_device_global = fw24h_replace_bad_char($this->getParam('v_device_global'));
        if($v_device_global == ''){
            global $v_device_global;
        }
        
        $cat_id = (int)$this->getParam('cat_id');
        $rs_cat = fe_danh_sach_chuyen_muc();
        $row_cat = fe_chuyen_muc_theo_id($cat_id);
        //1: footer phien ban PC
		//9: footer phien ban mobile
        $v_loai_trang = 1;
        $row_footer = array();
        $v_parent_cat = ($row_cat['Parent'] > 0)?$row_cat['Parent']:$cat_id;
        $row_tag_category_ctrl = array();
        $this->setParam('rs_cat', $rs_cat);
        $this->setParam('row_cat', $row_cat);
        $this->setParam('row_footer_link', $row_footer_link);
        $this->setParam('row_footer', $row_footer);
        $this->setParam('row_tag_category_ctrl', $row_tag_category_ctrl);
        // Hiển thị giao diện phiên bản PC
        if(_is_thiet_bi_pc($v_device_global)){
            $this->render($this->thisPath().'view/footer_magazine_pc.php');
        }
        // Hiển thị giao diện phiên bản PC
        if(_is_thiet_bi_mobile($v_device_global)){
            $this->render($this->thisPath().'view/footer_magazine_mobile.php');
        }
        // Hiển thị giao diện phiên bản PC
        if(_is_thiet_bi_ipad($v_device_global)){
            $this->render($this->thisPath().'view/footer_magazine_ipad.php');
        }
    }
}

