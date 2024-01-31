<?php

function Gnud_Db_read_connect($new_link=FALSE)
{
    global $fwDbConfig;
	global $fwDbReadConn;
	global $fwDbReadConnNewLink;
    if(is_object($fwDbReadConn) && !$fwDbReadConnNewLink) {
	    return $fwDbReadConn;
	}

	$fwDbReadConn = @mysqli_connect($fwDbConfig['read']['server'], $fwDbConfig['read']['username'], $fwDbConfig['read']['password'],$fwDbConfig['read']['database']) or trigger_error( mysqli_connect_errno(), E_USER_ERROR);
	//mysqli_query($fwDbReadConn,"SET NAMES 'utf8'");
	return $fwDbReadConn;
}

function Gnud_Db_read_close()
{
    global $fwDbReadConn;
    if(is_object($fwDbReadConn)) {
        mysqli_close($fwDbReadConn);
	}
	$fwDbReadConn = NULL;
}

/**
 * Thực hiện SQL cho CSDL chỉ đọc
 * @param string $sql Câu lệnh SQL cần thực hiện
 * @return resultset
 */
function Gnud_Db_read_sql_query($sql)
{
    global $fwDbReadConn;
    Gnud_Db_read_connect();
    $rs = mysqli_query($fwDbReadConn, $sql) or trigger_error( mysqli_error($fwDbReadConn).'@@@'.$sql, E_USER_ERROR);
    return $rs;
}

/**
 * Thực hiện store cho CSDL chỉ đọc
 * @param $sql Store cần thực hiện
 * @return resultset
 */
function Gnud_Db_read_call_store($sql)
{
    global $fwDbReadConn;
    Gnud_Db_read_connect();
    $rs = mysqli_query($fwDbReadConn, $sql, MYSQLI_STORE_RESULT) or trigger_error( mysqli_error($fwDbReadConn).'@@@'.$sql, E_USER_ERROR);
    return $rs;
}

/**
 * Thực hiện store cho CSDL chỉ đọc
 * @param $sql Store cần thực hiện
 * @return resultset
 */
function Gnud_Db_read_call_store_multi($sql)
{
    global $fwDbReadConn;
    Gnud_Db_read_connect();
	$time_start = microtime(true);
    $rs = mysqli_multi_query($fwDbReadConn, $sql) or trigger_error( mysqli_error($fwDbReadConn).'###'.$sql, E_USER_ERROR);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	// echo '<hr>',$sql,' ',$time.'<hr>';
    return $rs;
}

function Gnud_Db_read_fetch_store_multi($rs)
{
    global $fwDbReadConn;
    $count = 0;
    $result2 = array();
    do {
        /* store first result set */
        if ($result = mysqli_store_result($fwDbReadConn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $result2['record'.$count][] = $row;
            }
            mysqli_free_result($result);
        }
        /* print divider */
        if (mysqli_more_results($fwDbReadConn)) {
            $count++;
        }
    } while (mysqli_next_result($fwDbReadConn));
    if(sizeof($result2) == 1) {
        $result2 = $result2['record0'];
    }
    return $result2;
}

/**
 * Xử lý 1 resultset trả về từ Gnud_Db_read_call_store đặt vào mảng 2 chiều
 * @param resultset $rs
 * @return array
 */
function Gnud_Db_read_fetch_store($rs)
{
    global $fwDbReadConn;
    $result = array();
    if(mysqli_num_rows($rs)>0) {
        $result[] = mysqli_fetch_assoc($rs);
	    while (mysqli_next_result( $fwDbReadConn)) {
			mysqli_use_result( $fwDbReadConn);
			$result[] = mysqli_fetch_row( $rs);
			mysqli_free_result( $rs);
		}
    }
    return $result;
}
/**
 * Xử lý 1 resultset trả về từ Gnud_Db_read_sql_query đặt vào mảng 2 chiều
 * @param resultset $result
 * @return array
 */
