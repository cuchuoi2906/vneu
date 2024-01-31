<div class="colRt" id="right">
	<aside> <?php
		if (!is_array($_RIGHT_CONTENT)) {
			$_RIGHT_CONTENT = array();
		}
		$v_kieu_content_truoc = '';
		foreach ($_RIGHT_CONTENT as $v_right_content) {
			if ($v_kieu_content_truoc == 'banner' || ($v_kieu_content_truoc != '' && $v_kieu_content_truoc != 'banner' && preg_match('#^banner:#', $v_right_content))) {
				echo '<div class="clear padB2"></div>';
			}
			if (preg_match('#^function:#', $v_right_content)) {
				$v_kieu_content_truoc = 'function';
				$v_right_content = str_replace('function:', '', $v_right_content);
				eval( $v_right_content.';');
			} else if (preg_match('#^object:#', $v_right_content)) {
				$v_kieu_content_truoc = 'object';
				$v_right_content = str_replace('object:', '', $v_right_content);
				$v_right_content = explode('/', $v_right_content);
				$class = $v_right_content[0].'_block';
				$obj_temp = new $class();
				$v_methods ='$obj_temp->'.$v_right_content[1].';';
				eval ($v_methods);
			} else if (preg_match('#^key_value:#', $v_right_content)) {
				$v_kieu_content_truoc = 'key_value';
				$v_right_content = str_replace('key_value:', '', $v_right_content);
				$v_right_content = explode('/', $v_right_content);
				echo Gnud_Db_read_get_key($v_right_content[0],$v_right_content[1]);
			} else if (preg_match('#^string:#', $v_right_content)) {
				$v_right_content = str_replace('string:', '', $v_right_content);
				echo $v_right_content;
			} else if (preg_match('#^banner:#', $v_right_content)) {
				$v_kieu_content_truoc = 'banner';
				$v_right_content = str_replace('banner:', '', $v_right_content);			
				$v_text_ad_zone = '';
				$v_text_quang_cao =0;
				if($v_hien_thi_quang_cao != 1 && $v_hien_thi_text_ad_zone_quang_cao != 1){
					$v_text_ad_zone = html_text_phan_biet_quang_cao(2);
					$v_text_quang_cao =1;
				}
				$v_class_div  = ($v_right_content=='ADS_7')?'':' class="bnrLR"';
				echo _hien_thi_quang_cao_tren_trang($v_right_content,$v_text_quang_cao,'',$v_class_div);
				?>
				<div class="clF"></div>
				<?php
			}
		}	?>	
		<div id="subRight">
			<?php echo _hien_thi_quang_cao_tren_trang('ADS_197_15s',0,'','txtBnrHor'); ?>
			<div class="clF pdB10"></div>
		</div>
	<aside>
</div>	
<div class="clF"></div>