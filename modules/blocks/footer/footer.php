<?php
/**
 * $Author: tuanna
 * $Revision: 2055 $
 * $Date: 2012-01-18 08:50:00 +0700 (Thu, 24 Nov 2011) $
 */
__get_db_functions('db.general');
class footer_block extends Fw24H_Block
{
    var $_cache_key = 'footer_092018';
    function index($cat_id = 1, $v_device_global = 'pc', $view_type = 1, $noindex = 1, $gen_key = 0)
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
		
		if(_is_thiet_bi_mobile($v_device_global)){
            $this->render($this->thisPath().'view/footer_mobile.php');
        }elseif(_is_thiet_bi_ipad($v_device_global)){
            $this->render($this->thisPath().'view/footer_ipad.php');
        }else{
			$this->render($this->thisPath().'view/footer_pc.php');
		}
    }
}

