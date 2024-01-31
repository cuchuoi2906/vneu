<div id="div_dsp_all_item">
    <div class="contentTitle">LỊCH SỬ SỬA ĐỔI MENU NGANG</div>
    <form name="frm_history_data" method="GET"  target="frm_submit">
    <input type = "hidden" name ="goback" value = "<?php echo $_REQUEST['goback']?>"/>    
    <div>
        Hiển thị <input class="textbox" type="text" style="width:20px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="document.frm_history_data.submit()"> menu/trang &nbsp;&nbsp;&nbsp;
        <?php
        // phan trang co su dung ajax
        echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');      
        ?>
    </div>
    <?php
    if(count($rs_data)>0){
        $i = ($page-1)*$number_per_page;        
        $v_count = count($rs_data) -1;
        for($j= $v_count; $j>=0; $j--) {       
            $rs_single_menu = json_decode($rs_data[$j]['du_lieu'], true);
            $v_nguoi_tao =  $rs_data[$j]['nguoi_tao'];
            $v_ngay_tao = date_format(date_create($rs_data[$j]['ngay_tao']), 'd/m/Y H:i:s');       
            $i++
    ?>
            <div class="line-dot"></div>
                <table border="0" width="100%" cellpadding="5" cellspacing="0">
                    <tr>
                        <td width="130" class="tbLabel" align ="left"><b>Lần sửa: <?php echo $i;?></b></td>
                        <td align ="right">
                            <a class="button_big btn_grey" href="javascript:frm_submit(document.frm_history_data,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Quay lại</a>&nbsp;                  
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Người sửa: </td>
                        <td>                        
                            <?php echo $v_nguoi_tao ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <span>Thời gian sửa:<?php echo $v_ngay_tao;?></span>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Tên menu ngang  <span class="redText">(*)</span></td>
                        <td>
                           <?php echo $rs_single_menu['name']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Tiêu đề hiển thị trên web <span class="redText">(*)</span></td>
                        <td>
                           <?php echo $rs_single_menu['c_title']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="tbLabel">Link</td>
                        <td>
                            <?php echo $rs_single_menu['url']; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">icon</td>
                        <td>
                            <img src="<?php echo '/'.str_replace('/upload','upload',$rs_single_menu['icon']);?>"/>        
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Trọng số</td>
                        <td>
                            <?php echo $rs_single_menu['ordering']?>        
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Chuyên mục</td>
                        <td>
                            <?php echo $rs_single_menu['cat_published_list']?>            
                        </td>
                    </tr>
                    <tr>
                        <td width="130" class="tbLabel">Trạng thái xuất bản</td>
                        <td>
                            <?php echo html_select_box('sel_publish', $v_arr_trang_thai, 'c_code', 'c_name', $rs_single_menu['published'], $extend=' disabled', $add_option = 0); ?>
                        </td>
                    </tr>
                </table>       
            <?php
        }
    } else {?>    
        <p>Chưa có trong dữ liệu lịch sử</p>
        <a class="button_big btn_grey" href="javascript:frm_submit(document.frm_history_data,'<?php echo fw24h_base64_url_decode($_GET['goback'])?>','')">Quay lại</a>
    <?php
    }
    ?> 
    <div class="line-dot"></div>
    <div>
        Hiển thị <input class="textbox" type="text" style="width:20px" name="number_per_page" id="number_per_page" value="<?php echo $number_per_page; ?>" onchange="document.frm_history_data.submit()"> menu/trang &nbsp;&nbsp;&nbsp;
        <?php
        // phan trang co su dung ajax
        echo _page_nav($phan_trang, $this->_getCurrentUri(false), 'div_dsp_all_item');      
        ?>
    </div>    
    <iframe name="frm_submit" class="iframe-form"></iframe>
    </form>
</div>