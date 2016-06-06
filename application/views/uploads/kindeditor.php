<?php
$breadcrumb=<<<EOD
<ul class="breadcrumb">
	<li>
		<i class="ace-icon fa fa-home home-icon"></i>
		<a href="javascript:;" onclick='jumpmaster()'>后台</a>
	</li>

	<li>
		<a href="#">成绩管理</a>
	</li>
	<li class="active">学生成绩</li>
</ul>
EOD;
?>		
<?php $this->load->view('master/public/header',array(
	'breadcrumb'=>$breadcrumb,
));?>
<style>
.swfupload {position: absolute;z-index: 1;}
.mainnav_title {display:none;}
</style>
<a href="javascript:;" onclick="pub_alert_html('/master/core/uploads/index?s=1')">12321</a>
<?php $this->load->view('master/public/js_css_kindeditor');?>

<!-- /section:settings.box -->
<div class="page-header">
	<h1>
		学生成绩
	</h1>
</div><!-- /.page-header -->
	<div class="editor_box">
		<div style="display:none;" id="content_aid_box"></div>
		<textarea name="content" class=""  id="content"  boxid="content"   style="width:99%;height:300px;visibility:hidden;"></textarea>
		<script type="text/javascript"> 
			KindEditor.ready(function(K) {
			K.create('#content', {
			cssPath : '<?=RES?>master/js/Kindeditor/plugins/code/prettify.css',
			fileManagerJson:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=20&file_types=jpg,jpeg,gif,png,doc,docx,rar,zip,swf&file_size=5&moduleid=2&is_edit=1',
			editorid:'content',
			upImgUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=gif,jpg,jpeg,png,bmp&file_size=5&moduleid=2&is_edit=1',
			upFlashUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=swf,flv&file_size=5&moduleid=2&is_edit=1',
			upMediaUrl:'/master/core/uploads/index?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=mpg,wmv,avi,wma,mp3,mid,asf,rm,rmvb,wav,wma,mp4&file_size=5&moduleid=2&is_edit=1',
			allowFileManager : true
			});
			});
			</script>
		<div  class='editor_bottom2'>
			<input type="checkbox" name="add_description" value="1" checked />
			是否截取内容
			<input type="text" name="description_length" value="200" style="width:24px;" size="3" />
			字符至内容摘要
			<input type="checkbox" name="auto_thumb" value="1" checked />
			是否获取内容第
			<input type="text" name="auto_thumb_no" value="1" size="1" />
			张图片作为标题图片
		</div>
	</div>
<!-- script -->
<input id="qwe" value="111">
<!-- ace scripts -->
<script src="<?=RES?>master/js/upload.js"></script>
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script type="text/javascript">
</script>


<!-- end script -->
<?php $this->load->view('master/public/footer');?>