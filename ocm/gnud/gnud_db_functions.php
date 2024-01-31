<?php
/**
* SVN FILE: $Id: gnud_db_functions.php 2778 2011-12-21 04:15:54Z dungpt $
*
* $Author: dungpt
* $Revision: 2778 $
* $Date: 2011-12-21 11:15:54 +0700 (Wed, 21 Dec 2011) $
* $LastChangedBy: dungpt $
* $LastChangedDate: 2011-12-21 11:15:54 +0700 (Wed, 21 Dec 2011) $
* $URL: http://svn.24h.com.vn/svn_24h/services-tier/gnud/gnud_db_functions.php $
*
*/

/*
$fwDbConfig['read']['server'] = 'localhost';
$fwDbConfig['read']['username'] = 'root';
$fwDbConfig['read']['password'] = '123456as';
$fwDbConfig['read']['database'] = 'test';

$fwDbConfig['write']['server'] = 'localhost';
$fwDbConfig['write']['username'] = 'root';
$fwDbConfig['write']['password'] = '123456as';
$fwDbConfig['write']['database'] = 'test';

$GNUD_
*/

include_once 'gnud_plugin_functions.php';
include_once 'db/mysqli.inc.php';

$GNUD_DB_DEFAULT_CONN = 'read';

if(!defined('GNUD_DEBUG_MODE')) {
    define('GNUD_DEBUG_MODE', false);
}

if(!defined('GNUD_DB_LOG_SQL')) {
    define('GNUD_DB_LOG_SQL', false);
}

if(!defined('GNUD_DB_LOG_SQL_PATH')) {
    define('GNUD_DB_LOG_SQL_PATH', '/tmp/');
}

if(!defined('GNUD_DB_CACHE_SQL_PATH')) {
    define('GNUD_DB_CACHE_SQL_PATH', '/home/mnhacvui/cache_query/');
}

if(!defined('GNUD_DB_CACHE_TYPE')) {
    define('GNUD_DB_CACHE_TYPE', 'file');
}

if (!isset($fwDbReadConnNewLink)) {
    $fwDbReadConnNewLink = false;
}

/**
 * Thực thi 1 câu lệnh SQL và trả về 1 mảng 2 chiều
 * @param $sql Câu lệnh SQL cần thực thi
 * @return array
 */
function Gnud_Db_read_query($sql)
{
	$sql = gnud_apply_filters('gnud_db_read_query', $sql);
	$time_start = microtime(true);
    $result = Gnud_Db_read_query_multi($sql);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
    $time = number_format($time, 12);
	if ($time > 0.09 && GNUD_DEBUG_MODE) {
		$message = date('[Y-m-d H:i:s]').' ['.$time.'] ['.$sql."]\n";
		$file = WEB_ROOT . 'logs/slow_sql.log';
		error_log($message, 3, $file);
		$_SESSION['slow_sql'][] = $message;
		@chmod($file, 0777);
	}
    if (GNUD_DEBUG_MODE) {
        $time = substr($time, 0, 9);
		$errorMsg = date('Y-m-d H:i:s')." $time $sql\n";
		@error_log($errorMsg, 3, WEB_ROOT.'/logs/sql.log');
        @chmod(WEB_ROOT.'/logs/sql.log', 0777);
        global $SQL_IN_BLOCK;
        $SQL_IN_BLOCK .= $errorMsg;
	}
    return $result;
}

function Gnud_Db_read_get_key($keyname, $table='key_value')
{
	$sql = "SELECT C_VALUE FROM {$table} WHERE C_KEY='{$keyname}' LIMIT 1";
    $result = Gnud_Db_read_query($sql);
    return $result[0]['C_VALUE'];
}

/**
 *
 * @param $sql
 */
function Gnud_Db_read_query_one($sql)
{
    $result = Gnud_Db_read_query($sql);
    return $result[0];
}

function Gnud_Db_cache_fetch($sql, $expiration=1800)
{
	$filename = md5($sql);
	$filename = CACHE_SQL_DIR.$filename;

	if (!file_exists($filename)) {
		return false;
	}

	if (time(0) - filemtime($filename) > $expiration) {
		return false;
	}

	$data = unserialize (file_get_contents($filename));

	return $data;
}

