<?php
// Begin: toi_uu_key_value anhnt1: 29/09/2015

/* tạo kết nối tới redis
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_kieu		kiểu kết nối: read/write
* return : connect redis
*/
function _connect_redis($p_kieu = 'read'){
	global $v_arr_redis_config;
	global $v_arr_redis_connect;
	
	if (!check_array($v_arr_redis_config[$p_kieu])){
		# nếu không có cấu hình tương ứng. Thoát
		return;
	}
	
	if (check_array($v_arr_redis_connect[$p_kieu]) && $v_arr_redis_connect[$p_kieu]['c_connected'] === true){
		# nếu đã có kết nối từ trước. Thoát
		return;
	}
	
	# thực hiện kết nối
	$v_arr_redis_connect[$p_kieu]['c_ojb_connect'] = new Redis();
	$v_arr_redis_connect[$p_kieu]['c_connected'] = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->connect($v_arr_redis_config[$p_kieu]['server'], $v_arr_redis_config[$p_kieu]['port']);
	if ($v_arr_redis_connect[$p_kieu]['c_connected'] === true){
		# password bảo mật nếu có
		$v_password = $v_arr_redis_config[$p_kieu]['password'];
		if (!is_null($v_password) && $v_password != ''){
			$v_arr_redis_connect[$p_kieu]['c_ojb_connect']->auth($v_password);
		}
		
		# lựa chọn db redis
		$v_arr_redis_connect[$p_kieu]['c_ojb_connect']->select($v_arr_redis_config[$p_kieu]['database']);
	}
}

/* Ngắt kết nối tới redis
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_kieu		kiểu kết nối: read/write
* return :
*/
function _close_redis($p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	
	# thực hiện ngắt kết nối
	$v_arr_redis_connect[$p_kieu]['c_ojb_connect']->close();
	# xóa thông tin kết nối đã có
	unset($v_arr_redis_connect[$p_kieu]);
}

/* Lấy tổng số key đang có trong db redis đang dùng
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_kieu		kiểu kết nối: read/write
* return :
*/
function _get_total_key_redis($p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	
	return $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->dbSize();
}

/* xóa toàn bộ số key đang có trong db redis
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_kieu		kiểu kết nối: read/write
* return :
*/
function _delete_all_key_redis($p_kieu = 'write'){
	global $v_arr_redis_connect;
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	
	return $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->flushAll();
}

/* lấy thời gian cuối cùng cập nhật vào redis
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_format	Định dạng time xuất ra
* param text	p_kieu		kiểu kết nối: read/write
* return :
*/
function _get_last_time_update_redis($p_format = 'Y-m-d H:i:s', $p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	
	$v_time = intval($v_arr_redis_connect[$p_kieu]['c_ojb_connect']->lastSave());
	if ($v_time <= 0){
		return '';
	}
	
	$p_format = (is_null($p_format) || $p_format == '') ? 'Y-m-d H:i:s' : $p_format;
	return date($p_format, $v_time);
}

/* Làm mới lại tất cả các key có trong redis
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_kieu		kiểu kết nối: read/write
* return :
*/
function _refresh_all_key_redis($p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	
	return $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->resetStat();
}

/* set giá trị 1 key
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_ten		tên key
* param text	p_value		giá trị dạng string (base64_decode)
* param text	p_time		Thời gian duy trì key tính theo giây
* param text	p_kieu		kiểu kết nối: read/write
* return : boolean: có set được hay không
*/
function _set_key_value_redis($p_ten, $p_value, $p_time = 0, $p_kieu = 'write'){
	global $v_arr_redis_connect;
	global $v_arr_redis_config;
	
	if (is_null($p_ten) || $p_ten == ''){
		# không xác định tên key. Thoát
		return false;
	}
	
	$v_start_time = microtime(true); # debug thời gian thực hiện
	
	_connect_redis($p_kieu); // thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return false;
	}
	
	$p_value = strval($p_value);
	$p_time = intval($p_time);
	if ($p_time > 0){
		$v_seted = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->set($p_ten, $p_value, $p_time);
	} else {
		$v_seted = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->set($p_ten, $p_value);
	}
	// anhnt1 06/10/2015: lưu trạng thái  ghi thành công hay ko thành công
	$v_status = '';
	if ($v_seted){
		$v_status = 'OK_'.$v_arr_redis_config['write']['server'];
	}else{
		$v_status = 'NOT_OK_'.$v_arr_redis_config['write']['server'];
		// Nếu ghi không thành công thì xóa key trong redis - để việc đọc key sẽ chuyển sang đọc từ mysql & đồng thời ghi lại redis-key
		_delete_1_key_redis('write', $p_ten);
	}
	
	$v_end_time = microtime(true); // debug thời gian thực hiện
	
	$v_str_log = date('Y-m-d H:i:s : ').$v_status.':thực hiện _set_key_value_redis {'.$p_ten.'} trong '.round($v_end_time - $v_start_time, 6).' giây'."\n";
	if (DEBUG_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis'.date('H').'.log');
	}
	
	if (DEBUG_SLOW_REDIS && ($v_end_time - $v_start_time) >= MAX_TIME_EXE_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis_slow.log');
	}
	
	return $v_seted;
}

