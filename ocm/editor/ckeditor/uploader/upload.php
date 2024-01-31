<?php
//Begin 17-11-2016 : Thangnb fix_loi_bao_mat_sql_injection_xss
session_start();
if (!(isset($_SESSION['staff_id'])  && isset($_SESSION['user_name']) && $_SESSION['staff_id'] != '' && $_SESSION['user_name'] != '')) {
	die;
}
$v_web_root = realpath(dirname(__FILE__));
$v_web_root = str_replace('editor/ckeditor/uploader', '', $v_web_root);
define('WEB_ROOT', $v_web_root);
include WEB_ROOT.'includes/app_common.php';
include WEB_ROOT.'modules/blocks/upload_image/upload_image.php';
$v_upload_obj = new upload_image_block();
$v_result = $v_upload_obj->act_upload_single_image($_FILES['upload']);
?>
<html>
<body>
<script type="text/javascript">
window.parent.CKEDITOR.tools.callFunction(<?php echo $_GET['CKEditorFuncNum']; ?>, '<?php echo $v_result['file_path']; ?>', '<?php echo implode("\n", $v_result['errors']); ?>');
</script>
</body>
</html>