<script type="text/javascript">var fileNumber = 0;</script>
<div id="upload_result"></div>
<form name="frmUpload" method="post" action="<?php html_link($this->className().'/act_upload_video_for_banner/'); ?>" enctype="multipart/form-data" class="padBot" target="frm_submit">
     <table border="0" cellspacing="0" cellpadding="3">               
        <tr>
            <td valign="top" width="300" colspan=2>
                <div id="uploadContent">
                    Chọn file video:<input type="file" name="file_video[]" style="width:215px" />
                </div>               
                <div style="padding-top:10px">
                    <input type="submit" value="Upload file" />
                    <input type="button" value="Đóng cửa sổ" onclick="window.close()" />
                </div>
            </td>         
        </tr>
    </table>
</form>
<div id="upload_result_2"></div>
<table border="1" cellpadding="5" cellspacing="0" width="470" style="border-collapse:collapse;background-color:#ccffcc">
    <caption style="font-weight:bold;color:red;height:30px;">QUY ĐỊNH VỀ DUNG LƯỢNG 1 FILE VIDEO ĐƯỢC PHÉP UPLOAD:</caption>
    <tr>
        <td>- Số lượng file video được tối đa 1 lần: <span class="redText" style="font-weight:bold">1 video</span></td>
    </tr>   
    <tr>
        <td>- Dung lượng tối đa: <span class="redText" style="font-weight:bold"><?php echo $upload_video_conf['max_video_size_for_banner']/(1024*1024);?> MB</span></td>
    </tr>   
    <tr>
        <td>- Định dạng file cho phép upload: <span class="redText" style="font-weight:bold"><?php echo strtoupper($upload_video_conf['extension_video_for_banner']);?></span></td>
    </tr>   
</table>
<iframe name="frm_submit" class="iframe-form"></iframe>