<?php
/**
* Cấu hình thông số module
* @author Tytv <Tytv@24h.com.vn>
*/
/**
* Cấu hình mã lỗi/thông báo của MySQL
* @author Tytv <Tytv@24h.com.vn>
*/
$arr_quiz['SQL_ERROR']="Lỗi câu lệnh SQL";
$arr_quiz['QUIZ_DANG_XUAT_BAN']="Quiz đang xuất bản, bạn không thể xóa!";
$arr_quiz['QUIZ_DA_DUOC_GAN_BAI_VIET']="Quiz đang được gán vào bài viết, bạn không thể xóa!";
$arr_quiz['ERROR_TRUNG_TEN_QUIZ']="Quiz đã có trên hệ thống vui lòng kiểm tra lại!";




/** Cấu hình mã nội dung box lọc dữ liệu */
$arr_quiz['DANH_SACH_LOAI_QUIZ']= array(
    '1'=>array(
        'c_name'=>'Quiz 1 - Trắc nghiệm kiến thức hoặc tính cách ( trắc nghiệm dài)',
        'c_value'=>1,
        'c_image'=>'images/quiz/template-quiz-1.png',
        'c_desc'=>' Nhiều câu hỏi, mỗi câu hỏi có 1 hoặc nhiều lựa chọn (câu trả lời). Bắt buộc phải trả lời hết để nhận được 1 kết quả cuối'),
    '2'=>array(
        'c_name'=>'Quiz 2 - Trắc nghiệm ngắn. Một câu hỏi với nhiều lựa chọn (câu trả lời)',
        'c_value'=>2,
        'c_image'=>'images/quiz/template-quiz-2.png',
        'c_desc'=>'Vị trí hiển thị kết quả nằm trên danh sách câu hỏi trắc nghiệm. Tại 1 thời điểm chỉ hiển thị 1 kết quả tương ứng cho 1 lựa chọn (câu trả lời)'),
    '3'=>array(
        'c_name'=>'Quiz 3 - Trắc nghiệm tìm điểm đúng sai, đố vui (1 P/a trả lời)',
        'c_value'=>3,
        'c_image'=>'images/quiz/template-quiz-3.png',
        'c_desc'=>' Nhiều câu hỏi, mỗi câu hỏi có 1 câu trả lời. Bấm vào câu hỏi nào sẽ hiển thị luôn câu trả lời tương ứng (vị trí hiển thị câu trả lời thay thế luôn cho câu hỏi)'),
    '4'=>array(
        'c_name'=>'Quiz 4 - Trắc nghiệm tìm điểm đúng sai, đố vui (Nhiều P/a trả lời)',
        'c_value'=>4,
        'c_image'=>'images/quiz/template-quiz-4.png',
        'c_desc'=>' Nhiều câu hỏi, mỗi câu hỏi có nhiều câu trả lời. Bấm vào câu trả lời nào sẽ hiển thị luôn câu trả lời tương ứng (vị trí hiển thị câu trả lời thay thế luôn cho câu hỏi)'),
    '5'=>array(
        'c_name'=>' Quiz 5 - Trắc nghiệm kiến thức qua hình ảnh',
        'c_value'=>5,
        'c_image'=>'images/quiz/template-quiz-5.png',
        'c_desc'=>' Nhiều câu hỏi, mỗi câu hỏi có 2 hoặc nhiều câu trả lời để lựa chọn. Bấm vào câu trả lời nào sẽ hiển thị luôn đáp án đúng của câu hỏi tương ứng đó.'),
    /* begin 29/11/2017 Tytv xay_dung_qiuz_6_24h(ocm) */
    '6'=>array(
        'c_name'=>' Quiz 6 - Trắc nghiệm phân trang',
        'c_value'=>6,
        'c_image'=>'images/quiz/template-quiz-5.png',
        'c_desc'=>' Mỗi câu hỏi nằm trên 1 trang. Bấm vào câu trả lời, trang sẽ mở trang mới với kết quả câu hỏi cũ và đưa nội dung câu hỏi mới.'),
    /* end 29/11/2017 Tytv xay_dung_qiuz_6_24h(ocm) */
);
 
