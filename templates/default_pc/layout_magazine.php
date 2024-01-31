<?php
global $v_is_page_listing_magazine;
// nếu là trang listing magazine
if($v_is_page_listing_magazine == 1){
    echo '<main id="cated" class="list-mgz">';
}
	include _get_theme_path().'header.php'; 
// nếu không phải trang listing magazine
if($v_is_page_listing_magazine != 1){
?>
<div class="clF">
    <div class="contnr">
<?php
}
?>
        
        <?php echo $__MASTER_CONTENT__; ?>
<?php
// nếu không phải trang listing magazine
if($v_is_page_listing_magazine != 1){
?>
    </div>
</div>
<?php 
}
include _get_theme_path().'footer_magazine.php'; 

if($v_is_page_listing_magazine == 1){
    echo '</main>';
}
?>