<?php
/*
* Lay danh sach doi tuong thay doi du lieu theo dinh ky
*/
function fe_danh_sach_doi_tuong_thay_doi_du_lieu_theo_dinh_ky($p_key) {
    $sql = "call fe_danh_sach_doi_tuong_thay_doi_du_lieu_theo_dinh_ky('$p_key')";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
/**
 * Lay bai viet theo id
 * @param $p_news_id: id bai viet
 * @return array
 */
function fe_bai_viet_theo_id($p_news_id=0)
{
    $p_news_id =intval($p_news_id);
    if($p_news_id <=0){return $rs;}
    $sql = "call fe_bai_viet_theo_id($p_news_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/**
 * Lay bai viet theo id
 * @param $p_news_id: id bai viet
 * @return array
 */
function fe_chuyen_muc_theo_id($p_cate_id=0)
{
    $p_cate_id =intval($p_cate_id);
    if($p_cate_id <=0){return $rs;}
    $sql = "call fe_chuyen_muc_theo_id($p_cate_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
function fe_seo_chi_tiet_bai_viet_theo_id($p_news_id,$v_thiet_bi = 1){
    $sql = "call fe_seo_chi_tiet_bai_viet_theo_id($p_news_id, $v_thiet_bi)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}

/**
 * Lay magazine theo id
 * @param $p_magazine_id: id magazine
 * @return array
 */
function fe_magazine_theo_id($p_magazine_id,$p_get_key_value=1)
{
    $sql = "call fe_magazine_theo_id($p_magazine_id)";
    $rs = Gnud_Db_read_query($sql);
    return $rs[0];
}
/**
 * Ham lay danh sach chuyen muc theo dang  menu
 *  @return array[id_cat] = array("ID"=>id cat, "Name"=>"Ten chuyen muc");
 */
function fe_danh_sach_chuyen_muc(){
    $sql = "call fe_danh_sach_chuyen_muc()";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

/**
 * Lay danh sach bai viet theo chuyen muc
 * @param $p_cat_id: id the loai
		  $p_from_date: Lay bai xuat ban tu ngay
		  $p_from_date: Lay bai xuat ban den ngay
          $p_page : Trang can lay
          $p_number_items : So luong can lay
		  $p_get_key_value: 1-Co lay du lieu tu key-value; 0- Khong lay
 * @return array
 */
function fe_bai_viet_theo_chuyen_muc($p_cat_id, $p_page = 1, $p_number_items = SO_LUONG_BAI_VIET_TRONG_KEY_DATA)
{
    $sql = "call fe_bai_viet_theo_chuyen_muc($p_cat_id, $p_page, $p_number_items)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}

function fe_bai_viet_moi_nhat(
	$p_page
	,$p_number_items
){
    $sql = "call fe_bai_viet_moi_nhat($p_page, $p_number_items)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}
function fe_bai_viet_theo_khoang_thoi_gian_xuat_ban($p_ngay_xuat_ban_tu_ngay, $p_ngay_xuat_ban_den_ngay, $p_page=1, $p_number_items_per_page=30000){
    echo $sql = "CALL fe_bai_viet_theo_khoang_thoi_gian_xuat_ban('$p_ngay_xuat_ban_tu_ngay', '$p_ngay_xuat_ban_den_ngay', $p_page, $p_number_items_per_page)";
    $rs = Gnud_Db_read_query($sql);
    return $rs;
}