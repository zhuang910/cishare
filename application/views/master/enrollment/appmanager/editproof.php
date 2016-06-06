<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header no-padding">
			<div class="table-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">
					<span class="white">&times;</span>
				</button>
				查看凭据
			</div>
		</div>
		<div style="text-align:center;">
				<ul class="ace-thumbnails clearfix">
					<li>
						<div>
							<img width="590" src="<?=$img?>">
							<div class="text">
								<div class="inner">
									<span>收据图片 </span>
									<br>
									<a title="点击预览" target="view_window" data-rel="colorbox" href="<?=$img?>" class="cboxElement">
										<i class="ace-icon fa fa-search-plus"></i>
									</a>
									<a target="_blank" href="/download?path=<?=$img?>&file=<?=$id?>.jpg">
										<i title="下载" class="ace-icon fa fa-download"></i>
									</a>
								</div>
							</div>
						</div>
					</li>
				</ul>

		</div>
	</div>
</div>
