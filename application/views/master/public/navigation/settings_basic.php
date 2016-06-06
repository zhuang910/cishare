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
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/cms/template'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/cms/template">模板设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/function_on_off'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/function_on_off">功能开关</a>
						</li>
					<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/cms/configuration'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/cms/configuration/sitelang">多语言设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/warning_line'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/warning_line">提醒线设置</a>
						</li>
						<?php }?>
					</ul>
				</li>
			<?php }?>
			<?php if(!empty($_SESSION['power']) && in_array(md5('set_basic_2'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-cog bigger-110 blue"></i> 基本设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/inform/emailset'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/inform/emailset">邮件设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/message/messagedot'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/message/messagedot">通知设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/excel/educe'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/excel/educe">导出设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/print/printsetting'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/print/printsetting">打印设置</a>
						</li>
						<?php }?>
						<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/payconf'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/payconf">收费设置</a>
						</li>
						<?php }?>
					</ul>
				</li>
				<?php }?>
				<?php if(!empty($_SESSION['power']) && in_array(md5('set_apply'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-pencil-square bigger-110 blue"></i> 申请设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/degree'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/degree">学历设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/major'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/major/major">专业设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/enrollment/apply_form'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/enrollment/apply_form">申请表设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/enrollment/attachment'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/enrollment/attachment">附件设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/scholarship'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/scholarship">奖学金设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/enrollment/acc_camp'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/enrollment/acc_camp">住宿设置</a>
						</li>
					<?php }?>
						
					</ul>
				</li>
				<?php }?>
				<?php if(!empty($_SESSION['power']) && in_array(md5('set_in_school_2'), $_SESSION['power'])){?>
				<li class="dropdown-hover">
					<a class="clearfix" tabindex="-1" href="#"><i class="ace-icon fa fa-university bigger-110 blue"></i> 在学设置 <i class="ace-icon fa fa-angle-down bigger-110"></i></a>
					<ul class="dropdown-menu">
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/hour'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/hour">时间设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/faculty'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/faculty">院系设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/course'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/course">课程设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/teacher'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/teacher/teacher">老师设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/classroom'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/classroom">教室设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/score/itemsetting'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/score/itemsetting">考试设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/evaluate/settime'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/evaluate/settime">评教时间设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/books'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/books">书籍设置</a>
						</li>
					<?php }?>
					<?php if(!empty($_SESSION['power']) && in_array(md5('/master/basic/attendance_notice_set'), $_SESSION['power'])){?>
						<li>
							<a tabindex="-1" href="/master/basic/attendance_notice_set">考勤通知设置</a>
						</li>
					<?php }?>
					</ul>
				</li>
				<?php }?>
			</ul>
		</li>

		<li>
			<a href="/master/enrollment/appmanager/index?label_id=1">
				<i class="ace-icon fa fa-pencil-square"></i>
				
				<span class="badge badge-warning" id="addapply">0</span>
				个待处理申请
			</a>
		</li>
		<?php }?>
	</ul>
	<!-- /section:basics/navbar.nav -->
</nav>