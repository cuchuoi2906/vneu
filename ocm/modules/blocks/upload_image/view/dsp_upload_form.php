<script type="text/javascript">switch_page_by_ie('<?php html_link($this->className().'/index/ie'); ?>');</script>
<form name="frmUpload" action="<?php html_link($this->className().'/act_upload/'); ?>" method="post" enctype="multipart/form-data" style="padding:10px 0 20px 0">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td class="tbLabel" width="60"><b>Nguồn ảnh</b></td>
            <td width="220"><input type="file" name="file_image[]" multiple="multiple" onchange="displayFileUploadInfo(this.files, 'filesInfo'); document.getElementById('btn_upload').style.display =  ''" /></td>
            <td><input type="image" id="btn_upload" src="<?php html_image('images/btn-upload-anh.gif')?>" style="display:none" /></td>
        </tr>    
        <tr>
        	<td></td>
            <td id="filesInfo" colspan="2" valign="bottom"></td>
        </tr>
    </table>
    <input type="hidden" name="news_title" value="<?php echo $_REQUEST['news_title']; ?>" />
    <?php //Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif ?>
    <input type="hidden" name="image_type" value="<?php echo $_REQUEST['image_type']; ?>" />
    <?php //End 07-04-2016 : Thangnb toi_uu_upload_anh_gif ?>
</form>
<?php
echo $this->dsp_regulation_table();