function Gnud_Db_read_fetch_row($result)
{
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);
    return $rows;
}

function Gnud_Db_read_query_multi($sql)
{
    $rs = Gnud_Db_read_call_store_multi($sql);
    $result = Gnud_Db_read_fetch_store_multi($rs);
    return $result;
}

/**
 * Lấy thông tin tên các field trong 1 bảng
 * @param $table Tên table cần lấy tất cả các field
 * @return array Danh sách các field
 */
function Gnud_Db_read_show_columns($table)
{
    $sql = 'SHOW COLUMNS FROM '.$table;
    $rs_columns = Gnud_Db_read_sql_query($sql);
    $result = array();
    while ($row_columns = mysqli_fetch_assoc($rs_columns)) {
        $result[] = $row_columns['Field'];
    }
    mysqli_free_result($rs_columns);
    return $result;
}

/**
 * Thực thi 1 câu lệnh SQL đến CSDL ghi
 * @param string $sql
 * @return resultset
 */
function Gnud_Db_write_query($sql, $close_link=true)
{
    global $fwDbWriteConn;
    $sql = gnud_apply_filters('gnud_db_write_query', $sql);
    // Gnud_Db_write_connect();
    $time_start = microtime(true);
	$result = Gnud_Db_write_query_multi($sql);
    $time_end = microtime(true);
    
    if (GNUD_DEBUG_MODE) {
        $time = $time_end - $time_start;
        $time = number_format($time, 12);
        $time = substr($time, 0, 9);
		$errorMsg = date('Y-m-d H:i:s')." $time $sql\n";
		@error_log($errorMsg, 3, WEB_ROOT.'/logs/sql.log');
        @chmod(WEB_ROOT.'/logs/sql.log', 0777);
        global $SQL_IN_BLOCK;
        $SQL_IN_BLOCK .= $errorMsg;
    }
    // $result = mysqli_query($fwDbWriteConn, $sql) or trigger_error( mysqli_error($fwDbWriteConn).'@@@'.$sql, E_USER_ERROR);
    // if($close_link) {
        // Gnud_Db_write_close();
    // }
    return $result;
}

/**
 * Mở 1 kết nối đến CSDL ghi
 * @return object
 */
function Gnud_Db_write_connect()
{
    global $fwDbConfig;
	global $fwDbWriteConn;
	if(is_object($fwDbWriteConn)) {
		mysqli_close($fwDbWriteConn);
	}
	/* @var $fwDbWriteConn unknown_type */
	$fwDbWriteConn = @mysqli_connect($fwDbConfig['write']['server'], $fwDbConfig['write']['username'], $fwDbConfig['write']['password'],$fwDbConfig['write']['database']) or trigger_error( mysqli_error($fwDbWriteConn), E_USER_ERROR);
	//mysqli_query($fwDbReadConn,"SET NAMES 'utf8'");
	return $fwDbWriteConn;
}
/**
 * Đóng kết nối đến CSDL ghi
 */
function Gnud_Db_write_close()
{
    global $fwDbWriteConn;
    if(is_object($fwDbWriteConn)) {
		@mysqli_close($fwDbWriteConn);
	}
	$fwDbWriteConn = NULL;
}

/**
 * Insert dữ liệu vào 1 bảng đến CSDL ghi
 * @param string $table Tên bảng cần insert
 * @param array $data mảng chứa thông tin cần insert
 * @return integer $lastId ID bản ghi được insert
 */
function Gnud_Db_write_insert($table, $data)
{
    global $fwDbWriteConn;
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
    Gnud_Db_write_query($sqlInsert, FALSE);
    $lastId = mysqli_insert_id($fwDbWriteConn);
    Gnud_Db_write_close();
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
    Gnud_Db_write_query($query, FALSE);
    $affected_rows = mysqli_affected_rows($fwDbWriteConn);
    Gnud_Db_write_close();
    return $affected_rows;
}

