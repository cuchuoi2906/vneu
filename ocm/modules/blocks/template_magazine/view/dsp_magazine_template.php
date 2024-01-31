<?php

$v_list_form_title  = "Cập nhật template"; 
html_set_title($v_list_form_title);

$v_template_id      = $v_template['pk_magazine_template'];

$v_extract_resource_uri     = 'ajax/' . $this->className().'/act_extract_html';
$v_upload_template_file_uri = 'ajax/' . $this->className().'/act_upload_magazine_template_files';
$v_remove_template_fileupload_uri = 'ajax/' . $this->className().'/act_remove_magazine_template_fileupload';
$v_save_template_url = 'ajax/' . $this->className().'/act_update_template_magazine';

$v_allow_extensions         = json_encode($v_template_config['allow_extensions']);
$v_allow_mimetype_regex     = str_replace(array('/', '.', '+'), array('\/', '\.', '\+'), implode('|', $v_template_config['allow_mime_types']));
?>
<script type="text/javascript">
    // define global configuration object for magazine
    var magCnf = {};
    magCnf.extractHtmlUrl = "<?php html_link($v_extract_resource_uri); ?>";
    magCnf.maxFileSize = 3000000;
    magCnf.allowedMimeTypeRegex = '(<?php echo $v_allow_mimetype_regex;?>)';
    magCnf.extFilter = <?php echo $v_allow_extensions;?>;
    magCnf.uploadUrl = "<?php html_link($v_upload_template_file_uri); ?>";
    magCnf.removeTemplateFileUploadUrl = "<?php html_link($v_remove_template_fileupload_uri); ?>";
    magCnf.saveTemplateUrl = "<?php html_link($v_save_template_url); ?>";
</script>
<link rel="stylesheet" type="text/css" href="<?php html_link('css/magazine.css'); ?>?v=17092018">
<script type="text/javascript" src="<?php html_link('js/magazine.js'); ?>?v=17092018"></script>

