<?php
// services-tier functions for web-tier
// 8/19/2011 dungpt@24h.com.vn

$max_requests = 10;

$curl_options = array(
    CURLOPT_SSL_VERIFYPEER => FALSE,
    CURLOPT_SSL_VERIFYHOST => FALSE,
    CURLOPT_TIMEOUT => 30,
);

if (!defined('CACHE_SQL_TYPE')) {
	define('CACHE_SQL_TYPE', 'file');
}
if (!defined('CACHE_SQL_TYPE')) {
	define('CACHE_SQL_TYPE', 'file');
}

function gnud_request_services_tier_cache($url, array $options = array(), $ttl=900, $key_sql='')
{
	$cache = gnud_Cache::singleton(CACHE_SQL_TYPE);
	$key='query_'.md5($url);
	if ($key_sql != '') {
		$key='query_'.md5($key_sql);
	}

    $cache->setCachePath(CACHE_SQL_DIR);
	if ($ttl == 0 || !$data = $cache->get($key, $ttl)) {
		$data = gnud_request_services_tier($url, $options);
		if ($data != '') {
	        $cache->put($key, serialize($data), $ttl);
		}
    } else {
		$data = unserialize($data);
    }
    return $data;
}

function gnud_request_services_tier($url, array $options = array())
{   
	$defaults = array(
        CURLOPT_URL => $url,
        CURLOPT_HEADER => 0,
        CURLOPT_RETURNTRANSFER => TRUE,
        CURLOPT_TIMEOUT => 3
    );
	
    $ch = curl_init();
    curl_setopt_array($ch, ($options + $defaults));
	curl_setopt($ch, CURLOPT_USERAGENT, 'gnud_request_services_tier');
    if( !$result = curl_exec($ch))
    {
		echo curl_error($ch);
        //trigger_error(curl_error($ch));
		return '';
    }
    curl_close($ch);
	error_log(date("Y-m-d H:i:s ").$url."\n\n", 3, WEB_ROOT."logs/request_services_tier.log");
    return $result;
} 
 
function gnud_add_request_services_tier($id, $url, $callback, $gnud_request_type='function', $array=array(), $func_before='', $func_after='')
{
	$array['position_id'] = $id;
	
	global $_gnud_request_services_tier;
	$_gnud_request_services_tier[$id]['url'] = $url;
	$_gnud_request_services_tier[$id]['callback'] = $callback;
	$_gnud_request_services_tier[$id]['array'] = $array;
	$_gnud_request_services_tier[$id]['func_before'] = $func_before;
	$_gnud_request_services_tier[$id]['gnud_request_type'] = $gnud_request_type;
	$_gnud_request_services_tier[$id]['func_after'] = $func_after;
	
	$value = array();
	if ($func_before != '') {
		$value = $func_before($_gnud_request_services_tier[$id]);
	}
	if ($value['show_data']){ 
		unset($_gnud_request_services_tier[$id]);
		echo $value['data'];
		return true;
	}
	//echo '<!--@'.$id.'@-->';
	//error_log(date("Y-m-d H:i:s ").$url."\n\n", 3, WEB_ROOT."logs/request_services_tier.log");
}

function gnud_replace_with_services_tier_content($content, $url, $ch, $search)
{
	global $GNUD_HTML; // bat buoc phai lay du lieu can thay the dua vao bien nay
	
	$GNUD_HTML = str_replace('<!--@'.$search['position_id'].'@-->', $content, $GNUD_HTML);
	return $GNUD_HTML;
}
