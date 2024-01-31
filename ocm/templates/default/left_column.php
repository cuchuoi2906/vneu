<?php
 if (!is_array($_LEFT_CONTENT)) {
	$_LEFT_CONTENT = array();
}	
foreach ($_LEFT_CONTENT as $v_left_content) {
	if (preg_match('#^function:#', $v_left_content)) {
		echo 32;die;
		$v_left_content = str_replace('function:', '', $v_left_content);
		eval( $v_left_content.';');
	} else if (preg_match('#^object:#', $v_left_content)) {
		$v_left_content = str_replace('object:', '', $v_left_content);
		$v_left_content = explode('/', $v_left_content);
		$class = $v_left_content[0].'_block';    
		$obj_temp = new $class();
		$v_methods ='$obj_temp->'.$v_left_content[1].';';

		eval ($v_methods);        
	} else if (preg_match('#^key_value:#', $v_left_content)) {
		$v_left_content = str_replace('key_value:', '', $v_left_content);
		$v_left_content = explode('/', $v_left_content);
		echo Gnud_Db_read_get_key($v_left_content[0],$v_left_content[1]);
	}
}