/* get giá trị 1 key
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_ten		tên key
* param text	p_kieu		kiểu kết nối: read/write
* return : String: giá trị của key
*/
function _get_key_value_redis($p_ten, $p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	if (is_null($p_ten) || $p_ten == ''){
		# không xác định tên key. Thoát
		return false;
	}
	
	$v_start_time = microtime(true); # debug thời gian thực hiện
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return false;
	}
	
	$v_value = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->get($p_ten);
	
	$v_end_time = microtime(true); # debug thời gian thực hiện
	
	$v_str_log = date('Y-m-d H:i:s : ').'thực hiện _get_key_value_redis {'.$p_ten.'} trong '.round($v_end_time - $v_start_time, 6).' giây'."\n";
	if (DEBUG_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis'.date('H').'.log');
	}
	
	if (DEBUG_SLOW_REDIS && ($v_end_time - $v_start_time) >= MAX_TIME_EXE_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis_slow.log');
	}
	
	return $v_value;
}

/* lấy thời gian tồn tại còn lại của 1 key
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_ten		tên key
* param text	p_kieu		kiểu kết nối: read/write
* return : int: thời gian còn lại. Tính theo giây : giá trị đặc biệt: -1: không giới hạn; -2: không tồn tại key
*/
function _get_key_ttl_redis($p_ten, $p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	if (is_null($p_ten) || $p_ten == ''){
		# không xác định tên key. Thoát
		return false;
	}
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return false;
	}
	
	return $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->ttl($p_ten);
}

/* lấy thời gian tồn tại còn lại của 1 key
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param text	p_chuoi_tim_kiem	chuỗi từ khóa tìm kiếm (vd: *tim*)
* param text	p_kieu				kiểu kết nối: read/write
* return : Mảng các tên key thỏa mãn điều kiện tìm kiếm
*/
function _search_key_redis($p_chuoi_tim_kiem = '', $p_kieu = 'read'){
	global $v_arr_redis_connect;
	
	$v_start_time = microtime(true); # debug thời gian thực hiện
	
	_connect_redis($p_kieu); # thử kết nối trước khi làm bất cứ gì
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return false;
	}
	
	$p_chuoi_tim_kiem = is_null($p_chuoi_tim_kiem) ? '' : $p_chuoi_tim_kiem;
	
	# thực hiện tìm kiếm theo tên key
	$v_arr_rerun = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->keys($p_chuoi_tim_kiem);
	
	
	$v_end_time = microtime(true); # debug thời gian thực hiện
	
	$v_str_log = date('Y-m-d H:i:s : ').'thực hiện _search_key_redis {'.$p_chuoi_tim_kiem.'} trong '.round($v_end_time - $v_start_time, 6).' giây'."\n";
	if (DEBUG_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis'.date('H').'.log');
	}
	
	if (DEBUG_SLOW_REDIS && ($v_end_time - $v_start_time) >= MAX_TIME_EXE_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis_slow.log');
	}
	
	return $v_arr_rerun;
}

