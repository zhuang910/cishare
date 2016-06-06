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
<a href="javascript:swfupload('image_uploadfile','image','文件上传',0,3,'jpeg,jpg,png,gif',3,0,yesdo,nodo)">
<img id="image_pic" src="./Public/Images/admin_upload_thumb.png">
</a>
<td width="90%" id="box_content">
					<div class="editor_box">
						<div style="display:none;" id="content_aid_box"></div>
						<textarea name="content" class=""  id="content"  boxid="content"   style="width:99%;height:300px;visibility:hidden;"></textarea>
						<script type="text/javascript" src="/Public/Kindeditor/kindeditor-min.js"></script>
						<script type="text/javascript">
							KindEditor.ready(function(K) {
							K.create('#content', {
							cssPath : '<?=RES?>master/js/Kindeditor/plugins/code/prettify.css',
							fileManagerJson:'/index.php?g=Admin&m=Attachment&a=index&isadmin=1&more=1&isthumb=0&file_limit=20&file_types=jpg,jpeg,gif,png,doc,docx,rar,zip,swf&file_size=5&moduleid=2&auth=5c94RB1CFulKqQ8GvVszG73wgtTbUUzq8tRAEDKfqbMh+XTNCNcv+jsAlJgyndVmh4HIN2xZaGAD/jeShMEN4BNOYY877gfBrKxguQeFfw&l=cn',
							editorid:'content',
							upImgUrl:'/index.php/master/core/uploads/indexs?isadmin=1&more=1&isthumb=0&file_limit=1&file_types=gif,jpg,jpeg,png,bmp&file_size=5&moduleid=2&auth=7896hxCu4CMyJ3kYGpwCKPaX4tuxQJOiOSs14Mhl1H+vjkKAmTMseLcxRk3hbz+vjXr7xazGPaZsIj0zvA&l=cn',
							upFlashUrl:'/index.php?g=Admin&m=Attachment&a=index&isadmin=1&more=1&isthumb=0&file_limit=1&file_types=swf,flv&file_size=5&moduleid=2&auth=b093Z1ELjJtmF9miB+FwUKBufzqdwnD+tWiFIi892GANaGE1QC7oYNz2IiFKRtEM&l=cn',
							upMediaUrl:'/index.php?g=Admin&m=Attachment&a=index&isadmin=1&more=1&isthumb=0&file_limit=1&file_types=mpg,wmv,avi,wma,mp3,mid,asf,rm,rmvb,wav,wma,mp4&file_size=5&moduleid=2&auth=3bf7zlIhXvd9kLwO2fH8uCKGxdkDT8HcejYRoN/CBxmL4J7g6LoVjgcjS21g3MYkUtnx8NJOUO9sJRiLnGg/oCPGFFjxIOk2IEYE2lYVta0eFA7wBaXbtw&l=cn',
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
				</td>
<!-- script -->
<!-- ace scripts -->
<script src="<?=RES?>master/js/upload.js"></script>
<script src="<?=RES?>master/js/ace-extra.min.js"></script>
<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
<script src="<?=RES?>/master/js/ace.min.js"></script>
<script type="text/javascript">
</script>

<!-- end script -->
<?php $this->load->view('master/public/footer');?>