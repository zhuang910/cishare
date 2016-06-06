<link rel="stylesheet" href="<?=RES?>master/css/sdyinc.css" />
<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">查看收据信息</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="widget-body">
				<div class="widget-body-inner" style="display: block;">
					<div class="widget-main" style="height: 230px">
					<?if(!empty($info['proof_number'])&&!empty($info['file_path'])){?>
						<table class="table table-striped table-bordered">
							<tr>
								<th>收据号:</th><th><?=!empty($info['proof_number'])?$info['proof_number']:''?></th>
							</tr>
						</table>
						<ul class="ace-thumbnails clearfix">
							<li>
								<div>
									<img width="191" src="<?=$info['file_path']?>">
									<div class="text">
										<div class="inner">
											<span>收据图片 </span>
											<br>
											<a title="点击预览" target="view_window" data-rel="colorbox" href="<?=$info['file_path']?>" class="cboxElement">
												<i class="ace-icon fa fa-search-plus"></i>
											</a>
											<a target="_blank" href="/download?path=<?=$info['file_path']?>&file=<?=$info['id']?>.jpg">
												<i title="下载" class="ace-icon fa fa-download"></i>
											</a>
										</div>
									</div>
								</div>
							</li>
						</ul>
						<?php }?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
