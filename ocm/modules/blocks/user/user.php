<?php
__get_db_functions('db.user'); 
__get_db_functions('db.general'); 
//07-03-2015 Thangnb add
include WEB_ROOT . 'includes/google2fa.php';
//End 07-03-2015 Thangnb add
class user_block extends Fw24H_Block
{
   /**
	* $Author: tuanna
	* $Date: 2012-02-16 08:50:00 +0700 (Thu, 16 Feb 2012) $
	* index 
	* @return     string 
	*/	

    function index()
    {				
		$this->setParamAll();		
		$this->generate();
	}

    function doAction()
    {	
		
		$this->setParam('media_data', $media_data);
		$this->render();		
    }
	
    function dsp_change_password()
    {
		$v_goback = $_REQUEST['goback'];
		if ((int)$_SESSION['user_id'] > 0 && $_SESSION['user'] != '') {
		 	$v_user_name = $_SESSION['user'];
			$row_user = user_staffgetsinglebyusername($v_user_name);
			$this->setParam('row_user', $row_user);
			$this->setParam('v_goback', $v_goback);
			$this->render($this->thisPath().'view/dsp_change_password.php');
		}		
    }
	
    function act_change_pass()
    {
		$v_old_password = $_POST['txt_old_password'];
		// Neu khong thay doi mat khau, chi cap nhat author snippet
		if ($v_old_password != '') {
			if ($_POST['txt_old_password'] == '') {
				js_message('Bạn chưa nhập mật khẩu đang dùng');
				js_set('top.document.frm_dsp_single_item.txt_old_password.focus()');
				die;
			}
			if ($_POST['txt_password'] == '') {
				js_message('Bạn chưa nhập mật khẩu');
				js_set('top.document.frm_dsp_single_item.txt_password.focus()');
				die;
			}
			if ($_POST['txt_re_password'] == '') {
				js_message('Bạn chưa nhập lại mật khẩu');
				js_set('top.document.frm_dsp_single_item.txt_re_password.focus()');
				die;
			}
			
			if ($_POST['txt_re_password'] != $_POST['txt_password']) {
				js_message('Nhập lại mật khẩu chưa đúng.');
				js_set('top.document.frm_dsp_single_item.txt_re_password.focus()');
				die;
			}
			//Begin 18-11-2016 : thangnb fix_loi_bao_mat_change_pass
			$user = user_get_single_by_username(fw24h_replace_bad_char($_POST['txt_username']));
			if (!check_array($user)) {
				js_message('Thông tin không hợp lệ');
				die;				
			}
			if ($_POST['hdn_old_password'] != $user['Password']) {
				js_message('Mật khẩu đang dùng không đúng');
				js_set('top.document.frm_dsp_single_item.txt_old_password.focus()');
				die;
			}		
			//End 18-11-2016 : thangnb fix_loi_bao_mat_change_pass
			if (!_kiem_tra_do_manh_matkhau($_POST['txt_password'])) {
				js_message('Mật khẩu không đủ mạnh, vui lòng nhập lại:\n- Bat dau la chu in thuong\n- Co toi thieu 1 chu IN HOA\n- Co toi thieu 1 so\n- Ko co ky tu space\n- Do dai toi thieu 6 ky tu');
				js_set('top.document.frm_dsp_single_item.txt_password.focus()');
				die;
			}
		} 
		// kiem tra author snippet
		$v_author_snippet = _utf8_to_ascii($_POST['txt_author_snippet']);
		if ($v_author_snippet != '' && strlen($v_author_snippet) > 200) {
			js_message('Author Snippet tối đa 200 ký tự');
			js_set('top.document.frm_dsp_single_item.txt_author_snippet.focus()');
			die;
		}
		
		$v_password = md5(fw24h_replace_bad_char($_POST['txt_password']));
		if ($v_old_password =='') {
			$v_user_name = $_SESSION['user'];
			$rs_user = user_staffgetsinglebyusername($v_user_name);
			$v_password	 = $rs_user['C_PASSWORD'];
		}		
		$rs = User_24h_ChangePass($_SESSION['staff_id'],$v_password );	
		if ($rs > 0) {
			ocm_ChangePass($_SESSION['staff_id'], $_SESSION['user_id'], $v_password, $v_author_snippet);
			if ($v_old_password !='') {
				$v_logout = html_link('ajax/user/logout.php', false);
				js_message('Đổi mật khẩu thành công.');
				js_set('window.location = "'.$v_logout.'"');
			} else {
				js_message('Cập nhật author snippet thành công.');
				die;	
			}	
		}
    }
	
