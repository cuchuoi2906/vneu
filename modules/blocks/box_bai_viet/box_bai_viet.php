<?php
__get_db_functions('db.general');
class box_bai_viet_block extends Fw24H_Block
{
	var $_cache_key = 'header_092018';
    function index($row_news=array(),$view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
		$this->generate();
	}
		
    function doAction()
    {
        $row_news = $this->getParam('row_news');
        if(!check_array($row_news)){
            return;
        }
        $rs_items_moi = fe_bai_viet_moi_nhat(1,4);
        $this->setParam('row_news', $row_news);
        $this->setParam('rs_items_moi', $rs_items_moi);
        // Chuyển sang view PC
        $this->render($this->thisPath().'view/box_bai_viet.php');
    }
}