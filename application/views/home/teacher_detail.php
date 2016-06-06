<?php $this->load->view('public/header.php')?>
<link href="<?=RES?>home/css/home.css" rel="stylesheet" type="text/css" />
<?php $this->load->view('public/nav.php')?>
<script src="<?=RES?>/home/js/plugins/raty/jquery.raty.js"></script>

<!--内容-->
<div class="width_1024 clearfix">
 <?=$bread_line?>
	<div class="f_l share-left">
		<img src="<?=!empty($teacher_basic->photo)?$teacher_basic->photo:''?>" width="155" height="188" alt=""/>
		<p class="pragraph-1">
		
		
		<?php if($puri == 'cn'){?>
		<?=!empty($teacher_basic->name)?$teacher_basic->name:''?>
		<?php }else{?>
		<?=!empty($teacher_name[$teacher_basic->teacherid])?$teacher_name[$teacher_basic->teacherid]:''?>
		<?php }?>
		
		</p>
		<p class="pragraph"><?=!empty($teacher_basic->professional)?$teacher_basic->professional:''?></p>
		<div class="star-teacher">
			<div class="pragraph"><?=lang('student_eval')?></div>
			<div class="starstar clearfix">
				<div id="scoreall_t" class="f_l"></div>
				<div> <?=!empty($avg_t)?number_format($avg_t,1):0?></div>
				<script type="text/javascript">
					$('#scoreall_t').raty({
						readOnly:  true,
						score:     <?=!empty($avg_t)?number_format($avg_t,1):0?>
					});
				</script>
			</div>
			<!--<div class="clear"><a href="#evaluate_page"><span class="about-school-main-news-see"><?=lang('look_detail')?></span></a></div>-->
		</div>
		<div class="teacher-detail-left-btn">
			<?php if(!empty($_SESSION['student']['userinfo'])){?> 
			<a href="#CUCASreview"><?=lang('doeval')?></a>
			<?php }else{?><a href="javascript:;" onclick="doeval()"><?=lang('doeval')?></a><?php }?>
		</div>
	</div>
	<div class="f_l share-right">
		<?php if(!empty($teacher_basic->content)){?>
		<div class="sharenow mb30">
			<p class="sharenow-title"><?=lang('teacher_info')?></p>
			<p class="pragraph">
			<?=!empty($teacher_basic->content)?$teacher_basic->content:''?>
			</p>
			
		</div>
		<?php }?>
		<?php if(!empty($teacher_jpkc)){?>
			<div class="sharenow mb30">
			<p class="sharenow-title"><?=lang('good_course')?></p>
			<!--add zmm-->
            <div class="wufenbox">
            	<div class="pic">
            <div class="wf_btnlf"></div>
            	<div class="wfwrap">
					<?php foreach($teacher_jpkc as $jk => $jv){?>
					<?php 
						if(!empty($jv['video'])){
						//弹视频
							$href = 'onclick="video_play(\''.$jv['video'].'\')"';
						
						}else{
							//弹图集
							$href = 'onclick="piclb('.$jv['id'].')"';
						}
					?>
                	<div class="sqlist" <?=$href?>>
                    	<div class="topvideo"><?php if(!empty($jv['image'])){?><img src="<?=$jv['image']?>" width="205" height="118"><?php }?></div>
                        <p><?=!empty($jv['title'])?$jv['title']:''?></p>
                        <span class="benbtn"></span>
                    </div>
					<?php  }?>
                  
                </div>
            	
                <div class="wf_btnrt"></div>
            </div>
            </div>
		</div>
		
		<?php }?>
		<?php if(!empty($teacher_jxsb)){?>
			<div class="sharenow mb30">
			<p class="sharenow-title"><?=lang('teaching')?></p>
			<div class="togglebox">
			<?php foreach($teacher_jxsb as $k => $vv){?>
				<div class="fortogg">
                    <p class="plist"><font><?=!empty($vv['title'])?$vv['title']:''?></font><span class="btntogg"></span></p>
                    <div class="toggcont"><p><?=!empty($vv['info'])?$vv['info']:''?> </p></div>
                </div>
			<?php }?>
            	
            </div>
		</div>
		<?php }?>
		
		
		<?php if(!empty($evaluate)){?>
        
		<div class="sharenow mt30 positionre"  id="evaluate_page">
			<p class="sharenow-title"><?=lang('student_eval')?></p>
			
			<div class="width_720 student-assessment">
				<ul>
					<?php
						if(!empty($evaluate)){
							foreach($evaluate as $key => $val){
					?>
						<li class="<?php if($key == 0){ echo 'mg_b_30-g';}?>">
						<div class="f_l nickname">
							<div class="hxk_xg"><img src="<?=!empty($val['photo'])?$val['photo']:'/resource/home/images/user/ures_pic22.png'?>"></div>
							<div class="namecountry">
								<p><?=!empty($val['name'])?$val['name']:''?></p>
								<p class="color-7">From <?=!empty($val['nationality'])?$nationality[$val['nationality']]:''?></p>
							</div>
							<div class="clear"></div>
						</div>
						<div class="assessment">
							<p class="mb15"><?=!empty($val['title'])?$val['title']:''?></p>
							<div class="huanhang">
							<div class="f_l pragraph"><?=lang('scoreall')?>:</div>
								<div class="f_l starstar">
									<div id="scoreall<?=$key?>" style="float:left;"></div>
									<script type="text/javascript">
										$('#scoreall<?=$key?>').raty({
											  readOnly:  true,
											  score:     <?=!empty($val['scoreall'])?number_format($val['scoreall'],1):0?>
										});
									</script>
									
									<div class="hxk_xgg" style="float:left;"><?=!empty($val['scoreall'])?number_format($val['scoreall'],1):0?></div>
								</div>
							</div>
							<div class="contactus-word-title-describe"><?=lang('evaluate_evaluate')?> :  <?=!empty($val['evaluate'])?$val['evaluate']:''?></div>
							<div class="contactus-word-title-describe"><?=lang('evaluate_content')?> :  <?=!empty($val['content'])?$val['content']:''?></div>
							<div class="mt20 color-7 f12"><?=!empty($val['createtime'])?date('Y/m/d',$val['createtime']):''?></div>
						</div>
					</li>
					<?php }}?>


				</ul>
			</div>
			<?php if(!empty($pagecount) && $pagecount > 1){?>
			<div class="assessment-next mt20">
				<a href="javascript:;" onclick="next(<?=$teacher_basic->id?>,<?=$page?>)" class="assessment-next-previous assessment-next-previous-next"></a>
				<a href="javascript:;" onclick="prev(<?=$teacher_basic->id?>,<?=$page?>)" class="assessment-next-previous assessment-next-previous-pre"></a>
			</div>
			<?php }?>
		</div>
		
		<?php }?>
		<?php if(!empty($_SESSION['student']['userinfo'])){?>
		<div class="sharenow mt30 positionre" id="CUCASreview">
			<p class="sharenow-title"><?=lang('i_evalutate')?></p>
			<div class="msgplace" >
												<span style="color:red;font-size: 14px;"><?=lang('item_require')?></span>
						<div class="yanzhengma mb10">
							<form action="/teacher/doevaluate" method="post" name="myform1" id="myform1" onsubmit="return check_null()">
								<p class="addp">
									<input type="text" id="title" name="title" placeholder="<?=lang('evaluate_title')?>"></p>
								<p class="addp">
									<textarea rows="5" name="evaluate" id="evaluate" style="width: 100%; height: 86px;"  placeholder="<?=lang('evaluate_evaluate')?>"></textarea>
									</p>

								<p class="addp">
									<textarea rows="5" name="content" id="content" style="width: 100%; height: 86px;"  placeholder="<?=lang('evaluate_content')?>"></textarea>
									</p>
			
								<div class="setdiv">
								<ul>
									<li style="width: 140px; float: left;">
										<div><?=lang('score1')?></div>
										<div class="starBox"><span id="score0" style="float:right;"></span></div>
										
									</li>
									<li style="width: 140px; float: left;">
										<div><?=lang('score2')?></div>
										<div class="starBox"><span id="score1" style="float:right;"></span></div>
									</li>
									<li style="width: 140px; float: left;">
										<div><?=lang('score3')?></div>
										<div class="starBox" ><span id="score2" style="float:right;"></span></div>
									</li>
									<li style="width: 140px; float: left;">
										<div><?=lang('score4')?></div>
										<div class="starBox"><span id="score3" style="float:right;"></span></div>
									</li>
									<input  type="hidden" name="teacherid" value="<?=$teacher_basic->id?>">

									<li class="mt10" style="float:right;">
										<input class="tijiao" type="submit"  value="<?=lang('submit')?>">
									</li>
								</ul>
								</div>
							</form>
						</div>
					</div>
		</div>
		<?php }?>
	</div>
