<?php $this->load->view('public/header.php')?>
<?php $this->load->view('public/js_My97DatePicker')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<link href="<?=RES?>home/css/user.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<?php 
	$uri2 = $this->uri->segment(2);
?>
<div class="width_1024 clearfix mg-t-50">

	<div class="three-left-nav">
		<ul>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_introduce'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_introduce"><?=lang('nav_92')?></a>
		</li>
		<li <?=!empty($uri2) && $uri2 == 'accommodation_book'?'class="selected"':''?>>
			<a href="/<?=$puri?>/accommodation_book"><?=lang('nav_94')?></a>
		</li>
		
		
		</ul>
	</div>
	<div class="pickup-main">
	<div class="crumbs-nav mb30"><a href="/<?=$puri?>/"><?=lang('nav_1')?></a><i> / </i><a href="/<?=$puri?>/accommodation"><?=lang('nav_67')?></a><i> / </i><span><?=lang('nav_92')?></span></div>
		<div class="borderwtie">
			<div class="homelisbox">
			<div class="boxzq">
				
			<?php if(!empty($camp)){
				foreach($camp as $k => $v){
			?>
			
				
				<div class="forbox">
					<h3><?=!empty($v['name'])?$v['name']:''?></h3>
					<p><?=!empty($v['info'])?$v['info']:''?></p>
					<dl>
					<?php if(!empty($v['build'])){
						foreach($v['build'] as $bk => $bv){
					?>
						<dd><span>&gt;</span><a href="/<?=$puri?>/accommodation_introduce/building?bulidingid=<?=$bv['id']?>"><?=$bv['name']?> </a></dd>
					<?php }}?>
				   
						
					  </dl>
				  </div>
			
			
			
			<?php }}?>
			</div>
			</div>
			
		</div>
	</div>
</div>
<script type="text/javascript">
	function select_building(){
		var campid = $('#campid').val();
		if(campid != ''){
			$.ajax({
				type:'GET',
				url:'/accommodation_book/select_building?campid='+campid,
				success:function(r){
					if(r.state == 1){
						$('#buildingid').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
	
	function select_room(){
		var buildingid = $('#buildingid').val();
		if(buildingid != ''){
			$.ajax({
				type:'GET',
				url:'/accommodation_book/select_room?buildingid='+buildingid,
				success:function(r){
					if(r.state == 1){
						$('#type').html(r.data);
					}
				},
				dataType:'json'
			
			
			});
		}else{
			return false;
		}
	}
</script>
<script>

$(function(){
	$('#myform').ajaxForm({
		beforeSend:function(){
			var d = dialog({
					id:'cucasdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
				dialog({id:'cucasdialog'}).close();
			if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.href='/<?=$puri?>/student/accommodation';
			}else if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'
	
	});
	
	

});
</script>
<?php $this->load->view('public/footer.php')?>
