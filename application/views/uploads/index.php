<?php if($is_edit !== 1){ ?>
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header"><button aria-hidden="true" data-dismiss="modal" class="bootbox-close-button close" type="button">×</button><h4 class="modal-title">上传文件管理</h4></div>
			<div class="modal-body">
			<?php $this->load->view('uploads/upload');?>
			</div>
			<div class="modal-footer">
				<button class="btn btn-primary uptrue" onclick="yesdo('<?=$img_id?>','<?=$input_name?>')" type="button">确定</button>
			</div>
		</div>
	</div>
</div>
<?php }else { ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Admin - <?=APPNAME?></title>

		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
		<?php $this->load->view('master/public/css_basic');?>
		<?php $this->load->view('master/public/js_basic')?>
	</head>
	<body class="no-skin" style="background-color:#FFF">
		<?php $this->load->view('uploads/upload');?>
	<script src="<?=RES?>master/js/ace-extra.min.js"></script>
	<script src="<?=RES?>/master/js/ace-elements.min.js"></script>
	<script src="<?=RES?>/master/js/ace.min.js"></script>
	</body>
</html>
<?php } ?>