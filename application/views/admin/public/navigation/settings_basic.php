<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-9-13
 * Time: 下午3:29
 */
?>
<nav class="navbar-menu pull-left collapse navbar-collapse" role="navigation">
	<!-- #section:basics/navbar.nav -->
	<ul class="nav navbar-nav">	
	<?php if(!empty($_SESSION['power']) && in_array(md5('set_basic'), $_SESSION['power'])){?>
		<li>
			<a data-toggle="dropdown" class="dropdown-toggle" href="#">
				<i class="ace-icon fa fa-cogs"></i>
				基础设置
				<i class="ace-icon fa fa-angle-down bigger-110"></i>
			</a>
			<ul class="dropdown-menu dropdown-light-blue dropdown-caret">
			<?php if(!empty($_SESSION['power']) && in_array(md5('set_web'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-globe bigger-110 blue"></i> 网站设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/cms/template'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/cms/template">模板设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/function_on_off'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/function_on_off">功能开关</a>
						</li>
					<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/cms/configuration'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/cms/configuration/sitelang">多语言设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/warning_line'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/warning_line">提醒线设置</a>
						</li>
						<?php }?>
					</ul>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('set_basic_2'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-cog bigger-110 blue"></i> 基本设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/inform/emailset'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/inform/emailset">邮件设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/message/messagedot'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/message/messagedot">通知设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/excel/educe'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/excel/educe">导出设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/print/printsetting'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/print/printsetting">打印设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/payconf'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/payconf">收费设置</a>
						</li>
						<?php }?>
					</ul>
				</li>
				<?php }?>
				<?php if(!empty($_SESSION['power']) && in_array(md5('set_apply'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-pencil-square bigger-110 blue"></i> 申请设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/degree'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/degree">学历设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/major'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/major/major">专业设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/apply_form'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/enrollment/apply_form">申请表设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/attachment'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/enrollment/attachment">附件设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/scholarship'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/scholarship">奖学金设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/enrollment/acc_camp'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/enrollment/acc_camp">住宿设置</a>
						</li>
					<?php }?>
						
					</ul>
				</li>
				<?php }?>
				<?php if(!empty($_SESSION['power']) && in_array(md5('set_in_school_2'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-university bigger-110 blue"></i> 在学设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/hour'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/hour">时间设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/faculty'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/faculty">院系设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/course'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/course">课程设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/teacher'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/teacher/teacher">老师设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/classroom'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/classroom">教室设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/score/itemsetting'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/score/itemsetting">考试设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/evaluate/settime'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/evaluate/settime">评教时间设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/books'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/books">书籍设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/admin/basic/attendance_notice_set'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/admin/basic/attendance_notice_set">考勤通知设置</a>
						</li>
					<?php }?>
					</ul>
				</li>
				<?php }?>
			</ul>
		</li>

		<li>
			<a href="/admin/enrollment/appmanager/index?label_id=1">
				<i class="ace-icon fa fa-pencil-square"></i>
				
				<span class="badge badge-warning" id="addapply">0</span>
				个待处理申请
			</a>
		</li>
		<?php }?>
	</ul>
	<!-- /section:basics/navbar.nav -->
</nav>