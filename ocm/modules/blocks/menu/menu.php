<?php

class menu_block extends Fw24H_Block
{	
   /**
	* $Author: tuanna
	* $Date: 2012-07-13 08:50:00 +0700 (Fri,Jul 13 2012) $
	* index	
	* @param	
	* @return string
	*/	

    function index()
    {				
		$this->setParamAll(get_defined_vars());		
		$this->generate();
	}
		
    function doAction()
    {
		$menu = be_menu_get_all();
		$this->setParam('menu', $menu);
		$this->render();
    }	
	
	/**
	* Hien thi menu backend theo user dang nhap
	* $Author: tuanna
	* $Date: 2012-07-13 08:50:00 +0700 (Fri,Jul 13 2012) $
	* index	
	* @param	
	* @return string
	*/	
	function be_menu_user() {
		$menu = be_menu_get_all();
		$this->setParam('arr_menu_box', $menu['arr_menu_box']);
		$this->setParam('arr_menu_item', $menu['arr_menu_item']);
		$this->render($this->thisPath().'view/be_menu_user.php');
	}
}

