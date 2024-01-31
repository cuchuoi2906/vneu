<?php
	header("Content-type: text/xml");
	
	$object = new box_xml_block();
	$object->index($v_ten_file);
	
	die;
?>