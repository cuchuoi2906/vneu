<?php 

/*
 * Lay danh sach doc gia
 */
function be_danh_sach_doc_gia(
	$p_name,
	$p_page,
	$p_num_per_page
){
    $sql = "call be_danh_sach_doc_gia(
        '$p_name',
        $p_page,
        $p_num_per_page
    )";
    //echo $sql;die;
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}