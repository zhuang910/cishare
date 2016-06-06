<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-8-21
 * Time: 下午8:02
 */

$action_url = $action == 'add' ? 'insert' : 'update';
$action_title = $action == 'add' ? '添加' : '修改';
?>

<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="blue"><?=$action_title?>发件人设置</h4>
			</div>
			<form id="form_mail_config" class="form-horizontal no-margin" action="<?=$zjjp?>emailset/<?=$action_url?>" method="post" onsubmit="return save_mail_config()">
				<div class="modal-body">
					<div class="space-4"></div>
					<div class="form-group">
						<label for="smtp_host" class="col-sm-3 control-label no-padding-right"> 服务器地址 </label>
						<div class="col-sm-8">
							<input type="text" name="smtp_host" value="<?=!empty($info->smtp_host) ? $info->smtp_host : ''?>" class="col-xs-8" placeholder="服务器地址" id="smtp_host">
						</div>
					</div>
					<div class="form-group">
						<label for="smtp_user" class="col-sm-3 control-label no-padding-right"> 用户帐号 </label>
						<div class="col-sm-8">
							<input type="text" name="smtp_user" value="<?=!empty($info->smtp_user) ? $info->smtp_user : ''?>" class="col-xs-8" placeholder="用户帐号" id="smtp_user">
						</div>
					</div>
					<div class="form-group">
						<label for="smtp_pass" class="col-sm-3 control-label no-padding-right"> 密码 </label>
						<div class="col-sm-8">
							<input type="password" name="smtp_pass" class="col-xs-8" placeholder="密码" id="smtp_pass">
						</div>
					</div>
					<div class="form-group">
						<label for="smtp_port" class="col-sm-3 control-label no-padding-right"> 端口 </label>
						<div class="col-sm-8">
							<input type="text" name="smtp_port" value="<?=!empty($info->smtp_port) ? $info->smtp_port : ''?>" class="col-xs-8" placeholder="端口" id="smtp_port">
						</div>
					</div>
					<div class="form-group">
						<label for="mailtype" class="col-sm-3 control-label no-padding-right"> 邮件类型 </label>
						<div class="col-sm-8">
							<select name="mailtype" class="col-xs-8" id="mailtype">
								<option value="html" <?=!empty($info->mailtype) && $info->mailtype == 'html' ? 'selected' : ''?>>HTML</option>
								<option value="text" <?=!empty($info->mailtype) && $info->mailtype == 'text' ? 'selected' : ''?>>TEXT</option>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer center">
					<input type="hidden" name="id" value="<?=!empty($info->id) ? $info->id : ''?>">
					<input type="hidden" name="old_smtp_pass" value="<?=!empty($info->smtp_pass) ? $info->smtp_pass : ''?>">
					<button class="btn btn-sm btn-success" type="submit"><i class="ace-icon fa fa-check"></i>
						提交
					</button>
					<button data-dismiss="modal" class="btn btn-sm" type="button"><i class="ace-icon fa fa-times"></i>
						取消
					</button>
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		function save_mail_config(){
			var that_form =  $("#form_mail_config");
			var data = that_form.serialize();

			$.ajax({
				type:'post',
				url:that_form.attr('action'),
				data:data,
				dataType:'json',
				success:function(r){
					if(r.state == 1){
						pub_alert_success(r.info);
						setTimeout('window.location.reload()',800);
					}else{
						pub_alert_error(r.info);
					}
				}
			});
			return false;
		}
	</script>
</div>