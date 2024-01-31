<?php
/**
* SVN FILE: $Id: gnud_plugin_functions.php 2055 2011-11-24 01:50:00Z dungpt $
* 
* $Author: dungpt
* $Revision: 2055 $
* $Date: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-11-24 08:50:00 +0700 (Thu, 24 Nov 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/gnud/gnud_plugin_functions.php $
*
*/
//$gnud_filter = array();
//function _test($abc)
//{
    //echo time();
//}

//$content = 'gnud_filter';

//gnud_add_filter('the_title', '_test');
//gnud_apply_filters('the_title', $content);

function gnud_add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $gnud_filter;
    $idx = $function_to_add;
	$gnud_filter[$tag][$idx] = array('function' => $function_to_add, 'accepted_args' => $accepted_args);
	return true;
}

function gnud_remove_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1)
{
    global $gnud_filter;
    $idx = $function_to_add;
	unset($gnud_filter[$tag][$idx]);
	return true;
}

function gnud_apply_filters($tag, $value) {
	global $gnud_filter;

	if( is_array( $gnud_filter[$tag])) {
		foreach( $gnud_filter[$tag] as $fun) {
			$arg = $value;
			$value = call_user_func( $fun['function'], $arg);
		}
	}
	return $value;
}

