<div id="pub_edit_bootbox" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="widget-header">
				<h5 class="widget-title">附件预览</h5>
				<div class="widget-toolbar">
					<a href="#" data-action="collapse" aria-hidden="true" data-dismiss="modal">
						<i class="1 ace-icon bigger-125 fa fa-remove"></i>
					</a>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<!-- PAGE CONTENT BEGINS -->
					<div>
						<ul class="ace-thumbnails clearfix">
						<?php if(!empty($data)){ foreach($data as $k => $v){ ?>
							<li>
								<div>
									<img width="191" src="<?=$v['thumbnailUrl']?>" />
									<div class="text">
										<div class="inner">
											<span><?=!empty($v['attachmentid']) && !empty($dataF[$v['attachmentid']])?$dataF[$v['attachmentid']]:''?></span>
											<br />
											<a href="<?=$v['thumbnailUrl']?>" data-rel="colorbox" title="点击预览">
												<i class="ace-icon fa fa-search-plus"></i>
											</a>
											<a href="<?=$v['url']?>" target="_blank">
												<i class="ace-icon fa fa-download" title="下载"></i>
											</a>
										</div>
									</div>
								</div>
							</li>
							<?php }}?>
						</ul>
					</div>
				</div>
		</div>
	</div>
</div>
<script src="<?=RES?>master/js/jquery.colorbox-min.js"></script>
<script type="text/javascript">
	jQuery(function($) {
		var $overflow = '';
		var colorbox_params = {
			rel: 'colorbox',
			reposition:true,
			scalePhotos:true,
			scrolling:false,
			previous:'<i class="ace-icon fa fa-arrow-left"></i>',
			next:'<i class="ace-icon fa fa-arrow-right"></i>',
			close:'&times;',
			current:'{current} of {total}',
			maxWidth:'100%',
			maxHeight:'100%',
			onOpen:function(){
				$overflow = document.body.style.overflow;
				document.body.style.overflow = 'hidden';
			},
			onClosed:function(){
				document.body.style.overflow = $overflow;
			},
			onComplete:function(){
				$.colorbox.resize();
			}
		};

		$('.ace-thumbnails [data-rel="colorbox"]').colorbox(colorbox_params);
		$("#cboxLoadingGraphic").append("<i class='ace-icon fa fa-spinner orange'></i>");//let's add a custom loading icon
	})
</script>