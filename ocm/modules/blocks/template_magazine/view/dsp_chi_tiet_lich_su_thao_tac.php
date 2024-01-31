<?php

$v_list_form_title  = "Lịch sử sửa đổi chi tiết"; 
html_set_title($v_list_form_title);
?>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_URL . 'css/magazine.css'; ?>">
<div class="widget">
	<div class="widget_title">
        <h5><?php echo $v_list_form_title; ?></h5>
    </div>
    <div class="widget_body quiz" style="padding: 10px;">	
        <div id="err_common" class="redText"></div>

		<form method='post' id='frm_dsp_single_item' class="frm frm-stacked">
            <div class="wrapper">
                <div class="row">
                    <div class="col-1-1">
                        <div class="control-group">
                            <p><strong>Tên template:</strong> <?php echo $v_template['c_name'];?></p>
                            <p><strong>Thao tác:</strong> <?php echo $v_template['c_action_type'] > 0 ? 'Thay đổi trạng thái Xuất bản' : 'Chỉnh sửa';?></p>
                            <p><strong>Người thao tác:</strong> <?php echo $v_template['Username'];?>   -  <strong>Thời gian thao tác:</strong> <?php echo $v_template['c_created_at'];?></p>
                            <hr>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3-5">
                        <div class="control-group">
                            <label for="c_name">Tên template</label>
                            <input id="c_name" name="c_name" type="text" value="<?php echo $v_template['c_name'];?>" class="fluid" disabled>
                        </div>
                        <div class="row">
                            <div class="col-1-2">
                                <div class="control-group">
                                    <label for="c_name">Trọng số</label>
                                    <input type="text" name="c_position" id="c_position" value="<?php echo $v_template['c_position'];?>" class="fluid" disabled>
                                </div>
                            </div>
                            <div class="col-1-2">
                                <div class="control-group">
                                    <label for="c_name">Trạng thái xuất bản</label>
                                    <select name="c_status" id="c_status" class="fluid" disabled style="background: #fff;color: #333;">
                                        <option value="1" <?php echo intval($v_template['c_status']) ? 'selected="selected"' : '';?> >Đã xuất bản</option>
                                        <option value="0" <?php echo !intval($v_template['c_status']) ? 'selected="selected"' : '';?> >Chưa xuất bản</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-2-5">
                        <div class="control-group">
                            <label for="c_description">Ghi chú</label>
                            <textarea id="c_description" name="c_description" class="fluid" rows="5" disabled><?php echo $v_template['c_description'];?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-3-5">
                        <div class="control-group">
                            <label>Upload template</label>
                        </div>
                        <div class="panel p-lr-6">
                            <div class="body ovl-hidden">
                                <ul class="list ovl-y" id="listUploadFiles" style="max-height:110px">
                                    <?php foreach ($v_template_fileupload as  $file) { 
                                            $v_file_id = $file['pk_magazine_template_fileupload'];
                                        ?>
                                        <li class="item file-uploaded">
                                            <div class="file-item">
                                                <a href="javascript:void(0)"><?php echo $file['c_name'] . ' - ' . $file['c_created_at'];?></a>
                                            </div>
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
                    <div class="col-1-1">
                        <div class="control-group">
                            <label>Nội dung html của template</label>
                            <?php echo htmlLoadEditor('c_html',$v_template['c_original_html'],'100%', 400, 'Basic'); ?>
                            <script type="text/javascript">
                                // avoid edit and remove <html>, <head> and <body> tag
                                CKEDITOR.config.protectedSource.push( /^[\s\S]*<body>\s*/i );
                                CKEDITOR.config.protectedSource.push( /\s*<\/body>[\s\S]*$/i );
                                // avoid edit inline javascript
                                CKEDITOR.config.protectedSource.push( /<script((?:(?!src=).)*?)>(.*?)<\/script>/simg );
                            </script>
                        </div>
                    </div>
                    <div class="col-5-5 p-lr-6">
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
                                    if (!empty($v_template_map_defined)) {
                                        foreach ($v_template_map_defined as $element_type => $arr_element) {
                                            if (empty($arr_element)) continue;
                                            foreach ($arr_element as $element_index => $element_data) {
                                                // if (empty($element_data['arr_data'])) continue;
                                                // foreach ($element_data['arr_data'] as $attribute => $data) { 
                                    
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
                                                // } //endforeach
                                            } //endforeach
                                        } //endforeach
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1-1">
                        <a class="btn btn-primary" href="<?php echo fw24h_base64_url_decode($_GET['goback'])?>" id="btnGoBack" style="margin-right:10px;display: table;margin: 10px auto;">Thoát</a>
                    </div>
                </div>
            </div>
		</form>
	</div>
</div>