/*Begin Thangnb toi_uu_upload_anh_gif */
CKEDITOR.plugins.add( 'fakescript', {
    requires: ['fakeobjects'],
    init: function (editor) {
        //editor.addCss('.cke_script{background-color: #FC0; border-radius:10px; display: block;width:100%;height: 30px;}');
    },
    afterInit: function (editor) {
        var dataProcessor = editor.dataProcessor;
        var dataFilter = dataProcessor && dataProcessor.dataFilter;
        if (dataFilter) {
            dataFilter.addRules({
                elements: {
                    'script': function (element) {
                        if(element.children.length > 0 &&  element.children[0].value.indexOf('justifiedGallery') != '-1'){
                            var fake = editor.createFakeParserElement(element, 'cke_script_off', 'script', false);
                            return fake;
                        }else{
                            var fake = editor.createFakeParserElement(element, 'cke_script', 'script', false);
                            fake.attributes.alt = element.attributes.src;
                            fake.attributes.title = 'Vị trí code video, audio';
                            fake.attributes["data-cke-realelement"] = fake.attributes["data-cke-realelement"].replace(/%26amp%3B/gi, '%26'); //fix double encoding on ampersands in src
                            return fake;
                        }
                    },
                    'div' : function( element )
                    {
                        var attributes = element.attributes;
                        if (attributes.id) {
                            if (attributes.id.indexOf('non-gif-image-gif-') != '-1') {
                                var fake_gif = editor.createFakeParserElement(element, 'cke_anh_gif', 'img', false);
                                fake_gif.attributes.title = 'Vị trí ảnh GIF';
                                fake_gif.attributes.src = element.children[0].attributes.src;
                                return fake_gif;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('twentytwenty-') != '-1') {
                                var fake_anh_so_sanh_1 = editor.createFakeParserElement(element, 'cke_anh_gif', 'img', false);
                                fake_anh_so_sanh_1.attributes.title = 'Vị trí ảnh so sánh 1';
                                fake_anh_so_sanh_1.attributes.src = element.children[0].attributes.src;

                                return fake_anh_so_sanh_1;
                            }
                        }
                        //Begin 13-03-2017 : Thangnb flip_anh_so_sanh
                        if (attributes.class) {
                            if (attributes.class.indexOf('flip-container') != '-1') {
                                var fake_anh_so_sanh_1 = editor.createFakeParserElement(element, 'cke_anh_so_sanh_flip', 'img', false);
                                fake_anh_so_sanh_1.attributes.title = 'Vị trí ảnh so sánh Flip';
                                fake_anh_so_sanh_1.attributes.src = element.children[0].children[0].children[0].children[0].attributes.src;
                                return fake_anh_so_sanh_1;
                            }
                        }
                        //End 13-03-2017 : Thangnb flip_anh_so_sanh
                        /*Begin 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-quiz') != '-1') {
                                var fake_quiz = editor.createFakeParserElement(element, 'cke_quiz_container', 'img', false);
                                fake_quiz.attributes.title = 'Vị trí chèn quiz';
                                fake_quiz.attributes.src = '/ocm/images/quiz-avatar.png';
                                return fake_quiz;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-poll') != '-1') {
                                var fake_poll = editor.createFakeParserElement(element, 'cke_poll_container', 'img', false);
                                fake_poll.attributes.title = 'Vị trí chèn poll';
                                fake_poll.attributes.src = '/ocm/images/poll-avatar.png';
                                return fake_poll;
                            }
                        }
                        /*End 17-04-2018 trungcq XLCYCMHENG_29323_toi_uu_hien_thi_bai_quiz_poll_ocm*/
                        // begin 17-8-2018 BangND XLCYCMHENG_26387_bo_sung_chuc_nang_nhap_bai_magazine
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-magazine') != '-1') {
                                var fake_magazine = editor.createFakeParserElement(element, 'cke_magazine_container', 'img', false);
                                fake_magazine.attributes.title = 'Vị trí chèn magazine';
                                fake_magazine.attributes.src = '/ocm/images/magazine-avatar.png';
                                return fake_magazine;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-textlink-box') != '-1') {
                                var fake_textlink_box = editor.createFakeParserElement(element, 'cke_magazine_container', 'img', false);
                                fake_textlink_box.attributes.title = 'Vị trí chèn textlink box';
                                fake_textlink_box.attributes.src = '/ocm/images/textlink-box-avatar.jpg';
                                return fake_textlink_box;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-minigame') != '-1') {
                                var fake_minigame = editor.createFakeParserElement(element, 'cke_magazine_container', 'img', false);
                                fake_minigame.attributes.title = 'Vị trí chèn minigame';
                                fake_minigame.attributes.src = '/ocm/images/minigame.png';
                                return fake_minigame;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-hoidap') != '-1') {
                                var fake_hoidap = editor.createFakeParserElement(element, 'cke_hoidap_container', 'img', false);
                                fake_hoidap.attributes.title = 'Vị trí chèn hỏi đáp';
                                fake_hoidap.attributes.src = '/ocm/images/hoidap.png';
                                return fake_hoidap;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-chinhta') != '-1') {
                                var fake_hoidap = editor.createFakeParserElement(element, 'cke_chinhta_container', 'img', false);
                                fake_hoidap.attributes.title = 'Check chính tả';
                                fake_hoidap.attributes.src = '/ocm/images/chinhta.PNG';
                                return fake_hoidap;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-bangphuctap') != '-1') {
                                var fake_hoidap = editor.createFakeParserElement(element, 'cke_bangphuctap_container', 'img', false);
                                fake_hoidap.attributes.title = 'Đánh dấu bảng SCROLL - RESPONSIVE';
                                fake_hoidap.attributes.src = '/ocm/images/bangphuctap.png';
                                return fake_hoidap;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('data-embed-code-addon-pr') != '-1') {// XLCYCMHENG-38119 box add-on-pr
                                var fake_addon_pr = editor.createFakeParserElement(element, 'cke_addon_pr_container', 'img', false);
                                fake_addon_pr.attributes.title = 'Vị trí chèn box add-on sản phẩm pr';
                                fake_addon_pr.attributes.src = '/ocm/images/addon_pr.png';
                                return fake_addon_pr;
                            }
                        }
                        if (attributes.class) {
                            if (attributes.class.indexOf('justified-gallery') != '-1') {
                                var fake_chum_anh = editor.createFakeParserElement(element, 'cke_chum_anh', 'img', false);
                                fake_chum_anh.attributes.title = 'Vị trí chèn chùm ảnh';
                                //fake_chum_anh.attributes.src = "#";
                                return fake_chum_anh;
                            }
                        }
                    }
                }
            }, 5);

        }
    }
});
/*End Thangnb toi_uu_upload_anh_gif */
