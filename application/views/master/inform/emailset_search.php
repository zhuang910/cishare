<?php
/**
 * Created by CUCAS TEAM.
 * User: JunJie Zhang
 * Mail: zhangjunjie@chiwest.cn
 * Date: 14-8-22
 * Time: 下午3:34
 */

?>

<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button data-dismiss="modal" class="close" type="button">×</button>
				<h4 class="blue">筛选</h4>
			</div>
				<div class="modal-body form-horizontal no-margin">
					<div class="space-4"></div>
					<div class="form-group">
						<label class="col-sm-3 control-label no-padding-right"> 关键字 </label>
						<div class="col-sm-8">
							<input type="text" class="col-xs-8" placeholder="关键字" id="keywords">
						</div>
					</div>
				</div>
				<div class="modal-footer center">
					<button class="btn btn-sm btn-success" data-dismiss="modal" type="submit" onclick="go_search()"><i class="ace-icon fa fa-check"></i>
						筛选
					</button>
				</div>
		</div>
	</div>
	<script type="text/javascript">
		function go_search(){
			var keywords = $("#keywords").val();

			jQuery(grid_selector).jqGrid('setGridParam', {
				page: 1,
				url:'<?=$zjjp?>emailset?keywords='+keywords
			}).trigger("reloadGrid");
		}
	</script>
</div>