<div class="widget">
	<div class="widget_title"><h5>Đổi mật khẩu người dùng</h5></div>
	<form action="<?php html_link($this->className().'/act_change_pass')?>" method="post" name="frm_dsp_single_item" target="frm_submit">
		<input type="hidden" name="hdn_old_password" value="<?php echo $row_user['C_PASSWORD']?>" />
		<input type="hidden" name="goback" value="<?php echo $v_goback; ?>" />
	<table class="form_table1" align="center" border="0">
		<col width="145px"><col width="">
		<tbody><tr>
			<td height="5" colspan="2">
			<div style="padding:5px 10px 10px 80px;">
		<b>Yêu cầu nhập mật khẩu bao gồm:</b><br/>
			- Bắt đầu là chữ in thường<br/>
			- Có tối thiểu 1 chữ IN HOA<br/>
			- Có tối thiểu 1 số<br/>
			- Không có ký tự space<br/>
			- Độ dài tối thiểu 6 ký tự <br/>
			</td>
			</div>
		</tr>
		<tr>
			<td class="normal_label">Tên người dùng <small class="redText">*</small></td>
			<td><input name="txt_name" class="normal_textbox" readonly="true" size="30" value="<?php echo $row_user['C_NAME']?>" type="text"></td>
		</tr>
		<tr>
			<td class="normal_label">Tên đǎng nhập <small class="redText">*</small></td>
			<td><input name="txt_username" class="normal_textbox" readonly="true" size="30" value="<?php echo $row_user['C_USERNAME']?>" type="text"></td>
		</tr>
		<tr>
			<td class="normal_label">Mật khẩu đang dùng <small class="redText">*</small></td>
			<td><input name="txt_old_password" class="normal_textbox" size="30" value=""  type="password"></td>
		</tr>
		<tr>
			<td class="normal_label">Mật khẩu mới <small class="redText">*</small></td>
			<td><input name="txt_password" class="normal_textbox" size="30" value="" type="password"></td>
		</tr>
		<tr>
			<td class="normal_label">Xác định lại <small class="redText">*</small></td>
			<td><input name="txt_re_password" class="normal_textbox" size="30" value="" type="password"></td>
		</tr>	
		<tr>
			<td class="normal_label">Author Snippet</td>
			<td><input name="txt_author_snippet" class="normal_textbox" size="100" value="<?php echo $row_user['c_author_snippet']?>" type="text"></td>
		</tr>	
		<tr><td height="5">&nbsp;</td></tr>
		<tr>
			<td colspan = 2>
				<input class="normal_button" value="Xác nhận" type="submit">&nbsp;&nbsp;
				<input onclick="top.history.back()" class="normal_button" value="Huỷ" type="button">
			</td>
		</tr>
		<tr>
			<td colspan = 2><div class="line-dot"></div></td>
		</tr>
		<tr>
			<td colspan = 2>
				<div style ="padding-left:10px; padding-bottom:10px;">
					<p>Ghi chú, để tạo Author snippet trong OCM các BTV phải tiến hành các bước chi tiết như sau: </p>
					<p><b>o	Bước 01:</b> Đăng nhập google plus. <a href="#">http://plus.google.com </a></p>
					<p><b>o	Bước 02:</b> Gắn nhãn avatar của google plus là hình chân dung của mình</p>
					<p><b>o	Bước 03:</b> lấy đường dẫn  từ google plus sau khi đăng nhập và nhập vào ô Text box “Author snippet” bên trên trong OCM thường có dạng (ví dụ: <a href ="#">https://plus.google.com/113269326385430056170</a>). Ghi chú: Khi click truy cập vào đường dẫn tới vừa nhập sẽ ra trang hồ sơ cá nhân của mình trên google plus</p>
					<p><b>o	Bước 04:</b> Truy cập vào đường dẫn sau: <a href="#">http://plus.google.com/me/about/edit/co</a></p>
					<p><b>o	Bước 05:</b> Tìm chữ Contributor to trong phần label điền nhẫn hiệu website (ví dụ 24h điền 24h, eva điền eva), phần website ghi như sau:</p>
						&nbsp;&nbsp;&nbsp;+Website 24h: <a href ="http://www.24h.com.vn">www.24h.com.vn</a></p>
						&nbsp;&nbsp;&nbsp;+Website eva: <a href = "http://eva.vn">http://eva.vn</a></p>
					<p><b>o	Bước 06:</b> Gửi mail to: <a href="#">dm@24h.com.vn</a> thông báo đã hoàn thành việc nhập profile cho 24h và 1 đường dẫn bài viết mà BTV tự viết </p>
				</div>
			</td>
		</tr>
	</tbody>
	</table>
	</form>
	<iframe name="frm_submit" class="iframe-form"></iframe>
</div>