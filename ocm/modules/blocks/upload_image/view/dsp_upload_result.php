<?php
if (count($v_arr_error)>0) {
    ?>
    <div class="error_box">
        <ul>
            <?php
            foreach ($v_arr_error as $v_error) {
                ?>
                <li><?php echo $v_error; ?></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
}
if (count($v_uploaded_file)>0) {
    ?>
    <center>
        <?php
		//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        foreach ($v_uploaded_file as $key => $v_file) {
            $v_size = getimagesize(substr(BASE_DOMAIN, 0, -1).$v_file);
			if ($v_arr_image_type[$key] == 'gif') {
				$v_file = get_image_thumbnail($v_file,'medium',true);
            ?>
            <h2 style="padding:10px 0px">Copy code sau dán vào phần nhập tin bài </h2>
            <textarea rows="10" cols="60"><div id='non-gif-image-gif-{{count_gif}}' align='center'><img id="non-gif-img-image-gif-{{count_gif}}" src="<?php echo $v_file; ?>" /><div align='center'><img id='gif-image-gif-{{count_gif}}' src='' style="display:none" /></div></div></textarea>
            <?php
			} else { ?>
            	<div class="greyBox-light"><?php echo $v_file; ?></div>
            	<p align="center"><img src="<?php echo $v_file; ?>"/></p>
            <?php
			}
        }
		//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
        ?>
    </center>
    <?php
}
?>
<div style="text-align:center;padding:10px"><input src="<?php html_image('images/btn-thoat.gif')?>" onclick="window.close()" type="image"></div>