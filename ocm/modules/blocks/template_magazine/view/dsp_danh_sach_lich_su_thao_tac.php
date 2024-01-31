<?php
// Khởi tạo biến + xử lý các giá trị ở đây
$v_current_style_name = "rowContent-w";
$v_count = $v_record_count;
?>
<div id="div_dsp_all_item">
	<div class="header-popup">Lịch sửa đổi template magazine</div>
	<div class="popupContent">
		<div class="greyBox-light" style="height: 26px;">
			Bài viết: <b><?php echo $v_template['c_name'];?></b>
			<div style="float: right;">
				<a class="button_big btn_grey" href="<?php echo fw24h_base64_url_decode($goback); ?>">Thoát</a>				
			</div>
		</div>
		<div class="greyBox">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right">
                        <?php
                        // phan trang co su dung ajax
                        echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');
                        // phan trang ko su dung ajax
                        // echo _page_nav($phan_trang, $this->_getCurrentUri(false));
                        ?>
                    </td>
				</tr>
			</table>
		</div>
		<table width="100%" cellpadding="1" cellspacing="1" border="0" class="listing">
			<tr>
				<td class="rowTitle">Lần sửa</td>
				<td class="rowTitle">Loại thay đổi</td>
				<td class="rowTitle">Người sửa</td>
				<td class="rowTitle">Thời gian sửa</td>
				<td class="rowTitle">Chi tiết sửa</td>
			</tr>
			<?php	
			// Hien thi danh sach
			$v_count_down = $v_total_items - ($page-1)*$number_per_page;
			for ($i=0; $i< $v_count; $i++) {
				$v_id = $v_arr_items[$i]["pk_magazine_template_history"];
				$v_ngay_tao = $v_arr_items[$i]["c_created_at"];	
				$v_ngay_tao = date_format(date_create($v_ngay_tao), 'd-m-Y H:i:s');
				$v_nguoi_tao = $v_arr_items[$i]["Username"];	
				$v_loai_thay_doi = $v_arr_items[$i]["c_action_type"];
				
				$v_current_style_name = ($v_current_style_name=='rowContent-w'?'rowContent-g':'rowContent-w');
				$v_href_item = html_link($this->className() . "/dsp_single_magazine_template_history/$v_id?goback=".$goback, false);
				?>
				<tr class="<?php echo $v_current_style_name ?>">
					<td class="rowContent-w" align="center">Lần <?php echo $v_count_down?></td>
					<td class="rowContent-w"><?php echo $v_loai_thay_doi > 0 ? 'Thay đổi trạng thái xuất bản' : 'Chỉnh sửa'; ?></td>
					<td class="rowContent-w"><?php echo $v_nguoi_tao?></td>
					<td class="rowContent-w"><?php echo $v_ngay_tao?></td>
					<td class="rowContent-w"><a href="<?php echo $v_href_item?>" class="blueText">Xem chi tiết</a></td>
				</tr>
				<?php
				$v_count_down--;
			} // for
			?>
		</table>
		<?php
		html_no_data($v_count);
		?>
		<div class="greyBox">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td align="right">
                        <?php
                        // phan trang co su dung ajax
                        echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');
                        // phan trang ko su dung ajax
                        // echo _page_nav($phan_trang, $this->_getCurrentUri(false));
                        ?>
                    </td>
				</tr>
			</table>
		</div>
	</div>
</div> 