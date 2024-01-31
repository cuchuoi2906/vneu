// ham btn_save_onclick() duoc goi khi NSD nhan chuot vao nut "Chap nhan"
//  - p_fuseaction: ten fuseaction tiep theo
function btn_save_onclick(p_frm, p_fuseaction) {
	if (_MODAL_DIALOG_MODE==1)
		p_frm.action = "index.php?modal_dialog_mode=1";
	else
		p_frm.action = "index.php";

	if (verify(p_frm)){
		p_frm.fuseaction.value = p_fuseaction;
		p_frm.submit();
	}
}
// Ham check all cac checkbox tren man hinh danh sach
function check_all(p_frm, chk_object){
	var v_is_checked = chk_object.checked;
	var v_record_count = p_frm.hdn_record_count.value*1;
	for(var i = 0; i < v_record_count; i++){
		var p_check_obj = eval("p_frm.chk_item_id"+i);
		if(p_check_obj){
			p_check_obj.checked = v_is_checked;
		}
	}
}

function check_all_by_class(p_class_name, p_status, p_callback)
{
    $('.'+p_class_name).attr('checked', p_status);
    if (p_callback!=null && p_callback!='') {
        p_callback();
    }
}
function check_checkbox_by_id(p_checkbox_id, p_status)
{
    document.getElementById(p_checkbox_id).checked = p_status;
}

function check_checkbox_by_input(p_checkbox_id, p_input_id)
{
    v_input_value = document.getElementById(p_input_id).value;
    check_checkbox_by_id(p_checkbox_id+v_input_value, true);
    window.location.hash = p_checkbox_id+v_input_value;
    // Xử lý thêm nút chọn chuyên mục chính
    if(typeof v_select_main_cate !== 'undefined' && v_select_main_cate >0){
        create_html_main_cate_by_sub_cate(v_input_value);
    }
}

function list_check_has_checked(p_forms, p_item_id) {
	var v_record_count = p_forms.hdn_record_count.value*1;
	for(var i = 0; i < v_record_count; i++){
		var p_check_obj = eval("p_forms."+p_item_id+i);
		if(p_check_obj && p_check_obj.checked == true){
			return true;
		}
	}
}
// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
/**
 * Hàm kiểm tra trang hiện tại có phải dạng tin tức không
 * @returns {Boolean}
 */
function list_in_object_news() {
    var uri = window.location.pathname;
    var regex = /(drafting_news|deleted_news|pending_approval_news|pending_publication_news|published_news|xuat_ban_them_google_news)/;
    return regex.test(uri);
}
/**
 * Hàm bỏ tích chọn màn hình danh sách
 * @param {type} p_forms
 * @param {type} p_item_id
 * @returns {Boolean}
 */
function list_uncheck_all(p_forms, p_item_id) {
    var v_record_count = p_forms.hdn_record_count.value*1;
    for(var i = 0; i < v_record_count; i++){
		var p_check_obj = eval("p_forms."+p_item_id+i);
		p_check_obj.checked = false;
	}
    return false;
}

/**
 * Kiểm tra và hiển thị thông báo khi thực hiện thao tác
 * @param {type} p_forms
 * @param {type} p_action_url
 * @param {type} p_target
 * @param {type} p_item_id
 * @param {type} message
 * @param {type} success
 * @param {type} failed
 * @returns {Boolean}
 */
function list_check_has_pr_checked(p_forms, p_action_url, p_target, p_item_id, message, success, failed, p_show_dialog) {
	var v_record_count = 0;
    if (typeof p_forms.hdn_record_count != 'undefined') {
        var v_record_count = p_forms.hdn_record_count.value*1;
        var v_ids = {};
        for(var i = 0; i < v_record_count; i++){
            var p_check_obj = eval("p_forms."+p_item_id+i);
            if(p_check_obj && p_check_obj.checked == true){
                v_ids[i] = p_forms[''+p_item_id+i].value;
            }
        }
        if ($.isEmptyObject(v_ids)) {
            alert('Chưa có đối tượng nào được chọn!');
            return false;
        }
    }
    var result = false;
    var text = '<p><b>'+message+'</b></p>';
    if (typeof p_show_dialog == 'undefined') {
        p_show_dialog = true;
    }
    if (list_in_object_news()) {
        var temp = check_list_bai_pr(v_ids);
        if (temp != '') {
            text += temp;
            p_show_dialog = true;
        }
    }
    if (p_show_dialog) {
        show_dialog_confirm(text, success, failed);
    } else {
        return success();
    }    
    return result;
}

/**
 * Kiểm tra mảng bài PR
 * @param {type} v_ids
 * @returns {String}
 */
function check_list_bai_pr(v_ids) {
    // Lấy danh sách ID bài PR trong số item select
    var v_url = CONFIG.BASE_URL + 'ajax/news/act_check_news_is_pr';
    var text = '';
    $.ajax({
        type: "POST",
        url: v_url,
        data: {p_arr_ids: v_ids},
        async: false,
        success: function (data) {
            if (data != '') {
                data = JSON.parse(data);
                if (!$.isEmptyObject(data)) {
                    text += '<p><b style="color:#d00;font-size: 18px;">Trong số bài được chọn có chứa bài PR, bạn có chắc muốn thực hiện thao tác này?</b></p>';
                    for (var i in data) {
                        // Nếu là bài PR
                        text += '<p> - ID: ' + data[i] + '</p>';
                    }
                }
            }
        }
    });
    return text;
}

/**
 * Hiển thị dialog confirm
 * @param {type} text HTML hiển thị thông báo
 * @param {type} success Hàm xử lý khi nhấn OK
 * @param {type} failed Hàm xử lý khi nhấn Cancel
 * @returns {undefined}
 */
function show_dialog_confirm(text, success, failed) {
    if (text != '') {
        if ($('#dialog_confirm').length == 0) {
            $('<div id="dialog_confirm" style="display:none;" title="Xác nhận thao tác"><div id="confirm_text">'+text+'</div></div>').appendTo('body');
        } else {
            $('#confirm_text').html(text);
        }
        $("#dialog_confirm").dialog({
            resizable: false,
            height: "auto",
            width: 400,
            modal: true,
            buttons: {
                "OK": function () {
                    $(this).dialog("close");
                    return success();
                },
                "Cancel": function () {
                    $(this).dialog("close");
                    return failed();
                }
            }
        });
    }
}

/**
 * Hàm kiểm tra và hiển thị popup thao tác bài PR trong trang chi tiết
 * @param {type} p_action_url
 * @param {type} p_message
 * @param {type} success
 * @param {type} failed
 * @returns {void}
 */

function single_check_bai_pr(p_forms, p_message, success, failed, p_id) {
    var v_id = p_id;
    if (typeof p_id == 'undefined') {
        v_id = get_news_id_in_forms(p_forms);
    }
    if (v_id <= 0) {
        return failed();
    }
    var v_ids = [v_id];
    var text = '';
    if (p_message != '') {
        text = '<p>' + p_message + '</p>';
    }
    var v_url = CONFIG.BASE_URL + 'ajax/news/act_check_news_is_pr';
    $.ajax({
        type: "POST",
        url: v_url,
        data: {p_arr_ids: v_ids},
        async: false,
        success: function (data) {
            if (data != '') {
                data = JSON.parse(data);
                if (!$.isEmptyObject(data)) {
                    text += '<p><b style="color:#d00;font-size: 18px;">Bạn đang thao tác với bài PR, bạn có chắc muốn thực hiện thao tác này?</b></p>';
                }
            }
        }
    });
    /*Begin 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
    if (text!='') {
		show_dialog_confirm(text, success, failed);
    } else {
        return success();
    }  
    /*End 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
}

/**
 * Hàm lọc thông tin id tin tức trên uri
 * @param {type} uri
 * @returns v_id
 */
function get_news_id_in_forms(p_forms) {
    if (p_forms.news_id) {
        var p_obj_id = p_forms.news_id;
        var v_id = p_obj_id.value;
        return v_id;
    } else {
        return 0;
    }
}
// End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR

function btn_delete_onclick(p_forms, p_action_url, p_target, p_class_button){
	v_class_button = (typeof(p_class_button)==='undefined')? '':p_class_button;
	v_is_ok = false;
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn có chắc chắn muốn xóa các đối tượng đã chọn không?', function () {
        frm_submit(p_forms, p_action_url, p_target);
        v_is_ok = true;
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
	if (!v_is_ok && v_class_button != '') {
		set_enable_link(v_class_button);
	}
}

function btn_remove_onclick(p_forms, p_action_url, p_target, have_item_id) {
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Đối tượng này sẽ bị xóa hẳn không thể khôi phục được?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_newscategory_status_by_list_onclick(p_forms, p_action_url, p_target){
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn THAY ĐỔI VỊ TRÍ XUẤT BẢN các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_news_back_drafting_onclick(p_forms, p_action_url, p_target){
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn GỬI BIÊN TẬP LẠI các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_news_to_pending_approval_onclick(p_forms, p_action_url, p_target) {
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn GỬI DUYỆT các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_news_back_pending_approval_onclick(p_forms, p_action_url, p_target) {
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn GỬI DUYỆT LẠI các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_news_to_pending_publication_onclick(p_forms, p_action_url, p_target) {
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn GỬI XUẤT BẢN các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function btn_update_news_back_pending_publication_onclick(p_forms, p_action_url, p_id, p_target) {
    single_check_bai_pr(p_forms, 'Bạn muốn HẠ XUẤT BẢN bài viết?', function () {
		frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        return false;
    }, p_id);
}

function btn_update_news_to_published_from_pending_publication_onclick(p_forms, p_action_url, p_target, p_pending_date, p_show_loading) {
    if(confirm("Bài này đã hẹn giờ xuất bản lúc "+p_pending_date+"\nNếu bạn xuất bản thì thông tin hẹn giờ sẽ không có tác dụng.\nBạn có chắc chắn xuất bản bài viết này không?")){
		frm_submit(p_forms, p_action_url, p_target, p_show_loading);
    }else{
        return false;
    }
}

function btn_update_list_onclick (p_forms, p_action_url, p_target) {
	frm_submit (p_forms, p_action_url, p_target);
}

function delete_news_publication_time (p_forms, p_action_url, p_id, p_target, p_forward) {
    if (typeof p_forward == 'undefined') {
        single_check_bai_pr(p_forms, 'Bạn chắc chắn muốn hủy hẹn giờ xuất bản bài viết này?', function () {
            frm_submit(p_forms, p_action_url, p_target);
        }, function () {
            return false;
        }, p_id);
    } else {
        frm_submit(p_forms, p_action_url, p_target);
    }
}

function btn_cancel_publication_time_onclick (p_forms, p_action_url, p_target) {
    single_check_bai_pr(p_forms, 'Bạn thực sự muốn HUỶ hẹn giờ xuất bản?', function () {
		frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        return false;
    });
}

// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
function btn_show_frm_publication_time_onclick(p_forms, p_action_url, p_id, p_target) {
    /*Begin 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
    single_check_bai_pr(p_forms, '', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {}, p_id);
    /*End 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
}

function btn_show_frm_public_onclick(p_forms, p_action_url, p_id, p_target) {
    /*Begin 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
    single_check_bai_pr(p_forms, '', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {}, p_id);
    /*End 29-01-2017 trungcq XLCYCMHENG_29976_toi_uu_canh_bao_thao_tac_bai_pr*/
}

function btn_public_onclick(p_forms, p_action_url, p_target) {
	single_check_bai_pr(p_forms, '', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {});
}
// End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR

function btn_preview_onclick(p_forms, p_action_url, p_target)
{
    arr_txt_input = ['txt_summary_image_tip'];
    for (i in arr_txt_input) {
        p_forms[arr_txt_input[i]].value = (p_forms[arr_txt_input[i]].value!=p_forms[arr_txt_input[i]].getAttribute('title')) ? p_forms[arr_txt_input[i]].value : '';
    }
    window.open(p_action_url, p_target, 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')
    frm_submit (p_forms, p_action_url, p_target);
}

/*Begin 08-10-2018 trungcq XLCYCMHENG_32888_canh_bao_chuyen_muc_giai_dau*/
function verify_save_news(p_forms, v_id, p_message, success, failed, p_hien_thi_canh_bao_giai_dau, p_show_dialog)

{
    if (p_forms.txt_title.value == '') {
		alert('Bạn chưa nhập tiêu đề bài viết!');
		p_forms.txt_title.focus();
		return false;
	}
	if (p_forms.txt_summary.value == '') {
		alert('Bạn chưa nhập tóm tắt dài!');
		p_forms.txt_summary.focus();
		return false;
	}
	/* Thangnb video_kieu_moi_doi_tac_21_05_2015 */
	if (!(p_forms.txt_video_code) || p_forms.txt_video_code.value == '') {
		if (window.CKEDITOR.instances.txt_body) {
			if (window.CKEDITOR.instances.txt_body.getData() == '') {
				alert('Bạn chưa nhập nội dung!');
				return false;
			}
		}
	}
	var v_arr_temp = new Array();
	var objSelectList = window.document.getElementById('sel_category_list');
    for (var i=0; i<objSelectList.options.length; i++) {
		v_arr_temp[v_arr_temp.length] = objSelectList.options[i].value;
    }
	var v_category_list = v_arr_temp.join(',');
	if (v_category_list == '') {
		alert('Bạn chưa chọn chuyên mục xuất bản!');
		p_forms.sel_category_list.focus();		
		return false;
	}/* 
	if (!document.getElementById("ifr_qlnb")) {
		alert('Bạn chưa nhập thông tin nhuận bút!');
		return false;
	} */
    // kiểm tra thẻ space liền và thẻ br
    if(document.getElementById('txt_body')){
        var v_body = window.CKEDITOR.instances.txt_body.getData();
        if (v_body != '') {
            var d = new Date();
            var n = d.getTime();
            var v_url = CONFIG.BASE_URL + 'ajax/news/check_space_body_news/?' + n;
            p_check_body = false;
            var text_body = '';
            $.ajax({
                type: "POST",
                url: v_url,
                data: {v_body: v_body},
                async: false,
                success: function (data) {
                    if (data != '') {
                        text_body += data;
                        p_check_body = true;
                    }
                }
            });
            if (p_check_body) {
                if(confirm(text_body)){
                   
                }else{
                    return false;
                }
            }
        }
    }
    /* Begin: 18-05-2019 TuyenNT toi_uu_bat_buoc_check_loi_chinh_ta */
    // Kiểm tra có cho phép auto check chính tả khi lưu bài
    if(document.getElementById('check_chinh_ta_auto')){
        if(document.getElementById('txt_body')){
            var v_body = window.CKEDITOR.instances.txt_body.getData();
        }else{
            var v_body = '';
        }
        var txt_title = p_forms.txt_title.value;
        var txt_summary = p_forms.txt_summary.value;
        if (v_body != '' || txt_title != '' || txt_summary != '') {
            var d = new Date();
            var n = d.getTime();
            var v_url = CONFIG.BASE_URL + 'ajax/news/dsp_check_chinhta/1?' + n;
            p_check_body = false;
            var text_show = '';
            $.ajax({
                type: "POST",
                url: v_url,
                data: {v_body: v_body, v_title: txt_title, txt_summary: txt_summary},
                async: false,
                success: function (data) {
                    if (data != '') {
                        var v_check_body = data.indexOf("##");
                        // kiểm tra nếu có lỗi body thì mới thực hiện check
                        if(parseInt(v_check_body) > 0){
                            var v_arr_text = data.split("?");
                            if(Array.isArray(v_arr_text)){
                                data = v_arr_text[0]+'?';
                                var text_error = v_arr_text[1];
                                // thực hiện cắt count và text
                                var v_arr_error = text_error.split("##");
                                var count_error = v_arr_error[0];
                                var text_error_body = v_arr_error[1];
                                var v_arr_error_body = text_error_body.split("<**>");
                                if(Array.isArray(v_arr_error_body)){
                                    // xử lý thẻ a
                                    reg_tag_a = new RegExp(/<a\b[^>]*>([\s\S]*?)<\/a>/g);
                                    var v_matches_tag_a = v_body.match(reg_tag_a);
                                    if(Array.isArray(v_matches_tag_a)){
                                        for(n=0;n<v_matches_tag_a.length;n++){
                                            v_body = v_body.replace(v_matches_tag_a[n], '<!--ta_a_replace_'+n+'-->');
                                        }
                                    }
                                    // xử lý video
                                    reg_script_video = new RegExp(/<script\b[^>]*>([\s\S]*?)<\/script>/g);
                                    var v_matches_script_video = v_body.match(reg_script_video);
                                    if(Array.isArray(v_matches_script_video)){
                                        for(l=0;l<v_matches_script_video.length;l++){
                                            v_body = v_body.replace(v_matches_script_video[l], '<!--script_replace_'+l+'-->');
                                        }
                                    }
                                    reg_img = new RegExp(/<img.*?src="(.*?)"[^\>]+>/g);
                                    var v_matches_image = v_body.match(reg_img);
                                    if(Array.isArray(v_matches_image)){
                                        for(j=0;j<v_matches_image.length;j++){
                                            v_body = v_body.replace(v_matches_image[j], '<!--img_replace_'+j+'-->');
                                        }
                                    }
                                    for(i=0;i<count_error;i++){
                                        var body_text = '';
                                        var body_text_replace = '';
                                        body_text = v_arr_error_body[i];
                                        body_text_replace = '<!--ma_replace_error--><span class="text_error">' + body_text + '</span><!--ma_replace_error-->';
                                        v_body = v_body.replace(new RegExp("("+body_text+")", "g"), body_text_replace);
                                    }
                                    if(Array.isArray(v_matches_image)){
                                        for(k=0;k<v_matches_image.length;k++){
                                            v_body = v_body.replace('<!--img_replace_'+k+'-->', v_matches_image[k]);
                                        }
                                    }
                                    if(Array.isArray(v_matches_script_video)){
                                        for(m=0;m<v_matches_script_video.length;m++){
                                            v_body = v_body.replace('<!--script_replace_'+m+'-->', v_matches_script_video[m]);
                                        }
                                    }
                                    if(Array.isArray(v_matches_tag_a)){
                                        for(o=0;o<v_matches_tag_a.length;o++){
                                            v_body = v_body.replace('<!--ta_a_replace_'+o+'-->', v_matches_tag_a[o]);
                                        }
                                    }
                                    // inner lại vào text body
                                    CKEDITOR.instances.txt_body.setData(v_body);
                                }
                            }
                        }
                        text_show += data;
                    }
                }
            });
            // nếu có báo lỗi
            if (text_show !== '') {
                // kiểm tra xem có cho phép show yes/no
                if(document.getElementById('check_chinh_ta_show_yes_no')){
                    if(confirm(text_show)){

                    }else{
                        return false;
                    }
                }else{
                    alert(text_show);
                    return false;
                }
            }
        }
    }
    /* End: 18-05-2019 TuyenNT toi_uu_bat_buoc_check_loi_chinh_ta */
    //Begin 13/4/2020 AnhTT check_n_textlink_trong_bai_viet
    // kiểm tra box text link 
    if(document.getElementById('txt_body')){
        var v_body = window.CKEDITOR.instances.txt_body.getData();
        if (v_body != '') {
            var d = new Date();
            var n = d.getTime();
            var v_url = CONFIG.BASE_URL + 'ajax/news/check_box_textlink/?' + n;
            p_check_body = false;
            var text_body = '';
            $.ajax({
                type: "POST",
                url: v_url,
                data: {v_body: v_body},
                async: false,
                success: function (data) {
                    if (data != '') {
                        text_body += data;
                        p_check_body = true;
                    }
                }
            });
            if (p_check_body) {
                if(confirm(text_body)){
                   
                }else{
                    return false;
                }
            }
        }
    }
    //End 13/4/2020 AnhTT check_n_textlink_trong_bai_viet
    
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    // Thực hiển kiểm tra cảnh báo loại bài đẩy erp
    if(document.getElementById('news_type_push_erp') && document.getElementById('news_type_push_erp').style.display != 'none'){
        if(p_forms.news_type_push_erp.value == ''){
            var r = confirm("Loại bài đẩy sang ERP chưa được chọn!");
            if (r == false) {
                return false;
            }
        } 
    }
    var v_ids = {v_id};
    var text = '<p>' + p_message + '</p>';
    var v_url = CONFIG.BASE_URL + 'ajax/news/act_check_news_is_pr';
    if (typeof p_show_dialog == 'undefined') {
        p_show_dialog = false;
    }
    if (typeof p_hien_thi_canh_bao_giai_dau == 'undefined') {
        p_hien_thi_canh_bao_giai_dau = false;
    }
    $.ajax({
        type: "POST",
        url: v_url,
        data: {p_arr_ids: v_ids},
        async: false,
        success: function (data) {
            if (data != '') {
                data = JSON.parse(data);
                if (!$.isEmptyObject(data)) {
                    text += '<p><b style="color:#d00;font-size: 18px;">Bạn đang thao tác với bài PR, bạn có chắc muốn thực hiện thao tác này?</b></p>';
                    p_show_dialog = true;
                }
            }
        }
    });
    // Kiểm tra chọn chuyên mục bóng đá ngoại hạng anh nhưng chưa tích giải đấu ==> thông báo lỗi
    if(kiem_tra_tich_chon_giai_dau_theo_chuyen_muc(v_str_id_chuyen_muc_kiem_tra_chon_loai_giai_dau) && p_hien_thi_canh_bao_giai_dau){
        p_show_dialog = true;
        text += '<p><b style="color:#d00;font-size: 18px;">Bạn chưa chọn LOẠI GIẢI ĐẤU!</b></p>';
    }
    if (p_show_dialog) {
        show_dialog_confirm(text, success, failed);
    } 
    else {
        return success();
    }
    
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    return true;
}
/*End 08-10-2018 trungcq XLCYCMHENG_32888_canh_bao_chuyen_muc_giai_dau*/

function btn_save_news_onclick(p_forms, p_action_url, p_target)
{
    arr_txt_input = ['txt_summary_image_tip'];
    for (i in arr_txt_input) {
        p_forms[arr_txt_input[i]].value = (p_forms[arr_txt_input[i]].value!=p_forms[arr_txt_input[i]].getAttribute('title')) ? p_forms[arr_txt_input[i]].value : '';
    }
    frm_submit (p_forms, p_action_url, p_target, true);
}

function btn_change_published_date_onclick(p_forms, p_action_url, p_id, p_target) {
    single_check_bai_pr(p_forms, '', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {}, p_id);
}


function rollback_data(p_forms, p_action_url, p_target) {
    // Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn KHÔI PHỤC các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}

function frm_submit(p_forms, p_action_url, p_target, p_show_loading) {
    p_show_loading = false;
    if (p_show_loading) {
        if ($('#loading_overlay').length == 0) {
            $('<div id="loading_overlay" class="loading-overlay" title="Thao tác đang được thực hiện"><div class="inner"><img src="' + CONFIG.BASE_URL_FRONT_END + 'images/2014/ajax_loader_blue_256.gif" alt="" /><p>Thao tác đang được thực hiện</p></div></div>').appendTo('body');
        } else {
            $('#loading_overlay').fadeIn();
        }
    }
    // End TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan
	if (p_action_url) {
		p_forms.action = p_action_url;
	} else {
		p_forms.action = "";
	}		
	if (p_target) {
		p_forms.target = p_target;
	} else {
		p_forms.target = "";	
	}   
	p_forms.submit();	
    // Begin TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan
    if (p_show_loading) {
        setTimeout(function () {
            $('#loading_overlay').fadeOut();
            clearTimeout();
        }, 60000);
    }
    // End TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan	
}

// Valid number
function isnum(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}

// Valid double
function isdouble(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	// if there are more character ".", it is invalid double
	if (count_char(passedVal,'.')>1)
		return false;
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)!="." && passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)!="." && passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}
// Valid float
function isfloat(passedVal)
{
	if (passedVal == "")
	{
		return false;
	}
	// if there are more character ".", it is invalid float
	if (count_char(passedVal,'.')>1)
		return false;
	// if there are more character "-", it is invalid float
	if (count_char(passedVal,'-')>1)
		return false;
	if (passedVal.indexOf('-')>0)
		return false;
		passedVal=passedVal.substring(1);
	for (i=0; i<passedVal.length; i++)
	{
		if(passedVal.charAt(i)!="." && passedVal.charAt(i)< "0")
		{
			return false;
		}
		if (passedVal.charAt(i)!="." && passedVal.charAt(i)> "9")
		{
			return false;
		}
	}
	return true;
}

//Checking email;

function isemail(email)
{
 	var invalidChars ="/ :,;";

	if (email=="")
	{
		return false;
	}

	for (i=0; i<invalidChars.length;i++)
	{
		badChar = invalidChars.charAt(i);
		if(email.indexOf(badChar,0)>-1)
		{
			return false;
		}
	}
	atPos =email.indexOf("@",1)
	if(atPos==-1){
		return false;
	}
	if (email.indexOf("@",atPos+1)>-1){
		return false;
	}
	periodPos = email.indexOf(".",atPos);
	if (periodPos==-1){
		return false;
	}
	if(periodPos+3 > email.length){
		return false;
	}
	return true;
}

// Check date
function isdate(the_date) {
	var strDatestyle = "EU";  //European date style
	var strDate;
	var strDateArray;
	var strDay;
	var strMonth;
	var strYear;
	var intday;
	var intMonth;
	var intYear;
	var booFound = false;
	var strSeparatorArray = new Array("-"," ","/",".");
	var intElementNr;
	var err = 0;
	var strMonthArray = new Array(12);

	strMonthArray[0] = "Jan";
	strMonthArray[1] = "Feb";
	strMonthArray[2] = "Mar";
	strMonthArray[3] = "Apr";
	strMonthArray[4] = "May";
	strMonthArray[5] = "Jun";
	strMonthArray[6] = "Jul";
	strMonthArray[7] = "Aug";
	strMonthArray[8] = "Sep";
	strMonthArray[9] = "Oct";
	strMonthArray[10] = "Nov";
	strMonthArray[11] = "Dec";

	strDate = the_date;

	if (strDate == "") {
		return false;
	}
	for (intElementNr = 0; intElementNr < strSeparatorArray.length; intElementNr++) {
		if (strDate.indexOf(strSeparatorArray[intElementNr]) != -1) {
			strDateArray = strDate.split(strSeparatorArray[intElementNr]);
			if (strDateArray.length != 3) {
				err = 1;
				return false;
			} else {
				strDay = strDateArray[0];
				strMonth = strDateArray[1];
				strYear = strDateArray[2];
			}
			booFound = true;
	   }
	}
	if (booFound == false) {
		if (strDate.length>5) {
			strDay = strDate.substr(0, 2);
			strMonth = strDate.substr(2, 2);
			strYear = strDate.substr(4);
		} else {
			return false;
		}
	}
	if (strYear.length == 2) {
		strYear = '20' + strYear;
	}
	// US style
	if (strDatestyle == "US") {
		strTemp = strDay;
		strDay = strMonth;
		strMonth = strTemp;
	}

	if (!isnum(strDay)) {
		err = 2;
		return false;
	}

	intday = parseInt(strDay, 10);
	if (isNaN(intday)) {
		err = 2;
		return false;
	}

	if (!isnum(strMonth)) {
		err = 3;
		return false;
	}
	intMonth = parseInt(strMonth, 10);
	if (isNaN(intMonth)) {
		for (i = 0;i<12;i++) {
			if (strMonth.toUpperCase() == strMonthArray[i].toUpperCase()) {
				intMonth = i+1;
				strMonth = strMonthArray[i];
				i = 12;
		   }
		}
		if (isNaN(intMonth)) {
			err = 3;
			return false;
	   }
	}

	if (!isnum(strYear)) {
		err = 4;
		return false;
	}

	intYear = parseInt(strYear, 10);
	if (isNaN(intYear)) {
		err = 4;
		return false;
	}
	if (intMonth>12 || intMonth<1) {
		err = 5;
		return false;
	}
	if ((intMonth == 1 || intMonth == 3 || intMonth == 5 || intMonth == 7 || intMonth == 8 || intMonth == 10 || intMonth == 12) && (intday > 31 || intday < 1)) {
		err = 6;
		return false;
	}
	if ((intMonth == 4 || intMonth == 6 || intMonth == 9 || intMonth == 11) && (intday > 30 || intday < 1)) {
		err = 7;
		return false;
	}
	if (intMonth == 2) {
		if (intday < 1) {
			err = 8;
			return false;
		}
		if (LeapYear(intYear) == true) {
			if (intday > 29) {
				err = 9;
				return false;
			}
		} else {
			if (intday > 28) {
				err = 10;
				return false;
			}
		}
	}
	return true;
}

// return true if a string contains only white characters
function isblank(s)
{
	var i;
	for (i=0;i<s.length;i++)
	{
		var c=s.charAt(i);
		if ((c!=" ") && (c!="\n") && (c!="\t")) return false;
	}
	return true;
}
function verify(f)
{
	var errors = "";
	var i;
	for (i=0;i<f.length;i++)
	{
		var e=f.elements[i];		
		if (e.getAttribute("type") =="radio" &&  !e.getAttribute("optional"))
			{
				if (ischecked(f,e.name)==false)
				{
					if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
					else alert("At least one "+e.name+" must be checked ");
					e.focus();
					return false;
				}
			}	
		// If it is hour object
		if ((e.getAttribute("ishour")) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{ 
			if (IsHour(e,':')==false)
			{
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert("Hour is invalid");
				e.focus();
				return false;
			}	
		}
		// If it is email object
		if ((e.getAttribute("isemail")) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (isemail(e.value)==false)
			{
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert("Email is invalid");
				e.focus();
				return false;
			}	
		}

		// if it is Date object
		if ((e.getAttribute("isdate")) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{
			if (isdate(e.value)==false)
			{
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert("Date is invalid");
				e.focus();
				return false;
			}	
		}
		// if it is number object
		if ((e.getAttribute("isnumeric") || e.getAttribute("isdouble") || (e.getAttribute("min")!=null) || (e.getAttribute("max")!=null)) && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{		
			if (!_DECIMAL_DELIMITOR) decimal_delimitor = ",";else decimal_delimitor = _DECIMAL_DELIMITOR;
			test_value = replace(e.value,decimal_delimitor,"");
			if (e.getAttribute("isdouble"))
				is_number = isdouble(test_value);
			else
				is_number = isnum(test_value);
					
			var v = parseFloat(test_value);
			if (!is_number 
				|| ((e.getAttribute("min")!=null) && (v<e.getAttribute("min")))
				|| ((e.getAttribute("max")!=null) && (v>e.getAttribute("max"))))
			{
				errors += "- The field "+ e.name + " must be a number";
				if (e.getAttribute("min")!=null)
					errors += " that is greater than "+e.getAttribute("min");
				if (e.getAttribute("min")!=null && e.getAttribute("max")!=null)
					errors += " and less than "+e.getAttribute("max");
				else if (e.getAttribute("max") !=null)	
					errors += " That is less than "+e.getAttribute("max");
				errors += ".\n";
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert(errors);
				e.focus();
				return false;
			}	
		}

		// check maxlength
		if ((e.getAttribute("maxlength")!=null && e.getAttribute("maxlength")!="") && !((e.value==null) || (e.value=="") || isblank(e.value)))
		{		
			if (e.value.length>e.getAttribute("maxlength"))
			{
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert("The length of "+e.name+" must be less than "+e.getAttribute("maxlength"));
				e.focus();
				return false;
			}
		}

		// check multiple selectbox must be not empty
		if (e.getAttribute("checkempty") && e.getAttribute("type")=="select-multiple" && e.length==0)
		{
			if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
			else alert(e.name+" must be not empty");
			e.focus();
			return false;
		}
		
		// Check for text, textarea
		if (((e.getAttribute("type") == "password") || (e.getAttribute("type") =="text") || (e.getAttribute("type")=="textarea") || (e.getAttribute("type") =="select-one")) && e.getAttribute("optional") && e.getAttribute("optional")=="false")
		{					
			if ((e.value==null) || (e.value=="") || isblank(e.value))
			{
				if (e.getAttribute("message")!=null) alert(e.getAttribute("message"));
				else alert(e.name+" must be not empty");
				if (e.getAttribute("type")!="select-one"){
					e.focus();
				}
				return false;
			}
		}		
	}
	
	return true;
}

//	Ham to_upper_case bien chu thuong thanh chu hoa
//	Khi goi : onchange="JavaScript:ToUpperKey(this)"
 function to_upper_case(p_obj)
 {
	p_obj.value = p_obj.value.toUpperCase();
 }
//	Ham to_lower_case bien chu hoa thanh chu thuong
//	Khi goi : onchange="JavaScript:ToLowerKey(this)"
 function to_lower_case(p_obj)
 {
	p_obj.value = p_obj.value.toLowerCase();
 }

function isurl(p_url){
	if (p_url.indexOf("..") >= 0)
		return false;
		
	if (p_url.indexOf(" ") != -1)
		return false;
	else if (p_url.indexOf("http://") == -1)
		return false;
	else if (p_url == "http://")
		return false;
	else if (p_url.indexOf("http://") > 0)
		return false;
	
	p_url = p_url.substring(7, p_url.length);
	if (p_url.indexOf(".") == -1)
		return false;
	else if (p_url.indexOf(".") == 0)
		return false;
	else if (p_url.charAt(p_url.length - 1) == ".")
		return false;
	
	if (p_url.indexOf("/") != -1) {
		p_url = p_url.substring(0, p_url.indexOf("/"));
		if (p_url.charAt(p_url.length - 1) == ".")
		  return false;
	}
	
	if (p_url.indexOf(":") != -1) {
		if (p_url.indexOf(":") == (p_url.length - 1))
		  return false;
		else if (p_url.charAt(p_url.indexOf(":") + 1) == ".")
		  return false;
		p_url = p_url.substring(0, p_url.indexOf(":"));
		if (p_url.charAt(p_url.length - 1) == ".")
		  return false;
	}
 
    return true;
}

// Ham tu dong check vao cac o check box neu trang thai cua dong duoc thay doi
function chk_status_index_onclick(p_object){
	var v_index = replace(p_object.name,"chk_status_index","")*1;
	var p_obj_chk = eval("document.frm_dsp_all_item.chk_item_id"+v_index);
	if(p_obj_chk){
		p_obj_chk.checked = true;
	}
}

function txt_order_index_onchange(p_object){
	var v_index = replace(p_object.name,"txt_order_index","");
	var p_obj_chk = eval("document.frm_dsp_all_item.chk_item_id"+v_index);
	if(p_obj_chk){
		p_obj_chk.checked = true;
	}
}

function sel_status_index_onchange(p_object){
	var v_index = replace(p_object.name,"sel_newscategory_status","");
	var p_obj_chk = eval("document.frm_dsp_all_item.chk_item_id"+v_index);
	if(p_obj_chk){
		p_obj_chk.checked = true;
	}
}

function replace(string,text,by) {
    return string.replace(text,by);
}

function change_focus(f,o){
	return;
}

function AjaxAction(where, url)
{
	var xmlHttp=new GetXmlHttpObject()
	if(xmlHttp==null){return;}
	var bar='&#272;ang t&#7843;i d&#7919; li&#7879;u';
	document.getElementById(where).innerHTML=bar
	xmlHttp.onreadystatechange=function() {
		if(xmlHttp.readyState==4||xmlHttp.readyState==200) {
			document.getElementById(where).innerHTML=xmlHttp.responseText;
			ocm_chay_javascript_tu_ket_qua_ajax(xmlHttp.responseText);
		}
	}
	xmlHttp.open("GET",url,true);xmlHttp.send(null);
}
function GetXmlHttpObject(){var objXMLHttp=null;if(window.XMLHttpRequest){objXMLHttp=new XMLHttpRequest();}else if(window.ActiveXObject){objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP");}
	return objXMLHttp;}
	

function close_box_popup() {
	if($('#_box_overlay').length){
	$('#_box_overlay').hide();
	}
	if($('#loading_overlay').length){
		$('#loading_overlay').hide();
	}
}

function show_box_popup(v_html,width_box, height_box) {
	if (!width_box) width_box=320;
	if (!height_box) height_box=300;
	
	//Opera Netscape 6 Netscape 4x Mozilla
	if (window.innerWidth || window.innerHeight){
		docwidth = window.innerWidth;
		docheight = window.innerHeight;
	}
	//IE Mozilla
	if (document.body.clientWidth || document.body.clientHeight){
		docwidth = document.body.clientWidth;
		docheight = document.body.clientHeight;
	}
	v_top = (f_clientHeight()-height_box)/2;
	v_left = (docwidth-width_box)/2;
	
	if (document.getElementById('_box_popup')) {
		$("#_box_popup").css({left:v_left,top:v_top,width:width_box,height:height_box});
	} else {
		var v_popup_overlay = '<div class="popup-overlay boxy-modal" id="_box_overlay"><div class="box-popup" id="_box_popup" style="left:'+v_left+'px;top:'+v_top+'px;width:'+width_box+'px;height:'+height_box+'px;overflow:auto"></div></div>';
		$("body").append(v_popup_overlay);
	} 
	$("#_box_popup").html(v_html);
	$('#_box_overlay').show();
    // add key press event
    $("body").keypress(function(e){
        if (e.keyCode == 27) { //Esc keycode
            close_box_popup();
        }
    });
}

function f_clientWidth() {
	return f_filterResults (
		window.innerWidth ? window.innerWidth : 0,
		document.documentElement ? document.documentElement.clientWidth : 0,
		document.body ? document.body.clientWidth : 0
	);
}
function f_clientHeight() {
	return f_filterResults (
		window.innerHeight ? window.innerHeight : 0,
		document.documentElement ? document.documentElement.clientHeight : 0,
		document.body ? document.body.clientHeight : 0
	);
}

function isIE() {
	if (navigator.appName=='Microsoft Internet Explorer') {
		return true;
	}
	return false;
}

function isIE6() {
	if (!window.XMLHttpRequest) {
		return true;
	}
	return false;
}

function f_filterResults( n_win, n_docel, n_body) {
	var n_result = n_win ? n_win : 0;
	if (n_docel && (!n_result || (n_result > n_docel)))
		n_result = n_docel;
	return n_body && (!n_result || (n_result > n_body)) ? n_body : n_result;
}

function openWindow(p_url, p_width, p_height, p_modal)
{
    p_modal = (typeof p_modal == undefined) ? true : p_modal;
    if (isIE() || !p_modal) {
        window.open(p_url, 'new_window', 'width='+p_width+', height='+p_height+',toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
    } else {
        //Begin: Tytv - 09/09/2016 - chinh_ocm_redirect_301 (fix_loi_ko_mo_cua_so_bang_showModalDialog_tren_1_so_trinh_duyet)
        if (!window.showModalDialog) {
            window.showModalDialog = function (arg1, arg2, arg3) {

               var w;
               var h;
               var resizable = "no";
               var scroll = "no";
               var status = "no";

               var mdattrs = arg3.split(";");
               for (i = 0; i < mdattrs.length; i++) {
                  var mdattr = mdattrs[i].split(":");

                  var n = mdattr[0];
                  var v = mdattr[1];
                  if (n) { n = n.trim().toLowerCase(); }
                  if (v) { v = v.trim().toLowerCase(); }

                  if (n == "dialogheight") {
                     h = v.replace("px", "");
                  } else if (n == "dialogwidth") {
                     w = v.replace("px", "");
                  } else if (n == "resizable") {
                     resizable = v;
                  } else if (n == "scroll") {
                     scroll = v;
                  } else if (n == "status") {
                     status = v;
                  }
               }

               var left = window.screenX + (window.outerWidth / 2) - (w / 2);
               var top = window.screenY + (window.outerHeight / 2) - (h / 2);
               var targetWin = window.open(arg1, arg1, 'toolbar=no, location=no, directories=no, status=' + status + ', menubar=no, scrollbars=' + scroll + ', resizable=' + resizable + ', copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
               targetWin.focus();
            };
        }
        //End: Tytv - 09/09/2016 - chinh_ocm_redirect_301 (fix_loi_ko_mo_cua_so_bang_showModalDialog_tren_1_so_trinh_duyet)
        window.showModalDialog(p_url, '', 'dialogWidth:'+p_width+'px;dialogHeight:'+p_height+'px;');
    }
}

function disableEnterKey(e)
{
    var key;

    if(window.event) {
        key = window.event.keyCode; //IE
    } else {
        key = e.which; //firefox
    }

    if (key == 13) {
        return false;
    } else {
        return true;
    }
}

function checkToDisplay(p_check_obj, p_target_id)
{
    objTarget = window.document.getElementById(p_target_id);
    if (p_check_obj.checked) {
        objTarget.style.display = '';
    } else {
        objTarget.style.display = 'none';
    }
}

function hiddenAllElementById(p_obj_prefix, p_length)
{
    for (i=0; i<p_length; i++) {
        v_obj = document.getElementById(p_obj_prefix+i);
        if (v_obj) {
            v_obj.style.display = 'none';
        }
    }
}

function removeBlockById(p_obj_id)
{
    v_obj = document.getElementById(p_obj_id);
    if (v_obj) {
        v_obj.innerHTML = '';
        v_obj.style.display = 'none';
    }
}

function getAllSelectOption(p_obj_id, p_display_all)
{
    objSelectList = window.document.getElementById(p_obj_id);
    obj_data = p_display_all ? new Object() : new Array();
    for (var i=0; i<objSelectList.options.length; i++) {
        if (p_display_all==true) {
            obj_data[objSelectList.options[i].value] = encodeURIComponent(objSelectList.options[i].text.replace(/"/g,''));
        } else {
            obj_data[obj_data.length] = encodeURIComponent(objSelectList.options[i].value.replace(/"/g,''));
        }
    }
    str_data = JSON.stringify(obj_data);
    return str_data;
}

function resetSelectList(p_select_list_id) {
	var objSelectList = window.document.getElementById(p_select_list_id);
	if (!objSelectList) {
		return false;
	}
	objSelectList.length=0;
	
	var change_data = window.document.getElementById('change_data');
	if (change_data) {
		change_data.value = 1;
	}
}

function putElementToSelectList(elementText, elementValue, p_select_list_id)
{
	var objSelectList = window.document.getElementById(p_select_list_id);
	if (!objSelectList) {
		return false;
	}
	var len = objSelectList.length;
	//objSelectList.options[len] = new Option(elementText,elementValue,false,false);
	objSelectList.options[len] = new Option(elementText,elementValue,false,true);
	objSelectList.options[len].setAttribute('title', elementText);
}

function openerResetSelectList(p_select_list_id) {
	window.opener.resetSelectList(p_select_list_id);
}

function openerPutElementToSelectList(elementText, elementValue, p_select_list_id)
{
	window.opener.putElementToSelectList(elementText, elementValue, p_select_list_id);
}

function togglePrRegion(element)
{
    if (document.getElementById('ifr_qlnb')) {
        /*
		if (!confirm('Thay đổi đánh dấu bài PR sẽ làm thay đổi thông tin nhuận bút. \n Bạn có muốn thay đổi?')) {
			if (element.checked) {
                element.checked = false;
            } else {
                element.checked = true;
            }
		} else {
        */
            v_ifr_url = $('#ifr_qlnb').attr('src');
            v_is_pr = element.checked ? 1 : 0;
            v_ifr_url = v_ifr_url.substring(0, v_ifr_url.length-1) + v_is_pr;
            $('#ifr_qlnb').attr('src', v_ifr_url);
        // }
	}
    objPr = document.getElementById('chk_pr_uu_tien');
    objlinkdacbiet = document.getElementById('chk_pr_link_dac_biet');
    objtrongmuc = document.getElementById('chk_pr_trong_muc');
    objPrDauTrang = document.getElementById('chk_pr_dau_trang');
    objPrTuVan = document.getElementById('chk_pr_tu_van');
    objPrRegion = document.getElementById('block_pr_region');
    //Begin 19-05-2016 : trungcq xu_ly_nhuan_but_bai_pr_gia_re
    objPrGiare = document.getElementById('chk_pr_gia_re');
    objPrlienquan = document.getElementById('chk_pr_lien_quan');
    objPrnhanhang = document.getElementById('chk_pr_nhan_hang');
    objPrchuyensau = document.getElementById('chk_pr_chuyen_sau');
    objPrlongghep = document.getElementById('chk_pr_long_ghep');
    document.getElementById('div_thiet_bi').style.display='';
    document.getElementById('div_vung_mien').style.display='';
    document.getElementById('div_ck_thu_phi').style.display='';
    if(element.id!='chk_pr_uu_tien' && element.id!='chk_pr_dau_trang'){
        document.getElementById('tr_xuat_ban_bai_pr_24hmoney').style.display='none';
        if(document.getElementById('chk_day_bai_24hmoney')){
            document.getElementById('chk_day_bai_24hmoney').checked=false;
        }
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_24hmoney').style.display='none';

        document.getElementById('tr_xuat_ban_bai_pr_tinmoi').style.display='none';
        if(document.getElementById('chk_day_bai_tinmoi')){
            document.getElementById('chk_day_bai_tinmoi').checked=false;
        }
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_tinmoi').style.display='none';
    }
    if (element.checked) {
        if (element.id=='chk_pr_uu_tien') {
            objPrRegion.style.display = '';
            objPrDauTrang.checked = false;
            objPrTuVan.checked = false;
            objPrGiare.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
            document.getElementById('tr_xuat_ban_bai_pr_24hmoney').style.display='';
            document.getElementById('tr_xuat_ban_bai_pr_tinmoi').style.display='';
        }
        else if (element.id=='chk_pr_dau_trang') {
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrTuVan.checked = false;
            objPrGiare.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
            document.getElementById('tr_xuat_ban_bai_pr_24hmoney').style.display='';
            document.getElementById('tr_xuat_ban_bai_pr_tinmoi').style.display='';
        } else if (element.id=='chk_pr_tu_van') {
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        } else if (element.id=='chk_pr_gia_re') {
            objPrRegion.style.display = 'none';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_lien_quan') {
            document.getElementById('div_thiet_bi').style.display='none';
            document.getElementById('div_vung_mien').style.display='none';
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_nhan_hang') {
            document.getElementById('div_thiet_bi').style.display='none';
            document.getElementById('div_vung_mien').style.display='none';
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_link_dac_biet') {
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_trong_muc') {
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objPrchuyensau.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_chuyen_sau') {
            document.getElementById('div_thiet_bi').style.display='none';
            document.getElementById('div_vung_mien').style.display='none';
            document.getElementById('div_ck_thu_phi').style.display='none';
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objPrlongghep.checked = false;
        }else if(element.id=='chk_pr_long_ghep') {
            document.getElementById('div_thiet_bi').style.display='none';
            document.getElementById('div_vung_mien').style.display='none';
            document.getElementById('div_ck_thu_phi').style.display='none';
            objPrRegion.style.display = '';
            objPr.checked = false;
            objPrDauTrang.checked = false;
            objPrGiare.checked = false;
            objPrTuVan.checked = false;
            objPrlienquan.checked = false;
            objPrnhanhang.checked = false;
            objlinkdacbiet.checked = false;
            objtrongmuc.checked = false;
            objtrongmuc.checked = false;
            objPrchuyensau.checked = false;
        }
    }
    //End 19-05-2016 : trungcq xu_ly_nhuan_but_bai_pr_gia_re
    else if (!objPr.checked && !objPrDauTrang.checked) {
        objPrRegion.style.display = 'none';
    }
}

function frm_submit_select_category(p_form_obj, p_target_id, p_has_count,p_no_submit)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_category = new Array();
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id_'+i];
        v_name = p_form_obj['hdn_category_name_'+i];
        if (v_id.checked) {
            v_count++;
            v_arr_category[v_arr_category.length] = new Array(v_name.value, v_id.value);
        }
    }
    if (v_count == 0 && p_has_count == true) {
        alert("Chưa có chuyên mục nào được chọn!");
    } else {
        if(document.getElementsByName("chk_item_main_cate_id").length >0){
            obj = document.getElementsByName("chk_item_main_cate_id");
            v_id = getRadioButtonValue(obj);
            if(v_id == '' || (typeof v_id == 'undefined')){
                alert("Bạn phải chọn chuyên mục gốc!");
                return;
            }else{
                window.opener.document.getElementById("sel_main_category_id").value=v_id;
            }
        }
        openerResetSelectList(p_target_id);
        var v_arr_category_show_erp = new Array();
        if(window.opener.document.getElementById("chuyen_muc_hien_thi_o_nhap_loai_bai_erp")){
            v_cate_show_news_type_erp = window.opener.document.getElementById("chuyen_muc_hien_thi_o_nhap_loai_bai_erp").value;
            v_arr_category_show_erp = v_cate_show_news_type_erp.split(",");
        }
        var v_hien_thi_loai_bai_erp_theo_chuyen_muc = 0;
        for (i in v_arr_category) {
            if(v_id == parseInt(v_arr_category[i][1])){
                // Ghép chuỗi chuyên mục chính
                v_arr_category[i][0] = v_arr_category[i][0]+' (Chuyên mục XB gốc)';
            }
            if(v_arr_category_show_erp.length >0){
                if(v_arr_category_show_erp.indexOf(v_arr_category[i][1]) >=0){
                    v_hien_thi_loai_bai_erp_theo_chuyen_muc  =1;
                    v_ket_hop_layout = window.opener.document.getElementById("ket_hop_layout_hien_thi_loai_bai_erp").value;
                    if(v_ket_hop_layout == 0){
                        window.opener.document.getElementById("news_type_push_erp").style.display ="";
                    }else{
                        if(window.opener.document.getElementById("sel_chuyen_muc_xb_banner")){
                            var v_selectcmbanner = window.opener.document.getElementById("sel_chuyen_muc_xb_banner");
                            var valuecmbanner = v_selectcmbanner.options[v_selectcmbanner.selectedIndex].value;
                            if(valuecmbanner >0){
                                window.opener.document.getElementById("news_type_push_erp").style.display ="";
                            }
                        }
                    }
                }
            }
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
        }
        // Hiển thị loại bài erp để kiểm tra điều kiện
        if(window.opener.document.getElementById("hien_thi_loai_bai_erp_theo_chuyen_muc")){
            window.opener.document.getElementById("hien_thi_loai_bai_erp_theo_chuyen_muc").value = v_hien_thi_loai_bai_erp_theo_chuyen_muc;
        }
        // off ô nhập loại bài
        if(v_hien_thi_loai_bai_erp_theo_chuyen_muc == 0 && window.opener.document.getElementById("news_type_push_erp")){
            window.opener.$('.rd_news_type_push_erp').attr('checked', false);
            window.opener.document.getElementById("news_type_push_erp").style.display ="none";
        }
        /* Begin: 18-12-2018 code_day_bai_viet_sang_cms_baogiaothong */
        // Kiểm tra có input hiden hdn_c_code hay không
        if(document.getElementById('hdn_c_code')){
            // Lấy giá trị mã đối tác
            var v_code = document.getElementById('hdn_c_code').value;
            // Thực hiện appen mã đối tác  vào input hiden sel_name_partners_id
            window.opener.document.getElementById("sel_name_partners_id").value=v_code;
            
            // Kiểm tra có input hiden hdn_c_name_partners hay không
            if(document.getElementById('hdn_c_code')){
                 // Lấy giá trị tên đối tác
                var v_name = document.getElementById('hdn_c_name_partners').value;
                // Thực hiện appen tên đối tác  vào input hiden sel_name_partners_id
                window.opener.document.getElementById("name_partners").innerHTML =v_name;
            }
        }
        /* End: 18-12-2018 code_day_bai_viet_sang_cms_baogiaothong */
        /* Begin: 06-11-2019 TuyenNT bo_sung_link_goc_cho_bai_khai_thac_ocm */
        // Chỉ xử lý chọn chuyên mục phụ
        if(document.getElementById("hdn_c_name_partners") && window.opener.document.getElementById("txt_url_news_partners")){
            window.opener.document.getElementById("txt_url_news_partners").readOnly = true;
        }
        /* End: 06-11-2019 TuyenNT bo_sung_link_goc_cho_bai_khai_thac_ocm */
        // window.close();
        if (p_no_submit != 1) {
            p_form_obj.submit();
        } else {
            window.close();
        }
    }
    return false;
}


// Dannc function bao loi chon box xuat ban
function frm_submit_select_box_publish_mobile(p_form_obj)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_category_count = p_form_obj.hdn_category_count.value;
    v_arr_box = new Array();
    v_error = '';
    /*Begin 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
    // Trạng thái kiểm tra loại tin bài có được xuất bản IA không
    v_kiem_tra_xuat_ban_ia = parseInt(p_form_obj.hdn_kiem_tra_xuat_ban_ia.value);
    // Cấu hình tên box xuất bản IA
    v_ten_box_xuat_ban_them_instant_article = p_form_obj.hdn_ten_box_xuat_ban_them_instant_article.value;
    /*End 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
    for (i=0; i<v_rows; i++) {
        v_count = 0;
        v_id = p_form_obj['chk_item_id_'+i];
        v_box_name = p_form_obj['hdn_box_name_'+i];
        v_str_category_list = '';
        v_str_category_id = '';
        
        if (v_id.checked) {
            for (j=0; j<v_category_count; j++) {
                v_category_id = p_form_obj['chk_category_id_'+i+'_'+j];
                v_category_name = p_form_obj['hdn_category_name_'+i+'_'+j];
                v_category_active = p_form_obj['hdn_category_active_'+i+'_'+j];
                if (v_category_id && v_category_id.checked) {
                    if(v_category_active.value ==0){
                        alert('Bạn không được phép xuất bản chuyên mục chưa xuất bản vào box!')
                        return false;
                    }
                    v_count++;
                    v_str_category_id += (v_str_category_id=='') ? v_category_id.value : '|'+v_category_id.value;
                    v_str_category_list += (v_str_category_list=='') ? v_category_name.value : ' | '+v_category_name.value;
                }
            }
            // Neu box da chon chuyen muc
            if (v_count > 0) {
                v_value = v_id.value+'-'+v_str_category_id;
                v_text = v_box_name.value+' - '+v_str_category_list;
                v_arr_box[v_arr_box.length] = new Array(v_text, v_value);
            } else {
                v_error += ((v_error=='') ? 'Bạn chưa chọn chuyên mục xuất bản thêm nào cho box: ' : ', ') + v_box_name.value;
            }
            /*Begin 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
            // Kiểm tra được xuất bản IA không
            if(v_kiem_tra_xuat_ban_ia==1 && v_ten_box_xuat_ban_them_instant_article==v_id.value){
                alert('Bài infographic/bài magazine/bài quiz/bài ảnh gif/bài ảnh so sánh/bài tường thuật/bài trắc nghiệm/bài livescore không hỗ trợ hiển thị trên IA');
                return false;
            }
            /*End 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
        }
    }
    if (v_error != '') {
        alert(v_error);
        return false;
    }else {
        return true;
    }
}
// hết function
function frm_submit_select_box_publish(p_form_obj)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_category_count = p_form_obj.hdn_category_count.value;
    v_arr_box = new Array();
    v_error = '';
    /*Begin 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
    // Trạng thái kiểm tra loại tin bài có được xuất bản IA không
    v_kiem_tra_xuat_ban_ia = parseInt(p_form_obj.hdn_kiem_tra_xuat_ban_ia.value);
    // Cấu hình tên box xuất bản IA
    v_ten_box_xuat_ban_them_instant_article = p_form_obj.hdn_ten_box_xuat_ban_them_instant_article.value;
    /*End 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
    for (i=0; i<v_rows; i++) {
        v_count = 0;
        v_id = p_form_obj['chk_item_id_'+i];
        v_box_name = p_form_obj['hdn_box_name_'+i];
        v_str_category_list = '';
        v_str_category_id = '';
        
        if (v_id.checked) {
            for (j=0; j<v_category_count; j++) {
                v_category_id = p_form_obj['chk_category_id_'+i+'_'+j];
                v_category_name = p_form_obj['hdn_category_name_'+i+'_'+j];
                v_category_active = p_form_obj['hdn_category_active_'+i+'_'+j];
                if (v_category_id && v_category_id.checked) {
                    if(v_category_active.value ==0){
                        alert('Bạn không được phép xuất bản chuyên mục chưa xuất bản vào box!')
                        return false;
                    }
                    v_count++;
                    v_str_category_id += (v_str_category_id=='') ? v_category_id.value : '|'+v_category_id.value;
                    v_str_category_list += (v_str_category_list=='') ? v_category_name.value : ' | '+v_category_name.value;
                }
            }
            // Neu box da chon chuyen muc
            if (v_count > 0) {
                v_value = v_id.value+'-'+v_str_category_id;
                v_text = v_box_name.value+' - '+v_str_category_list;
                v_arr_box[v_arr_box.length] = new Array(v_text, v_value);
            } else {
                v_error += ((v_error=='') ? 'Bạn chưa chọn chuyên mục xuất bản thêm nào cho box: ' : ', ') + v_box_name.value;
            }
            /*Begin 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
            // Kiểm tra được xuất bản IA không
            if(v_kiem_tra_xuat_ban_ia==1 && v_ten_box_xuat_ban_them_instant_article==v_id.value){
                alert('Bài infographic/bài magazine/bài quiz/bài ảnh gif/bài ảnh so sánh/bài tường thuật/bài trắc nghiệm/bài livescore không hỗ trợ hiển thị trên IA');
                return false;
            }
            /*End 08-02-2018 trungcq xu_ly_bai_viet_instance_article*/
        }
    }
    if (v_error != '') {
        alert(v_error);
    } else {
        openerResetSelectList('sel_box_publish_list');
        for (i in v_arr_box) {
            openerPutElementToSelectList(v_arr_box[i][0], v_arr_box[i][1], 'sel_box_publish_list');
        }
        //window.close();
		p_form_obj.submit();
    }
    return false;
}

// dannc function de cu tin mobile
function frm_submit_select_special_box_mobile(p_form_obj, p_has_count) {
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_box = new Array();
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id_'+i];
        v_name = p_form_obj['hdn_box_name_'+i];
        v_category = p_form_obj['sel_category_'+i];
        v_box_name = p_form_obj['hdn_box_name_'+i];
        if (v_id.checked) {
            v_count++;
            v_value = v_id.value+'-'+v_category.value;
            v_text = v_box_name.value+' - '+v_category.options[v_category.selectedIndex].text.replace('-- ', '');
            v_arr_box[v_arr_box.length] = new Array(v_text, v_value);
        }
    }
    if (v_count == 0 && p_has_count == true) {
        alert("Chưa có box nào được chọn!");
        return false;
    }else {
        return true;
    }
}
function frm_submit_select_special_box(p_form_obj, p_has_count)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_box = new Array();
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id_'+i];
        v_name = p_form_obj['hdn_box_name_'+i];
        v_category = p_form_obj['sel_category_'+i];
        v_box_name = p_form_obj['hdn_box_name_'+i];
        if (v_id.checked) {
            v_count++;
            v_value = v_id.value+'-'+v_category.value;
            v_text = v_box_name.value+' - '+v_category.options[v_category.selectedIndex].text.replace('-- ', '');
            v_arr_box[v_arr_box.length] = new Array(v_text, v_value);
        }
    }
    if (v_count == 0 && p_has_count == true) {
        alert("Chưa có box nào được chọn!");
    } else {
        openerResetSelectList('sel_special_box_list');
        for (i in v_arr_box) {
            openerPutElementToSelectList(v_arr_box[i][0], v_arr_box[i][1], 'sel_special_box_list');
        }
        //window.close();
		p_form_obj.submit();
    }
    return false;
}

function getRadioButtonValue(radioObj) {
	if(!radioObj) {
		return "";
	}
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		if(radioObj.checked) {
			return radioObj.value;
		} else {
			return "";
		}
	}
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i].value;
		}
	}
	return "";
}

function getRadioButton(radioObj) {
	if(!radioObj) {
		return "";
	}
	var radioLength = radioObj.length;
	if(radioLength == undefined) {
		if(radioObj.checked) {
			return radioObj;
		} else {
			return "";
		}
	}
	for(var i = 0; i < radioLength; i++) {
		if(radioObj[i].checked) {
			return radioObj[i];
		}
	}
	return "";
}
function frm_submit_select_template_table(p_form_obj)
{
    v_bang_phuc_tap = p_form_obj.chk_bang_phuc_tap.checked;
    v_content = '';

    if (v_bang_phuc_tap == 1) {
        v_content = window.CKEDITOR.instances.txt_table_content.getData();
    } else {
        v_type = getRadioButtonValue(p_form_obj.rad_template_type);
        v_align = p_form_obj.sel_align.value;
        v_bg_color = p_form_obj.txt_bg_color.value;
        v_width = parseInt(p_form_obj.txt_width.value);
        /* Begin: Tytv - 10/05/2017 - toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
        if (v_width>660) {
            alert('Kích thước chiều rộng của bảng tối đa 660 px');
            return false;
        }
        /* End: Tytv - 10/05/2017 - toi_uu_kich_thuoc_anh_video_bang_bieu_trang_bai_viet */
        v_content = '<table class="not_tblCustom" width="'+v_width+'" align="'+v_align+'" cellpadding="3" cellspacing="0" style="border:1px solid #bbb;background-color:#'+v_bg_color+';margin:5px;'+((v_align=='center') ? 'margin-left:auto;margin-right:auto' : '')+'"><tr><td valign="top" style="text-align:justify">';
        v_content += window.CKEDITOR.instances.txt_table_content.getData();
        v_content += '</td></tr></table><p></p>';
    }
    
    oEditor = window.opener.CKEDITOR.instances.txt_body;
    oEditor.insertHtml(v_content);
    window.close();
    return false;
}

function frm_submit_select_poll(p_form_obj, p_poll_id, p_poll_display, p_is_editor)
{
    v_poll_id = getRadioButtonValue(p_form_obj.rad_poll_id);
	v_width = 210;
	v_height = 240;
	if (!v_poll_id) {
        alert('Chưa có POLL nào được chọn!');
        return false;
    }

    if (typeof p_poll_id != undefined && p_poll_id != '') {
        window.opener.document.getElementById(p_poll_id).value = v_poll_id;
    }
    if (typeof p_poll_display != undefined && p_poll_display != '') {
        v_align = p_form_obj.sel_align.value;
        v_str_align = (v_align!='' && v_align!='center') ? 'align="'+v_align+'"' : '';

        v_content = '<iframe name="iframePoll'+v_poll_id+'" src="/ajax/poll/dsp_poll.php?pollID='+v_poll_id+'" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" width="'+v_width+'" height="'+v_height+'" style="margin:5px" '+v_str_align+'></iframe>';
        v_content = (v_align=='center') ? '<p align="center">'+v_content+'</p>' : v_content;
        /*Begin 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/
        v_content = '<div class="data-embed-code-poll">'+v_content+'</div>';
        /*End 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/

        if (p_is_editor == true) {
            // FCKeditor
            // oEditor = window.opener.FCKeditorAPI.GetInstance(p_poll_display);
            // oEditor.InsertHtml(v_content);
            // CKeditor
            oEditor = window.opener.CKEDITOR.instances[p_poll_display];
            oEditor.insertHtml(v_content);
        } else {
            window.opener.document.getElementById(p_poll_display).innerHTML = v_content;
        }
    }
    window.close();
    return false;
}

function frm_submit_select_event(p_form_obj)
{
    v_event_id = getRadioButtonValue(p_form_obj.rad_event_id);
    if (!v_event_id) {
        alert('Chưa có sự kiện nào được chọn!');
        return false;
    }
    window.opener.document.getElementById('hdn_event_id').value = v_event_id;
    window.opener.$('#hdn_event_id').change();
    openerEventSearch = window.opener.document.getElementById('txt_event_search');
    v_obj_event = p_form_obj['hdn_event_'+v_event_id];
    openerEventSearch.value = (v_obj_event.length > 0) ? p_form_obj['hdn_event_'+v_event_id][0].value : p_form_obj['hdn_event_'+v_event_id].value;
    openerEventSearch.readOnly = true;
    window.close();
    return false;
}

function frm_submit_select_album(p_form_obj)
{
    v_album_id = getRadioButtonValue(p_form_obj.rad_album_id);
    if (!v_album_id) {
        alert('Chưa có album nào được chọn!');
        return false;
    }
    window.opener.document.getElementById('txt_album_id').value = v_album_id;
    window.opener.document.getElementById('txt_dsp_album_name').innerHTML = '<a href="'+CONFIG.BASE_URL+'album/dsp_single_album/'+v_album_id+'" target="_blank">Album: '+p_form_obj['hdn_album_'+v_album_id].value+'</a>';
    window.close();
    return false;
}

function frm_submit_select_relate_news(p_form_obj, p_focus_obj)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_content = '';
    v_arr_selected = new Array();
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id'+i];
        v_title = p_form_obj['hdn_title_'+i].value;
        v_url = p_form_obj['hdn_url_'+i].value;
        if (v_id.checked) {
            if ($.inArray(v_id.value, v_arr_selected) !== -1) {
                alert('Bài "'+v_title+'" đã bị chọn nhiều lần');
                return false;
            }
            v_arr_selected[v_arr_selected.length] = v_id.value;
            v_count++;
            v_content += '<p><a href="'+v_url+'">'+v_title+'</a></p>';
        }
    }
    if(p_focus_obj=='txt_bai_lien_quan_duoi_sapo' && v_count>3) {
        alert('Tin bài liên quan dưới sapo đã nhiều hơn 3 bài.');
        return false;
    }
    if (v_count == 0) {
        alert("Chưa có bài nào được chọn!");
    } else {
        // FCKeditor
        // oEditor = window.opener.FCKeditorAPI.GetInstance('txt_bai_lien_quan');
        // oEditor.InsertHtml(v_content);
        // CKeditor        
        oEditor = window.opener.CKEDITOR.instances[p_focus_obj];        
        oEditor.insertHtml(v_content);
        window.close();
    }
    return false;
}

function frm_submit_select_one_relate_news(p_form_obj, p_row_id, p_focus_obj)
{
    v_id = p_form_obj['chk_item_id'+p_row_id];
    v_title = p_form_obj['hdn_title_'+p_row_id].value;
    v_url = p_form_obj['hdn_url_'+p_row_id].value;
    v_content = '<p><a href="'+v_url+'">'+v_title+'</a></p>';    
    oEditor = window.opener.CKEDITOR.instances[p_focus_obj];        
    v_content = oEditor.getData() + v_content;
    oEditor.setData(v_content);
    $('.news_'+v_id.value).hide();
}

function frm_submit_delete_image_from_album(p_form_obj, p_image_id, p_album_id)
{
    removeBlockById('block_image_'+p_image_id);
	p_form_obj.c_total_images.value= p_form_obj.c_total_images.value-1;
    p_form_obj.action = CONFIG.BASE_URL+'/album/act_delete_image/'+p_image_id+'/'+p_album_id;
    p_form_obj.submit();
	document.frmUpload.c_total_images.value= p_form_obj.c_total_images.value;
}

function dialog_for_public_submit(p_forms, p_action_url, p_target)
{
	frm_submit(p_forms, p_action_url, p_target);
}

function close_news_publication_info(p_forms, p_action_url, p_target)
{
	frm_submit(p_forms, p_action_url, p_target);
}

function objSelectAll(p_obj) {
	for (var i = 0; i < p_obj.options.length; i++) {
		p_obj.options[i].selected = true;
	}       
}

// Function for FCKeditor
/*
function orderEditorContent(p_editor, p_order)
{
    if (!p_editor.EditorDocument) {
        return false;
    } else {
        if(p_editor.EditorDocument.selection != null) {
            v_selection_content = p_editor.EditorDocument.selection.createRange().text;
        }
        else {
            v_selection_content = p_editor.EditorWindow.getSelection();
        }
    }
    if (v_selection_content=='') {
        return false;
    }
    v_content = p_editor.GetHTML();
    matches = v_content.match(/<p[^>]*>(<[^p]+[^>]*>)[^<]*(<\/[^p]+>)?<\/p>/g);
    v_arr = new Array();
    if (matches!=null && matches.length>0) {
        for (i=0; i<matches.length; i++) {
            if (matches[i]=='') {
                continue;
            }
            if (matches[i].match(v_selection_content)) {
                if (p_order=='delete') {
                    continue;
                } else if (p_order=='up') {
                    if (i>0) {
                        v_arr[i] = v_arr[i-1];
                        v_arr[i-1] = matches[i];
                        continue;
                    }
                } else if (p_order=='down') {
                    if (i<matches.length-1) {
                        v_arr[i] = matches[i+1];
                        v_arr[i+1] = matches[i];
                        i++;
                        continue;
                    }
                }
            }
            v_arr[v_arr.length] = matches[i];
        }
    } else {
        return false;
    }
    v_new_content = v_arr.join('');
    p_editor.SetHTML(v_new_content);
}
*/

// Function for CKeditor
function orderEditorContent(p_editor, p_order)
{
    v_selection_content = p_editor.getSelection().getSelectedText();
    if (v_selection_content=='') {
        return false;
    }
    v_content = p_editor.getData();
    matches = v_content.match(/<p[^>]*>(<[^p]+[^>]*>)[^<]*(<\/[^p]+>)?<\/p>/g);
    v_arr = new Array();
    if (matches!=null && matches.length>0) {
        for (i=0; i<matches.length; i++) {
            if (matches[i]=='') {
                continue;
            }
            if (matches[i].match(v_selection_content)) {
                if (p_order=='delete') {
                    continue;
                } else if (p_order=='up') {
                    if (i>0) {
                        v_arr[i] = v_arr[i-1];
                        v_arr[i-1] = matches[i];
                        continue;
                    }
                } else if (p_order=='down') {
                    if (i<matches.length-1) {
                        v_arr[i] = matches[i+1];
                        v_arr[i+1] = matches[i];
                        i++;
                        continue;
                    }
                }
            }
            v_arr[v_arr.length] = matches[i];
        }
    } else {
        return false;
    }
    v_new_content = v_arr.join('');
    p_editor.setData(v_new_content);
}

function displayFileUploadInfo(o, p_target_id)
{
	var nFiles = o.length;
	var str = '';
	for ( var i=0; i<nFiles; i++) {
		str += '<div class="line-dot">';
		str += '<div style="width:400px">File <b>'+o[i]['name']+'</b>: '+(Math.round(o[i]['size']/1024*100)/100)+'KB</div>';
		str += '</div>';
	}
	document.getElementById(p_target_id).innerHTML = str;
}

/*
 * Cai dat autocomplete cho 1 input
 * params
 *      p_input_id : id cua input
 *      p_json_data : du lieu tim kiem kieu json (vi du: [{"id":"1","name":"Tieng Viet co dau","ascii_name":"Tieng Viet khogn dau"})
 *      p_target_id : id cua input can gan du lieu
 *      p_min_char : so ky tu toi thieu can nhap de autocomplete
 *      p_callback : function can goi sau khi chon doi tuong tim kiem
 * vi du: setAutoComplete('txt_search', p_json_data, 'hdn_search_id', 1, 'abc()')
 */
function setAutoComplete(p_input_id, p_json_data, p_target_id, p_min_char, p_callback) {
    $(document).ready(function () {
        //attach autocomplete
        v_split_char = '#';
        $("#"+p_input_id).autocomplete({
            source: $.map($.makeArray(p_json_data), function(val) {
                return {
                    value: val.name + v_split_char + val.ascii_name,
                    id: val.id,
                    name: val.name
                };
            }),
            minLength: (p_min_char>0) ? p_min_char : 1,
            delay: 20,

            focus: function(event, ui) {
                return false;
            },
            //define select handler
            select: function(e, ui) {
                v_name = ui.item.name.split(v_split_char);
                v_name = v_name[0];
                $("#"+p_input_id).val(v_name);
                if (p_target_id != '') {
                    $("#"+p_target_id).val(ui.item.id);
                    $("#"+p_target_id).change();


                }
                if (p_callback!=null && p_callback!=''){
                    eval(p_callback);
                }
                return false;
            },
            //define select handler
            change: function() {
                // do nothing
            }
        })
        .data( "autocomplete" )._renderItem = function( ul, item ) {
            re = new RegExp(this.term, "i");
            t = item.name.replace(re, "<span class='ui-autocomplete-match'>$&</span>");
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + t + "</a>" )
                .appendTo( ul );
        };
    });
};

function disableInputAutoComplete(p_input_id, p_target_id, p_status)
{
    v_obj = document.getElementById(p_input_id);
    v_obj.readOnly = p_status;
    // Nhap lai input
    if (!p_status) {
        v_obj.value = '';
        document.getElementById(p_target_id).value = '';
    }
}

function setCountdown(p_input_id, p_max_length, p_target_id)
{
    $(document).ready(function () {
        currentLength = $('#'+p_input_id).val().length;
        left = p_max_length - currentLength;
        v_countdown = 'Đã nhập <span style="color:red;fontweight:bold">'+currentLength+'</span>';
        v_countdown += ' còn <span style="color:red;fontweight:bold">'+left+'</span>';
        $('#'+p_target_id).html(v_countdown);
        $('#'+p_input_id).keyup(function () {
            currentLength = $(this).val().length;
            left = p_max_length - currentLength;
            if (left < 0) {
                left = 0;
                currentLength = p_max_length;
                $(this).val($(this).val().substring(0,p_max_length));
            }
            v_countdown = 'Đã nhập <span style="color:red;fontweight:bold">'+currentLength+'</span>';
            v_countdown += ' còn <span style="color:red;fontweight:bold">'+left+'</span>';
            $('#'+p_target_id).html(v_countdown);
        });
    });
}

function getImageDataURL(obj, target_id, p_callback)
{
    if ( window.FileReader ) {
        files = obj.files; // FileList object

        f = files[0];
        // Only process image files.
        /* begin 13/9/2017 TuyenNT toi_uu_upload_anh_gif_de_khong_bi_treo_may */
        if (!f.type.match('image.*') || f.type.match('gif.*')) {
        /* end 13/9/2017 TuyenNT toi_uu_upload_anh_gif_de_khong_bi_treo_may */
            return false;
        }

        reader = new FileReader();

        // Closure to capture the file information.
        reader.onload = (function(theFile) {
            return function(e) {
                document.getElementById(target_id).value = e.target.result;
            };
        })(f);

        // Read in the image file as a data URL.
        reader.readAsDataURL(f);
    } else {
        document.getElementById(target_id).value = obj.value;
    }
    if (p_callback!=null && p_callback!='') {
        p_callback();
    }
}

// Ham loc truong theo ten + ma
function chon_nhanh(p_text_input,p_sel_arr,event){
    if (typeof searching == undefined) var searching=null;
	if (null!=searching) clearTimeout(searching);
	if(event.keyCode==13){p_text_input.form.submit();}
	p_text_input.autocomplete ='off';
	searching=setTimeout( function(){ searchNow(p_text_input,p_sel_arr)},100);
}

// ham tim kiem tren mang
function searchNow(p_text_input,p_sel_arr){
    if (typeof searching == undefined) var searching=null;
	if(null!=searching)clearTimeout(searching);
	var input=locdau(p_text_input.value).toUpperCase();
	var selectList=p_sel_arr;
	var selectOptions=selectList.getElementsByTagName('option');
	if(p_text_input.value==''){
		selectOptions[0].selected=true;
		return;
	}
	var found;
	var hid_value;
	found=false;
	var foundCount;
	foundCount=0;
	var opt;
	for(var i=0;i<selectOptions.length;i++){
		opt=selectOptions[i];
		var obj = locdau(opt.title).toUpperCase();
		if(obj.indexOf(input)>=0){
			if(!found){
                /* Bỏ comment nếu muốn option được chọn nhảy lên trên cùng
				if(i>0){
					selectList.removeChild(opt);
					selectList.insertBefore(opt,selectOptions[0]);
				}
                */
				opt.selected=true;
				found=true;
			}else{
                /* Bỏ comment nếu muốn option được chọn nhảy lên trên cùng
				selectList.removeChild(opt);
				if(selectOptions[foundCount]){
					selectList.insertBefore(opt,selectOptions[foundCount]);
				}else{
					selectList.insertBefore(opt,selectOptions[foundCount-1]);
				}
                */
			}
			foundCount++;
		}else{
			opt.selected=false;
		}
	}
	if(!found){
		selectOptions[0].selected=true;
	}
}

function changeValueBySelect(p_obj, p_target_id)
{
    v_idx = p_obj.selectedIndex;
    v_target = document.getElementById(p_target_id);
    for (i=0; i<v_target.options.length; i++) {
        if (v_target.options[i].value == p_obj.options[v_idx].value) {
            v_target.options[i].selected = true;
            break;
        }
    }
}

function flashWrite(p_url, p_width, p_height)
{
    return;
    base_url = CONFIG.BASE_URL+'/js/';
    p_url = p_url.substring(p_url.indexOf('file=')+5);
    p_url = p_url.replace('&', '');
    p_url = p_url.replace(' ', '');
    urlArr = p_url.split(',');
    arr_playlist = new Array();
    for (i=0; i<urlArr.length; i++) {
        arr_playlist[i] = '{file:"'+urlArr[i]+'?start=0"';
        if (i==0) {
            arr_playlist[i] += ', image:"'+base_url+'jwplayer/preview.swf"';
        }
        arr_playlist[i] += '}';
    }
    v_playlist = '['+arr_playlist.join()+']';
    
    playerID = 'mediaplayer'+Math.random();
    v_str_player = '<div id="'+playerID+'"></div>';
    v_str_player+= '<scr'+'ipt type="text/javascript">';
    v_str_player+= 'jwplayer("'+playerID+'").setup({';
    v_str_player+= 'flashplayer: "'+base_url+'jwplayer/player.swf",';
    v_str_player+= 'playlist: '+v_playlist+',';
    v_str_player+= 'repeat: "list",';
    v_str_player+= 'stretching: "uniform",';
    v_str_player+= 'width: 528,';
    v_str_player+= 'height: 335,'; // 297
    v_str_player+= 'skin: "'+base_url+'jwplayer/skin-1.swf",';
    v_str_player+= '"controlbar.idlehide":"true","controlbar.position":"bottom"';
    v_str_player+= '})';
    v_str_player+= '</scr'+'ipt>';
    document.write(v_str_player);
}

//Ham chuyen chu co dau sang khong dau
function locdau(str) {
   str= str.toLowerCase();
   str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
   str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
   str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
   str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
   str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
   str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
   str= str.replace(/đ/g,"d");
   return str;
}

// Chuyen trang neu la trinh duyet IE
function switch_page_by_ie(p_page)
{
    if (isIE()) {
        window.location.href = p_page;
    }
}

function deleteFile(divID)
{
    /*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/
    $("#"+divID).parent().remove();
    $('#div_add_file').css("display","inline-block");
    /*End 14-06-2014 trungcq tang_so_luong_upload_video*/
}

function addFile(contentDiv, inputName, num)
{
    /*Begin 14-06-2014 trungcq tang_so_luong_upload_video*/
    var v_curent_count_file = parseInt($(".file_video").size());
    var uploadContent = document.getElementById(contentDiv);
    for ( var i=1; i<=num; i++) {
        fileNumber++;
        var fileUpload = document.createElement('div');
        fileUpload.innerHTML = '<div class="file_video" id="file'+fileNumber+'"><div style="float:left;width:50px;">File '+(v_curent_count_file+1)+': </div><div><input type="file" id="filevideo'+fileNumber+'" onchange="if(!check_dung_luong_video_truoc_khi_upload(this)){return;}" name="'+inputName+'[]" style="width:215px" /> <a href="javascript:void(0);" onclick="deleteFile(\'file'+fileNumber+'\')">[Xoá]</a></div></div>';
        uploadContent.appendChild(fileUpload);
    }
    if(v_curent_count_file >=4){
        alert('Chỉ được phép upload tối đa 5 file video');
        $('#div_add_file').css("display","none");
    }
    /*End 14-06-2014 trungcq tang_so_luong_upload_video*/
}

function display_preview_image(p_image_dsp, p_total_image, p_image_block_id, p_thumb_id)
{
    p_image_dsp = (p_image_dsp > p_total_image-1) ? p_total_image-1 : p_image_dsp;
    p_image_dsp = (p_image_dsp < 0) ? 0 : p_image_dsp;
    for (i=0; i<p_total_image; i++) {
        if (p_image_dsp == i) {
            document.getElementById(p_image_block_id+i).style.display = '';
            document.getElementById(p_thumb_id+i).style.border = '1px solid red';
        } else {
            document.getElementById(p_image_block_id+i).style.display = 'none';
            document.getElementById(p_thumb_id+i).style.border = '0px';
        }
    }
}

function remove_poll()
{
    document.getElementById('txt_poll_id').value = '';
    document.getElementById('dsp_poll').innerHTML = '';
}

function loadAjaxFromInput(p_input_id, p_link, p_target)
{
    v_input_obj = document.getElementById(p_input_id);
    if (v_input_obj.value !='') {
        AjaxAction(p_target, p_link+v_input_obj.value);
    }
}

function insertImageToAlbum(p_id, p_big_img, p_thumbnail)
{
    p_id = (typeof p_id == 'undefined') ? 0 : p_id;
// FCKeditor
    // v_content = '<div style="width:150px;float:left;">Sắp xếp: &nbsp; <input name="txt_order[]" type="text" style="width:30px" value="'+(p_id+1)+'" /> <img src="'+CONFIG.BASE_URL+'/images/iconDelete.gif" style="cursor:pointer" align="absmiddle" alt="[Xoá ảnh]" title="Xoá ảnh" onclick="if (confirm(\'Xoá ảnh?\')) removeBlockById(\'imgBlock_'+p_id+'\');" /><br /><br /><img src="/'+p_thumbnail+'" width="97" /><input name="hdn_big_image[]" value="'+p_big_img+'" type="hidden" /><input name="hdn_thumbnail_image[]>" value="'+p_thumbnail+'" type="hidden" /> </div><div>Chú thích ảnh:<br /><br /><input type="hidden" id="txt_description['+p_id+']" name="txt_description[]" value="" style="display:none" /><input type="hidden" id="txt_description['+p_id+']___Config" value="SkinPath='+CONFIG.BASE_URL+'/editor/fckeditor/editor/skins/office2003/" style="display:none" /><iframe id="txt_description['+p_id+']___Frame" src="'+CONFIG.BASE_URL+'/editor/fckeditor/editor/fckeditor.html?InstanceName=txt_description['+p_id+']&amp;Toolbar=Basic" width="560" height="130" frameborder="0" scrolling="no"></iframe></div><div class="clear"></div><div class="line-dot"></div>';
    // CKeditor
    v_content = '<div style="width:150px;float:left;">Sắp xếp: &nbsp; <input name="txt_order[]" type="text" style="width:30px" value="'+(p_id+1)+'" /> <img src="'+CONFIG.BASE_URL+'/images/iconDelete.gif" style="cursor:pointer" align="absmiddle" alt="[Xoá ảnh]" title="Xoá ảnh" onclick="if (confirm(\'Xoá ảnh?\')) removeBlockById(\'imgBlock_'+p_id+'\');" /><br /><br /><img src="/'+p_thumbnail+'" width="97" /><input name="hdn_big_image[]" value="'+p_big_img+'" type="hidden" /><input name="hdn_thumbnail_image[]>" value="'+p_thumbnail+'" type="hidden" /> </div><div style="float:left">Chú thích ảnh:<br /><br /><textarea id="txt_description_'+p_id+'" name="txt_description[]"></textarea></div><div class="clear"></div><div class="line-dot"></div>';
    fileUploaded = document.createElement('div');
    fileUploaded.id = 'imgBlock_'+p_id;
    fileUploaded.innerHTML = v_content;
    p_id++;
    v_target_obj = document.getElementById('block_image_uploaded');
    v_target_obj.appendChild(fileUploaded);
}

// Kiem tra du lieu da duoc luu truoc khi chuyen trang khac
function check_unsaved_change(p_check_id, p_arr_fckeditor_id)
{
    $(document).ready(function() {
        $("input").change(function(){
            $("#"+p_check_id).val("1");
        });
        $("select").change(function(){
            $("#"+p_check_id).val("1");
        });
        $("textarea").change(function(){
            $("#"+p_check_id).val("1");
        });
        $("input:checkbox").click(function(){
            $("#"+p_check_id).val("1");
        });
        $("input:radio").click(function(){
            $("#"+p_check_id).val("1");
        });
        window.onbeforeunload = function(e) {
            for (i in p_arr_fckeditor_id) {
                // FCKeditor
                /*
                if (FCKeditorAPI.GetInstance(p_arr_fckeditor_id[i]).IsDirty()
                        && FCKeditorAPI.GetInstance(p_arr_fckeditor_id[i]).GetData()!=''
                        && $("#"+p_check_id).val()!=-1) {
                    $("#"+p_check_id).val("1");
                    break;
                }
                */
                // CKEDITOR
                if (CKEDITOR.instances[p_arr_fckeditor_id[i]].checkDirty() && $("#"+p_check_id).val()!=-1) {
                    $("#"+p_check_id).val("1");
                    break;
                }
            }
            if ($("#"+p_check_id).val() == "1") {
                msg = 'Dữ liệu chưa được lưu.\nBạn có chắc chắn muốn chuyển trang khác?';
                if ( /Firefox[\/\s](\d+)/.test(navigator.userAgent) && new Number(RegExp.$1) >= 4) {
                    if(confirm(msg)) {
                        history.go();
                    } else {
                        window.setTimeout(function(){window.stop();}, 1);
                    }
                } else {
                    return msg;
                }
            }
        }
    });
}

function setDatePicker(p_object_class)
{
    $(document).ready(function() {
        $(function() {
            $("."+p_object_class).datepicker({
                changeMonth: true,
                changeYear: true
            });
        });
    });
}

$(document).ready(function () {
    // Tu dong dien text mac dinh neu khong nhap du lieu
    $('.auto-title').bind({
        focus: function() {
            if ($(this).val() == $(this).attr('title')) {
                $(this).val('');
            }
        },
        blur: function() {
            if ($(this).val() == '') {
                $(this).val($(this).attr('title'));
            } 
        }
    });
    
    // Toggle display/hide object
    $('.linkRedArrow,.linkRedArrow-up').click(function(){
        if ($(this).attr('class') == 'linkRedArrow') {
            $(this).removeClass("linkRedArrow").addClass("linkRedArrow-up");
        } else {
            $(this).removeClass("linkRedArrow-up").addClass("linkRedArrow");
        }
        v_target_id = $(this).attr('rel');
        v_target_obj = document.getElementById(v_target_id);
		if (v_target_obj) {
			if (v_target_obj.style.display == 'none') {
				v_target_obj.style.display = '';
			} else {
				v_target_obj.style.display = 'none';
			}
		}
    });
});

function setDragAndDrop()
{
    document.onmousedown=function (e)
    {
        if (e == null) { e = window.event;}
        var sender = (typeof( window.event ) != "undefined" ) ? e.srcElement : e.target;
        item_mousedown_id = sender.id;
        if (item_mousedown_id.match(/^hander_/)) {
            doTooltip(e,'<div style="background:black;color:white;padding:5px;text-align:center;font:bold 11px Arial;">Kéo thả để thay đổi thứ tự</div>');
            document.onmousemove=function (e) {
                if (e == null) { e = window.event;}
                doTooltip(e,'<div style="background:black;color:white;padding:5px;text-align:center;font:bold 11px Arial;">Kéo thả để thay đổi thứ tự</div>');
                return false;
            }
            return false;
        }
        //else { return false; }
    }

    document.onmouseup=function (e){
        document.onmousemove=null;
        if (e == null) { e = window.event;}
        var sender = (typeof( window.event ) != "undefined" ) ? e.srcElement : e.target;
        item_mouseup_id = sender.id;
        hideTip();
        if (item_mouseup_id.match(/^hander_/) && item_mousedown_id.match(/^hander_/)) {
            if (item_mousedown_id != item_mouseup_id) {	
                if(item_mousedown_id.match(/^hander_cat/)){
                    id1 = item_mousedown_id.replace('hander_cat','');
                    id2 = item_mouseup_id.replace('hander_cat','');
                    v_url = CONFIG.BASE_URL+'ajax/category/act_reorder_category/'+id1+'/'+id2;               
                } else {
                    id1 = item_mousedown_id.replace('hander_','');
                    id2 = item_mouseup_id.replace('hander_','');
                    v_url = CONFIG.BASE_URL+'ajax/news/act_reorder_newscategory/'+id1+'/'+id2;
                }
                frm_submit(document.frm_dsp_all_item, v_url, 'iframe_submit');			
            }		
        }
    }
}

function btn_update_onclick(p_forms, p_action_url, p_target, p_confirm_message, p_class_button){
	v_class_button = (typeof(p_class_button)==='undefined')? '':p_class_button;
	v_is_ok = false;	
	// Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', p_confirm_message, function () {
        frm_submit(p_forms, p_action_url, p_target);
        v_is_ok = true;
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
	if (!v_is_ok && v_class_button != '') {
		set_enable_link(v_class_button);
	}
}
/*
    Ham chuyen sang man hinh cap nhat
*/
function btn_add_onclick(p_forms, p_action_url, p_target){
	frm_submit(p_forms, p_action_url, p_target) ;	
}

/*
    Ham quay lai man hinh danh sach
*/
function btn_back_onclick(p_forms, p_action_url, p_target){
	frm_submit(p_forms, p_action_url, p_target) ;	
}

/**
 * Check or un check cac checkbox hiện thị
 * ocm_check_all('selectall', 'case'); id=selectall, class=case
 * @example http://viralpatel.net/blogs/multiple-checkbox-select-deselect-jquery-tutorial-example/
 * @params string p_checkbox_id id của checkbox khi bấm để chọn tất cả
 * @params string p_checkbox_class tên class các checkbox khi bấm vào id p_checkbox_id cần chọn tất
 * @return
 */
function ocm_check_all(p_checkbox_id, p_checkbox_class)
{
	$(function(){
		// add multiple select / deselect functionality
		$("#"+p_checkbox_id).click(function () {
            $('.'+p_checkbox_class).attr('checked', this.checked);
		});

		// if all checkbox are selected, check the selectall checkbox
		// and viceversa
		$('.'+p_checkbox_class).click(function(){

			if($('.'+p_checkbox_class).length == $("."+p_checkbox_class+":checked").length) {
				$("#"+p_checkbox_id).attr("checked", "checked");
			} else {
				$("#"+p_checkbox_id).removeAttr("checked");
			}
		});
	});
}
/*
 * Ham tra lai gia tri danh sach duoc chon khi click chọn
 * @author  cuongnx
 * @param  string p_checkbox_id ID control checkbox
 * @param  string p_tong_so_phan_tu Tong so phan tu cua checkbox
 * @return string
 */
function chon_va_bo_chon_tat_ca_checkbox_co_id_tu_tang(p_checkbox_id, p_tong_so_phan_tu) {
	for(i=0;i<p_tong_so_phan_tu;i++){
		if(document.getElementById(p_checkbox_id+'['+i+']')){
			if (v_check != false && v_check != true) {
				if (document.getElementById(p_checkbox_id+'['+i+']').checked == true) {
					var v_check  = false;
				} else {
					var v_check  = true;
				}
			}
			document.getElementById(p_checkbox_id+'['+i+']').checked = v_check;
		}
	}
}
/*
 * Ham chon check box sau khi AutoComplete
 * @author  ducnq - 04/10/2012
 * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_id id cua hidden luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @param  p_attr_sub Thuoc tinh cua checkbox
 * @return  string
 */
function chon_checkbox_sau_khi_auto_complete(checkbox_id,hdn_id,hdn_tong,txt_input,is_submit,form_id, p_attr_sub,trigger_change){
	v_attr_sub = (typeof(p_attr_sub)==='undefined')? '':p_attr_sub;
	var id_duoc_chon = document.getElementById(hdn_id).value;
	var i=0;
	var so_tinh = document.getElementById(hdn_tong).value;
	for(i=0;i<so_tinh;i++){
		var p_check_obj = document.getElementById(checkbox_id+'['+i+']');
		if(p_check_obj.value == id_duoc_chon){
			p_check_obj.checked = true;
			p_check_obj.focus();
			document.getElementById(txt_input).value = '';
			document.getElementById(txt_input).focus();		
			if(v_attr_sub!='') {
				chon_chuyen_muc_cap_2(p_check_obj, v_attr_sub);	// goi ham tu dong check chuyen muc cap 2
			}
			if(is_submit == 1) {
				eval("document."+form_id+".submit()");
			}
            if (trigger_change == 1) {
                p_check_obj.onchange();
            }
		}
	}
	return false;
}

/*
 * Ham thuc hien load lai trang khi thay doi gia tri control loc tim
 * @param : frm_name:ten form can load lai
 * @return submit
 */
function autosubmit_when_control_changed(p_class_name, frm_name)
{
    $(document).ready(function() {
		$("."+p_class_name).on({
            change: function(event) {
                var frm = eval('document.'+frm_name);
                frm.submit();
            },
            keypress: function(event) {
                if (event.which == 13) {
                    var frm = eval('document.'+frm_name);
                    frm.submit();
                }
            }
        })
    });
}
/*
 * Ham tra lai gia tri danh sach duoc chon khi click chọn
 * @author  cuongnx
 * @param  string p_checkbox_id ID control checkbox
 * @param  string p_tong_so_phan_tu Tong so phan tu cua checkbox
 * @return string
 */
function chon_va_bo_chon_tat_ca_checkbox(p_checkbox_id, p_tong_so_phan_tu, p_checked) {
	for(i=0;i<p_tong_so_phan_tu;i++){
		if(document.getElementById(p_checkbox_id+'['+i+']')){
			document.getElementById(p_checkbox_id+'['+i+']').checked = p_checked;
		}
	}
}

/*
 * Ham thuc hien check/uncheck tat ca cac checkbox trong list checkbox control
 * @author  phuonghv
 * @param  string p_checkbox_id ID control checkbox
 * @param  string p_tong_so_phan_tu Tong so phan tu cua checkbox
 * @return string
 */
function submit_check_all_checkbox(p_checkbox_id, p_tong_so_phan_tu, p_hdn_check_all, frm_name, p_checked) {
	//unchecked all = chon tat ca
	if(document.getElementById('tong_so_chuyen_muc')){
		v_total_cat = document.getElementById('tong_so_chuyen_muc').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_cat', v_total_cat, false);
	}
	if(document.getElementById('tong_so_user')){
		v_total_user = document.getElementById('tong_so_user').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_user', v_total_user, false);
	}
	if(document.getElementById('tong_so_status')) {
		v_total_status = document.getElementById('tong_so_status').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_status', v_total_status, false);
	}
	if(document.getElementById(p_hdn_check_all)) {
		document.getElementById(p_hdn_check_all).value = (p_checked==1)?0:1;// danh dau la chon tat ca
	}
    var frm = eval('document.'+frm_name);
    frm.submit();
}

/*
 * Ham thuc hien submit khi thay doi so ban ghi hien thi tren/trang man hinh danh sach menu ngang
 * @author  phuonghv
 */
function page_number_onchange(frm_name) {
	//unchecked all = chon tat ca
	if(document.getElementById('tong_so_chuyen_muc')){
		v_total_cat = document.getElementById('tong_so_chuyen_muc').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_cat', v_total_cat, false);
	}
	if(document.getElementById('tong_so_user')){
		v_total_user = document.getElementById('tong_so_user').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_user', v_total_user, false);
	}
	if(document.getElementById('tong_so_status')) {
		v_total_status = document.getElementById('tong_so_status').value;
		chon_va_bo_chon_tat_ca_checkbox('checkbox_status', v_total_status, false);
	}
    var frm = eval('document.'+frm_name);
    frm.submit();
}
/**
 * Thuc hien chay javascript doi voi cac ket qua tu ajax
 * Author: Cuongnx
 * @param string html_ajax HTML tra ve tu ajax
 */
function ocm_chay_javascript_tu_ket_qua_ajax(html_ajax) {
  var scripts = new Array();         // Tao mang chua ma script

  // Lay ma script
  while(html_ajax.indexOf("<script") > -1 || html_ajax.indexOf("</script") > -1) {
    var s = html_ajax.indexOf("<script");
    var s_e = html_ajax.indexOf(">", s);
    var e = html_ajax.indexOf("</script", s);
    var e_e = html_ajax.indexOf(">", e);

    // Them script vao mang
    scripts.push(html_ajax.substring(s_e+1, e));
    // Tach script ra html_ajax
    html_ajax = html_ajax.substring(0, s) + html_ajax.substring(e_e+1);
  }

  // Thuc hien eval doi voi tung script trong mang
  for(var i=0; i<scripts.length; i++) {
    try {
      eval(scripts[i]);
    }
    catch(ex) {

    }
  }
}
/*
 * Ham dung de goi ham tim kiem nhanh tren select box
 * @author  ducnq
 * @param  p_text_input : id cua textbox nhap chuoi kim kiem
 * @param  p_sel_arr : id cua select box can tim kiem
 * @param  event : bat su kien go phim
 */
var searching=null;
function goi_ham_tim_kiem_select_box(p_text_input,p_sel_arr,event){
	if(null!=searching)clearTimeout(searching);
	if(event.keyCode==13){return;}
	document.getElementById(p_text_input).autocomplete ='off';
	searching=setTimeout("tim_kiem_tren_select_box('"+p_text_input+"','"+p_sel_arr+"')",100);
}
/*
 * Ham tim kiem tren mang gia tri cua select box
 * @author  ducnq
 * @param  p_text_input : id cua textbox nhap chuoi kim kiem
 * @param  p_sel_arr : id cua select box can tim kiem
 */
function tim_kiem_tren_select_box(p_text_input,p_sel_arr){
	if(null!=searching)clearTimeout(searching);
	var input=ocm_to_khong_dau(document.getElementById(p_text_input).value).toUpperCase();
	var selectList=document.getElementById(p_sel_arr);
	var selectOptions=selectList.getElementsByTagName('option');
	if(document.getElementById(p_text_input).value==''){
		selectOptions[0].selected=true;
		return;
	}
	var found;
	found=false;
	var foundCount;
	foundCount=0;
	var opt;
	// tim kiem trong tung option cua selectbox
	for(var i=0;i<selectOptions.length;i++){
		opt=selectOptions[i];
		var obj = ocm_to_khong_dau(opt.title).toUpperCase();
		if(obj.indexOf(input)>=0){
			if(!found){
				if(i>0){
					selectList.removeChild(opt);
					selectList.insertBefore(opt,selectOptions[0]);
				}
				opt.selected=true;
				found=true;
			}else{
				selectList.removeChild(opt);
				if(selectOptions[foundCount]){
					selectList.insertBefore(opt,selectOptions[foundCount]);
				}else{
					selectList.insertBefore(opt,selectOptions[foundCount-1]);
				}
			}
			foundCount++;
		}else{
			opt.selected=false;
		}
	}
	if(!found){
		if (selectOptions[0]) {
			selectOptions[0].selected=true;
		}
	}
}
/**
 * Ham chuyen chu co dau sang khong dau
 * @param string str chuoi co dau can chuyen thanh ko dau
 * @return string
 */  
function ocm_to_khong_dau(str) {
   str= str.toLowerCase();
   str= str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a");
   str= str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e");
   str= str.replace(/ì|í|ị|ỉ|ĩ/g,"i");
   str= str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o");
   str= str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u");
   str= str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y");
   str= str.replace(/đ/g,"d");
   return str;
}

/*
 * Ham submit form tim kiem cho cac chuc nang quan tri chuyen muc
 * @author  phuonghv
 * @param  string frm_name Ten form
 * @return string
 */
function submit_home_box_filter(frm_name) {	
	//unchecked all = chon tat ca
	
	if(document.getElementById('tong_so_chuyen_muc') && document.getElementById('chk_all_cat')){
		if(document.getElementById('chk_all_cat').value ==1) {
			v_total_cat = document.getElementById('tong_so_chuyen_muc').value;
			chon_va_bo_chon_tat_ca_checkbox('checkbox_cat', v_total_cat, false);
		}
	}
	if(document.getElementById('tong_so_user') && document.getElementById('chk_all_user')){
		if(document.getElementById('chk_all_user').value ==1) {
			v_total_user = document.getElementById('tong_so_user').value;
			chon_va_bo_chon_tat_ca_checkbox('checkbox_user', v_total_user, false);
		}
	}
	if(document.getElementById('tong_so_status') && document.getElementById('chk_all_status')) {
		if(document.getElementById('chk_all_status').value == 1) {
			v_total_status = document.getElementById('tong_so_status').value;
			chon_va_bo_chon_tat_ca_checkbox('checkbox_status', v_total_status, false);
		}
	}
	if(document.getElementById('tong_so_box') && document.getElementById('chk_all_boxs')) {
		if(document.getElementById('chk_all_boxs').value == 1) {
			v_total_status = document.getElementById('tong_so_status').value;
			chon_va_bo_chon_tat_ca_checkbox('checkbox_status', v_total_status, false);
		}
	}
    var frm = eval('document.'+frm_name);				
    frm.submit();
}

/*
 * Ham chon check box sau khi AutoComplete
 * @author  phuonghv - 12/01/2013
 * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_id id cua hidden luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @return  string 
 */
 
function chon_radiobuton_sau_khi_auto_complete(radio_id,hdn_id,hdn_tong,txt_input,is_submit,form_id){
	var v_selected_value = document.getElementById(hdn_id).value;
	var i=0;
	var v_count = document.getElementById(hdn_tong).value;
	for( i= 0 ; i < v_count; i++) {
		if(document.getElementById(radio_id+i).value == v_selected_value){
			document.getElementById(radio_id+i).checked = true;
			document.getElementById(radio_id+i).focus();
			document.getElementById(txt_input).value = '';
			document.getElementById(txt_input).focus();
			if(is_submit == 1) {
				eval("document."+form_id+".submit()");
			}
			break;
		}
	}
	return false;
}
/*
 * Ham chon/bo chon radio button group
 * @author  phuonghv - 12/01/2013
 * @param  p_radio_id id chung cua danh sach radio button group
 * @param  p_tong_so_phan_tu tong so radio button
 * @param  p_check  gia tri true/false
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @return  string
 */
function chon_hoac_bo_chon_tat_ca_radio_button(p_radio_id, p_tong_so_phan_tu,p_check, p_is_submit,form_id)
{
	for(i=0;i<p_tong_so_phan_tu;i++){
		if(document.getElementById(p_radio_id+i)){
			document.getElementById(p_radio_id+i).checked = p_check;
		}
	}
	if(p_is_submit == 1) {
		eval("document."+form_id+".submit()");
	}
	return false;
}
/*
 * Ham thuc hien tim kiem tren man hinh danh sach textlink
 * @author  phuonghv - 12/01/2013
 * @param  form_id Nhap ten form
 */
function textlink_search(form_id)
{
	var v_ngay_bat_dau_tu_ngay = document.getElementById('txt_start_from_date').value;
	var v_ngay_bat_dau_den_ngay = document.getElementById('txt_start_to_date').value;
	var v_ngay_ket_thuc_tu_ngay = document.getElementById('txt_end_from_date').value;
	var v_ngay_ket_thuc_den_ngay = document.getElementById('txt_end_to_date').value;
	if(v_ngay_bat_dau_tu_ngay !='' && v_ngay_bat_dau_den_ngay !='') {
		v_startDate = parseDate(v_ngay_bat_dau_tu_ngay).getTime();
		v_endDate = parseDate(v_ngay_bat_dau_den_ngay).getTime();
		if (v_startDate > v_endDate){
			alert("Ngày bắt đầu không được lớn hơn ngày kết thúc");
			document.getElementById('txt_start_from_date').focus();
			return false;
		}
	}
	if(v_ngay_ket_thuc_tu_ngay !='' && v_ngay_ket_thuc_den_ngay !='') {
		v_startDate = parseDate(v_ngay_ket_thuc_tu_ngay).getTime();
		v_endDate = parseDate(v_ngay_ket_thuc_den_ngay).getTime();
		if (v_startDate > v_endDate){
			alert("Ngày bắt đầu không được lớn hơn ngày kết thúc");
			document.getElementById('txt_end_from_date').focus();
			return false;
		}
	}
	// kiem tra ngay bat dau khong duoc lon ngay ket thuc
	if(v_ngay_bat_dau_tu_ngay !='' && v_ngay_ket_thuc_tu_ngay !='') {
		v_startDate = parseDate(v_ngay_bat_dau_tu_ngay).getTime();
		v_endDate = parseDate(v_ngay_ket_thuc_tu_ngay).getTime();
		if (v_startDate > v_endDate){
			alert("Ngày bắt đầu không được lớn hơn ngày kết thúc");
			document.getElementById('txt_start_from_date').focus();
			return false;
		}
	}
	eval("document."+form_id+".submit()");
	return true;
}

/*
 * Chuyển chuỗi kí tự (string) sang đối tượng Date()
 * @author  phuonghv - 12/01/2013
 * @param  form_id Nhap ten form
 */
function parseDate(str)
{
    var mdy = str.split('-');
    return new Date(mdy[2], mdy[1]-1, mdy[0]);
}

/**
* Format date as a string
* @param date - a date object (usually "new Date();")
* @param format - a string format, eg. "DD-MM-YYYY"
*/
function dateFormat(date, format) {
    // Calculate date parts and replace instances in format string accordingly
    format = format.replace("DD", (date.getDate() < 10 ? '0' : '') + date.getDate()); // Pad with '0' if needed
    format = format.replace("MM", (date.getMonth() + 1 < 9 ? '0' : '') + (date.getMonth() + 1)); // Months are zero-based
    format = format.replace("YYYY", date.getFullYear());
    return format;
}

function display_thu_phi(objCategory)
{
    // Lay danh sach chuyen muc duoc chon
    categorySeleted = [];
    objOption = objCategory.options;
    for (i=0; i<objOption.length; i++) {
        if (objOption[i].selected == true) {
            categorySeleted[categorySeleted.length] = objOption[i] ;
        }
    }
    // Hien thi box tick chon thu phi
    objCategoryThuPhi = document.getElementById('sel_category_thu_phi');
    objCategoryThuPhi.length=0;
    for (i=0; i<categorySeleted.length; i++) {
        objCategoryThuPhi.options.add(new Option(categorySeleted[i].text, categorySeleted[i].value));
    }
    blockThuPhi = document.getElementById('block_thu_phi');
    blockThuPhi.style.display = '';
}

/**
*  Ham them mot giai doan xuat ban
* @param  p_id:  la id cua public
* @param  p_startdate:  ngay bat dau
* @param  p_enddate:  Ngay ket thuc
* @param  p_so_tuan:  so tuan can dang
* @param  p_ngay_bu:  so ngay bu
* @param  p_ghi_chu:  ghi chu
*
*/
function addRow(p_id, p_startdate, p_enddate, p_so_tuan, p_ngay_bu, p_ghi_chu)
{
	// Lay so luong giai doan xuat ban
	var table_id = "tbDateList";
	var v_row_index = document.frm_dsp_single_item.hdn_giai_doan_count.value;
	v_row_index =(v_row_index=="")? "0":v_row_index;
	var tbody = document.getElementById(table_id).tBodies[0];
	var row = document.createElement("TR"); // Tao mot the TR
	row.setAttribute("id","row"+v_row_index);
	//Cell 1
	//checkbox
	var cell1 = document.createElement("TD");
	cell1.setAttribute("id","col1"+v_row_index);
	cell1.innerHTML = '<input type="checkbox" name="chk_giai_doan'+v_row_index*1+'" value ="'+p_id+'" />';
    row.appendChild(cell1);

    //Ngay bat dau
	var cell2 = document.createElement("TD");
	cell2.setAttribute("id","col2"+v_row_index);
	cell2.innerHTML = '<input id="txt_startdate'+v_row_index*1+'" name="txt_startdate'+v_row_index*1+'" class="date_picker" style="width:70px;" value="'+p_startdate+'" readonly="readonly" type="text" />';
    row.appendChild(cell2);

	var cell3 = document.createElement("TD");
	cell3.setAttribute("id","col3"+v_row_index);
	cell3.innerHTML = '<input id="txt_enddate'+v_row_index*1+'" name="txt_enddate'+v_row_index*1+'" class="date_picker" style="width:70px;" value="'+p_enddate+'" readonly="readonly" type="text" />';
    row.appendChild(cell3);

    var cell4 = document.createElement("TD");
	cell4.setAttribute("id","col4"+v_row_index);
	cell4.innerHTML = '<input id="txt_so_tuan'+v_row_index*1+'" name="txt_so_tuan'+v_row_index*1+'" style="width:60px;" value="" onblur="getEndDateByWeek(\'txt_so_tuan'+v_row_index+'\', \'txt_startdate'+v_row_index+'\', \'txt_enddate'+v_row_index+'\')" />';
    row.appendChild(cell4);
    
    if (p_ngay_bu != undefined) {
        var cell5 = document.createElement("TD");
        cell5.setAttribute("id","col5"+v_row_index);
        cell5.innerHTML = '<input id="txt_ngay_bu'+v_row_index*1+'" name="txt_ngay_bu'+v_row_index*1+'" value="'+p_ngay_bu+'" style="width:60px;" value="" />';
        row.appendChild(cell5);
    }
    
    if (p_ghi_chu != undefined) {
        var cell6 = document.createElement("TD");
        cell6.setAttribute("id","col6"+v_row_index);
        cell6.innerHTML = '<input id="txt_ghi_chu'+v_row_index*1+'" name="txt_ghi_chu'+v_row_index*1+'" value="'+p_ghi_chu+'" style="width:100%;" value="" />';
        row.appendChild(cell6);
    }

	tbody.appendChild(row);

	v_row_index = v_row_index*1 +1;
	document.frm_dsp_single_item.hdn_giai_doan_count.value = v_row_index;
    setDatePicker("date_picker");
    // $(".date_picker").datepicker({changeMonth: true, changeYear: true});
}

/**
*  Ham xoa mot hay nhieu giai doan xuat ban
*
*/
function delRow(){
	var v_found = false;
	var table_id = "tbDateList";
	var v_row_index = document.frm_dsp_single_item.hdn_giai_doan_count.value;
	var tbl_album = document.getElementById(table_id);
	var v_list_row_delete = document.frm_dsp_single_item.hdn_giai_doan_delete_list.value;
	// tim index cua dong duoc danh dau xoa.
	var row_id;
	for(var i =0; i<v_row_index ; i++){
		var chk_obj = eval("document.frm_dsp_single_item.chk_giai_doan"+i);
		if(chk_obj && chk_obj.checked){
			row_id = "row"+i;
			var rowToDelete = document.getElementById(row_id);// Tham chieu toi TR can xoa trong box tran dau dang theo doi
			if (rowToDelete) {
				rowToDelete.parentNode.removeChild(rowToDelete); // Thuc hien xoa dong hoi danh sach
				// Ghi lai index dong bi xoa
				if(v_list_row_delete ==""){
					v_list_row_delete = i;
				}
				else {
					v_list_row_delete+= ","+i;
				}
				v_found = true;
			}
		}
	}
	if (v_found == false) {
		alert('Bạn chưa chọn ngày để xóa!');
	}
	document.frm_dsp_single_item.hdn_giai_doan_delete_list.value = v_list_row_delete;
}

function getEndDateByWeek(p_input_id, p_start_date_obj_id, p_end_date_obj_id)
{
    obj_input_date = $('#'+p_input_id);
    number_week = parseInt(obj_input_date.val());
    if (number_week > 0) {
        obj_start_date = $('#'+p_start_date_obj_id);
        v_start_date = obj_start_date.val();
        if (v_start_date == '') {
            obj_input_date.val('');
        } else {
            obj_end_date = $('#'+p_end_date_obj_id);

            v_start_date = parseDate(v_start_date);
            v_new_date = new Date(v_start_date);
            v_new_date.setDate(v_new_date.getDate() + number_week*7);

            obj_end_date.val(dateFormat(v_new_date, 'DD-MM-YYYY'));
        }
    }
}

function insert_element_from_oject_to_checkbox_list(p_obj, p_block_id, p_name)
{
	if (document.getElementById(p_block_id)) {
		if (p_obj.checked) {
			v_str = '<div id="'+p_name+'_'+p_obj.value+'"><label><input type="checkbox" name="'+p_name+'[]" value="'+p_obj.value+'" class="'+p_name+'" data-parent-selected="'+$(p_obj).attr('data-parent')+'" onclick ="chon_chuyen_muc_cap_2(this, \'data-parent-selected\');"/> '+$(p_obj).attr('data-label')+'</label></div>';
			$('#'+p_block_id).append(v_str);		
		} else {
			$('#'+p_name+'_'+p_obj.value).remove();
		}
	}
	$('input[data-parent="'+p_obj.value+'"]').each(function(i){
		$(this).attr("checked", p_obj.checked); 
		if (document.getElementById(p_block_id)) {
			insert_element_from_oject_to_checkbox_list(this,p_block_id, p_name);
		}
	}); 
}

/**
*  Ham thuc hien refresh lai man hinh cap nhat snippet rating khi onchange txt_event_id, txt_tag_id, txt_news_id
* @param  p_object_value:  gia tri nhap vao o textboxt ( event, tag, news)
* @param  p_go_to_url:  url hien thi chi tiet 1 snippet rating
* @param  p_url_goback:  url goback
*/
function snippet_rating_onchange(p_object_value, p_goto_url, p_goback_url)
{
	// kiem tra loai trang duoc chon
	var rad_val = '';
	for (var i=0; i < parent.document.frm_update_data.rad_loai_trang.length; i++)
	{
		if (parent.document.frm_update_data.rad_loai_trang[i].checked)
		{
			rad_val = parent.document.frm_update_data.rad_loai_trang[i].value;
			break;
		}
	}
	var v_url = p_goto_url+'?object_id='+p_object_value;
	v_url+= '&page_type='+rad_val;
	v_url+= '&goback='+p_goback_url;
	parent.document.location.href = v_url;
}

/**
*  Ham thuc hien refresh lai man hinh cap nhat snippet rating khi onchange rad_loai_trang
* @param  p_object_value:  trang duoc chon (news, tag, event)
* @param  p_go_to_url:  url hien thi chi tiet 1 snippet rating
* @param  p_url_goback:  url goback
*/
function page_type_onchange(p_object_value, p_goto_url, p_goback_url)
{
	var v_url = p_goto_url;
	v_url+= '?page_type='+p_object_value;
	v_url+= '&goback='+p_goback_url;
	parent.document.location.href = v_url;
}

/**
*  Ham thuc hien refresh lai man hinh cap nhat tag bai viet, tag chuyen muc
* @param  p_object_value:  trang duoc chon (news, tag, event)
* @param  p_go_to_url:  url hien thi chi tiet 1 snippet rating
* @param  p_url_goback:  url goback
*/
function rad_category_onchange(p_object_value, p_goto_url, p_goback_url)
{
	var v_url = p_goto_url;
	v_url+= '?cat_id='+p_object_value;
	v_url+= '&goback='+p_goback_url;
	parent.document.frm_update_data.action = v_url;
	parent.document.frm_update_data.target = "";
	parent.document.frm_update_data.submit();
}
/*
 * Ham chon check box sau khi AutoComplete
 * @author  phuonghv - 12/01/2013
 * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_id id cua hidden luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @return  string
 */

function chon_chuyen_muc_sau_khi_auto_complete(radio_id,hdn_id,hdn_tong,txt_input, p_goto_url, p_goback_url){
	var v_selected_value = document.getElementById(hdn_id).value;
	var i=0;
	var v_count = document.getElementById(hdn_tong).value;
	for( i= 0 ; i < v_count; i++) {
		if(document.getElementById(radio_id+i).value == v_selected_value){
			document.getElementById(radio_id+i).checked = true;
			document.getElementById(radio_id+i).focus();
			document.getElementById(txt_input).value = '';
			document.getElementById(txt_input).focus();
			rad_category_onchange(v_selected_value, p_goto_url, p_goback_url);
			break;
		}
	}
	return false;
}

/**
*  Ham thuc hien refresh lai man hinh cap nhat poll
* @param  p_object: check contrl
*/
function chk_of_category_onchange(p_object)
{
	checkToDisplay(p_object,"tr_note");
	checkToDisplay(p_object,"tr_giao_dien_chuyen_muc");
}

/**
*  Ham thuc hien preview giao dien poll
*  @param  p_object: check contrl
*/
function preview_giao_dien_poll(frm_name, radio_id,  p_goto_url, p_frm_target, p_total_row)
{
	for( i= 0 ; i < p_total_row; i++) {
		if (document.getElementById(radio_id+i).checked == true)
		{
			var v_id_giao_dien = document.getElementById(radio_id+i).value;
			p_goto_url+= v_id_giao_dien;
			frm_submit(frm_name, p_goto_url, p_frm_target);
			break;
		}
	}

}

/**
*  Ham thuc hien thay doi chieu rong giao dien poll minh hoa
*/
function poll_interface_change_style(p_object, p_id, p_style)
{
	var v_width = 210;
	var v_color = "D45BA0";
	switch(p_style) 
	{
		case "width":
			v_width = parseInt(p_object.value);
			v_width = (v_width < 210) ? 210: v_width;
			v_width = (v_width > 500) ? 500: v_width;
			document.getElementById(p_id).style.width = v_width+"px";	
			break;
		case "color":
			v_color = "#" + p_object.value;
			document.getElementById(p_id).style.borderColor = v_color;
			break;
		case "bgcolor":
			v_color = "#" + p_object.value;
			document.getElementById(p_id).style.backgroundColor = v_color;
			break;
		case "text_color":
			v_color = "#" + p_object.value;
			document.getElementById(p_id).style.color = v_color;
			break;	
	}	
}

function chk_all_category_onclick(frm){
	frm.action = 'ajax/user_category/act_chk_all_category_onclick/';
	frm.submit();
	frm.action = 'ajax/user_category/act_update_user_category/'+frm.user_id.value;
}

var tinytip=function(){
	var id = 'tt';
	var top = 0;
	var left = 0;
	var maxw = 400;
	var speed = 10;
	var timer = 10;
	var endalpha = 100;
	var alpha = 20;
	var tt,t,c,b,h;
	var ie = document.all ? true : false;
	return{
		show:function(v,w){
			if(tt == null){
				tt = document.createElement('div');
				tt.setAttribute('id',id);
				t = document.createElement('div');
				t.setAttribute('id',id + 'top');
				c = document.createElement('div');
				c.setAttribute('id',id + 'cont');
				b = document.createElement('div');
				b.setAttribute('id',id + 'bot');
				tt.appendChild(t);
				tt.appendChild(c);
				tt.appendChild(b);
				document.body.appendChild(tt);
				tt.style.opacity = 0;
				tt.style.filter = 'alpha(opacity=0)';
				document.onmousemove = this.pos;
			}
			tt.style.display = 'block';
			c.innerHTML = v;
			tt.style.width = w ? w + 'px' : 'auto';
			if(!w && ie){
				t.style.display = 'none';
				b.style.display = 'none';
				tt.style.width = tt.offsetWidth;
				t.style.display = 'block';
				b.style.display = 'block';
			}
			if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
			h = parseInt(tt.offsetHeight) + top;
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tinytip.fade(1)},timer);
		},
		pos:function(e){
			var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
			var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
			tt.style.top = (u-20) + 'px';
			//tt.style.left = (l - tt.offsetWidth) + 'px';
			tt.style.left = (l+15) + 'px';
		},
		fade:function(d){
			var a = alpha;
			if((a != endalpha && d == 1) || (a != 0 && d == -1)){
				var i = speed;
				if(endalpha - a < speed && d == 1){
					i = endalpha - a;
				}else if(alpha < speed && d == -1){
					i = a;
				}
				alpha = a + (i * d);
				tt.style.opacity = alpha * .01;
				tt.style.filter = 'alpha(opacity=' + alpha + ')';
			}else{
				clearInterval(tt.timer);
				if(d == -1){tt.style.display = 'none'}
			}
		},
		hide:function(){
			clearInterval(tt.timer);
			tt.timer = setInterval(function(){tinytip.fade(-1)},timer);
		}
	};
}();
/**
* Ham xu ly su kien khi click vao loai giao dien poll trong man hinh chi tiet poll
*/
function poll_interface_onclick(p_object) {
	if(v_arr_giao_dien){
		var v_poll_interface = 0;
		var v_poll_width = 0;
		var v_count = v_arr_giao_dien.length;
		for(i = 0; i < v_count; i++) { 
			var v_poll_interface = v_arr_giao_dien[i].pk_poll_interface;
			if(v_poll_interface == p_object.value) {
				v_poll_width = v_arr_giao_dien[i].c_chieu_rong*1;
				document.frm_update_data.txt_width1.value = v_poll_width;
				document.frm_update_data.txt_width2.value = v_poll_width;
			}
		}
	}
}

// Ham check all cac checkbox tren man hinh danh sach chon chuyen muc xuat ban
function check_all_category_by_select(p_frm, chk_object){
	var v_is_checked = chk_object.checked;
	var v_record_count = p_frm.hdn_record_count.value*1;
	for(var i = 0; i < v_record_count; i++){
		var p_check_obj = eval("p_frm.chk_item_id_"+i);
		if(p_check_obj){
			p_check_obj.checked = v_is_checked;
		}
        if(typeof v_select_main_cate !== 'undefined' && v_select_main_cate >0){
            create_html_main_cate_by_sub_cate(p_check_obj.value);
        }
	}
}
// Ham them su kien cho poll 20/04/2013
function frm_submit_event_by_checkbox_select(p_form_obj, p_target_id, p_has_count)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_event = new Array();
	for (i=0; i<v_rows; i++) {
        /* Begin 14/09/2017 Tytv fix_them_su_kien_trong_man_hinh_poll */
        v_id = eval(p_form_obj['chk_item_id_'+i]);
        v_name = eval(p_form_obj['hdn_event_'+i]);
		if(v_id && v_id.checked == true){
            v_count++;
            v_arr_event[v_arr_event.length] = new Array(v_name.value, v_id.value);
        }
        /* End 14/09/2017 Tytv fix_them_su_kien_trong_man_hinh_poll */
    }	
    if (v_count == 0 && p_has_count == true) {
        alert("Chưa có sự kiện nào được chọn!");
    } else {		
        openerResetSelectList(p_target_id);
        for (i in v_arr_event) {
            openerPutElementToSelectList(v_arr_event[i][0], v_arr_event[i][1], p_target_id);
        }        
	    p_form_obj.submit();
		window.close();
    }
    return false;
}

function insert_bai_lien_quan()
{
    v_str_before = '<div class="box-bai-lien-quan"><div class="green-box-bg marT8">';
    v_str_before += '<strong>Bài liên quan:</strong><div class="baiviet-bailienquan">';
    v_str_after = '</div></div></div>';
    v_content = CKEDITOR.instances.txt_bai_lien_quan.getData();
    //xoa thẻ p thừa không có số liệu               
    v_content = v_str_before + v_content.replace(/<p><\/p>/gi, "") + v_str_after;    
    CKEDITOR.instances.txt_body.insertHtml(v_content);
}

/**
*  Ham thuc hien check đã sửa thông tin thời tiết 1 ngày trang quản trị thông tin thời tiết
*  @param  p_checkbox_tinh_va_ngay_id: id checkbox theo tỉnh thành và ngày được sửa
*/
function set_chinh_sua_thong_tin_thoi_tiet(p_checkbox_tinh_va_ngay_id) 
{
	if (document.getElementById(p_checkbox_tinh_va_ngay_id)) {
		document.getElementById(p_checkbox_tinh_va_ngay_id).checked=true;
	}
	// báo cho hàm sumit biết đã có chỉnh sửa trong trang
	if (document.getElementById('chk_item_id0')) {
		document.getElementById('chk_item_id0').checked=true;
	}
}

/**
*  Ham thuc hien check đã sửa thông tin tỷ giá 1 ngày trang quản trị thông tin tỷ giá
*  @param  p_checkbox_ngoai_te_va_ngay_id: id checkbox theo ngoại tệ và ngày được sửa
*/
function set_chinh_sua_thong_tin_ty_gia(p_checkbox_ngoai_te_va_ngay_id) 
{
	if (document.getElementById(p_checkbox_ngoai_te_va_ngay_id)) {
		document.getElementById(p_checkbox_ngoai_te_va_ngay_id).checked=true;
	}
	// báo cho hàm sumit biết đã có chỉnh sửa trong trang
	if (document.getElementById('chk_item_id0')) {
		document.getElementById('chk_item_id0').checked=true;
	}
}


/**
*  Ham thuc hien hiển thị danh sách kênh đã lấy tự động và ghi lại thành công trên trang quản trị truyền hình
*  @param  p_kenh: mã kênh vừa ghi lại dữ liệu
*/
function set_thong_bao_ds_kenh_da_lay_tu_dong(p_kenh) 
{
	var html = $('#div_thong_bao_kenh_da_lay').html();
	html = html.replace('chưa có kênh nào', '');
	$('#div_thong_bao_kenh_da_lay').html(html + ' .' + p_kenh);
}

/*
	Ham submit form tim kiem trong chuc nang quan ly script header
*/
function header_script_submit(p_form) {
	v_tu_ngay = $('#txt_tu_ngay').val();
	v_den_ngay  = $('#txt_den_ngay').val();
	if(v_tu_ngay !='' && v_den_ngay !='') {
		v_startDate = parseDate(v_tu_ngay).getTime();
		v_endDate = parseDate(v_den_ngay).getTime();
		if (v_startDate > v_endDate){
			alert("Ngày bắt đầu không được lớn hơn ngày kết thúc");
			document.getElementById('txt_tu_ngay').focus();
			return false;
		}
	}	
	p_form.submit();
}

function add_tinh_huong_tuong_thuat(p_type, p_position)
{
    p_id = (typeof p_id == 'undefined') ? 0 : p_id;
    v_str_phut_input_width = (p_type == 2) ? 'style="width:100px"' : '';
    v_html = '<tr>';
    v_html += '<td><input type="text" name="txt_order[]" class="score" /></td>';
    v_html += (p_type == 1 || p_type == 2) ? '<td><input type="text" name="txt_tinh_huong[]" class="score" '+v_str_phut_input_width+' /><input type="hidden" name="hdn_set[]" value="0" /></td>' : '';
    v_html += '<td><input type="text" placeholder="Nhập tiêu đề tình huống(Ví dụ: TIN BÃO MỚI NHẤT)" name="txt_tinh_huong_tuong_thuat[]" style="width:560px;height: 20px;" autocomplete="off" />&nbsp;&nbsp;'+v_html_text_color_title+'<br /><br />';
    v_html += '<textarea id="txt_binh_luan_'+p_id+'" name="txt_binh_luan[]"></textarea>';
    v_html +='<br />';
    // Kiểm tra html icon tường thuật có tồn tại
    if(typeof v_html_icon_tinh_huong !== 'undefined'){
        v_html +=v_html_icon_tinh_huong;
    }
    /* Begin: 6-6-2019 TuyenNT: bo_sung_tinh_nang_chen_tin_lien_quan_tinh_huong_bai_tuong_thuat */
     v_html += '<input type="checkbox" value="1" name="txt_tinh_huong_noi_bat[]" /><span class="redText">Đánh dấu là tình huống nổi bật</span> <a style="float: right; font-size: 13px;" class="linkChonBaiLienQuan" href="javascript:void(0)" onclick="openWindow(\''+CONFIG.BASE_URL+'news_common/dsp_published_news_by_select/txt_bai_lien_quan_noi_dung_bai_viet_tuong_thuat_24h?id_tuong_thuat='+p_id+'\',950,600,false)">Chọn bài liên quan</a><br /> </td>';
    /* End: 6-6-2019 TuyenNT: bo_sung_tinh_nang_chen_tin_lien_quan_tinh_huong_bai_tuong_thuat */ 
    /* Begin 09/08/2017 Tytv toi_uu_chuc_nang_nhap_bai_video */
    if (p_type != 2) {
        v_html += '<td><div class="padBot">Video cho web (mp4)<br /><input type="file" name="file_video[]" id="file_video'+p_id+'" onchange="check_dung_luong_video_truoc_khi_upload(this)" /></div><div class="padBot" style="display: none">Video mobile (3gp)<br /><input type="file" name="file_video_mobile[]" /></div><div class="padBot" style="display: none">Video mobile (mp4)<br /><input type="file" name="file_video_mobile_hd[]" /></div></td>';
    } else {
        v_html += '<td style="display: none"><div class="padBot">Video mobile (3gp)<br /><input type="text" name="txt_video_mobile[]" /></div><div class="padBot">Video mobile (mp4)<br /><input type="text" name="txt_video_mobile_hd[]" /></div></td>';
    }
    
    
    v_html += '</tr>';
    if (p_position == 'top') {
        $('#tableContent').prepend(v_html);
    } else {
        $('#tableContent').append(v_html);
    }
    // AnhTT - Begin - 21/5/2020 - them_upload_video_tuong_thuat
    // editor tường thuật tin tức
    if(p_type === 2){
        CKEDITOR.replace('txt_binh_luan_'+p_id, {baseHref:CONFIG.BASE_URL,width: 678,height: 100,customConfig: CONFIG.BASE_URL+'editor/ckeditor/custom/standard_config.js',toolbar: 'SimpleVideo'});
    }else{
        CKEDITOR.replace('txt_binh_luan_'+p_id, {baseHref:CONFIG.BASE_URL,width: 678,height: 100,customConfig: CONFIG.BASE_URL+'editor/ckeditor/custom/standard_config.js',toolbar: 'Basic'});
    }
    // AnhTT - Begin - 21/5/2020 - them_upload_video_tuong_thuat
    /* End 09/08/2017 Tytv toi_uu_chuc_nang_nhap_bai_video */
    p_id++;
}

function add_set_dau(p_position)
{
    v_html = '<tr>';
    v_html += '<td><input type="text" name="txt_order[]" style="width:40px;background-color:#ffffcc" /><input type="hidden" name="hdn_set[]" value="1" /></td>';
    v_html += '<td><b>Tên set</b></td>';
    v_html += '<td><input type="text" name="txt_tinh_huong[]" style="width:550px;background-color:#ffffcc" /><input type="hidden" name="txt_binh_luan[]" value="" /></td>';
    v_html += '<td></td>';
    v_html += '</tr>';
    if (typeof p_position !== 'undefined' && p_position == 'top') {
        $('#tableContent').prepend(v_html);
    }else{
        $('#tableContent').append(v_html);
    }
}

/* Begin: 27-5-2019 TuyenNT toi_uu_sau_trien_khai_cac_van_de_bai_tuong_thuat_tintuc_thethao_bongda */
function add_score()
{
    v_html = '<div class="padBot"><input type="text" placeholder="Tie Break" name="txt_tie_break_1[]" value="" style="margin-right: 2px;width:40px" /><input type="text" name="txt_ban_thang_1[]" value="" style="width:40px" /> - <input type="text" name="txt_ban_thang_2[]" value="" style="width:40px" /><input type="text" name="txt_tie_break_2[]" value="" placeholder="Tie Break" style="width:40px;margin-left: 2px;" /></div>';
    $('#score').append(v_html);
}
/* End: 27-5-2019 TuyenNT toi_uu_sau_trien_khai_cac_van_de_bai_tuong_thuat_tintuc_thethao_bongda */

function add_upload_input(p_block_id, p_input_name)
{
    v_block = document.getElementById(p_block_id);
    v_block.innerHTML = '<input type="file" name="'+p_input_name+'" id="'+p_input_name+'" onchange="check_dung_luong_video_truoc_khi_upload(this)" />';
}

/**
*  Ham them mot file tai bai viet goc
* @param  p_id:  la id file
*
*/
function add_row_file(p_id){
	// Lay so luong giai doan xuat ban
	var table_id = "tbl_file_list";
	var v_row_index = document.frm_dsp_single_item.hdn_count_file.value;	
	v_row_index =(v_row_index=="")? "0":v_row_index;
	var tbody = document.getElementById(table_id).tBodies[0];
	var row = document.createElement("TR"); // Tao mot the TR
	row.setAttribute("id","row"+v_row_index);
	//Cell 1
	//checkbox
	var cell1 = document.createElement("TD");
	cell1.setAttribute("id","col1"+v_row_index);
	cell1.innerHTML = '<input type="checkbox" name="chk_file'+v_row_index*1+'" value ="'+p_id+'" />';
	
    //Ngay bat dau
	var cell2 = document.createElement("TD");
	cell2.setAttribute("id","col2"+v_row_index);
	cell2.innerHTML = '<input type="file" name="file_bai_viet_goc'+v_row_index*1+'" /><input type ="hidden" name = "hdn_file_bai_viet_goc'+v_row_index*1+'" value = ""/><span class ="redText"><i>Định dạng file .doc,.docx, dung lượng tối đa 1 MB</i></span>';
	// Add td to tr
	row.appendChild(cell1);
    row.appendChild(cell2);
	tbody.appendChild(row);
	v_row_index = v_row_index*1 +1;
	document.frm_dsp_single_item.hdn_count_file.value = v_row_index;
}

/**
*  Ham xoa mot hay nhieu file bai viet
*
*/
function del_row_file(){
	var v_found = false;
	var table_id = "tbl_file_list";
	var v_row_index = document.frm_dsp_single_item.hdn_count_file.value;
	var tbl_file = document.getElementById(table_id);
	var v_list_row_delete = document.frm_dsp_single_item.hdn_delete_file_list.value;
	// tim index cua dong duoc danh dau xoa.
	var row_id;
	for(var i =0; i<v_row_index ; i++){
		var chk_obj = eval("document.frm_dsp_single_item.chk_file"+i);
		if(chk_obj && chk_obj.checked){
			row_id = "row"+i;		
			var rowToDelete = document.getElementById(row_id);
			if (rowToDelete) {				
				rowToDelete.parentNode.removeChild(rowToDelete); 
				// Ghi lai index dong bi xoa
				if(v_list_row_delete ==""){
					v_list_row_delete = i;
				}
				else {
					v_list_row_delete+= ","+i;
				}
				v_found = true;
			}
		}
	}
	if (v_found == false) {
		alert('Bạn chưa chọn file để xóa!');
	}
	document.frm_dsp_single_item.hdn_delete_file_list.value = v_list_row_delete;
}

/**
*  Ham chon user vao selectbox
*
*/
function frm_submit_select_user(p_form_obj, p_target_id, p_has_count)
{
	v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_user = new Array();
	for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id'+i];
        v_name = p_form_obj['hdn_user_name_'+i];
        if (v_id.checked) {
            v_count++;
            v_arr_user[v_arr_user.length] = new Array(v_name.value, v_id.value);
        }
    }	
    if (v_count == 0 && p_has_count == true) {
        alert("Chưa có BTV/CTV nào được chọn!");
    } else {		
        //openerResetSelectList(p_target_id);
        for (i in v_arr_user) {
            openerPutElementToSelectList(v_arr_user[i][0], v_arr_user[i][1], p_target_id);
        }        
	    p_form_obj.submit();
		window.close();
    }
    return false;
}

function toggleElement(elementID)
{
        tblObj = document.getElementById(elementID);
        tblObj.style.display = (tblObj.style.display=='none') ? '' : 'none';
}

function openVideo(phut)
{
    currentObj = document.getElementById('video_'+phut);
    if (!isIE()) {
        currentObj.innerHTML = currentObj.innerHTML;
        currentObj.style.display = (currentObj.style.display=='none') ? '' : 'none';
        btnObj = document.getElementById('xem_video_'+phut);
        btnObj.innerHTML = (btnObj.innerHTML=='Xem video') ? 'Đóng video' : 'Xem video';
    } else {
        currentObj.style.display = '';
    }
}

function btn_import_onclick(p_forms, p_action_url, p_target, p_confirm_message, p_class_button){
	v_class_button = (typeof(p_class_button)==='undefined')? '':p_class_button;
	v_is_ok = false;
	if(confirm(p_confirm_message))
	{
		frm_submit(p_forms, p_action_url, p_target) ;
		v_is_ok = true;
	}	 
	if (!v_is_ok && v_class_button != '') {
		set_enable_link(v_class_button);
	}
}

/*
	Ham xu ly su kien onclick vao check box tinh thanh
*/
function chk_city_onclick(p_url, p_goback_url){
	p_url += "?data="+$.checked_box_city()+"&goback="+p_goback_url;
	parent.document.frm_update_data.action = p_url;
	parent.document.frm_update_data.target = "";
	parent.document.frm_update_data.submit();
}

function frm_submit_select_giai_dau(p_form_obj,p_giai_dau_display, p_is_editor)
{
	v_loai_du_lieu = getRadioButtonValue(p_form_obj.rad_loai_du_lieu);	
	v_width = 210;
	v_height = 200;
	if (!v_loai_du_lieu) {
        alert('Chưa có loại dữ liệu nào được chọn!');
        return false;		
    }  
	v_content = document.getElementById('view_loai_du_lieu_'+v_loai_du_lieu).innerHTML;
	
	if (typeof p_giai_dau_display != undefined && p_giai_dau_display != '') { 		
		if (p_is_editor == true) {
            oEditor = window.opener.CKEDITOR.instances[p_giai_dau_display];
			oEditor.insertHtml(v_content);
        } else {
            window.opener.document.getElementById(p_giai_dau_display).innerHTML = v_content;
        }
    } 
    window.close();
    return false;
}

/*
* Ham an nut lenh khi nhan nut cap nhat
* param p_object_class : ten class cua nut lenh 
* param p_class_button : ten class cua doi tuong chua cac nut lenh ( tr, div, ...)
*/
function set_disable_link(p_object_class, p_class_button)
{
    $(document).ready(function() {
        $(function() {
            $("."+p_object_class).click(function(){
				 $('.'+p_class_button).hide();	// an doi tuong chua cac nut lenh 			
			});
        });
    });
}

/*
* Ham hien thi cac nut lenh khi co loi xay ra trong qua trinh cap nhat
* param p_class_button : ten class cua doi tuong chua nut lenh  ( tr, div, ...)
*/
function set_enable_link(p_class_button) {
	 $('.'+p_class_button).show();	
     // Begin TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan
     close_loading_overlay();
     // End TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan	
}

function bank_istop_onclick(p_frm, p_check_obj) {
	var v_is_checked = p_check_obj.checked;
	var v_record_count = p_frm.hdn_record_count.value*1;
	for(var i = 0; i < v_record_count; i++){
		var v_check_obj = eval("p_frm.chk_bank_istop"+i);
		if(v_check_obj && v_is_checked){
			v_check_obj.checked = false;			 		
		}
	}
	p_check_obj.checked = v_is_checked;
}

/**
*  Ham them mot icon cho block cua box rss
* @param  p_index:  index cua block
*
*/
function add_row_box_xml_icon(p_index, p_icon_id){
	// Lay so luong giai doan xuat ban
	var table_id = "tbl_list_icon"+p_index;
	var v_row_index = eval("document.frm_update_data.hdn_count_icon"+p_index+".value");	
	v_row_index =(v_row_index=="")? "0":v_row_index;
	var tbody = document.getElementById(table_id).tBodies[0];
	var row = document.createElement("TR"); // Tao mot the TR
	row.setAttribute("id","row"+p_index+'_'+v_row_index);
	
	//Cell 1
	//checkbox
	var cell1 = document.createElement("TD");
	cell1.setAttribute("id","col1"+p_index+'_'+v_row_index);
	cell1.innerHTML = '<input type="checkbox" name="chk_icon'+p_index+'_'+v_row_index*1+'" value ="'+p_icon_id+'" />';
	
    //Ngay bat dau
	var cell2 = document.createElement("TD");
	cell2.setAttribute("id","col2"+p_index+'_'+v_row_index);
	cell2.innerHTML = '<table border="0" cellpadding="3" cellspacing="0" width="100%"><tr><td width="128" class="tbLabel">Ch&#7885;n &#7843;nh icon:</td><td><input type="file" name="file_icon_'+p_index+'_'+v_row_index+'" /><span class ="redText"><em>&#7842;nh jpg, gif, flash</em></span></td></tr><tr><td class="tbLabel">K&#237;ch c&#7905; icon:</td><td>&#272;&#7897; r&#7897;ng <input type="text" name="txt_icon_width_'+p_index+'_'+v_row_index+'" style="width:50px" />&nbsp;&nbsp;&nbsp;&#272;&#7897; cao <input type="text" name="txt_icon_height_'+p_index+'_'+v_row_index+'" style="width:50px" /></td></tr><tr><td class="tbLabel">Link icon:</td><td><input type="text" name="txt_icon_link_'+p_index+'_'+v_row_index+'" style="width:310px;" /></td></tr><tr><td class="tbLabel">Th&#7913; t&#7921;:</td><td><input type="text" name="txt_icon_order_'+p_index+'_'+v_row_index+'" style="width:50px;" /><span class ="redText"><em>L&#224; s&#7889; nguy&#234;n d&#432;&#417;ng</em></span></td></tr></table><hr style="border:1px solid #CCC" width="100%">';
	// Add td to tr	
	row.appendChild(cell1);
    row.appendChild(cell2);
	tbody.appendChild(row);
	v_row_index = v_row_index*1 +1;
	eval("document.frm_update_data.hdn_count_icon"+p_index+".value = "+v_row_index);
}

/**
*  Ham xoa mot hay nhieu file bai viet
*
*/
function del_row_box_xml_icon(p_index){
	var v_found = false;
	var table_id = "tbl_list_icon"+p_index;
	var v_row_index = eval("document.frm_update_data.hdn_count_icon"+p_index+".value");	
	var tbl_file = document.getElementById(table_id);
	var v_list_row_delete = eval("document.frm_update_data.hdn_delete_icon_list"+p_index+".value");
	// tim index cua dong duoc danh dau xoa.
	var row_id;
	for(var i =0; i<v_row_index ; i++){
		var chk_obj = eval("document.frm_update_data.chk_icon"+p_index+'_'+i);
		if(chk_obj && chk_obj.checked){
			row_id = "row"+p_index+'_'+i;		
			var rowToDelete = document.getElementById(row_id);
			if (rowToDelete) {				
				rowToDelete.parentNode.removeChild(rowToDelete); 
				// Ghi lai index dong bi xoa
				if(v_list_row_delete ==""){
					v_list_row_delete = i;
				}
				else {
					v_list_row_delete+= ","+i;
				}
				v_found = true;
			}
		}
	}
	if (v_found == false) {
		alert('Bạn chưa chọn icon để xóa!');
	}
	eval("document.frm_update_data.hdn_delete_icon_list"+p_index+".value = '" + v_list_row_delete+"'");	
}
/**
*  Ham them mot video mobile url cho tin bai
* @param  p_index:  index cua block
*
*/
function add_row_video_mobile(p_row_id){
	// Lay so luong giai doan xuat ban
	var table_id = "tbl_"+p_row_id+"_list";
	var v_row_index = eval("document.frm_dsp_single_item.hdn_"+p_row_id+"_count.value");	
	v_row_index =(v_row_index=="")? "0":v_row_index;
	var tbody = document.getElementById(table_id).tBodies[0];
	var row = document.createElement("TR"); // Tao mot the TR
	row.setAttribute("id","row_" + p_row_id + '_' + v_row_index);	
	//Cell 1
	//checkbox
	var cell1 = document.createElement("TD");
	cell1.setAttribute("id","col1_"+p_row_id+'_'+v_row_index);
	cell1.innerHTML = '<input type="checkbox" name="chk_'+p_row_id+'_'+v_row_index*1+'" value ="1" />';	 
	var cell2 = document.createElement("TD");
	cell2.setAttribute("id","col2_"+p_row_id+'_'+v_row_index);	
	cell2.innerHTML = '<input type="text" name="txt_'+p_row_id+'_'+v_row_index*1+'" value="" style="width:290px"/>';
    var cell3 = document.createElement("TD");
	cell3.setAttribute("id","col2_"+p_row_id+'_'+v_row_index);	
	cell3.innerHTML = '<input type="text" name="txt_video_group_'+v_row_index*1+'" value="" style="width:20px" title="Nhóm video cùng vị trí" />';
	// Add td to tr	
	row.appendChild(cell1);
    row.appendChild(cell2);
    row.appendChild(cell3);
	tbody.appendChild(row);
	v_row_index = v_row_index*1 +1;
	eval("document.frm_dsp_single_item.hdn_"+p_row_id+"_count.value = "+v_row_index);
}

/**
*  Ham xoa mot hay nhieu file bai viet
*
*/
function del_row_video_mobile(p_row_id){
	var v_found = false;
	var table_id = "tbl_"+p_row_id+"_list";
	var v_row_index = eval("document.frm_dsp_single_item.hdn_"+p_row_id+"_count.value");
	var tbl_file = document.getElementById(table_id);
	var v_list_row_delete = eval("document.frm_dsp_single_item.hdn_"+p_row_id+"_delete_list.value");
	// tim index cua dong duoc danh dau xoa.
	var row_id;
	for(var i =0; i<v_row_index ; i++){
		var chk_obj = eval("document.frm_dsp_single_item.chk_"+p_row_id+"_"+i);
		if(chk_obj && chk_obj.checked){
			row_id = "row_"+p_row_id+'_'+i;		
			var rowToDelete = document.getElementById(row_id);
			if (rowToDelete) {				
				rowToDelete.parentNode.removeChild(rowToDelete); 
				// Ghi lai index dong bi xoa
				if(v_list_row_delete ==""){
					v_list_row_delete = i;
				}
				else {
					v_list_row_delete+= ","+i;
				}
				v_found = true;
			}
		}
	}
	if (v_found == false) {
		alert('Bạn chưa chọn video url để xóa!');
	}
	eval("document.frm_dsp_single_item.hdn_"+p_row_id+"_delete_list.value = '" + v_list_row_delete+"'");	
}

// Kiem tra phan tu the_element co trong danh sach the_list hay khong
function list_have_element(the_list,the_element, the_separator)
{
	try{
		if (the_list=="") return -1;
		if (the_list==the_element) return 1;
		if (the_list.indexOf(the_separator)==-1) return -1;
		arr_value = the_list.split(the_separator);
		for(var i=0;i<arr_value.length;i++){
			if (arr_value[i]==the_element){
				return i;
			}
		}
	}catch(e){;}
	return -1;
}
// Ham gan gia tri cho o textbox
function putElementToTextbox(elementValue, p_textbox_id)
{
	var objTextbox = window.document.getElementById(p_textbox_id);
	if (!objTextbox) {
		return false;
	}
	objTextbox.value = elementValue;
}
// Ham xu ly xu kien nhan vao nut chon tren cua so popup them moi/cap nhat video mobile
function frm_submit_select_video_mobile(p_form_obj, p_target_id)
{
	v_video_url_list = '';
	v_rows = p_form_obj.hdn_video_mobile_count.value;
	list_delete_row = p_form_obj.hdn_video_mobile_delete_list.value;
	v_count = 0;
    v_arr_group = new Array();
   
    for (i=0; i<v_rows; i++) {
        v_object = p_form_obj['txt_video_mobile_'+i];
        v_group = p_form_obj['txt_video_group_'+i];
		if(v_object) {
			v_video_url = v_object.value;
			if (list_have_element(list_delete_row,i, ',')==-1 && v_video_url !='') {// Neu chua co trong danh sach xoa
				v_video_url_list += (v_video_url_list=='')? v_video_url:','+v_video_url;
                if (v_group.value*1 > 0) {
                    if (v_arr_group[v_group.value] != undefined) {
                        v_arr_group[v_group.value] += '_' + i;
                    } else {
                        v_arr_group[v_group.value] = i;
                    }
                }
			}
		}
	}
    v_str_group = '';
    for (i in v_arr_group) {
        v_str_group += v_arr_group[i]+'/';
    }
    v_str_group = v_str_group.substring(0, v_str_group.length-1);
    if (v_str_group != '') {
        v_video_url_list += '?group='+v_str_group;
    }
    window.opener.putElementToTextbox(v_video_url_list, p_target_id);   
	window.close();
	return false;	
}


/*
 * Ham gan gia tri duoc chon cho selectbox sau khi AutoComplete
 * @author  ducnq - 04/10/2012
 * @param  select_id id cua selectbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @return  string
 */
function set_selected_index_to_selectbox(select_id, hdn_id, txt_input, is_submit, form_id){
	var id_duoc_chon = document.getElementById(hdn_id).value;
	$('#'+select_id).val(id_duoc_chon);
	$('#'+txt_input).val('');
	if(is_submit == 1) {
			eval("document."+form_id+".submit()");
	}
	return false;
}

/*
 * Ham xu ly khi check vao chuyen muc cap 1 duoc chon thi tu dong chon cac chuyen muc cap 2 khi xuat ban banner, background
 * @author  phuonghv - 29/09/2013
 * @param  p_obj checkbox
 */
 
function chon_chuyen_muc_cap_2(p_obj, p_attr){
	$(p_obj).attr("checked", p_obj.checked); 	
	$('input['+p_attr+'="'+p_obj.value+'"]').each(function(i){
		$(this).attr("checked", p_obj.checked); 		
	}); 
}

/*
 * Ham chon check box sau khi AutoComplete
 * @author  phuonghv - 02/10/2013
 * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  form_id Nhap ten form 
 * @param  p_attr thuoc tinh cua checkbox
 * @return  string
 */
function selected_category_publish_banner(checkbox_id, hdn_id, hdn_tong, txt_input,form_id, p_attr){
	var id_duoc_chon = document.getElementById(hdn_id).value;
	var i=0;
	var so_tinh = document.getElementById(hdn_tong).value;
	for(i=0;i<so_tinh;i++){
		var p_check_obj = document.getElementById(checkbox_id+'['+i+']');
		if(p_check_obj.value == id_duoc_chon){
            if (!p_check_obj.checked) {
                p_check_obj.checked = true;
                insert_element_from_oject_to_checkbox_list(p_check_obj, 'block_checkbox_thu_phi', 'sel_category_thu_phi');
            }
			p_check_obj.focus();
			document.getElementById(txt_input).value = '';
			document.getElementById(txt_input).focus();		
			if(p_attr!='') {
				chon_chuyen_muc_cap_2(p_check_obj, p_attr);	// goi ham tu dong check chuyen muc cap 2
			}
		}
	}
	return false;
}

/*
 * Ham tinh tong so huy chuong 
 * @author  phuonghv - 02/10/2013
 */
function tinh_tong_huy_chuong(p_index) {
    v_huy_chuong_vang = $('#txt_gold'+p_index).val();
    v_huy_chuong_bac = $('#txt_silver'+p_index).val();
    v_huy_chuong_dong = $('#txt_bronze'+p_index).val();
    if (isNaN(v_huy_chuong_vang)) {
        alert('Số huy chương vàng phải là số nguyên dương.');
        return;
    }
    if (isNaN(v_huy_chuong_bac)) {
        alert('Số huy chương bạc phải là số nguyên dương.');
        return;
    }
    if (isNaN(v_huy_chuong_dong)) {
        alert('Số huy chương đồng phải là số nguyên dương.');
        return;
    }
    v_tong_cong =  parseInt(v_huy_chuong_vang) + parseInt(v_huy_chuong_bac) + parseInt(v_huy_chuong_dong);
    $('#txt_total'+p_index).val(v_tong_cong);    
}

// ********* CAC HAM CHO QUAN LY NHUAN BUT ************

/* 
* Ham gui message tu parent window
*/
function qlnb_call_check_data_nb(p_id_button_submit) {
    //  18-10-2021 DanNC begin Bổ sung popup xác nhận thao tác
    v_id_button_submit = p_id_button_submit;
    if (v_id_button_submit !== '' && v_id_button_submit !== null && v_id_button_submit !== undefined) {
        var text = '';
        if (v_id_button_submit == 'button_gui_bien_tap_lai') {
            var text = 'Bạn có muốn lưu thông tin thay đổi và Gửi biên tập lại bài không?';
        }
        if (v_id_button_submit == 'button_gui_duyet_lai') {
            var text = 'Bạn có muốn lưu thông tin thay đổi và Gửi duyệt lại bài không?';
        }
        if (text != '') {
            const txt = confirm(text);
            if(!txt){
                return false;
            }
        }
    }
    //  18-10-2021 DanNC end Bổ sung popup xác nhận thao tác
    // phuonghv add 03/09/2014
    // kiem tra noi dung bai viet la bai video 
	v_has_video = 0;
	if (document.getElementById("txt_body")){
		v_noi_dung_bai_viet = $("#txt_body").val();
		v_has_video = v_noi_dung_bai_viet.indexOf("flashWrite")>0? 1: 0;
	}
      
	if (document.getElementById("ifr_qlnb")) {
		ifr_qlnb = document.getElementById("ifr_qlnb").contentWindow;
		ifr_qlnb.postMessage('CHECK_DATA_NB;'+ v_has_video, "*");
	} else {
		if (typeof(v_id_button_submit) != undefined && v_id_button_submit != '') {
			document.getElementById(v_id_button_submit).click();                            
		}
	}
}

/* 
* Ham nhan message tra ve tu iframe
*/
function qlnb_check_data_nb(event) {
    if (event.origin == event.origin.replace('qlnb.24h.com.vn', '')) {
		// check không phải QLNB trả về thì thoát
		alert('E1:' + event.origin + ':' + event.data);
		return;
	}

	v_data = event.data;
	v_arr_data = v_data.split(';');
	v_data_length = v_arr_data.length;
	  
	if (v_data == 'QLNB_SUBMIT_OK' || v_data.indexOf("QLNB_SUBMIT_OK") >= 0) {
		if (typeof(url_redirect_news) != 'undefined' && url_redirect_news != '') {
			top.location.href = url_redirect_news;
			return;
		} else {
			top.window.history.back();
			return;
		}
	} else {
		if (v_data.substr(0, 4) == 'QLNB') {
			alert(v_data);
			set_enable_link("tr_button");
			return;
		}
	}
	
	if (v_data_length < 9) {
		// phải có đủ 9 tham số trả về
		alert('E2:' + event.origin + ':' + event.data);
		$('#block_nhuan_but_bai_viet').show();
		return;
	}
	  
	v_co_the_sua = parseInt(v_arr_data[0]);
	v_sel_doi_tuong_btv_ctv = parseInt(v_arr_data[1]);
	v_sel_loai_bai_sxkt = parseInt(v_arr_data[2]);
	v_sel_chuyen_muc_nhuan_but = parseInt(v_arr_data[3]);
	v_sel_nhom_theo_tin_bai = parseInt(v_arr_data[4]);
	v_hdn_tong_btv_ctv_thuc_hien_bai = parseInt(v_arr_data[5]);
    //phuonghv add 03/09/2014
    v_has_video = parseInt(v_arr_data[6]);
	v_valid_btv_ctv_bai_video = parseInt(v_arr_data[7]);
    v_ds_id_btv_ctv_thuc_thuc_hien_bai_video = v_arr_data[8];
    
	if (v_co_the_sua == 1) {
		if (v_sel_doi_tuong_btv_ctv <= 0) {
			alert('QLNB: Chưa chọn đối tượng BTV-CTV cho tin bài!');
			$('#block_nhuan_but_bai_viet').show();
			return;
		}
		if (v_sel_loai_bai_sxkt <= 0) {
			alert('QLNB: Chưa chọn loại bài sản xuất khai thác cho tin bài!');
			$('#block_nhuan_but_bai_viet').show();
			return;
		}
		if (v_sel_chuyen_muc_nhuan_but <= 0) {
			alert('QLNB: Chưa chọn chuyên mục tính nhuận bút cho tin bài!');
			$('#block_nhuan_but_bai_viet').show();
			return;
		}
		if (v_sel_nhom_theo_tin_bai <= 0) {
			alert('QLNB: Chưa chọn nhóm tính nhuận bút cho tin bài!');
			$('#block_nhuan_but_bai_viet').show();
			return;
		}
		if (v_hdn_tong_btv_ctv_thuc_hien_bai <= 0) {
			alert('QLNB: Chưa chọn BTV-CTV thực hiện tin bài!');
			$('#block_nhuan_but_bai_viet').show();
			return;
		}
        //phuonghv add 03/09/2014
        // if (v_has_video == 1 && v_valid_btv_ctv_bai_video <=0) {
            // alert("QLNB: Chưa chọn đúng BTV-CTV thực hiện tin bài video.\n(Chọn 1 trong các ID btv-ctv sau:"+v_ds_id_btv_ctv_thuc_thuc_hien_bai_video+")!");
			// $('#block_nhuan_but_bai_viet').show();
			// return;
        // }
	}
	  
	if (typeof(v_id_button_submit) != undefined && v_id_button_submit != '') {
		document.getElementById(v_id_button_submit).click();                            
	}
}

// function js này anh gọi tới sau khi đã lưu được tin bài trên OCM 24h/eva và đã lấy được ID tin bài, tạo được time và key submit
function qlnb_submit_save_data(p_id_tin_bai_ocm, p_time, p_key_by_time) {
	ifr_qlnb = document.getElementById("ifr_qlnb").contentWindow;
	ifr_qlnb.postMessage('SUBMIT;' + p_id_tin_bai_ocm + ';' + p_time + ';' + p_key_by_time, "*");
}

function get_thong_tin_nb(p_news_id, p_reload_form_ttnb) {
	var v_arr_temp = new Array();
	objSelectList = window.document.getElementById('sel_category_list');
    for (var i=0; i<objSelectList.options.length; i++) {
		v_arr_temp[v_arr_temp.length] = objSelectList.options[i].value;
    }
	v_category_list = v_arr_temp.join(',');
	if (v_category_list == '') {
		alert('Bạn chưa chọn chuyên mục xuất bản!');
		return;
	}
	if ($('#ifr_qlnb').length == 0 || p_reload_form_ttnb == true) {
        v_is_pr = 0;
        //Begin 26-05-2016 : trungcq xu_ly_nhuan_but_bai_pr_gia_re
        //Begin 04-10-2016 : trungcq xu_ly_nhuan_but_bai_pr_lien_quan
        if ($('#chk_pr_dau_trang').prop('checked') || $('#chk_pr_uu_tien').prop('checked') || $('#chk_pr_tu_van').prop('checked') || $('#chk_pr_gia_re').prop('checked') || $('#chk_pr_lien_quan').prop('checked') || $('#chk_pr_chuyen_sau').prop('checked') || $('#chk_pr_long_ghep').prop('checked') || $('#chk_pr_trong_muc').prop('checked') || $('#chk_pr_link_dac_biet').prop('checked') || $('#chk_pr_nhan_hang').prop('checked')) {
            v_is_pr = 1;
        }
        //End 04-10-2016 : trungcq xu_ly_nhuan_but_bai_pr_lien_quan
        //End 26-05-2016 : trungcq xu_ly_nhuan_but_bai_pr_gia_re
		var v_url = CONFIG.BASE_URL+'ajax/news/dsp_sua_thong_tin_nb/'+p_news_id+'/'+v_category_list+'/'+v_is_pr;
		$.get(v_url, function(data) {
			$('#block_nhuan_but_bai_viet').html(data).show();
			
		});
		//document.getElementById("chk_bo_qua_ttnb").checked = false;
	} 
	$('#block_nhuan_but_bai_viet').toggle();
}

function get_thong_tin_nb_khi_thay_doi_chuyen_muc(p_news_id) {
	get_thong_tin_nb(p_news_id, true);
}

function chon_chuyen_muc_xuat_ban_tin_bai(p_news_id) {
    function show_popup_chon_chuyen_muc_inner() {
        var v_title = '';
        if (document.frm_dsp_single_item && document.frm_dsp_single_item.txt_title) {
            v_title = document.frm_dsp_single_item.txt_title.value;
        } else if (document.frm_dsp_news_publication_info && document.frm_dsp_news_publication_info.txt_title) {
            v_title = document.frm_dsp_news_publication_info.txt_title.value;
        }
        v_main_cate_id = '';
        if(document.getElementById('sel_main_category_id').value){
            v_main_cate_id = document.getElementById('sel_main_category_id').value;
        }
		// Replace ky tu dac biet
        v_title = v_title.replace(/#.*$/, '');
        openWindow(CONFIG.BASE_URL+'category_common/dsp_all_category_by_select/?news_id='+p_news_id+'&title='+v_title+'&select_main_cate=1&v_main_cate_id='+v_main_cate_id+'&data='+getAllSelectOption('sel_category_list'), 400, 500);
    }
	if (document.getElementById('ifr_qlnb')) {
        var text = 'Thay đổi chuyên mục xuất bản sẽ làm thay đổi thông tin nhuận bút. \n Bạn có muốn thay đổi chuyên mục?';
        var v_ids = [p_news_id];
        text += check_list_bai_pr(v_ids);
		show_dialog_confirm(text, function () {
            return show_popup_chon_chuyen_muc_inner();
        }, function () {
            return false;
        });
	} else {
        return show_popup_chon_chuyen_muc_inner();
    }    
}

function get_thong_tin_nb_ttcb(p_news_id, p_reload_form_ttnb) {
    v_arr_map_category = new Array(349, 350, 351);
	v_loai_trang = $('#sel_loai_trang').val();
    if (v_loai_trang == '') {
		alert('Bạn chưa chọn chuyên mục xuất bản!');
		return;
	}
    v_category = v_arr_map_category[v_loai_trang];
	if ($('#ifr_qlnb').length == 0 || p_reload_form_ttnb == true) {
		var v_url = CONFIG.BASE_URL+'ajax/video_trang_thong_tin_can_biet/dsp_sua_thong_tin_nb/'+p_news_id+'/'+v_category;
		$.get(v_url, function(data) {
			$('#block_nhuan_but_bai_viet').html(data).show();
			
		});
		//document.getElementById("chk_bo_qua_ttnb").checked = false;
	} 
	$('#block_nhuan_but_bai_viet').toggle();
}

/*
Ham xu ly load lai danh sach chuyen muc tren man hinh cap nhat header_script
phuonghv add 07/05/2014
*/
function get_danh_sach_chuyen_muc(p_chk_option, p_type, p_target_id, p_danh_sach_id_chuyen_muc_duoc_chon) {       
    p_type = p_chk_option.checked?  p_type: 0;    
    if(p_type==2) {// hien thi chuyen muc xuat ban
        document.frm_update_data_chi_tiet.chk_show_all_category.checked=false;
    } else {// hien thi tat ca chuyen muc
        document.frm_update_data_chi_tiet.chk_show_all_category_publish.checked=false;  
    }
   	var v_url = CONFIG.BASE_URL+'ajax/header_script/dsp_all_category_onclick/'+p_type+'/'+p_danh_sach_id_chuyen_muc_duoc_chon;
    AjaxAction(p_target_id, v_url);    
}

/*
Ham kiem tra link redirect co dung hay khong
phuonghv add 12/12/2014
*/
function kiem_tra_link_redirect(p_link_id) {      
    v_link = $('#'+p_link_id).val();
    openWindow(v_link, 780, 400, 1);  
}

function btn_cap_nhat_import_link_redirect_onclick(p_forms, p_action_url, p_target, p_confirm_message, p_class_button){
	v_class_button = (typeof(p_class_button)==='undefined')? '':p_class_button;
	v_is_ok = false;	        
    var data = $('input:checkbox[name=sel_thiet_bi[]]');     
    var v_arr_thiet_bi = [];
    $.each(data, function(key, object) {
        if(object.name=='sel_thiet_bi[]'){            
            if(object.checked) {                                
                v_arr_thiet_bi.push(object.value);                
            }
        }        
    });      
    if(v_arr_thiet_bi.length==0) {
        alert('Bạn chưa chọn thiết bị');
        set_enable_link(v_class_button);
        return;
    }       

    v_trang_thai_xuat_ban = $("#sel_trang_thai_xuat_ban" ).val();    
    if(v_trang_thai_xuat_ban != 0 && v_trang_thai_xuat_ban!=1) {
        alert('Bạn chưa chọn trạng thái xuất bản');
        set_enable_link(v_class_button);
        return;
    }
    $('#hdn_thiet_bi').val(v_arr_thiet_bi.join(','));
    $('#hdn_trang_thai').val(v_trang_thai_xuat_ban);      
	if(confirm(p_confirm_message))
    {       
        frm_submit(p_forms, p_action_url, p_target) ;
        v_is_ok = true;
    }	
	if (!v_is_ok && v_class_button != '') {
		set_enable_link(v_class_button);
	}
}

function add_row_pr_2015(){
	var v_html_row_now = html_row_pr_goc.replace(/{stt}/g, stt_row_pr);
	++stt_row_pr;
	document.getElementById('so_dong_pr').value = stt_row_pr;
	$('#tableContent').append(v_html_row_now);
	setDatePicker("frm_dsp_filter_date_select_pr_news");
}

//Dannc function add_row_pr
function add_row_pr_mobile(){
	var v_html_row_now = html_row_pr_goc.replace(/{stt}/g, stt_row_pr);
	++stt_row_pr;
	document.getElementById('so_dong_pr').value = stt_row_pr;
	$('#total_pr').append(v_html_row_now);
	setDatePicker("frm_dsp_filter_date_select_pr_news");
}
function btn_remove_news_pr_onclick(p_forms, p_action_url, p_tr_id) {
	if(confirm('Bạn có chắc chắn xóa không?')) {
		if (p_action_url) {
			p_forms.action = p_action_url;
		} else {
			p_forms.action = "";
		}
		p_forms.submit();
		del_row_pr_2015(p_tr_id);
	}
}

function btn_add_news_pr_onclick(p_forms, p_action_url) {
	if (p_action_url) {
		p_forms.action = p_action_url;
	} else {
		p_forms.action = "";
	}
	p_forms.submit();
}

function del_row_pr_2015(p_tr_id) {
	document.getElementById('so_dong_pr').value = (document.getElementById('so_dong_pr').value - 1);
	$('#' + p_tr_id).remove();
}
function RefreshParent() {
    if (window.opener != null && !window.opener.closed) {
        window.opener.location.reload();
    }
}

/*
* Thêm bài liên quan vào vị trí dưới sapo bài viết
*/
function insert_bai_lien_quan_duoi_sapo()
{  
    v_noi_dung_bai_viet = CKEDITOR.instances.txt_body.getData();             
    if(v_noi_dung_bai_viet.indexOf("baiviet_bailienquan_duoi_sapo")<0){   
        v_str_before = '<div id="baiviet_bailienquan_duoi_sapo" class="baiviet-bailienquan green-box-bg-light">';
        v_str_after = '</div>';           
        v_content = CKEDITOR.instances.txt_bai_lien_quan.getData();
        v_content = v_str_before + v_content + v_str_after + v_noi_dung_bai_viet;    
        CKEDITOR.instances.txt_body.setData(v_content);    
    } else {
        alert('Chỉ được thêm bài liên quan dưới sapo bài viết một lần duy nhất.');
    }   
}

/*
 *Author : Thangnb 21-07-2015
 * Hàm check các thông tin nhập vào của UC nhập code facebook cho bài viết
*/
function check_code_facebook(p_forms)
{
    if (p_forms.txt_width.value == '') {
		alert('Bạn chưa nhập chiều rộng!');
		p_forms.txt_width.focus();
		return false;
	}
    if (p_forms.txt_height.value == '') {
		alert('Bạn chưa nhập chiều cao!');
		p_forms.txt_width.focus();
		return false;
	}
    if (p_forms.code_facebook.value == '') {
		alert('Bạn chưa nhập code facebook!');
		p_forms.code_facebook.focus();
		return false;
	}
	return true;
}

/*
* Phuonghv add 03/09/2015
* Chon bai viet gan code tracking
*/
function chon_bai_viet_gan_code_tracking(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }        
	if(v_danh_sach_id_bai_viet=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id.value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/code_tracking/dsp_bai_viet_gan_code_tracking/'+v_danh_sach_id_bai_viet;
    window.opener.AjaxAction('div_danh_sach_bai_viet', v_url);   
    window.close();    
}


/*
* Phuonghv add 09/09/2015
* Chon chu de cho bai viet
*/
function chon_chu_de_cho_bai_viet(p_forms, p_control_id){  
	var v_record_count = p_forms.hdn_record_count.value*1;    
    var v_count =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_id_chu_de = p_check_obj.value;
            v_ten_chu_de = eval("p_forms.hdn_tag_name"+i+".value");    
            window.opener.them_chu_de_cho_bai_viet(p_control_id, v_id_chu_de, v_ten_chu_de);
            v_count++;
        }
    }        	      
    if(v_count==0) {
        alert('Chưa có profile nào được chọn.');
        return;
    }
    window.close();    
}

/*
* Phuonghv add 09/09/2015
* Thêm chủ đề vào ô text box profile và tag app
*/
function them_chu_de_cho_bai_viet(p_id_control, p_tag_app_id, p_tag_app_name){    
    $("#"+p_id_control).tokenInput("add", {pk_tag_app:p_tag_app_id,c_tag:p_tag_app_name});
}

// begin: cap_nhat_profile_doi_bong
// phuonghv sua ngay 13/11/2015 xu ly an hien o selectbox chon loai profile tren man hinh cap nhat
/*
* Phuonghv add 14/09/2015
* Ẩn hiện vùng thông tin nhập cho chủ đề loại profile
*/
function show_or_hide_profile(p_id_control, p_div_show_hide_id) {
    v_loai_tag = $("#"+p_id_control).val();
    if(v_loai_tag==1) {
        $("#"+p_div_show_hide_id).show();
        $("#profile_loai_doi_tuong").show();        
        /* begin 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de */
        $("#id_tom_tat_profile").show(); 
        $("#id_tieu_su").show(); 
        $("#id_noi_dung_profile_thuong").hide();
        $("#td_anh_profile").show();
        $("#td_anh_chu_de").hide();
        $("#td_anh_cover").show();
        $("#td_anh_share").hide();
        $("#sp_profile").show();
        $("#sp_chu_de").hide();
        $("#id_crop_profile").show();
        $("#id_crop_chu_de_thuong").hide();
    } else {
        $("#"+p_div_show_hide_id).show();
        $("#profile_loai_doi_tuong").hide();        
        $("#id_tom_tat_profile").hide();   
        $("#id_tieu_su").hide(); 
        $("#id_noi_dung_profile_thuong").show(); 
        $("#td_anh_profile").hide();
        $("#td_anh_chu_de").show();
        $("#td_anh_cover").hide();
        $("#td_anh_share").show();
        $("#sp_profile").hide();
        $("#sp_chu_de").show();
        $("#id_crop_profile").hide();
        $("#id_crop_chu_de_thuong").show();
        /* end 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de */
    }    
    
}
//end: cap_nhat_profile_doi_bong

/*
* Phuonghv add 17/09/2015
* Tự động tính ngày hết hiệu lực của code tracking
*/
function code_tracking_ngay_bat_dau_onchange(p_date_control){
    var v_ngay_bat_dau = p_date_control.value;    
    var v_arr_date = v_ngay_bat_dau.split('-');  
    if(v_arr_date.length==3) {
        var v_date = new Date(v_arr_date[2], v_arr_date[1], v_arr_date[0]);
        v_date.setFullYear(v_date.getFullYear()+1);    
        $('#txt_den_ngay').val((v_date.getDate()<10?'0'+v_date.getDate():v_date.getDate())+'-'+(v_date.getMonth()<10? '0'+v_date.getMonth():v_date.getMonth())+'-'+v_date.getFullYear());
    }
}

/*
* Phuonghv add 24/10/2015
* Chon bai viet gan cau hinh hien thi banner khach hang
*/
function chon_bai_viet_gan_banner_khach_hang(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }        
	if(v_danh_sach_id_bai_viet=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id.value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/bai_viet_chua_banner/dsp_bai_viet_gan_banner/'+v_danh_sach_id_bai_viet;
    window.opener.AjaxAction('div_danh_sach_bai_viet', v_url);   
    window.close();    
}

//begin:cap_nhat_profile_doi_bong       
/*
* Phuonghv add 12/11/2015
* Ẩn hiện ô text box nhập id đội bóng
*/
// begin 10/03/2016 tuyennt: bo_sung_o_nhap_id_giai_dau_lich_thi_dau_doi_hinh_doi_bong
function show_or_hide_loai_profile(p_id_control, p_div_show_hide_id, p_id_tieu_su, p_id_tieu_su_doi_bong, p_id_doi_hinh, p_id_lich_thi_dau) {
    v_loai_profile = $("#"+p_id_control).val();
    /* begin 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de */
    if(v_loai_profile==2) {
        $("#"+p_div_show_hide_id).show();
        $("#"+p_id_tieu_su_doi_bong).show();
        $("#"+p_id_doi_hinh).show();
        $("#"+p_id_lich_thi_dau).show();
        $("#"+p_id_tieu_su).hide();
        $("#id_noi_dung_profile_thuong").hide(); 
    } else {
        $("#"+p_div_show_hide_id).hide();
        $("#"+p_id_tieu_su_doi_bong).hide();
        $("#"+p_id_doi_hinh).hide();
        $("#"+p_id_lich_thi_dau).hide();
        $("#"+p_id_tieu_su).show();
        $("#id_noi_dung_profile_thuong").hide(); 
    }    
    /* end 02/08/2016 TuyenNT tinh_chinh_chuc_nang_quan_ly_chu_de */
}
// end 10/03/2016 tuyennt: bo_sung_o_nhap_id_giai_dau_lich_thi_dau_doi_hinh_doi_bong
/*
* ham hien thi ten doi tuong theo id
*/
function doi_tuong_id_onchange(p_doi_tuong_id) {
    var v_url = CONFIG.BASE_URL+'ajax/chu_de_tren_app/lay_ten_doi_bong/'+p_doi_tuong_id;
    $.get(v_url, function(data) {
        $('#ten_doi_tuong').html(data).show();        
    });
}
/*
* Phuonghv add 13/11/2015
* Chon ID doi bong cho profile
*/
function chon_doi_bong_cho_profile(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;    
    var v_count =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_id_doi_bong = p_check_obj.value;
            v_ten_doi_bong = eval("p_forms.hdn_ten_doi_bong"+i+".value");             
            v_count++;           
        }
    }
    if(v_count>1) {
        alert('Bạn chỉ được phép chọn 1 đội bóng.');
        return;
    }    
    if(v_count==0) {
        alert('Chưa có đội bóng nào được chọn.');
        return;
    }
    
    $("#txt_doi_tuong_id", window.opener.document).val(v_id_doi_bong);
    $("#ten_doi_tuong", window.opener.document).html(v_ten_doi_bong);
    window.close();    
}
//end:cap_nhat_profile_doi_bong       

// begin 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h
/**
 * Function:  crop_image crop anh trong ocm
 * param: p_forms 
 * param: p_action_url
 * param: p_target
 **/
function crop_image(p_forms, p_action_url, p_target)
{
    window.open(p_action_url, p_target, 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')
    frm_submit (p_forms, p_action_url, p_target);
}
// end 07/03/2016 tuyennt: bo_sung_chuc_nang_crop_anh_cho_cac_chu_nang_ocm_24h

// begin 10/03/2016 TuyenNT: bo_sung_o_nhap_id_giai_dau_lich_thi_dau_doi_hinh_doi_bong
/*
 * TuyenNT add 10/03/2016
* ham hien thi ten doi tuong theo id
*/
function doi_tuong_id_giai_dau_onchange(p_doi_tuong_id) {
    var v_url = CONFIG.BASE_URL+'ajax/chu_de_tren_app/lay_ten_doi_bong/'+p_doi_tuong_id;
    $.get(v_url, function(data) {
        $('#ten_doi_tuong').html(data).show();        
    });
}
/*
* TuyenNT add 10/03/2016
* Chon ID giai dau cho profile
*/
function chon_giai_dau_cho_profile(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;    
    var v_count =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_id_giai_dau = p_check_obj.value;
            v_ten_giai_dau = eval("p_forms.hdn_ten_giai_dau"+i+".value");             
            v_count++;           
        }
    }
    if(v_count>1) {
        alert('Bạn chỉ được phép chọn 1 giải đấu.');
        return;
    }    
    if(v_count==0) {
        alert('Chưa có giải đấu nào được chọn.');
        return;
    }
    
    $("#txt_doi_tuong_id_giai_dau", window.opener.document).val(v_id_giai_dau);
    $("#ten_doi_tuong_giai_dau", window.opener.document).html(v_ten_giai_dau);
    window.close();    
}
// end 10/03/2016 tuyennt: bo_sung_o_nhap_id_giai_dau_lich_thi_dau_doi_hinh_doi_bong
//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
function openWindowUploadImage(p_image_type){
    var news_title  = document.frm_dsp_single_item.txt_title.value;
    window.open(CONFIG['BASE_URL']+'upload_image/?image_type='+p_image_type+'&news_title='+news_title, '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}
//End 07-04-2016 : Thangnb toi_uu_upload_anh_gif
/* Begin anhpt1 24/5/2016 chuc_nang_upload_video */
function frm_submit_image_video(p_stt_video){
    v_url = CONFIG.BASE_URL+'/upload_video/act_get_image_video/'+p_stt_video;
    document.forms['frm_Upload_image_video'].action = v_url;
    document.forms['frm_Upload_image_video'].submit();
}/* End anhpt1 24/5/2016 chuc_nang_upload_video */

/* begin 30/05/2016 TuyenNT bo_sung_chuc_nang_chon_CM_banner_layout_chuc_nang_cap_nhat_tin_bai 
 * Thuc hien an hien chuc nang chon banner layout cho bai viet
 * @param
 * element  trang thai cua checkbox
 * */
function add_banner_layout_news(element)
{
    //chk_cau_hinh_banner_layout
    obj_banner_layout = document.getElementById('chk_cau_hinh_banner_layout');
    obj_block_banner_layout_region = document.getElementById('block_banner_layout_region');
    if (element.checked) {
        obj_block_banner_layout_region.style.display = 'block';
    }else{
        obj_block_banner_layout_region.style.display = 'none';
    }
}
/* end 30/05/2016 TuyenNT bo_sung_chuc_nang_chon_CM_banner_layout_chuc_nang_cap_nhat_tin_bai */
/* Begin anhpt1 07/5/2016  chuyen_keyword_link_ocm */
function thong_tin_doi_tuong_xuat_ban_keyword(p_obj, p_type, p_id_div_info) {
    var v_danh_sach_id = p_obj.value;    
    if(v_danh_sach_id!='') {
        v_danh_sach_id = v_danh_sach_id.replace("/", "");
        p_type = p_type.replace("/", "");
        var v_url = CONFIG.BASE_URL+'ajax/keyword_link/thong_tin_doi_tuong_xuat_ban_keyword/'+p_type+'/'+v_danh_sach_id;
        $.get(v_url, function(data) {
            $('#'+p_id_div_info).html(data).show();        
        });
    }
}/* End anhpt1 07/5/2016  chuyen_keyword_link_ocm */

//Begin 09-06-2016 : Thangnb upload_anh_so_sanh
function add_row_upload_anh_so_sanh() {
	if (document.getElementsByClassName('anh_so_sanh').length > 0) {
		if (document.getElementsByClassName('anh_so_sanh').length >= 20) {
			alert('Bạn chỉ được upload tối đa 20 cặp ảnh');
			return false;	
		}
		var v_all_row = document.getElementsByClassName('anh_so_sanh');
		var last_stt_item = v_all_row.length;
		var last_id = v_all_row.item(last_stt_item-1).getAttribute('id');
		var last_stt = last_id.replace('anhsosanh_cap','');
		var new_stt = parseInt(last_stt)+1;
	} else {
		var new_stt = 1;	
	}
	//Begin 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
	$.ajax({
		url : "/ocm/ajax/anhsosanh_common/dsp_cap_anh_so_sanh/"+new_stt,
		async:true,
		success: function(result){
			$('#table_anh_so_sanh').append(result);	
			$("#max_count_anh_so_sanh").val(parseInt($("#max_count_anh_so_sanh").val())+1);
		}
	})
	//End 26-07-2016 : Thangnb bo_sung_crop_anh_so_sanh
}

function remove_row_upload_anh_so_sanh() {
	var v_all_row = document.getElementsByClassName('anh_so_sanh');
	var last_stt_item = v_all_row.length;
	var last_id = v_all_row.item(last_stt_item-1).getAttribute('id');
	var last_stt = last_id.replace('anhsosanh_cap','');
	p_forms = document.frmUpload_anh_so_sanh;
	
	var v_kiem_tra_tich_chon = false;
	for(var i = 1; i <= last_stt; i++){
		var p_check_obj = eval("p_forms.anhsosanh_cap"+i);
		if(p_check_obj && p_check_obj.checked == true){
			v_kiem_tra_tich_chon = true;
			$("#tr_anh_so_sanh_cap"+i+"_1").remove();
			$("#tr_anh_so_sanh_cap"+i+"_2").remove();
		}
	}	
	if (v_kiem_tra_tich_chon == false) {
		alert('Không cặp nào được chọn để xoá');	
		return false;
	}
}
//End 09-06-2016 : Thangnb upload_anh_so_sanh

//Begin 16-06-2016 : Thangnb thay_doi_logo_video_doi_tac
function bo_chon_1_select_box(p_id_select_box) {
	if (p_id_select_box != '' && typeof(p_id_select_box) != 'undefined') {
		$('#'+p_id_select_box).val( $('#'+p_id_select_box).prop('defaultSelected') );
	}
}
//End 16-06-2016 : Thangnb thay_doi_logo_video_doi_tac
/* Begin anhpt1 27/06/2016 export_import_title_des_key_bai_viet */
function export_du_lieu_news_theo_ngay(v_class_name_block,goback){
    var v_html = '<form name="frm_dsp_filter_export_theo_ngay" action="'+v_class_name_block+'" target="fr_submit_export_theo_ngay" method="get"><div class="export-data">';
    v_html = v_html + '<input value="'+goback+'" type="hidden" name="goback" id="goback" />';
    v_html = v_html + '<div class="title-export-data" style="font-weight: 700;">Export dữ liệu theo ngày</div>';
    v_html = v_html + '<div class="content-export-data">';
    v_html = v_html + '<div class="date-export-data">';
    v_html = v_html + '<label for="">Chọn ngày cần xuất dữ liệu</label>';
    v_html = v_html + '<input type="text" class="frm_dsp_filter_date_select" name="txt_date" id="txt_date" value="" placeholder="">';
    v_html = v_html + '</div>';
    v_html = v_html + '<div class="button-export-data">';
    v_html = v_html + ' <button type="button" onclick="document.forms[\'frm_dsp_filter_export_theo_ngay\'].submit();">Export</button>';
    v_html = v_html + '<button type="button" onclick="dong_cua_so_popup_overlay();">Đóng</button>';
    v_html = v_html + '</div>';
    v_html = v_html + '</div>';
    v_html = v_html + '</div></from><iframe name="fr_submit_export_theo_ngay" class="iframe-form"></iframe>';
    mo_cua_so_popup_overlay(v_html,400,200);
    setDatePicker("frm_dsp_filter_date_select");
    
}

/*
 * Ham mo cua so popup overlay
 * @param string v_html ma html cua cua so can mo
 * @param string width_box chieu rong cua cua so popup
 * @param string height_box chieu cao cua cua so popup
 * @return string
 */
function mo_cua_so_popup_overlay(v_html,width_box, height_box) {
	if (!width_box) width_box=320;
	if (!height_box) height_box=300;
	v_top = 6;
	v_left = 10;

	//Opera Netscape 6 Netscape 4x Mozilla
	if (window.innerWidth || window.innerHeight){
		docwidth = window.innerWidth;
		docheight = window.innerHeight;
	}
	//IE Mozilla
	if (document.body.clientWidth || document.body.clientHeight){
		docwidth = document.body.clientWidth;
		docheight = document.body.clientHeight;
	}
    
	v_left = (docwidth-width_box)/2;
	if(v_left < 0){
		v_left = 10;
	}

	if (document.getElementById('_box_popup')) {
		//$("#_box_popup").css({left:v_left,top:v_top,width:width_box});
		//$("#_box_popup").css({left:v_left,top:v_top,width:width_box});
		$("#_box_overlay").css({});
	} else {
		var v_popup_overlay = '<div class="popup-overlay boxy-modal" id="_box_overlay" onclick="javascript:dong_cua_so_popup_overlay();"><div class="box-popup" id="_box_popup" style="margin: 0 auto;top:30px;width:'+width_box+'px;height:'+height_box+'px;position: static;background-color: azure;margin-top: 100px;"></div><div class="clear"></div></div>';
		$("body").append(v_popup_overlay);
	}
	$("#_box_popup").html(v_html);
	$('#_box_overlay').show();
		
    // add key press event
    $("body").keypress(function(e){
        if (e.keyCode == 27) { //Esc keycode
            dong_cua_so_popup_overlay();
        }
    });
	$('#_box_popup').click(function(e){
		if (!e) var e = window.event;
		e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
	});
    
    // Phucnn : check di?n tho?i d? thay d?i view + focus input
    var is_keyboard = false;
    var is_landscape = false;
    var initial_screen_size = window.innerHeight;
    
    /* Android */
	if (window.addEventListener) {
		window.addEventListener("resize", function() {
			is_keyboard = (window.innerHeight < initial_screen_size);
			is_landscape = (screen.height < screen.width);
		
			updateViews();
		}, false);
    }
}
/*
 * Ham dong cua so popup overlay
 * @param khong co
 * @return string
 */
function dong_cua_so_popup_overlay(p_url) {

	$('#_box_overlay').hide();
	//$("#_box_overlay").css({left:0,top:0,width:0,height:0});
	for (f = 0; f < document.forms.length; f++)
    {
        var elements = document.forms[f].elements;
        for (e = 0; e < elements.length; e++)
        {
            if (elements[e].type == "select-one")
            {
				if (elements[e].className == "class_fix_popup_overlay")
				{
					v_class_name = elements[e].getAttribute('classname_old_fix_popup_overlay');
					elements[e].className = v_class_name;
					elements[e].style.display = '';
				}
            }
        }
    }
	$("#_box_popup").html('');
	
	if (typeof p_url !== "undefined" && p_url != '') {
		window.location = p_url;
	}	
}
/* End anhpt1 27/06/2016 export_import_title_des_key_bai_viet */

/* Begin: Tytv - 28/07/2016 - Bổ xung quản trị giá trị và loại danh mục */

function them_5_gia_tri(){
	obj_so_lan_them = $('#hdn_so_lan_them');
	var so_lan_them=0;
	if(obj_so_lan_them.val() == 0){
		so_lan_them=4;
	}else{
		so_lan_them=5;
	}
	
	for(j=0;j<so_lan_them;j++){
		them_1_gia_tri();
	}
}
function them_1_gia_tri(){
	tr_gia_tri = $('#tr_0');
	obj_so_lan_them = $('#hdn_so_lan_them');
	obj_ma_gia_tri = $('#hdn_ma_gia_tri');
	
	var d = document;
	var table = document.getElementById("tbl_gia_tri" );
	var rows = table.rows;
	var str_html = rows[1].innerHTML;
	var tbody = table.getElementsByTagName('tbody')[0];
	var tr_id = parseInt(obj_so_lan_them.val())+1;
	var tr = d.createElement('tr');
	
	var arr_thay_the=new Array(); 
	arr_thay_the[0]=new Array("(0)","("+ tr_id +")");       
    arr_thay_the[1]=new Array("txt_ma_idx_0","txt_ma_idx_"+ tr_id	);
	arr_thay_the[2]=new Array("txt_ten_idx_0","txt_ten_idx_"+ tr_id	);
	arr_thay_the[3]=new Array("txt_trong_so_idx_0","txt_trong_so_idx_" + tr_id);
	arr_thay_the[4]=new Array("txt_gia_tri_ss_idx_0","txt_gia_tri_ss_idx_" + tr_id);
	arr_thay_the[5]=new Array("txt_ghi_chu_idx_0","txt_ghi_chu_idx_" + tr_id);
	arr_thay_the[6]=new Array("upload_file_idx_0","upload_file_idx_" + tr_id);
	arr_thay_the[7]=new Array("hdn_url_idx_0","hdn_url_idx_" + tr_id);
	arr_thay_the[8]=new Array("ten_file_idx_0","ten_file_idx_" + tr_id);
	arr_thay_the[9]=new Array("hdn_item_id_idx_0","hdn_item_id_idx_" + tr_id);
	arr_thay_the[10]=new Array("hdn_is_upload_idx_0","hdn_is_upload_idx_" + tr_id);
	arr_thay_the[11]=new Array("btn_delete_0","btn_delete_" + tr_id);
	arr_thay_the[12]=new Array("btn_xoa_anh_0","btn_xoa_anh_" + tr_id);
	arr_thay_the[13]=new Array("div_upload_file_0","div_upload_file_" + tr_id);
	arr_thay_the[14]=new Array('href="javascript:void(null)" style="display:none"','href="javascript:xoa_1_gia_tri('+ tr_id + ')"');
	
	tr.id = "tr_"+ tr_id;
	
	for(i=0;i<arr_thay_the.length;i++){
		str_html = str_html.split(arr_thay_the[i][0]).join(arr_thay_the[i][1]);
	}
	
	tr.innerHTML = str_html;
	tbody.appendChild(tr);
	
	obj_ma_gia_tri.val(obj_ma_gia_tri.val()+";{"+tr_id+"}");
	obj_so_lan_them.val(parseInt(tr_id));
	xoa_anh(tr_id);
}
function xoa_1_gia_tri(tr_id){
	var d     = document;
	var table = document.getElementById("tbl_gia_tri" );
	var tbody = table.getElementsByTagName('tbody')[0];
	var tr    = d.getElementById("tr_"+tr_id);
	obj_ma_gia_tri = $('#hdn_ma_gia_tri');

	tbody.removeChild(tr);
	var tmp_ma_gia_tri = obj_ma_gia_tri.val();
	obj_ma_gia_tri.val(tmp_ma_gia_tri.replace(";{"+tr_id+"}",""));
}
function get_file_name(p_id){
	var fullPath = document.getElementById('upload_file_idx_'+p_id).value;
	if (fullPath) {
		var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
		var filename = fullPath.substring(startIndex);
		if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
			filename = filename.substring(1);
		}
		document.getElementById('ten_file_idx_'+p_id).innerHTML=filename;
		document.getElementById('hdn_is_upload_idx_'+p_id).value=1;
		document.getElementById('hdn_xoa_file').value=1;
		document.getElementById('btn_xoa_anh_'+p_id).style.display='';
	}else{
		document.getElementById('ten_file_idx_'+p_id).innerHTML='';
		document.getElementById('hdn_is_upload_idx_'+p_id).value=0;
	}
}
function xoa_anh(p_id){
	document.getElementById('div_upload_file_'+p_id).innerHTML =
    document.getElementById('div_upload_file_'+p_id).innerHTML;
	document.getElementById('ten_file_idx_'+p_id).innerHTML='';
	document.getElementById('hdn_url_idx_'+p_id).value='';
	document.getElementById('hdn_is_upload_idx_'+p_id).value=0;
	document.getElementById('hdn_xoa_file').value=1;
	document.getElementById('btn_xoa_anh_'+p_id).style.display='none';
}


/**
 * Ham cap nhat gia tri cua loai danh muc lien quan sau khi chon loai danh muc tu box tim kiem co goi y
 * @author cuongnx
  * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_id id cua hidden luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @param  is_submit co submit form hay khong
 * @param  form_id Nhap ten form neu is_submit = 1 (tuc la co submit)
 * @return string
 */
function cap_nhat_gia_tri_duoc_chon(checkbox_id,hdn_id,hdn_tong,txt_input,is_submit,form_id) { 
   var id_duoc_chon = document.getElementById(hdn_id).value;		
	var i=0;
	var so_tinh = document.getElementById(hdn_tong).value;	
	for(i=0;i<so_tinh;i++){		
		if(document.getElementById(checkbox_id+i).value == id_duoc_chon){					
			document.getElementById(checkbox_id+i).checked = true;			
			document.getElementById(checkbox_id+i).focus();			
			document.getElementById(txt_input).value = '';	
			document.getElementById(txt_input).focus();	
			if(is_submit == 1) {
				eval("document."+form_id+".submit()");			
			}
		}
	}	
	return false;
}

/*
 * Ham hien thi popup chon anh san pham thay the
 * @author cuongnx
 * @param int p_stt so thu tu id them anh
 * @return string
 */
function chon_anh_tu_server(p_stt) {	
	v_url = url_modul_anh+'/dsp_danh_sach_anh_san_pham?';
	v_url = v_url + 'data=[""]'+'&hdn='+p_stt+'&call_back=xu_ly_anh_duoc_chon&che_do_chon_nhieu=0&'+ocm_randomizeNumber();
	if (ocm_isIE()) {		
		var popupWindow = window.open(v_url, 'new_window', 'width=1000pt, height=600pt,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');		
	} else {
		sRtn = showModalDialog(v_url,"","dialogWidth=1000pt;dialogHeight=600pt;dialogLeft:300pt;dialogTop=120pt;status=no;scroll=no;");					
	}	
}

/*
 * Ham xu ly anh duoc chon
 * @author cuongnx
 * @param string p_hdn control hidden luu gia tri
 * @param string p_str_data chuoi json tra lai ket qua anh chon
 * @return string
 */
function xu_ly_anh_duoc_chon(p_hdn, p_str_data){	
	
	if (p_str_data != '') {
		arr_str_data = p_str_data.split('","');
		id_anh_duoc_chon = arr_str_data[(arr_str_data.length*1-1)];
		id_anh_duoc_chon = id_anh_duoc_chon.replace('"]','');
		id_anh_duoc_chon = id_anh_duoc_chon.replace('["','');
	}	
	
	// thay the anh san pham vua chon		
	if (id_anh_duoc_chon!='') {
		v_url = url_modul_ajax+'/dsp_anh_duoc_chon/?id_anh='+id_anh_duoc_chon+'&'+ocm_randomizeNumber();
// alert(v_url);
		$.ajax({
		  url: v_url,
		  success: function(data_html) {
			document.getElementById('ten_file_idx_'+p_hdn).innerHTML = data_html;
			document.getElementById('btn_xoa_anh_'+p_hdn).style.display = '';
			document.getElementById('hdn_url_idx_'+p_hdn).value = data_html;
			// arr_data = data_html.split('{#;#}');
			// obj_nganh_hang_duoc_chon.innerHTML = arr_data[0];
			// nganh_hang_cap1 = arr_data[1];
			// if (nganh_hang_cap1_cu != nganh_hang_cap1) {
				// obj_gian_hang_trong_nhom.innerHTML='';
				// document.getElementById('hdn_list_gian_hang').value='[""]';
				// document.getElementById('hdn_list_gian_hang_cu').value='[""]';
				
			// }
		  }
		});
	}
	return;
}

/**
 * Check or uncheck cac checkbox gia tri loc tim 
 * @return
 */
function check_all_gia_tri_loc_tim(p_checkbox_id, p_checkbox_class, p_checkbox_class_1)
{
	$(function(){
		// add multiple select / deselect functionality
		$("#"+p_checkbox_id).click(function () {
			$('.'+p_checkbox_class).attr('checked', this.checked);
			$('.'+p_checkbox_class_1).attr('checked', this.checked);
		});

		// if all checkbox are selected, check the selectall checkbox
		// and viceversa
		$('.'+p_checkbox_class).click(function(){

			if($('.'+p_checkbox_class).length == $("."+p_checkbox_class+":checked").length) {
				$("#"+p_checkbox_id).attr("checked", "checked");
			} else {
				$("#"+p_checkbox_id).removeAttr("checked");
			}
		});
	});
}

/*
 * Ham thuc hien khi chon 1 giá trị tren popup chon 1 giá trị
 * @author  hailt 
 * @param  string p_chk_id id radio check giá trị
 * @param  integer p_id_button id nút bấm để chọn
 * @return string
 */
function chon_1_gia_tri_tren_danh_sach_chon_1_tu_ten_va_id(p_chk_id, p_id_button) {	
	document.getElementById(p_chk_id).click();
	document.getElementById(p_id_button).click();
}

/*
 * Ham thuc hien khi chon 1 giá trị tren popup chon nhiều giá trị
 * @author  hailt 
 * @param  string p_chk_id id checkbox check giá trị
 * @return string
 */
function chon_1_gia_tri_tren_danh_sach_chon_nhieu_tu_ten_va_id(p_chk_id) {	
	document.getElementById(p_chk_id).click();
}

function selectFile(p_id) {  
	obj = document.getElementById('upload_file_idx_'+p_id);  
	obj.click();
}  

/* ========== loai danh muc  =================*/

/**
 * Ham tra lai danh sach nganh hang sau khi thuc hien thao tac chon. tra lai danh sach nganh nghe cho cua so mo popup
 * @author ducnq
 * @param integer p_cap cap nganh hang
 * @param string p_control_id id control nganh hang
 * @return string
 */
function tra_lai_du_lieu_nganh_hang(p_hdn, p_str_data){	
	obj_nganh_hang_duoc_chon = document.getElementById(p_hdn);
	if (obj_nganh_hang_duoc_chon) {
		obj_nganh_hang_duoc_chon.value = p_str_data;
	}		
	// Hien thi nganh hang vua chon	
	v_url = url_modul+'/dsp_nganh_hang_duoc_chon/?data='+obj_nganh_hang_duoc_chon.value+'&'+ocm_randomizeNumber();
	ocm_AjaxAction('div_nganh_hang_duoc_chon', v_url);		
	return;
}

/**
 * Ham chon tat ca cac gia tri cua select box
 * @author ducnq
 * @param string selectBox id control selectbox hoac 1 control selectbox
 * @param boolean selectAll gia tri chon tat/bo chon tat
 * @return string
 */
function selectAll(selectBox,selectAll) { 
    // have we been passed an ID 
    if (typeof selectBox == "string") { 
        selectBox = document.getElementById(selectBox);
    } 
    // is the select box a multiple select box? 
    if (selectBox.type == "select-multiple") { 
        for (var i = 0; i < selectBox.options.length; i++) { 
             selectBox.options[i].selected = selectAll; 
        } 
    }
}

/**
 * Hàm chuyển tất cả các selectbox trong nhóm theo giá trị của 1 select box, tích chọn ô check chọn nếu có
 * @author Tytv 
 * @param string p_id_check_box_all: Id select box chứa giá trị chung
 * @param string p_id_tong_so: Id hidden chứa tổng số select box trong nhóm
 * @param string p_tien_to_select_box: Tiền tố đầu tên các select box trong nhóm
 * @param string p_tien_to_check_box: Tiền tố đầu tên các checkbox cho các select box trong nhóm
 * @param string p_gia_tri_bo_qua: Giá trị bỏ qua không thực hiện
 * @return 
 */
function js_select_box_all(p_id_check_box_all, p_id_tong_so, p_tien_to_select_box, p_tien_to_check_box, p_gia_tri_bo_qua) {
	var gia_tri_chung = document.getElementById(p_id_check_box_all).value;
	if (gia_tri_chung != p_gia_tri_bo_qua) {
            var tong_so = parseInt(document.getElementById(p_id_tong_so).value);
            var i = 0;
            for (i = 0; i < tong_so; ++i) {
                if (document.getElementById(p_tien_to_select_box + i)) {
                    document.getElementById(p_tien_to_select_box + i).value = gia_tri_chung;
                }
                console.log(document.getElementById(p_tien_to_check_box + i));
                if (document.getElementById(p_tien_to_check_box + i)) {
                 //đảm bảo click như thật. Để gọi các js nếu checkbox có để điều kiện onclick
                    document.getElementById(p_tien_to_check_box + i).checked = true;
//		    document.getElementById(p_tien_to_check_box + i).click();
	    }
        }    
    }
}
/**
 * Tìm kiếm và cập nhật checkboxes khi có dữ liệu thay đổi ở 1 dòng dữ liệu
 */ 
function ocm_form_listing_update()
{
	$("input[type='text']").change( function() {
		ocm_update_checkbox($(this));
	});
	$("input[type='checkbox']").change( function() {		
		if (!String($(this).attr('id')).match(/chk_idx/)) {			
			ocm_update_checkbox($(this));
		}
	});
	$("select").change(function () {
		ocm_update_checkbox($(this));
	});
}

/**
 * Cập trạng thái checked của checkbox khi dữ liệu ở dòng thay đổi.
 * ID của đối tượng được đặt theo quy tắc: id=id_name_idx_4, trong đó idx_ là bắt buộc, 4 là id của bản ghi
 * ID của checkbox đặt theo quy tắc: chk_idx_4
 * ocm_update_checkbox(this) or ocm_update_checkbox(form.input_id)
 * @param object p_object Object  
 */ 
function ocm_update_checkbox(p_object)
{
	v_object_id = String(p_object.attr('id')).replace(/^[A-Za-z0-9\_]+_idx_/g, '');	
	if ($.isNumeric(v_object_id)) {
		$('#chk_idx_'+v_object_id).attr("checked", "checked");
	}
}

/**
 * Dùng khi cần có xác nhận thực hiện 1 hành động xóa ở trong phần hiện thị danh sách,
 * dùng trong trường hợp liên quan đến nhiều bản ghi
 * ocm_button_on_click(document.forms.test, '/sowmewhere/', '', 'Are you sure?', 'case')
 * @param object
 * @param string p_action_url Nơi cần chuyển dữ liệu
 * @param string p_target Nơi hiện thị dữ liệu sau khi submit, là id của iframe or để trống
 * @param string p_message Nội dung thông báo xác nhận hành động
 * @param string p_checkbox_class class_name của checkboxes để kiểm tra xem có checkbox đc chọn trước khi xóa
 */
function ocm_button_on_click(p_forms, p_action_url, p_target, p_message, p_checkbox_class)
{	
	if (ocm_checkboxes_is_checked(p_checkbox_class)) {
		if (p_message != '') {
			if(confirm(p_message))
			{
				frm_submit(p_forms, p_action_url, p_target) ;
			}
		} else {
			frm_submit(p_forms, p_action_url, p_target) ;
		}
	} else {
		alert('Chưa có đối tượng nào được chọn!');
	}
}

/**
 * Kiểm tra xem đã có checkbox nào đã được chọn trong danh sách các checkbox
 * ocm_checkboxes_is_checked('class_name')
 * @params p_checkbox_class class_name của checkbox cần kiểm
 * return boolean
 */
function ocm_checkboxes_is_checked(p_checkbox_class)
{
	var selected = [];
	$('.'+p_checkbox_class).each(
		function() {
			if (this.checked) {
				selected.push($(this).attr('name'));
			}
		}
	);
	if (selected.length > 0) {
		return true;
	}
}
/*
 * Hàm thực hiện thay đổi số trang textbox lọc dưới
 * @author  trungtb 17/10/2014
 * @return 
 */	
function change_den_trang(){
	var id_so_dong = $('#page_duoi').val();
	$('#page').val(id_so_dong);
	document.getElementById("frm_dsp_filter").submit();
}
/* End: Tytv - 28/07/2016 - Bổ xung quản trị giá trị và loại danh mục */

/*Begin 27-07-2016 trungcq bo_sung_xem_lich_su_nhan_xet_tin_bai*/
/*
 * @desc: Thực hiện mở popup danh sách lịch sử nhận xét tin bài
 * @param string p_ocm_url ID tin bài
 * @param interger p_news_id ID tin bài
 * @param interger p_width Độ rộng popup
 * @param interger p_height Độ cao popup
 * @return
 */
function view_lich_su_nhan_xet_tin_bai(p_ocm_url, p_news_id, p_width, p_height){
    var v_url = p_ocm_url+'/dsp_xem_lich_su_nhan_xet_tin_bai?';
    $.ajax({
        url: v_url,
        type: "POST",
        data:{
            v_news_id:p_news_id,
        },
        success: function (data){
            show_box_popup('',p_width, p_height);
            $('#_box_popup').html(data);
            return false;
        }
   });
}
/*End 27-07-2016 trungcq bo_sung_xem_lich_su_nhan_xet_tin_bai*/

/* Begin: Tytv - 08/08/2016 - quan_ly_highlight_video */
/**
 * Hàm thực hiện thay đổi loại profile
 * @author  Tytv <tytv@24h.com.vn>
 * @date    29-04-2016
 * @param   e Đối tượng mà telerik gửi vào hàm khi click vào button save
 * 
 * @returns Null
 */
function change_news_highlight_video() { 
    var news_id = parseInt($('#fk_news').val()); 
    if(typeof(news_id)== 'NaN' || news_id<=0){
        alert('Không thể xác định được ID bài viết!');
        exit();
	}else{
        // Load dữ liệu từ url
        $.ajax({url: v_url_modul_ajax+'/dsp_change_news_highlight_video?id='+news_id, success: function(result){
            if(result){
                var v_arr_result = result.split('#@@@#'); 
                $('#zone_highlight_video').html(v_arr_result[0]);
                $('#info_news').html(v_arr_result[1]);
            }
        }});
    }
    
}
/**
 * Hàm thực hiện Xóa 1 highlight của 1 video
 * @author  Tytv <tytv@24h.com.vn>
 * @date    09-08-2016
 * 
 * @returns Null
 */
function hvn_xoa_1_highlight(p_id_doi_tuong) { 
    var v_arr = p_id_doi_tuong.split("_pos_");
    if(confirm('Bạn có chắc muốn xóa các tình huống highlight của video "'+v_arr[0]+'" không?')){
        $('#tr_highlight_'+p_id_doi_tuong).remove();
    }
}
/**
 * Hàm thực hiện Xóa nhiều highlight của 1 video
 * @author  Tytv <tytv@24h.com.vn>
 * @date    09-08-2016
 * 
 * @returns Null
 */
function hvn_xoa_nhieu_highlight(p_ten_doi_tuong) { 
    var v_ten_video = p_ten_doi_tuong.replace("_DAU_CHAM_",'.');
    var check_count = $('input.'+p_ten_doi_tuong+':checked').length;
    if(check_count>0){
        if(confirm('Bạn có chắc muốn xóa các tình huống highlight đã chọn của video "'+v_ten_video+'" không?')){
            $('input.'+p_ten_doi_tuong+':checked').parent('tr.'+p_ten_doi_tuong).remove();
            $('input.'+p_ten_doi_tuong).each(function () {
                if(this.checked){
                    var s_id = $(this).attr('id');
                    var s_id_dele = s_id.replace('chk_','tr_highlight_');
                    $('#'+s_id_dele).remove();
                }
            });
            //$('#tr_highlight_'+p_id_doi_tuong).remove();
        }
    }else{
        alert('Bạn chưa chọn tình huống highlight nào?');
    }
}
/**
 * Hàm thực hiện Xóa nhiều highlight của 1 video
 * @author  Tytv <tytv@24h.com.vn>
 * @date    09-08-2016
 * 
 * @returns Null 
 */
function hvn_them_1_highlight(p_ten_doi_tuong) { 
    var v_ten_video = p_ten_doi_tuong.replace("_DAU_CHAM_",'.');
    var check_count = $('input.'+p_ten_doi_tuong).length;
    var tong_lan_them = parseInt($('#hdn_luot_them_'+p_ten_doi_tuong).val());
    if((check_count+1)>v_max_video_for_video){
        alert('Chỉ được phép nhập tối đa '+v_max_video_for_video+' tình huống highlight cho video "'+v_ten_video+'"');
    }else{
        // Load dữ liệu từ url
        $.ajax({url: v_url_modul_ajax+'/dsp_add_single_highlight_for_video?name='+p_ten_doi_tuong+'&pos='+tong_lan_them, success: function(result){
            if(result){
                $('#tr_highlight_insert_'+p_ten_doi_tuong).before(result);
                tong_lan_them = tong_lan_them+1;
                $('#hdn_luot_them_'+p_ten_doi_tuong).val(tong_lan_them);
            }
        }});
    }
    
}

function frm_submit_selecting_video_news(p_form_obj)
{
    v_id = p_form_obj.chk_item_id.value;
    if (v_id <= 0 || v_id == '') {
        alert("Chưa có bài nào được chọn!");
    } else {      
        window.opener.document.getElementById('fk_news').value = v_id;
        window.opener.change_news_highlight_video();       
        window.close();
    }
    return false;
}
/* Hàm trả về đối tượng "giá trị loại danh mục" được chọn */
function frm_submit_selecting_listtype_value(p_form_obj,p_callback,p_return_obj)
{
    v_id = p_form_obj.chk_item_id.value;
    if (v_id <= 0 || v_id == '') {
        alert("Bạn chưa chọn đối tượng nào!");
    } else {      
        eval('window.opener.'+p_callback+'('+v_id+',"'+p_return_obj+'");');
        window.close();
    }
    return false;
}

/* Hàm xử lý hiển thị khi "giá trị loại danh mục" được chọn */
function hvn_hien_thi_anh_duoc_chon(p_id,p_return_obj)
{
    // Load dữ liệu từ url
    $.ajax({url: v_url_modul_ajax+'/dsp_icon_highlight_video_for_selecting?id='+p_id, success: function(result){
        if(result){
            var v_arr_result = result.split('#@_@#');
            var url = v_arr_result[0];
            var status = v_arr_result[1];
            if(status<=0){
                alert('Đối tượng chưa được xuất bản!');
            }else{
                $('#img_txt_icon_'+p_return_obj).attr('src',url);
                $('#txt_icon_'+p_return_obj).val(url);
            }
        }
    }});
}
/* End: Tytv - 08/08/2016 - quan_ly_highlight_video */

// begin 08/08/2016 TuyenNT xay_dung_co_che_auto_save_bai_trong_ocm_24h
/*
    Ham chuyen sang man hinh danh sach auto log news
*/
function btn_all_auto_log_news_onclick(p_forms, p_action_url, p_target){
	frm_submit(p_forms, p_action_url, p_target) ;
}
/*
 * ham thuc hien auto save log news 
 * @param
 *  p_title      title bai viet
 *  p_summary    tom tat dai bai viet
 *  p_body      noi dung bai viet
 */
function auto_save_log_news(){
    v_url = CONFIG.BASE_URL+'ajax/news/act_auto_update_log_save_news/';
    frm_submit(document.frm_dsp_single_item, v_url, 'iframe_submit');
}
// end 08/08/2016 TuyenNT xay_dung_co_che_auto_save_bai_trong_ocm_24h

//Begin 10-08-2016 : Thangnb su_kien_chua_banner
function chon_su_kien_gan_banner_khach_hang(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_su_kien ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_su_kien+=(v_danh_sach_id_su_kien=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }        
	if(v_danh_sach_id_su_kien=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id_su_kien.value;
    v_danh_sach_id_su_kien = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_su_kien: v_danh_sach_id_su_kien;
    var v_url = CONFIG.BASE_URL+'ajax/su_kien_chua_banner/dsp_su_kien_gan_banner/'+v_danh_sach_id_su_kien;
    window.opener.AjaxAction('div_danh_sach_su_kien', v_url);   
    window.close();    
}
//End 10-08-2016 : Thangnb su_kien_chua_banner
/* Begin anhpt1 12/08/2016 xy_ly_1_bai_viet_gan_nhieu_su_kien */
/*
* anhpt1 add 15/08/2016
* Chon sự kiện vào bài viết
*/
function chon_su_kien_cho_bai_viet(p_forms, p_control_id){  
	var v_record_count = p_forms.hdn_record_count.value*1;    
    var v_count =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id_"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_id_event = p_check_obj.value;
            v_ten_event = eval("p_forms.hdn_event_"+i+".value");    
            window.opener.them_event_cho_bai_viet(p_control_id, v_id_event, v_ten_event);
            v_count++;
        }
    }        	      
    if(v_count==0) {
        alert('Chưa có sự kiện nào được chọn.');
        return;
    }
    window.close();    
}

/*
* Phuonghv add 09/09/2015
* Thêm chủ đề vào ô text box profile và tag app
*/
function them_event_cho_bai_viet(p_id_control, p_tag_app_id, p_tag_app_name){    
    $("#"+p_id_control).tokenInput("add", {id:p_tag_app_id,name:p_tag_app_name});
}
/* End anhpt1 12/08/2016 xy_ly_1_bai_viet_gan_nhieu_su_kien */
/*
* anhpt1 add 08/09/2016
* Chon bai viet gan cau hinh bài pr liên quan
*/
function chon_bai_viet_gan_bai_pr_lien_quan(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }        
	if(v_danh_sach_id_bai_viet=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id.value;
    var v_hdn_id_cau_hinh = window.opener.frm_update_item.hdn_id_cau_hinh.value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/cau_hinh_bai_pr_lien_quan/dsp_bai_pr_gan_vao_bai_lien_quan/'+v_danh_sach_id_bai_viet+'/0/'+v_hdn_id_cau_hinh;
    window.opener.AjaxAction('div_danh_sach_bai_viet', v_url);   
    window.close();    
}
/* Begin anhpt1 26/09/2016  quang_cao_chien_dich_heniken */
/*
* anhpt1 add 08/09/2016
* Load ghi chú theo loại quảng cáo
*/
function load_ghi_chu_quang_cao_theo_loai(value){
    v_value = parseInt(value);
    // nếu không là loại quảng cáo nào thì không hiển thị ghi chú
    if(v_value == 0){
        document.getElementById('div_html_ghi_chu').innerHTML = ''; 
        return;
    }
    var v_url = CONFIG.BASE_URL+'ajax/doi_tac_quang_cao_video/get_html_ghi_chu_doi_tac_quang_cao/'+v_value;
    AjaxAction('div_html_ghi_chu', v_url);   
}
/* End anhpt1 26/09/2016  quang_cao_chien_dich_heniken */
/*
 * Thực hiện mở popup de xuất event/profile
 * @param string $p_profile_id        
 * @return none
 */
function de_xuat_event(){
    var v_ds_cate = '';
    var options = $('#sel_category_list option');
    // Lấy danh sách chuyên mục được xuất bản bài viết
    $.map(options ,function(option) {
        if(option.value != ''){
            v_ds_cate += option.value + ',';
        }
    });
    if(v_ds_cate == ''){ 
        alert('Bạn chưa chọn chuyên mục xuất bản');
        return;
    }
    var v_content_body = '';
    // Lấy nội dung được nhập cho bài viết
    if (window.CKEDITOR.instances.txt_body){
        var v_content_body = CKEDITOR.instances.txt_body.getData();
        // Xử lý loại bỏ các thẻ HTML trong nội dung bài viết
        v_content_body = v_content_body.replace(/<\/?[^>]+>/gi, '');
    }
    var v_keyword = ''; 
    // Lấy nội dung được nhập cho keyword
    if(document.getElementById('txt_keywords')){
        var v_keyword = $('#txt_keywords').val();
    }
    if(v_content_body == '' && v_keyword == ''){
        alert('Bạn chưa nhập nội dung bài viết và chưa nhập keyword cho bài viết');
        return;
    }
    v_id_event 		= $('#hdn_event_id').val();
    v_id_event_sub 	= $('#hdn_event_sub_search').val();
    var v_url = url_modul_news+'/dsp_de_xuat_su_kien?';
    $.ajax({
            url: v_url,
            type: "POST",
            data:{
                v_content_body:v_content_body,
                v_keyword:v_keyword,
                v_ds_cate:v_ds_cate,
                v_id_event: v_id_event,
                v_id_sub: v_id_event_sub
            },
            success: function (data)
            {
                p_width = 1200;
                p_height = 700;
                mo_cua_so_popup_overlay('',p_width, p_height);
                $('#_box_popup').html(data);
                v_height = document.getElementById('div_dsp_all_item').offsetHeight;
                v_height = v_height + 10;
                document.getElementById('_box_popup').style.height = v_height+'px';
                document.getElementById('_box_popup').style.backgroundColor = 'white';
            }
       });
}
/*
 *anhpt1 hàm lấy dữ liệu sự kiện và profile được chọn 
 * @return string
 */
/*
 *anhpt1 hàm lấy dữ liệu sự kiện và profile được chọn 
 * @return string
 */
function lay_su_kien(){
    // Lấy giá trị event
    v_data_event = $('input[name=chk_item_id_event]:checked').val();
    if(v_data_event != '' && typeof v_data_event !== 'undefined'){
        v_data = v_data_event.split('_');
        v_id_event = v_data[0];
        v_name_event = v_data[1];
        $('#hdn_event_id').val(v_id_event);
        $('#txt_event_search').val(v_name_event);
    }
    v_record_count = document.getElementById('hdn_record_count').value;
    var v_data_news = [];
    if(v_record_count != ''){
        for(var i = 0;i< v_record_count;i++){
            v_check = document.getElementById('chk_item_id'+i).checked;
            if(!v_check){ continue;}
            v_data_event_sub = document.getElementById('chk_item_id'+i).value;
            if(v_data_event_sub != '' && typeof v_data_event_sub !== "undefined"){
                v_data_news.push(v_data_event_sub);   
            }
        }
        if(v_data_news.length > 5){
            alert('Bạn chỉ được phép chọn 5 sự kiện phụ');
            return;
        }
        for(var j = 0;j< v_data_news.length;j++){
            v_data = v_data_news[j].split('_');
            // Thêm dữ liệu cho event
            them_event_cho_bai_viet('txt_event_sub_search',v_data[0] , v_data[1]);
        }
    }
    if((v_data_event == '' || typeof v_data_event === 'undefined') && v_data_news.length == 0){
        alert('Bạn chưa chọn sự kiện chính / phụ');
        return;
    }
    dong_cua_so_popup_overlay();
}
/* Begin: Tytv - 17/10/2016 - quan_ly_quiz */
/**
 * Hàm thực hiện thay đổi loại quiz
 * @author  Tytv <tytv@24h.com.vn>
 * @date    17-10-2016
 * 
 * @returns Null
 */
function change_quiz_type() {  
    if(confirm('Các thông tin đã nhập sẽ bị mất. Bạn muốn tiếp tục?')){
        var type = $('#c_loai').val();
        var id = $('#hdn_item_id').val();
        // thay đổi thông tin ảnh template
        $('.img_template_quiz').hide();
        $('#btn_view_template_quiz'+type).show();
        $("#hdn_type_quiz" ).val(type);
        // Load dữ liệu từ url
        $.ajax({url: v_url_modul_ajax+'/dsp_change_quiz_type?id='+id+'&type='+type, success: function(result){
            if(result){
                var v_arr_result = result.split('#@@_@@#'); 
                $('#zone_quiz_question_and_answer').html(v_arr_result[0]);
                $('#zone_quiz_result').html(v_arr_result[1]);
            }
        }});
    }else{
        var v_type = $("#hdn_type_quiz" ).val();
        $('#c_loai').val(v_type);
    }
}
/**
 * Hàm thực hiện thêm câu trả lời theo loại
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function add_single_answer(p_loai,p_vi_tri_cau_hoi) { 
    // Load dữ liệu từ url
    var p_tong_luot = parseInt($('#tong_luot_them_tra_loi_q'+p_vi_tri_cau_hoi).val());
    switch (p_loai){
        case 1:
        case 2:
        case 4:
        case 5:
        case 6:
        case 7:
            var obj_answer = document.getElementsByClassName('tr_tra_loi_q'+p_vi_tri_cau_hoi);
            if(obj_answer.length>=max_record_answer){
                alert('Câu hỏi đã có tối đa '+max_record_answer+' câu trả lời');
                return;
            }
            break;
    }
    p_trong_so = 999;
    if(document.getElementById('tr_insert_answer_loai'+p_loai+'_q'+p_vi_tri_cau_hoi)){
        // Lấy div cuối cùng được thêm của câu trả lời
        objPreviousElement = document.getElementById('tr_insert_answer_loai'+p_loai+'_q'+p_vi_tri_cau_hoi).previousElementSibling;
        if(objPreviousElement){
            var idPostion = objPreviousElement.id.replace("tr_tra_loi_", "c_trong_so_tl_");
            if(document.getElementById(idPostion)){
                // Lấy giá trị trọng số  câu trả lời cuối cùng
                var valuePostion = parseInt(document.getElementById(idPostion).value);
                if(valuePostion > 0 && valuePostion  != 999){
                    p_trong_so = valuePostion+1;
                }
            }
        }
    }
    var v_vi_tri = p_tong_luot+1;
    $.ajax({url: v_url_modul_ajax+'/dsp_add_single_answer?loai='+p_loai+'&q='+p_vi_tri_cau_hoi+'&a='+p_tong_luot+'&p_trong_so='+p_trong_so, success: function(result){
        if(result){
            $('#tr_insert_answer_loai'+p_loai+'_q'+p_vi_tri_cau_hoi).before(result);
            $('#tong_luot_them_tra_loi_q'+p_vi_tri_cau_hoi).val(v_vi_tri);
        }
    }});
}
/**
 * Hàm thực hiện Xóa câu trả lời theo loại
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function delete_multi_answer(p_stt_cau_hoi) { 
    var check_count = $('input.chk_tra_loi_q'+p_stt_cau_hoi+':checked').length;
    if(check_count>0){
        if(confirm('Bạn có chắc muốn xóa các câu trả lời của câu hỏi '+p_stt_cau_hoi+' đã chọn không?')){
            //$('input.chk_tra_loi_q'+p_stt_cau_hoi+':checked').parent('tr.tr_cau_tra_loi_'.$v_vi_tri.'"'+p_ten_doi_tuong).remove();
            $('input.chk_tra_loi_q'+p_stt_cau_hoi).each(function () {
                if(this.checked){
                    var s_id = $(this).attr('id');
                    var s_id_dele = s_id.replace('chk_','tr_');
                    $('#'+s_id_dele).remove();
                }
            });
        }
    }else{
        alert('Bạn chưa chọn câu trả lời nào?');
    }
}

/**
 * Hàm thực hiện thêm câu hỏi
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function add_single_question(p_loai) { 
    // Load dữ liệu từ url
    var p_tong_luot = parseInt($('#tong_luot_them_cau_hoi').val());
    var list_q = document.getElementsByClassName('tbl_q');
    if(list_q.length>=max_record_question){
        alert('Chỉ được phép nhập tối đa '+max_record_question+' câu hỏi cho quiz');
    }else{
        var v_vi_tri = p_tong_luot+1;
        $.ajax({url: v_url_modul_ajax+'/dsp_add_single_question?loai='+p_loai+'&q='+p_tong_luot, success: function(result){
            if(result){
                $('#add_question').before(result);
                $('#tong_luot_them_cau_hoi').val(v_vi_tri);
            }
        }});
    }
}
/**
 * Hàm thực hiện xóa câu hỏi quiz
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function delete_single_answer(p_stt_cau_hoi,p_stt_tra_loi) {
    if(confirm('Bạn có chắc muốn xóa câu trả lời đã chọn không?')){
         $('#tr_tra_loi_q'+p_stt_cau_hoi+'_a'+p_stt_tra_loi).remove();
    }
}
/**
 * Hàm thực hiện xóa câu hỏi quiz
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function delete_single_question(p_stt_cau_hoi) {
    if(confirm('Bạn có chắc muốn xóa câu hỏi đã chọn không?')){
         $('#tbl_q'+p_stt_cau_hoi).remove();
    }
}
/**
 * Hàm thực hiện xóa 1 kết quả quiz
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function delete_single_result(p_stt) {
    if(confirm('Bạn có chắc muốn xóa kết quả đã chọn không?')){
         $('#tr_r'+p_stt).remove();
    }
}

/**
 * Hàm thực hiện thêm 1 kết quả
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function add_single_result(p_loai) { 
    // Load dữ liệu từ url
    var p_tong_luot = parseInt($('#tong_luot_them_kq').val());
    var list_r = document.getElementsByClassName('tr_r');
    if(list_r.length>=max_record_result){
        alert('Chỉ được phép nhập tối đa '+max_record_result+' kết quả cho quiz');
    }else{
        var v_vi_tri = p_tong_luot+1;
        $.ajax({url: v_url_modul_ajax+'/dsp_add_single_result?loai='+p_loai+'&r='+p_tong_luot, success: function(result){
            if(result){
                $('#tr_insert_result').before(result);
                $('#tong_luot_them_kq').val(v_vi_tri);
            }
        }});
    }
}

/**
 * Hàm thực hiện Xóa câu trả lời theo loại
 * @author  Tytv <tytv@24h.com.vn>
 * @date    18-10-2016
 * @returns Null
 */
function delete_multi_result() { 
    var check_count = $('input.chk_r:checked').length;
    if(check_count>0){
        if(confirm('Bạn có chắc muốn xóa các kết quả đã chọn không?')){
            $('input.chk_r').each(function () {
                if(this.checked){
                    var s_id = $(this).attr('id');
                    var s_id_dele = s_id.replace('chk_','tr_');
                    $('#'+s_id_dele).remove();
                }
            });
        }
    }else{
        alert('Bạn chưa chọn kết quả nào đề xóa?');
    }
}
function btn_preview_onclick_new_window(p_forms, p_action_url, p_target)
{
    window.open(p_action_url, p_target, 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')
    frm_submit (p_forms, p_action_url, p_target);
}
// xóa 1 ảnh đã dduocj chọn của quiz
function btn_xoa_anh_upload_quiz(p_id)
{
    if($('#txt_'+p_id)){
        $('#txt_'+p_id).val('');
    }
    if($('#zone_view_img_'+p_id)){
        $('#zone_view_img_'+p_id).html('');
    }
    $('#btn_view_img_'+p_id).css('visibility','hidden');
    $('#btn_delete_img_'+p_id).css('visibility','hidden');
    
}

function frm_submit_selecting_quiz(p_form_obj,p_poll_display,p_is_editor)
{
    v_id = p_form_obj.chk_item_id.value;
    v_width = 210;
	v_height = 240;
    if (v_id <= 0 || v_id == '') {
        alert("Chưa có quiz nào được chọn!");
    } else {              
        if (typeof p_poll_display != undefined && p_poll_display != '') {

            v_content = '<!--noidungbaiquiz_'+v_id+'-->';
            v_content = '<p align="center">'+v_content+'</p>';
            /*Begin 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/
            v_content = '<div class="data-embed-code-quiz"><p align="center">'+v_content+'</div></p>';
            /*End 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/

            if (p_is_editor == true) {
                // FCKeditor
                // oEditor = window.opener.FCKeditorAPI.GetInstance(p_poll_display);
                // oEditor.InsertHtml(v_content);
                // CKeditor
                oEditor = window.opener.CKEDITOR.instances[p_poll_display];
                oEditor.insertHtml(v_content);
            } else {
                window.opener.document.getElementById(p_poll_display).innerHTML = v_content;
            }
        }
        window.close();
    }
    return false;
}
 
function frm_submit_have_confirm(p_forms, p_action_url, p_target, p_obj_check, p_text_alert) {
    // Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, p_obj_check, p_text_alert, function () {
        frm_submit(p_forms, p_action_url, p_target);
        v_is_ok = true;
    }, function () {
        list_uncheck_all(p_forms, p_obj_check);
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}
/*
* @desc: Hàm thay đường dẫn ảnh CROP khi thay đổi cột hiển thị của bài quiz
* @author: Tytv - 17/11/2016
* @returns 
*/
function change_display_colums_for_quiz(){
    var v_cot_hien_thi_pc = $("#c_cot_hien_thi_pc").val();
    var sum = $(".cls_crop_image_quizz").length;
    var v_rep = '';
    if(v_cot_hien_thi_pc!=1){
        v_rep = "_preview/240/160?free_size=0";
    }else{
        v_rep = "_preview/0/0?free_size=1";
    }
    $(".cls_crop_image_quizz").each(function( index ) {
            var txt = $( this ).attr("onclick");
            var txt1 = $( this ).attr("lang");
            $( this ).attr("onclick",txt.replace(txt1, v_rep));
      });
    $(".cls_crop_image_quizz").attr("lang",v_rep);    
}

/* End: Tytv - 17/10/2016 - quan_ly_quiz */

/* begin 18/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo */
/*
* @desc: Hiển thị đồng hồ đếm ngược
* @author: trungcq 04-08-2016
* @param string p_div_id   ID div hiển thị countdown
* @param string p_date_end Thời gian kết thúc countdown
* @returns 
*/
function set_time_coundown(p_div_id, p_hdn_id, p_date_end){
   // lấy thời gian hiện tại
   v_datetime_current = new Date();
   v_count_second = (p_date_end-v_datetime_current);
   if (v_count_second*1 < 1 || v_count_second*1 == 0){
       if(document.getElementById(p_div_id)) document.getElementById(p_div_id).style.display="none";
       if(document.getElementById(p_hdn_id)) document.getElementById(p_hdn_id).value="0";
   } else {
       v_minute = Math.floor(v_count_second/(60*1000),0);
       v_count_second = v_count_second - (v_minute*60*1000);
       v_second = Math.floor(v_count_second/(1000),0);
       if(document.getElementById('span_countdown_minute')) {
           v_timeminute = document.getElementById('span_countdown_minute');
           if(v_minute < 10) v_minute="0"+v_minute;
           v_timeminute.innerHTML = v_minute;
       }
       if(document.getElementById('span_countdown_second')) {
           v_timesecond = document.getElementById('span_countdown_second');
           if(v_second < 10) v_second="0"+v_second;
           v_timesecond.innerHTML = v_second;
       }
       if(document.getElementById(p_hdn_id)) document.getElementById(p_hdn_id).value="1";
   }
}
/* end 18/10/2016 TuyenNT nang_cap_chuc_nang_soan_tin_bai_cho_phep_gui_mail_seo */
/* Begin anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
// load div loại video nivea
function load_div_loai_video_nivea(value){
    // nếu là loại quảng cáo nivea, thì hiển thi div chọn loại giải đấu
    if(value == 'flashWrite'){




        document.getElementById('div_loai_video_nivea').style.display = 'none';
    }else{
        document.getElementById('div_loai_video_nivea').style.display = 'block';
    }
}
// load div loại video nivea
/* 
 * Màn hình bài tường thuật
 * */
function load_div_loai_video_nivea_bai_tuong_thuat(value){
    // nếu là loại quảng cáo nivea, thì hiển thi div chọn loại giải đấu
    if(value == 1){
        document.getElementById('div_loai_video_nivea').style.display = 'block';
    }else{
        document.getElementById('div_loai_video_nivea').style.display = 'none';
    }
}
/* End anhpt1 21/10/2016  xy_ly_ma_tracking_video_nevia */
/* begin 3/11/2016 TuyenNT bo_sung_chuc_nang_crop_anh_chia_se_mxh_24h_dv */
/**
 * Function:  crop_image crop anh trong ocm
 * param: p_forms 
 * param: p_action_url
 * param: p_target
 **/
function crop_image_mxh(p_forms, p_action_url, p_target)
{
    window.open(p_action_url, p_target, 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
    frm_submit (p_forms, p_action_url, p_target);
}
/* end 3/11/2016 TuyenNT bo_sung_chuc_nang_crop_anh_chia_se_mxh_24h_dv */
/**
 * Function:  Hàm để check dung lượng ảnh gif trước khi upload
 * param: obj: đối tượng ảnh gif
 * param: p_type_img: loại ảnh cần check: gif,jpg,png....
 **/
function check_dung_luong_image_truoc_khi_upload(obj,p_max_size,p_thong_bao,p_type_img){
    // lấy đường dẫn ảnh
    url = obj.value;
    // nếu loại ảnh là dạng ảnh không cần check thì bỏ qua
    if(typeof p_type_img !== 'undefined' && url.indexOf(p_type_img) <= 0){
        return true;
    }
    if (!obj.value == ""){
        var img=obj.files[0].size;
        // Nếu kích cỡ ảnh lớn hơn max thì báo lỗi luôn
        if(img >p_max_size){
            alert(p_thong_bao);
            obj.value= "";
            return false;
        }
        return true;
    }
}
/* begin 19/12/2016 TuyenNT dieu_chinh_co_che_hien_thi_su_kien_cho_1_so_su_kien_khong_con_dung_24h */
// Ham check all cac checkbox tren man hinh danh sach
function check_all_item_sk(p_frm, chk_object){
    var v_is_checked = chk_object.checked;
    var v_record_count = p_frm.hdn_record_count.value*1;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_frm.chk_hien_thi"+i);
        if(p_check_obj){
            p_check_obj.checked = v_is_checked;
        }
    }
}
/* end 19/12/2016 TuyenNT dieu_chinh_co_che_hien_thi_su_kien_cho_1_so_su_kien_khong_con_dung_24h */
/*
* @desc: Chọn tất cả các chuyên mục theo id cấu hình
* @author: anhpt1 
* @returns 
*/
function check_all_by_id_config(obj_checked){
    v_arr_cate = v_arr_cate_id_nhom;
    // Kiếm tra xem có mảng chuyên mục nào được cấu hình
    if(v_arr_cate.length <=0){return;}
    $('.chk_category').attr('checked',false);
    // Lặp theo từng chuyên mục
    for(i = 0;i<v_arr_cate.length;i++){
        v_id = v_arr_cate[i];
        obj = document.getElementById('chk_category_'+v_id);
        if (obj == null){continue;}
        // Lấy thẻ input nằm trong div
        obj_input = obj.getElementsByTagName('input');
        if(obj_checked){
            obj_input[0].checked = true;
            $('input[data-parent="'+v_id+'"]').each(function(i){
                $(this).attr("checked", obj_input[0].checked); 
            });
        }else{
            obj_input[0].checked = false;
            $('input[data-parent="'+v_id+'"]').each(function(i){
                $(this).attr("checked", obj_input[0].checked); 
            });
        }
    }
}
/*
* @desc: load hien thi bai pr theo link
* @author: anhpt1 
* @returns 
*/
function loai_bai_pr_theo_link(value){
    if(value == 2){
    	document.getElementById('div_bai_pr_lien_quan_link_eva').style.display="none";
    	document.getElementById('div_bai_pr_lien_quan_link_kh').style.display="block";
    	document.getElementById('div_buttom_bai_pr_lien_quan_link_kh').style.display="block";
    }else{
    	document.getElementById('div_bai_pr_lien_quan_link_eva').style.display="block";
    	document.getElementById('div_bai_pr_lien_quan_link_kh').style.display="none";
    	document.getElementById('div_buttom_bai_pr_lien_quan_link_kh').style.display="none";
    }
}

 /*
* @desc: hàm thêm khoảng thời gian
* @author: anhpt1 
* @returns 
*/
function add_item_bai_pr_link_khac_hang(){
	var obj = document.getElementsByClassName("bai_pr_link_khac_hang");
    // Chỉ được phép thêm khoảng thời gian được cấu hình
    if(parseInt(obj.length) >= v_sl_bai_hien_thi ){ alert('Bạn chỉ được phép thêm '+v_sl_bai_hien_thi+' bài pr link khách hàng !'); return;}
	v_stt_pr_gian_tiep = 0;
    if(obj.length > 0){
        v_tong_pr_link_khach_hang =  parseInt(obj.length - 1);
        // Lấy tên cuối cùng của link khách hàng
        v_name_khoang_thoi_gian =  obj[v_tong_pr_link_khach_hang].id;
        // lấy số thứ tự cuối cùng khoảng thời gian
        v_stt_pr_cuoi = v_name_khoang_thoi_gian.replace('bai_pr_link_khac_hang','');
        // lấy thứ tự khoảng thoi tiếp theo cần thêm
        v_stt_pr_gian_tiep = parseInt(v_stt_pr_cuoi) + 1;
    }
	$.ajax({url: v_url_modul_ajax+'/dsp_html_link_khac_hang/'+v_stt_pr_gian_tiep+'/'+(v_stt_pr_gian_tiep+1)+'/'+0, success: function(result){
        if(result){
            $( "#div_bai_pr_lien_quan_link_kh" ).append(result);
        }
    }});
}
/*
* @desc:xóa khoảng thời gian bài PR theo list checkbox
* @author: anhpt1 
* @param string v_id_items xóa khoảng thời gian bài PR
* @returns 
*/
function delete_list_pr_link_khac_hang(){
    if(!confirm('Nếu bạn xóa tin bài này thì toàn bộ số liệu pageview của tin bài này trên thongke99 CŨNG BỊ XÓA THEO, Bạn có chắc chắn muốn xóa không?')){
        return;
    }
    var obj = document.getElementsByClassName("container_bai_pr_link_khac_hang");
	v_tong_pr_link_kh =  parseInt(obj.length - 1);
	// Lấy tên cuối cùng của khoảng thời gian
	v_name_pr_link_kh =  obj[v_tong_pr_link_kh].id;
	// Lấy thứ tự khoảng thời gian cuối cùng
	v_stt_pr_link_kh = v_name_pr_link_kh.replace('container_bai_pr_link_khac_hang','');
	// lấy thứ tự câu khoảng thời gian tiếp theo
	v_stt_pr_link_kh_tiep = parseInt(v_stt_pr_link_kh) + 1;
    v_count = 0;
    if(v_stt_pr_link_kh_tiep > 0){
        for(i = 0;i<v_stt_pr_link_kh_tiep;i++){
            if(document.getElementById('chk_pr_lien_quan_kh'+i) && document.getElementById('chk_pr_lien_quan_kh'+i).checked){
                /* Begin: 6-6-2019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
                if(document.getElementById('hdn_pk_cau_hinh_bai_pr_bai_viet_'+i)){
                    // Thực hiện 1 cấu hình pr news
                    v_url = CONFIG.BASE_URL +'ajax/cau_hinh_bai_pr_lien_quan/act_delete_cau_hinh_bai_pr_lien_quan_theo_id/?p_id_cau_hinh_bai_viet='+document.getElementById('hdn_pk_cau_hinh_bai_pr_bai_viet_'+i).value + '&p_id_cau_hinh='+document.getElementById('hdn_fk_cau_hinh_bai_pr_'+i).value;
                    frm_submit(frm_update_item, v_url, 'frm_submit');
                }
                /* End: 6-6-2019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
                
                $("#container_bai_pr_link_khac_hang"+i).remove();
                v_count = v_count +1;
            }
        }
    }
    if(v_count == 0){
        alert('Chưa có đối tượng nào được chọn');
    }
}

/* Begin 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */
/**
 * Hàm chèn thêm từ khóa vào các text box tương ứng
 * @param {type} element : giá trị của select box
 * @param {type} object_need_fill : các đối tượng cần chèn
 */
function fill_keyword_to_textbox(element, object_need_fill){
    var keyword = element.value;
    if(keyword && object_need_fill.length > 0){
        for (i in object_need_fill) {
            //Đối tượng cần insert text
            ele_id = object_need_fill[i].id;
            if( $('#'+ele_id) ){
                //Giá trị hiện tại
                cur_val = $('#'+ele_id).val();
                //Giá trị sau khi chèn
                delimiter = ' ';
                if(typeof object_need_fill[i].delimiter != 'undefined'){
                    delimiter = object_need_fill[i].delimiter;
                }
                new_val = keyword + delimiter + cur_val;
                //Kiểm tra max length nếu có, và chèn text
                if(typeof object_need_fill[i].max_length != 'undefined'){
                    max_length = parseInt(object_need_fill[i].max_length);
                    current_length = new_val.length;
                    if( current_length >= max_length ){
                        $('#error_'+ele_id).html('Không thể chèn thêm từ khóa. Số lượng kí tự tối đa là '+max_length);
                    } else {
                        $('#'+ele_id).val(new_val);
                        $('#error_'+ele_id).html('');
                    }
                } else {
                    $('#'+ele_id).val(new_val);
                }
                //Thêm sk này để trigger vào hàm setCountdown
                $('#'+ele_id).keyup();
            }
        }
    }
}
/* End 08/03/2017 LuanAD XLCYCMHENG_16889_toi_uu_seo_bai_viet */

//Begin 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet
/*
	p_form_obj : form submit
	p_row_id : id row duoc chon
	p_target_id : id textbox hien thi ket qua
*/
function frm_submit_select_one_tin_lien_quan_noi_dung_bai_viet_doi_tac(p_form_obj, p_row_id, p_target_id)
{
	if (typeof(p_form_obj) != 'undefined' && typeof(p_row_id) != 'undefined' && typeof(p_target_id) != 'undefined') {
		v_id = p_form_obj['hdn_item_id'+p_row_id].value;
		v_title = p_form_obj['hdn_title_'+p_row_id].value;
		/* Begin 09/08/2017 Tytv fix_loi_ko_hien_thi_anh_bai_quan_tron_noi_dung_bai_viet */
        if(v_title != ''){
            v_title = v_title.replace(/\"/g,  '”').replace(/&#34;/g, '”');
        }
        /* Begin 09/08/2017 Tytv fix_loi_ko_hien_thi_anh_bai_quan_tron_noi_dung_bai_viet */
		v_url = p_form_obj['hdn_url_'+p_row_id].value;
		v_sapo = p_form_obj['hdn_sapo_'+p_row_id].value;
		v_anh_dai_dien = p_form_obj['hdn_anh_dai_dien_'+p_row_id].value;
		oEditor = window.opener.CKEDITOR.instances[p_target_id];
		if(oEditor) {
			/*Begin trungcq 23-05-2017 XLCYCMHENG_21898_sua_text_hien_thi_box_tin_lien_quan*/
			v_content = '<div class="bv-lq-inner-hide"><div class="img-bv-lq imgFloat"><a href="'+v_url+'" title="'+v_title+'"><img class="img_tin_lien_quan_trong_bai" src="'+v_anh_dai_dien+'" alt="'+v_title+'" /></a></div><div class="content-bv-lq"><div class="title-bv"><a class="url_tin_lien_quan_trong_bai" href="'+v_url+'">'+v_title+'</a></div><div class="ct-bv"><p>'+v_sapo+'</p></div><div class="see-now"><a href="'+v_url+'" title="">Bấm xem >></a></div></div></div><p class="title_for_show">'+v_title+'</p>';
			/*End trungcq 23-05-2017 XLCYCMHENG_21898_sua_text_hien_thi_box_tin_lien_quan*/
			oEditor.setData(v_content);
			$('.news_'+v_id.value).hide();   
			window.close(); 
		}
	}
}  

function insert_bai_lien_quan_trong_noi_dung_bai()
{
    v_str_before = '<div class="bv-lq"><!--begin_tlq_nd-->';
    v_str_after = '<!--end_tlq_nd--></div>';
    v_content = CKEDITOR.instances.txt_bai_lien_quan_noi_dung_bai_viet_24h.getData();
	v_content = v_content.replace('bv-lq-inner-hide','bv-lq-inner');
	v_content = v_content.replace(/<p class="title_for_show"[^>]*>.*<\/p>/g, "");
	if (v_content == '' || typeof(v_content) == 'undefined') {
		alert('Bạn chưa chọn bài liên quan 24h!');	
	} else {
    	//xoa thẻ p thừa không có số liệu               
   		v_content = v_str_before + v_content.replace(/<p>\s*<\/p>/gi, "") + v_str_after;    
            //CKEDITOR.instances.txt_body.insertHtml(v_content+'<br />');
            v_content_body = CKEDITOR.instances.txt_body.getData();
            CKEDITOR.instances.txt_body.setData(v_content_body + v_content);
	}
}

function frm_submit_select_bai_lien_quan_trong_noi_dung_bai(p_form_obj, p_target_id)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_content = '';
    v_arr_selected = new Array();
	
	v_id_checked  = p_form_obj['chk_item_id'].value;
	if (v_id_checked == '' || typeof(v_id_checked) == 'undefined') {
		alert("Chưa có bài nào được chọn!");
	} else {
		v_id = v_id_checked;
		v_stt = $('input[name=chk_item_id]:checked').attr('id').replace('chk_item_id','');
		v_title = p_form_obj['hdn_title_'+v_stt].value;
		/* Begin 09/08/2017 Tytv fix_loi_ko_hien_thi_anh_bai_quan_tron_noi_dung_bai_viet */
        if(v_title != ''){
            v_title = v_title.replace(/\"/g,  '”').replace(/&#34;/g, '”');
        }
        /* Begin 09/08/2017 Tytv fix_loi_ko_hien_thi_anh_bai_quan_tron_noi_dung_bai_viet */
		v_url = p_form_obj['hdn_url_'+v_stt].value;
		v_sapo = p_form_obj['hdn_sapo_'+v_stt].value;
		v_anh_dai_dien = p_form_obj['hdn_anh_dai_dien_'+v_stt].value;
		
		oEditor = window.opener.CKEDITOR.instances[p_target_id];
		if(oEditor) {
			/*Begin trungcq 23-05-2017 XLCYCMHENG_21898_sua_text_hien_thi_box_tin_lien_quan*/
			v_content = '<div class="bv-lq-inner-hide"><!--begin_img_tlq_nd--><div class="img-bv-lq imgFloat"><a href="'+v_url+'" title="'+v_title+'"><img class="img_tin_lien_quan_trong_bai" src="'+v_anh_dai_dien+'" alt="'+v_title+'" /></a></div><!--end_img_tlq_nd--><div class="content-bv-lq"><!--begin_title_tlq_nd--><div class="title-bv"><a class="url_tin_lien_quan_trong_bai" href="'+v_url+'">'+v_title+'</a></div><!--end_title_tlq_nd--><div class="ct-bv"><p>'+v_sapo+'</p></div><div class="see-now"><a href="'+v_url+'" title="">Bấm xem >></a></div></div></div><p class="title_for_show">'+v_title+'</p>';
			/*End trungcq 23-05-2017 XLCYCMHENG_21898_sua_text_hien_thi_box_tin_lien_quan*/
			oEditor.setData(v_content);
			window.close();
		}
	}
    return false;
}
//End 27-03-2017 : Thangnb chon_bai_lien_quan_trong_noi_dung_bai_viet

//Begin Lucnd 03-03-2017 : quan_tri_slot_dfp
function btn_update_list_onclick_not_check_item_id(p_forms, p_action_url, p_target, p_confirm_message){
	if(confirm(p_confirm_message))
	{
		frm_submit(p_forms, p_action_url, p_target) ;
	}
}
//End Lucnd 03-03-2017 : quan_tri_slot_dfp
/* begin 11/5/2017 TuyenNT xay_dung_tinh_nang_chon_bai_viet_day_len_zalo */
function btn_update_list_news_zalo_onclick(p_forms, p_action_url, p_target) {
    // Begin TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
    list_check_has_pr_checked(p_forms, p_action_url, p_target, 'chk_item_id', 'Bạn muốn XUẤT BẢN LÊN ZALO các bài viết đã chọn?', function () {
        frm_submit(p_forms, p_action_url, p_target);
    }, function () {
        list_uncheck_all(p_forms, 'chk_item_id');
    });
    // End TungVN 07-09-2017 - bo_sung_hien_thi_canh_bao_khi_thao_tac_bai_PR
}
/* end 11/5/2017 TuyenNT xay_dung_tinh_nang_chon_bai_viet_day_len_zalo */

//Begin 14-06-2017 trungcq XLCYCMHENG_22841_xay_dung_chuc_nang_quan_tri_cau_hinh_goi_y_vai_viet
/*
* trungcq add 15/06/2017
* Chon bai viet goi y vao box chi tiet bai viet
*/
function chon_bai_viet_goi_y(p_forms){  
    var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }        
    if(v_danh_sach_id_bai_viet=='') {
        alert('Chưa có đối tượng nào được chọn!');
        return;
    }
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id.value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/cau_hinh_goi_y_bai_viet/dsp_bai_viet_goi_y/'+v_danh_sach_id_bai_viet;
    window.opener.AjaxAction('div_danh_sach_bai_viet', v_url);   
    window.close();    
}

/* 
* Ham luu cac checkbox da chon o man hinh danh sach vao the hidden
* @author trungcq
*/
function luu_id_bai_viet_goi_y_da_chon(p_obj) 
{	
    var id_bai_viet_goi_y_da_chon = $('#hdn_id_bai_viet_goi_y_da_chon').val();
    if (p_obj.checked == true) {
        id_bai_viet_goi_y_da_chon = id_bai_viet_goi_y_da_chon.replace(',\"'+p_obj.getAttribute("data-cate")+'\"', '');
        id_bai_viet_goi_y_da_chon = id_bai_viet_goi_y_da_chon.replace(']', ',\"'+p_obj.getAttribute("data-cate")+'\"]');
    } else {
        id_bai_viet_goi_y_da_chon = id_bai_viet_goi_y_da_chon.replace(',\"'+p_obj.getAttribute("data-cate")+'\"', '');
    }
    $('#hdn_id_bai_viet_goi_y_da_chon').val(id_bai_viet_goi_y_da_chon);
}

function luu_tat_ca_id_bai_viet_goi_y_da_chon () {
    $('.case').each(function () {
        var obj = $(this);
        v_obj_id = obj.attr('id');
        luu_id_bai_viet_goi_y_da_chon(document.getElementById(v_obj_id));
    });
}

function chinh_sua_cau_hinh_goi_y_bai_viet_theo_lo(p_forms, p_action_url, p_target ){
    cau_hinh_goi_y_bai_viet_da_chon = document.getElementById('hdn_id_bai_viet_goi_y_da_chon').value;
    if (cau_hinh_goi_y_bai_viet_da_chon == '[""]') {
            alert('Chưa có ID nào được chọn!');
            return;
    } else {
        cau_hinh_goi_y_bai_viet_da_chon = cau_hinh_goi_y_bai_viet_da_chon.replace('["","', '');
        cau_hinh_goi_y_bai_viet_da_chon = cau_hinh_goi_y_bai_viet_da_chon.replace('"]', '');
        cau_hinh_goi_y_bai_viet_da_chon = cau_hinh_goi_y_bai_viet_da_chon.replace('"', '');
        cau_hinh_goi_y_bai_viet_da_chon = cau_hinh_goi_y_bai_viet_da_chon.replace(/"/gi, '');
        var v_url= p_action_url + '&id_cau_hinh_da_chon='+cau_hinh_goi_y_bai_viet_da_chon;
        btn_add_onclick(p_forms, v_url, p_target);
    }
}
// End 14-06-2017 trungcq XLCYCMHENG_22841_xay_dung_chuc_nang_quan_tri_cau_hinh_goi_y_vai_viet

/* Begin 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
/**
 * Ẩn hiện 1 box theo checkbox
 * @param {type} element
 * @param {type} id
 */
function show_hide_box(element, id){
    obj_block = document.getElementById(id);
    if (element.checked) {
        obj_block.style.display = '';
    }else{
        obj_block.style.display = 'none';
    }
}
/* End 28/06/2017 LuanAD XLCYCMHENG_23374_bo_sung_nut_tich_ha_xb_24h */
/* Begin: Tytv - 20/07/2017 - chuan_hoa_code_script_file_video */
function chuan_hoa_code_video(p_forms)
{
    if(!p_forms.txt_chuan_hoa_video) return;
    v_code = p_forms.txt_chuan_hoa_video.value;
    if(v_code == ''){
        alert('Bạn chưa nhập code video cần chuẩn hóa');
        p_forms.txt_chuan_hoa_video.focus();
        return false;
    }
    $.ajax({
		url : "/ocm/ajax/news/dsp_chuan_hoa_code_video/",
        method: "POST",
        data: { code : v_code },
		success: function(result){
            p_forms.txt_chuan_hoa_video.value = result;
			alert('Code video đã được chuẩn hóa thành công');
		}
	});
}
/* End: Tytv - 20/07/2017 - chuan_hoa_code_script_file_video */
/*
//Begin 31-08-2017 : Thangnb toi_uu_tim_kiem_tieu_de_bai_viet
 * Ham kiem tra tieu de bai viet tai box tim kiem
 * p_form_name : Ten form can kiem tra
 * p_name_input_bai_viet : Ten cua truong input tieu de bai viet
*/
function form_filter_before_submit(p_form_name, p_name_input_bai_viet) {
	if ($('form[name="'+p_form_name+'"]')) {
		if ($('input[name="'+p_name_input_bai_viet+'"]')) {
			var val_title = $('input[name="'+p_name_input_bai_viet+'"]').val();
			if (typeof(val_title) != 'undefined' && val_title != '' && val_title != 'Ten bai viet' && val_title != 'Tên bài viết') {
				val_title_array = val_title.split(' ');
				if (val_title_array.length < 2) {
					alert('Tiêu đề tìm kiếm phải nhập ít nhất 2 từ !');
					$('input[name="'+p_name_input_bai_viet+'"]').focus();
					return false;
				}
			}
		}
		$('form[name="'+p_form_name+'"]').submit();
	}
}
//End 31-08-2017 : Thangnb toi_uu_tim_kiem_tieu_de_bai_viet

// Begin TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan
function close_loading_overlay() {
    if ($('#loading_overlay').length) {
        $('#loading_overlay').fadeOut();
    }
    clearTimeout();
}
// End TungVN 20-09-2017 - toi_uu_tinh_huong_cap_nhat_bai_2_lan
function openWindowUploadImageTitle(){
    var news_title  = document.frm_dsp_single_item.txt_title.value;
    window.open(CONFIG['BASE_URL']+'upload_image/?news_title='+news_title, '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}

/* Begin: Tytv - 22/09/2017 - toi_uu_tinh_chinh_menu_ngang_header */
/*
 * Ham hiển thị chuyên mục sau tích chọn
 * @author  Tytv - 22/09/2017
 * @param  checkbox_id id chung cua danh sach checkbox
 * @param  hdn_id id hdn luu gia tri truoc do
 * @param  hdn_id id cua hidden luu gia tri truoc do
 * @param  hdn_tong id cua hidden luu tong so doi tuong
 * @param  txt_input id cua textbox tim nhanh
 * @return  string
 */
function html_hien_thi_menu_duoc_chon(checkbox_id,hdn_id,txt_input){
    var tong_menu_hien_co = parseInt($('.tr_menu_ngang_header').length);
    var limit_menu = parseInt($('input#c_so_luong_max').val());
    if((tong_menu_hien_co+1)>limit_menu){
        alert('Chỉ được phép nhập tối đa '+limit_menu+' theo Số lượng chuyên mục tối đa hiển thị trên menu ngang header');
        $('#'+txt_input).val('');
        $('#'+txt_input).focus();
    }else{
        var stt_menu = document.getElementById('hdn_tong_so_them_menu').value;
        var id_duoc_chon = document.getElementById(hdn_id).value;
        stt_menu =  parseInt(stt_menu) +1;
        // chèn html vào đối tượng cần thêm
        var v_url = CONFIG.BASE_URL + 'ajax/menu_ngang_header/dsp_hien_thi_menu_duoc_chon/'+stt_menu+'/'+id_duoc_chon;
        console.log(v_url);
        $.ajax({
            type: "POST",
            url: v_url,
            data: {v_add_item: 1},
            async: false,
            success: function (result) {
                if(result!=''){
                    $('#zone-add-menu-header').before(result);
                    $('#hdn_tong_so_them_menu').val(stt_menu);
                    $('#'+txt_input).val('');
                    $('#'+txt_input).focus();

                }
            }
        });
    }
	
	return false;
}
/**
 * Hàm thực hiện Xóa 1 menu ngang header chi tiết 
 * @author  Tytv <tytv@24h.com.vn>
 * @date    25/09/2017
 * 
 * @returns Null
 */
function mnh_xoa_1_menu_ngang_header(p_vi_tri) { 
    p_vi_tri = parseInt(p_vi_tri);
    if($('#txt_name'+p_vi_tri)){
        var name_menu = $('#txt_name'+p_vi_tri).val();
        if(confirm('Bạn có chắc muốn xóa menu ngang header "'+name_menu+'" này không?')){
            if($('#tr_menu_ngang_'+p_vi_tri)){
                $('#tr_menu_ngang_'+p_vi_tri).remove();
                $('#hdn_tong_so_menu').val(p_vi_tri-1);
            }
        }
    }
}

/**
 * Hàm thực hiện Xóa nhiều highlight của 1 video
 * @author  Tytv <tytv@24h.com.vn>
 * @date    09-08-2016
 * 
 * @returns Null 
 */
function mnh_them_1_menu_ngang_header() { 
    var tong_menu_hien_co = parseInt($('.tr_menu_ngang_header').length);
    var limit_menu = parseInt($('input#c_so_luong_max').val());
    if((tong_menu_hien_co+1)>limit_menu){
        alert('Chỉ được phép nhập tối đa '+limit_menu+' theo Số lượng chuyên mục tối đa hiển thị trên menu ngang header');
    }else{
        var stt_menu = document.getElementById('hdn_tong_so_them_menu').value;
        stt_menu =  parseInt(stt_menu) +1;
        v_url = CONFIG.BASE_URL + 'ajax/menu_ngang_header/dsp_hien_thi_menu_duoc_chon/'+stt_menu+'/0';
        // Load dữ liệu từ url
        $.ajax({url: v_url, success: function(result){
            if(result!=''){
                $('#zone-add-menu-header').before(result);
                $('#hdn_tong_so_them_menu').val(stt_menu);
                $('#txt_loc_chuyen_muc_header').val('');
                $('#txt_loc_chuyen_muc_header').focus();

            }
        }});
    }
}
/* Begin: Tytv - 22/09/2017 - toi_uu_tinh_chinh_menu_ngang_header */
// Begin TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header
/**
 * Hàm hiển thị xem trước menu ngang header
 * @param {type} p_id
 * @returns {null}
 */
function mnh_preview_menu_ngang_header(p_id, p_forms) {
    if (p_id <= 0) {
        p_id = '';
    }
    var v_action_url = CONFIG.BASE_URL + 'ajax/menu_ngang_header/dsp_preview_menu_ngang_header/' + p_id + '?v=' + Math.random();
    window.open(v_action_url, 'Preview', 'width=1021, height=700, toolbar=no, status=yes, menubar=no, scrollbars=yes, resizable=yes');
    if (typeof p_forms != 'undefined') {
        frm_submit(p_forms, v_action_url, 'Preview');
    }
}
// End TungVN 28-09-2017 - toi_uu_tinh_chinh_menu_ngang_header
/* begin 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
/*
 * hàm thực hiện show background mã màu được chọn
 */
function show_background_ma_mau(){
    // lấy mã màu mà select box được chọn
    if(document.getElementById('sel_ma_mau')){
        var ma_mau = document.getElementById('sel_ma_mau').value;
    }
    // trả lại mã màu trong select box
    if(ma_mau == '-- Chọn --'){ // nếu người dùng chọn về mặc định thì background đổi về màu mặc định
        document.getElementById('sel_ma_mau').style.backgroundColor = '#fff';
    }else{
        document.getElementById('sel_ma_mau').style.backgroundColor = '#' + ma_mau;
    }
}
/* end 22/11/2017 TuyenNT xu_ly_hien_mau_tuy_chon_cho_tab_cm_cap_2 */
//Begin Tytv - 20/3/2018  toi_uu_hien_thi_menu_ngang_header_mobile
function xoa_icon(p_id){
    document.getElementById('ten_file_idx_'+p_id).innerHTML='';
	document.getElementById('hdn_url_idx_'+p_id).value='';
	document.getElementById('btn_xoa_anh_'+p_id).style.display='none';
}
//End Tytv - 20/3/2018  toi_uu_hien_thi_menu_ngang_header_mobile
// begin 15/04/2018 Tytv quan_tri_recommend_video
/*
* Tytv 16/04/2018
* Hàm cho phép ẩn hiển 1 đối tượng cụ thể
*/
function show_or_hide_object(p_div_show_hide_id) {
    var isHidden = $('#'+p_div_show_hide_id).is(':hidden');
    if(isHidden){
        $("#"+p_div_show_hide_id).show(1000);
    }else{
        $("#"+p_div_show_hide_id).hide(500);
    }    
}
/*
 * Ham kiem tra tieu de bai viet tai box tim kiem
 * p_form_name : Ten form can kiem tra
 * p_name_input_bai_viet : Ten cua truong input tieu de bai viet
*/
function form_filter_suggestion_recommend(p_form_name, p_name_input_bai_viet,p_action) {
   
	if ($('form[name="'+p_form_name+'"]')) {
		if ($('input[name="'+p_name_input_bai_viet+'"]')) {
			var val_title = $('input[name="'+p_name_input_bai_viet+'"]').val();
			if (typeof(val_title) != 'undefined' && val_title != '' && val_title != 'Ten bai viet' && val_title != 'Tên bài viết') {
				val_title_array = val_title.split(' ');
				if (val_title_array.length < 2) {
					alert('Tiêu đề tìm kiếm phải nhập ít nhất 2 từ !');
					$('input[name="'+p_name_input_bai_viet+'"]').focus();
					return false;
				}
			}
		}
        var hdn_item_id = $('#hdn_item_id').val();
        var hdn_item_type = $('#hdn_item_type').val();        
        var sel_category_id = $('#sel_category_id').val();
        var sel_status = $('#sel_status').val();
        var txt_news_id = $('#txt_news_id').val();
        var txt_news_name = $('#txt_news_name').val();
        var txt_tu_ngay = $('#txt_tu_ngay').val();
        var txt_den_ngay = $('#txt_den_ngay').val();
        
        // Load dữ liệu từ url
        $.ajax({
            type: "POST",
            url: '/ocm/recommend_video_common/dsp_filter_recommend_video_goi_y',
            data: {
                hdn_item_id:hdn_item_id,
                hdn_item_type:hdn_item_type,
                sel_category_id: sel_category_id,
                sel_status: sel_status,
                txt_news_id: txt_news_id,
                txt_news_name: txt_news_name,
                txt_tu_ngay: txt_tu_ngay,
                txt_den_ngay: txt_den_ngay,
            },
            async: false,
            success: function(result){
                if(result){                    
                    $('#wap_list_suggestion').html(result);
                   
                }
            }
        });
	}
    
}
// end 15/04/2018 Tytv quan_tri_recommend_video
/*
* hàm thực hiện tạo html chuyên mục chính theo chuyên mục phụ được chọn
* param : value giá trị của chuyên mục chính
*/
function create_html_main_cate_by_sub_cate(value){
    // Lấy giá trị chuyên mục phụ
   if(document.getElementById('div_main_cate_id'+value)){
       obj = document.getElementById('div_main_cate_id'+value);
       v_html_radion_main_cate = '';
       if(document.getElementById('item_id_'+value).checked){
            // Tạo HTML chuyen mục chính
            var v_html_radion_main_cate =  '<input type="radio" name="chk_item_main_cate_id" id="chk_item_main_cate_id'+value+'" value="'+value+'">';
       }
       obj.innerHTML = v_html_radion_main_cate;
       return;
   }
}

/*
* anhpt1 add 08/09/2016
* Chon bai viet gan cau hinh bài pr liên quan
*/
function frm_submit_select_video_news(p_forms,v_type){  
    // Lấy số lượng bài được chọn
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_arr_news_origin = new Array();
    // Lấy danh sách bài viết gốc để kiểm tra
    if(window.opener.$('#hdn_id_origin').length > 0 && window.opener.$('#hdn_recommend_video_news').length > 0){
        v_list_news_origin = window.opener.$('#hdn_id_origin').val();
        if(v_list_news_origin != ''){
            v_arr_news_origin = v_list_news_origin.split(',');
        }
        if(v_arr_news_origin.length <= 0 ){
            alert('Bạn phải chọn bài viết gốc!');
            return;
        }
    }
    v_arr_selected_news_id = new Array();
    if(window.opener.$('#hdn_list_id_news_add').length > 0){
        // Lấy các id bài viết đã được chọn từ trước
        v_list_id_news_add = window.opener.$('#hdn_list_id_news_add').val();
        if(v_list_id_news_add != ''){
            v_arr_selected_news_id = v_list_id_news_add.split(',');
        }
    }
    var v_mess_video_news = '';
    var v_sl_news = 0;
    var j = v_arr_selected_news_id.length;
    var v_arr_trang_thai = new Array();
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_trang_thai = $("#sel_trang_thai"+i).val();
            v_check_origin = v_arr_news_origin.indexOf(p_check_obj.value);
            if(v_check_origin >= 0){
                alert('Bài viết gốc '+p_check_obj.value+' đang nằm trong danh sách video gợi ý');
                return;
            }
            v_check = v_arr_selected_news_id.indexOf(p_check_obj.value);
            if(v_check < 0){
                v_arr_selected_news_id[j] = p_check_obj.value;
                v_arr_trang_thai[j] = new Array(p_check_obj.value, v_trang_thai);;
                j++;
            }
            v_sl_news++;
        }
    }
    // NSD chọn bài viết không phải loại Video
    if(v_mess_video_news != ''){
        alert(v_mess_video_news);
        return;
    }
	if(v_sl_news == 0) {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    v_list_id_news = v_arr_selected_news_id.join();
    var v_id_news_origin = 0;
    if(v_arr_news_origin.length>0){
        v_id_news_origin = v_arr_news_origin.join();
    }
    var hdn_item_id = window.opener.$('#hdn_item_id').val();
    var hdn_item_type = window.opener.$('#hdn_item_type').val();
    var sel_category_id = window.opener.$('#sel_category_id').val();
    var sel_status = window.opener.$('#sel_status').val();
    var txt_news_id = window.opener.$('#txt_news_id').val();
    var txt_news_name = window.opener.$('#txt_news_name').val();
    var txt_tu_ngay = window.opener.$('#txt_tu_ngay').val();
    var txt_den_ngay = window.opener.$('#txt_den_ngay').val();
    var v_close_popup = true;
    $.ajax({
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/recommend_video_common/dsp_filter_recommend_video_goi_y/'+v_list_id_news,
        data: {
            hdn_item_id:hdn_item_id,
            hdn_item_type:hdn_item_type,
            sel_category_id: sel_category_id,
            sel_status: sel_status,
            v_arr_status: v_arr_trang_thai,
            txt_news_id: txt_news_id,
            txt_news_name: txt_news_name,
            txt_tu_ngay: txt_tu_ngay,
            txt_den_ngay: txt_den_ngay,
            hdn_id_news_origin: v_id_news_origin,
            hdn_record_count: v_record_count,
        },
        // anhpt11
        async: false,
        success: function(result){
            if(result){
                if(result.indexOf("<!--err_mess_news-->") >= 0){
                    result = result.replace('<!--err_mess_news-->','');
                    alert(result);
                    v_close_popup = false;
                    return;
                }
                window.opener.$('#wap_list_suggestion').html(result);
                window.opener.$('#hdn_list_id_news_add').val(v_list_id_news);
                var v_html_page = '<span>[Trang 1]</span><a href="javascript:rcv_pagination_sugges_news(1,1)"> Trang sau &gt;&gt;</a>';
                window.opener.$('#container_rcv_pagination_sugges_news').html(v_html_page);
            }
        }
    });
    if(v_close_popup){
        window.close();
    }
}

/*
* anhpt1 add 08/09/2016
* Chon bai viet gan cau hinh bài pr liên quan
*/
function frm_submit_select_video_news_origin(p_forms,v_type){  
    var j = 0;
    var k = 0;
    var h = 0;
    var v_record_count = p_forms.hdn_record_count.value*1;
    // lấy danh sách id bài viết chính đã được ch
    obj_news_list = window.opener.document.getElementById("hdn_id_origin");
    v_news_list_id = obj_news_list.value;
    v_arr_news_list = new Array();
    if(v_news_list_id != ''){
        v_arr_news_list = v_news_list_id.split(',');
    }
    // Kiểm tra mảng bài viết gốc đã có dữ liệu chưa
    if(v_arr_news_list.length >0){
       k = v_arr_news_list.length;
    }
    v_arr_news_suges_id = new Array();
    if(window.opener.document.getElementById("hdn_record_count")){
        v_record_count_goi_y = window.opener.document.getElementById("hdn_record_count").value;
        if(v_record_count_goi_y > 0){
            for(var j=0;j<v_record_count_goi_y;j++){
                v_id_news_goi_y = window.opener.document.getElementById("hdn_id_bai_goi_y"+j).value;
                v_arr_news_suges_id[j] = v_id_news_goi_y;
            }
        }
    }
    v_arr_selected_news_id = new Array();
    v_arr_news_id_same = new Array();
    var v_sl_news = 0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            // Kiểm tra bài viết gốc với bài gợi ý đã chọn
            if(v_arr_news_suges_id.length > 0){
                v_check_id_goi_y = v_arr_news_suges_id.indexOf(p_check_obj.value);
                if(v_check_id_goi_y >= 0){
                    v_arr_news_id_same[h] = p_check_obj.value;
                    h++;
                }
            }
            v_check = v_arr_selected_news_id.indexOf(parseInt(p_check_obj.value));
            v_check_id_origin = v_arr_news_list.indexOf(p_check_obj.value);
            if(v_check < 0 && v_check_id_origin < 0){
                v_arr_selected_news_id[j] = parseInt(p_check_obj.value);
                v_arr_news_list[k] = p_check_obj.value;
                k++;
                j++;
            }
            v_sl_news++;
        }
    }
    // Kiểm tra xem bài viết gốc có bị trùng với bài video gợi ý hay không
    if(v_arr_news_id_same.length > 0){
        v_list_id_news_same = v_arr_news_id_same.join();
        alert('Danh sách bài viết gốc [ID:'+v_list_id_news_same+'] Đã tồn tại trong danh sách bài viết gợi ý!');
        return;
    }
	if(v_sl_news == 0) {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    v_list_id_news = v_arr_selected_news_id.join();
    // danh sách bài viết cần lưu lại để kiểm tra lần sau
    v_list_id_news_save = v_arr_news_list.join();
    obj_record = window.opener.document.getElementById("hdn_record_count_main");
    var v_close_popup = true;
    // Load dữ liệu từ url
    $.ajax({
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/recommend_video_common/dsp_html_main_list_news_recommend_video_by_news_id/'+v_list_id_news_save,
        async: false,
        success: function(result){
            if(result){
                if(result.indexOf("<!--err_mess_news_origin-->") >= 0){
                    result = result.replace('<!--err_mess_news_origin-->','');
                    alert(result);
                    v_close_popup = false;
                    return;
                }
                if(result.indexOf("<!--no_data-->") <= 0){
                   window.opener.$('#zone_selected_news_list_origin').html(result);
                   window.opener.$('#hdn_id_origin').val(v_list_id_news_save);
                }
            }
        }
    });
    if(v_close_popup){
        window.close();
    }
}
/*
* phân bài bài video gọi ý
*/
function rcv_pagination_sugges_news(p_current_page,type_pagi){
    if(!confirm('Các thay đổi bạn đã thực hiện có thể không được lưu.')){
        return;
    }   
    v_page_new = p_current_page +1;
    if(type_pagi == 0){
        v_page_new = p_current_page -1;
    }
    var hdn_item_id = $('#hdn_item_id').val();
    var hdn_item_type = $('#hdn_item_type').val();
    var sel_category_id = $('#sel_category_id').val();
    var sel_status = $('#sel_status').val();
    var txt_news_id = $('#txt_news_id').val();
    var txt_news_name = $('#txt_news_name').val();
    var txt_tu_ngay = $('#txt_tu_ngay').val();
    var txt_den_ngay = $('#txt_den_ngay').val();
    $.ajax({
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/recommend_video_common/dsp_filter_recommend_video_goi_y/?page='+v_page_new,
        data: {
            hdn_item_id:hdn_item_id,
            hdn_item_type:hdn_item_type,
            sel_category_id: sel_category_id,
            sel_status: sel_status,
            txt_news_id: txt_news_id,
            txt_news_name: txt_news_name,
            txt_tu_ngay: txt_tu_ngay,
            txt_den_ngay: txt_den_ngay,
        },
        async: false,
        success: function(result){
            if(result){
                if(result.indexOf("<!--no_data-->") > 0){
                    alert('Trang tiếp theo không có dữ');
                }else{
                    $('#wap_list_suggestion').html(result);
                    $('#hdn_list_id_news_add').val('');
                    v_html_pagi = html_pagination_sugges_news(v_page_new);
                    $('#container_rcv_pagination_sugges_news').html(v_html_pagi);
                }
            }
        }
    });
}
/*
* Hàm tạo HTML phân trang bài video gợi ý
*/
function html_pagination_sugges_news(p_current){
    v_html = '';
    // Nếu từ các trang tiếp theo thì sẽ hiển thị nút prev
    if(p_current > 1){
        v_html +='<a href="javascript:rcv_pagination_sugges_news('+p_current+',0)">&lt;&lt;Trang trước </a>';
    }
    v_html +='<span>[Trang '+p_current+']</span>';
    v_html +='<a href="javascript:rcv_pagination_sugges_news('+p_current+',1)"> Trang sau &gt;&gt;</a>';
    return v_html;
}

/*
* xóa bài video gợi ý
*/
function rcv_delete_sugges_news(p_forms){
    var v_record_count = p_forms.hdn_record_count.value*1;
    var v_arr_news_delete = new Array();
    var j =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_arr_news_delete[j] = $('#hdn_id_bai_goi_y'+i).val();
            j++;
        }
    }
    // Thông báo
    if(v_arr_news_delete.length <= 0){
        alert('Chưa có đối tượng nào được chọn');
        return;
    }
    // Danh sách ID bài viết sẽ bị xóa khỏi danh sách
    v_news_id_list = v_arr_news_delete.join();
    rcv_delete_sugges_news_detail(v_news_id_list);
}
/*
* xóa bài video gợi ý chi tiết
*/
function rcv_delete_sugges_news_detail(p_news_id_list){
    var hdn_item_id = $('#hdn_item_id').val();
    // Hiển thị tiếp thông báo
    if(!confirm('bạn có muốn xóa đối tượng đã được chọn')){
        return;
    }
    // lấy danh sách ID vừa thêm
    var v_list_id_news_add = $('#hdn_list_id_news_add').val();
    var v_arr_news_delete = p_news_id_list.split(',');
    if(v_list_id_news_add != ''){
        var v_arr_news_add = v_list_id_news_add.split(',');
        v_record_count = v_arr_news_add.length;
        for(i = 0;i<v_record_count;i++){
            v_check = v_arr_news_delete.indexOf(v_arr_news_add[i]);
            // Nếu ID bài đã tồn tại trong mảng dữ liệu xóa. Thì bỏ ra khỏi danh sách thêm
            if(v_check >= 0){
                // xóa bỏ mảng phần tử đã thêm
                v_arr_news_add.splice(i, 1);
                v_record_count = v_arr_news_add.length;
                i = i -1;
                // xóa bỏ mảng phần tử xóa
                v_arr_news_delete.splice(v_check, 1)
            }
        }
        v_list_id_news_add = v_arr_news_add.join();
        p_news_id_list = v_arr_news_delete.join();
    }
    var v_news_id_list = '';
    if($('#hdn_list_news_id_delete').length > 0 && $('#hdn_list_news_id_delete').val() != ''){
        v_id_list_news = $('#hdn_list_news_id_delete').val();
        v_news_id_list = v_id_list_news+','+p_news_id_list;
    }else{
        v_news_id_list = p_news_id_list;
    }
    var hdn_item_type = $('#hdn_item_type').val();
    var sel_category_id = $('#sel_category_id').val();
    var sel_status = $('#sel_status').val();
    var txt_news_id = $('#txt_news_id').val();
    var txt_news_name = $('#txt_news_name').val();
    var txt_tu_ngay = $('#txt_tu_ngay').val();
    var txt_den_ngay = $('#txt_den_ngay').val();
    $.ajax({
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/recommend_video_common/dsp_filter_recommend_video_goi_y/'+v_list_id_news_add,
        data: {
            hdn_item_id:hdn_item_id,
            hdn_item_type:hdn_item_type,
            sel_category_id: sel_category_id,
            sel_status: sel_status,
            txt_news_id: txt_news_id,
            txt_news_name: txt_news_name,
            txt_tu_ngay: txt_tu_ngay,
            txt_den_ngay: txt_den_ngay,
            p_news_id_list_delete: v_news_id_list,
        },
        async: false,
        success: function(result){
            if(result){
                var no_data = result.indexOf("<!--no_data-->");
                if(no_data > 0){
                    $('#tbl_selected_news_list').html($('#tr_lbale_selected_news_list').html());
                    $('#hdn_record_count').val(0);
                    $('#hdn_list_id_news_add').val('');
                }else{
                    $('#wap_list_suggestion').html(result);
                    if($('#hdn_list_news_id_delete').length > 0){
                        $('#hdn_list_news_id_delete').val(v_news_id_list);
                    }
                    $('#hdn_list_id_news_add').val(v_list_id_news_add);
                }
            }
        }
    });
}
/*
* Xóa bài video gốc
*/
function rcv_xoa_1_bai_video_goc(p_forms){
    var v_record_count = p_forms.hdn_record_count_main.value*1;
    var v_arr_news_delete = new Array();
    var j =0;
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id_main"+i);
        if(p_check_obj && p_check_obj.checked == true){
            v_arr_news_delete[j] = $('#hdn_id_bai_goi_y_main'+i).val();
            j++;
        }
    }
    // Thông báo
    if(v_arr_news_delete.length <= 0){
        alert('Chưa có đối tượng nào được chọn');
        return;
    }
    // Danh sách ID bài viết sẽ bị xóa khỏi danh sách
    v_news_id_list = v_arr_news_delete.join();
    rcv_xoa_1_bai_video_goc_detail(v_news_id_list);
}
/*
* Xóa bài video gốc chi tiêt
*/
function rcv_xoa_1_bai_video_goc_detail(p_news_id_list){
    // Hiển thị tiếp thông báo
    if(!confirm('bạn có muốn xóa đối tượng đã được chọn')){
        return;
    }
    // lấy danh sách ID vừa thêm
    var v_list_id_news_add = $('#hdn_id_origin').val();
    var v_arr_news_delete = p_news_id_list.split(',');
    if(v_list_id_news_add != ''){
        var v_arr_news_add = v_list_id_news_add.split(',');
        v_record_count = v_arr_news_add.length;
        //console.log(v_arr_news_add[1]);
        for(i = 0;i<v_record_count;i++){
            v_check = v_arr_news_delete.indexOf(v_arr_news_add[i]);
            // Nếu ID bài đã tồn tại trong mảng dữ liệu xóa. Thì bỏ ra khỏi danh sách thêm
            if(v_check >= 0){
                // xóa bỏ mảng phần tử đã thêm
                v_arr_news_add.splice(i, 1);
                v_record_count = v_arr_news_add.length;
                i = i -1;
            }
        }
        v_list_id_news_add = v_arr_news_add.join();
        p_news_id_list = v_arr_news_delete.join();
    }
    if(v_list_id_news_add == ''){
        $('#tbl_selected_news_main_list').html($('#tr_lbale_selected_news_main_list').html());
        $('#hdn_id_origin').val('');
        return;
    }
    
    // Load dữ liệu từ url
    $.ajax({
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/recommend_video_common/dsp_html_main_list_news_recommend_video_by_news_id/'+v_list_id_news_add,
        async: false,
        success: function(result){
            if(result){
                if(result.indexOf("<!--no_data-->") <= 0){
                   $('#zone_selected_news_list_origin').html(result);
                   $('#hdn_id_origin').val(v_list_id_news_add);
                }
            }
        }
    });
}
/*Begin BangND 25-05-2018 XLCYCMHENG_31392_bo_sung_chuc_nang_thong_ke_tong_so_luong_bai_viet_theo_đieu_kien_loc */
function ajaxDspThongKe(where, url, param) {
    AjaxAction(where, url + param + (param ? '&' : '?') + 'thong_ke=1');
}
/*End BangND 25-05-2018 XLCYCMHENG_31392_bo_sung_chuc_nang_thong_ke_tong_so_luong_bai_viet_theo_đieu_kien_loc */

/*Begin 02-07-2018 trungcq XLCYCMHENG_31872_toi_uu_chuc_nang_nhap_nguon_thong_tin*/
/*
 *@desc: Hàm chọn nguồn thông tin
 *@param: p_form_obj string Form submit
 *@return:  
 */
function frm_submit_select_source(p_form_obj)
{
    v_source_id = getRadioButtonValue(p_form_obj.rad_source_id);
    if (!v_source_id) {
        alert('Chưa có nguồn nào được chọn!');
        return false;
    }
    window.opener.document.getElementById('hdn_source_id').value = v_source_id;
    openersourceSearch = window.opener.document.getElementById('txt_source');
    v_obj_source = p_form_obj['hdn_source_'+v_source_id];
    openersourceSearch.value = (v_obj_source.length > 0) ? p_form_obj['hdn_source_'+v_source_id][0].value : p_form_obj['hdn_source_'+v_source_id].value;
    openersourceSearch.readOnly = true;
    window.close();
    return false;
}

/*
 *@desc: Hàm check radio
 *@param: p_rad_id string ID radio
 *@param: p_input_id string ID input text
 *@return:  
 */
function check_radio_by_input(p_rad_id, p_input_id)
{
    if(document.getElementById(p_input_id)){
        v_input_value = document.getElementById(p_input_id).value;
        if(document.getElementById(p_rad_id+v_input_value)){
            v_rad_value = document.getElementById(p_rad_id+v_input_value).value;
            console.log('v_input_value='+v_input_value);
            console.log('v_rad_value='+v_rad_value);
            if(v_input_value==v_rad_value){
                document.getElementById(p_rad_id+v_input_value).checked = true;
                window.location.hash = p_rad_id+v_input_value;
            }
        }
    }
}
/*End 02-07-2018 trungcq XLCYCMHENG_31872_toi_uu_chuc_nang_nhap_nguon_thong_tin*/
/*
 * @desc: Hàm kiểm tra đã chọn giải đấu theo chuyên mục hay chưa
 * @param string p_str_chuyen_muc_id
 * @return {Boolean}
 */
function kiem_tra_tich_chon_giai_dau_theo_chuyen_muc(p_str_chuyen_muc_id){
    if (typeof p_str_chuyen_muc_id == 'undefined') {
        return false;
    }
    // Nếu tồn tại
    if(window.document.getElementById('sel_category_list') && window.document.getElementsByTagName('chk_giai_dau_content')){
        var v_check_category = false;
        var arr_str_chuyen_muc_id =  p_str_chuyen_muc_id.split(',');
        // Lấy danh sách chuyên mục được tích chọn
        objSelectList = window.document.getElementById('sel_category_list');
        for (var i=0; i<objSelectList.options.length; i++) {
            if(arr_str_chuyen_muc_id.indexOf(objSelectList.options[i].value) != -1){
               v_check_category = true;
               continue;
            }
        }
        // Kiểm tra loại giải đấu được tích chọn
        if(v_check_category==true){
            var check_content = 0;
            $('input:checkbox[name="chk_giai_dau_content[]"]').each(function() {
               if ($(this).is(":checked")) {
                   check_content = $(this).attr('value');
               }
            });
            if(check_content>0){
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
    return false;
}
// begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
function on_change_dang_hien_thi_magazine(that) {
    var newVal = $(that).val();
    var oldVal = $('#hdn_dang_hien_thi').val();
    $('#txt_dang_hien_thi').val('');
    $('#txt_dang_hien_thi').blur();
    var href = $('#preview_dang_hien_thi').attr('data-base-href');

      if (oldVal != newVal) {
        if (!confirm("Bài viết sẽ đổi dạng hiển thị trên trang, bạn có chắc muốn thực hiện thao tác?")) {
            $(that).val(oldVal); //set back
            return;
        }
        $('#hdn_dang_hien_thi').val(newVal);
      }
      if ($('#hdn_dang_hien_thi').val()) {
        href = href.replace('[[placeholder]]', $('#hdn_dang_hien_thi').val());
    } else {
        href = '';
    }
    
    $('#preview_dang_hien_thi').attr('data-href', href);
}
// end 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
/*
* anhpt1 add 08/09/2016
* Chon bai viet gan cau hinh bài pr liên quan
*/
function chon_bai_viet_gan_bai_pr_special(p_forms){  
	var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    var v_danh_sach_id_bai_viet_da_chon ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
            if(window.opener.document.getElementById('hdn_news_id'+p_check_obj.value)){
                v_danh_sach_id_bai_viet_da_chon+=(v_danh_sach_id_bai_viet_da_chon=='')? p_check_obj.value:','+p_check_obj.value;
            }else{
                v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
            }
        }
    }
    if(v_danh_sach_id_bai_viet_da_chon != ''){
        alert('Danh sách bài viết đã được chọn ID:'+v_danh_sach_id_bai_viet_da_chon);
        return;
    }
	if(v_danh_sach_id_bai_viet=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    
    obj_stt_row = window.opener.document.getElementById("hdn_stt_row");
    var v_old_id_list = window.opener.frm_dsp_single_item.hdn_danh_sach_id.value;
    var v_type_pr_special= document.getElementById("hdn_type_pr_special").value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/xuat_ban_bai_pr_dac_biet/dsp_bai_pr_gan_vao_nhan/'+v_danh_sach_id_bai_viet+'/'+parseInt(obj_stt_row.value)+'?v_type_pr_special='+v_type_pr_special;
    // Load dữ liệu từ url
    $.ajax({
        type: "POST",
        url: v_url,
        async: false,
        success: function(result){
            if(result){
                var myregexp = /<!--count_record(\d+)-->/;
                var match = myregexp.exec(result);
                v_so_luong_them = parseInt(match[1]);
                obj_stt_row.value = parseInt(obj_stt_row.value) + v_so_luong_them;
                window.opener.$('#tr_insert_row').before(result);
            }
        }
    });
    window.close();
}
 /*
* @desc: hàm thêm khoảng thời gian
* @author: anhpt1 
* @returns 
*/
function add_item_bai_pr_dac_biet_link_khac_hang(){
	var obj = document.getElementsByClassName("bai_pr_link_khac_hang");
        var hdn_id_pr_special = document.getElementById("hdn_id_pr_special").value;
    // Chỉ được phép thêm khoảng thời gian được cấu hình
    if(parseInt(obj.length) >= v_sl_bai_hien_thi ){ alert('Bạn chỉ được phép thêm '+v_sl_bai_hien_thi+' bài pr link khách hàng !'); return;}
	v_stt_pr_gian_tiep = 0;
    if(obj.length > 0){
        v_tong_pr_link_khach_hang =  parseInt(obj.length - 1);
        // Lấy tên cuối cùng của link khách hàng
        v_name_khoang_thoi_gian =  obj[v_tong_pr_link_khach_hang].id;
        // lấy số thứ tự cuối cùng khoảng thời gian
        v_stt_pr_cuoi = v_name_khoang_thoi_gian.replace('bai_pr_link_khac_hang','');
        // lấy thứ tự khoảng thoi tiếp theo cần thêm
        v_stt_pr_gian_tiep = parseInt(v_stt_pr_cuoi) + 1;
    }
	$.ajax(
    {
        url: v_url_modul_ajax+'/dsp_chon_bai_viet_gan_link_khach_hang/'+v_stt_pr_gian_tiep+'/'+(v_stt_pr_gian_tiep+1)+'/?p_id_pr_special='+hdn_id_pr_special
        , success: function(result){
            if(result){
                $( "#div_bai_pr_lien_quan_link_kh" ).before(result);
            }
        }
    });
}
 /*
* @desc: hàm thực hiện thêm bài PR link khách hàng đã nhập
* @author: anhpt1 
* @returns 
*/
function add_item_pr_link_khach_hang_da_nhap(p_forms){
    var obj = document.getElementsByClassName("container_bai_pr_link_khac_hang");
	v_tong_pr_link_kh =  parseInt(obj.length);
	// Lấy tên cuối cùng của khoảng thời gian
	v_name_pr_link_kh =  obj[0].id;
	// Lấy thứ tự khoảng thời gian cuối cùng
	v_stt_pr_link_kh = v_name_pr_link_kh.replace('container_bai_pr_link_khac_hang','');
	// lấy thứ tự câu khoảng thời gian tiếp theo
	v_stt_pr_link_kh_first = parseInt(v_stt_pr_link_kh);
    v_count = 0;
    
    var url_validate_img = CONFIG.BASE_URL+'ajax/xuat_ban_bai_pr_dac_biet/dsp_validate_link_khac_hang/';
    var v_record_count = (v_tong_pr_link_kh +v_stt_pr_link_kh_first);
    if(v_stt_pr_link_kh_first > 0){     
        frm_submit (p_forms, url_validate_img+v_stt_pr_link_kh_first+'/'+v_record_count, 'link_khach_hang');
    }else{
        frm_submit (p_forms, url_validate_img+0+'/'+v_record_count, 'link_khach_hang');
	}
}
 /*
* @desc: Thêm html link khách hàng
* @author: anhpt1 
* @returns 
*/
function add_html_pr_link_khach_hang(){
    var obj = document.getElementsByClassName("container_bai_pr_link_khac_hang");
	v_tong_pr_link_kh =  parseInt(obj.length);
	// Lấy tên cuối cùng của khoảng thời gian
	v_name_pr_link_kh =  obj[0].id;
	// Lấy thứ tự khoảng thời gian cuối cùng
	v_stt_pr_link_kh = v_name_pr_link_kh.replace('container_bai_pr_link_khac_hang','');
	// lấy thứ tự câu khoảng thời gian tiếp theo
	v_stt_pr_link_kh_first = parseInt(v_stt_pr_link_kh);
    v_count = 0;
    // Kiểm tra validate bài PR link khách hàng
    obj_stt_row = window.opener.document.getElementById("hdn_stt_row");
    var v_row_add = 0;
    var v_record_count = (v_tong_pr_link_kh +v_stt_pr_link_kh_first);
    if(v_record_count > 0){
        for(i = v_stt_pr_link_kh_first;i<v_record_count;i++){
            v_open_tab = 0;
            if ($('#chk_open_tab'+i).attr('checked')){
                v_open_tab = 1;
            }
            $.ajax({
                type: "POST",
                url: CONFIG.BASE_URL+'ajax/xuat_ban_bai_pr_dac_biet/dsp_html_bai_pr_link_khach_hang/'+(parseInt(obj_stt_row.value) +v_count),
                data: {
                    txt_title: document.getElementById('txt_title'+i).value,
                    txt_summary: document.getElementById('txt_summary'+i).value,
                    txt_link: document.getElementById('txt_link'+i).value,
                    txt_so_hop_dong: document.getElementById('txt_so_hop_dong'+i).value,
                    txt_summary_image_chu_nhat: document.getElementById('txt_summary_image_chu_nhat'+i).value,
                    chk_open_tab: v_open_tab,
                    txt_tu_ngay: document.getElementById('txt_tu_ngay'+i).value,
                    txt_den_ngay: document.getElementById('txt_den_ngay'+i).value,
                    txt_gio_tu: $("select#txt_gio_tu"+i+" option").filter(":selected").val(),
                    txt_phut_tu: $("select#txt_phut_tu"+i+" option").filter(":selected").val(),
                    txt_gio_den: $("select#txt_gio_den"+i+" option").filter(":selected").val(),
                    txt_phut_den: $("select#txt_phut_den"+i+" option").filter(":selected").val(),
                    hdn_type_pr_special: document.getElementById('hdn_type_pr_special').value,
                    hdn_id_brand_produc: document.getElementById('hdn_id_brand_produc'+(i+1)).value,
                    hdn_name_brand_produc: document.getElementById('hdn_name_brand_produc'+(i+1)).value,
                },
                async: false,
                success: function(result){
                    if(result){
                        window.opener.$('#tr_insert_row').before(result);
                        v_row_add = v_row_add +1;
                        v_count = v_count +1;
                    }
                }
            });
        }
    }
    obj_stt_row.value = parseInt(obj_stt_row.value) + v_row_add;
    window.close();
}
 /*
* @desc: Xóa bài PR đặc biệt
* @author: anhpt1 
* @returns 
*/
function delete_pr_news_special(p_stt, p_id_cau_hinh, p_id_cau_hinh_pr_dac_biet){
    // Thực hiện 1 cấu hình pr news
    v_url = CONFIG.BASE_URL +'ajax/xuat_ban_bai_pr_dac_biet/act_delete_cau_hinh_bai_pr_theo_id/'+p_id_cau_hinh +'/'+p_id_cau_hinh_pr_dac_biet;
    frm_submit(frm_dsp_single_item, v_url, 'frm_submit');


    $('#tr_row_news_pr'+p_stt).remove();
}
/*
 * Ham kiem tra tieu de bai viet tai box tim kiem
 * p_form_name : Ten form can kiem tra
 * p_name_input_bai_viet : Ten cua truong input tieu de bai viet
*/
function form_filter_suggestion_pr_special(p_form_name, p_name_input_bai_viet) {
	if ($('form[name="'+p_form_name+'"]')) {
		if ($('input[name="'+p_name_input_bai_viet+'"]')) {
			var val_title = $('input[name="'+p_name_input_bai_viet+'"]').val();
			if (typeof(val_title) != 'undefined' && val_title != '' && val_title != 'Ten bai viet' && val_title != 'Tên bài viết') {
				val_title_array = val_title.split(' ');
				if (val_title_array.length < 2) {
					alert('Tiêu đề tìm kiếm phải nhập ít nhất 2 từ !');
					$('input[name="'+p_name_input_bai_viet+'"]').focus();
					return false;
				}
			}
		}
        var hdn_item_id = $('#hdn_item_id').val();
        var hdn_item_type = $('#hdn_item_type').val();        
        var sel_category_id = $('#sel_category_id').val();
        var sel_status = $('#sel_status').val();
        var txt_news_id = $('#txt_news_id').val();
        var txt_news_name = $('#txt_news_name').val();
        var txt_tu_ngay = $('#txt_tu_ngay').val();
        var txt_den_ngay = $('#txt_den_ngay').val();
        
        // Load dữ liệu từ url
        $.ajax({
            type: "POST",
            url: '/ocm/xuat_ban_bai_pr_dac_biet/dsp_filter_news_pr_special',
            data: {
                hdn_item_id:hdn_item_id,
                hdn_item_type:hdn_item_type,
                sel_category_id: sel_category_id,
                sel_status: sel_status,
                txt_news_id: txt_news_id,
                txt_news_name: txt_news_name,
                txt_tu_ngay: txt_tu_ngay,
                txt_den_ngay: txt_den_ngay,
            },
            async: false,
            success: function(result){
                if(result){                    
                    //$('#wap_list_suggestion').html(result);
                }
            }
        });
	}
}
/*
 * Mở popup chon link khach hang
 * p_form_name : Ten form can kiem tra
 * p_name_input_bai_viet : Ten cua truong input tieu de bai viet
*/
function open_popup_pr_link_khac_hang(){
    var stt = document.getElementById('hdn_stt_row').value;
    var hdn_item_id = document.getElementById('hdn_item_id').value;
    v_url_khach_hang_poup = v_url_khach_hang + parseInt(stt)+'/'+(parseInt(stt)+1)+'?all_html=1&p_id_pr_special='+hdn_item_id;
    openWindow(v_url_khach_hang_poup,850,500,false);
}

/*
 * Mở popup chỉnh pr link khách hàng
 * p_form_name : Ten form can kiem tra
 * p_name_input_bai_viet : Ten cua truong input tieu de bai viet
*/
/* Begin: 5-6-22019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
function mo_popup_chinh_sua_pr_link_khach_hang(p_stt, $p_cau_hinh_id){
    var hdn_item_id = document.getElementById('hdn_item_id').value;
    var hdn_id_brand_produc = document.getElementById('hdn_id_brand_produc'+p_stt).value;
    v_url_khach_hang_poup1 = v_url_khach_hang + parseInt(p_stt)+'/'+(parseInt(p_stt)+1)+'?update_data=1&p_id_pr_special='+hdn_item_id+'&hdn_id_brand_produc='+hdn_id_brand_produc+'&p_cau_hinh_id='+$p_cau_hinh_id;
    v_content_window = window.open(v_url_khach_hang_poup1, 'v_content_window', 'width=850, height=500,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}
/* End: 5-6-22019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
/*
 * update item pr link khách hàng đã nhập
*/
function update_item_pr_link_khach_hang_da_nhap(p_stt, p_cau_hinh_id){
    // lấy trọng số
    v_order = window.opener.document.getElementById('txt_order'+p_stt).value;
    v_status = window.opener.$("select#sel_trang_thai_xuat_ban"+p_stt+" option").filter(":selected").val();
    v_open_tab = 0;
    if ($('#chk_open_tab'+p_stt).attr('checked')){
        v_open_tab = 1;
    }
    $.ajax({
        /* Begin 06-12-2018 Trungcq - XLCYCMHENG_33482_hen_gio_xuat_ban_bai_pr_box_nhan_hang */
        type: "POST",
        url: CONFIG.BASE_URL+'ajax/xuat_ban_bai_pr_dac_biet/dsp_html_bai_pr_link_khach_hang/'+p_stt+'?v_update_item=1&p_order='+v_order+'&p_status='+v_status+'&p_cau_hinh_id='+p_cau_hinh_id,
        data: {
            txt_title: document.getElementById('txt_title'+p_stt).value,
            txt_summary: document.getElementById('txt_summary'+p_stt).value,
            txt_link: document.getElementById('txt_link'+p_stt).value,
            txt_so_hop_dong: document.getElementById('txt_so_hop_dong'+p_stt).value,
            txt_summary_image_chu_nhat: document.getElementById('txt_summary_image_chu_nhat'+p_stt).value,
            chk_open_tab: v_open_tab,
            txt_tu_ngay: document.getElementById('txt_tu_ngay'+p_stt).value,
            txt_den_ngay: document.getElementById('txt_den_ngay'+p_stt).value,
            txt_gio_tu: $("select#txt_gio_tu"+p_stt+" option").filter(":selected").val(),
            txt_phut_tu: $("select#txt_phut_tu"+p_stt+" option").filter(":selected").val(),
            txt_gio_den: $("select#txt_gio_den"+p_stt+" option").filter(":selected").val(),
            txt_phut_den: $("select#txt_phut_den"+p_stt+" option").filter(":selected").val(),
            hdn_type_pr_special: document.getElementById('hdn_type_pr_special').value,
            hdn_id_brand_produc: document.getElementById('hdn_id_brand_produc'+(p_stt+1)).value,
            hdn_name_brand_produc: document.getElementById('hdn_name_brand_produc'+(p_stt+1)).value
        },
        /* End 06-12-2018 Trungcq - XLCYCMHENG_33482_hen_gio_xuat_ban_bai_pr_box_nhan_hang */
        async: false,
        success: function(result){
            if(result){
                window.opener.$('#tr_row_news_pr'+p_stt).html(result);
            }
        }
    });
    window.close();
}
/*
 * validate data link khách hàng đã nhập
*/
function validate_item_pr_link_khach_hang_da_nhap(sttt,p_cau_hinh_id,p_forms){
    var url_validate_img = CONFIG.BASE_URL+'ajax/xuat_ban_bai_pr_dac_biet/dsp_validate_link_khac_hang/'+sttt+'/'+(sttt+1)+'?v_update=1&p_cau_hinh_id='+p_cau_hinh_id;
    frm_submit (p_forms, url_validate_img, 'link_khach_hang');
}


//Begin 19-11-2018 Trungcq - XLCYCMHENG_33288_tools_upload_map_prebid
/*
 * Hàm check các giá trị vùng miền, thiết bị trước khi upload file excel prebid
 */
function check_upload_file_excel_prebid(p_form_filter_name, p_form_upload_name) {
    var vung_mien = $("#sel_region option:selected").text();
    var thiet_bi = $("#sel_thiet_bi option:selected").text();
    if (confirm('Bạn đang Upload file Excel cho thiết bị '+thiet_bi+' - Vùng miền '+vung_mien+'. Bạn cần xác nhận để thực hiện Upload?')) {
        $('#frm_upload_excel_prebid').submit();
    }
}
/*
* Hàm xuất bản code prebid cho 1 dòng
** params:
* p_url_publish : Link xuất bản để gọi ajax
* p_ten_chuyen_muc : tên chuyên mục đang được xuất bản
 */
function btn_publish_prebid_code_one_row(p_url_publish, p_ten_chuyen_muc){
    var vung_mien = $("#sel_region option:selected").text();
    var thiet_bi = $("#sel_thiet_bi option:selected").text();
    if(confirm('Bạn có chắc chắn muốn xuất bản Code Prebid Test lên Production cho Chuyên Mục '+p_ten_chuyen_muc+' - Vùng miền '+vung_mien+' - Thiết bị '+thiet_bi+' không?'))
    {
        if (p_url_publish !='') {
            AjaxAction_and_alert_response(p_url_publish, true);
        }
    }

}
/*
* Hàm xuất bản code prebid cho tất cả các dòng
** params:
* p_url_publish : Link xuất bản để gọi ajax
 */
function btn_publish_prebid_code_all_row(p_url_publish){
    var vung_mien = $("#sel_region option:selected").text();
    var thiet_bi = $("#sel_thiet_bi option:selected").text();
    if(confirm('Bạn có chắc chắn muốn xuất bản TOÀN BỘ Code Prebid TEST lên Production cho Vùng miền '+vung_mien+' - Thiết bị '+thiet_bi+' không?'))
    {
        if (p_url_publish !='') {
            AjaxAction_and_alert_response(p_url_publish, true);
        }
    }
}
/*
* Hàm back code prebid cho 1 dòng
** params:
* p_url_publish : Link back code để gọi ajax
* p_ten_chuyen_muc : tên chuyên mục đang được back code
 */
function btn_back_prebid_code_one_row(p_url_publish, p_ten_chuyen_muc){
    var vung_mien = $("#sel_region option:selected").text();
    var thiet_bi = $("#sel_thiet_bi option:selected").text();
    if(confirm('Bạn có chắc chắn muốn BACK lại code prebid cho Chuyên Mục '+p_ten_chuyen_muc+' - Vùng miền '+vung_mien+' - Thiết bị '+thiet_bi+' không?'))
    {
        if (p_url_publish !='') {
            AjaxAction_and_alert_response(p_url_publish, true);
        }
    }
}
/*
* Hàm back code prebid cho tất cả các dòng
** params:
* p_url_publish : Link back code để gọi ajax
 */
function btn_back_prebid_code_all_row(p_url_publish){
    var vung_mien = $("#sel_region option:selected").text();
    var thiet_bi = $("#sel_thiet_bi option:selected").text();
    if(confirm('Bạn có chắc chắn muốn BACK lại code prebid cho Vùng miền '+vung_mien+' - Thiết bị '+thiet_bi+' không?'))
    {
        if (p_url_publish !='') {
            AjaxAction_and_alert_response(p_url_publish, true);
        }
    }

}
/*
* Hàm thực hiện Ajax 1 link sau đó alert kết quả trả về
** params:
* url : Link gọi Ajax
* p_reload : có load lại trang sau khi hoàn thành ko
 */
function AjaxAction_and_alert_response(url, p_reload)
{
    $.ajax({
        url: url,
        async: true,
        success: function (data) {
            alert(data);
            if (p_reload == true) {
                window.location.href = window.location.href;
            }
        }
    });
}
//End 19-11-2018 Trungcq - XLCYCMHENG_33288_tools_upload_map_prebid
// begin BangND 15-11-2018 XLCYCMHENG_33349_quan_tri_icon_hot_new_menu_ngang_header
function displayCheckIconHotNew() {
    if ($('input[name="chk_vi_tri[]"]').length) {
        $('input[name="chk_vi_tri[]"]').change(function(e) { // Select the radio input group
            // neu vi tri hien thi la menu ngang header
            if ($(this).val() == 1) {
                $('.chk_show_icon').removeClass('hidden');
            } else {
                $('.chk_show_icon').addClass('hidden');
            }
        });
    }
}
function toggleIconHotNewCheck(p_selector) {
    $(p_selector).change(function() {
        var checked = $(this).is(':checked');
        $(p_selector).prop('checked',false);
        if(checked) {
            $(this).prop('checked',true);
        }
    });
}
// end BangND 15-11-2018 XLCYCMHENG_33349_quan_tri_icon_hot_new_menu_ngang_header

/* Begin: 18-12-2018 code_day_bai_viet_sang_cms_baogiaothong */
/*
 * Hàm thực hiện kiểm tra đã nhập chuyên mục xuất bản chính chưa trước khi chọn popup chọn chuyên mục web phụ
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 18-12-2018
 * @param: KHông
 * 
 */
function check_import_main_category_for_news(){
    // kiểm tra input hiden chứa id chuyên mục xuất bản chính
    var v_main_cate_id = document.getElementById('sel_main_category_id').value;
    // Kiểm tra nếu btv chưa chọn chuyên mục xuất bản chính bài viết thì thông báo và dừng màn hình cập nhật
    if(v_main_cate_id == ''){
        // Thông báo chưa nhập chuyên mục xuất bản bài viết
        alert('Bạn chưa chọn chuyên mục xuất bản chính của bài viết');
        return false;
    }
}
/*
 * Hàm thực hiện đong,mở box tìm kiếm nâng cao
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 14-11-2018
 * @param: Không
 */
function dong_mo_box_tim_kiem_nang_cao(){
    // Nếu là mở box tìm kiếm
    if(document.getElementsByClassName('dong_box_tim_kiem').length == 0){
        // Thực hiện add thêm class dong_box_tim_kiem
        $("#dong_box_tim_kiem").addClass("dong_box_tim_kiem");
        // Thực hiện none thẻ mở tìm kiểm
        document.getElementById('mo_box_tim_kiem').style.display = 'none';
        // Thực hiện mở thẻ đóng tìm kiểm
        document.getElementById('dong_box_tim_kiem').style.display = 'block';
        // Thực hiện show box tìm kiếm
        document.getElementById('box_tim_kiem_mo_rong').style.display = 'block';
        // gán giá trị cho input
        document.getElementById('show_box_tim_kiem_nang_cao').value = '1';
    // Trường hợp đóng box tìm kiếm
    }else{
        // Thực hiện add thêm class dong_box_tim_kiem
        $("#dong_box_tim_kiem").removeClass("dong_box_tim_kiem");
        // Thực hiện block thẻ mở tìm kiểm
        document.getElementById('mo_box_tim_kiem').style.display = 'block';
        // Thực hiện none thẻ đóng tìm kiểm
        document.getElementById('dong_box_tim_kiem').style.display = 'none';
        // Thực hiện none box tìm kiếm
        document.getElementById('box_tim_kiem_mo_rong').style.display = 'none';
        // gán giá trị cho input
        document.getElementById('show_box_tim_kiem_nang_cao').value = '';
    }
    
}
/* End: 18-12-2018 code_day_bai_viet_sang_cms_baogiaothong */
/*
 * Hàm thực hiện thêm đội hình đánh đôi
 * @date: 25-02-2019
 * @param: Không
 */
function add_doi_hinh_danh_doi(){
    // Hiển thị div đội hình 2 đánh đôi
    if(document.getElementById('txt_doi_2_danh_doi').style.display == 'none'){
        document.getElementById('txt_doi_2_danh_doi').style.display = 'block';
    }
    // Hiển thị div đội hình 1 đánh đôi
    if(document.getElementById('txt_doi_1_danh_doi').style.display == 'none'){
        document.getElementById('txt_doi_1_danh_doi').style.display = 'block';
    }
}
/*
 * Hàm thực hiện
 * @date: 25-02-2019
 * @param: Không
 */
function delete_doi_hinh_danh_doi(){
    // Hiển thị div đội hình 2 đánh đôi
    if(document.getElementById('txt_doi_2_danh_doi').style.display == 'block' || document.getElementById('txt_doi_2_danh_doi').style.display == ''){
        document.getElementById('txt_doi_2_danh_doi').style.display = 'none';
    }
    // Hiển thị div đội hình 1 đánh đôi
    if(document.getElementById('txt_doi_1_danh_doi').style.display == 'block' || document.getElementById('txt_doi_1_danh_doi').style.display == ''){
        document.getElementById('txt_doi_1_danh_doi').style.display = 'none';
    }
}/*
* Hàm hiển thị box phân loại nội dung event vào 1 div
* p_target_id : Div đích
* p_url : url lấy dữ liệu
* p_obj : trường thông tin để lấy id
 */
function show_phan_loai_noi_dung_event(p_target_id, p_url, p_obj) {
    var v_id = p_obj.value;
    var v_url = p_url+v_id+'?'+Math.random().toString(36).substring(7);
    AjaxAction(p_target_id, v_url);
}

/*
* Hàm kiểm tra dữ liệu đã được chọn hay chưa sau đó mở cửa sổ chọn phân loại nội dung cho bài viết
* p_form_obj : form danh sách để kiểm tra tích chọn
* p_url: link để mở cửa số lựa chọn tiếp theo
 */
function check_add_news_to_content_event(p_form_obj, p_url) {
    v_rows = p_form_obj.hdn_record_count.value;
    var v_arr_news_id = new Array();
    var v_count = 0;
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['chk_item_id'+i];
        if (v_id.checked) {
            v_count++;
            v_arr_news_id[v_arr_news_id.length] = v_id.value;
        }
    }
    if(v_count == 0) {
        alert('Chưa có bài viết nào được chọn.');
        return;
    }
    p_url = p_url+'?list_news='+v_arr_news_id+'&url_back='+window.location.href;
    openWindow(p_url, 950,600);
}

function add_more_row_to_table_content_event(p_table_id,p_class_row,p_url_get_html, p_max_row_add){
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
        var event_id = $('#hdn_event_id').val();
        if (event_id > 0) {
            var v_url_get_html = p_url_get_html + '/'+event_id+'/1/'+ (parseInt(v_max_stt) + 1);
            $.get(v_url_get_html, function (data) {
                $('#' + p_table_id).append(data);
            });
        }
    }
}

function check_delete_content_event(p_form, p_url_delete, p_form_target) {
    if (confirm('Bạn có thực sự xóa các đối tượng đã chọn ?')) {
        var v_count = document.getElementById('hdn_record_count').value;
        v_dem = 0;
        for(i =0;i<v_count;i++){
            var check_item = $('input[name="chk_item_id'+i+'"]:checked');
            var is_delete = true;
            check_item.each(function(){
                var id_content_event = $(this).val();
                var v_url_check = CONFIG.BASE_URL + 'ajax/noi_dung_event/act_check_content_event_has_news/'+id_content_event;
                $.ajax({
                    type: "POST",
                    url: v_url_check,
                    async: false,
                    success: function (data) {
                        if (data != '') {
                            if (data == 1) {
                                if (confirm('Bài viết thuộc phân loại nội dung ID: '+id_content_event+' sẽ bị hủy xác định phân loại nội dung. Bạn có chắc muốn thực hiện thao tác?')) {
                                    is_delete = true;
                                } else {
                                    is_delete = false;
                                }
                            }
                        }
                    }
                });
            });
            if (is_delete == true) {
                v_dem++;
                frm_submit(p_form, CONFIG.BASE_URL +'ajax/noi_dung_event/act_delete_man_hinh_danh_sach/', 'iframe_submit');
            }
        }
        if (v_dem == 0) {
            alert('Chưa có đối tượng nào được chọn!');
            return;
        }
    }
}

function check_delete_content_event_info_row(p_id_row_value, p_id_row_for_delete) {
    if (window.confirm('Bài viết thuộc phân loại nội dung này sẽ bị hủy xác định phân loại nội dung. Bạn có chắc muốn thực hiện thao tác?')) {
        var id_content_event_info = $('#'+p_id_row_value).val();
        if (id_content_event_info > 0) {
            var is_delete = false;
            var v_url_check = CONFIG.BASE_URL + 'ajax/noi_dung_event/act_check_content_event_info_has_listing_template/'+id_content_event_info;
            $.ajax({
                type: "POST",
                url: v_url_check,
                async: false,
                success: function (data) {
                    if (data > 0) {
                        alert('Phân loại nội dung đang được sử dụng trong cấu hình giao diện chuyên mục/event ID: '+data+'. Không thể xóa');
                    } else if (data == 0) {
                        is_delete = true;
                    }
                }
            });
            if (is_delete == true) {
                var v_url_delete = CONFIG.BASE_URL + 'ajax/noi_dung_event/act_delete_content_event_info/'+id_content_event_info;
                $.ajax({
                    type: "POST",
                    url: v_url_delete,
                    async: false,
                    success: function (data) {
                        if (data > 0) {
                            alert('Đã xóa thành công nội dung phân loại.');
                            $('#'+p_id_row_for_delete).remove();
                        }
                    }
                });
            }
        }else{
            alert('Đã xóa thành công nội dung phân loại.');
            $('#'+p_id_row_for_delete).remove();
        }
    }
}

function checking_and_remove_row_from_table_content_event_info(p_row_class_check,p_prefix_id_for_delete, p_message) {
    if (window.confirm(p_message)) {
        if (p_row_class_check != '' && p_prefix_id_for_delete != '') {
            var count = 0;
            $('.'+p_row_class_check+':checked').each(function() {
                var suffix_id_for_delete = $(this).attr('itemid');
                var id_content_event_info = $('#id_'+p_prefix_id_for_delete+suffix_id_for_delete).val();
                var v_ten_noi_dung = $('#c_ten_noi_dung_'+suffix_id_for_delete).val();
                if (id_content_event_info > 0) {
                    var is_delete = false;
                    var v_url_check = CONFIG.BASE_URL + 'ajax/noi_dung_event/act_check_content_event_info_has_listing_template/'+id_content_event_info;
                    $.ajax({
                        type: "POST",
                        url: v_url_check,
                        async: false,
                        success: function (data) {
                            if (data > 0) {
                                alert('Phân loại nội dung '+v_ten_noi_dung+' đang được sử dụng trong cấu hình giao diện chuyên mục/event ID: '+data+'. Không thể xóa');
                            } else if (data == 0) {
                                is_delete = true;
                            }
                        }
                    });
                    if (is_delete == true) {
                        var v_url_delete = CONFIG.BASE_URL + 'ajax/noi_dung_event/act_delete_content_event_info/'+id_content_event_info;
                        $.ajax({
                            type: "POST",
                            url: v_url_delete,
                            async: false,
                            success: function (data) {
                                if (data > 0) {
                                    alert('Đã xóa thành công phân loại nội dung '+v_ten_noi_dung);
                                    $('#'+p_prefix_id_for_delete+suffix_id_for_delete).remove();
                                }
                            }
                        });

                    }
                }else{
                    alert('Đã xóa thành công phân loại nội dung '+v_ten_noi_dung);
                    $('#'+p_prefix_id_for_delete+suffix_id_for_delete).remove();
                }
                count++;
            });
            if (count == 0) {
                alert('Chưa có đối tượng nào được chọn!');
            }
        }
    }
}
function add_more_row_to_table(p_table_id,p_class_row,p_url_get_html, p_max_row_add){
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
            if (v_stt => v_max_stt) {
                v_max_stt = v_stt;
            }
        }
        var v_url_get_html = p_url_get_html+'0/'+(parseInt(v_max_stt)+1);
        $.get(v_url_get_html, function(data) {
            $('#'+p_table_id).append(data);
            chay_javascript_tu_ket_qua_ajax(data);
        });
    }
}

function remove_row_from_table(p_row_class, p_message) {
    if (window.confirm(p_message)) {
        if (p_row_class != '') {
            $('.' + p_row_class).remove();
        }
    }
}

function checking_and_remove_row_from_table(p_row_class_check,p_prefix_id_for_delete, p_message) {
    if (window.confirm(p_message)) {
        if (p_row_class_check != '' && p_prefix_id_for_delete != '') {
            var is_delete = 0;
            $('.'+p_row_class_check+':checked').each(function() {
                var suffix_id_for_delete = $(this).attr('itemid');
                $('#'+p_prefix_id_for_delete+suffix_id_for_delete).remove();
                is_delete++;
            });
            if (is_delete == 0) {
                alert('Chưa có đối tượng nào được chọn!');
            }
        }
    }
}
/*
* Hàm chọn chuyên mục vào select box sau đó đóng cửa sổ chọn chuyên mục.Ko submit form
* p_form_obj : form truyền vào
* p_target_id : ID select box để bắn dữ liệu vào
 */
function radio_select_category_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_category = new Array();
    v_id = p_form_obj['chk_item_id_'+p_target_id].value;
    v_name = p_form_obj['hdn_category_name_'+v_id].value;
    if (p_class_check_da_duoc_chon != '') {
        var all_source_from = window.opener.$("."+p_class_check_da_duoc_chon);
        for (i = 0; i < all_source_from.length; i++) {
            current_id = all_source_from[i].value;
            if (current_id == v_id) {
                var class_name = all_source_from[i].id;
                var arr_class_id = class_name.split("_");
                var class_source_id = 'box_tin_ben_duoi_name_'+arr_class_id[(arr_class_id.length)-2];
                var name_box_erorr = window.opener.document.getElementById(class_source_id).value;
                alert("Chuyên mục " + v_name + " đã được chọn vào box "+name_box_erorr+"!");
                return false;
            }
        }
    }
    v_arr_category[v_arr_category.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có chuyên mục nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_category) {
            var v_ten_chuyen_muc = v_arr_category[i][0];
            v_arr_category[i][0] = 'Chuyên mục: '+v_arr_category[i][0]+' - ID: '+v_arr_category[i][1];
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box)) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_chuyen_muc);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            window.opener.$("#"+p_div_to_put_source_from).val('category');
            window.opener.$("#"+p_div_to_put_source_from+'_id').val(v_id);
        }
        window.close();
    }
    return false;
}
function select_one_event_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_event = new Array();
    v_id = p_form_obj['rad_event_id'].value;
    v_name = p_form_obj['hdn_event'+v_id].value;
    if (p_class_check_da_duoc_chon != '') {
        var all_source_from = window.opener.$("."+p_class_check_da_duoc_chon);
        for (i = 0; i < all_source_from.length; i++) {
            current_id = all_source_from[i].value;
            if (current_id == v_id) {
                alert("Sự kiện " + v_name + " đã được chọn!");
                return false;
            }
        }
    }
    v_arr_event[v_arr_event.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có profile nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_event) {
            var v_ten_profile = v_arr_event[i][0];
            v_arr_event[i][0] = 'Sự kiện: '+v_arr_profile[i][0]+' - ID: '+v_arr_event[i][1];
            openerPutElementToSelectList(v_arr_event[i][0], v_arr_event[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box).length > 0) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_event);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            if (window.opener.$("#"+p_div_to_put_source_from).length > 0) {
                window.opener.$("#" + p_div_to_put_source_from).val('event');
            }
            if (window.opener.$("#"+p_div_to_put_source_from+'_id').length > 0) {
                window.opener.$("#" + p_div_to_put_source_from + '_id').val(v_id).trigger('change');
            }
        }
        window.close();
    }
    return false;
}
function radio_select_event_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_event = new Array();
    v_id = p_form_obj['rad_event_id'].value;
    v_name = p_form_obj['hdn_event'+v_id].value;
    if (p_class_check_da_duoc_chon != '') {
        var all_source_from = window.opener.$("."+p_class_check_da_duoc_chon);
        for (i = 0; i < all_source_from.length; i++) {
            current_id = all_source_from[i].value;
            if (current_id == v_id) {
                var class_name = all_source_from[i].id;
                var arr_class_id = class_name.split("_");
                var class_source_id = 'box_tin_ben_duoi_name_'+arr_class_id[(arr_class_id.length)-2];
                var name_box_erorr = window.opener.document.getElementById(class_source_id).value;
                alert("Sự kiện " + v_name + " đã được chọn vào box "+name_box_erorr+"!");
                return false;
            }
        }
    }
    v_arr_event[v_arr_event.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có Sự kiện nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_event) {
            var v_ten_event = v_arr_event[i][0];
            v_arr_event[i][0] = 'Sự kiện: '+v_arr_event[i][0]+' - ID: '+v_arr_event[i][1];
            openerPutElementToSelectList(v_arr_event[i][0], v_arr_event[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box).length > 0) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_event);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            if (window.opener.$("#"+p_div_to_put_source_from).length > 0) {
                window.opener.$("#" + p_div_to_put_source_from).val('event');
            }
            if (window.opener.$("#"+p_div_to_put_source_from+'_id').length > 0) {
                window.opener.$("#" + p_div_to_put_source_from + '_id').val(v_id).trigger('change');
            }
        }
        window.close();
    }
    return false;
}
/*
* hàm thực hiện load template mẫu bài PR đặc biệt
* value : gia trị
 */
function load_template_mau_bai_pr_dac_viet(value,p_div){
    obj = document.getElementsByClassName(p_div);
    if(obj.length <= 0){
        return;
    }
    // lặp dữ liệu
    for(var i=1;i<=obj.length;i++){
        if(i==value){
            document.getElementById(p_div+i).style.display='';
        }else{
            document.getElementById(p_div+i).style.display='none';
        }
    }
}


/*
* hàm thực hiện xóa 1 row theo ID table 
* @param : p_table_id ID table cần thao tác
* @param : p_tr_id ID row cần thao tác
* @param : hdn_count_id ID hidden đếm
 */
function tttt_delete_row_by_id(p_table_id, p_tr_id, hdn_count_id){
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
* @param : p_table_id ID table cần thao tác
* @param : p_class_row class row cần thao tác
* @param : p_url_get_html URL thực chứa HTML 1 rows
* @param : p_max_row_add Tổng số rows được thêm
* @param : hdn_count_id ID hidden đếm
 */
function tttt_add_more_row_to_table(p_table_id,p_class_row,p_url_get_html, p_max_row_add, hdn_count_id){
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
        var v_url_get_html = p_url_get_html+'/'+(parseInt(v_max_stt)+1);
        $.get(v_url_get_html, function(data) {
            $('#'+p_table_id).append(data);
        });
		obj_count = $('#'+hdn_count_id);
		var tmp_count = parseInt(obj_count.val()) +1;
		obj_count.val(parseInt(tmp_count));
    }
}

/*
* hàm thực hiện xóa video highlight
 */
function tttt_remove_video_highligt(p_hidden_id, p_file_id){
	if(document.getElementById(p_hidden_id)) document.getElementById(p_hidden_id).value = '';
    if(document.getElementById(p_file_id)) document.getElementById(p_file_id).innerHTML = '';
}

/* Begin 7-6-2019 TuyenNT bo_sung_tinh_nang_chen_tin_lien_quan_tinh_huong_bai_tuong_thuat */
/*
 * Hàm thực hiện chèn tin liên quan vào tình huống tường thuật
 * @author: TuyenNT<tuyennt@24h.com.vn>
 * @date: 7-6-2019
 * @param
 *  p_form_obj      form submit
 *  p_target_id     Vị trí muốn chèn html
 */
function frm_submit_select_bai_lien_quan_trong_noi_dung_bai_tuong_thuat(p_form_obj, p_target_id)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_content = '';
    v_arr_selected = new Array();
	// Kiểm tra đã chọn bài viết
	v_id_checked  = p_form_obj['chk_item_id'].value;
	if (v_id_checked == '' || typeof(v_id_checked) == 'undefined') {
            alert("Chưa có bài nào được chọn!");
	} else {
            v_id = v_id_checked;
            v_stt = $('input[name=chk_item_id]:checked').attr('id').replace('chk_item_id','');
            v_title = p_form_obj['hdn_title_'+v_stt].value;
            if(v_title != ''){
                // Loại bỏ ký tự đặc biệt
                v_title = v_title.replace(/\"/g,  '”').replace(/&#34;/g, '”');
            }
            v_url = p_form_obj['hdn_url_'+v_stt].value;
            // Sapo
            v_sapo = p_form_obj['hdn_sapo_'+v_stt].value;
            // ảnh đại diện
            v_anh_dai_dien = p_form_obj['hdn_anh_dai_dien_'+v_stt].value;

            v_str_before = '<div class="bv-lq bv_lq_tt">';
            v_str_after = '</div>';
            v_content = '<div class="bv-lq-inner-hide"><div class="img-bv-lq imgFloat"><a href="'+v_url+'" title="'+v_title+'"><img class="img_tin_lien_quan_trong_bai" src="'+v_anh_dai_dien+'" alt="'+v_title+'" /></a></div><div class="content-bv-lq"><div class="title-bv"><a class="url_tin_lien_quan_trong_bai" href="'+v_url+'">'+v_title+'</a></div><div class="ct-bv"><p>'+v_sapo+'</p></div><div class="see-now"><a href="'+v_url+'" title="">Bấm xem >></a></div></div></div><p class="title_for_show">'+v_title+'</p>';
            v_content = v_content.replace('bv-lq-inner-hide','bv-lq-inner');
            v_content = v_content.replace(/<p class="title_for_show"[^>]*>.*<\/p>/g, "");

            //xoa thẻ p thừa không có số liệu               
            v_content = v_str_before + v_content.replace(/<p>\s*<\/p>/gi, "") + v_str_after;    
            // inserthtml vào vị trí muốn trèn
            window.opener.CKEDITOR.instances[p_target_id].insertHtml(v_content+'<br />');
            window.close(); 
	}
    return false;
}
/* End 7-6-2019 TuyenNT bo_sung_tinh_nang_chen_tin_lien_quan_tinh_huong_bai_tuong_thuat */
/*
* hàm thực hiện mở popup danh sách câu trả lời khác
 */
function popup_other_answer_list(p_poll_id){
    var v_url = CONFIG.BASE_URL+'poll/dsp_popup_other_answer_list/'+p_poll_id;
    window.open(v_url, "", 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}
/*
* Hàm thực hiện xuất file excel câu trả lời khác poll
 */
function export_data_poll_answer_list(p_form_obj,p_action_url){
    p_form_obj.action = p_action_url;
    p_form_obj.submit();
}


























































































































































































// Hàm thực hiện load câu trả lời khác
function load_other_answer_poll(obj){
    // lặp dữ liệu
    var stt_them_y_kien = 0;
    for(var i=0;i<10;i++){
        obj_tra_loi = document.getElementById('txt_tra_loi'+i);
        v_value_answer = obj_tra_loi.value;
        if(v_value_answer.toLowerCase()=='ý kiến khác' && !obj.checked){
            obj_tra_loi.value ='';
        }
        if(v_value_answer == '' && v_value_answer.toLowerCase() != 'ý kiến khác'){
            stt_them_y_kien = i;
            break;
        }
    }
    if(obj.checked && stt_them_y_kien > 0){
        document.getElementById('txt_tra_loi'+stt_them_y_kien).value = 'Ý kiến khác';
    }












































































}
/*
































* hàm thực hiện ẩn hiển html theo class
*/
function _show_hide_html_by_class(obj,p_classname){
    // Lấy danh sách đối tượng theo classname
    objclass = document.getElementsByClassName(p_classname);
    for(i=0;i<objclass.length;i++){
        if(obj.checked){
            objclass[i].style.display='block';
        }else{
            objclass[i].style.display='none';
        }
    }
}
/**
 * Hàm thực hiện thêm câu trả lời theo loại
 * @date    18-10-2016
 * @returns Null
 */
function game_add_single_trung_thuong() { 
    // Load dữ liệu từ url
    var p_tong_luot = parseInt($('#tong_danh_sach_trung_thuong').val());
    var v_vi_tri = p_tong_luot+1;
    $.ajax({url: v_url_modul_ajax+'/dsp_add_single_nguoi_trung_thuong/'+v_vi_tri+'/1', 
        success: function(result){
            if(result){
                $('#after_tr_win').before(result);
                $('#tong_danh_sach_trung_thuong').val(v_vi_tri);
            }
        }
    });
}
function game_landing_page_initUploader(object) {
    files = object.files
    var nFiles = files.length;
    if(nFiles > 0){
        $('#listUploadFiles').empty();
    }
    for ( var i=0; i<nFiles; i++) {
        game_landing_page_add_file(files[i].name);
    }
}
function game_landing_page_add_file(file_name) {
	var html = '';
	html += '<li class="item" >';
	html +=		'<div class="file-item">';
	html +=			'<a href="javascript:void(0)">'+ file_name +'</a>';
  html +=         '&nbsp;&nbsp;<span class="progress">';
  html +=         '</span>';
	html +=		'</div>';
	html +=	'</li>';
	$('#listUploadFiles').prepend(html);
}
function gameLandingPageRemoveAllFileUpload() {
    var btn = $('#btnRemoveAllFileUpload');
    if(btn.length) {
      if(confirm("Bạn có chắc muốn xóa tất cả các file đã tải lên?") == true) {
        $('#listUploadFiles').empty();
      }
    }
}
// Hàm xử lý khi chọn mini game 
function chooseminigame() {
        // Lấy id mini game
        var v_minigame_id = document.frm_dsp_all_item.rad_minigame_id.value;
        if (!v_minigame_id) {
            // nếu id trống thì trả thông báo cho người dùng
            alert('Chưa có đối tượng nào được chọn.'); return false;
        }
        // gán giá trị v_content
        v_content = '<div class="data-embed-code-minigame"><!--minigame_' + v_minigame_id + '--></div>';
        // gui data sang parent window
        oEditor = window.opener.CKEDITOR.instances['txt_body'];
        oEditor.insertHtml(v_content);
        window.close();
        return false;
    }
/**
 * Hàm thực hiện xóa người trúng thưởng
 * @date    18-10-2016
 * @returns Null
 */
function delete_single_landing_page_nguoi_trung_thuong(p_stt){
    if(confirm('Bạn có chắc muốn xóa kết quả đã chọn không?')){
        $('#row_nguoi_trung_thuong'+p_stt).remove();
        objclass = document.getElementsByClassName('row_nguoi_trung_thuong');
        if(objclass.length > 0){
            for(i = 0;i<objclass.length;i++){
                sttIdOld = objclass[i].id.replace('row_nguoi_trung_thuong','');
                // Lấy các giá trị nhập danh sách người trúng thưởng
                vlueStt = document.getElementById('c_stt_trung_thuong'+sttIdOld).value;
                vlueName = document.getElementById('c_name_trung_thuong'+sttIdOld).value;
                vlueCmt = document.getElementById('c_cmt_trung_thuong'+sttIdOld).value;
                vlueGiaiThuong = document.getElementById('c_giai_thuong_trung_thuong'+sttIdOld).value;
                
                objclass[i].id='row_nguoi_trung_thuong'+(i+1);
                v_html = objclass[i].innerHTML;
                v_html = v_html.replace(/c_stt_trung_thuong(\d+)/g, 'c_stt_trung_thuong'+(i+1));
                v_html = v_html.replace(/c_name_trung_thuong(\d+)/g, 'c_name_trung_thuong'+(i+1));
                v_html = v_html.replace(/c_cmt_trung_thuong(\d+)/g, 'c_cmt_trung_thuong'+(i+1));
                v_html = v_html.replace(/c_giai_thuong_trung_thuong(\d+)/g, 'c_giai_thuong_trung_thuong'+(i+1));
                objclass[i].innerHTML = v_html;
                
                // set lại các giá trị nhập danh sách người trúng thưởng
                document.getElementById('c_stt_trung_thuong'+(i+1)).value=vlueStt;
                document.getElementById('c_name_trung_thuong'+(i+1)).value = vlueName;
                document.getElementById('c_cmt_trung_thuong'+(i+1)).value =vlueCmt;
                document.getElementById('c_giai_thuong_trung_thuong'+(i+1)).value = vlueGiaiThuong;
            }
        }
        objtrungthuong = document.getElementById('tong_danh_sach_trung_thuong');
        objtrungthuong.value = objtrungthuong.value -1;
    }
}
/**
 * Hàm thực hiện load html landing page theo chiến dịch ID
 */
function load_landing_page_by_chien_dich_id(p_chien_dich_id){
    v_url = v_url_modul_ajax+'/dsp_html_landing_page_by_chien_dich_id/'+p_chien_dich_id;
    AjaxAction('landing_page_by_chien_dich_id',v_url);
}

/* BEgin: 05-09-2019 TuyenNT toi_uu_chuc_nang_quan_tri_landing_page_09_19 */
// hàm mở popup upload ảnh cho template landing page
function openWindowUploadImagegame(){
    window.open(CONFIG['BASE_URL']+'upload_image/?news_title=', '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}

// hàm mở popup upload video cho template landing page
function openWindowUploadvideogame(){
    window.open(CONFIG['BASE_URL']+'upload_video/', '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}
/* End: 05-09-2019 TuyenNT toi_uu_chuc_nang_quan_tri_landing_page_09_19 */


function setAutoComplete_tt(p_input_id, p_json_data, p_target_id, p_min_char, p_callback, p_logo, p_link, p_stt) {
    $(document).ready(function () {
        //attach autocomplete
        v_split_char = '#';
        $("#"+p_input_id).autocomplete({
            source: $.map($.makeArray(p_json_data), function(val) {
                return {
                    value: val.name + v_split_char + val.ascii_name + v_split_char + val.c_logo + v_split_char + val.c_link,
                    id: val.id,
                    name: val.name,
                    c_logo: val.c_logo,
                    c_link: val.c_link
                };
            }),
            minLength: (p_min_char>0) ? p_min_char : 1,
            delay: 20,

            focus: function(event, ui) {
                return false;
            },
            //define select handler
            select: function(e, ui) {
                v_name = ui.item.name.split(v_split_char);
                v_name = v_name[0];
                $("#"+p_input_id).val(v_name);
                if (p_target_id != '') {
                    $("#"+p_target_id).val(v_name);
                    $("#"+p_target_id).change();
                }	
                // add logo
                v_logo = ui.item.c_logo.split(v_split_char);
                v_logo = v_logo[0];
                if (p_logo != '') {
                    $("#"+p_logo).val(v_logo);
                    if(v_logo != ''){
                        $("#file_"+p_target_id).css("display","none");
                    }
                }	
                // add link
                v_link = ui.item.c_link.split(v_split_char);
                v_link = v_link[0];
                if (p_logo != '') {
                    $("#"+p_link).val(v_link);
                    $("#"+p_link+p_stt).css("display","block");
                }	
                if (p_callback!=null && p_callback!=''){
                    eval(p_callback);
                }
                return false;
            },
            //define select handler
            change: function() {
                // do nothing
            }
        })
        .data( "autocomplete" )._renderItem = function( ul, item ) {
            re = new RegExp(this.term, "i");
            t = item.name.replace(re, "<span class='ui-autocomplete-match'>$&</span>");
            return $( "<li></li>" )
                .data( "item.autocomplete", item )
                .append( "<a>" + t + "</a>" )
                .appendTo( ul );
        };
    });
};

function disableInputAutoComplete_tt(p_input_id, p_target_id, p_status, p_link)
{
    v_obj = document.getElementById(p_input_id);
    v_obj.readOnly = p_status;
    // Nhap lai input
    if (!p_status) {
        v_obj.value = '';
        document.getElementById(p_target_id).value = '';
        $("#file_"+p_target_id).css("display","block");
    }
    if(p_link != ''){
        $("#"+p_link).css("display","none");
    }
}
/*
 * Hàm thực hiện hiện thị template theo loại bài PR 
 */
function hien_thi_template_theo_loai_bai_pr(value){
    if(value == 7){ // Là loại pr box infeed
        document.getElementById('template_pr_trong_box').style.display = '';
    }else{
        document.getElementById('template_pr_trong_box').style.display = 'none';
    }
    if(value == 2 || value == 3 || value ==7){
        document.getElementById('tr_xuat_ban_bai_pr_24hmoney').style.display = '';
    }else{
        document.getElementById('tr_xuat_ban_bai_pr_24hmoney').style.display = 'none';
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_24hmoney').style.display = 'none';
        if(document.getElementById('chk_day_bai_24hmoney')){
            document.getElementById('chk_day_bai_24hmoney').checked = false;
        }
    }
    if(value == 2 || value == 3 || value ==7 || value ==8){
        document.getElementById('tr_xuat_ban_bai_pr_tinmoi').style.display = '';
    }else{
        document.getElementById('tr_xuat_ban_bai_pr_tinmoi').style.display = 'none';
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_tinmoi').style.display = 'none';
        if(document.getElementById('chk_day_bai_tinmoi')){
            document.getElementById('chk_day_bai_tinmoi').checked = false;
        }
    }
}
/*
 * Hàm thực hiện ẩn hiện thời gian xuất bản bài PR 24hmoney
 */
function an_hien_thoi_gian_xuat_ban_pr_24hmoney(obj){
    if(obj.checked){
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_24hmoney').style.display = '';
    }else{
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_24hmoney').style.display = 'none';
    }
}
/*
 * hàm chọn ảnh nền quiz
 */
function chon_anh_nen_quiz (p_value, p_id){
    // set giá trị href cho preview
    if(document.getElementById('a_url_anh_cau_hoi_'+p_id)){
        document.getElementById('a_url_anh_cau_hoi_'+p_id).href = p_value;
    }
    // set giá trị input
    if(document.getElementById('txt_c_url_anh_cau_hoi_'+p_id)){
        // gán giá trị value cho input ẩn
        document.getElementById('txt_c_url_anh_cau_hoi_'+p_id).value = p_value;
    }
}


function btn_show_frm_article_error_onclick(p_forms, p_action_url, p_id, p_target) {
    frm_submit(p_forms, p_action_url, p_target);
}
//Begin 13/1/2020 AnhTT bo_sung_chuyen_muc_su_kien
function toggle_chuyen_muc_su_kien_dac_biet(p_vi_tri){
    if(p_vi_tri == 'MENU_NGANG_CHUYEN_MUC_MOBILE'){
        document.getElementById("chuyen_muc_su_kien_dac_biet").style.display = "contents";
        document.getElementById("chuyen_muc_su_kien_dac_biet_button").style.display = "table-row";
    }else{
        document.getElementById("chuyen_muc_su_kien_dac_biet").style.display = "none";
        document.getElementById("chuyen_muc_su_kien_dac_biet_button").style.display = "none";
    }
}
//End 13/1/2020 AnhTT bo_sung_chuyen_muc_su_kien
/*
 * Hàm thực hiện xóa 1 hàng import bài viết chứa layout đặc biệt
 */
function btn_xoa_hang_import_bai_viet_layout_dac_biet(p_id_row){
    if(document.getElementById(p_id_row)){
        document.getElementById(p_id_row).style.display = 'none';
        document.getElementById(p_id_row+'_value').value = 0;
    }
}
function show_thong_ke_bai_viet_layout_dac_biet(){
    document.getElementById('show_thong_ke_tich_layout').innerHTML = '';
    var v_url = document.URL;
    v_url = v_url+'?&p_is_tk=1';
    $.ajax({url: v_url, 
        success: function(result){
            if(result){
                $('#show_thong_ke_tich_layout').append(result);	
            }
        }
    });
}
/*
 * hàm thực hiện xử lý dữ liệu khi thay đổi xuất bản banener chuyên mục layout
 */
function onchang_chuyen_muc_xb_banner_layout(value){
    if(document.getElementById('hien_thi_loai_bai_erp_theo_chuyen_muc') && document.getElementById('ket_hop_layout_hien_thi_loai_bai_erp')){
        v_ket_hop_layout_hien_thi = document.getElementById('ket_hop_layout_hien_thi_loai_bai_erp').value;
        v_hien_thi_loai_bai_erp = document.getElementById('hien_thi_loai_bai_erp_theo_chuyen_muc').value;
        if(v_ket_hop_layout_hien_thi == 1 && v_hien_thi_loai_bai_erp == 1){
            if(value == -100){
                document.getElementById('news_type_push_erp').style.display = 'none';
            }else{
                document.getElementById('news_type_push_erp').style.display = '';
            }
        }
    }
}




/*
 * hàm thực hiện hạ neo bài viết sự kiện
 */
function btn_ha_neo_bai_viet_event(p_forms, p_action_url, p_target) {
	if (confirm('Bạn có chắc chắn muốn hủy neo bài viết không?'))
	{
		frm_submit(p_forms, p_action_url, p_target) ;
	}
}

/*
 * Hàm thực hiện chọn câu hỏi: dạng hỏi đáp
 * author: TuyenNT<tuyennt@24h.com.vn>
 * date: 12-02-2020
 * @param: Không
 * return:String
 */
function choose_hoidap() {
    // Lấy id câu hỏi
    var v_hoidap_id = document.frm_dsp_all_item.rad_hoidap_id.value;
    if (!v_hoidap_id) {
        // nếu id trống thì trả thông báo cho người dùng
        alert('Chưa có đối tượng nào được chọn.'); return false;
    }
    // gán giá trị v_content
    v_content = '<div class="data-embed-code-hoidap"><!--hoidap_' + v_hoidap_id + '--></div>';
    // gui data sang parent window
    oEditor = window.opener.CKEDITOR.instances['txt_body'];
    oEditor.insertHtml(v_content);
    window.close();
    return false;
}
/*
 * Hàm thực hiện chọn câu hỏi: dạng hỏi đáp
 * author: TuyenNT<tuyennt@24h.com.vn>
 * date: 12-02-2020
 * @param: 
 *  p_hoidap_id       ID cau hoi
 * return:String
 */
function choose_hoidap_single(p_hoidap_id){
    // Lấy id câu hỏi
    var v_hoidap_id = p_hoidap_id;
    if (!v_hoidap_id) {
        // nếu id trống thì trả thông báo cho người dùng
        alert('Chưa có đối tượng nào được chọn.'); return false;
    }
    // gán giá trị v_content
    v_content = '<div class="data-embed-code-hoidap"><!--hoidap_' + v_hoidap_id + '--></div>';
    // gui data sang parent window
    oEditor = window.opener.CKEDITOR.instances['txt_body'];
    oEditor.insertHtml(v_content);
    window.close();
    return false;
}

/*
 * Hàm thực hiện xử lý nut check box ngoài màn hình danh sách quản trị câu hỏi
 * @author: TuyenNT
 * @date: 17-02-2020
 * @param
 *  p_name      name của input
 *  p_count     Số lượng câu trả lời
 */
function toggle_checkbox_main_hoidap(p_name, p_count){
    if(p_count > 0){
        // for de loai bo nut check box
        for(i=0; i<p_count; i++){
            var v_name_input = 'chk_tl_chinh_thuc'+i;
            // kiểm tra name để bỏ tích chọn
            if(v_name_input !== p_name){
                document.getElementById(v_name_input).checked = false;
            }
        }
    }
}

 //Begin 17/3/2020 AnhTT bo_sung_tab_su_kien_dac_biet
 function toggle_checkbox_hien_thi_thoi_gian_xb(){
     var checkBox = document.getElementById("chk_hen_gio_xb");
     if (checkBox.checked == true){
        document.getElementById("check_thoi_gian_xb").style.display = "contents";
      }else{
          document.getElementById("check_thoi_gian_xb").style.display = "none";
      }
 }
  //Begin 17/3/2020 AnhTT bo_sung_tab_su_kien_dac_biet
  //Begin AnhTT 17/04/2020 xu_ly_luu_bai_2ID
/*
* AnhTT add 17/04/2020
* Toi uu click xuan ban ra 2 id bai
*/
function update_news_id_sau_khi_xuatban(news_id){
    if (typeof news_id != 'undefined') {
        document.getElementById("news_id_xb").value = news_id;
    }
}
//Begin AnhTT 17/04/2020 xu_ly_luu_bai_2ID

/*
 * hàm xử lý từ điển: replace sửa lỗi vào body và đẩy api sang tool chinhta và tool crawl 
 */
function grammar_xu_ly_tu_dien(){
    var v_count_title = document.getElementById('grammar_count_title').value;
    
    var arr_title = [];
    var arr_title_old = [];
    // lặp v_count_title để lấy các thông tin tiêu đề
    if(v_count_title > 0){
        // lấy tiêu đề
        var txt_title = document.getElementById('txt_title').value;
        for(i=0;i<v_count_title;i++){
            var grammar_error_title = document.getElementById('grammar_error_title_'+i).value;
            var grammar_error_add_title = document.getElementById('grammar_error_add_title_'+i).value;
            // nếu người dùng có nhập thì mới replace
            if(grammar_error_add_title !== ''){
                reg_title1 = new RegExp("\\s("+grammar_error_title+")\\s", "g");
                var arr_title1 = txt_title.match(reg_title1);
                if(Array.isArray(arr_title1)){
                    for(l=0;l<arr_title1.length;l++){
                        var txt_title = txt_title.replace(arr_title1[l], ' '+grammar_error_add_title+' ');
                    }
                }
                
                reg_title2 = new RegExp("\("+grammar_error_title+")\\s", "g");
                var arr_title2 = txt_title.match(reg_title2);
                if(Array.isArray(arr_title2)){
                    for(j=0;j<arr_title2.length;j++){
                        var txt_title = txt_title.replace(arr_title2[j], grammar_error_add_title+' ');
                    }
                }
                
                reg_title3 = new RegExp("\\s("+grammar_error_title+")[^h]", "g");
                var arr_title2 = txt_title.match(reg_title3);
                if(Array.isArray(arr_title2)){
                    for(j=0;j<arr_title2.length;j++){
                        var txt_title = txt_title.replace(arr_title2[j], ' '+grammar_error_add_title);
                    }
                }
            }
            
            // nếu cho phép đẩy
            if(document.getElementById('grammar_chk_push_title_'+i).checked){
                if(grammar_error_add_title !== ''){
                    arr_title[i] = grammar_error_add_title;
                }else{
                    arr_title[i] = grammar_error_title;
                }
                arr_title_old[i] = grammar_error_title;
            }
        }
        // add lại vào tiêu đề
        document.getElementById('txt_title').value = txt_title;
        document.getElementById('txt_title').innerHTML = txt_title;
    }
    
    // xử lý mô tả
    var v_count_summary = document.getElementById('grammar_count_summary').value;
    var arr_summary = [];
    var arr_summary_old = [];
    // lặp v_count_title để lấy các thông tin tiêu đề
    if(v_count_summary > 0){
        // lấy mô tả
        var txt_summary = document.getElementById('txt_summary').value;
        for(i=0;i<v_count_summary;i++){
            var grammar_error_summary = document.getElementById('grammar_error_summary_'+i).value;
            var grammar_error_add_summary = document.getElementById('grammar_error_add_summary_'+i).value;
            // nếu người dùng có nhập thì mới replace
            if(grammar_error_add_summary !== ''){
                reg_summary1 = new RegExp("\\s("+grammar_error_summary+")\\s", "g");
                var arr_summary1 = txt_summary.match(reg_summary1);
                if(Array.isArray(arr_summary1)){
                    for(l=0;l<arr_summary1.length;l++){
                        var txt_summary = txt_summary.replace(arr_summary1[l], ' '+grammar_error_add_summary+' ');
                    }
                }
                
                reg_summary2 = new RegExp("\("+grammar_error_summary+")\\s", "g");
                var arr_summary2 = txt_summary.match(reg_summary2);
                if(Array.isArray(arr_summary2)){
                    for(j=0;j<arr_summary2.length;j++){
                        var txt_summary = txt_summary.replace(arr_summary2[j], grammar_error_add_summary+' ');
                    }
                }
                
                reg_summary3 = new RegExp("\\s("+grammar_error_summary+")[^h]", "g");
                var arr_summary3 = txt_summary.match(reg_summary3);
                if(Array.isArray(arr_summary3)){
                    for(j=0;j<arr_summary3.length;j++){
                        var txt_summary = txt_summary.replace(arr_summary3[j], ' '+grammar_error_add_summary);
                    }
                }
            }
            // nếu cho phép đẩy
            if(document.getElementById('grammar_chk_push_summary_'+i).checked){
                if(grammar_error_add_summary !== ''){
                    arr_summary[i] = grammar_error_add_summary;
                }else{
                    arr_summary[i] = grammar_error_summary;
                }
                arr_summary_old[i] = grammar_error_summary;
            }
        }
        
        // add lại vào mô tả
        document.getElementById('txt_summary').value = txt_summary;
        document.getElementById('txt_summary').innerHTML = txt_summary;
    }
    
    // xử lý body
    var v_count_body = document.getElementById('grammar_count_body').value;
    var arr_body = [];
    var arr_body_old = [];
    // lặp v_count_body để lấy các thông tin nội dung
    if(v_count_body > 0){
        // lấy nội dung
        var txt_body = window.CKEDITOR.instances.txt_body.getData();
        for(i=0;i<v_count_body;i++){
            var grammar_error_body = document.getElementById('grammar_error_body_'+i).value;
            var grammar_error_add_body = document.getElementById('grammar_error_add_body_'+i).value;
            // nếu người dùng có nhập thì mới replace
            if(grammar_error_add_body !== ''){
                reg1 = new RegExp("\\s("+grammar_error_body+")\\s", "g");
                var arr_text1 = txt_body.match(reg1);
                if(Array.isArray(arr_text1)){
                    for(j=0;j<arr_text1.length;j++){
                        var txt_body = txt_body.replace(arr_text1[j], ' '+grammar_error_add_body+' ');
                    }
                }
                
                reg2 = new RegExp("\("+grammar_error_body+")\\s", "g");
                var arr_text2 = txt_body.match(reg2);
                if(Array.isArray(arr_text2)){
                    for(j=0;j<arr_text2.length;j++){
                        var txt_body = txt_body.replace(arr_text2[j], grammar_error_add_body+' ');
                    }
                }
                
                reg3 = new RegExp("\\s("+grammar_error_body+")[^h]", "g");
                var arr_text3 = txt_body.match(reg3);
                if(Array.isArray(arr_text3)){
                    for(j=0;j<arr_text3.length;j++){
                        var txt_body = txt_body.replace(arr_text3[j], ' '+grammar_error_add_body);
                    }
                }
                // trường hợp đã được dc bôi đậm bằng thẻ span
                var text_replace = '<span class="text_error">'+grammar_error_body+'</span>'
                var text_error_add_body_replace = '<span class="text_error">'+grammar_error_add_body+'</span>'
                var txt_body = txt_body.replace(new RegExp("("+text_replace+")", "g"), text_error_add_body_replace);
            }
            // nếu cho phép đẩy
            if(document.getElementById('grammar_chk_push_body_'+i).checked){
                if(grammar_error_add_body !== ''){
                    arr_body[i] = grammar_error_add_body;
                }else{
                    arr_body[i] = grammar_error_body; 
                }
                arr_body_old[i] = grammar_error_body;
            }
        }
        // add lại vào mô tả
        CKEDITOR.instances.txt_body.setData(txt_body);
    }
    
    // xử lý đẩy sang tool chinhta và tool crawl
    if(v_count_title > 0 || v_count_summary > 0 || v_count_body > 0){
        var d = new Date();
        var n = d.getTime();
        var v_url = CONFIG.BASE_URL + 'ajax/news/grammar24h_push_data_to_tool_crawl_and_chinhta/?' + n;
        var title_news = document.getElementById('txt_title').value;
        $.ajax({
            type: "POST",
            url: v_url,
            data: {arr_title: arr_title, arr_summary: arr_summary, arr_body: arr_body, v_title: title_news, arr_title_old: arr_title_old, arr_summary_old: arr_summary_old, arr_body_old: arr_body_old},
            async: false,
            success: function (data) {
            }
        });
    }
    
    // xử lý xong thì đóng cửa sổ overlay
    dong_cua_so_popup_overlay();
}
//Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
function send_main_cate_id_for_source(){
    var objSource = json_source;
    if(objSource != ''){
		if(window.opener.document.getElementById("hdn_source_id") != ''){
            window.opener.document.getElementById("hdn_source_id").value = objSource['id'];
        }
        if(window.opener.document.getElementById("txt_source") != ''){
            window.opener.document.getElementById("txt_source").value = objSource['name'];
        }
    }
}
//Begin 13/5/2020 AnhTT toi_uu_nguon_thong_tin_mac_dinh
/*
 * Hàm show crop riêng cho quiz
 */
function btn_onclick_new_window_quiz(p_forms, p_action_url, p_target){
    //frm_submit (p_forms, p_action_url, p_target);
    if (p_action_url) {
            p_forms.action = p_action_url;
    } else {
            p_forms.action = "";
    }		
    if (p_target) {
            p_forms.target = p_target;
    } else {
            p_forms.target = "";	
    }   
    p_forms.submit();
    //window.open(p_action_url, p_target, 'width=1000, height=700,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes')
}
// end 10/03/2016 tuyennt: bo_sung_o_nhap_id_giai_dau_lich_thi_dau_doi_hinh_doi_bong
//Begin 07-04-2016 : Thangnb toi_uu_upload_anh_gif
function openWindowImportWord(){
    window.open(CONFIG['BASE_URL']+'news_common/import_word/', '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}

/*
 * Hàm thực hiện check enabled hoặc disabled 2 cột check fresize
 */
function enabled_and_disabled_free_size(p_id){
    if(document.getElementById("chk_co_thanh_scroll_"+p_id).checked === true){
        $('#chk_co_dinh_cot_scroll_'+p_id).prop('disabled', false);
        $('#txt_so_cot_scroll_'+p_id).prop('disabled', false);
    }else{
        $('#chk_co_dinh_cot_scroll_'+p_id).prop('disabled', true);
        $('#txt_so_cot_scroll_'+p_id).prop('disabled', true);
        // bỏ luôn cả checked và xóa trống số cột ở phần freesize
        $('#chk_co_dinh_cot_scroll_'+p_id).prop('checked', false);
        document.getElementById('txt_so_cot_scroll_'+p_id).innerHTML = '';
        document.getElementById('txt_so_cot_scroll_'+p_id).value = '';
    }
}
/*
 * Hàm thực hiện set scroll hoặc responsive cho table trong nội dung bài viết
 */
function set_scroll_responsive_table() {
    // lấy nội dung
    //var txt_body = window.CKEDITOR.instances.txt_body.getData();
    if(document.getElementById('txt_body')){
        var txt_body = window.CKEDITOR.instances.txt_body.getData();
    }
    if(document.getElementById('txt_dulieubongda')){
        var txt_body = window.CKEDITOR.instances.txt_dulieubongda.getData();
    }
                
    // lấy danh sách table trong nội dung bài
    reg_tag_table = new RegExp(/<table\b[^>]*>([\s\S]*?)<\/table>/g);
    var v_matches_table = txt_body.match(reg_tag_table);
    
    if(document.getElementById('txt_dulieubongda')){
        reg_tag_div = new RegExp(/<div class="table_news_scroll">([\s\S]*?)<\/table>/g);
        var v_matches_div = txt_body.match(reg_tag_div);
    }
    
    if(document.getElementById('txt_body')){
        reg_tag_div = new RegExp(/<div class="table_news_scroll">([\s\S]*?)<\/div>/g);
        var v_matches_div = txt_body.match(reg_tag_div);
    }
    //console.log(v_matches_table);
    // kiểm tra mảng table
    if(Array.isArray(v_matches_table)){
        for(n=0;n<v_matches_table.length;n++){
            var tablenewsscroll_index = v_matches_table[n].indexOf("tablenewsscroll");
            var tablenewsscroll_th_index = v_matches_table[n].indexOf("tablenewsscroll_th");
            // kiểm tra có check scroll và đã có div bao ngoài hay chưa(nếu đã có rồi thì sẽ không thực hiện add nữa)
            if(document.getElementById("chk_co_thanh_scroll_"+n).checked === true){
                if(tablenewsscroll_index < 0){
                    var table = v_matches_table[n].replace('<table', '<table class="tablenewsscroll"');
                    // gắn thêm div báo ngoài
                    var v_table = '<div class="table_news_scroll"><div class="table_scroll">'+ table + '</div></div>';
                }else{
                    var v_table = v_matches_table[n];
                }
                // kiểm tra xem có tích freesize hay ko và số cột
                if(document.getElementById("chk_co_dinh_cot_scroll_"+n).checked === true && document.getElementById("txt_so_cot_scroll_"+n).value !== ''){
                    // replace <th, </th> thành td
                    v_table = v_table.replace(new RegExp("(<th)", "g"), '<td');
                    v_table = v_table.replace(new RegExp("(</th)", "g"), '</td');
                    // bắt theo tr
                    reg_tag_tr = new RegExp(/<tr\b[^>]*>([\s\S]*?)<\/tr>/g);
                    var v_matches_tr = v_table.match(reg_tag_tr);
                    if(Array.isArray(v_matches_tr)){
                        // khai báo mảng gắn cờ
                        var check_row = '';
                        
                        // lấy dòng đầu tiên tính lại cái number fix
                        var number_colum_fix = document.getElementById("txt_so_cot_scroll_"+n).value;
                        // lặp td của tr đầu tiên để lấy được xem số cột neo có bao nhiêu  colspan
                        var value_tr_0 = v_matches_tr[0];
                        // thực hiện lặp từng td
                        reg_tag_td_0 = new RegExp(/<td\b[^>]*>([\s\S]*?)<\/td>/g);
                        var v_matches_td_0 = value_tr_0.match(reg_tag_td_0);
                        
                        var number_colspan_check = 0;
                        for(m=0;m<v_matches_td_0.length && m<number_colum_fix;m++){
                            var check_colspan_number = v_matches_td_0[m].indexOf("colspan");
                            if(check_colspan_number > 0){
                                reg_tag_colspan_number = new RegExp(/colspan="([\s\S]*?)"/g);
                                var v_matches_colspan_number = v_matches_td_0[m].match(reg_tag_colspan_number);
                                if(Array.isArray(v_matches_colspan_number)){
                                    var arr_colspan_0 = v_matches_colspan_number[0].split('"');
                                    var number_colpan_0 = parseInt(arr_colspan_0[1]);
                                    number_colspan_check = number_colspan_check + parseInt(number_colpan_0);
                                }
                            }else{
                                number_colspan_check = number_colspan_check + 1;
                            }
                        }
                        
                        // gắn lại số cột fix
                        number_colum_fix = number_colspan_check;
                        // lặp tr
                        for(k=0;k<v_matches_tr.length;k++){
                            // số colum
                            var number_colum = number_colum_fix;
                            var value_tr = v_matches_tr[k];
                            // thực hiện cắt từng td
                            reg_tag_td = new RegExp(/<td\b[^>]*>([\s\S]*?)<\/td>/g);
                            var v_matches_td = value_tr.match(reg_tag_td);
                            if(Array.isArray(v_matches_td)){
                                var number_rowpan = 0;
                                if(check_row !== ''){
                                    var v_check = check_row.split("#");
                                    if(Array.isArray(v_check)){
                                        for(m=0;m<v_check.length;m++){
                                            if(v_check[m] !== ''){
                                                // kiểm tra xem k là thứ mấy
                                                var number_check = parseInt(v_check[m]);
                                                if(k<number_check){
                                                    number_colum = parseInt(number_colum) - 1;
                                                }
                                            }
                                        }
                                    }
                                }
                                var number_td_for = number_colum;
                                var number_colspan = 0
                                var number_colspan_check_canh_bao = 0
                                // số lượng colum để check số lượng rowspan và colspan
                                for(l=0;l<v_matches_td.length && l<number_colum;l++){
                                    // kiểm tra xem td có rowpan hay ko
                                    var check_rowspan = v_matches_td[l].indexOf("rowspan");
                                    if(check_rowspan > 0){
                                        reg_tag_rowspan = new RegExp(/rowspan="([\s\S]*?)"/g);
                                        var v_matches_rowpan = v_matches_td[l].match(reg_tag_rowspan);
                                        if(Array.isArray(v_matches_rowpan)){
                                            var arr_rowpan = v_matches_rowpan[0].split('"');
                                            var number_rowpan = arr_rowpan[1];
                                            number_rowpan = parseInt(number_rowpan) + k;
                                            check_row += number_rowpan+'#';
                                        }
                                    }
                                    
                                    // kiểm tra xem td có colspan hay ko
                                    var check_colspan = v_matches_td[l].indexOf("colspan");
                                    if(check_colspan > 0){
                                        reg_tag_colspan = new RegExp(/colspan="([\s\S]*?)"/g);
                                        var v_matches_colspan = v_matches_td[l].match(reg_tag_colspan);
                                        if(Array.isArray(v_matches_colspan)){
                                            var arr_colspan = v_matches_colspan[0].split('"');
                                            number_colspan_check_canh_bao = number_colspan_check_canh_bao + parseInt(arr_colspan[1]);
                                            // số vòng lặp + số rownpan của td
                                            if(parseInt(arr_colspan[1]) > 1){
                                                l = l + parseInt(arr_colspan[1]);
                                            }
                                        }
                                        number_colspan = number_colspan + 1;
                                    }else{
                                        number_colspan = number_colspan + 1;
                                        number_colspan_check_canh_bao = number_colspan_check_canh_bao + 1;
                                    }
                                    
                                    // kiểm tra nếu số lượng tổng colspan mà lơn hơn số lượng number colum thì thông báo và dừng
                                    if(number_colspan_check_canh_bao > number_colum){
                                        alert('Bảng số '+ (n+1) +': Cột chọn cố định có Merge cells!');
                                        return false;
                                    }
                                }
                                
                                //gắn lại
                                number_td_for = number_colspan;
                                // lặp số td để replace
                                for(j=0;j<v_matches_td.length && j<number_td_for;j++){
                                    // replace td thành tr
                                    var value_td = v_matches_td[j].replace('<td', '<th class="fixed-side"');
                                    value_td = value_td.replace('</td>', '</th>');
                                    // replace td mới vào table
                                    value_tr = value_tr.replace(v_matches_td[j], value_td);
                                }
                                
                            }
                            
                            // replace tr vào table
                            v_table = v_table.replace(v_matches_tr[k], value_tr);
                        }
                        //v_table = v_table.replace('tablenewsscroll', 'tablenewsscroll tablenewsscroll_th');
                    }
                }else{
                    // replace <th, </th> thành td
                    v_table = v_table.replace(new RegExp("(<th)", "g"), '<td');
                    v_table = v_table.replace(new RegExp("(</th)", "g"), '</td');
                }
                
                // replace lại vào trong text body
                var txt_body = txt_body.replace(v_matches_table[n], v_table);
                
            // trường hợp nếu bỏ chọn scroll (trước đó có tích trọn scroll)
            }else if(document.getElementById("chk_co_thanh_scroll_"+n).checked === false && (tablenewsscroll_index > 0 || tablenewsscroll_th_index > 0)){
                // replace <th, </th> thành td
                v_matches_table[n] = v_matches_table[n].replace(new RegExp("(<th)", "g"), '<td');
                v_matches_table[n] = v_matches_table[n].replace(new RegExp("(</th)", "g"), '</td');
                
                if(document.getElementById('txt_body')){
                    if(Array.isArray(v_matches_div)){
                        for(i=0;i<v_matches_div.length;i++){
                            v_table_reg = v_matches_div[i].replace(new RegExp("(<th)", "g"), '<td');
                            v_table_reg = v_table_reg.replace(new RegExp("(</th)", "g"), '</td');
                            var table_index = v_table_reg.indexOf(v_matches_table[n]);
                            if(table_index > 0){
                                var table_reg = v_matches_table[n].replace('tablenewsscroll', '');
                                var table_reg = table_reg.replace('tablenewsscroll_th', '');
                                var txt_body = txt_body.replace(v_matches_div[i], table_reg);
                            }
                        }
                    }
                }
                
                if(document.getElementById('txt_dulieubongda')){
                    if(Array.isArray(v_matches_div)){
                        for(i=0;i<v_matches_div.length;i++){
                            v_table_reg = v_matches_div[i].replace(new RegExp("(<th)", "g"), '<td');
                            v_table_reg = v_table_reg.replace(new RegExp("(</th)", "g"), '</td');
                            var table_index = v_table_reg.indexOf(v_matches_table[n]);
                            if(table_index > 0){
                                var table_reg = v_matches_table[n].replace('tablenewsscroll', '');
                                var table_reg = table_reg.replace('tablenewsscroll_th', '');
                                var txt_body = txt_body.replace(v_matches_div[i], table_reg);
                            }
                        }
                    }
                }
            }
        }
        if(document.getElementById('txt_body')){
            // add lại body
            CKEDITOR.instances.txt_body.setData(txt_body);
        }
        if(document.getElementById('txt_dulieubongda')){
            // add lại body
            CKEDITOR.instances.txt_dulieubongda.setData(txt_body);
        }
    }
	
    // xử lý xong thì đóng cửa sổ overlay
    dong_cua_so_popup_overlay();
}
function chon_bai_lien_quan_duoi_noi_dung(){
    v_news_id = 0;
    if(typeof CKEDITOR.instances.txt_bai_lien_quan_noi_dung_bai_viet_24h != 'undefined'){
        v_content = CKEDITOR.instances.txt_bai_lien_quan_noi_dung_bai_viet_24h.getData();
        if(v_content != ''){
            var regex = /-c(\d+)a(\d+)/gi;
            match = regex.exec(v_content);
            v_news_id = parseInt(match[2]);
        }
    }
    openWindow(CONFIG.BASE_URL+'news_common/dsp_published_news_by_select/txt_bai_lien_quan/?news_id='+v_news_id,850,500,false);
}


/* Begin: ocm_mobile_lazyload */
/*!
  hey, [be]Lazy.js - v1.3.1 - 2015.02.01 
  A lazy loading and multi-serving image script
  (c) Bjoern Klinggaard - @bklinggaard - http://dinbror.dk/blazy
*/
;(function(root, blazy) {
	if (typeof define === 'function' && define.amd) {
        // AMD. Register bLazy as an anonymous module
        define(blazy);
	} else if (typeof exports === 'object') {
		// Node. Does not work with strict CommonJS, but
        // only CommonJS-like environments that support module.exports,
        // like Node. 
		module.exports = blazy();
	} else {
        // Browser globals. Register bLazy on window
        root.Blazy = blazy();
	}
})(this, function () {
	'use strict';
	
	//vars
	var source, options, viewport, images, count, isRetina, destroyed;
	//throttle vars
	var validateT, saveViewportOffsetT;
	
	// constructor
	function Blazy(settings) {
		//IE7- fallback for missing querySelectorAll support
		if (!document.querySelectorAll) {
			var s=document.createStyleSheet();
			document.querySelectorAll = function(r, c, i, j, a) {
				a=document.all, c=[], r = r.replace(/\[for\b/gi, '[htmlFor').split(',');
				for (i=r.length; i--;) {
					s.addRule(r[i], 'k:v');
					for (j=a.length; j--;) a[j].currentStyle.k && c.push(a[j]);
						s.removeRule(0);
				}
				return c;
			};
		}
		//init vars
		destroyed 				= true;
		images 					= [];
		viewport				= {};
		//options
		options 				= settings 				|| {};
		options.error	 		= options.error 		|| false;
		options.offset			= options.offset 		|| 100;
		options.success			= options.success 		|| false;
	  	options.selector 		= options.selector 		|| '.b-lazy';
		options.separator 		= options.separator 	|| '|';
		options.container		= options.container 	?  document.querySelectorAll(options.container) : false;
		options.errorClass 		= options.errorClass 	|| 'b-error';
		options.breakpoints		= options.breakpoints	|| false;
		options.successClass 	= options.successClass 	|| 'b-loaded';
		options.src = source 	= options.src			|| 'data-src';
		isRetina				= window.devicePixelRatio > 1;
		viewport.top 			= 0 - options.offset;
		viewport.left 			= 0 - options.offset;
		//throttle, ensures that we don't call the functions too often
		validateT				= throttle(validate, 25); 
		saveViewportOffsetT			= throttle(saveViewportOffset, 50);

		saveViewportOffset();	
				
		//handle multi-served image src
		each(options.breakpoints, function(object){
			if(object.width >= window.screen.width) {
				source = object.src;
				return false;
			}
		});
		
		// start lazy load
		initialize();	
  	}
	
	/* public functions
	************************************/
	Blazy.prototype.revalidate = function() {
 		initialize();
   	};
	Blazy.prototype.load = function(element, force){
		if(!isElementLoaded(element)) loadImage(element, force);
	};
	Blazy.prototype.destroy = function(){
		if(options.container){
			each(options.container, function(object){
				unbindEvent(object, 'scroll', validateT);
			});
		}
		unbindEvent(window, 'scroll', validateT);
		unbindEvent(window, 'resize', validateT);
		unbindEvent(window, 'resize', saveViewportOffsetT);
		count = 0;
		images.length = 0;
		destroyed = true;
	};
	
	/* private helper functions
	************************************/
	function initialize(){
		// First we create an array of images to lazy load
		createImageArray(options.selector);
		// Then we bind resize and scroll events if not already binded
		if(destroyed) {
			destroyed = false;
			if(options.container) {
	 			each(options.container, function(object){
	 				bindEvent(object, 'scroll', validateT);
	 			});
	 		}
			bindEvent(window, 'resize', saveViewportOffsetT);
			bindEvent(window, 'resize', validateT);
	 		bindEvent(window, 'scroll', validateT);
		}
		// And finally, we start to lazy load. Should bLazy ensure domready?
		validate();	
	}
	
	function validate() {
		for(var i = 0; i<count; i++){
			var image = images[i];
 			if(elementInView(image) || isElementLoaded(image)) {
				Blazy.prototype.load(image);
 				images.splice(i, 1);
 				count--;
 				i--;
 			} 
 		}
		if(count === 0) {
			Blazy.prototype.destroy();
		}
	}
	
	function loadImage(ele, force){
		// if element is visible
		if(force || (ele.offsetWidth > 0 && ele.offsetHeight > 0)) {
			var dataSrc = ele.getAttribute(source) || ele.getAttribute(options.src); // fallback to default data-src
			if(dataSrc) {
				var dataSrcSplitted = dataSrc.split(options.separator);
				var src = dataSrcSplitted[isRetina && dataSrcSplitted.length > 1 ? 1 : 0];
				var img = new Image();
				// cleanup markup, remove data source attributes
				each(options.breakpoints, function(object){
					ele.removeAttribute(object.src);
				});
				ele.removeAttribute(options.src);
				img.onerror = function() {
					if(options.error) options.error(ele, "invalid");
					ele.className = ele.className + ' ' + options.errorClass;
				}; 
				img.onload = function() {
					// Is element an image or should we add the src as a background image?
			      		ele.nodeName.toLowerCase() === 'img' ? ele.src = src : ele.style.backgroundImage = 'url("' + src + '")';	
					ele.className = ele.className + ' ' + options.successClass;	
					if(options.success) options.success(ele);
				};
				img.src = src; //preload image
			} else {
				if(options.error) options.error(ele, "missing");
				ele.className = ele.className + ' ' + options.errorClass;
			}
		}
	 }
			
	function elementInView(ele) {
		var rect = ele.getBoundingClientRect();
		
		return (
			// Intersection
			rect.right >= viewport.left
			&& rect.bottom >= viewport.top
			&& rect.left <= viewport.right
			&& rect.top <= viewport.bottom
		 );
	 }
	 
	 function isElementLoaded(ele) {
		 return (' ' + ele.className + ' ').indexOf(' ' + options.successClass + ' ') !== -1;
	 }
	 
	 function createImageArray(selector) {
 		var nodelist 	= document.querySelectorAll(selector);
 		count 			= nodelist.length;
 		//converting nodelist to array
 		for(var i = count; i--; images.unshift(nodelist[i])){}
	 }
	 
	 function saveViewportOffset(){
		 viewport.bottom = (window.innerHeight || document.documentElement.clientHeight) + options.offset;
		 viewport.right = (window.innerWidth || document.documentElement.clientWidth) + options.offset;
	 }
	 
	 function bindEvent(ele, type, fn) {
		 if (ele.attachEvent) {
         		ele.attachEvent && ele.attachEvent('on' + type, fn);
       	 	} else {
         	       ele.addEventListener(type, fn, false);
       		}
	 }
	 
	 function unbindEvent(ele, type, fn) {
		 if (ele.detachEvent) {
         		ele.detachEvent && ele.detachEvent('on' + type, fn);
       	 	} else {
         	       ele.removeEventListener(type, fn, false);
       		}
	 }
	 
	 function each(object, fn){
 		if(object && fn) {
 			var l = object.length;
 			for(var i = 0; i<l && fn(object[i], i) !== false; i++){}
 		}
	 }
	 
	 function throttle(fn, minDelay) {
     		 var lastCall = 0;
		 return function() {
			 var now = +new Date();
         		 if (now - lastCall < minDelay) {
           			 return;
			 }
         		 lastCall = now;
         		 fn.apply(images, arguments);
       		 };
	 }
  	
	 return Blazy;
});
/*
 * goi toi 1 url theo kieu ajax
 * @param string p_element_id ID noi can hien du lieu
 * @param string url URL can lay du lieu
 */
function AjaxAction_lazyload_list_news(p_element_id, url, p_func_ext)
{
	var xmlHttp = new GetXmlHttpObject();
	if (xmlHttp == null) {
		return;
	}
    // lấy nội dung của body
	var v_body = $("body").html();
    var v_domain_image = '//static.24h.com.vn';
	// Nếu sử dụng link cdn thì sẽ đưa đường dẫn về ảnh về cdn
    if(v_body && v_body.indexOf('cdn.24h.com.vn') > 0){
        var v_domain_image = '//cdn.24h.com.vn';
    }
	var bar='<div style="background:#ebebeb;text-align: center"><img alt="Xin ch&#7901; &#273;&#7907;i, 24H &#273;ang t&#7843;i th&#234;m s&#7843;n ph&#7849;m" src="'+v_domain_image+'/images/loading.gif" class="more-loading"><b style="font-size:12px; color:#999999; text-align:center;">Xin ch&#7901; &#273;&#7907;i, 24H &#273;ang t&#7843;i th&#234;m video</b><div class="clear" style="margin-bottom:10px;"></div></div>';
    
    if(p_element_id != '' && document.getElementById(p_element_id)){
        document.getElementById(p_element_id).innerHTML = bar;
    }
	xmlHttp.onreadystatechange = function() {
		if (xmlHttp.readyState==4 || xmlHttp.readyState==200) {
            if(p_element_id != '' && document.getElementById(p_element_id)){
                document.getElementById(p_element_id).innerHTML =xmlHttp.responseText;
            }
			chay_javascript_tu_ket_qua_ajax(xmlHttp.responseText);
			if (p_func_ext && p_func_ext != '') {
				eval(p_func_ext);
			}
		}
	}
	xmlHttp.open("GET", url, true);
	xmlHttp.send(null);
}

function thiet_lap_thay_doi_html_theo_lazy_load(img_id,current_page,ma_box,url_prev,url_next){
	// ẩn ảnh loading...
    if (document.getElementById(img_id)) {
		document.getElementById(img_id).style.display = 'none';
	}          
} 

function chay_javascript_tu_ket_qua_ajax(html_ajax) {
  var scripts = new Array();         // Tao mang chua ma script

  // Lay ma script
  while(html_ajax.indexOf("<script") > -1 || html_ajax.indexOf("</script") > -1) {
    var s = html_ajax.indexOf("<script");
    var s_e = html_ajax.indexOf(">", s);
    var e = html_ajax.indexOf("</script", s);
    var e_e = html_ajax.indexOf(">", e);

    // Them script vao mang
    scripts.push(html_ajax.substring(s_e+1, e));
    // Tach script ra html_ajax
    html_ajax = html_ajax.substring(0, s) + html_ajax.substring(e_e+1);
  }

  // Thuc hien eval doi voi tung script trong mang
  for(var i=0; i<scripts.length; i++) {
    try {
      eval(scripts[i]);
    }
    catch(ex) {

    }
  }
}
/* End: ocm_mobile_lazyload */
//14-10-2020 Begin DanNC function phan quyen danh muc
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
function openCity(evt, cityName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("ocm_tab");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("item");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.className += " active";
}
function xoa_dau(str) {
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/đ/g, "d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    return str;
}
function loc_view(bt, view){
    $(bt).on("keyup", function() {
        var value = $(this).val().toLowerCase();
        var value = xoa_dau(value);
        $(view).filter(function() {
            var show = $(this).text();
            var show = xoa_dau(show);
            $(this).toggle(show.toLowerCase().indexOf(value) > -1);
        });
    });
}
//14-10-2020 End DanNC function phan quyen danh muc
// hàm mở upload ảnh gif hỏi đáp
function openWindowUploadImagegifhoidap(p_image_type){
    var news_title  = document.frm_update_data.txt_title.value;
    window.open(CONFIG['BASE_URL']+'upload_image/?image_type='+p_image_type+'&news_title='+news_title, '', 'width=500,height=600,toolbar=no,status=yes,menubar=no,scrollbars=yes,resizable=yes');
}
function add_row_pr_24hmoney(){
    var stt_row_pr_24hmoney = document.getElementById('so_dong_pr_24hmoney').value;
    console.log(stt_row_pr_24hmoney);
	v_html_xuat_ban_pr = v_html_xuat_ban_pr_24hmoney.replace(/{stt}/g, parseInt(stt_row_pr_24hmoney)+1);
    if(stt_row_pr_24hmoney >=10){
        alert('Bạn chỉ được phép thêm 10 khoảng thời gian bài pr');
        return;
    }
	++stt_row_pr_24hmoney;
	document.getElementById('so_dong_pr_24hmoney').value = stt_row_pr_24hmoney;
	$('#tableContent').append(v_html_xuat_ban_pr);
    ocm_chay_javascript_tu_ket_qua_ajax(v_html_xuat_ban_pr);
}
/*
 * Hàm xóa row 24h money
 */
function delete_row_pr_24hmoney(){
    // Xóa dòng pr 24hmoney
    for(i =1;i<20;i++){
        if(document.getElementById('chk_stt_pr24hmoney'+i)){
            obj = document.getElementById('chk_stt_pr24hmoney'+i);
            if (obj.checked == true){
                $('#rowContent-'+i).remove();
                document.getElementById('so_dong_pr_24hmoney').value = (document.getElementById('so_dong_pr_24hmoney').value - 1);
            }
        }
    }
    if(document.getElementById('so_dong_pr_24hmoney').value == 0){
        if(document.getElementById('chk_day_bai_24hmoney')){
            document.getElementById('chk_day_bai_24hmoney').checked = false;
        }
    }
}
/*
 * Hàm thực hiện ẩn hiện thời gian xuất bản bài PR 24hmoney
 */
function an_hien_thoi_gian_xuat_ban_pr_tinmoi(obj){
    if(obj.checked){
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_tinmoi').style.display = '';
    }else{
        document.getElementById('tr_thoi_gian_xuat_ban_bai_pr_tinmoi').style.display = 'none';
    }
}
function add_row_pr_tinmoi(){
    var stt_row_pr_tinmoi = document.getElementById('so_dong_pr_tinmoi').value;
	v_html_xuat_ban_pr = v_html_xuat_ban_pr_tinmoi.replace(/{stt}/g, parseInt(stt_row_pr_tinmoi)+1);
    if(stt_row_pr_tinmoi >=10){
        alert('Bạn chỉ được phép thêm 10 khoảng thời gian bài pr');
        return;
    }
	++stt_row_pr_tinmoi;
	document.getElementById('so_dong_pr_tinmoi').value = stt_row_pr_tinmoi;
	$('#tableContenttinmoi').append(v_html_xuat_ban_pr);
    ocm_chay_javascript_tu_ket_qua_ajax(v_html_xuat_ban_pr);
}
/*
 * Hàm xóa row 24h money
 */
function delete_row_pr_tinmoi(){
    // Xóa dòng pr tinmoi
    for(i =1;i<20;i++){
        if(document.getElementById('chk_stt_prtinmoi'+i)){
            obj = document.getElementById('chk_stt_prtinmoi'+i);
            if (obj.checked == true){
                $('#rowContent-'+i).remove();
                document.getElementById('so_dong_pr_tinmoi').value = (document.getElementById('so_dong_pr_tinmoi').value - 1);
            }
        }
    }
    if(document.getElementById('so_dong_pr_tinmoi').value == 0){
        if(document.getElementById('chk_day_bai_tinmoi')){
            document.getElementById('chk_day_bai_tinmoi').checked = false;
        }
    }
}

// Hàm thực hiện ẩn hiện box video highlight
function show_box_select_box_video_highlight(element){
    if (document.getElementById('giai_dau_tuong_thuat_video_highlight')) {
        // kiểm tra checked
        if (element.checked) {
            document.getElementById('giai_dau_tuong_thuat_video_highlight').style.display = 'block';
        }else{
            document.getElementById('giai_dau_tuong_thuat_video_highlight').style.display = 'none';
        }
    }
}
function slug_seo_chi_tiet(obj) {
    slugText = obj.value;
    if(slugText == ''){
        return;
    }
    var v_url = CONFIG.BASE_URL + 'ajax/seo_chi_tiet_bai_viet/get_slug_by_input_text';
    var text = '';
    $.ajax({
        type: "POST",
        url: v_url,
        data: {slugText: slugText},
        async: false,
        success: function (data) {
            if (data != '') {
                obj.value = data;
            }
        }
    });
}
// ham xu ly chane box type
function change_box_type_template(p_id_box, p_stt){
    // chọn loại box
    var box_type_template = $('#'+p_id_box).val();
    var template_id = $('#sel_page_template').val();
    $('#tr_max_row_new_box_'+p_stt).show();
    $('#tr_show_date_'+p_stt).show();
    $('#source_tr_'+p_stt).show();
    $('#tr_show_event_'+p_stt).show();
    $('#tr_img_background_'+p_stt).show();
    $('#tr_check_duplicate_'+p_stt).hide();
    $('#tr_view_new_box_'+p_stt).hide();
    $('#tr_show_tagline_'+p_stt).hide();
    $('#tr_show_filter_date_'+p_stt).hide();
    // nếu là box dữ liệu bóng đá
    if(box_type_template === 'du_lieu_bong_da' || box_type_template == 'du_lieu_google_sheets'){
        switch (box_type_template){
            case 'du_lieu_bong_da':
        	$('#div_source_du_lieu_bong_da_'+p_stt).show();
                $('#div_source_google_sheets_'+p_stt).hide();
                break;

            case 'du_lieu_google_sheets':
                $('#div_source_du_lieu_bong_da_'+p_stt).hide();
                $('#div_source_google_sheets_'+p_stt).show();
                break;
        }

        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#source_all_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).hide();
        $('#tr_max_row_new_box_'+p_stt).hide();
        $('#tr_show_date_'+p_stt).hide();
        $('#tr_show_event_'+p_stt).hide();
        $('#tr_img_background_'+p_stt).hide();

    // nếu là box tin nổi bật
    }else if(box_type_template === 'tin_bai_cot_phai'){
        $('#div_source_tin_noi_bat_'+p_stt).show();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).hide();

    // nếu là box video highligh bóng đá
    }else if(box_type_template === 'video_highlight_bong_da' ){
        $('#div_source_video_highlight_bong_da_'+p_stt).show();
        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).hide();
        $('#tr_check_duplicate_'+p_stt).show();

    // nếu là box tin nổi bật khác
    }else if(box_type_template === 'tin_bai_noi_bat_khac'){
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).show();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).hide();
        $('#source_tin_dang_tab_'+p_stt).hide();
        $('#tr_check_duplicate_'+p_stt).show();
        if(template_id ==1){
            $('#tr_show_tagline_'+p_stt).show();
            $('#tr_show_filter_date_'+p_stt).show();
        }

    // nếu là box tin bài dạng tab
    }else if(box_type_template === 'box_tin_bai_dang_tab'){
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).hide();
        $('#tab_muc_phu_container_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).show();
        $('#show_tab_muc_phu_'+p_stt).prop('checked', true);

    // nếu là box trực tiếp bóng đá theo giải đấu
    }else if(box_type_template === 'box_truc_tiep_bong_da_theo_giai_dau'){
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).show();
        $('#source_all_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).hide();
    }else if(box_type_template === 'tin_noi_bat' || box_type_template === 'tin_bai_noi_dung' || box_type_template === 'box_tin_bai_su_kien_noi_bat'){
        $('#div_source_tin_noi_bat_'+p_stt).show();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#source_tin_noi_bat_khac_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).show();
        $('#source_tin_dang_tab_'+p_stt).hide();
        $('#tr_check_duplicate_'+p_stt).show();
        if(box_type_template === 'tin_bai_noi_dung' || box_type_template === 'box_tin_bai_su_kien_noi_bat'){
            $('#tr_view_new_box_'+p_stt).show();
        }
        if((template_id ==2 && box_type_template === 'tin_noi_bat') || box_type_template === 'tin_bai_noi_dung'){
            $('#tr_show_tagline_'+p_stt).show();
        }
        
    }else if(box_type_template === 'box_video_chon_loc' || box_type_template === 'box_tin_nhieu_nguoi_doc'){
        $('#div_source_video_highlight_bong_da_'+p_stt).hide();
        $('#div_source_tin_noi_bat_'+p_stt).hide();
        $('#div_source_du_lieu_bong_da_'+p_stt).hide();
        $('#div_source_google_sheets_'+p_stt).hide();
        $('#source_tin_box_video_chon_loc_'+p_stt).hide();
        $('#tr_view_new_box_'+p_stt).hide();
        $('#div_source_truc_tiep_bong_da_theo_giai_dau_'+p_stt).hide();
        $('#source_all_'+p_stt).hide();
        $('#source_tin_dang_tab_'+p_stt).hide();
        $('#tr_check_duplicate_'+p_stt).hide();
        $('#tr_show_date_'+p_stt).hide();
        $('#tr_show_event_'+p_stt).hide();
        $('#tr_max_row_new_box_'+p_stt).hide();
        $('#source_tr_'+p_stt).hide();
        if(box_type_template === 'box_tin_nhieu_nguoi_doc'){
            $('#tr_view_new_box_'+p_stt).show();
        }
    }
}

/*
* Hàm chọn chuyên mục vào select box sau đó đóng cửa sổ chọn chuyên mục.Ko submit form
* p_form_obj : form truyền vào
* p_target_id : ID select box để bắn dữ liệu vào
 */
function radio_select_livescore_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_category = new Array();
    v_id = p_form_obj['chk_item_id_'+p_target_id].value;
    v_name = p_form_obj['hdn_livescore_name_'+v_id].value;
    v_arr_category[v_arr_category.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có mã livescore nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_category) {
            var v_ten_chuyen_muc = v_arr_category[i][0];
            v_arr_category[i][0] = 'Mã livescore: '+v_arr_category[i][1];
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box)) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_chuyen_muc);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            window.opener.$("#"+p_div_to_put_source_from).val('livescore');
            window.opener.$("#"+p_div_to_put_source_from+'_id').val(v_id);
        }
        window.close();
    }
    return false;
}

// chọn loại data google sheets và đóng cửa sổ chọn
function radio_select_google_sheets_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_category = new Array();
    v_id = p_form_obj['chk_item_id_'+p_target_id].value;
    v_name = p_form_obj['hdn_google_sheets_name_'+v_id].value;
    v_arr_category[v_arr_category.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có loại dữ liệu nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_category) {
            var v_ten_chuyen_muc = v_arr_category[i][0];
            v_arr_category[i][0] = 'Mã dữ liệu Google Sheets: '+v_arr_category[i][1];
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box)) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_chuyen_muc);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            window.opener.$("#"+p_div_to_put_source_from).val('google_sheets');
            window.opener.$("#"+p_div_to_put_source_from+'_id').val(v_id);
        }
        window.close();
    }
    return false;
}

/*
* Hàm chọn chuyên mục vào select box sau đó đóng cửa sổ chọn chuyên mục.Ko submit form
* p_form_obj : form truyền vào
* p_target_id : ID select box để bắn dữ liệu vào
 */
function radio_select_video_highlight_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_category = new Array();
    v_id = p_form_obj['chk_item_id_'+p_target_id].value;
    v_name = p_form_obj['hdn_giai_highlight_name_'+v_id].value;
    v_arr_category[v_arr_category.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có giải đấu nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_category) {
            var v_ten_chuyen_muc = v_arr_category[i][0];
            v_arr_category[i][0] = 'Giải đấu: '+v_name;
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box)) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_chuyen_muc);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            window.opener.$("#"+p_div_to_put_source_from).val('highlight_giai_dau');
            window.opener.$("#"+p_div_to_put_source_from+'_id').val(v_id);
        }
        window.close();
    }
    return false;
}

/*
* Hàm chọn chuyên mục vào select box sau đó đóng cửa sổ chọn chuyên mục.Ko submit form
* p_form_obj : form truyền vào
* p_target_id : ID select box để bắn dữ liệu vào
 */
function radio_select_giai_dau_tuong_thuat_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_arr_category = new Array();
    v_id = p_form_obj['chk_item_id_'+p_target_id].value;
    v_name = p_form_obj['hdn_giai_dau_tuong_thuat_name_'+v_id].value;
    v_arr_category[v_arr_category.length] = new Array(v_name, v_id);
    if (v_id <= 0) {
        alert("Chưa có giải đấu nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        for (i in v_arr_category) {
            var v_ten_chuyen_muc = v_arr_category[i][0];
            v_arr_category[i][0] = 'Giải đấu: '+v_name;
            openerPutElementToSelectList(v_arr_category[i][0], v_arr_category[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box)) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_chuyen_muc);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            window.opener.$("#"+p_div_to_put_source_from).val('giai_dau_tuong_thuat');
            window.opener.$("#"+p_div_to_put_source_from+'_id').val(v_id);
        }
        window.close();
    }
    return false;
}
/*
 * hàm xóa ảnh icon tab template
 */
function xoa_icon_tab_template(p_id_tab){
    if(document.getElementById('hdn_file_icon_tab_'+p_id_tab)){
        document.getElementById('hdn_file_icon_tab_'+p_id_tab).value = '';
        document.getElementById('div_icon_tab_'+p_id_tab).style.display = 'none';
        alert('Xóa icon thành công');
    }
}
function send_message_quan_tri_nhan_hang(news_id,news_type,p_stt){
    if(typeof p_stt === 'undefined'){
        p_stt = news_id;
    }
    console.log(p_stt);
    ifr_qlnb = document.getElementById("ifrm_load_quan_ly_nhan"+p_stt).contentWindow;
    var data    = {news_id:news_id,newstype:news_type};
    data        = JSON.stringify(data);
    ifr_qlnb.postMessage(data, '*');
}
function quanlynhan_process_mesage(event){
     try {
        data = JSON.parse(event.data);
    } catch (error) {
        return;
    }
    console.log(data);
    // Kiểm tra biến
    if (data.web != "quanlynhan") return;
    var v_id = parseInt(data.id);
    var v_stt = parseInt(data.stt);
    if(v_id >0){
        if(v_stt > 0){
            document.getElementById('hdn_id_brand_produc'+v_stt).value = v_id;
        }else{
            document.getElementById('hdn_id_brand_produc').value = v_id;
        }
    }
    var v_name = data.name;
    if(v_name != ''){
        if(v_stt > 0){
            document.getElementById('hdn_name_brand_produc'+v_stt).value = v_name;
        }else{
            document.getElementById('hdn_name_brand_produc').value = v_name;
        }
    }
}
//  17-03-2021 DanNC begin bo sung quan tri slot DFP
function toggle_set_tracking(p_phien_ban, box_phien_ban) {
    var this_check = document.getElementById(p_phien_ban).checked;
    var checkboxes = document.querySelectorAll(box_phien_ban);
    if(this_check == true) {
        // Lặp và thiết lập checked
        for (var i = 0; i < checkboxes.length; i++){
            checkboxes[i].checked = true;
        }
    } else {
        // Lặp và thiết lập checked
        for (var i = 0; i < checkboxes.length; i++){
            checkboxes[i].checked = false;
        }
    }
}
function btn_on_off_slot(p_forms, p_action_url) {
    if (p_action_url) {
            p_forms.action = p_action_url;
    } else {
            p_forms.action = "";
    }
    p_forms.submit();
}
//  17-03-2021 DanNC end bo sung quan tri slot DFP

function checkbox_select_event_and_close_window(p_form_obj, p_target_id, p_div_to_put_source_from, p_div_to_insert_ten_box, p_class_check_da_duoc_chon)
{
    v_rows = p_form_obj.hdn_record_count.value;
    v_count = 0;
    v_arr_event = new Array();
    v_list_id = '';
    for (i=0; i<v_rows; i++) {
        v_id = p_form_obj['rad_event_id_'+i];
        v_name = p_form_obj['hdn_event_'+i];
        if (v_id && v_id.checked) {
            v_count++;
            v_arr_event[v_arr_event.length] = new Array(v_name.value, v_id.value);
            if(v_list_id !== ''){
                v_list_id += ','+v_id.value;
            }else{
                v_list_id += v_id.value;
            }
        }
    }
    if (p_class_check_da_duoc_chon != '') {
        var all_source_from = window.opener.$("."+p_class_check_da_duoc_chon);
        for (i = 0; i < all_source_from.length; i++) {
            current_id = all_source_from[i].value;
            if (current_id == v_id) {
                var class_name = all_source_from[i].id;
                var arr_class_id = class_name.split("_");
                var class_source_id = 'box_tin_ben_duoi_name_'+arr_class_id[(arr_class_id.length)-2];
                var name_box_erorr = window.opener.document.getElementById(class_source_id).value;
                alert("Sự kiện " + v_name + " đã được chọn vào box "+name_box_erorr+"!");
                return false;
            }
        }
    }
    //v_arr_event[v_arr_event.length] = new Array(v_name, v_id);
    if (v_count <= 0) {
        alert("Chưa có Sự kiện nào được chọn!");
    } else {
        openerResetSelectList(p_target_id);
        console.log(v_arr_event.length);
        for (i = 0; i < v_arr_event.length; i++) {
            var v_ten_event = v_arr_event[i][0];
            v_arr_event[i][0] = 'Sự kiện: '+v_arr_event[i][0]+' - ID: '+v_arr_event[i][1];
            openerPutElementToSelectList(v_arr_event[i][0], v_arr_event[i][1], p_target_id);
            if (p_div_to_insert_ten_box != '') {
                if (window.opener.$("#"+p_div_to_insert_ten_box).length > 0) {
                    window.opener.$("#" + p_div_to_insert_ten_box).html(v_ten_event);
                }
            }
        }
        if (p_div_to_put_source_from != '') {
            if (window.opener.$("#"+p_div_to_put_source_from).length > 0) {
                window.opener.$("#" + p_div_to_put_source_from).val('event');
            }
            if (window.opener.$("#"+p_div_to_put_source_from+'_id').length > 0) {
                window.opener.$("#" + p_div_to_put_source_from + '_id').val(v_list_id).trigger('change');
            }
        }
        window.close();
    }
    return false;
}
// 16-06-2021 Begin DanNC function add row new table
function add_more_row_to_table_new(p_table_id,p_class_row,p_url_get_html, p_max_row_add){
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
            if (v_stt => v_max_stt) {
                v_max_stt = v_stt;
            }
        }
        var v_url_get_html = p_url_get_html+'/'+(parseInt(v_max_stt)+1);
        $.get(v_url_get_html, function(data) {
            $('#'+p_table_id).append(data);
            chay_javascript_tu_ket_qua_ajax(data);
        });
    }
}
// 16-06-2021 End DanNC function add row new table													

// 16-07-2021 DanNC begin gan bai pr DFP
function chon_bai_viet_gan_bai_pr_dfp(p_forms){
    var v_record_count = p_forms.hdn_record_count.value*1;
    var v_danh_sach_id_bai_viet ='';
    for(var i = 0; i < v_record_count; i++){
        var p_check_obj = eval("p_forms.chk_item_id"+i);
        if(p_check_obj && p_check_obj.checked == true){
           v_danh_sach_id_bai_viet+=(v_danh_sach_id_bai_viet=='')? p_check_obj.value:','+p_check_obj.value;
        }
    }
	if(v_danh_sach_id_bai_viet=='') {
		alert('Chưa có đối tượng nào được chọn!');
        return;
	}
    var v_old_id_list = window.opener.frm_update_item.hdn_danh_sach_id.value;
    var v_hdn_id_cau_hinh = window.opener.frm_update_item.hdn_id_cau_hinh.value;
    v_danh_sach_id_bai_viet = v_old_id_list!=''? v_old_id_list+','+v_danh_sach_id_bai_viet: v_danh_sach_id_bai_viet;
    var v_url = CONFIG.BASE_URL+'ajax/quan_tri_bai_pr_dfp/dsp_bai_pr_gan_vao_bai_dfp/'+v_danh_sach_id_bai_viet+'/0/'+v_hdn_id_cau_hinh;
    window.opener.AjaxAction('div_danh_sach_bai_viet', v_url);
    window.close();
}

function delete_list_pr_link_khac_hang_dfp(btnObj){
    var tableObj = btnObj.closest('.container_bai_pr_link_khac_hang');
    if (!tableObj){
        return;
    }

    if(!confirm('Nếu bạn xóa tin bài này thì toàn bộ số liệu pageview của tin bài này trên thongke99 CŨNG BỊ XÓA THEO, Bạn có chắc chắn muốn xóa không?')){
        return;
    }

    var table_stt = tableObj.id.replace('container_bai_pr_link_khac_hang', '');
    if (document.getElementById('chk_pr_lien_quan_kh'+table_stt) && document.getElementById('chk_pr_lien_quan_kh'+table_stt).checked){
        /* Begin: 6-6-2019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
        if(document.getElementById('hdn_pk_cau_hinh_bai_pr_bai_viet_'+table_stt)){
            // Thực hiện 1 cấu hình pr news
            v_url = CONFIG.BASE_URL +'ajax/cau_hinh_bai_pr_lien_quan/act_delete_cau_hinh_bai_pr_lien_quan_theo_id/?p_id_cau_hinh_bai_viet='+document.getElementById('hdn_pk_cau_hinh_bai_pr_bai_viet_'+table_stt).value + '&p_id_cau_hinh='+document.getElementById('hdn_fk_cau_hinh_bai_pr_'+table_stt).value;
            frm_submit(frm_update_item, v_url, 'frm_submit');
        }
        /* End: 6-6-2019 TuyenNT toi_uu_co_che_ghi_nhan_so_lieu_bai_pr_box_tin_pr_nhan_hang */
    }

    tableObj.remove();
}
// 16-07-2021 DanNC begin gan bai pr DFP
function livescore_iframe_load(iframe_id){
    if (!document.getElementById(iframe_id)){
        return false;
    }

    if (!document.getElementById(iframe_id).src){
        var src = document.getElementById(iframe_id).getAttribute('data-src');
        if (typeof(src) == 'undefined' || src == ''){
            return false;
        }

        document.getElementById(iframe_id).src = src;

        // Lắng nghe sự kiện message từ ocm livescore trả về
        if (window.addEventListener){
            addEventListener("message", livescore_process_mesage, false)
        // Xử lý đối với ie6 trở xuống
    } else if (window.attachEvent) {
            attachEvent("onmessage", livescore_process_mesage)
        }
    }

    return true;
}

function livescore_process_mesage(event){
    try {
        if (event.origin == event.origin.replace('livescore.24h.com.vn', '')) {
            return;// chỉ xử lý các mesage từ livescore
        }

        if (!event.data){
            return;
        }

        var event_data = JSON.parse(event.data);

        if (!event_data.livescore_data){
            return;
        }

        var livescore_data = event_data.livescore_data;
        var link_type = typeof(livescore_data.link_type) == 'string' ? livescore_data.link_type.trim() : '';
        var link_type_name = typeof(livescore_data.link_type_name) == 'string' ? livescore_data.link_type_name.trim() : '';
        var match_id = typeof(livescore_data.match_id) == 'string' ? livescore_data.match_id.trim() : '';
        var match_info = typeof(livescore_data.match_info) == 'string' ? livescore_data.match_info.trim() : '';
        var change_src = typeof(livescore_data.change_src) == 'string' ? livescore_data.change_src.trim() : '';

        // begin 24/03/2022 duclt bai_viet_theo_nhip_tran_dau
        var match_name_a = typeof(livescore_data.match_data.c_team_a_name) == 'string' ? livescore_data.match_data.c_team_a_name.trim() : '';
        var match_name_b = typeof(livescore_data.match_data.c_team_b_name) == 'string' ? livescore_data.match_data.c_team_b_name.trim() : '';
        var match_name = '';
        if(match_name_a != '' && match_name_b != ''){
            match_name = match_name_a + ' - ' + match_name_b;
        }
        // end 24/03/2022 duct bai_viet_theo_nhip_tran_dau

        if (document.getElementById('txt_livescore_link_type')) {
            document.getElementById('txt_livescore_link_type').value = link_type;
        }

        if (document.getElementById('txt_livescore_link_type_name')) {
            document.getElementById('txt_livescore_link_type_name').value = link_type_name;
        }

        if (document.getElementById('txt_livescore_match_id') && change_src == 'match_id') {
            document.getElementById('txt_livescore_match_id').value = match_id;
        }

        if (document.getElementById('txta_livescore_match_info')) {
            document.getElementById('txta_livescore_match_info').value = match_info;
        }

        if (document.getElementById('div_livescore_match_info') && change_src == 'match_id') {
            document.getElementById('div_livescore_match_info').innerHTML = match_info;
        }

        // begin 24/03/2022 duclt bai_viet_theo_nhip_tran_dau
        if (document.getElementById('txt_livescore_match_name') && change_src == 'match_id') {
            document.getElementById('txt_livescore_match_name').value = match_name;
        }
        // end 24/03/2022 duclt bai_viet_theo_nhip_tran_dau
    } catch(err) {
        console.log(err);
    }
}
function ap_dung_dong_bo() {
    var input_hop_dong = document.getElementById("input_hop_dong").value;
    var input_improgression = document.getElementById("input_improgression").value;
    var input_click = document.getElementById("input_click").value;
    var list = document.getElementsByClassName('txt_so_hop_dong');
    var list_imp = document.getElementsByClassName('txt_link_tracking_imp');
    var list_click = document.getElementsByClassName('txt_link_tracking_click');
    var x, y, z;
    for (x = 0; x < list.length; ++x) {
        list[x].value=input_hop_dong;
    }
    for (y = 0; y < list_imp.length; ++y) {
        list_imp[y].value=input_improgression;
    }
    for (z = 0; z < list_click.length; ++z) {
        list_click[z].value=input_click;
    }
}
// 24-09-2021 DanNC begin bổ sung tìm kiếm bảng giá xe
function search_car(p_id_input, p_arr) {
    var hang_xe = document.getElementById(p_id_input);
    var v_arr_car = JSON.parse(p_arr);
    if (hang_xe !== null && hang_xe !== '' && hang_xe !== 'undefined') {
        var v_id = hang_xe.value;
        if (v_id > 0) {
            v_html = '';
            for(var i = 0; i < v_arr_car.length; i++){
                if (v_arr_car[i]['fk_gia_tri_lien_quan'] == v_id) {
                    var v_id_car = v_arr_car[i]['pk_gia_tri'];
                    v_html += '<div id="chk_phien_ban_xe_K3"><label><input type="checkbox" name="chk_phien_ban_xe[]" id="chk_phien_ban_xe['+[i]+']" value="'+v_arr_car[i]['pk_gia_tri']+'" data-label="'+v_arr_car[i]['c_ten']+'"> '+v_arr_car[i]['c_ten']+'</label></div>'
                }
            }
            document.getElementById("box_car").innerHTML = v_html;
        }
    }
}

function chon_checkbox_sau_khi_auto_complete_car(checkbox_id,hdn_id,hdn_tong,txt_input,is_submit,form_id, p_attr_sub,trigger_change){
    v_attr_sub = (typeof(p_attr_sub)==='undefined')? '':p_attr_sub;
    var id_duoc_chon = document.getElementById(hdn_id).value;
    var i=0;
    var so_tinh = document.getElementById(hdn_tong).value;
    v_check_id = false;
    v_check_so_tinh = false;
    if (id_duoc_chon !== null && id_duoc_chon !== '' && id_duoc_chon !== 'undefined') {
        v_check_data = true;
    }
    if (so_tinh !== null && so_tinh !== '' && so_tinh !== 'undefined') {
        v_check_so_tinh = true;
    }
    if (v_check_data && v_check_so_tinh) {
        for(i=0;i<so_tinh;i++){
            var p_check_obj = document.getElementById(checkbox_id+'['+i+']');
            if (p_check_obj !== null && p_check_obj !== '' && p_check_obj !== 'undefined') {
                if (p_check_obj.value == id_duoc_chon) {
                    p_check_obj.checked = true;
                    p_check_obj.focus();
                    document.getElementById(txt_input).value = '';
                    document.getElementById(txt_input).focus();
                    if(v_attr_sub!='') {
                        chon_chuyen_muc_cap_2(p_check_obj, v_attr_sub);	// goi ham tu dong check chuyen muc cap 2
                    }
                    if(is_submit == 1) {
                        eval("document."+form_id+".submit()");
                    }
                    if (trigger_change == 1) {
                        p_check_obj.onchange();
                    }
                }
            }
        }
        return false;
    }
}

function check_value(p_id,p_box) {
    var v_id = document.getElementById(p_id);
    var v_box = document.getElementById(p_box);
    if (v_id !== null && v_id !== '' && v_id !== 'undefined') {
        if (v_id.checked == true) {
            if(p_id == 'chk_bang_gia_o_to'){
                document.getElementById('chk_bang_gia_xe_dap').checked = false;
                document.getElementById('total_box_bang_gia_xe_dap').style.display = "none";
                document.getElementById('chk_bang_gia_hitech').checked = false;
                document.getElementById('total_box_bang_gia_hitech').style.display = "none";
            }
            if(p_id == 'chk_bang_gia_xe_dap'){
                document.getElementById('chk_bang_gia_o_to').checked = false;
                document.getElementById('total_box_bang_gia_xe').style.display = "none";
                document.getElementById('chk_bang_gia_hitech').checked = false;
                document.getElementById('total_box_bang_gia_hitech').style.display = "none";
            }
            if(p_id == 'chk_bang_gia_hitech'){
                document.getElementById('chk_bang_gia_xe_dap').checked = false;
                document.getElementById('total_box_bang_gia_xe_dap').style.display = "none";
                document.getElementById('chk_bang_gia_o_to').checked = false;
                document.getElementById('total_box_bang_gia_xe').style.display = "none";
            }
            if (v_box !== null && v_box !== '' && v_box !== 'undefined') {
                v_box.style.display = "flex";
            }
        } else {
            if(p_id == 'chk_bang_gia_xe_dap'){
                var v_arr_box_car = document.getElementById('box_bike');
            }else if(p_id == 'chk_bang_gia_hitech'){
                var v_arr_box_car = document.getElementById('box_hitech');
            }else{
                var v_arr_box_car = document.getElementById('box_car');
            }
            var inputs = v_arr_box_car.getElementsByTagName('input');
            if (inputs !== null && inputs !== '' && inputs !== 'undefined') {
               for(var i = 0; i < inputs.length; i++){
                    inputs[i].checked = false;
                } 
            }
            if (v_box !== null && v_box !== '' && v_box !== 'undefined') {
                v_box.style.display = "none";
            }
        }
    }
}

function copy_code_video(){
  var textarea = document.getElementById("textarea");
  textarea.select();
  document.execCommand("copy");
}
function config_player_load_cate_event_by_type(obj){
    if(obj.value == 1){
        document.getElementById('config_player_cate').style.display = "";
        document.getElementById('config_player_event').style.display = "none";
    }else{
        document.getElementById('config_player_cate').style.display = "none";
        document.getElementById('config_player_event').style.display = "";
    }
}															  
/**
 * Function:  Hàm để check dung lượng ảnh gif trước khi upload
 * param: obj: đối tượng ảnh gif
 * param: p_type_img: loại ảnh cần check: gif,jpg,png....
 **/
var v_tong_dung_luong_upload = 0;
var v_arr_input_upload = [];
function check_dung_luong_video_truoc_khi_upload(obj){
    // lấy đường dẫn video
    if (!obj.value == ""){
        if(obj.files[0] && obj.files[0].size >0){
            v_arr_input_upload[v_arr_input_upload.length] = obj.id;
            var vdSize=obj.files[0].size;
            v_tong_dung_luong_upload = vdSize+v_tong_dung_luong_upload;
            // Nếu kích cỡ ảnh lớn hơn max thì báo lỗi luôn
            if(v_tong_dung_luong_upload >v_max_size_video){
                alert('Tổng dung lượng video upload vượt quá '+Math.ceil(v_max_size_video/(1024*1024))+'MB');
                if(v_arr_input_upload.length > 0){
                    for(i=0;i<v_arr_input_upload.length;i++){
                        document.getElementById(v_arr_input_upload[i]).value= "";
                    }
                }
                v_tong_dung_luong_upload = 0;
                return false;
            }
        }
        return true;
    }
}   
/*
 * Hàm hiện thị các thông tin file ảnh upload trang album
 */
function displayFileUploadInfo_album(o, p_target_id, obj)
{
	var nFiles = o.length;
	var str = '';
    var max_width = 650;
    if(document.getElementById('width_image_album')){
        max_width = document.getElementById('width_image_album').value;
    }
	for ( var i=0; i<nFiles; i++) {
        var name_input = 'image_album_'+i;
        var dau_nhay = "'";
		str += '<div class="line-dot" style="display: flex;">';
            str += '<div style="width:400px; float: left;">File <b>'+o[i]['name']+'</b>: '+(Math.round(o[i]['size']/1024*100)/100)+'KB</div>';
            str += '<div>';
                str += '<a id="icon_crop_'+name_input+'" href="javascript:;" onclick="crop_image(document.frmUpload, '+dau_nhay+base_url_domain+'ocm/crop_image/index/'+name_input+'/crop_'+name_input+'/hdn_'+name_input+'/0/0?free_size=1&max_width='+max_width+'&crop_image_album=1'+dau_nhay+','+dau_nhay+'new_window'+dau_nhay+')";>';
                    str += '<img src="'+base_url_domain+'ocm/images/image-crop-icon.png" align="absmiddle" width="16" height="16" />';
                str += '</a>';
            str += '</div>';
            str += '<input type="hidden" id="'+name_input+'" name="'+name_input+'" value="">';
            str += '<input type="hidden" id="hdn_'+name_input+'" name="hdn_'+name_input+'" value="">';
            str += '<input type="hidden" id="crop_'+name_input+'" name="crop_'+name_input+'" value="" data-old="" class="hidden-crop" />';
		str += '</div>';
	}
    // set lại giá trị số lượng ảnh chọn vào input
	document.getElementById('number_count_image').value = nFiles;
	document.getElementById(p_target_id).innerHTML = str;
}
/*
 * hàm set value width ảnh album
 */
function set_value_width_image_album(width){
    if(document.getElementById('width_image_album')){
        document.getElementById('width_image_album').value = width;
    }
}

/*
 * ham thuc hien auto save log news
 */
function auto_save_log_album(){
    v_url = CONFIG.BASE_URL+'ajax/album/act_auto_save_log_album/';
    frm_submit(document.frm_update_album, v_url, 'frm_submit');
}
function frm_submit_image_gif_video(p_stt_video){
    var element = document.getElementById("loadding_page");
    element.classList.remove("none_load_page");
    element.classList.add("show_load_page");
    v_url = CONFIG.BASE_URL+'/upload_video/act_get_image_gif_video/'+p_stt_video;
    document.forms['frm_Upload_image_gif_video'].action = v_url;
    document.forms['frm_Upload_image_gif_video'].submit();
}
/*
 * Hàm xử lý kiểm tra check trùng tin các box trang chủ
 * @param
 *  p_news_id           ID bài viết
 *  p_special_box_id    ID box
 */
function sb_kiem_tra_check_trung_du_lieu_cac_box(p_msg, p_news_id, p_special_box_id){
    // nếu có truyền message thì check confirm
    if(p_msg != ''){
        // kiểm tra confirm
        if (!confirm(p_msg)){
            return false;
        }
    }
    // ajax action
    var d = new Date();
    var n = d.getTime();
    var v_url = CONFIG.BASE_URL + 'ajax/special_box_news/act_kiem_tra_check_trung/?' + n;
    p_check_body = false;
    var text_show = '';
    $.ajax({
        type: "POST",
        url: v_url,
        data: {v_news_id: p_news_id, v_special_box_id: p_special_box_id},
        async: false,
        success: function (data) {
            if (data != '') {
                text_show += data;
            }
        }
    });
    // nếu có báo lỗi
    if (text_show !== '') {
        if(confirm(text_show)){
            return true;
        }else{
            return false;
        }
    }
    return true;
}
/*
 * Hàm hiện thị dữ liệu của xe máy xe đạp qua ajax
 */
function show_bike_ajax(p_id = 0) {
    var id_hang_xe_dap = document.getElementById('hdn_hang_xe_dap').value;
    var id_hang_xe_dap = (p_id > 0) ? p_id : id_hang_xe_dap;
    if (id_hang_xe_dap > 0) {
        var arr_seach_show = [];
        var arr_id_hang_xe_dap = [];
        let rates = document.getElementsByName('chk_hang_xe_dap[]');
        rates.forEach((chk_hang_xe_dap) => {
            if (chk_hang_xe_dap.checked) {
                arr_id_hang_xe_dap.push(chk_hang_xe_dap.value);
            }
        });
        var data = JSON.parse(v_arr_phien_ban_xe_dap);
        if (data != '') {
            for(i = 0; i < data.length; i++){
                if (data[i]['fk_gia_tri_lien_quan'] == id_hang_xe_dap && !arr_id_hang_xe_dap.includes(data[i]['fk_gia_tri_lien_quan'])) {
                    var id_remove = 'chk_phien_ban_xe_dap_'+data[i]['pk_gia_tri'];
                    var myobj = document.getElementById(id_remove);
                    myobj.remove();
                }
                if (arr_id_hang_xe_dap.includes(data[i]['fk_gia_tri_lien_quan']) && data[i]['fk_gia_tri_lien_quan'] == id_hang_xe_dap) {
                    var check_inner = document.getElementById('chk_phien_ban_xe_'+data[i]['pk_gia_tri']);
                    if (check_inner == '' || check_inner == null || check_inner == 'undefined') {
                        const div = document.createElement('div');
                        div.id = "chk_phien_ban_xe_dap_"+data[i]['pk_gia_tri'];
                        div.innerHTML = `<label><input type="checkbox" name="chk_phien_ban_xe_dap[]" id="chk_phien_ban_xe_dap[${[i]}]" value="${data[i]['pk_gia_tri']}" data-label="${data[i]['c_ten']}"> ${data[i]['c_ten']}</label>`;
                        arr_seach_show.push(ds_phien_ban_xe_dap[i]);
                        document.getElementById("box_bike").appendChild(div);
                    }
                }
            }
        }
        setAutoComplete('txt_phien_ban_xe_dap',arr_seach_show,'hdn_phien_ban_xe_dap',1,' chon_checkbox_sau_khi_auto_complete_car("chk_phien_ban_xe_dap","hdn_phien_ban_xe_dap","hdn_tong_so_phien_ban_xe_dap","txt_phien_ban_xe_dap",0)');
    }
}
/*
 * Hàm hiện thị dữ liệu của xe máy xe đạp qua ajax
 */
function show_hitech_ajax(p_id = 0) {
    var id_hang_hitech = document.getElementById('hdn_hang_hitech').value;
    var id_hang_hitech = (p_id > 0) ? p_id : id_hang_hitech;
    if (id_hang_hitech > 0) {
        var arr_seach_show = [];
        var arr_id_hang_hitech = [];
        let rates = document.getElementsByName('chk_hang_hitech[]');
        rates.forEach((chk_hang_hitech) => {
            if (chk_hang_hitech.checked) {
                arr_id_hang_hitech.push(chk_hang_hitech.value);
            }
        });
        var data = JSON.parse(v_arr_phien_ban_hitech);
        if (data != '') {
            for(i = 0; i < data.length; i++){
                if (data[i]['fk_gia_tri_lien_quan'] == id_hang_hitech && !arr_id_hang_hitech.includes(data[i]['fk_gia_tri_lien_quan'])) {
                    var id_remove = 'chk_phien_ban_hitech_'+data[i]['pk_gia_tri'];
                    var myobj = document.getElementById(id_remove);
                    myobj.remove();
                }
                if (arr_id_hang_hitech.includes(data[i]['fk_gia_tri_lien_quan']) && data[i]['fk_gia_tri_lien_quan'] == id_hang_hitech) {
                    var check_inner = document.getElementById('chk_phien_ban_hitech_'+data[i]['pk_gia_tri']);
                    if (check_inner == '' || check_inner == null || check_inner == 'undefined') {
                        const div = document.createElement('div');
                        div.id = "chk_phien_ban_hitech_"+data[i]['pk_gia_tri'];
                        div.innerHTML = `<label><input type="checkbox" name="chk_phien_ban_hitech[]" id="chk_phien_ban_hitech[${[i]}]" value="${data[i]['pk_gia_tri']}" data-label="${data[i]['c_ten']}"> ${data[i]['c_ten']}</label>`;
                        arr_seach_show.push(ds_phien_ban_hitech[i]);
                        document.getElementById("box_hitech").appendChild(div);
                    }
                }
            }
        }
        setAutoComplete('txt_phien_ban_hitech',arr_seach_show,'hdn_phien_ban_hitech',1,' chon_checkbox_sau_khi_auto_complete_car("chk_phien_ban_hitech","hdn_phien_ban_hitech","hdn_tong_so_phien_ban_hitech","txt_phien_ban_hitech",0)');
    }
}