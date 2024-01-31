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
    define('GNUD_DB_CACHE_SQL_PATH', '/tmp/');
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
    $result = Gnud_Db_Query($sql, 'read');
    return $result;
}

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
function Gnud_Db_read_get_key($keyname, $table='key_value', 
                              $p_use_redis = USE_REDIS, 
                              $p_kieu_du_lieu = '', 
                              $p_auto_set_key_to_redis = AUTO_SET_KEY_TO_REDIS, 
                              $p_time_live_redis = DEFALUT_TIME_LIVE_REDIS)
{
	if ($p_use_redis){ // ưu tiên đọc redis đầu tiên
		global $v_arr_table_key_redis;
		# xác định kiểu dữ liệu theo tên bảng
		if ($p_kieu_du_lieu == '' && check_array($v_arr_table_key_redis)){
			for ($i = 0, $s = sizeof($v_arr_table_key_redis); $i < $s; ++$i){
				if ($v_arr_table_key_redis[$i]['c_ten'] == $table){
					$p_kieu_du_lieu = $v_arr_table_key_redis[$i]['c_kieu_du_lieu'];
					break;
				}
			}
		}
		$v_kieu_du_lieu = ($p_kieu_du_lieu === false || is_null($p_kieu_du_lieu)) ? 'data' : $p_kieu_du_lieu;

		$v_ten_key_redis = _make_name_key_redis(array(
			'c_muc_dich_su_dung'=>'keyvalue',
			'c_loai_du_lieu'=>$table,
			'c_kieu_du_lieu'=>$v_kieu_du_lieu,
			'c_ten_du_lieu'=>$keyname,
		));
		$v_value = _get_key_value_redis($v_ten_key_redis);

	$v_value = ($v_value === false || is_null($v_value)) ? '' : $v_value;

	//Begin 03-07-2017 : Thangnb bo_sung_ghi_log_key_khong_co_du_lieu
		if ($v_value == '' && GNUD_DEBUG_MODE) {
		$errorMsg = date('Y-m-d H:i:s')." - Key - $keyname : Khong co du lieu"."\r\n";
			fw24h_write_log($errorMsg, WEB_ROOT . '/logs/danh_sach_key_redis_khong_co_du_lieu.log');
		}
	//End 03-07-2017 : Thangnb bo_sung_ghi_log_key_khong_co_du_lieu
	}

	$v_value = ($v_value === false || is_null($v_value)) ? '' : $v_value;
	
	// Kiểm tra xem $p_key có thuộc key chỉ đọc từ redis hay ko
	$v_read_redis_only = _if_key_read_from_redis_only($keyname);
	
	if (!$p_use_redis || ($v_value == '' && $p_use_redis && TRY_SQL_AFTER_REDIS && !$v_read_redis_only)){ # nếu không đọc từ redis hoặc đọc nhưng không có key
		// anhnt1 06/3/2015: ghi log thoi gian thuc thi
		$v_start_time = microtime(true);
        $sql = "SELECT C_VALUE FROM {$table} WHERE C_KEY='{$keyname}' LIMIT 1";
        $result = Gnud_Db_Query($sql, 'read');
		// anhnt1 06/3/2015: ghi log thoi gian thuc thi
		$time = round((microtime(true) - $v_start_time), 5);
		if ($time >= 0.1){
			$errorMsg = date('Y-m-d H:i:s '). "time: $time giay ; SQL: $sql ; UA: ".$_SERVER['HTTP_USER_AGENT']."\n";
			fw24h_write_log($errorMsg, WEB_ROOT.'logs/slow_key.log');	
        }
		$v_value = $result[0]['C_VALUE'];
		$v_value = is_null($v_value) ? '' : $v_value;

	//Begin 03-07-2017 : Thangnb bo_sung_ghi_log_key_khong_co_du_lieu
		if ($v_value == '' && GNUD_DEBUG_MODE) {
		$errorMsg = date('Y-m-d H:i:s')." - Key - $keyname : Khong co du lieu"."\r\n";
			fw24h_write_log($errorMsg, WEB_ROOT . '/logs/danh_sach_key_mysql_khong_co_du_lieu.log');
		}
	//End 03-07-2017 : Thangnb bo_sung_ghi_log_key_khong_co_du_lieu
	
		if ($p_use_redis && $p_auto_set_key_to_redis && $v_value != ''){ # nếu gọi đọc key từ redis mà phải lấy từ sql. chế độ set tự động ghi key ==> đưa key đọc đc vào redis
			_set_key_value_redis($v_ten_key_redis, $v_value, $p_time_live_redis);
		}
	}

	return $v_value;
}
// End: toi_uu_key_value anhnt1: 29/09/2015

/**
 *
 * @param $sql
 */
function Gnud_Db_read_query_one($sql)
{
    $result = Gnud_Db_Query($sql, 'read');
    return $result[0];
}

