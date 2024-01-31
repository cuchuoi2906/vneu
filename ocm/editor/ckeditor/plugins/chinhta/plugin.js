CKEDITOR.plugins.add( 'chinhta', {
    icons: 'chinhta',
    init: function( editor ) {
        var v_label = 'Check chính tả';
        editor.addCommand( 'chinhtaDialog', {
            exec: function( editor ) {
                if(document.getElementById('txt_body') || document.getElementById('txt_title') || document.getElementById('txt_summary')){
                    var v_body = window.CKEDITOR.instances.txt_body.getData();
                    var v_title = document.getElementById('txt_title').value;
                    var txt_summary = document.getElementById('txt_summary').value;
                    if (v_body != '' || v_title != '' || txt_summary != '') {
                        var d = new Date();
                        var n = d.getTime();
                        var v_url = CONFIG.BASE_URL + 'ajax/news/dsp_check_chinhta/?' + n;
                        p_check_body = false;
                        var text_body = '';
                        $.ajax({
                            type: "POST",
                            url: v_url,
                            data: {
                                v_title: v_title,
                                txt_summary: txt_summary,
                                v_body: v_body
                            },
                            async: false,
                            success: function (data) {
                                if (data != '') {
                                    text_body += data;
                                    p_width = 500;
                                    p_height = 500;
                                    mo_cua_so_popup_overlay('',p_width, p_height);
                                    $('#_box_popup').html(data);
                                    if(top.document.getElementById('grammar_error_body_count')){
                                        var grammar_error_body_count = top.document.getElementById('grammar_error_body_count').value;
                                        if(grammar_error_body_count > 0){
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
                                            // xử lý ảnh
                                            reg_img = new RegExp(/<img.*?src="(.*?)"[^\>]+>/g);
                                            var v_matches_image = v_body.match(reg_img);
                                            if(Array.isArray(v_matches_image)){
                                                for(j=0;j<v_matches_image.length;j++){
                                                    v_body = v_body.replace(v_matches_image[j], '<!--img_replace_'+j+'-->');
                                                }
                                            }
                                            
                                            for(i=0;i<grammar_error_body_count;i++){
                                                if(top.document.getElementById('grammar_error_body_'+i)){
                                                    var body_text = '';
                                                    var body_text_replace = '';
                                                    body_text = top.document.getElementById('grammar_error_body_'+i).value;
                                                    body_text_replace = '<!--ma_replace_error--><span class="text_error">' + body_text + '</span><!--ma_replace_error-->';
                                                    v_body = v_body.replace(new RegExp("("+body_text+")", "g"), body_text_replace);
                                                }
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
                            }
                        });
                    }
                }
            }
        } );
        editor.ui.addButton( 'chinhta', {
            label: v_label,
            command: 'chinhtaDialog'
        });
        if (editor.addMenuItem) {
            editor.addMenuGroup('toolgroup');
            // Create a manu item
            editor.addMenuItem('chinhta', {
                label: v_label,
                icon: 'chinhta',
                command: 'chinhtaDialog',
                group: 'toolgroup'
            });
        }
        if (editor.contextMenu) {
            editor.contextMenu.addListener(function(element, selection) {
                return { chinhta: CKEDITOR.TRISTATE_ON };
            });
        }
    }
});