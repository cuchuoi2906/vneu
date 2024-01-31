<?php
include _get_theme_path().'header.php';
?>
<div class="mWrp clF mrT10">
    <div class="contnr">
        <?php
        if(_is_trang_video($cat_id)){
            echo display_breadcrumb_video($row_cat);
        }
        ?>
        <div class="colLt" id="content"><?php echo $__MASTER_CONTENT__; ?></div>
        <div class="colRt"><?php include _get_theme_path().'right_column_trang_video.php'; ?></div>
    </div>
</div>
<div class="clF"></div>
<?php
include _get_theme_path().'footer.php';
?>


