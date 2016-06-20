<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-13
 * Time: 下午3:42
 */
?>
<?php if(!empty($_SESSION['power']) && in_array(md5('set_authority'), $_SESSION['power'])){?>
<li class="<?=!empty($uri3) && $uri2=='authority'&&in_array($uri3, array('admin','teacher','agency','group','log','society'))?'active open':''?>  hsub">
	
	<a class="dropdown-toggle" href="#">
		<i class="menu-icon fa fa-unlock-alt"></i>
		<span class="menu-text"> 权限管理 </span>
		<b class="arrow fa fa-angle-down"></b>
	</a>
	
	<b class="arrow"></b>
	<ul class="submenu nav-hide" <?=!empty($uri3)&&$uri2=='authority' && in_array($uri3, array('admin','teacher','agency','society','group','log'))?'style="display: block;"':''?>>
	<?php if(!empty($_SESSION['power']) && in_array(md5('col_username'), $_SESSION['power'])){?>
		<li class="<?=!empty($uri3) && in_array($uri3, array('admin','teacher','agency','society'))?'active open':''?>  hsub">
			<a class="dropdown-toggle" href="#"><i class="menu-icon fa fa-caret-right"></i>
				帐号管理
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu nav-hide" class="submenu nav-hide" <?=!empty($uri3)&&$uri2=='authority' && in_array($uri3, array('admin','teacher','agency','society'))?'style="display: block;"':''?>>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/authority/admin'), $_SESSION['power'])){?>
				<li class="<?=$uri3 == 'admin'?'active':''?>">
					<a href="/admin/authority/admin/index"><i class="menu-icon fa fa-caret-right"></i>
						管理员帐号
					</a>
				</li>
			<?php }?>
			</ul>
		</li>
		<?php }?>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/authority/group'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'group'?'active':''?>">
			<a href="/admin/authority/group"><i class="menu-icon fa fa-caret-right"></i>
				权限管理
			</a>
		</li>
		<?php }?>
		<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/authority/log'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'log'?'active':''?>">
			<a href="/admin/authority/log"><i class="menu-icon fa fa-caret-right"></i>
				日志管理
			</a>
		</li>
	<?php }?>
	</ul>
</li>
<?php }?>