<?php
// services-tier functions for web-tier
// 8/19/2011 dungpt@24h.com.vn
// xu ly khi hoan thanh script
/*
GNUD_REQUEST_TYPE = 'webservcies'; // su dung ws de lay du lieu, bien dc dung xd cho services-tier
GNUD_REQUEST_TYPE = 'function'; // goi ham xy ly du lieu, bien dc dung xd cho services-tier
*/

global $_gnud_request_services_tier;

if (!is_array($_gnud_request_services_tier)) {
	$_gnud_request_services_tier = array();
}

$result = array();
$gnud_makeup = array();
preg_match_all('#<!--@([a-zA-Z0-9\_\-\(\)\'\"]+)@-->#', $GNUD_HTML, $result);
if (isset($result[1])) {
	$gnud_makeup = $result[1];
}

foreach($_gnud_request_services_tier as $srv) {
	if (!isset($srv['gnud_request_type'])) {
		$srv['gnud_request_type'] = 'function';
	}

	if (in_array($srv['array']['position_id'], $gnud_makeup)) {
		$srv = gnud_apply_filters('gnud_request_services_tier', $srv);
		if ($srv['gnud_request_type'] == 'function') {
			$url = preg_replace( '#[\:\.a-z0-9\/\/\-]+ajax/#', '', $srv['url']);
			$url = explode('/', $url);
			$class = $url[0].'_block';
			$object = new $class();

			$params = array();
			if (count( $url) > 2) {
				$params = $url;
				array_shift( $params);
				array_shift( $params);
			}

			$object->autoRender = false;
			call_user_func_array( array( &$object, $url[1]), $params);
			$masterContent = $object->blockContent;
			call_user_func($srv['callback'], $masterContent, $srv['url'], $object, $srv['array']);
		} else if($srv['gnud_request_type'] == 'webservices') {
			$masterContent = gnud_request_services_tier($srv['url'], $curl_options);
			call_user_func($srv['callback'], $masterContent, $srv['url'], $object, $srv['array']);
		}
	}
}
