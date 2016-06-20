<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-13
 * Time: 下午4:16
 */
?>
<?php if(!empty($_SESSION['power']) && in_array(md5('set_enrollment'), $_SESSION['power'])){?>


<li class="<?=!empty($uri3) && in_array($uri3, array('appmanager','register','pickup'))&& $uri4!='proof'?'active open':''?>  hsub">
	<a class="dropdown-toggle" href="#">
		<i class="menu-icon fa fa-user"></i>
		<span class="menu-text"> 招生管理 </span>
		<b class="arrow fa fa-angle-down"></b>
	</a>
	<b class="arrow"></b>
	<ul class="submenu nav-hide" <?=!empty($uri3) && in_array($uri3, array('register','pickup'))?'style="display: block;"':''?>>
	<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/register'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'register'?'active':''?>">
			<a href="/admin/enrollment/register">
				<i class="menu-icon fa fa-caret-right"></i>
				注册管理
			</a>
		</li>
	<?php }?>

	<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/apply'), $_SESSION['power'])){?>
		<li class="<?=!empty($uri3) && in_array($uri3, array('appmanager')) && $uri4!='proof'?'active open':''?> hsub">
			<a class="dropdown-toggle" href="#">
				<i class="menu-icon fa fa-caret-right"></i>
				<?php if($_SESSION['master_user_info']->groupid==1):?>
					申请处理
				<?php else:?>
					申请管理
				<?php endif;?>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<ul class="submenu nav-hide" <?=!empty($uri3) && in_array($uri3, array('appmanager'))?'style="display: block;"':''?>>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/appmanager'), $_SESSION['power'])){?>
				<li class="<?=$uri3 == 'appmanager'&&$uri4==''?'active':''?>">
					<a href="/admin/enrollment/appmanager"><i class="menu-icon fa fa-caret-right"></i>
						
						<?php if($_SESSION['master_user_info']->groupid==1):?>
							录取阶段
						<?php else:?>
							处理申请
						<?php endif;?>
					</a>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/appmanager/app_offer'), $_SESSION['power'])){?>
				<li class="<?=$uri4 == 'app_offer'?'active':''?>">
					<a href="/admin/enrollment/appmanager/app_offer?label_id=7&ispageoffer=-1"><i class="menu-icon fa fa-caret-right"></i>
						发送Offer阶段
					</a>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/appmanager/app_finish'), $_SESSION['power'])){?>
				<li class="<?=$uri4 == 'app_finish'?'active':''?>">
					<a href="/admin/enrollment/appmanager/app_finish"><i class="menu-icon fa fa-caret-right"></i>
						入学确认
					</a>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/appmanager/app_over'), $_SESSION['power'])){?>
				<li class="<?=$uri4 == 'app_over'?'active':''?>">
					<a href="/admin/enrollment/appmanager/app_over"><i class="menu-icon fa fa-caret-right"></i>
						申请结束
					</a>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/appmanager/app_allof'), $_SESSION['power'])){?>
				<li class="<?=$uri4 == 'app_allof'?'active':''?>">
					<a href="/admin/enrollment/appmanager/app_allof"><i class="menu-icon fa fa-caret-right"></i>
						所有申请
					</a>
				</li>
			<?php }?>
			</ul>
		</li>
	<?php }?>

	<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/pickup'), $_SESSION['power'])){?>
		<li class="<?=$uri3 == 'pickup'?'active':''?>">
			<a class="" href="/admin/enrollment/pickup">
				<i class="menu-icon fa fa-caret-right"></i>
				接机预定
			</a>
		</li>
	<?php }?>


	</ul>
</li>

<?php }?>
