<?php
__get_db_functions('db.general');
class register_block extends Fw24H_Block
{
	var $_cache_key = 'header_092018';
    function index($v_device_global = 'pc', $view_type = 1, $noindex = 1, $gen_key = 0)
    {
        $this->setParamAll(get_defined_vars(), array('number_items'));
		$this->generate();
	}
		
    function doAction()
    {
        $v_device_global = fw24h_replace_bad_char($this->getParam('v_device_global'));
        if($v_device_global == ''){
            global $v_device_global;
        }
        // Chuyển sang view PC
        $this->dsp_register();
    }
    function dsp_register(){
        $this->render($this->thisPath().'view/register.php');
    }
    function dang_ky_thanh_vien(){
        /*if((isset($_POST['g_recaptcha']) && !empty($_POST['g_recaptcha']))){
            $secret = '6LcRnCwfAAAAAB8En9mN2Yi6RRjrbWh7YGP0MHSy'; // Key của site
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g_recaptcha']);
            $responseData = json_decode($verifyResponse);
            if($responseData->success)
            {*/
                $name = fw24h_replace_bad_char($_POST['name']);
                $name_ascii = _utf8_to_ascii($name);
                //$email = fw24h_replace_bad_char($_POST['email']);
                $your_addrest = fw24h_replace_bad_char($_POST['your_addrest']);
                $phone_number = fw24h_replace_bad_char($_POST['phone_number']);
                $your_message = fw24h_replace_bad_char($_POST['your_message']);
                
                $sql = "call fe_cap_nhat_registration(
                        '$name'
                        ,'$name_ascii'
                        ,'$phone_number'
                        ,''
                        ,'$your_addrest'
                        ,'$your_message'
                    );";
                $rs = Gnud_Db_write_query($sql);
                $result = [] ;
                if(intval($rs[0]['c_id']) > 0){
                    $result['err'] = false;
                    $result['msg'] = 'Đăng ký thành công. Chúng tôi sẽ liên hệ với bạn';
                }
                echo json_encode($result);die;
            /*}else{
                $result['suscess'] = 0;
                $result['message'] = 'Recaptcha không đúng';
                echo json_encode($result);die;
            }
        }else{
            $result['suscess'] = 0;
            $result['message'] = 'Bạn phải chọn recaptcha';
            echo json_encode($result);die;
        }*/
    }
}