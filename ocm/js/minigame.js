/*
* hàm thực hiện xóa 1 row theo ID table 
* Author: TuyenNT<tuyennt@24h.com.vn>
* @date: 2-8-2019
* @param : p_table_id ID table cần thao tác
* @param : p_class_row class row cần thao tác
* @param : p_url_get_html URL thực chứa HTML 1 rows
* @param : p_max_row_add Tổng số rows được thêm
* @param : hdn_count_id ID hidden đếm
 */
function minigame_add_more_row_to_table(p_table_id,p_class_row,p_url_get_html, p_max_row_add, hdn_count_id){
    if (p_table_id != '' && p_class_row != '' && p_url_get_html != '') {
        p_max_row_add = parseInt(p_max_row_add);
        if (p_max_row_add > 0) {
            obj = document.getElementsByClassName(p_class_row);
            var obj_length = obj.length;
            if (obj_length >= p_max_row_add) {
                alert('Bạn chỉ được phép thêm tối đa '+p_max_row_add+' dòng!');
                return;
            }
        }
        var v_max_stt = 0;
        for (i=0;i<obj_length;i++) {
            v_stt = obj[i].getAttribute('itemid');
            if (v_stt > v_max_stt) {
                v_max_stt = v_stt;
            }
        }
        obj_count = $('#'+hdn_count_id);
        var tmp_count = parseInt(obj_count.val()) +1;
        var v_url_get_html = p_url_get_html+'&v_stt='+tmp_count+'&v_add_them=1';
        console.log(v_url_get_html);
        $.get(v_url_get_html, function(data) {
            $('#'+p_table_id).append(data);
        });
        obj_count.val(parseInt(tmp_count));
    }
}

/*
* hàm thực hiện xóa 1 row theo ID table của thông tin câu trả lời
* @author: tuyenNT<tuyennt@24h.com.vn>
* @date: 05-08-2019
* @param : p_table_id ID table cần thao tác
* @param : p_tr_id ID row cần thao tác
* @param : hdn_count_id ID hidden đếm
 */
function minigame_delete_row_by_id(p_table_id, p_tr_id, hdn_count_id, hdn_id_dap_an){
    // Thực hiện xóa dữ liệu khi onclick
    if(document.getElementById(hdn_id_dap_an)){
        if(!confirm('Bạn có chắc chắn xóa đáp án không?')){
            return;
        }
    }
    if(document.getElementById('hdn_id_dap_an_xoa')){
        var id_dap_an = document.getElementById('hdn_id_dap_an_xoa').value;
        document.getElementById('hdn_id_dap_an_xoa').value = document.getElementById(hdn_id_dap_an).value +','+id_dap_an;
    }
    
    
    
    // ẩn dòng trên giao diện
    if(document.getElementById(p_table_id) && document.getElementById(p_tr_id) && document.getElementById(hdn_count_id)){
        var d     = document;
        var table = document.getElementById(p_table_id);
        var tbody = table.getElementsByTagName('tbody')[0];
        var tr    = d.getElementById(p_tr_id);
        tbody.removeChild(tr);
        obj_count = $('#'+hdn_count_id);
        var tmp_count = parseInt(obj_count.val()) - 1;
        obj_count.val(parseInt(tmp_count));
    }
}


/*
* hàm thực hiện xóa 1 row theo ID table 
* Author: TuyenNT<tuyennt@24h.com.vn>
* @date: 2-8-2019
* @param : p_table_id ID table cần thao tác
* @param : p_class_row class row cần thao tác
* @param : p_url_get_html URL thực chứa HTML 1 rows
* @param : p_max_row_add Tổng số rows được thêm
* @param : hdn_count_id ID hidden đếm
 */
function minigame_add_more_div(p_table_id,p_class_row,p_url_get_html, p_max_row_add, hdn_count_id){
    if (p_table_id != '' && p_class_row != '' && p_url_get_html != '') {
        p_max_row_add = parseInt(p_max_row_add);
        if (p_max_row_add > 0) {
            obj = document.getElementsByClassName(p_class_row);
            var obj_length = obj.length;
            if (obj_length >= p_max_row_add) {
                alert('Bạn chỉ được phép thêm tối đa '+p_max_row_add+' dòng!');
                return;
            }
        }
        var v_max_stt = 0;
        for (i=0;i<obj_length;i++) {
            v_stt = obj[i].getAttribute('itemid');
            if (v_stt > v_max_stt) {
                v_max_stt = v_stt;
            }
        }
        obj_count = $('#'+hdn_count_id);
        var tmp_count = parseInt(obj_count.val()) +1;
        var so_cau = document.getElementById('hdn_count_cau_hoi').value;
        var v_url_get_html = p_url_get_html+'v_cau_hoi='+so_cau+'&v_add_them=1';
        //console.log(v_url_get_html);
        $.get(v_url_get_html, function(data) {
            $('#'+p_table_id).append(data);
        });
            obj_count.val(parseInt(tmp_count));
    }
}


/*
* hàm thực hiện xóa 1 row theo ID table của thông tin câu trả lời
* @author: tuyenNT<tuyennt@24h.com.vn>
* @date: 05-08-2019
* @param : p_table_id ID table cần thao tác
* @param : p_tr_id ID row cần thao tác
* @param : hdn_count_id ID hidden đếm
 */
function minigame_delete_div_by_id(p_div_id, p_div_class, hdn_count_id, p_id_cau_hoi){
    // Thực hiện xóa dữ liệu khi onclick
    if(!confirm('Bạn có chắc chắn xóa câu hỏi không?')){
        return;
    }
    
    if(p_id_cau_hoi > 0){
        //v_url = CONFIG.BASE_URL +'ajax/minigame_game/act_delete_cau_hoi_theo_id/?p_id_cau_hoi='+p_id_cau_hoi;
        //frm_submit(frm_update_data, v_url, 'frm_submit');
    }
    
    if(document.getElementById('hdn_id_cau_hoi_xoa')){
        var id_cau_hoi = document.getElementById('hdn_id_cau_hoi_xoa').value;
        document.getElementById('hdn_id_cau_hoi_xoa').value = p_id_cau_hoi +','+id_cau_hoi;
    }
        
    $( "div" ).remove('.'+p_div_class);
    obj_count = $('#'+hdn_count_id);
    var tmp_count = parseInt(obj_count.val()) - 1;
    obj_count.val(parseInt(tmp_count));
}
/*
* Hàm thực hiện load câu trả lời khi chọn game template
 */
function htmlLoadAnswerWhenGameTemplateChose(gameTemplateId){
    if(gameTemplateId >0){
        v_url = v_url_modul_ajax+'/dsp_html_answer_by_gametem_id/'+gameTemplateId;
        AjaxAction('danh_sach_cau_hoi',v_url);
    }
}