$arr_quiz['kich_thuoc_anh'] = array(520,480);
// cấu hình các thông số dữ liệu 
$arr_quiz['validate_form'] = array(
    'max_ky_tu_quiz'=>300,
    'max_ky_tu_cau_hoi'=>1000,
    'max_ky_tu_cau_tra_loi'=>1000,
    'max_ky_tu_dap_an'=>1000,
    'max_ky_tu_ket_qua'=>1000,
    'max_anh_noi_dung_ket_qua'=>20,
    'min_cau_tra_loi_quiz4'=>2, // số câu trả lời tối thiểu ở bài quiz 4
    'min_cau_tra_loi_quiz5'=>2, // số câu trả lời tối thiểu ở bài quiz 5
    'min_cau_tra_loi_quiz6'=>2, // số câu trả lời tối thiểu ở bài quiz 5
    );
// cấu hình thông số ảnh upload
$arr_quiz['image_upload'] = array(
    'FORMAT_IMAGE'=>'jpg,png,gif', // dịnh dạng ảnh
    'KICH_THUOC_IMAGE'=>array(660,0), // dịnh dạng ảnh
    'MAX_IMAGE_SIZE'=>153600, // 150kb // kink check http://www.whatsabyte.com/P1/byteconverter.htm
    'RATIO_IMAGE'=>array(3,2), // tỷ lệ ảnh
);

$arr_quiz['v_arr_list_domain'] = array('http://static.24h.com.vn','http://image.24h.com.vn','http://anh.24h.com.vn','http://www.24h.com.vn/','image.24h.com.vn','http://www.24h.com.vn/','static.24h.com.vn','anh.24h.com.vn');

/**
* Cấu hình mã nội dung box lọc dữ liệu
* @author Tytv <tytv@24h.com.vn>
*/
$arr_quiz['ID_QUIZ']="Nhập id quiz";
$arr_quiz['TEN_QUIZ']="Nhập tên quiz";
$arr_quiz['ID_BAI_VIET']="Nhập id bài viết";
$arr_quiz['TEN_BAI_VIET']="Nhập tên bài viết";

$arr_quiz['MAX_TONG_SO_BAN_GHI_CAU_HOI']= 20;/* edit: Tytv - 27/10/2017 - nang_so_luong_cau_hoi_quizz */
$arr_quiz['MAX_TONG_SO_BAN_GHI_TRA_LOI']= 20;
$arr_quiz['MAX_TONG_SO_BAN_GHI_KET_QUA']= 10;


// Cấu hình ghi dữ liệu tới redis
$arr_quiz['WRITE_DATA_TO_REDIS'] = true;
// Cấu hình tên table loại danh mục: dùng để tạo key redis
$arr_quiz['KEY_VALUE_NAME'] = 'data_quiz_id_';
$arr_quiz['KEY_VALUE_NAME'] = 'data_quiz_id_';
$arr_quiz['TABLE_NAME'] = _CACHE_TABLE;

// thiết lập kích thước ảnh resize theo kích thước của từng loại quiz
$arr_quiz['IMAGE_RESIZE'] =  array(
    '1'=> array( // loại quiz 1
        '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),
        '4' => array( // 4 cột
            'width' => 157,
            'height' => 105,
            'folder' => 'quiz114x77'
        ),   
    ),
    '2'=> array( // loại quiz 2
         '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),
        '4' => array( // 4 cột
            'width' => 157,
            'height' => 105,
            'folder' => 'quiz114x77'
        ),   
    ),
    '3'=> array( // loại quiz 3
        '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),
    ),
    '4'=> array( // loại quiz 4
        '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),   
    ),
    '5'=> array( // loại quiz 5
        '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),
        '4' => array( // 4 cột
            'width' => 157,
            'height' => 105,
            'folder' => 'quiz114x77'
        ),   
    ),/* Begin 29/11/2017 Tytv xay_dung_qiuz_6_24h(ocm) */
    '6'=> array( // loại quiz 6
        '2' => array( // 2 cột
            'width' => 318,
            'height' => 212,
            'folder' => 'quiz240x160'
        ),
        '3' => array( // 3 cột
            'width' => 211,
            'height' => 141,
            'folder' => 'quiz158x106'
        ),
        '4' => array( // 4 cột
            'width' => 157,
            'height' => 105,
            'folder' => 'quiz114x77'
        ),   
    ),/* End 29/11/2017 Tytv xay_dung_qiuz_6_24h(ocm) */
);