function Gnud_Db_read_query_cache($sql, $ttl=1800)
{
    $key = md5($sql);
    if(defined('GNUD_DB_LOG_SQL') && GNUD_DB_LOG_SQL) {
        Gnud_Db_log_sql($key, $sql, $ttl);
    }

    $sql = gnud_apply_filters('gnud_db_read_query_cache', $sql);

    if ($data = Gnud_Db_cache_fetch($sql, $ttl)) {
        return $data['data'];
    } else {
		$rows = Gnud_Db_read_query($sql);
		Gnud_Db_cache_store($sql, $rows);
		return $rows;
    }
}

function Gnud_Db_cache_store($sql, $data)
{
	$filename = md5($sql);
	$filename = CACHE_SQL_DIR.$filename;

	$rows['data'] = $data;
	$rows['sql'] = $sql;

	return file_put_contents($filename, serialize($rows));
}

function gnud_cache_get_cache($key, $type)
{
    $function = 'gnud_cache_get_'.$type;
    return $function($key);
}

function gnud_cache_get_file($key)
{
    $filename = GNUD_DB_CACHE_SQL_PATH.$key;
    if(file_exists($filename)){
        $result = @unserialize(file_get_contents($filename));
        if(is_array($result)) {
            return $result;
        }
    }
    return false;
}

function Gnud_Db_sql_reduce($sql)
{
    $unwantedChars = array("\r\n", "\r", "\n", "\n\r", "\t");

    $sql = str_replace($unwantedChars, ' ', $sql);
    $sql = preg_replace('#[ ]{2,}#', ' ', $sql);
    $sql = trim($sql);
    return $sql;
}

function Gnud_Db_log_sql($key, $sql, $ttl)
{
    $filename = GNUD_DB_LOG_SQL_PATH.$ttl.'/'.$key;
    file_put_contents($filename, $sql);
}

function Gnud_Db_get_total_rows($sql, $timeout=86400) {
	$sql = Gnud_Db_count_stmt($sql);
	$rows = Gnud_Db_read_query_cache($sql, $timeout);
	return $rows[0]['total_rows'];
}

//----------------
function Gnud_Db_add_slashes($value)
{
    if(!get_magic_quotes_runtime()) {
        return addslashes($value);
    }
    return $value;
}

function Gnud_Db_count_stmt($sql='')
{
	$sql = Gnud_Db_sql_reduce($sql);
	$sql = ' '.trim($sql);
	$start = stripos($sql, ' SELECT');
	$end = stripos($sql, 'FROM ');

	$rest = substr($sql, $start, $end - $start);
	$sql = str_replace($rest, 'SELECT COUNT(*) as total_rows ', $sql);

	$start = stripos($sql, 'ORDER BY');
	if ($start === false) {
		$start = stripos($sql, ' LIMIT ');
	}

	if ($start !== false) {
		$rest = substr($sql, $start);
		$sql = str_replace($rest, ' ', $sql);
	}

	if (preg_match('#GROUP BY ([a-zA-Z0-9\.\_\-]+)#', $sql, $result)) {
		$sql = str_replace('SELECT COUNT(*) as total_rows ', 'SELECT COUNT(DISTINCT '.trim($result[1]).') as total_rows ', $sql);
		$sql = substr($sql, 0, stripos($sql, 'GROUP BY'));
	}
	return trim($sql);
}
// Begin: Tytv - chuyển code ghi keyredis từ cms2016 sang : 03/08/2016
// Begin: toi_uu_key_value anhnt1: 29/09/2015
/**
 * Cập nhật dữ liệu vào 1 bảng theo dạng key_value đến server master có kiểm tra checksum
 * @param array $record mảng chứa thông tin cần update: các key: C_KEY - id key cần update/insert, C_VALUE -  dữ liệu cần ghi
 * @param string $table Tên bảng cần insert/update
 * @return integer 0 nếu dữ liệu ko thay đổi, 1 nếu cập nhật thành công
 */