</div>
<script type="text/javascript">
	var pagecount = <?=$pagecount?>;
	//下一页
	function next(teacherid,page){
		page ++;
		if(page <= pagecount){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/teacher/get_data?teacherid='+teacherid+'&flag=1&page='+page,
				success:function(r){
					if(r.state == 1){
						$('#evaluate_page').html();
						$('#evaluate_page').html(r.data);
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
	
	function prev(teacherid,page){
	
		page --;
		if(page >= 1){
			$.ajax({
				type:'GET',
				url:'/<?=$puri?>/teacher/get_data?teacherid='+teacherid+'&flag=2&page='+page,
				success:function(r){
					if(r.state == 1){
						$('#evaluate_page').html();
						$('#evaluate_page').html(r.data);
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
<script type="text/javascript">
<!--验空-->
	function check_null(){
		var title = $('#title').val();
		if(title == ''){
			var d = dialog({
					content: '<?=lang('evaluate_title_null')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				return false;
		}

		var evaluate = $('#evaluate').val();
		
		if(evaluate == ''){
			var d = dialog({
				content:'<?=lang('evaluate_evaluate_null')?>'
			});
			d.show();
			setTimeout(function(){
				d.close().remove();
			},2000);
			return false;
		}

		var content = $('#content').val();
		if(content == ''){
			var d = dialog({
				content:'<?=lang('evaluate_content_null')?>'
			});
			d.show();
			setTimeout(function(){
				d.close().remove();
			},2000);
			return false;
		}
	}

$(function(){
	$(".starBox").each( function(i,v){
		$(this).raty({
			//half:true,
			width:110,
			target:'#score'+i,
			targetKeep:true,
			hints: ['1', '2', '3', '4', '5'],
			score:0
			
		});
	});
	
	$('#myform1').ajaxForm({
		beforeSend:function(){
			 d = dialog({
			 		id:'cucasartdialog',
					content: '<img src="<?=RES?>home/images/public/loading.gif">'
				});
				d.showModal();
		},
		success:function(msg){
			dialog({id:'cucasartdialog'}).close();
			if(msg.state == 0){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				field = msg.data.field;

			}else if(msg.state == 1){
				var d = dialog({
					content: ''+msg.info+''
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
				window.location.reload();
				
			}
		},
		dataType:'json'
	
	});

});

	function doeval(){
		$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasartdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/<?=$puri?>/teacher/is_ts_login?backurl=<?=$backurl?>',
		success:function(r){
		dialog({id:'cucasartdialog'}).close();
			if (r.state == 1) {
				
				/*直接跳转到申请页面*/
				//$('.msgplace').toggle('slow');
				window.location.reload();
			}else{		
				var d = dialog({
						content:r.data,
						padding:0
				});
				d.showModal();
			}
		},
		dataType:'json'

		});
	}
</script>
<!--togg-->
<script type="text/javascript">
	$.fn.extend({
	"slideIf":function(value){
		value=$.extend({
			"speed":1000,
			"next":"",
			"prev":""
		},value)
		var $ul=$(this).find(".wfwrap");
		var $firstli=$ul.find('div.sqlist:first');
		var $movew=$firstli.outerWidth();
		//下一张
		function changNext(){
			$ul.find('div.sqlist:first').stop().animate({"marginLeft":-$movew},value.speed,function(){
					$(this).appendTo($ul);
					$(this).css({"marginLeft":0});												  
			})	
		}
		//上一张
		function changPrev(){
			$ul.find('div.sqlist:last').prependTo($ul);
			$ul.find('div.sqlist:first').css({"marginLeft":-$movew});
			$ul.find('div.sqlist:first').stop().animate({"marginLeft":0},value.speed)	
		}
		//点击右边按钮
		$('.'+value.next).click(function(){
			if(!$ul.find('div.sqlist:first').is(":animated")){
				changNext();
			}
		})
		//点击左边按钮
		$('.'+value.prev).click(function(){
			if(!$ul.find('div.sqlist:first').is(":animated")){
				changPrev();
			}
		})
	}
});

$(function(){
	
	$(".plist").click(function(){
		$(".toggcont").slideUp();
		var that = $(this);
		var next = that.next('.toggcont:hidden');
		$(".plist").css({borderBottom:"1px dotted #b5b5b5"});
		$(".plist").find("span.btntogg").css('background','url(/resource/home/images/home/togg3.jpg)');
		next.slideDown();
		if($.trim(next.html()) !== ''){
			that.css({borderBottom:"0px dotted #b5b5b5"});
			that.find("span.btntogg").css('background','url(/resource/home/images/home/togg2.jpg)');
		}
	});
});

$(function(){
	var w=$('.pic li:first').outerWidth(),
		len=$('.pic div.sqlist').length;
	//$('.pic').css({'width':len*w+'px'});
	$(".pic").slideIf({
		"speed":800,
		"next":"wf_btnlf",
		"prev":"wf_btnrt"
	})	
});

	/*图片播放*/
function piclb(majorid){
		if(majorid != null){
			$.ajax({
			beforeSend:function(){
				var d = dialog({
						id:'cucasdialog',
						content: '<img src="<?=RES?>home/images/public/loading.gif">'
					});
					d.showModal();
					
			},
		type:'GET',
		url:'/<?=$puri?>/teacher/get_images?jpkcid='+majorid,
		success:function(r){
			if (r.state == 1) {
				dialog({id:'cucasdialog'}).close();
				var d = dialog({
						content:r.data,
						padding:0,
						cancel:true
				});
				d.showModal();
			}else{
				var d = dialog({
					content: '<?=lang('no_img')?>'
				});
				d.show();
				setTimeout(function () {
					d.close().remove();
				}, 2000);
			}
		},
		dataType:'json'

	});
		}
	}

function video_play(url){
	dialog({
				id: 'test-dialog',
				cancel:true,
				url:'/<?=$puri?>/course/video?url='+encodeURIComponent(url),
				padding:0
			})
			.showModal();
			return false;
}
</script>

<?php $this->load->view('public/footer.php')?>
