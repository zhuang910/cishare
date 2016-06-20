<?php 
	$uri3 = $this->uri->segment(3);
	$uri2 = $this->uri->segment(2);
	$uri4 = $this->uri->segment(4);
?>
<!-- #section:basics/sidebar -->
			<div id="sidebar" class="sidebar  responsive">
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
				</script>

				<ul class="nav nav-list">
					<li <?=$uri2.$uri3 == 'coreindex' ? 'class="active open hsub"' : ''?>>
						<a href="/admin/core/index">
							<i class="menu-icon fa fa-tachometer"></i>
							<span class="menu-text"> HOME </span>
						</a>

						<b class="arrow"></b>
					</li>
					<?php include_once "navigation/left_authority.php"; // 权限管理?>
					<?php include_once "navigation/left_article.php"; // 文章管理?>

				</ul><!-- /.nav-list -->

				<!-- #section:basics/sidebar.layout.minimize -->
				<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
					<i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
				</div>

				<!-- /section:basics/sidebar.layout.minimize -->
				<script type="text/javascript">
					try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
				</script>
			</div>