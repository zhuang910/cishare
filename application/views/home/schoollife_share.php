<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>


<!--内容-->

<div class="width_1024 clearfix" id="evaluate_page">
    <h2 class="mt50 mb30"><span><?=lang('nav_35')?></span></h2>

	
	 <?php 
		if(!empty($events)){
			foreach($events as $k => $v){
			
	?>
	  <div class="blue-top teacher-china <?php if( ($k+1) %3 == 0){?>mg_r_10<?php }?>"> <a href="/<?=$puri?>/schoollife/share_detail?id=<?=$v['id']?>">
		<dl>
		  <dt><img src="<?=!empty($v['photo'])?$v['photo']:''?>" width="117" height="154"></dt>
		  <dd class="ft-18">
			<?=!empty($v['name'])?$v['name']:''?>
		  </dd>
		  <dd class="teacher-history">
			<?=!empty($v['info'])?$v['info']:''?>
		  </dd>
		  <dd><span class="about-school-main-news-see">
			<?=lang('look_detail')?>
			</span></dd>
		</dl>
		</a> </div>
	  <?php }}?>
	
	
</div>
<?php if($pagecount > 1){?>
<div class="width_1024 campuslife_viewmore" id="page">
	<a href="javascript:;" onclick="more(<?=$page?>)" class="view-more-img">
		<img src="<?=RES?>home/images/public/loading.gif" alt="" id="loading" style="display:none;"/><?=lang('click_more')?>
	</a>
</div>
<?php }?>
<script type="text/javascript">
	var pagecount = <?=$pagecount?>;
	function more(page){
		page ++;
		
		if(page < pagecount){
	
			var page_html = '<a href="javascript:;" onclick="more('+page+')" class="view-more-img"><img src="<?=RES?>home/images/public/loading.gif" id="loading" alt=""/><?=lang('click_more')?></a>';
			$('#page').html();
			$('#page').html(page_html);
			
		}else{
		
			$('#page').html('');
		}
		
		if(page <= pagecount){
			$.ajax({
				type:'GET',
				url:'/schoollife/share_get_data?page='+page,
				success:function(r){
					if(r.state == 1){
						$('#loading').show();
						$('#evaluate_page').append(r.data);
						$('#loading').hide();
					}else{
						var d = dialog({
							content: ''+r.info+''
						});
						d.show();
						setTimeout(function () {
							d.close().remove();
						}, 2000);
					}
				},
				dataType:'json'

			});
		}else{
			var d = dialog({
				content: '<?=lang('no_data')?>'
			});
			d.show();
			setTimeout(function () {
				d.close().remove();
			}, 2000);
		}
	}

</script>
<?php $this->load->view('public/footer.php')?>
