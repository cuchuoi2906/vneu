<?php 
$v_count_menu = sizeof($arr_menu_box);
$v_count_menu_item = sizeof($arr_menu_item); ?>
<td width="220" class="leftColumn" valign="top">
<?php
for ($i = 0;$i < $v_count_menu; $i++) {	
	$v_modul_id = $arr_menu_box[$i]['MENU_BOX_ID'];	
	$v_menu_list = '';
	for ($j = 0; $j < $v_count_menu_item; $j++) {
		if(isset($arr_menu_item[$j]) && $arr_menu_item[$j]['MENU_BOX_ID']==$v_modul_id){
		$v_is_permision = _check_permision($arr_menu_item[$j]['MENU_ITEMT_PERMISSION']);
		$v_admin_permision = _check_permision('ADMIN_OCM_24H');
			if($v_is_permision==1 || $v_admin_permision==1  || $arr_menu_item[$j]['MENU_PUBLIC'] ==1){ 
				$v_menu_list .="<li><a href=\"".$arr_menu_item[$j]['MENU_ITEMT_LINK']."\" >".$arr_menu_item[$j]['MENU_ITEMT_NAME']."</a></li>";
			}
		}
	}
	if ($v_menu_list != '') {
		?>
		<div class="menuBox-t"></div>
		<div class="menuBox-c">
			<div class="menuGroupTitle"><?php echo $arr_menu_box[$i]['MENU_NAME'];?></div>
			<div class="menuContent">
				<ul>
					<?php 
						echo $v_menu_list
					?>
				</ul>
			</div>
		</div>
		<div class="menuBox-b"></div>
		<?php 
	}
} ?>
</td>

