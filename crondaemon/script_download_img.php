<?php
include 'common.php';

$v_page = 1;
$v_number_for_page = 200;
$v_loop = true;

while ($v_loop){
    Gnud_Db_read_close();

    // Lấy ra các bài viết
    $v_sql = 'SELECT c_html_map,c_html_template FROM magazine_content order by pk_magazine_content DESC
              LIMIT '.(($v_page - 1) * $v_number_for_page).",".$v_number_for_page;	
    $rs_result = Gnud_Db_read_query($v_sql);

    if(check_array($rs_result)){
        foreach($rs_result as $item){
            $v_html_maps = $item['c_html_map'];
            $v_arr_maps =  json_decode($v_html_maps,true);
            foreach($v_arr_maps as $key=>$value){
                foreach($value as $key1=>$value1){
                    if(!in_array($key1,array('image','css','js'))){
                        continue;
                    }
                    foreach($value1 as $value2){
                        if($value2['arr_data']['src']['type'] == 'image'){
                            $v_path = $value2['arr_data']['src']['data_origin'];
                            if(!file_exists(WEB_ROOT. ltrim($v_path,'/'))){
                                $v_arr_path = array();
                                $v_arr_path = explode('/', $v_path);
                                unset($v_arr_path[count($v_arr_path)-1]);
                                $path = WEB_ROOT;
                                for($i=0;$i<count($v_arr_path);$i++){
                                    if(empty($v_arr_path[$i])){
                                        continue;
                                    }
                                    $path .= $v_arr_path[$i];
                                    if (!is_dir($path)) {
                                        mkdir($path, 0777);
                                    }
                                    $path .='/'; 
                                }
                                if(is_dir($path)){
                                    $v_file_content = file_get_contents_curl('https://cdn.24h.com.vn'.$v_path);
                                    if (preg_match('#404 Not Found#i', $v_file_content)) {
                                        $v_error[] = 'File '.$v_file_name.' không tồn tại';
                                    } else {
                                        $result = file_put_contents(WEB_ROOT. ltrim($v_path,'/'), $v_file_content);
                                    }
                                }
                            }
                        }
                        $v_arr_path = array();
                        $v_path = $value2['arr_data']['src']['data'];
                        $v_arr_path = explode('/', $v_path);
                        unset($v_arr_path[count($v_arr_path)-1]);
                        $path = WEB_ROOT;
                        for($j=0;$j<count($v_arr_path);$j++){
                            if(empty($v_arr_path[$j])){
                                continue;
                            }
                            $path .= $v_arr_path[$j];
                            if (!is_dir($path)) {
                                mkdir($path, 0777);
                            }
                            $path .='/'; 
                        }
                        if(!is_dir($path)){
                            continue;
                        }
                        $v_file_content = file_get_contents_curl('https://cdn.24h.com.vn'.$v_path);
                        if (preg_match('#404 Not Found#i', $v_file_content)) {
                            $v_error[] = 'File '.$v_file_name.' không tồn tại';
                        } else {
                            $result = file_put_contents(WEB_ROOT. ltrim($v_path,'/'), $v_file_content);
                        }
                    }
                    
                }
            }
        }
    }
    ++$v_page;
    echo $v_page."\r\n";
    if (count($rs_result) < $v_number_for_page){
        $v_loop = false;
    }
}
function ws_create_upload_folder($p_upload_path='', $is_video = fasle) {
	$p_upload_path = ($p_upload_path != '') ? $p_upload_path : UPLOAD_FOLDER;
	$quarterByDate = quarterByDate(date('m'));
	$path = $p_upload_path.$quarterByDate.'-'.date('Y');
	if (!is_dir($path)) {
		mkdir($path, 0777);
		//@chmod($path, 0777);
	}
	// tao thu muc
	if($is_video) {
		$path .= '/videoclip/';
	} else {
		$path .= '/images/';
	}
	if (!is_dir($path)) {
		mkdir($path, 0777);
		//@chmod($path, 0777);
	}
	// tao theo ngay
	$path .= date('Y-m-d').'/';
	if (!is_dir($path)) {
		mkdir($path, 0777);
		//@chmod($path, 0777);
	}
	return $path;
}

function file_get_contents_curl($url, $curlopt = array(), $p_timeout=30){
    $ch = curl_init();
	$agent = CURLOPT_USERAGENT_WEB;
    $default_curlopt = array(
        CURLOPT_TIMEOUT => $p_timeout,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FOLLOWLOCATION => 0,
		CURLOPT_SSL_VERIFYPEER=>false,
        CURLOPT_SSL_VERIFYHOST=>0,
        CURLOPT_USERAGENT => $agent
    );

    $curlopt = array(CURLOPT_URL => $url) + $curlopt + $default_curlopt;
	//End 04-02-2016 : Thangnb bo_sung_ghi_log_box_get_video
    curl_setopt_array($ch, $curlopt);
    $response = curl_exec($ch);
    curl_close($ch);
	//End 04-02-2016 : Thangnb bo_sung_ghi_log_box_get_video
    return $response;
}