<?php
if ($_POST) {
    $v_links = $_POST['txt_links'];
    $v_code = htmlspecialchars('<div align="center"><script type="text/javascript">flashWrite("/images/24hvideo_player.swf?file='.$v_links.'",418,314);</script></div>');
    ?>
	<div class="padBot"><textarea cols="60" rows="4" style="background-color:#ffffcc" onfocus="this.select();"><?php echo $v_code; ?></textarea></div>
    <?php
}
?>
<div class="padBot">Điền đường link của file vừa upload vào đây (Ngăn cách bằng dấu phẩy ","):</div>
<form method="post" action="">
    <textarea rows="8" name="txt_links" cols="60" class="auto-title" title="Chèn link vào đây, thoải mái về số lượng!">Chèn link vào đây, thoải mái về số lượng!</textarea><br><br>  
    <input type="submit" value="Sinh mã" />
    <input type="button" value="Đóng cửa sổ" onclick="window.close()" />
</form>