<div class="widget">
	<div class="widget_title">
        <span class="iconsweet">r</span>
        <h5><?php echo $v_list_form_title ; ?></h5>
    </div>
    <div class="widget_body quiz" style="padding: 10px;">	
        <div id="err_common" class="redText"></div>

		<form action='<?php html_link('ajax/' . $this->className().'/act_update_template_magazine/' . $v_template_id); ?>' method='post' id='frm_dsp_single_item' class="frm frm-stacked" enctype="multipart/form-data">
			<input type="hidden" name="template_id" id="template_id" value="<?php echo $v_template_id; ?>" />
            <input type="hidden" name="c_thumbnail" id="c_thumbnail" value="<?php echo $v_template['c_thumbnail']; ?>" />
            <input type="hidden" name="goback" value="<?php echo $goback; ?>">
            <div class="wrapper">
                <div class="row">
                    <div class="col-2-5">
                        <div class="control-group">
                            <label for="c_name">Tên template <span class="redText">(*)</span></label>
                            <input id="c_name" name="c_name" type="text" value="<?php echo $v_template['c_name'];?>" class="fluid">
                            <!-- <span class="pure-form-message-inline">This is a required field.</span> -->
                        </div>
                        <div class="row">
                            <div class="col-1-2">
                                <div class="control-group">
                                    <label for="c_name">Trọng số</label>
                                    <input type="text" name="c_position" id="c_position" value="<?php echo $v_template['c_position'];?>" class="fluid">
                                </div>
                            </div>
                            <div class="col-1-2">
                                <div class="control-group">
                                    <label for="c_name">Trạng thái xuất bản</label>
                                    <?php if($this->getPerm('admin,publish')) { ?>
                                        <select name="c_status" id="c_status" class="fluid">
                                            <?php if($v_view_type == 'create') { ?>
                                                <option value="1" selected="selected">Đã xuất bản</option>
                                                <option value="0">Chưa xuất bản</option>
                                            <?php } else { ?>
                                                <option value="1" <?php echo intval($v_template['c_status']) ? 'selected="selected"' : '';?> >Đã xuất bản</option>

                                                <option value="0" <?php echo !intval($v_template['c_status']) ? 'selected="selected"' : '';?> >Chưa xuất bản</option>
                                            <?php } ?>
                                        </select>
                                    <?php } else { ?>
                                        <p style="line-height: 32px;margin: 0"><?php echo intval($v_template['c_status']) ? 'Đã xuất bản' : 'Chưa xuất bản';?></p>
                                        <input type="hidden" name="c_status" value="<?php echo intval($v_template['c_status']); ?>">
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-1-1">
                                <div class="control-group">
                                    <label for="c_description">Ghi chú</label>
                                    <textarea id="c_description" name="c_description" class="fluid" rows="3"><?php echo $v_template['c_description'];?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-3-5">
                        <div class="control-group">
                            <label>Upload template <span class="redText">(*)</span></label>
                        </div>
                        <div class="panel p-lr-6">
                            <div class="head">
                                <div id="uploadZone" class="dm-uploader">
                                  <div class="btn btn-primary">
                                      <span class="btn-title">Chọn file</span>
                                      <input type="file" title='Click to add Files' accept="<?php echo '.' . implode(',.',$v_template_config['allow_extensions']) . ',' . implode(',',$v_template_config['allow_mime_types']);?>"/>
                                  </div>
                                </div>
                                <div class="controls">
                                    <span class="btn btn-primary" id="btnStartUpload" style="margin-right:10px">Upload</span>
                                    <span class="btn pull-right btn-danger" id="btnRemoveAllFileUpload" data-href="<?php html_link('ajax/' . $this->className().'/act_remove_all_magazine_template_fileupload');?>">Xóa tất cả</span>

                                </div>
                            </div>
                            <div class="body ovl-hidden">
                                <ul class="list ovl-y" id="listUploadFiles" style="max-height:110px">
                                    <?php foreach ($v_template_fileupload as  $file) {
                                            $v_file_id = $file['pk_magazine_template_fileupload'];
                                        ?>
                                        <li class="item file-uploaded" id="fileUpload<?php echo $v_file_id;?>" data-fileupload-id="<?php echo $v_file_id;?>">
                                            <div class="file-item">
                                                <strong><a href="<?php echo $file['c_url'];?>" target="_blank"><?php echo $file['c_type'] == 'image' ? $file['c_name'] :$file['c_original_name'];?></a></strong> <small><?php echo ' - ' . $file['c_created_at'];?></small>
                                                <a href="javascript:void(0)" class="close">Xóa</a>
                                            </div>
                                            <input type="hidden" name="arr_fileupload[]" value="<?php echo $v_file_id; ?>" />
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                            <div class="m-tb-10">
                                <small class="block m-tb-10" style="color:#5aa101">*** Cho phép tải lên file thuộc các định dạng: <strong style="color:#5aa101"><?php echo implode(', ', $v_template_config['allow_extensions']); ?></strong></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2-5">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-1-1">
                        <div class="control-group">
                            <label>Nội dung template dạng html <span class="redText">(*)</span></label>
                            <script type="text/javascript" src="<?php echo html_js('editor/ckeditor-4.10.0/ckeditor.js?ver=20170329',false);?>"></script>
                            <textarea id="c_html" name="c_html" autocomplete="off"><?php  echo $v_template['c_original_html'];?></textarea>
                            <script>
                                var editor_external_files = <?php echo json_encode(mzt_get_template_css_js_files($v_template_map)); ?>;
                            </script>
                            <script>
                                    CKEDITOR.replace( 'c_html', {
                                        toolbar: 'Basic',
                                        autoParagraph: false,
                                        // fullPage: true,
                                        // extraPlugins: 'docprops',
                                        // Disable content filtering because if you use full page mode, you probably
                                        // want to  freely enter any HTML content in source mode without any limitations.
                                        allowedContent: true,
                                        height: 500
                                    } );
                                    CKEDITOR.config.toolbar_Basic = [
                                        [ 'Source' ],
                                        [ 'Cut', 'Copy', 'Paste', 'PasteText' ],
                                        [ 'Bold', 'Italic', 'Underline', '-', 'RemoveFormat' ],
                                        [ 'Link', 'Unlink' ],
                                        [ 'TextColor', 'BGColor' ],
                                        [ 'Image', 'Table' ],
                                        [ 'FontSize' ],
                                        [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ],
                                        [ 'Maximize' ]
                                    ];
                                    // avoid edit and remove <html>, <head> and <body> tag
                                    CKEDITOR.config.protectedSource.push( /^[\s\S]*<body>\s*/i );
                                    CKEDITOR.config.protectedSource.push( /\s*<\/body>[\s\S]*$/i );
                                    // avoid edit inline javascript
                                    CKEDITOR.config.protectedSource.push( /<script(?:(?!\/\/)(?!\/\*)[^'"]|"(?:\\.|[^"\\])*"|'(?:\\.|[^'\\])*'*'|\/\/.*(?:\n)|\/\*(?:(?:.|\s))*?\*\/)*?<\/script>/gim );
                                    // include css files of magazine template to the head of editor content iframe
                                    CKEDITOR.config.contentsCss = editor_external_files.css;
                            </script>
                        </div>
                    </div>
                    <div class="col-1-1">
                            <label class="p-lr-6">Thống kê các thành phần có thể thay đổi</label>
                            <style type="text/css">
                                .cntwrap img, .cntwrap video, .cntwrap audio, .cntwrap iframe {
                                    max-width: 100% !important;
                                }
                                .expl * {
                                    font-size: 12px !important;
                                }
                                .resp-container {
                                    position: relative;
                                    overflow: hidden;
                                    padding-top: 56.25%;
                                }
                                .resp-container img, .resp-container video, .resp-container iframe {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    width: 100%;
                                    height: 100%;
                                    border: 0;
                                }
                                .bdr {border-bottom: 1px solid #ddd;}
                            </style>
                            <table width="100%" cellpadding="1" cellspacing="1" border="0">
                                <colgroup>
                                    <col width="3%">
                                    <col width="15%">
                                    <col width="42%">
                                    <col width="40%">
                                </colgroup>
                                <tbody>
                                    <tr>
                                        <td class="rowTitle">#</td>                     
                                        <td class="rowTitle">Preview</td>                  
                                        <td class="rowTitle">Nội dung</td>      
                                        <td class="rowTitle">Quy tắc</td>
                                    </tr>
                                    <?php 
                                        $stt = 1;
                                        if (!empty($v_template_map['defined'])) {
                                            foreach ($v_template_map['defined'] as $element_type => $arr_element) {
                                                if (empty($arr_element)) continue;
                                                foreach ($arr_element as $element_index => $element_data) {      
                                                    ?>
                                                    <tr class="expl">
                                                        <td class="bdr"><?php echo $stt; ?></td>                     
                                                        <td class="bdr">
                                                            <div style="width: 200px;overflow: hidden;" class="cntwrap">
                                                                <?php 
                                                                    if (in_array($element_data['type'], ['image','video','iframe'])) {
                                                                        echo '<div class="resp-container">' . $element_data['html_preview'] . '</div>';
                                                                    } elseif ($element_data['type'] == 'audio') {
                                                                        echo $element_data['html_preview'];
                                                                    }
                                                                ?>
                                                                <strong style="text-align: center;display: block;padding:5px 0">
                                                                    <?php echo mzt_get_html_element_label($element_data['type'], $element_data['stt'] + 1); ?>
                                                                </strong>
                                                            </div>
                                                        </td>                  
                                                        <td style="padding: 10px 20px" class="bdr">
                                                            <?php if (!empty($element_data['arr_data'])) {
                                                                foreach ($element_data['arr_data'] as $attribute => $data) { 
                                                                    if (empty($data)) continue; 
                                                                ?>
                                                                <p>
                                                                    <strong style="color: green"><?php echo $data['attr']; ?>:</strong>
                                                                    <span>
                                                                        <?php 
                                                                        if (empty($data['data'])) {
                                                                            echo "[không có]";
                                                                        } else {
                                                                            if ($data['data_type'] == 'file' || $data['data_type'] == 'url') {
                                                                                echo '<a href="' .$data['data']. '" target="_blank">'.$data['data'].'</a>';
                                                                            } else {
                                                                                echo $data['data']; 
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </span>
                                                                </p>
                                                            <?php
                                                                } // endforeach
                                                            } ?>
                                                        </td>      
                                                        <td class="bdr">
                                                            <?php if (!empty($element_data['arr_data'])) {
                                                                foreach ($element_data['arr_data'] as $attribute => $data) { 
                                                            ?>
                                                                <p>
                                                                    <?php if (!empty($data['metadata'])) { ?>
                                                                        <strong style="color: green"><?php echo $data['attr']; ?>:</strong>
                                                                    <?php
                                                                        foreach ($data['metadata'] as $name => $meta) {
                                                                            if (empty($meta['value'])) continue;
                                                                    ?>
                                                                        <span><?php echo mzt_get_metadata_explanation_format($name, $meta['operator']); ?></span>
                                                                        <strong><?php echo $meta['value'] .', '; ?></strong>
                                                                    <?php
                                                                        } // endforeach
                                                                    } ?>
                                                                </p>
                                                            <?php
                                                                } // endforeach
                                                            } ?>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                                        $stt++;
                                                } //endforeach
                                            } //endforeach
                                        }
                                    ?>
                                </tbody>
                            </table>
                        <!-- </div> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-1-1 p-lr-6">
                        <div class="line-dot m-tb-10"></div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-1-5 center-block">
                        <span class="btn btn-primary" id="btnSubmitTemplate" style="margin-right:10px">Cập nhật</span>
                        <?php if($v_view_type == 'update') { ?>
                            <a href="javascript:void(0)" class="btn btn-primary" id="btnPreviewTemplate" style="margin-right:10px" onclick="window.open('<?php html_link('ajax/' . $this->className().'/act_preview_template_magazine/' . $v_template_id); ?>', 'new_window', 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')">Xem trước</a>
                        <?php } ?>
                        <a class="btn btn-primary" id="btnGoBack" href="<?php echo fw24h_base64_url_decode($goback); ?>" style="margin-right:10px">Thoát</a>
                    </div>
                </div>
            </div>
		</form>
	</div>
</div>