function Gnud_Db_write_update_key_redis($record, $table=_CACHE_TABLE, $p_use_redis = USE_REDIS, $p_kieu_du_lieu = '', $p_auto_set_key_to_redis = AUTO_SET_KEY_TO_REDIS, $p_time_live_redis = DEFALUT_TIME_LIVE_REDIS) 
{
    if ($record['C_KEY'] == '' || $record['C_VALUE'] == '') {
        return 0;
    }

    // checksum
    Gnud_Db_read_close();
	// Đọc dữ liệu từ redis
	$v_value_need_check_redis = Gnud_Db_read_get_key_redis($record['C_KEY'], $table);
	if (md5($record['C_VALUE']) == md5($v_value_need_check_redis)) {
		return 1;
    }
    
    $record['C_VALUE'] = $record['C_VALUE'];
    $record['C_LAST_MODIFIED'] = date('Y-m-d H:i:s');
    $record['C_TYPE'] = $record['C_TYPE'];

	// Ghi key/value vao redis
	if ($p_use_redis){ 
		// Lấy kiểu dữ liệu
        $v_kieu_du_lieu = Gnud_Db_get_data_type_redis($table);
	
		$v_ten_key_redis = _make_name_key_redis(array(
			'c_muc_dich_su_dung'=>'keyvalue',
			'c_loai_du_lieu'=>$table,
			'c_kieu_du_lieu'=>$v_kieu_du_lieu,
			'c_ten_du_lieu'=>$record['C_KEY'],
		));
		$v_retun = _set_key_value_redis($v_ten_key_redis, $record['C_VALUE'], DEFALUT_TIME_LIVE_REDIS);
		if ($v_retun) {
			//echo "\n Ghi key ".$v_ten_key_redis.' thành công <br/>';
		} else {
			//echo "\n Ghi key ".$v_ten_key_redis.' không thành công <br/>';
		}
	}
    
    return 1;
}
// End: toi_uu_key_value anhnt1: 29/09/2015

// Begin: toi_uu_key_value anhnt1: 29/09/2015
/*
* đọc key
* Last edited: HaiLT 20150312
# param: text		$keyname 					tên key
# param: text		$table 						tên table chứa key
# param: Boolean	$p_use_redis 				Có sử dụng redis hay không
# param: text		$p_kieu_du_lieu 			Kiểu dữ liệu khi ghi trong redis
# param: Boolean	$p_auto_set_key_to_redis 	Tự động ghi key vào redis khi phải đọc lại từ sql
# param: Boolean	$p_time_live_redis		 	Thời gian lưu key trong redis
*/
function Gnud_Db_read_get_key_redis($keyname, $table='key_value', 
                              $p_use_redis = USE_REDIS, 
                              $p_kieu_du_lieu = '', 
                              $p_auto_set_key_to_redis = AUTO_SET_KEY_TO_REDIS, 
                              $p_time_live_redis = DEFALUT_TIME_LIVE_REDIS)
{
	if ($p_use_redis){ // ưu tiên đọc redis đầu tiên
		// Lấy kiểu dữ liệu
        $v_kieu_du_lieu = Gnud_Db_get_data_type_redis($p_table);

		$v_ten_key_redis = _make_name_key_redis(array(
			'c_muc_dich_su_dung'=>'keyvalue',
			'c_loai_du_lieu'=>$table,
			'c_kieu_du_lieu'=>$v_kieu_du_lieu,
			'c_ten_du_lieu'=>$keyname,
		));
		$v_value = _get_key_value_redis($v_ten_key_redis);
	}

	$v_value = ($v_value === false || is_null($v_value)) ? '' : $v_value;
	
	return $v_value;
}
// End: toi_uu_key_value anhnt1: 29/09/2015

/**
 * Hàm thực hiện ghi dữ liệu vào redis kiểu hases
 * @author  Lucnd <lucnd@24h.com.vn>
 * @date    26-02-2016 
 * @param   Array    $p_keyname      Tên key
 * @param   Array    $p_record       mảng dữ liệu
 * @param   String   $p_table        tên bảng redis lưu dữ liệu
 * @param   Boolean  $p_use_redis    Trạng thái sử dụng redis
 * 
 * @return: string
 */