/**
 * Cập nhật dữ liệu vào 1 bảng theo dạng key_value đến server master có kiểm tra checksum
 * @param array $record mảng chứa thông tin cần update: các key: C_KEY - id key cần update/insert, C_VALUE -  dữ liệu cần ghi
 * @param string $table Tên bảng cần insert/update
 * @return integer 0 nếu dữ liệu ko thay đổi, 1 nếu cập nhật thành công
 */
function Gnud_Db_write_update_key($record, $table='key_value')
{
    if ($record['C_KEY'] == '' || $record['C_VALUE'] == '') {
        return 0;
    }

    // checksum
    $v_value_need_check = Gnud_Db_read_get_key($record['C_KEY'], $table);
    if (md5($record['C_VALUE']) == md5($v_value_need_check)) {
        return 0;
    }

    $record['C_VALUE'] = $record['C_VALUE'];
    $record['C_LAST_MODIFIED'] = date('Y-m-d H:i:s');
    $record['C_TYPE'] = $record['C_TYPE'];

    // update or insert
    if ($v_value_need_check == '') { //insert
        Gnud_Db_write_insert($table, $record);
    } else { //update
        Gnud_Db_write_update($table, $record, 'C_KEY="'.$record['C_KEY'].'"');
    }
    return 1;
}



/**
 * Thực hiện store cho CSDL chỉ đọc
 * @param $sql Store cần thực hiện
 * @return resultset
 */
function Gnud_Db_write_call_store_multi($sql)
{
    global $fwDbWriteConn;
    Gnud_Db_write_connect();
	$time_start = microtime(true);
    $rs = mysqli_multi_query($fwDbWriteConn, $sql) or trigger_error( mysqli_error($fwDbWriteConn).'###'.$sql, E_USER_ERROR);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
	// echo '<hr>',$sql,' ',$time.'<hr>';
    return $rs;
}

function Gnud_Db_write_fetch_store_multi($rs)
{
    global $fwDbWriteConn;
    $count = 0;
    $result2 = array();
    do {
        /* store first result set */
        if ($result = mysqli_store_result($fwDbWriteConn)) {
            while ($row = mysqli_fetch_assoc($result)) {
                $result2['record'.$count][] = $row;
            }
            mysqli_free_result($result);
        }
        /* print divider */
        if (mysqli_more_results($fwDbWriteConn)) {
            $count++;
        }
    } while (mysqli_next_result($fwDbWriteConn));
    if(sizeof($result2) == 1) {
        $result2 = $result2['record0'];
    }
    return $result2;
}

/**
 * Xử lý 1 resultset trả về từ Gnud_Db_read_call_store đặt vào mảng 2 chiều
 * @param resultset $rs
 * @return array
 */
function Gnud_Db_write_fetch_store($rs)
{
    global $fwDbWriteConn;
    $result = array();
    if(mysqli_num_rows($rs)>0) {
        $result[] = mysqli_fetch_assoc($rs);
	    while (mysqli_next_result( $fwDbWriteConn)) {
			mysqli_use_result( $fwDbWriteConn);
			$result[] = mysqli_fetch_row( $rs);
			mysqli_free_result( $rs);
		}
    }
    return $result;
}
/**
 * Xử lý 1 resultset trả về từ Gnud_Db_read_sql_query đặt vào mảng 2 chiều
 * @param resultset $result
 * @return array
 */
function Gnud_Db_write_fetch_row($result)
{
    $rows = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    mysqli_free_result($result);
    return $rows;
}

/**
 * Thuc hien sql stm vao db master
 * @param string $sql sql or store name can thuc hien
 * @return array
 */
function Gnud_Db_write_query_multi($sql)
{
    $rs = Gnud_Db_write_call_store_multi($sql);
    $result = Gnud_Db_write_fetch_store_multi($rs);
    return $result;
}
