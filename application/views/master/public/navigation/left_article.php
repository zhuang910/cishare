<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-13
 * Time: 下午4:26
 */
?>
<?php if(!empty($_SESSION['power']) && in_array(md5('set_in_article'), $_SESSION['power'])){?>
<li class="<?=!empty($uri3) &&$uri4!='part_class' && in_array($uri3, array('category','visa','activity','special'))?'active open':''?>  hsub">
	<a class="dropdown-toggle" href="#">
		<i class="menu-icon fa fa-users"></i>
		<span class="menu-text"> 文章管理 </span>
		<b class="arrow fa fa-angle-down"></b>
	</a>
	<b class="arrow"></b>
	<ul class="submenu nav-hide" class="submenu nav-hide" <?=!empty($uri3) && in_array($uri3, array())?'style="display: block;"':''?>>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/category'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'category'?'active':''?>">
			<a href="/master/category/category">
				<i class="menu-icon fa fa-caret-right"></i>
				分类管理
			</a>
		</li>
		<?php }?>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/student/visa'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'visa'?'active':''?>">
			<a href="/master/student/visa">
				<i class="menu-icon fa fa-caret-right"></i>
				文章管理
			</a>
		</li>
		<?php }?>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/student/activity'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'activity'?'active':''?>">
			<a href="/master/student/activity">
				<i class="menu-icon fa fa-caret-right"></i>
				评论管理
			</a>
		</li>
		<?php }?>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/special'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'special'?'active':''?>">
			<a href="/master/special/special">
				<i class="menu-icon fa fa-caret-right"></i>
				专题管理
			</a>
		</li>
		<?php }?>  
	</ul>
</li>
<?php }?>

