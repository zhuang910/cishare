<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-13
 * Time: 下午3:42
 */
?>
<?php if(!empty($_SESSION['power']) && in_array(md5('set_notice'), $_SESSION['power'])){?>
<li class="<?=!empty($uri3) && $uri2=='notice'&&in_array($uri3, array('notice'))?'active open':''?>  hsub">
	
	<a class="dropdown-toggle" href="#">
		<i class="menu-icon fa fa-bullhorn"></i>
		<span class="menu-text"> 通告管理 </span>
		<b class="arrow fa fa-angle-down"></b>
	</a>
	
	<b class="arrow"></b>
	<ul class="submenu nav-hide" <?=!empty($uri3)&&$uri2=='notice' && in_array($uri3, array('notice'))?'style="display: block;"':''?>>
	
		<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/notice'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'notice'?'active':''?>">
			<a href="/admin/notice/notice"><i class="menu-icon fa fa-caret-right"></i>
				通告管理
			</a>
		</li>
	<?php }?>
	</ul>
</li>
<?php }?>