function Gnud_Db_cache_filename($filename)
{
    $sub_dir = substr($filename, 0, 2);
    $filename = CACHE_SQL_DIR.$sub_dir.'/'.$filename;
    return $filename;
}

function Gnud_Db_cache_fetch($sql, $expiration=1800)
{
    $filename = md5($sql);
    $filename = Gnud_Db_cache_filename($filename);

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
    $filename = Gnud_Db_cache_filename($filename);

    $rows['data'] = $data;
    $rows['sql'] = $sql;

    return file_put_contents($filename, serialize($rows));
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


//--------------------------------------------------------
function Gnud_Db_Query($sql, $connection='read')
{
    $time_start = microtime(true);
    $result = Gnud_Db_query_multi($sql, $connection);
    $time_end = microtime(true);
    $time = $time_end - $time_start;
    $time = number_format($time, 12);
    //if ($time > 0.01 && GNUD_DEBUG_MODE) {
    $v_tg_ghi_log = 0.05;
    if('www.24h.com.vn' == strtolower($_SERVER['SERVER_NAME']) || preg_match('#^www(\d+).24h.com.vn#', $_SERVER['SERVER_NAME'])){
        $v_tg_ghi_log = 0.01;
    }
    if ($time > $v_tg_ghi_log) {
		$v_ip_client = Gnud_get_ip_client();									  
        $time = substr($time, 0, 8);
        $v_url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $message = date('[Y-m-d H:i:s]').' ['.$time.'] , IP client : '.$v_ip_client .' , '.$sql.', url: '.$v_url."\n";
        $file = WEB_ROOT . 'logs/query_slow.log';
        fw24h_write_log($message, $file);
        $_SESSION['slow_sql'][] = $message;
        //chmod($file, 0777);
    }
    if (GNUD_DEBUG_MODE) {
        $time = substr($time, 0, 8);
        $message = date('[Y-m-d H:i:s]').' ['.$time.'] '.$sql."\n";
        $file = WEB_ROOT . 'logs/query_log.log';
        fw24h_write_log($message, $file);
        global $SQL_IN_BLOCK;
        $SQL_IN_BLOCK .= $message;
    }
    return $result;
}

// Begin: toi_uu_key_value anhnt1: 29/09/2015
/**
 * Cập nhật dữ liệu vào 1 bảng theo dạng key_value đến server master có kiểm tra checksum
 * @param array $record mảng chứa thông tin cần update: các key: C_KEY - id key cần update/insert, C_VALUE -  dữ liệu cần ghi
 * @param string $table Tên bảng cần insert/update
 * @return integer 0 nếu dữ liệu ko thay đổi, 1 nếu cập nhật thành công
 */
function Gnud_Db_write_update_key($record, $table=_CACHE_TABLE, $p_use_redis = USE_REDIS, $p_kieu_du_lieu = '', $p_auto_set_key_to_redis = AUTO_SET_KEY_TO_REDIS, $p_time_live_redis = DEFALUT_TIME_LIVE_REDIS) 
{ 
    if ($record['C_KEY'] == '' || $record['C_VALUE'] == '') {
        return 0;
    }

    // checksum
    Gnud_Db_close('read');
	// begin - ducnq 07/01/2016 fix_loi_ko_update_key_vao_mysql
	// 0: Không đọc từ redis; 1: có đọc từ redis
    $v_value_need_check_mysql = Gnud_Db_read_get_key($record['C_KEY'], $table, 0);
    $v_ghi_key_mysql = 1;
	$v_ghi_key_redis = 1;
    
    if($p_use_redis){
        $v_value_need_check_redis = Gnud_Db_read_get_key($record['C_KEY'], $table);


        if (md5($record['C_VALUE']) == md5($v_value_need_check_mysql)) {
            $v_ghi_key_mysql = 0;
        }

        if (md5($record['C_VALUE']) == md5($v_value_need_check_redis)) {
            $v_ghi_key_redis = 0;
        }
        if ($v_ghi_key_mysql == 0 && $v_ghi_key_redis == 0) {
            return 0;	
        }
    }
    $record['C_VALUE'] = $record['C_VALUE'];
    $record['C_LAST_MODIFIED'] = date('Y-m-d H:i:s');
    $record['C_TYPE'] = $record['C_TYPE'];

	// Ghi key/value vao redis
	if ($p_use_redis && $v_ghi_key_redis == 1){ 
		global $v_arr_table_key_redis;
		# xác định kiểu dữ liệu theo tên bảng
		if ($p_kieu_du_lieu == '' && check_array($v_arr_table_key_redis)){
			for ($i = 0, $s = sizeof($v_arr_table_key_redis); $i < $s; ++$i){
				if ($v_arr_table_key_redis[$i]['c_ten'] == $table){
					$p_kieu_du_lieu = $v_arr_table_key_redis[$i]['c_kieu_du_lieu'];
					break;
				}
			}
		}
		$v_kieu_du_lieu = ($p_kieu_du_lieu === false || is_null($p_kieu_du_lieu)) ? 'data' : $p_kieu_du_lieu;
	
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
	
    // Ghi key/value vao mysql
    Gnud_Db_close('write');
	if ($v_ghi_key_mysql == 1) {
		if ($v_value_need_check_mysql == '') { //insert
			Gnud_Db_write_insert($table, $record);
		} else { //update
			Gnud_Db_write_update($table, $record, 'C_KEY="'.$record['C_KEY'].'"');
		}
	}
	// end - ducnq 07/01/2016 fix_loi_ko_update_key_vao_mysql
    return 1;
}
// End: toi_uu_key_value anhnt1: 29/09/2015

/**
 * Insert dữ liệu vào 1 bảng đến CSDL ghi
 * @param string $table Tên bảng cần insert
 * @param array $data mảng chứa thông tin cần insert
 * @return integer $lastId ID bản ghi được insert
 */
function Gnud_Db_write_insert($table, $data)
{
    global $fwDbWriteConn;
    global $fw24h_connections;
    $fields = Gnud_Db_read_show_columns($table);
    $sqlFields = '';
    $sqlValues = '';
    reset($data);
    while (list($key, $value) = each($data)) {
        if (in_array($key, $fields)) {
            $sqlFields .= ' ,'.$key;
            $sqlValues .= ' ,"'.Gnud_Db_add_slashes($value).'"';
        }
    }
    $sqlFields = preg_replace('#^ \,#', '', $sqlFields);
    $sqlValues = preg_replace('#^ \,#', '', $sqlValues);
    $sqlInsert = 'INSERT INTO '.$table.' ('.$sqlFields.') VALUES ('.$sqlValues.')';
    Gnud_Db_close('write');
    Gnud_Db_write_query($sqlInsert);
    $lastId = mysqli_insert_id($fw24h_connections['write']);
    return $lastId;
}

/**
 * Cập nhật dữ liệu vào 1 bảng đến CSDL ghi
 * @param string $table Tên bảng cần insert
 * @param array $record mảng chứa thông tin cần update
 * @param string $where điều kiện cần để update
 * @return integer $affected_rows Số bản ghi được cập nhật
 */
function Gnud_Db_write_update($table, $record, $where)
{
    if (!is_array($record)) return false;

    global $fwDbWriteConn;
    global $fw24h_connections;

    $fields = Gnud_Db_read_show_columns($table);

    $count = 0;

    foreach ($record as $key => $val) {
        if (in_array($key, $fields)) {
            if ($count==0) $set = $key."='".Gnud_Db_add_slashes($val)."'";
            else $set .= ", ".$key ."='".Gnud_Db_add_slashes($val)."'";
            ++$count;
        }
    }

    $query = "UPDATE ".$table." SET ".$set." WHERE ".$where;
    Gnud_Db_write_query($query);
    $affected_rows = mysqli_affected_rows($fw24h_connections['write']);
    return $affected_rows;
}

/**
 * Lấy thông tin tên các field trong 1 bảng
 * @param $table Tên table cần lấy tất cả các field
 * @return array Danh sách các field
 */
function Gnud_Db_read_show_columns($table)
{
    $sql = 'SHOW COLUMNS FROM '.$table;
    $rs_columns = Gnud_Db_Query($sql, 'read');
    $result = array();
    foreach ($rs_columns as $row_columns) {
        $result[] = $row_columns['Field'];
    }
    return $result;
}
/**
 * Hàm lấy dữ liệu id client
 * @param không
 */
function Gnud_get_ip_client(){
// Ip address
	$ipaddress = 'UNKNOWN';
	if ($_SERVER['HTTP_CLIENT_IP'] != '127.0.0.1' && !empty($_SERVER['HTTP_CLIENT_IP'])){
		$ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	} else if ($_SERVER['HTTP_X_FORWARDED_FOR'] != '127.0.0.1'  && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else if ($_SERVER['HTTP_X_FORWARDED'] != '127.0.0.1'  && !empty($_SERVER['HTTP_X_FORWARDED'])){
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	} else if ($_SERVER['HTTP_FORWARDED_FOR'] != '127.0.0.1'  && !empty($_SERVER['HTTP_FORWARDED_FOR'])){
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	} else if ($_SERVER['HTTP_FORWARDED'] != '127.0.0.1'  && !empty($_SERVER['HTTP_FORWARDED'])){
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	} else if ($_SERVER['REMOTE_ADDR'] != '127.0.0.1'  && !empty($_SERVER['REMOTE_ADDR'])){
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	}
	$ipaddress = $ipaddress;
	return $ipaddress;
}