/* Tạo tên key dùng cho redis. phục vụ tìm kiếm sau này
* Creater: HaiLT 20150311
* Last edit: HaiLT 20150311
* param array	p_arr_config		mảng cấu hình tạo tên key
* return : text 	tên key
*/
function _make_name_key_redis($p_arr_config = ''){
	$v_ten_key = '';
	
	$v_muc_dich_su_dung = strval($p_arr_config['c_muc_dich_su_dung']);
	if (!is_null($v_muc_dich_su_dung) && $v_muc_dich_su_dung != ''){
		$v_ten_key .= $v_muc_dich_su_dung.':'; # keyvalue | cache | log | session
	}
	
	$v_loai_du_lieu = strval($p_arr_config['c_loai_du_lieu']);
	if (!is_null($v_loai_du_lieu) && $v_loai_du_lieu != ''){
		$v_ten_key .= $v_loai_du_lieu.':'; # tên table | tên trang
	}
	
	$v_kieu_du_lieu = strval($p_arr_config['c_kieu_du_lieu']);
	if (!is_null($v_kieu_du_lieu) && $v_kieu_du_lieu != ''){
		$v_ten_key .= $v_kieu_du_lieu.':'; # html | data | string 
	}
	
	$v_ten_du_lieu = strval($p_arr_config['c_ten_du_lieu']);
	if (!is_null($v_ten_du_lieu) && $v_ten_du_lieu != ''){
		$v_ten_key .= $v_ten_du_lieu.':'; # tên key trong table | url trang đã md5
	}
	
	$v_ten_key = (!is_null($v_ten_key) && $v_ten_key != '') ? $v_ten_key : 'khong_xac_dinh';
	return $v_ten_key;
}

/* xóa một key đang có trong db redis
* Creater: Lucnd 20150926
* Last edit: Lucnd 20150926
* param text	p_kieu		kiểu kết nối: read/write
* param text	p_key		từ khóa lưu trử dữ liệu
* return :
*/
function _delete_1_key_redis($p_kieu = 'write', $p_key){
	global $v_arr_redis_connect;
	// debug thời gian thực hiện
	$v_start_time = microtime(true); 
	// thử kết nối trước khi làm bất cứ gì
	_connect_redis($p_kieu); 
	if (!check_array($v_arr_redis_connect[$p_kieu]) || $v_arr_redis_connect[$p_kieu]['c_connected'] !== true){
		# nếu kết nối không có hoặc ko kết nối được từ trước. Thoát
		return;
	}
	// debug thời gian thực hiện
	$v_end_time = microtime(true); 
	
	// Xóa key
	$v_return = $v_arr_redis_connect[$p_kieu]['c_ojb_connect']->del($p_key);
	
	// Kiểm tra kết quả xóa & ghi log
	$v_status = '';
	if ($v_return){
		$v_status = 'OK';
	}else{
		$v_status = 'NOT_OK';
	}

	$v_str_log = date('Y-m-d H:i:s : ').$v_status.':thực hiện _delete_1_key_redis {'.$p_key.'} trong '.round($v_end_time - $v_start_time, 6).' giây'."\n";
	if (DEBUG_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis'.date('H').'.log');
	}
	
	if (DEBUG_SLOW_REDIS && ($v_end_time - $v_start_time) >= MAX_TIME_EXE_REDIS){
		@error_log($v_str_log, 3, WEB_ROOT.'logs/redis_slow.log');
	}	
	return $v_return;
}

/* Kiểm tra xem có phải là key chỉ đọc dữ liệu từ redis (ko đọc từ mysql)
* Creater: Lucnd 20150926
* Last edit: Lucnd 20150926
* param text	p_key		từ khóa lưu trữ dữ liệu
* return : true - nếu $p_key có chứa cụm tiền tố khai báo trong $v_arr_key_read_from_redis_only 
*/
function _if_key_read_from_redis_only($p_key){
    // Kiểm tra khỏi tạo hằng số ban đầu để tránh lỗi
    if (!defined('KEY_READ_FROM_MYSQL')) {
        // Mảng key sẽ được đọc từ mysql
        define('KEY_READ_FROM_MYSQL', 'data_bai_viet_theo_id');
    }
    if (!defined('ID_BAI_KHONG_QUERRY_MYSQL')) {
        // Mảng key sẽ được đọc từ mysql
        define('ID_BAI_KHONG_QUERRY_MYSQL', 1011200);
    }
    // Nếu ko sử dụng redis thì trả lại false
	if (!USE_REDIS) return false;
    // Đưa các key được phép đọc từ mysql
	$v_arr_key  = explode(',',KEY_READ_FROM_MYSQL);
    foreach ($v_arr_key as $v_key){
        // Chuyển hết tên key về chữ thường để so sánh
        $v_key = strtolower($v_key);
        $p_key = strtolower($p_key);
        // Nếu $p_key không chứa cụm tiền tố khai báo trong mảng thì sẽ không được phép đọc từ mysql
        if (!preg_match("#$v_key#", $p_key) || ($v_key == 'data_bai_viet_theo_id' && intval(str_replace($v_key,'',$p_key)) < ID_BAI_KHONG_QUERRY_MYSQL)){
            return true;
        }
    }
	return false;
}
// End: toi_uu_key_value anhnt1: 29/09/2015