	function logout() {
		//Begin 03-02-2016 : Thangnb fix_loi_bao_mat_rapid7 session
		session_start();
		@session_unset();
		@session_destroy();
		@session_regenerate_id(true);
		//End 03-02-2016 : Thangnb fix_loi_bao_mat_rapid7 session
		echo "<script>top.location.href=\"" .BASE_URL. "user/login\"</script>";
	}
	function dologin() {
		if ($_POST) {
			$username1 = fw24h_replace_bad_char($_POST['username']);   			
			$password1= fw24h_replace_bad_char($_POST['password']);
			// Begin TungVN 07-09-2017 - xu_ly_redirect_login
			$redirect = trim(urldecode(base64_decode(fw24h_restore_bad_char($_SESSION['redirect']))));
            if ($redirect == '' || strpos($redirect, BASE_URL) !== 0) {
                unset($_SESSION['redirect']);
                $redirect = BASE_URL. 'index.php';
            }
            // End TungVN 07-09-2017 - xu_ly_redirect_login
			//7-3-2015 : Thangnb add lay bien token key va xu ly kiem tra nhap du lieu
			$token = 1;
			
			if (KIEM_TRA_OTP) {
				$token = fw24h_replace_bad_char($_POST['token']);
			}
			
			// Kiểm tra đã nhập đầy đủ thông tin
			if ($username1 == '' || $password1 == '' || $token == '') {
				js_message('Bạn chưa nhập đầy đủ thông tin đăng nhập');		
				exit;
			}
			//End 7-3-2015 : Thangnb add lay bien token key
			
			// Kiểm tra đã nhập đầy đủ thông tin
			if ($username1 == 'pentestadmin1' || $username1 == 'pentestadmin2' || $username1 == 'pentestadmin3' || $username1 == 'pentestview1') {
				js_message('Thông tin đăng nhập không hợp lệ.');		
				exit;
			}
			//End 7-3-2015 : Thangnb add lay bien token key
			// phuonghv edit 04/05/2015: Trường hợp cố tình truyền tham số dạng mảng để bắt lỗi bảo mật, thì thoát luôn 
            if(check_array($username1) || check_array($password1)) {
                js_message("Thông tin đăng nhập không hợp lệ."); 
				exit;
            }
            // end: phuonghv edit 04/05/2015
            
			if (!fw24h_isUsername($username1)) {
				//set_js_alert("Lỗi tài khoản có ký tự đặc biệt!");				
                js_message("Lỗi tài khoản có ký tự đặc biệt!"); // phuonghv edit 27/03/2015
				exit;
			}           
            
            /* Begin anhpt1 06/07/2016 fix_loi_quyen_session_fixation */
            session_regenerate_id();
            /* End anhpt1 06/07/2016 fix_loi_quyen_session_fixation */
			$row = user_all($username1);
			if (check_array($row) && $row["Password"] == md5($password1) && $row["Activate"] == 1) {
					//Khoi tao cac gia tri cho nguoi dang nhap thanh cong	
					$_SESSION['user'] = $username1;
					$_SESSION['user_name'] = $username1;
					$_SESSION['user_pass'] = $password1;
					$_SESSION['user_id'] = intval($row['ID']);
			} else {
				set_message_for_div('login-note', '<b style=\"color:red\">Thông tin đăng nhập không hợp lệ.<b/>');
				exit;
			}
			// Begin TungVN 07-09-2017 - xu_ly_redirect_login
			js_redirect($redirect);
            // End TungVN 07-09-2017 - xu_ly_redirect_login
		}
	}
	
	function is_logined()
	{
		return $_SESSION['user_name'];
	}
	
	/**
	* Hien thi man hinh danh sach bai viet/su kien xuat ban
	*/
	function dsp_all_user_by_select()
    {
		_setLayout('ajax');
        html_set_title('Chọn danh sách người thực hiện bài viết');
        
        // khai bao danh sach cac bien = _REQUEST
        $this->_arr_arg = array (
			'txt_title' => array('', '_utf8_to_ascii'),
            'page' => array(1, 'page_val'),
            'number_per_page' => array(15, 'intval')
        );
        
        $this->getRequest();
        $v_title = fw24h_replace_bad_char($this->_GET['txt_title']);
        $page = $this->_GET['page'];
		$number_per_page = $this->_GET['number_per_page'];  
        $v_arr_user = be_get_all_users($v_title,  $page, $number_per_page);        
		$this->setParam('v_arr_user', $v_arr_user['data']);
        $this->setParam('v_record_count', count($v_arr_user['data']));
        $this->setParam('v_paging', _db_page(count($v_arr_user['data']), $page, $number_per_page));
        $this->setParam('urlHelper', $urlHelper);            
        $this->render($this->thisPath().'view/dsp_all_user_by_select.php');
	}
}

