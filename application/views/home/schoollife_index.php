<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>

<style type="text/css">
	
	/* controller */
#controller{height:30px;overflow:hidden; border-bottom:1px solid #e5e5e5;}
.width_1024dd{ text-align:center; height:30px;}
#controller span{cursor:pointer; padding:0 10px; text-align:center;}
#controller span a{height:30px; line-height:30px; font-size:12px; color:#333;}
#controller span a:hover{ color:#024ba0; text-decoration:none;}
#controller .jflowselected a{color:#fff;background-color:#c00;}
#controller .jflowselected a:hover{color:#fff;background-color:#000;}
#controller .flowprev,#controller .flownext{font:18px/50px arial;font-weight:800;width:65px;color:#c00;text-align:center;}
</style>
<!--banner-->
<div id="zyj_images">

</div>

<!--内容-->

<div class="width_1024 clearfix" id="evaluate_page">
    <h2 class="mt50 mb30"><span><?=lang('nav_30')?></span></h2>
	<?php 
		if(!empty($events)){
			foreach($events as $k => $v){
	?>
		<div class="blue-top campuslife <?=($k+1)%3 ==0?'mg_r_10':''?>">
			<a class="erjitongyong" href="/<?=$puri?>/schoollife/events_detail?id=<?=$v['id']?>">
				<img src="<?=!empty($v['image'])?$v['image']:''?>">
				<div class="campuslife_describe">
					<h5 class="mg-b-15"><?=!empty($v['title'])?$v['title']:''?></h5>
					<p class="ft-12-5"><?=!empty($v['description'])?$v['description']:''?></p>
					<span class="about-school-main-news-see"><?=lang('look_detail')?></span>
					<div class="campuslife_describe_time"><?=date('Y-m-d',$v['createtime'])?></div>
				</div>
			</a>
		</div>
	
	<?php }}?>
	
	
</div>
<div class="width_1024 campuslife_viewmore" id="page">
	<a href="javascript:;" onclick="more(<?=$page?>)" class="view-more-img">
		<img src="<?=RES?>home/images/public/loading.gif" alt="" id="loading" style="display:none;"/><?=lang('click_more')?>
	</a>
</div>

<script type="text/javascript">
$(function(){

	sel_fac(32);
});


	function sel_fac(columnid){
		$.ajax({
			url: '/schoollife/get_fac_images?columnid='+columnid,
			type: 'GET',
			dataType: 'json',
		})
		.done(function(r) {
			if(r.state == 1){
				$('#zyj_images').html('');
				$('#zyj_images').html(r.data);
			}else{
				 var d = dialog({
						content: r.info
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
			}
		})
		.fail(function() {
			var d = dialog({
						content: r.info
					});
					d.show();
					setTimeout(function () {
						d.close().remove();
					}, 2000);
		})
		
	}

</script>
<script type="text/javascript">
	var pagecount = <?=$pagecount?>;
	function more(page){
		page ++;
		
		if(page < pagecount){
	
			var page_html = '<a href="javascript:;" onclick="more('+page+')" class="view-more-img"><img src="<?=RES?>home/images/public/loading.gif" alt="" id="loading"/><?=lang('click_more')?></a>';
			$('#page').html();
			$('#page').html(page_html);
		}else{
			$('#page').html('');
		}
		
		if(page <= pagecount){
			$.ajax({
				type:'GET',
				url:'/schoollife/get_data?page='+page,
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
