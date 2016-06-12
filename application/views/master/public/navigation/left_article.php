<?php
/**
 * Created by enjoy_share.
 * User: zhuangqianlin
 * Mail: zql_0539@163.com
 * Date: 16-6-1
 */
?>
<?php if(!empty($_SESSION['power']) && in_array(md5('set_in_article'), $_SESSION['power'])){?>
<li class="<?=!empty($uri3) &&$uri4!='part_class' && in_array($uri3, array('article','category','special','reply'))?'active open':''?>  hsub">
	<a class="dropdown-toggle" href="#">
		<i class="menu-icon fa fa-users"></i>
		<span class="menu-text"> 文章管理 </span>
		<b class="arrow fa fa-angle-down"></b>
	</a>
	<b class="arrow"></b>
	<ul class="submenu nav-hide" class="submenu nav-hide" <?=!empty($uri3) && in_array($uri3, array('article','category','special','reply'))?'style="display: block;"':''?>>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/article'), $_SESSION['power'])){?>
			<li class="<?=$uri3 == 'article'?'active':''?>">
				<a href="/master/article/article">
					<i class="menu-icon fa fa-caret-right"></i>
					文章管理
				</a>
			</li>
		<?php }?>

		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/article/category'), $_SESSION['power'])){?>
			<li class="<?=$uri3 == 'category'?'active':''?>">
				<a href="/master/article/category">
					<i class="menu-icon fa fa-caret-right"></i>
					分类管理
				</a>
			</li>
		<?php }?>

		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/article/special'), $_SESSION['power'])){?>
			<li class="<?=$uri3 == 'special'?'active':''?>">
				<a href="/master/article/special">
					<i class="menu-icon fa fa-caret-right"></i>
					专题管理
				</a>
			</li>
		<?php }?>

		<?php if(!empty($_SESSION['power']) && in_array(md5('/master/article/reply'), $_SESSION['power'])){?>
			<li class="<?=$uri3 == 'reply'?'active':''?>">
				<a href="/master/article/reply">
					<i class="menu-icon fa fa-caret-right"></i>
					评论管理
				</a>
			</li>
		<?php }?>

	</ul>
</li>
<?php }?>