function Gnud_Db_write_hashes_data_to_redis(
    $p_keyname,
    $p_record,
    $p_table,
    $p_use_redis = USE_REDIS
){
    // Kiểm tra dữ liệu
    if ($p_keyname == '' || !check_array($p_record)) {
        return 0;
    }    
    
    // Lấy kiểu dữ liệu
    $v_kieu_du_lieu = Gnud_Db_get_data_type_redis($p_table);
    
	// Ghi key/value vao redis
	if ($p_use_redis && $v_kieu_du_lieu != ''){ 
        // Lấy tên key_redis
		$v_ten_key_redis = _make_name_key_redis(array(
			'c_muc_dich_su_dung'    => 'keyvalue',
			'c_loai_du_lieu'        => $p_table,
			'c_kieu_du_lieu'        => $v_kieu_du_lieu,
			'c_ten_du_lieu'         => $p_keyname,
		));
        // Xóa key trước khi lưu
        _delete_hashes_1_key_redis($v_ten_key_redis);
        
        // Thực hiện lưu từng cột dữ liệu vào redis
        foreach ($p_record as $key => $value) {
            // Hashkey 
            $v_hashKey  = $key;
            $value      = restore_bad_char($value);
            // Trả về dữ liệu sau khi setup data redis
            $v_retun = _set_hashes_data_redis($v_ten_key_redis, $v_hashKey, $value);
        }
	} else {
        // Chuỗi ghi log
        $v_str_log = date('Y-m-d H:i:s : ')."Chưa cấu hình sử dụng redis hoặc chưa cấu hình bảng được lưu dữ liệu redis trong file cấu hình => app_redis_configs.php \n";
        // Log Debug redis
        if (DEBUG_REDIS){
            @error_log($v_str_log, 3, WEB_ROOT.'logs/hashes_redis'.date('H').'.log');
        }
        return false;
    }
    
    return true;
}

/**
 * Hàm thực hiện đọc key để lấy dữ liệu từ redis
 * @author  Lucnd <lucnd@24h.com.vn>
 * @date    04-03-2016
 * @param   text    $p_keyname      tên key
 * @param   text    $p_table        tên table chứa key
 * @param   Boolean	$p_use_redis    Có sử dụng redis hay không
 * 
 * @return Array  Mảng dữ liệu redis trả về
 */
function Gnud_Db_read_hashes_data_from_redis($p_keyname, $p_table, $p_use_redis = USE_REDIS){
    $v_value    = '';
    // ưu tiên đọc redis đầu tiên
	if ($p_use_redis){
        // Lấy kiểu dữ liệu
        $v_kieu_du_lieu = Gnud_Db_get_data_type_redis($p_table);

		$v_ten_key_redis = _make_name_key_redis(array(
			'c_muc_dich_su_dung'    => 'keyvalue',
			'c_loai_du_lieu'        => $p_table,
			'c_kieu_du_lieu'        => $v_kieu_du_lieu,
			'c_ten_du_lieu'         => $p_keyname,
		));
        // Lấy dữ liệu từ redis
		$v_value = _get_hashes_data_redis($v_ten_key_redis);
        $v_value = ($v_value === false || is_null($v_value)) ? '' : $v_value;
	}
	
	return $v_value;
}
// End: toi_uu_key_value anhnt1: 29/09/2015

/**
 * Hàm thực hiện lấy kiểu dữ liệu ghi vào redis từ cấu hình
 * @author  Lucnd <lucnd@24h.com.vn>
 * @date    26-02-2016 
 * @param   String  $p_table    Tên table ghi dữ liệu redis
 * 
 * @return: string
 */
function Gnud_Db_get_data_type_redis($p_table){
    // Lấy cấu hình bẳng được phép lưu redis
    global $v_arr_table_key_redis;
    
    // Kiểu dữ liệu ghi redis
    $v_kieu_du_lieu  = '';
    
    # xác định kiểu dữ liệu theo tên bảng
    if (check_array($v_arr_table_key_redis)){
        for ($i = 0, $s = sizeof($v_arr_table_key_redis); $i < $s; ++$i){
            // Kiểm tra table
            if ($v_arr_table_key_redis[$i]['c_ten'] == $p_table){
                // Lấy kiểu dữ liệu
                $v_kieu_du_lieu = $v_arr_table_key_redis[$i]['c_kieu_du_lieu'];
                break;
            }
        }
    }
    
    return $v_kieu_du_lieu;
}
// End: Tytv - chuyển code ghi keyredis từ cms2016 sang : 03/08/2016