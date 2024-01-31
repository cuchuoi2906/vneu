<?php
__get_db_functions('db.general');
class news_category_block extends Fw24H_Block
{
	var $_cache_key = 'header_092018';
    function index($row_cat=array(),$view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
		$this->generate();
	}
		
    function doAction()
    {
        $row_cat = $this->getParam('row_cat');
        if(!check_array($row_cat)){
            return;
        }
        $cate_id = $row_cat['ID'];
        $v_number_page = 4;
        $v_page = (isset($_GET['page']) && intval($_GET['page']) > 1) ? intval($_GET['page']) : 1;
        $rs_items = fe_bai_viet_theo_chuyen_muc($cate_id,$v_page,$v_number_page);
        if(!check_array($rs_items)){
            return;
        }
        $rs_items_moi = fe_bai_viet_moi_nhat(1,4);
        
        $sql = "SELECT count(ID) as tong_so_dong FROM news WHERE news.CategoryID = ".$cate_id." AND news.Status = 1 ORDER BY news.DatePublished DESC LIMIT 210;";
        $rs = Gnud_Db_read_query($sql);
        $v_tong_so_dong = intval($rs[0]['tong_so_dong']);
        
        $this->setParam('rs_items_moi', $rs_items_moi);
        $this->setParam('rs_items', $rs_items);
        $this->setParam('row_cat', $row_cat);
        $this->setParam('v_page', $v_page);
        $this->setParam('v_number_page', $v_number_page);
        $this->setParam('v_tong_so_dong', $v_tong_so_dong);
        // Chuyển sang view PC
        $this->render($this->thisPath().'view/news_category.php');
    }
}